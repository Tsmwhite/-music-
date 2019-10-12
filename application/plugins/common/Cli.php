<?php
/**
 * Created by PhpStorm.
 * User: chenlongmingob@outlook.com
 * Date: 2018/12/19
 * Time: 17:41
 */

namespace app\plugins\common;


use app\plugins\common\exception\CliException;
use app\plugins\common\exception\PluginInstallException;
use League\CLImate\CLImate;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use think\db\Query;

class Cli
{
    /** @var Filesystem $fs */
    protected static $fs;


    /** @var Query $query 数据库查询实例 */
    protected static $query;


    /**
     * 安装执行状态, 在各个阶段会执行 install 中相应的钩子函数
     * @var array
     * 说明:
     * init         初始化
     * lock         生成 lock.json
     * set_database 设置数据库
     * refresh_json 刷新 json 文件 (route.json, plugins.json)
     * complate     清除备份文件, 删除 lock.json, 安装完成
     */
    protected static $state = [
        'init'          =>  1,
        'lock'          =>  0,
        'set_database'  =>  0,
        'refresh_json'  =>  0,
        'complate'      =>  0
    ];

    /**
     * 状态描述文字
     * @var array
     */
    protected static $stateText = [
        'init'          =>  '初始化成功',
        'lock'          =>  '操作锁定',
        'set_database'  =>  '更新数据库完成',
        'refresh_json'  =>  '刷新插件列表和路由列表',
        'complate'      =>  '安装完成'
    ];

    /** @var CLImate $climate */
    public static $climate;


    /**
     * 初始化
     * @throws \think\Exception
     */
    public static function init ()
    {
        self::$climate = new CLImate();
        self::$climate->addArt(__DIR__ . DS . 'assets');

        $dbConfig = require_once __DIR__ . DS . '..' . DS . '..' . DS . 'database.php';
        self::$query = \think\Db::connect($dbConfig);
    }


    /**
     * 执行交互式命令行
     * @throws CliException
     * @throws \League\Flysystem\FileNotFoundException
     * @throws \think\Exception
     */
    public static function run()
    {
        try {
            self::$fs = new Filesystem(new Local(__DIR__ . DS . '..'));

            $action = self::$climate->input('请输入要进行的操作名称(输入 help 查看支持的操作): ')->prompt();
            switch ($action) {
                case 'help':
                    self::$climate->animation('help')->speed(500)->enterFrom('left');

                    self::run();
                    break;
                case 'install':

                    $input = self::$climate->input('请输入要安装的插件目录: ');

                    $dir = $input->prompt();
                    $plugnPath = __DIR__ . DS . '..' . DS . $dir . DS;


                    if (!is_dir($plugnPath)) {

                        throw new PluginInstallException('模块目录未找到');
                    } else {

                        if (self::$fs->has('lock.json'))
                            throw new PluginInstallException('当前有其他的安装操作正在进行, 请等待其他操作完成后再安装');

                        if (!self::$fs->has($dir . DS . 'plugin.json'))
                            throw new CliException('找不到 plugin.json 文件, 请确认该目录是一个合法的插件目录');

                        // 读取插件信息
                        $pluginInfo = json_decode(self::$fs->read($dir . DS . 'plugin.json'), true);
                        $pluginName = $pluginInfo['name'];
                        unset($pluginInfo['name']);

                        // 判断是否已安装
                        if (!empty($plugins[$pluginName])) {

                            throw new PluginInstallException('该插件已经安装, 需要重新安装的话请先删除该插件');
                        }

                        self::$climate->out('正在安装: ' . $pluginName);


                        self::update_lock('init', true);


                        try {

                            // 备份 plugins.json
                            $plugins = self::$fs->read('plugins.json');
                            self::$fs->write('plugins.bk.json', $plugins);
                            $plugins = json_decode($plugins, true);

                            // 备份 route.json
                            $routes = self::$fs->read('route.json');
                            self::$fs->write('route.bk.json', $routes);
                            $routes = json_decode($routes, true);

                            self::update_lock('lock');


                            // 锁定操作完成, 执行 locked 钩子
                            self::install_hook($pluginName, 'lock');


                            // 数据库操作, 交给插件的安装脚本去执行, 执行 set_database 钩子
                            self::install_hook($pluginName, 'set_database', self::$query);
                            self::update_lock('set_database');


                            // 重新生成路由文件
                            if (!empty($pluginInfo['route'])) {
                                foreach ($pluginInfo['route'] as $k => $url) {

                                    // 路由定义冲突, 提示处理冲突
                                    if (!empty($routes[$url])) {

                                        $r = self::select('路由定义冲突, 请选择冲突解决方式', [
                                            '1' => "${url} => ${routes[$url]} (保留当前)",
                                            '2' => "${url} => ${pluginName} (覆盖当前)"
                                        ]);

                                        if ($r == '2') {
                                            $routes[$url] = $pluginName;
                                        }
                                    } else {
                                        $routes[$url] = $pluginName;
                                    }
                                }

                                unset($pluginInfo['route']);

                                // 更新文件
                                self::$fs->put('route.json', json_encode($routes, JSON_PRETTY_PRINT));
                            }

                            $pluginInfo['dir'] = $dir;

                            // 重新生成插件列表文件
                            $plugins[$pluginName] = $pluginInfo;
                            self::$fs->put('plugins.json', json_encode($plugins, JSON_PRETTY_PRINT));


                            self::install_hook($pluginName, 'refresh_json');
                            self::update_lock('refresh_json');


                            self::install_hook($pluginName, 'complate');
                            self::update_lock('complate');

                            // 删除备份, 删除锁, 完成安装
                            self::safeDeleteFiles(['plugins.bk.json', 'route.bk.json', 'lock.json']);
                        } catch (\Exception $e) {
                            throw new PluginInstallException($e->getMessage(), $dir);
                        }
                    }

                    self::run();
                    break;
                case "exit":
                    exit;
                case "uninstall":
                    $dir = self::$climate->input('请输入要卸载的插件目录: ')->prompt();
                    self::uninstall($dir);

                    self::run();
                    break;
                default:
                    throw new CliException('当前不支持该操作');

            }
        } catch (PluginInstallException $e) {
            $msg = $e->getMessage() ? ("[ERROR] " . $e->getMessage()) : "安装失败, 未知的异常";
            self::$climate->red($msg);
            if ($e->pluginPath) {
                self::$climate->out('尝试回滚安装流程');
                self::rollback($e->pluginPath);
            }
        } catch (\Exception $e) {

            $msg = $e->getMessage() ? ("[ERROR] " . $e->getMessage()) : "未知的异常";
            self::$climate->red($msg);
            self::run();
        }
    }


    /**
     * 执行安装流程的钩子
     * @param $path
     * @param $hook
     * @param null $args
     */
    public static function install_hook ($path, $hook, $args = null)
    {
        $class = "\\app\\plugins\\${path}\\Install";

        if (class_exists($class) && method_exists($class, $hook)) {

            $class::$hook($args);
        }
    }


    public static function uninstall_hook ($path, $hook, $args = null)
    {
        $class = "\\app\\plugins\\${path}\\Uninstall";

        if (class_exists($class) && method_exists($class, $hook)) {

            $class::$hook($args);
        }
    }


    /**
     * 更新安装流程状态锁
     * @param $hook
     * @param bool $write
     * @throws \League\Flysystem\FileExistsException
     * @throws \League\Flysystem\FileNotFoundException
     */
    public static function update_lock ($hook, $write = false)
    {
        self::$state[$hook] = 1;
        $content = json_encode(self::$state, JSON_PRETTY_PRINT);

        if ($write) {
            self::$fs->write('lock.json', $content);
        } else {
            self::$fs->update('lock.json', $content);
        }

        if (!empty(self::$stateText[$hook])) {
            self::$climate->out(self::$stateText[$hook]);
        }
    }


    /**
     * 生成一个选择对话
     * @param $text     string      内容
     * @param $options  array       选择项
     * @return mixed
     */
    public static function select ($text, $options)
    {
        $tips = [];
        $optionText = "\n";

        foreach ($options as $key => $option) {
            $optionText .= ($key . '. ' . $option . "\n");
            $tips[] = $key;
        }

        $content = $text . '(输入' . implode(',', $tips) . '): ' . $optionText;

        $rs = self::$climate->input($content)->prompt();

        if (!in_array($rs, $tips)) {
            self::$climate->red('输入非法, 请重新输入你的选择');
            return self::select($text, $options);
        }

        return $rs;
    }


    /**
     * 卸载插件
     * @param string $path 插件目录
     * @throws \Exception
     */
    public static function uninstall ($path)
    {
        if (self::$fs->has('lock.json')) {
            throw new CliException('当前有其他操作正在进行, 请等待其他操作完成后再试');
        }
        if (!self::$fs->has($path . DS . 'plugin.json')) {
            throw new CliException('找不到 plugin.json 文件');
        }

        $state = [
            'init'  =>  1,
            'set_database' => 0,
            'complate' => 0
        ];

        self::$fs->write('lock.json', $state);


        self::uninstall_hook($path, 'set_database', self::$query);

        
        $pluginInfo = json_decode(self::$fs->read($path . DS . 'plugin.json'), true);

        // 删除 plugins 中对应的内容
        $plugins = json_decode(self::$fs->read('plugins.json'), true);
        $route = [];
        foreach ($plugins as $plugin => $val) {
            if ($plugin === $pluginInfo['name']) {
                // 删除 对应插件
                unset($plugins[$plugin]);
            } else {

                // route.json 中的内容
                $dir = $val['dir'];

                if (!self::$fs->has($dir . DS . 'plugin.json'))
                    throw new CliException("${dir}/plugin.json 文件不存在");
                $pInfo = json_decode(self::$fs->read($dir . DS . 'plugin.json'));

                // 提示用户处理冲突的 路由定义
                if (!empty($pInfo['route'])) {
                    foreach ($pInfo['route'] as $url) {
                        if (!empty($route[$url])) {
                            $r = self::select('路由定义冲突, 请选择冲突解决方式', [
                                '1' => "${url} => ${route[$url]}",
                                '2' => "${url} => ${pInfo['name']}"
                            ]);

                            if ($r == '2') {
                                $route[$url] = $pInfo['name'];
                            }
                        } else {
                            $route[$url] = $pInfo['name'];
                        }
                    }
                }
            }
        }

        // 写入 plugins.json 和 route.json
        self::$fs->put('plugins.json', json_encode($plugins, JSON_PRETTY_PRINT));
        self::$fs->put('route.json', json_encode($route, JSON_PRETTY_PRINT));


        self::uninstall_hook($path, 'complate');

        self::safeDeleteFiles('lock.json');
        self::$climate->out('卸载成功');

        exit;
    }


    /**
     * 回滚安装
     * @param $path
     * @throws CliException
     * @throws \League\Flysystem\FileNotFoundException
     */
    public static function rollback ($path)
    {
        if (!self::$fs->has($path . DS . 'plugin.json')) {
            throw new CliException('找不到 plugin.json 文件');
        }
        $pluginInfo = json_decode(self::$fs->read($path . DS . 'plugin.json'), true);


        // 执行用户自定义的回滚操作
        self::install_hook($path, 'rollback', self::$query);


        // 删除 plugins 中对应的内容
        $plugins = json_decode(self::$fs->read('plugins.json'), true);
        $route = [];
        foreach ($plugins as $plugin => $val) {
            if ($plugin === $pluginInfo['name']) {
                // 删除 对应插件
                unset($plugins[$plugin]);
            } else {

                // route.json 中的内容
                $dir = $val['dir'];

                if (!self::$fs->has($dir . DS . 'plugin.json'))
                    throw new CliException("${dir}/plugin.json 文件不存在");
                $pInfo = json_decode(self::$fs->read($dir . DS . 'plugin.json'));

                // 提示用户处理冲突的 路由定义
                if (!empty($pInfo['route'])) {
                    foreach ($pInfo['route'] as $url) {
                        if (!empty($route[$url])) {
                            $r = self::select('路由定义冲突, 请选择冲突解决方式', [
                                '1' => "${url} => ${route[$url]}",
                                '2' => "${url} => ${pInfo['name']}"
                            ]);

                            if ($r == '2') {
                                $route[$url] = $pInfo['name'];
                            }
                        } else {
                            $route[$url] = $pInfo['name'];
                        }
                    }
                }
            }
        }


        // 写入 plugins.json 和 route.json
        self::$fs->put('plugins.json', json_encode($plugins, JSON_PRETTY_PRINT));
        self::$fs->put('route.json', json_encode($route, JSON_PRETTY_PRINT));


        // 清除安装缓存和锁
        self::safeDeleteFiles(['lock.json', 'plugins.bk.json', 'route.bk.json']);

        self::$climate->out('自动回滚成功');
        exit;
    }


    /**
     * 安全删除文件
     * @param $path string|array 文件路径或多个路径的数组
     * @throws \League\Flysystem\FileNotFoundException
     */
    public static function safeDeleteFiles ($path)
    {
        if (is_array($path)) {
            foreach ($path as $p) {
                if (self::$fs->has($p)) {
                    self::$fs->delete($p);
                }
            }
        } else {
            if (self::$fs->has($path)) {
                self::$fs->delete($path);
            }
        }
    }
}
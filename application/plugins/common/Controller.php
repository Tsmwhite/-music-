<?php
/**
 * Created by PhpStorm.
 * User: chenlongmingob@outlook.com
 * Date: 2018/12/18
 * Time: 17:41
 */

namespace app\plugins\common;


use app\common\ViewModel;
use think\Config;
use think\Request;

class Controller extends \think\Controller
{
    /**
     * 插件名
     * @var string $pluginName
     */
    protected $pluginName;

    /**
     * 视图模型
     * @var ViewModel $vm
     */
    protected $vm;


    public function __construct($pluginName, $vm, Request $request = null)
    {
        $this->pluginName = $pluginName;
        if (is_string($this->vm) && class_exists($this->vm)) {
            $this->vm = new $this->vm;
        } else {
            $this->vm = $vm;
        }
        parent::__construct($request);
    }

    public function fetch($template = '', $vars = [], $replace = [], $config = [])
    {
        $config = Config::get('template');

        // 是否为一个模板文件
        $arr = explode('.', $template);
        if (array_pop($arr) !== ($config['view_suffix'] ?? 'html')) {

            if (!$template) {
                $template = $this->request->action();
            }

            // 将模板转换成路径
            $template = APP_PATH . 'plugins' . DS . $this->pluginName . DS . $this->request->module() . DS
                . ($config['view_path'] ? $config['view_path'] : 'view') . DS . strtolower($this->request->controller()) . DS . $this->request->action()
                . '.' . ($config['view_suffix'] ?? 'html');
        }
        return parent::fetch($template, $vars, $replace, $config);
    }
}
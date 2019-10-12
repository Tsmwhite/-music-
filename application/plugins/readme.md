### 插件说明
插件一般用于 **解决方案** 的拓展, 开发者能够快速的在原有功能的基础上拓展或增强原有功能


#### 根目录文件说明:

1. `*.lock.json` 相关操作的锁, 防止操作重复执行, 该文件存在时表示对应的操作正在进行, 用于操作失败时恢复, 操作异常时可能需要手动删除该文件
2. `plugins.json` 当前已安装的插件
3. `route.json` 操作路由文件, 安装插件成功后生成的记录方法路由的文件, 不需要每次请求时重新寻找对应的控制器和操作


* install.lock.json: 安装插件时生成的锁文件, 记录正在安装的插件信息, 相关的操作目录
* plugins.bk.json: 安装插件成功后生成新的 `plugins.json` 文件时的备份
* route.bk.json: 安装插件成功后重新生成 `route.json` 文件时的备份

```
--plugins                   插件根目录
  |--common                 插件公共目录
  |  |--traits              插件公共 traits 目录
  |  |  |--Add.php          插件视图模型对应的 Add 方法, 按需引用
  |  |  |--Index.php        插件视图模型对应的 Index 方法, 按需引用
  |  |  |--Update.php       插件视图模型对应的 Update 方法, 按需引用
  |  |  |--Delete.php       插件视图模型对应的 Delete 方法, 按需引用
  |  |--assets              命令行资源
  |  |--exception           异常类
  |  |--ApiController.php   插件公共 api 控制器, 所有插件的 API 控制器需要继承该类
  |  |--Controller.php      插件公共控制器, 所有插件控制器(非 API 控制器)需要继承该类
  |  |--Install.php         插件安装类, 安装脚本需要继承该类
  |  |--Uninstall.php       插件卸载类, 卸载脚本需要继承该类
  |  |--Cli.php             命令行类
  |--plugin01               插件 1 目录
  |  |--admin               插件 1 中 admin 模块目录
  |  |  |--controller       插件 1 中 admin 模块的控制器目录
  |  |  |--view             插件 1 中 admin 模块的视图目录
  |  |  |--viewModel        插件 1 中 admin 模块的视图模型目录
  |  |  ...
  |  |--api                 插件 1 中 api 模块目录
  |  |  |--controller       插件 1 中 api 模块的控制器目录
  |  |  ...
  |  |--module01            插件 1 中其他模块目录
  |  |--Install.php         安装脚本
  |  |--Uninstall.php       卸载脚本
  |  |--plugin.json         插件描述文件
  |  ...
  |--plugin02               插件 2 目录
  |  ...
  |--plugins.json           已安装的插件说明文件
  |--route.json             静态路由信息
  |--plugin-cli.php         命令行工具
  ...
```

#### `plugin.json` (不是`plugins`目录下的`plugins.json`)说明

* name: 插件名
* version: 版本
* description: 插件描述
* author: 作者
* email: 邮箱
* homePage: 主页
* route: 需要绑定的链接(数组)

例如: 
```json
{
  "name": "hello",
  "version": "0.0.1",
  "description": "",
  "author": "",
  "email": "",
  "homePage": "",
  "route": [
    "admin/hello/index",
    "admin/hello/add",
    "admin/hello/update",
    "admin/hello/delete"
  ]
}
```

#### `Install.php` 文件说明:
插件对应的安装脚本, 在对应插件的目录下, 允许用户自定义安装流程, 需要实现 `\app\plugins\common\Install` 接口, 对应的方法将在对应的阶段执行
`lock`: 操作锁生成之后, `set_database`: 数据库操作, `refresh_json`: 重新生成了 json 文件后, `complate`: 操作完成后, `rollback`: 安装出错回滚过程中

例如:

```php
class Install implements \app\plugins\common\Install
{
    protected static $time;

    public static function lock()
    {
        self::$time = time();
        Cli::$climate->out('lock.json 已生成');
    }

    public static function refresh_json()
    {
        Cli::$climate->out('重新生成 plugins.json, route.json');
    }

    /**
     * @param Query $query
     * @throws \think\db\exception\BindParamException
     * @throws \think\exception\PDOException
     */
    public static function set_database($query)
    {
        $query->execute('DROP TABLE IF EXISTS `hello`;');
        $query->execute('CREATE TABLE `hello`  (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `uuid` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
          `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
          `update_time` datetime(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0),
          PRIMARY KEY (`id`) USING BTREE
        ) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;');
        $query->execute('INSERT INTO `hello` VALUES (1, \'123\', \'1234\', \'2018-12-19 16:17:23\');');

        Cli::$climate->out('创建数据库成功');
    }


    public static function complate()
    {
        $long = (time() - self::$time) / 1000;
        Cli::$climate->out("安装成功, 用时 ${long}s.");
    }

    public static function rollBack($query)
    {
    }
}
```


#### `Uninstall.php` 文件说明:
插件对应的卸载脚本, 在对应插件目录下, 允许用户自定义卸载流程, 需要实现 `\app\plugins\common\Uninstall` 接口
`set_database` 方法用于更新数据库操作, `complate` 方法在完成后执行

例如: 
```php
class Uninstall implements \app\plugins\common\Uninstall
{
    public static function set_database($query)
    {
        $query->execute('DROP TABLE IF EXISTS `hello`;');
        Cli::$climate->out('删除旧表');
    }

    public static function complate()
    {
    }
}
```
<?php
/**
 * Created by PhpStorm.
 * User: chenlongmingob@outlook.com
 * Date: 2018/12/18
 * Time: 19:02
 */

namespace app\api\controller;


use app\ApiController;
use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local;
use think\exception\HttpException;

class None extends ApiController
{
    public function _empty ($name)
    {
        // 检查是否有匹配的插件
        $fs = new Filesystem(new Local(APP_PATH . DS . 'plugins'));
        $route = json_decode($fs->read('route.json'), true);
        if ($route) {
            foreach ($route as $url => $pluginName) {
                if (strtolower($this->request->path()) === strtolower($url)) {
                    $module = $this->request->module();
                    $controller = $this->request->controller();

                    $class = "\\app\\plugins\\${pluginName}\\${module}\\controller\\${name}";


                    // 如果匹配插件定义的 url 返回插件的页面
                    exit((new $class($pluginName, null, $this))->$name());
                }
            }
        }

        throw new HttpException(404, '无法访问当前页面');
    }
}
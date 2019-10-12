<?php
/**
 * Created by PhpStorm.
 * User: chenlongmingob@outlook.com
 * Date: 2018/12/18
 * Time: 19:03
 */

namespace app\admin\controller;


use app\Controller;
use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local;
use think\exception\HttpException;
use think\Loader;

class None extends Controller
{
    public function add($todo = null)
    {
        return $this->_empty('add');
    }

    public function update($todo = null)
    {
        return $this->_empty('update');
    }

    public function index()
    {
        return $this->_empty('index');
    }

    public function delete()
    {
        return $this->_empty('delete');
    }

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
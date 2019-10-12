<?php
/**
 * Created by PhpStorm.
 * User: chenlongmingob@outlook.com
 * Date: 2018/11/5
 * Time: 13:30
 */

namespace app\common\component;


use think\Config;
use think\View;

/**
 * 基础视图组件
 * Class Component
 * @package app\common\component
 */
abstract class Component
{
    /**
     * 获取当前模板的路径
     * @return mixed
     */
    protected static function getTemplate ()
    {
        $config = Config::get('component');
        $classArray = explode('\\', get_called_class());
        $class = end($classArray);
        $template = $config['path'] . $class . '/' . $class . $config['suffix'];
        return $template;
    }


    /**
     * 获取当前组件渲染后的内容
     * @param $data     mixed       原始数据
     * @return mixed
     */
    public static function getContent($data = null)
    {
        return View::instance()->assign($data)->fetch(self::getTemplate());
    }
}
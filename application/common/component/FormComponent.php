<?php
/**
 * Created by PhpStorm.
 * User: chenlongmingob@outlook.com
 * Date: 2018/11/5
 * Time: 13:41
 */

namespace app\common\component;


/**
 * 表单h视图组件
 * Class FormComponent
 * @package app\common\component
 */
abstract class FormComponent extends Component
{
    /**
     * 获取表单返回值
     * @param $value        mixed       表单返回值
     * @param $key          string      字段名
     * @param $data         array       完整的表单数组
     * @return mixed
     */
    public static function getParam ($value, $key = null, &$data = null)
    {
        if ($key !== null && $data !== null) $data[$key] = $value;
        return $value;
    }
}
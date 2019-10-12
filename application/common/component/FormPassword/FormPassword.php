<?php
/**
 * Created by PhpStorm.
 * User: chenlongmingob@outlook.com
 * Date: 2018/11/5
 * Time: 16:53
 */

namespace app\common\component\FormPassword;


use app\common\component\FormComponent;
use app\common\model\User;

class FormPassword extends FormComponent
{
    public static function getParam($value, $key = null, &$data = null)
    {
        $value = !empty($value) ? static::encode($value): null;
        if ($key && $data && $value !== null) {
            $data[$key] = $value;
        }
        return $value;
    }


    public static function encode ($value)
    {
        return md5($value);
    }
}
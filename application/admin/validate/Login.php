<?php
/**
 * Created by PhpStorm.
 * User: chenlongmingob@outlook.com
 * Date: 2018/11/15
 * Time: 11:00
 */

namespace app\admin\validate;


use validate\Validate;

class Login extends Validate
{
    protected $rule = [
        'username' => 'require',
        'password' => 'require',
        'captcha'  => 'require|captcha'
    ];
}
<?php
/**
 * Created by PhpStorm.
 * User: chenlongmingob@outlook.com
 * Date: 2018-10-23
 * Time: 15:20
 */

namespace app\common\model;


class User extends Model
{
    protected $table = 'admin';
    /**
     * 用户密码加密方式
     * @param $pwd      string      待加密字符串
     * @return string
     */
    public static function pwdEncode ($pwd)
    {
        return md5($pwd);
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: chenlongmingob@outlook.com
 * Date: 2018-10-23
 * Time: 15:21
 */

namespace app\common\model;


class Role extends Model
{
    public static function getUserRole ($uid, $field = null)
    {
        return (new static())->join('admin_role', 'admin_role.rid=role.id', 'left')
            ->where('admin_role.uid', 'eq', $uid)
            ->field($field ? $field : 'role.*')
            ->find();
    }
}
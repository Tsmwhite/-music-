<?php
/**
 * Created by PhpStorm.
 * User: chenlongmingob@outlook.com
 * Date: 2018/12/19
 * Time: 19:44
 */

namespace app\plugins\common;


use think\db\Query;

interface Uninstall
{
    /**
     * 设置数据库
     * @param Query $query
     */
    public static function set_database ($query);

    public static function complate ();
}
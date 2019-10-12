<?php
/**
 * Created by PhpStorm.
 * User: chenlongmingob@outlook.com
 * Date: 2018/12/20
 * Time: 13:48
 */

namespace app\plugins\hello;


use app\plugins\common\Cli;

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
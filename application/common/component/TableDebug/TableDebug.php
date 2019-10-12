<?php
/**
 * Created by PhpStorm.
 * User: chenlongmingob@outlook.com
 * Date: 2018/12/13
 * Time: 11:55
 */

namespace app\common\component\TableDebug;


use app\common\component\Component;

class TableDebug extends Component
{
    public static function getContent($data = null)
    {
        return "<script> console.log(" . json_encode($data) . ");</script>";
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: chenlongmingob@outlook.com
 * Date: 2019/5/11
 * Time: 12:21
 */

namespace app\common\component\TableDate;


use app\common\component\Component;

class TableDate extends Component
{
    public static function getContent ($data = null)
    {
        return strtotime($data['value']);
    }
}
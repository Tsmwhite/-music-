<?php
/**
 * Created by PhpStorm.
 * User: chenlongmingob@outlook.com
 * Date: 2019/5/11
 * Time: 12:21
 */

namespace app\common\component\TableTimestamp;


use app\common\component\Component;

class TableTimestamp extends Component
{
    public static function getContent ($data = null)
    {
        return date('Y-m-d H:i:s', $data['value']);
    }
}
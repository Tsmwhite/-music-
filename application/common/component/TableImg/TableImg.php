<?php
/**
 * Created by PhpStorm.
 * User: chenlongmingob@outlook.com
 * Date: 2018/11/5
 * Time: 14:24
 */

namespace app\common\component\TableImg;


use app\common\component\Component;

class TableImg extends Component
{
    public static function getContent($data = null)
    {
        return "<img src=\"${data['value']}\" data-img class=\"img-thumbnail\">";
    }
}
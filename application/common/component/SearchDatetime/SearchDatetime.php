<?php
/**
 * Created by PhpStorm.
 * User: chenlongmingob@outlook.com
 * Date: 2019/2/15
 * Time: 14:17
 */

namespace app\common\component\SearchDatetime;


use app\common\component\SearchComponent;

class SearchDatetime extends SearchComponent
{
    public static function search($field, &$params, &$query)
    {
        $datetimeArray = explode(' - ', $params[$field]);
        $query->where($field, 'between time', $datetimeArray);
    }
}
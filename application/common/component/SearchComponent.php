<?php
/**
 * Created by PhpStorm.
 * User: chenlongmingob@outlook.com
 * Date: 2018/12/4
 * Time: 14:00
 */

namespace app\common\component;


use think\db\Query;

abstract class SearchComponent extends Component
{
    /**
     * 生成查询条件
     * @param $field    string      表单字段
     * @param $params   array       查询参数
     * @param $query    Query       Query 实例
     */
    public static function search ($field, &$params, &$query)
    {
        $query->where($field, 'eq', $params[$field]);
    }
}
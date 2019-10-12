<?php
/**
 * Created by PhpStorm.
 * User: chenlongmingob@outlook.com
 * Date: 2019/1/19
 * Time: 14:21
 */

namespace app\common\component\SearchSelector;


use app\common\component\SearchComponent;
use think\Db;

class SearchSelector extends SearchComponent
{
    public static function search($field, &$params, &$query)
    {
        if ($params[$field] !== '__unset') {
            parent::search($field, $params, $query);
        }
    }


    /**
     * 当参数 list 不为空时, 将使用 list 中的数据,
     * 当 list 为空时, 将通过获取数据库的数据来完成
     * @param null $data
     * data => $data['data']
     * data.field   需要展示的字段 必填
     * data.value   作为值得字段 必填
     * data.list    作为选择项展示的数组 [['key1' => 'val1', 'key2' => 'val2'],...]
     * data.table   表名
     * data.join    join规则
     * data.where   where规则
     * data.format  显示格式, 如: "hello *field1*" -> "hello value1"
     * @return mixed
     * @throws \Exception
     */
    public static function getContent($data = null)
    {
        $data['data'] = $data['options'];
        if (empty($data['data']['list'])) {
            $db = Db::name($data['data']['table'])
                ->field($data['data']['value']);

            $db->field($data['data']['field']);


            if (!empty($data['data']['join'])) $db->join($data['data']['join']);
            if (!empty($data['data']['where'])) $db->where($data['data']['where']);

            $list = $db->select();


            // 自定义了字段显示的内容
            if (!empty($data['data']['format'])) {
                $data['list'] = [];
                foreach ($list as $item) {
                    $item['*formated'] = static::parseField($item, $data['data']['format'], $data['data']['field']);
                    $data['list'][] = $item;
                }

                $data['data']['field'] = '*formated';
            } else {
                $data['list'] = $list;
            }
        } else {
            $data['list'] = $data['data']['list'];
        }
        return parent::getContent($data);
    }


    /**
     * 解析自定义显示的模板
     * @param $row      array
     * @param $temp     string
     * @param $fields   array|string
     * @return mixed
     */
    protected static function parseField ($row, $temp, $fields)
    {
        if (is_string($fields)) {
            $fields = explode(',', $fields);
        }

        foreach ($fields as $field) {
            $field = trim($field);
            $temp = str_replace("*${field}*", $row[$field], $temp);
        }

        return $temp;
    }
}
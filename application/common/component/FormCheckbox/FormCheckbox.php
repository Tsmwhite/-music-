<?php
/**
 * Created by PhpStorm.
 * User: chenlongmingob@outlook.com
 * Date: 2018/11/14
 * Time: 11:10
 */

namespace app\common\component\FormCheckbox;


use app\common\component\FormComponent;
use think\Db;

class FormCheckbox extends FormComponent
{
    public static function getParam($value, $key = null, &$data = null)
    {
        $value = implode(',', $value);
        return parent::getParam($value, $key, $data);
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
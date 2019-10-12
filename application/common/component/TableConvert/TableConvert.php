<?php
/**
 * Created by PhpStorm.
 * User: chenlongmingob@outlook.com
 * Date: 2018/12/13
 * Time: 11:24
 */

namespace app\common\component\TableConvert;


use app\common\component\Component;

/**
 * 表格内容转换组件
 * 参数格式 ['value1' => 'name1', 'value2' => 'name2'],
 * 将数据库的 value1 转换成 name1 显示, value2 转换成 name2 显示
 * Class TableConvert
 * @package app\common\component\TableConvert
 */
class TableConvert extends Component
{
    public static function getContent($data = null)
    {
        return $data['data'][$data['value']] ?? '';
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: chenlongmingob@outlook.com
 * Date: 2018/10/22
 * Time: 15:03
 */

namespace app\common\model;


use think\Db;

class Model extends \think\Model
{

    protected $type = [
        'update_time'   =>  'string',
        'create_time'   =>  'string',
    ];


    /**
     * 添加了对表字段的处理
     * @param $join         string          关联表名
     * @param $condition    string|array    关联条件
     * @param $type         string          关联方式
     * @param $fieldMode    mixed           字段处理方式
     *  null    不处理
     *  "full"  保留全部字段，字段全部加上表名作为前缀
     *  Closure 用户自定义的处理发
     * @return $this|\think\Model
     */
    public function join($join, $condition = null, $type = 'INNER', $fieldMode = null)
    {
        parent::join($join, $condition, $type);

        // 关联字段处理
        if ($fieldMode === null) return $this;


        if (strtolower($fieldMode) === 'full') {

            // 字段重命名，加上表前缀
            $tableName = $this->getTable();
            foreach ($this->getTableFields() as $f1) {
                $this->field([$tableName . '.' . $f1 => $tableName . '_' . $f1]);
            }
            foreach (Db::name($join)->getTableFields() as $f2) {
                $this->field([$join . '.' . $f2 => $join . '_' . $f2]);
            }
        } elseif ($fieldMode instanceof \Closure) {

            // 用户自定义处理方式
            $this->field($fieldMode($this->getTableFields(), Db::name($join)->getTableFields()));
        }
        return $this;
    }
}
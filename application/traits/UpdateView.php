<?php
/**
 * Created by PhpStorm.
 * User: chenlongmingob@outlook.com
 * Date: 2018/11/5
 * Time: 18:18
 */

namespace app\traits;


use think\db\Query;
use think\Request;
use think\View;

/**
 * Trait UpdateView
 * @package app\traits
 * @method Query getDb()
 * @property Query db
 * @property Request request
 */
trait UpdateView
{
    /** @var array $updateFields update 页面和中展示的字段描述 */
    public $updateFields = [];



    public $updateValue = null;


    public function init_update ()
    {
        $this->parseFields($this->updateFields);
    }


    /**
     * 获取初始值
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getValue ()
    {
        if (empty($this->updateValue)) {
            $this->db->where($this->fullPk, 'eq', $this->request->param($this->pk));
            if (is_array($this->join)) {
                foreach ($this->join as $j) {

                    if (!empty($j[0])) {
                        $j[0] = $this->replaceTable($j[0]);
                    }

                    if (!empty($j[1])) {
                        $j[1] = $this->replaceTable($j[1]);
                    }


                    $this->db->join(...$j);
                }
            }

            // 字段处理
            $fields = [];
            foreach ($this->updateFields as $field => $prop) {
                if (!empty($prop['alias']))
                    $fields[$field] = $prop['alias'];
                else
                    $fields[] = $field;
            }

            $this->db->field($fields);


            $this->updateValue = $this->db->find();
        }

        return $this->updateValue;
    }


    /**
     * 构建编辑表单
     * @param null $fields
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function updateForm ($fields = null)
    {
        if (empty($fields)) $fields = $this->updateFields;

        $view = '';
        foreach ($fields as $field => $info) {
            $f = explode('.', $field)[1] ?? $field;

            // 视图变量
            $data = [
                'fieldName' => $this->fieldsName[$field],
                'field' => encode($field),
                'msg'  => $info[2] ?? null,
                'value' => $this->getValue()[$info['alias'] ?? $f] ?? null,
                'data' => $info[3] ?? null,
                'def' => $data['def'] ?? null
            ];

            if (is_file($info[0])) {
                $v = View::instance()->assign($data)->fetch($info[0]);
            } else {
                $v = getViewContent('form_' . $info[0] ?? 'input', $data);

                if ($v === null) {
                    if (empty($data['data'])) {
                        $data['data']['type'] = $info[0];
                    }
                    $v = getViewContent('form_input', $data);
                }
            }

            $view .= $this->fetchForm($v, $field, 'update', $this->getValue()[$info['alias'] ?? $f ?? $data['def'] ?? null]);

        }
        return $view;
    }


    /**
     * 将修改写入数据库
     * @param \Closure|null $filter     对即将写入数据库的数据进行筛选和处理
     * @param \Closure|null $update     自定意义更新数据的方法, 传入两个参数 #1 表单获取到的数据 #2 当前视图模型实例 #3 查询条件
     * @return int|mixed|string
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function update (\Closure $filter = null, \Closure $update = null) {
        $params = decodeArray($this->request->except(['action', $this->pk]));

        $data = $this->validate(
            $params,
            $this->updateFields,
            $this->fieldsName
        );

        if ($filter instanceof \Closure) {
            $data = $filter($data);
        }


        if ($update instanceof \Closure) {
            $this->getDb()->transaction(function () use ($data, $update) {
                $update($data, $this, [$this->pk => $this->request->param($this->pk)]);
            });

            return true;
        }

        return $this->getDb()
            ->where($this->pk ?? 'id', 'eq', $this->request->param($this->pk ?? 'id'))
            ->update($data);
    }



    public function updateView ()
    {
        return ['formGroup' => $this->updateForm()];
    }
}
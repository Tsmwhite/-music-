<?php
/**
 * Created by PhpStorm.
 * User: chenlongmingob@outlook.com
 * Date: 2018/11/5
 * Time: 18:18
 */

namespace app\traits;


use function PHPSTORM_META\map;
use think\db\Query;
use think\Request;
use think\View;


/**
 * Trait AddView
 * @package app\traits
 * @method Query getDb()
 * @property Request request
 */
trait AddView
{

    /**
     * @var array $addFields add 页面中展示的字段描述
     * 该属性为空的话将使用 $updateFields 的规则
     */
    public $addFields = null;


    public function init_addFields ()
    {
        if (empty($this->addFields)) $this->addFields = $this->updateFields;
        $this->parseFields($this->addFields);
    }

    /**
     * 渲染 add 模板的表单
     * @param null $fields
     * @return string
     * @throws \Exception
     */
    public function addForm ($fields = null)
    {
        if (empty($fields)) $fields = $this->addFields ?? $this->updateFields;

        $view = "";
        foreach ($fields as $field => $info) {
            $data = [
                'fieldName' => $this->fieldsName[$field],
                'field' => encode($field),
                'msg'  => $info[2] ?? null,
                'data' => $info[3] ?? null,
                'def' => $info['def'] ?? null
            ];

            if (is_file($info[0])) {
                $v = View::instance()->assign($data)->fetch($info[0]);
            } else {
                $v = getViewContent('form_' . $info[0] ?? 'input', $data);

                // 如果获取视图失败, 使用通用表单
                if ($v === null) {
                    if (empty($data['data'])) {
                        $data['data']['type'] = $info[0];
                    }
                    $v = getViewContent('form_input', $data);
                }
            }

            $view .= $this->fetchForm($v, $field, 'add', $info['def'] ?? null);
        }

        return $view;
    }


    /**
     * 添加到数据库的方法
     * @param \Closure $filter      对即将写入数据库的数据进行筛选和处理
     * @param \Closure $add         自定意义更新写入的方法, 传入1个参数 #1 表单获取到的数据 #2 当前视图模型实例
     * @return mixed
     */
    public function add (\Closure $filter = null, \Closure $add = null)
    {
        $params = decodeArray($this->request->except('action'));

        $data = $this->validate(
            $params,
            $this->addFields,
            $this->fieldsName
        );

        if ($filter instanceof \Closure) {
            $data = $filter($data);
        }

        if ($add instanceof \Closure) {
            $this->getDb()->transaction(function () use ($data, $add) {
                $add($data, $this);
            });

            return true;
        }

        return $this->getDb()->insert($data);
    }


    public function addView ()
    {
        return ['formGroup' => $this->addForm()];
    }
}
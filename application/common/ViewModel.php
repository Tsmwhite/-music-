<?php
/**
 * Created by PhpStorm.
 * User: chenlongmingob@outlook.com
 * Date: 2018-10-23
 * Time: 16:53
 */

namespace app\common;


use app\common\exception\FormValidateException;
use app\traits\AddView;
use app\traits\Export;
use app\traits\Import;
use app\traits\IndexView;
use app\traits\UpdateView;
use think\Config;
use think\Db;
use think\db\Query;
use think\Loader;
use validate\Validate;


/**
 * Class ViewModel
 * @package app\common
 * @property Query db
 * @method mixed filter($params)
 */
class ViewModel
{
    use IndexView,
        AddView,
        UpdateView,
        Export,
        Import;

    /** @var string $tableName 强制指定表名 */
    public $tableName = null;

    /** @var array $fields 字段名 */
    public $fieldsName = [];

    /** @var array $commonFields 通用字段名 */
    public $commonFields = [
        'id'            =>  'ID',
        'create_time'   =>  '创建时间',
        'update_time'   =>  '更新时间',
    ];



    /** @var Query $db */
    public $db = null;


    /** @var $pk string 默认主键 */
    public $pk = 'id';


    public $variables = [];


    public $fullPk = null;


    protected $request = null;


    public function __construct()
    {
        $this->request = request();
        if (empty($this->tableName)) {
            $tableName = explode('\\', get_called_class());
            $this->tableName = Loader::parseName(end($tableName));
        }
        if (empty($this->fullPk)) $this->fullPk = $this->tableName . '.' . $this->pk;

        $methods = getClassMethods($this);

        // 所有以 init_ 开头的方法将会在构造函数之后调用
        foreach ($methods as $method) {
            if (strpos($method, 'init_') === 0) {
                $this->$method();
            }
        }

        $this->db = $this->getDb();
    }



    public function init_fields ()
    {
        $this->parseFields($this->fieldsName);
    }


    protected function parseFields (array &$arr)
    {
        foreach ($arr as $field => $val) {
            $field = $this->replaceTable($this->parseField($field));
            $fields[$field] = $val;
        }

        if (!empty($fields)) {
            $arr = $fields;
        }
    }


    protected function parseField (string $field)
    {
        if (strpos($field, '.') === false) {
            $field = $this->tableName . '.' .  $field;
        }
        return $field;
    }

    protected function originalField ($field)
    {
        $tmp = explode('.', $field);
        return $tmp[1] ?? $tmp[0];
    }

    /**
     * 处理定义表格
     * @param $str
     * @return mixed
     */
    protected function replaceTable ($str)
    {
        $str = str_replace('$0', $this->tableName, $str);
        for ($i = 0; $i < count($this->variables); $i++) {
            $str = str_replace('$' . ($i + 1), $this->variables[$i], $str);
        }

        return $str;
    }


    /**
     * 获取对应的数据库模型
     * @return \think\db\Query
     */
    public function getDb ()
    {
        $table = Db::name($this->tableName);
        return $table;
    }


    /**
     * 返回自身实例
     * @param $model string
     * @return ViewModel
     */
    public static function instance ($model = null)
    {
        if (!empty($class)) {
            $className = $class;
        } elseif (empty($model)) {
            $className = get_called_class();
        } else {
            $module = \request()->module();
            $path = Config::get('viewModel')['path'];
            $className = "\\app\\${module}\\${path}\\${model}";
        }

        if (class_exists($className)) {
            return new $className();
        }

        return null;
    }


    /**
     * 前端参数(键名"_"替换成".")
     * @param $params
     * @return array
     */
    public function params ($params = null)
    {
        if (!$params) $params = $this->request->param();
        $ret = [];

        // 将 "_" 替换成 "."
        foreach ($params as $pk => $pv) {
            $k = explode('_', $pk);
            if (count($k) >= 2) {
                $key = $k[0] . '.' . $k[1];
                for ($i = 2; $i < count($k); $i++) {
                    $key .= "_${k[$i]}";
                }
            } else {
                $key = $pk;
            }

            $ret[$key] = $pv;
        }
        return $ret;
    }


    /**
     * 获取查询参数
     * @return array|mixed
     */
    public function getParam ()
    {
        $params = $this->request->except(Config::get('paginate.var_page'));
        if ($params) {
            foreach ($params as $k => $v) {
                if (!$v) {
                    unset($params[$k]);
                }
            }

            $params = decodeArray($params);
        }

        return $params;
    }


    /**
     * @param $db
     * @param $params
     */
    public function searchQuery (&$db, $params) {
        /** @var Query $db */
        $db->where(function (Query $query) use ($params) {
            foreach ($params as $key => $val) {
                $val = trim($val);
                if (!empty($this->search[$key][1])) {
                    switch ($this->search[$key][1]) {
                        case "like":
                            $query->where([$key => ['like', "%${val}%"]]);
                            break;
                        default:
                            $query->where([$key => $val]);
                            break;
                    }
                } else {
                    if (!empty($this->search[$key][0])) {
                        $component = getViewComponent('search_' . $this->search[$key][0]);
                        if ($component) {
                            $component::search($key, $params, $query);
                        } else {
                            $query->where([$key => $val]);
                        }
                    } else {
                        $query->where([$key => $val]);
                    }
                }
            }
        });
    }




    /**
     * 验证同时过滤掉非法字段
     * @param $params
     * @param $fields
     * @param $fieldNames
     * @return array
     * @throws FormValidateException
     */
    public function validate ($params, $fields, $fieldNames)
    {
        // 由于 tp 验证器在键名中包含特殊符号时验证会出错, 所以这里涉及的数组键名都编码一次
        $r_fields = encodeArray($fields);
        $fieldNames = encodeArray($fieldNames);


        // 数据预处理
        $data = [];
        foreach ($fields as $f => $i) {
            foreach ($params as $pk => $pv) {
                if ($f === $pk) {
                    $form = getViewComponent('form_' . $i[0]);

                    if (is_file($i[0])) {
                        $data[$pk] = $pv;
                    } else if ($form !== null && method_exists($form, 'getParam')) {
                        $form::getParam($pv, $pk, $data);
                    } else {
                        getViewComponent('form_input')::getParam($pv, $pk, $data);
                    }
                }
            }
        }


        // 生成验证规则
        $rules = [];
        foreach ($r_fields as $field => $info) {
            if (!empty($info[1])) {
                $rules[$field . '|' . $fieldNames[$field]] = $info[1];
            }
        }

        $rp = encodeArray($data);

        $validate = new Validate($rules);
        if (!$validate->check($rp)) {
            throw new FormValidateException($validate->getError());
        }


        return $data;
    }


    public function delete ()
    {
        $this->getDb()
            ->where($this->pk, 'in', explode(',', $this->request->param($this->pk)))
            ->delete();
    }


    /**
     * 表单构建流程
     * @param $content  string  表单渲染后的模板
     * @param $field    string  当前渲染的字段
     * @param $action   string  操作名称 (add, update)
     * @param $value    string  表单初始值
     * @return string
     */
    public function fetchForm ($content, $field, $action, $value = null) {
        return $content;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: chenlongmingob@outlook.com
 * Date: 2018/11/5
 * Time: 18:18
 */

namespace app\traits;

use app\common\component\TableButton\TableButton;
use think\Config;
use think\db\Query;
use think\Paginator;
use think\Request;
use think\View;


/**
 * Trait IndexView
 * @package app\traits
 * @property Query db
 * @property Request request
 *
 * 生成操作按钮, 如果该方法存在, 方法的返回值将会覆盖 buttons 属性
 * $row     array   当前行的表格数据
 * @method fetchButtons($row): array
 */
trait IndexView
{
    /** @var array $indexFields index 页面中展示的字段描述 */
    public $indexFields = [];

    /** @var array|string index 页面数据的排序规则 */
    public $order = null;

    /** @var array  */
    public $join = [];

    /** @var array 筛选按钮 */
    public $filters = [];

    /** @var $buttons array 操作按钮  */
    protected $buttons = [
        [
            'url'       =>  'update',
            'type'      =>  'info',
            'name'      =>  '编辑',
            'params'    =>  [
                '_PK_'  =>  null
            ]
        ],
        [
            'url'       =>  'delete',
            'type'      =>  'danger',
            'confirm'   =>  '确认要删除该项吗(该操作无法恢复)',
            'name'      =>  '删除',
            'params'    =>  [
                '_PK_'  =>  null
            ]
        ]
    ];

    /** @var $data mixed 查询数据缓存 */
    public $data = null;



    /** @var array $search 搜索字段 */
    public $search = [];


    /** @var bool $page 是否开启分页 */
    public $page = true;


    /**
     * 分级展示参数
     * 当 $tree 是一个有效数组时, 开启分级展示
     * @var array $tree
     * $tree['parent'] = '父级标识字段'
     * $tree['self'] = '自身标识字段'
     * @example
     public $tree = [
        'parent'        =>  'pid',
        'self'          =>  'id'
     ];
     */
    public $tree = null;


    /** @var bool $isTree 内部用于判断是否开启分级展示的依据 */
    private $isTree = false;


    /** field => table.field */
    public function init_indexFields ()
    {
        foreach ($this->indexFields as $indexField => $val) {
            if (is_string($val)) {
                $this->indexFields[$indexField] = [$val];
            }
        }

        foreach ($this->search as $searchField => $val) {
            if (is_string($val)) {
                $this->search[$searchField] = [$val];
            }
        }

        $this->parseFields($this->indexFields);
        $this->parseFields($this->search);

        if (is_array($this->tree) && !empty($this->tree['parent']) && !empty($this->tree['self'])) {
            $this->tree['parent_key'] = $this->replaceTable($this->parseField($this->tree['parent']));
            $this->tree['self_key'] = $this->replaceTable($this->parseField($this->tree['self']));

            $this->isTree = true;
            $this->page = false;
        }
    }


    /**
     * 获取 index 视图中的数据
     * @param $data array   使用自定义的数据
     * @return mixed|\think\Paginator
     * @throws \think\exception\DbException
     */
    public function getIndex ($data = null)
    {
        if (is_array($data)) $this->data = $data;
        if ($this->data) return $this->data;
        $db = $this->getIndexDb();


        // 是否开启分页
        if ($this->page) {
            $this->data = $db->paginate();
        } else {
            $this->data = $db->select();
        }

        if ($this->isTree) {
            $this->data = $this->formatTree($this->data);
        }

        return $this->data;
    }


    public function getIndexDb ()
    {
        $db = $this->db;

        // 处理关联
        if (is_array($this->join) && count($this->join) >= 1) {
            foreach ($this->join as $join) {

                if (!empty($join[0])) {
                    $join[0] = $this->replaceTable($join[0]);
                }

                if (!empty($join[1])) {
                    $join[1] = $this->replaceTable($join[1]);
                }

                $db->join(...$join);
            }
        }

        // 输出字段
        $fields = [];
        foreach ($this->indexFields as $field => $val) {
            if (empty($val['not_field'])) {
                if (!empty($val['alias']))
                    $fields[$field] = $val['alias'];
                else
                    $fields[] = $field;
            }
        }

        $db->field($fields);

        // 获取主键
        $db->field($this->fullPk);


        if ($this->isTree) {
            $db->field([$this->tree['parent_key'], $this->tree['self_key']]);
        }

        // 合并前端获取的查询条件和方法获取的查询条件
        $params = $this->getParam();

        // 判断如果存在 filter 方法, 就把 filter 方法传入到 where 中
        if (method_exists(get_called_class(), 'filter')) {
            $db->where($this->filter($params));
        }

        if ($this->order) {

            // 自定义排序规则
            $db->order($this->order);
        } else {

            // 默认排序规则
            $db->order($this->fullPk . ' desc');
        }

        $this->searchQuery($db, $params);

        return $db;
    }


    /**
     * 构建操作按钮
     * @param $pk           mixed       主键
     * @param $opt          array|null  需要展示的操作按钮
     * @return string
     * @throws \Exception
     */
    public function options ($pk, $opt = null)
    {
        $view = "";
        if (empty($opt) || !is_array($opt)) $opt = $this->buttons;
        foreach ($opt as $item) {

            // 生成查询条件
            if (!empty($item['params']) && is_array($item['params'])) {
                foreach ($item['params'] as $key => $val) {
                    switch ($key) {
                        case '_PK_':
                            unset($item['params'][$key]);
                            $item['params'][$this->pk] = $pk;
                            break;
                    }
                }
                $params = $item['params'];
            }

            if (!empty($item['formUrl']) && is_array($item['formUrl'])) {
                $item['formUrl'] = url($item['formUrl']['url'], $item['formUrl']['params']);
            }

            // 生成 url
            if (!empty($item['url']))
                $item['url'] = url($item['url'], $params ?? null);
            else
                $item['url'] = 'javascript: void(0);';


            $view .= TableButton::getContent(['data' => $item]);
        }
        return $view;
    }


    /**
     * 构建表头
     * @param null $indexFields
     * @param null $fields
     * @return string
     */
    public function tableHead ($indexFields = null, $fields = null)
    {
        if (empty($indexFields)) $indexFields = $this->indexFields;
        if (empty($fields)) $fields = array_merge($this->commonFields, $this->fieldsName);
        $view = "";
        foreach ($indexFields as $field => $val) {
            if ($val[0] !== '@hidden') {
                if (!empty($val['alias'])) {
                    $alias = $this->parseField($val['alias']);
                    $view .= "<th>${fields[$alias]}</th>";
                } else {
                    $f = $this->parseField($field);
                    $view .= "<th>${fields[$f]}</th>";
                }
            }
        }
        return $view;
    }


    /**
     * 构建表格内容
     * @param null $data
     * @param null $indexFields
     * @return string
     * @throws \Exception
     */
    public function tableBody ($data = null, $indexFields = null)
    {
        if (empty($data)) $data = $this->getIndex();
        $view = "";
        foreach ($data as $row) {
            $view .= $this->tableRow($row, $indexFields);
        }
        return $view;
    }


    /**
     * 构建行
     * @param $row
     * @param null $indexFields
     * @return string
     * @throws \Exception
     */
    public function tableRow ($row, $indexFields = null)
    {
        if (empty($indexFields)) $indexFields = $this->indexFields;

        if ($this->isTree) {
            $view = "<tr data-pk='" . $row[$this->pk] . "' data-pk-name='" . $this->pk . "' data-level='${row['$level']}' data-self='${row[$this->tree['self']]}' data-parent='${row[$this->tree['parent']]}'>";
        } else {
            $view = "<tr data-pk='" . $row[$this->pk] . "' data-pk-name='" . $this->pk . "'>";
        }
        foreach ($indexFields as $field => $val) {
            if ($val[0] !== '@hidden') {
                $fieldName = strpos($field, '.') === false ? $field : explode('.', $field)[1];
                if (!empty($val['alias'])) {
                    $fieldName = $val['alias'];
                }

                if (!isset($row[$fieldName])) {
                    $row[$fieldName] = null;
                }

                if (is_file($val[0])) {
                    $view .= '<td>' . View::instance()->assign([
                            'value'     =>  $row[$fieldName] ?? null,
                            'data'      =>  $val[1] ?? null,
                            'row'       =>  $row
                        ])->fetch($val[0]) . '</td>';
                } else if ($val[0] === 'text') {
                    $view .= "<td>${row[$fieldName]}</td>";
                } else {
                    $view .= sprintf("<td>%s</td>", getViewContent('table_' . $val[0], [
                        'value'     =>  $row[$fieldName] ?? null,
                        'data'      =>  $val[1] ?? null,
                        'row'       =>  $row
                    ]));
                }
            }
        }

        if (method_exists($this, 'fetchButtons')) {
            $view .= sprintf("<td>%s</td></tr>", $this->options($row[$this->pk], $this->fetchButtons($row)));
        } else {
            $view .= sprintf("<td>%s</td></tr>", $this->options($row[$this->pk]));
        }

        return $view;
    }

    /**
     * 构建搜索表单
     * @param $search
     * @return string
     * @throws \Exception
     */
    public function searchForm ($search = null)
    {
        if (!$search) $search = encodeArray($this->search);
        $view = "";
        $params = $this->params();
        $fieldName = encodeArray($this->fieldsName);
        foreach ($search as $key => $val) {
            if (is_file($val[0])) {
                $v = View::instance()->assign([
                    'value'     =>  $params[$key] ?? '',
                    'name'      =>  $fieldName[$key] ?? '',
                    'field'     =>  $key,
                    'options'   =>  $val[2] ?? null
                ])->fetch($val[0]);
            } else {
                $v = getViewContent('search_' . $val[0], [
                    'value'     =>  $params[$key] ?? '',
                    'name'      =>  $fieldName[$key] ?? '',
                    'field'     =>  $key,
                    'options'   =>  $val[2] ?? null
                ]);

                if (!$v) {
                    $v = getViewContent('search_input', [
                        'value'     =>  $params[$key] ?? '',
                        'name'      =>  $fieldName[$key] ?? '',
                        'type'      =>  strtolower($val[0]),
                        'field'     =>  $key,
                        'options'   =>  $val[2] ?? null
                    ]);
                }
            }

            $view .= $v;
        }
        return $view;
    }


    /**
     * 获取 Index 模板的变量
     * @return array
     * @throws \Exception
     */
    public function indexView ()
    {
        $variables = [
            'search' => $this->searchForm(),
            'tableHead' => $this->tableHead(),
            'tableBody' => $this->tableBody(),
            'page' => '',
            'tree' => $this->isTree,
            'showExportButton' => $this->showExportButton,
            'showImportForm' => $this->showImportButton
        ];

        if ($this->getIndex() instanceof Paginator) {
            $variables['page'] = $this->getIndex()->render();
            $variables['count'] = $this->getIndex()->total();
        } else {
            $variables['count'] = count($this->getIndex());
        }

        return $variables;
    }


    /**
     * 格式化数据
     * @param $data
     * @return mixed
     */
    protected function formatTree ($data)
    {
        foreach ($data as &$item) {
            if (!isset($item['$level'])) $item['$level'] = 0;
            $item['$level'] = $this->rowLevel($data, $item, $item['$level']);
        }


        $formatted = [];
        foreach ($data as $fi) {

            if ($fi['$level'] == 0) {
                array_push($formatted, $fi);
                $this->rowFormat($data, $fi, $formatted);
            }
        }

        return $formatted;
    }


    /**
     * 计算 level
     * @param $data
     * @param $item
     * @param $level
     * @return mixed
     */
    protected function rowLevel ($data, $item, $level)
    {
        if (!$item[$this->tree['parent']]) return $level;
        foreach ($data as $d) {
            if ($d[$this->tree['self']] === $item[$this->tree['parent']]) {
                $level++;
                $level = $this->rowLevel($data, $d, $level);
            }
        }

        return $level;
    }


    /**
     * 排序
     * @param $data
     * @param $i
     * @param $formatted
     */
    protected function rowFormat ($data, $i, &$formatted)
    {
        foreach ($data as $k => $item) {

            if ($i[$this->tree['self']]  == $item[$this->tree['parent']]) {
                array_push($formatted, $item);
                unset($data[$k]);
                $this->rowFormat($data, $item, $formatted);
            }
        }

        return;
    }
}
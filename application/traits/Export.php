<?php
/**
 * Created by PhpStorm.
 * User: chenlongmingob@outlook.com
 * Date: 2019/3/1
 * Time: 12:51
 */

namespace app\traits;


use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use think\db\Query;
use think\Request;

/**
 * Trait Export
 * @package app\traits
 * @property array $indexFields
 * @property array $fieldsName
 * @property Request $request
 * @property mixed $exportJoin 导出时的 join 规则, 未设置时默认使用 $join 属性 的规则
 * @method string parseField(string $original)
 * @method Query getDb()
 * @method string replaceTable(string $str)
 * @method string originalField(string $str)
 */
trait Export
{
    /** @var bool 是否显示导出按钮 */
    public $showExportButton = true;


    /** @var string 初始单元格 */
    public $exportStartCell = 'A1';


    /**
     * @var array 导出字段声明
     * @example 示例1: ['field1', 'field2', 'field3', ...] // 字符串数组
     * @example 示例2: ['field1,field1_name,convertMethod1', 'field2,field2_name,convertMethod2', ...] // 用","分割的字符串数组
     * @example 示例3: [['field1', 'field1_name', 'convertMenthod1'], ...] // 二维数组
     *
     * convertMethod 必须是静态方法, 参数:
     * #1 数据库获取到的对应字段值,
     * #2 数据库获取到的对应行的数组,
     * #3 对应的导出字段的参数数组,
     * #4 当前视图模型的实例
     */
    public $exportFields = [];


    /**
     * 初始化导出字段
     * 将 $exportFields 属性格式从 示例1 和 示例2 统一转换为 示例3 的格式, 并解析别名
     * @return void
     */
    private function init_exportField ()
    {
        $fields = $this->exportFields;
        if (!is_array($fields) || (is_array($fields) && count($fields) === 0) || is_array($fields[0])) {
            return;
        }

        foreach ($fields as $field) {
            $tmp = explode(',', $field);
            $tmp[0] = $this->replaceTable($this->parseField($tmp[0]));
            $formatted[] = $tmp;
        }

        if (!empty($formatted)) {
            foreach ($formatted as &$field) {
                $r = 0;
                foreach ($formatted as $_field) {
                    $r++;
                    if (
                        $_field[0] !== $field[0] &&
                        $this->originalField($_field[0]) === $this->originalField($field[0])
                    ) {
                        $field[3] = $field[0] . $r;

                    }
                }
            }

            $this->exportFields = $formatted;
        }
    }


    /**
     * 生成导出的表头
     * @return array
     */
    public function exportHead ()
    {
        foreach ($this->exportFields as $field) {
            $head[] = $field[1] ?? $this->fieldsName[$field[0]];
        }

        return $head ?? null;
    }


    /**
     * 格式化需要导出的数组
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function exportFormatter ()
    {
        $data = $this->exportDb()->select();
        foreach ($data as $row) {
            $formattedRow = [];
            foreach ($this->exportFields as $field) {
                $val = $row[$this->originalField($field[0])];


                if (!empty($field[2]) && method_exists(static::class, $field[2])) {
                    $val = call_user_func([static::class, $field[2]], $val, $row, $field);
                }

                $formattedRow[] = $val;
            }
            $formatted[] = $formattedRow;
        }

        return array_merge([$this->exportHead()], $formatted ?? []);
    }


    /**
     * @return Query
     */
    public function exportDb ()
    {
        $db = $this->getDb();

        foreach ($this->exportFields as $field) {
            if (!empty($field[3])) {
                $db->field($field[0] . ' as ' . $field[3]);
            } else {
                $db->field($field[0]);
            }
        }

        if (property_exists(static::class, 'exportJoin')) {
            $joins = $this->exportJoin;
        } else {
            $joins = $this->join;
        }

        foreach ($joins ?? [] as $join) {
            if (!empty($join[0])) {
                $join[0] = $this->replaceTable($join[0]);
            }

            if (!empty($join[1])) {
                $join[1] = $this->replaceTable($join[1]);
            }

            $db->join(...$join);
        }

        $this->searchQuery($db, $this->getParam());

        return $db;
    }


    /**
     * 导出到 excel
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function export ()
    {
        $sheet = new Spreadsheet();

        $sheet->setActiveSheetIndex(0)
            ->setTitle('sheet1')
            ->fromArray($this->exportFormatter(), null, $this->exportStartCell);

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . time() . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writter = IOFactory::createWriter($sheet, 'Xlsx');

        $writter->save('php://output');
        exit;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: chenlongmingob@outlook.com
 * Date: 2019/3/1
 * Time: 17:46
 */

namespace app\traits;


use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use think\db\Query;
use think\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * Trait Import
 * @package app\traits
 * @method string parseField(string $original)
 * @method Query getDb()
 * @method string replaceTable(string $str)
 * @property Request $request
 */
trait Import
{
    /** @var bool 是否展示导入按钮 */
    public $showImportButton = true;

    /** @var string 导入的起始单元个 */
    public $importStartCell = 'A1';

    /** @var string excel布局风格 row: 行 col: 列 */
    public $excelLayout = 'row';

    /**
     * 导入字段
     * @var array
     * @example ['field1', 'field2', ...]
     * @example ['field1,field1_alias1|field1_alias2,convertMethod1', 'field2,field2_alias1|field2_alias2,convertMethod2']
     * @example [['field1', 'field1_alias1|field1_alias2', 'convertMethod1'], ...]
     */
    public $importFields = [];


    /**
     * 初始化导入字段(统一参数风格)
     */
    private function init_importField ()
    {
        $fields = $this->importFields;
        if (!is_array($fields) || (is_array($fields) && count($fields) === 0) || is_array($fields[0])) {
            return;
        }

        foreach ($fields as $field) {
            $tmp = explode(',', $field);
            $tmp[0] = $this->replaceTable($this->parseField($tmp[0]));
            $formatted[] = $tmp;
        }

        if (!empty($formatted)) {
            $this->importFields = $formatted;
        }
    }


    /**
     * 按行读取 excel 中的数据
     * @param Worksheet $sheet
     * @param array $data
     * @return array|null
     */
    public function readFromRows (Worksheet $sheet, array &$data = null)
    {
        $startCellIndex = cellIndex($this->importStartCell);


        $rowIndex = 0;
        $dict = [];
        foreach ($sheet->getRowIterator($startCellIndex['row']) as $row) {
            if ($rowIndex === 0) {

                // 表头, 确定每一列对应的字段
                foreach ($row->getCellIterator($startCellIndex['column']) as $cell) {
                    $field = $this->nameToField($cell->getValue());
                    if ($field !== null) {
                        $dict[$cell->getColumn()] = $field;
                    }
                }
            } else {

                // 正文, 生成将要写入数据库的数组
                $_row = [];
                foreach ($row->getCellIterator($startCellIndex['column']) as $cell) {
                    $field = $dict[$cell->getColumn()] ?? null;
                    if (!empty($field)) {
                        $val = $cell->getValue();
                        if (!empty($field[2]) && method_exists(static::class, $field[2])) {
                            $val = call_user_func([static::class, $field[2]], $val, $row, $field);
                        }

                        $_row[$field[0]] = $val;
                    }
                }

                $data[] = $_row;
            }

            $rowIndex++;
        }

        return $data ?? null;
    }


    /**
     * 按列读取 excel 中的数据
     * @param Worksheet $sheet
     * @param array $data
     * @return array|null
     */
    public function readFromColumns (Worksheet $sheet, array &$data = null)
    {
        $startCellIndex = cellIndex($this->importStartCell);
        $dict = [];
        $columnIndex = 0;


        foreach ($sheet->getColumnIterator($startCellIndex['column']) as $column) {

            if ($columnIndex === 0) {
                // 表头, 确定每一列对应的字段
                foreach ($column->getCellIterator($startCellIndex['column']) as $cell) {
                    $field = $this->nameToField($cell->getValue());
                    if ($field !== null) {
                        $dict[$cell->getColumn()] = $field;
                    }
                }
            } else {
                // 正文, 生成将要写入数据库的数组
                $_col = [];
                foreach ($column->getCellIterator($startCellIndex['column']) as $cell) {
                    $field = $dict[$cell->getColumn()] ?? null;
                    if (!empty($field)) {
                        $val = $cell->getValue();
                        if (!empty($field[2]) && method_exists(static::class, $field[2])) {
                            $val = call_user_func([static::class, $field[2]], $val, $column, $field);
                        }

                        $_col[$field[0]] = $val;
                    }
                }

                $data[] = $_col;
            }

            $columnIndex++;
        }

        return $data ?? null;
    }


    /**
     * 导入方法
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function import ()
    {
        $file = $this->request->file('file');
        if (!$file)
            throw new \Exception('文件上传失败');

        $savePath = ROOT_PATH . 'runtime' . DS . 'import';
        $info = $file->move($savePath);

        if ($info->getError())
            throw new \Exception($info->getError());

        // 创建 excel sheet 实例
        $type = IOFactory::identify($info->getRealPath());
        $reader = IOFactory::createReader($type);
        $worker = $reader->load($info->getRealPath());
        $sheet = $worker->setActiveSheetIndex(0);

        // 读取数据
        $data = $this->excelLayout === 'row' ? $this->readFromRows($sheet) : $this->readFromColumns($sheet);

        // 写入数据库
        $this->importInsertAll($data);
    }


    /**
     * 写入数控
     * @param array $data
     */
    public function importInsertAll (array $data)
    {
        $this->getDb()->insertAll($data);
    }


    /**
     * 将字段名转换为表对应的字段
     * @param $name
     * @return string|null
     */
    public function nameToField ($name)
    {
        foreach ($this->importFields as $field) {
            $names = explode('|', $field[1]);
            foreach ($names as $_name) {
                if ($_name === trim($name)) {
                    return $field;
                }
            }
        }

        return null;
    }
}
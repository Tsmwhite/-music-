<?php
/**
 * Created by PhpStorm.
 * User: chenlongmingob@outlook.com
 * Date: 2018/12/19
 * Time: 10:53
 */

namespace app\plugins\hello\admin\viewModel;


use app\common\ViewModel;

class Hello extends ViewModel
{
    public $fieldsName = [
        'id'            =>  'ID',
        'uuid'          =>  '识别编号',
        'name'          =>  '名称',
        'update_time'   =>  '更新时间'
    ];


    public $indexFields = [
        'id' => ['text'],
        'uuid' => ['text'],
        'name' => ['text'],
        'update_time' => ['text']
    ];


    public $updateFields = [
        'id' => ['text', 'require'],
        'uuid' => ['text', 'require'],
        'name' => ['text'],
        'update_time' => ['datetime'],
    ];


    public $search = [
        'id' => 'text'
    ];
}
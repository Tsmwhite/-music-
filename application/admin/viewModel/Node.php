<?php
/**
 * Created by PhpStorm.
 * User: chenlongmingob@outlook.com
 * Date: 2018/11/21
 * Time: 13:11
 */

namespace app\admin\viewModel;


use app\common\ViewModel;

class Node extends ViewModel
{
    public $order = 'node.module asc, node.controller asc, node.action asc';
    public $fieldsName = [
        'node.id'           =>  'ID',
        'node.module'       =>  '模块',
        'node.controller'   =>  '控制器',
        'node.action'       =>  '方法',
        'node.create_time'  =>  '创建时间'
    ];


    public $indexFields = [
        'node.id'           =>  ['text'],
        'node.module'       =>  ['text'],
        'node.controller'   =>  ['text'],
        'node.action'       =>  ['text'],
        'node.create_time'  =>  ['text'],
    ];

    public $search = [
        'node.id'           =>  ['number'],
        'node.module'       =>  ['text'],
        'node.controller'   =>  ['text'],
        'node.action'       =>  ['text']
    ];


    public $updateFields = [
        'node.module'       =>  ['text', 'require'],
        'node.controller'   =>  ['text', 'require'],
        'node.action'       =>  ['text', 'require']
    ];
}
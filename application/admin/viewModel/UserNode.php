<?php
/**
 * Created by PhpStorm.
 * User: chenlongmingob@outlook.com
 * Date: 2018/11/21
 * Time: 16:47
 */

namespace app\admin\viewModel;


use app\common\ViewModel;

class UserNode extends ViewModel
{
    public $tableName = 'admin_node';
    
    public $join = [
        ['admin', 'admin.id=admin_node.uid'],
        ['node', 'node.id=admin_node.nid']
    ];


    public $fieldsName = [
        'admin_node.uid'         =>  '用户ID',
        'admin.username'         =>  '用户名',
        'admin_node.nid'         =>  '权限ID',
        'node.module'           =>  '模块',
        'node.controller'       =>  '控制器',
        'node.action'           =>  '方法',
        'admin_node.create_time' =>  '关联时间'
    ];


    public $indexFields = [
        'admin_node.uid'         =>  ['text'],
        'admin.username'         =>  ['text'],
        'admin_node.nid'         =>  ['text'],
        'node.module'           =>  ['text'],
        'node.controller'       =>  ['text'],
        'node.action'           =>  ['text'],
        'admin_node.create_time' =>  ['text']
    ];


    public $search = [
        'admin_node.uid'         =>  ['number'],
        'admin.username'         =>  ['text', 'like'],
        'admin_node.nid'         =>  ['number'],
        'node.module'           =>  ['text', 'like'],
        'node.controller'       =>  ['text', 'like'],
        'node.action'           =>  ['text', 'like']
    ];

    public $updateFields = [
        'admin_node.uid'         =>  ['number', 'require'],
        'admin_node.nid'         =>  ['selector', 'require', null, [
            'table'             =>  'node',
            'field'             =>  'module, controller, action',
            'value'             =>  'id',
            'format'            =>  '/*module*/*controller*/*action*'
        ]],
    ];
}
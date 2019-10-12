<?php
/**
 * Created by PhpStorm.
 * User: chenlongmingob@outlook.com
 * Date: 2018/11/21
 * Time: 13:37
 */

namespace app\admin\viewModel;


use app\common\ViewModel;

class RoleNode extends ViewModel
{
    public $join = [
        ['role', 'role.id=role_node.rid'],
        ['node', 'role_node.nid=node.id']
    ];


    public $fieldsName = [
        'role_node.rid'         =>  '角色ID',
        'role.name'             =>  '角色名',
        'role_node.nid'         =>  '权限ID',
        'node.module'           =>  '模块',
        'node.controller'       =>  '控制器',
        'node.action'           =>  '方法',
        'role_node.create_time' =>  '关联时间'
    ];


    public $indexFields = [
        'role_node.rid'         =>  ['text'],
        'role.name'             =>  ['text'],
        'role_node.nid'         =>  ['text'],
        'node.module'           =>  ['text'],
        'node.controller'       =>  ['text'],
        'node.action'           =>  ['text'],
        'role_node.create_time' =>  ['text']
    ];

    public $search = [
        'role_node.rid'         =>  ['number'],
        'role.name'             =>  ['text', 'like'],
        'role_node.nid'         =>  ['number'],
        'node.module'           =>  ['text', 'like'],
        'node.controller'       =>  ['text', 'like'],
        'node.action'           =>  ['text', 'like']
    ];

    public $updateFields = [
        'role_node.rid'         =>  ['selector', 'require', null, [
            'table'             =>  'role',
            'field'             =>  'name',
            'value'             =>  'id'
        ]],
        'role_node.nid'         =>  ['selector', 'require', null, [
            'table'             =>  'node',
            'field'             =>  'module, controller, action',
            'value'             =>  'id',
            'format'            =>  '/*module*/*controller*/*action*'
        ]],
    ];

    public $addFields = [
        'role_node.rid'         =>  ['selector', 'require', null, [
            'table'             =>  'role',
            'field'             =>  'name',
            'value'             =>  'id'
        ]],
        'role_node.nid'         =>  ['checkbox', 'require', null, [
            'table'             =>  'node',
            'field'             =>  'module, controller, action',
            'value'             =>  'id',
            'format'            =>  '/*module*/*controller*/*action*'
        ]],
    ];
}
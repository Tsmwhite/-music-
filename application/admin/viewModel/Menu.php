<?php
/**
 * Created by PhpStorm.
 * User: chenlongmingob@outlook.com
 * Date: 2018/11/21
 * Time: 16:58
 */

namespace app\admin\viewModel;


use app\common\ViewModel;

class Menu extends ViewModel
{
    public $order = 'id asc';
    public $join = [
        ['node', 'node.id=menu.nid', 'left']
    ];


    public $fieldsName = [
        'menu.id'           =>  '菜单ID',
        'menu.nid'          =>  '菜单权限(id)',
        'menu.pid'          =>  '父级菜单(id)',
        'menu.name'         =>  '菜单名称',
        'menu.order'        =>  '菜单排序',
        'menu.class'        =>  '菜单类名',
        'node.module'       =>  '模块',
        'node.controller'   =>  '控制器',
        'node.action'       =>  '方法'
    ];


    public $indexFields = [
        'menu.id'           =>  ['text'],
        'menu.nid'          =>  ['text'],
        'menu.pid'          =>  ['text'],
        'menu.name'         =>  ['text'],
        'menu.order'        =>  ['text'],
        'menu.class'        =>  ['text'],
        'node.module'       =>  ['text'],
        'node.controller'   =>  ['text'],
        'node.action'       =>  ['text']
    ];


    public $search = [
        'menu.id'           =>  ['number'],
        'menu.nid'          =>  ['number'],
        'menu.pid'          =>  ['number'],
        'menu.name'         =>  ['text', 'like'],
        'node.module'       =>  ['text'],
        'node.controller'   =>  ['text'],
        'node.action'       =>  ['text']
    ];

    public $updateFields = [
        'menu.name'         =>  ['text', 'require'],
        'menu.order'        =>  ['number'],
        'menu.class'        =>  ['text'],
        'menu.pid'          =>  ['selector', null, '选择父级菜单', [
            'table'         =>  'menu',
            'field'         =>  'name, id',
            'value'         =>  'id',
            'format'        =>  '*name*(id:*id*)'
        ]],
        'menu.nid'          =>  ['selector', null, '选择菜单权限(菜单权限为空并且没有子菜单时, 视为无效的菜单, 将不会显示)', [
            'table'         =>  'node',
            'field'         =>  'module, controller, action',
            'value'         =>  'id',
            'format'        =>  '/*module*/*controller*/*action*'
        ]],
    ];

    // parent 是记录父级标识的字段, self 是自身的标识
    public $tree = [
        'parent' => 'pid',
        'self' => 'id'
    ];


    public $exportFields = [
        'menu.id,菜单ID',
        'menu.nid,菜单权限(id)',
        'menu.pid,父级菜单(id)',
        'menu.name,菜单名称',
        'menu.order,菜单排序',
        'menu.class,菜单类名',
        'node.module,模块',
        'node.controller,控制器',
        'node.action,方法'
    ];
}
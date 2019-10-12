<?php
/**
 * Created by PhpStorm.
 * User: chenlongmingob@outlook.com
 * Date: 2018/11/21
 * Time: 11:10
 */

namespace app\admin\viewModel;


use app\common\ViewModel;

class Role extends ViewModel
{
    public $fieldsName = [
        'role.id'           =>  'ID',
        'role.name'         =>  '角色名',
        'role.create_time'  =>  '创建时间',
        'num'               =>  '用户数量'
    ];


    public $indexFields = [
        'role.id'           =>  ['text'],
        'role.name'         =>  ['text'],
        'role.create_time'  =>  ['text'],
        '(select count(*) from admin_role where admin_role.rid = role.id)' => ['text', 'alias' => 'num']
    ];


    public $updateFields = [
        'role.id'           =>  ['readonly'],
        'role.name'         =>  ['text', 'require|max:10'],
        'role.create_time'  =>  ['readonly']
    ];


    public $addFields = [
        'role.name'         =>  ['text', 'require|max:10']
    ];


    public $search = [
        'role.id'       =>  ['number'],
        'role.name'     =>  ['text', 'like']
    ];
}
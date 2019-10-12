<?php
/**
 * Created by PhpStorm.
 * User: chenlongmingob@outlook.com
 * Date: 2018/11/21
 * Time: 11:23
 */

namespace app\admin\viewModel;


use app\common\ViewModel;

class UserRole extends ViewModel
{
    public $tableName = 'admin_role';

    public $join = [
        ['admin', 'admin.id=admin_role.uid'],
        ['role', 'role.id=admin_role.rid']
    ];

    public $fieldsName = [
        'admin.id' => '用户ID',
        'admin.username' => '用户名',
        'role.id' => '角色ID',
        'role.name' => '角色名',
        'admin_role.create_time' => '关联时间',
        'admin_role.uid' => '用户ID',
        'admin_role.rid' => '角色ID'
    ];


    public $indexFields = [
        'admin_role.uid' => ['text'],
        'admin.username' => ['text'],
        'admin_role.rid' => ['text'],
        'role.name' => ['text'],
        'admin_role.create_time' => ['text'],
    ];


    public $search = [
        'admin.id' => ['number'],
        'admin.username' => ['text', 'like'],
        'role.id' => ['number'],
        'role.name' => ['text', 'like']
    ];


    public $updateFields = [
        'admin_role.uid' => ['number', 'require'],
        'admin_role.rid' => ['selector', 'require', null, [
            'table' => 'role',
            'field' => 'name',
            'value' => 'id'
        ]]
    ];
}
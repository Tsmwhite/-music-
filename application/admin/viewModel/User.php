<?php
/**
 * 由代码生成工具自动生成
 * Date: 2019-03-26
 * Time: 02:01:21
 */

namespace app\admin\viewModel;


use app\common\ViewModel;

class User extends ViewModel
{
    public $fieldsName = [
		'id' => 'ID',
		'email' => '邮箱',
		'pass' => '密码',
		'vip' => 'vip',
		'phone' => '手机号',
		'photo' => '头像',
		'name' => '姓名',
		'sex' => '性别',
		'birthday' => '生日',
		'address' => '地址',
		'interest' => '兴趣',
	];

    public $indexFields = [
		'id' => 'text',
		'email' => 'text',
		'pass' => 'text',
		'vip' => 'text',
		'phone' => 'text',
		'photo' => 'text',
		'name' => 'text',
		'sex' => 'text',
		'birthday' => 'text',
		'address' => 'text',
		'interest' => 'text',
	];

    public $updateFields = [
		'id' => ['text', 'Input'],
		'email' => ['text', 'Input'],
		'pass' => ['text', 'Input'],
		'vip' => ['text', 'Input'],
		'phone' => ['text', 'Input'],
		'photo' => ['text', 'Input'],
		'name' => ['text', 'Input'],
		'sex' => ['text', 'Input'],
		'birthday' => ['text', 'Input'],
		'address' => ['text', null],
		'interest' => ['text', null],
	];

    public $addFields = [
		'id' => ['text', 'Input'],
		'email' => ['text', 'Input'],
		'pass' => ['text', 'Input'],
		'vip' => ['text', 'Input'],
		'phone' => ['text', 'Input'],
		'photo' => ['text', 'Input'],
		'name' => ['text', 'Input'],
		'sex' => ['text', 'Input'],
		'birthday' => ['text', 'Input'],
		'address' => ['text', null],
		'interest' => ['text', null],
	];

    public $search = [
		'name' => ['text', 'like'],
		'phone' => ['int', 'like'],
	];

    public $exportFields = [
		'id,ID',
		'email,邮箱',
		'pass,密码',
		'vip,vip',
		'phone,手机号',
		'photo,头像',
		'name,姓名',
		'sex,性别',
		'birthday,生日',
		'address,地址',
		'interest,兴趣',
	];

    public $importFields = [
		'id,ID',
		'email,邮箱',
		'pass,密码',
		'vip,vip',
		'phone,手机号',
		'photo,头像',
		'name,姓名',
		'sex,性别',
		'birthday,生日',
		'address,地址',
		'interest,兴趣',
	];
}
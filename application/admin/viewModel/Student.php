<?php
/**
 * 由代码生成工具自动生成
 * Date: 2019-04-22
 * Time: 00:05:54
 */

namespace app\admin\viewModel;


use app\common\ViewModel;

class Student extends ViewModel
{
    public $fieldsName = [
		'id' => '编号',
		'email' => '邮箱',
		'pass' => '密码',
		'photo' => '头像',
		'name' => '名字',
		'sex' => '性别',
		'birthday' => '生日',
		'interest' => '兴趣',
		'address' => '地址',
		'phone' => '手机号',
		'add_time' => '注册时间',
		'update_time' => '登陆时间',
		'money' => '余额',
		'token' => '登陆token',
		'is_vip' => '是否是vip 1不是2是',
	];

	public function fetchButtons($row)
	{
			$addButtons[] = [
        'url'       =>  '/admin/student/update',
        'type'      =>  'info',
        'name'      =>  '查看',
        'params'    =>  [
            'id'   =>  $row['id'],
        ]
			];
			return $addButtons;
	}

    public $indexFields = [
		'id' => 'text',
		'photo' => 'img',
		'name' => 'text',
		'sex' => ['convert', [1 => '男', 2 => '女']],
		'email' => 'text',
		'phone' => 'text',
		'money' => 'text',
		'is_vip' => ['convert', [1 => '普通用户', 2 => 'VIP用户']],
	];

    public $updateFields = [
		'email' => ['text', null],
		'photo' => ['image', null],
		'name' => ['text', null],
		'sex' => ['text', null],
		'birthday' => ['text', null],
		'interest' => ['text', null],
		'address' => ['text', null],
		'phone' => ['text', null],
		'money' => ['text', null],
		'is_vip' => ['text', null],
	];

    public $addFields = [
		'email' => ['text', null],
		'photo' => ['image', null],
		'name' => ['text', null],
		'sex' => ['text', null],
		'birthday' => ['text', null],
		'interest' => ['text', null],
		'address' => ['text', null],
		'phone' => ['text', null],
		'money' => ['text', null],
		'is_vip' => ['text', null],
	];

    public $search = [
		'id' => ['text', ],
		'email' => ['text', 'like'],
		'name' => ['text', 'like'],
		'phone' => ['text', 'like'],
	];

    public $exportFields = [
		'id,编号',
		'email,邮箱',
		'pass,密码',
		'photo,头像',
		'name,名字',
		'sex,性别',
		'birthday,生日',
		'interest,兴趣',
		'address,地址',
		'phone,手机号',
		'add_time,注册时间',
		'update_time,登陆时间',
		'money,余额',
		'token,登陆token',
		'is_vip,是否是vip',
	];

    public $importFields = [
		'id,编号',
		'email,邮箱',
		'pass,密码',
		'photo,头像',
		'name,名字',
		'sex,性别',
		'birthday,生日',
		'interest,兴趣',
		'address,地址',
		'phone,手机号',
		'add_time,注册时间',
		'update_time,登陆时间',
		'money,余额',
		'token,登陆token',
		'is_vip,是否是vip',
	];
}
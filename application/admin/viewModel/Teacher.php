<?php
/**
 * 由代码生成工具自动生成
 * Date: 2019-04-23
 * Time: 00:03:27
 */

namespace app\admin\viewModel;


use app\common\ViewModel;

class Teacher extends ViewModel
{
    public $fieldsName = [
		'id' => '序号',
		'name' => '教师名称',
		'address' => '地址',
		'phone' => '手机号',
		'email' => '邮箱',
		'ABN' => 'ABN',
		'photo' => '图片',
		'content' => '简介',
		'sex' => '性别',
		'card' => '证件号',
		'return_body' => '审核字段，若审核失败则可以在此字段设置回复内容',
		'type' => '申请状态(3:申请中，2审核失败，1审核成功)',
		'ziliao_type' => '认证状态(4:失败，3未认证，2审核成功，1认证中)',
		'teacher_style1_photo' => '教师风采第一张',
		'teacher_style2_photo' => '教师风采第二张',
		'teacher_style3_photo' => '教师风采第三张',
		'teacher_style4_photo' => '教师风采第四张',
		'teacher_style5_photo' => '教师风采第五张',
		'teacher_style6_photo' => '教师风采第六张',
	];
	public function fetchButtons($row)
	{
			$addButtons[] = [
        'url'       =>  '/admin/teacher/type',
        'type'      =>  'info',
        'name'      =>  '认证内容',
        'params'    =>  [
            'id'   =>  $row['id'],
        ]
			];
			$addButtons[] = [
        'url'       =>  '/admin/teacher/info',
        'type'      =>  'info',
        'name'      =>  '审核内容',
        'params'    =>  [
            'id'   =>  $row['id'],
        ]
			];
			$addButtons[] = [
        'url'       =>  '/admin/teacher/update',
        'type'      =>  'info',
        'name'      =>  '查看',
        'params'    =>  [
            'id'   =>  $row['id'],
        ]
			];
			$addButtons[] = [
        'url'       =>  '/admin/teacher/delete',
        'type'      =>  'info',
        'name'      =>  '删除',
        'params'    =>  [
            'id'   =>  $row['id'],
        ]
			];
			return $addButtons;
	}


    public $indexFields = [
		'id' => 'text',
		'name' => 'text',
		'address' => 'text',
		'phone' => 'text',
		'email' => 'text',
		'type' => ['convert', [3 => '申请中', 1 => '审核成功', 2=>'审核失败']],
		'ziliao_type' => ['convert', [4 => '失败', 3 => '未认证', 2=>'审核成功',1=>'认证中']],
	];

    public $updateFields = [
		'name' => ['text', null],
		'address' => ['text', null],
		'phone' => ['text', null],
		'email' => ['text', null],
		'ABN' => ['text', null],
		'photo' => ['image', null],
		'content' => ['text', null],
		'card' => ['text', null],
		'teacher_style1_photo' => ['image', null],
		'teacher_style2_photo' => ['image', null],
		'teacher_style3_photo' => ['image', null],
		'teacher_style4_photo' => ['image', null],
		'teacher_style5_photo' => ['image', null],
		'teacher_style6_photo' => ['image', null],
	];

    public $addFields = [
		'name' => ['text', null],
		'address' => ['text', null],
		'phone' => ['text', null],
		'email' => ['text', null],
		'ABN' => ['text', null],
		'photo' => ['image', null],
		'content' => ['text', null],
		'card' => ['text', null],
		'teacher_style1_photo' => ['image', null],
		'teacher_style2_photo' => ['image', null],
		'teacher_style3_photo' => ['image', null],
		'teacher_style4_photo' => ['image', null],
		'teacher_style5_photo' => ['image', null],
		'teacher_style6_photo' => ['image', null],
	];

    public $search = [
			'type' =>  ['number'],
			'ziliao_type' => ['number']
 	];

    public $exportFields = [
		'id,序号',
		'name,教师名称',
		'address,地址',
		'phone,手机号',
		'email,邮箱',
		'ABN,ABN',
		'photo,图片',
		'content,简介',
	];

    public $importFields = [
		'id,序号',
		'name,教师名称',
		'address,地址',
		'phone,手机号',
		'email,邮箱',
		'ABN,ABN',
		'photo,图片',
		'content,简介',
	];
}
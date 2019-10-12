<?php
/**
 * 由代码生成工具自动生成
 * Date: 2019-04-22
 * Time: 23:49:06
 */

namespace app\admin\viewModel;


use app\common\ViewModel;

class Finance extends ViewModel
{
    public $fieldsName = [
		'id' => '序号',
		'teacher_id' => '教师id',
		'start_time' => '开始时间',
		'stop_time' => '结束时间',
		'status' =>'申请状态(1进行中，2已结款，3已拒绝,4未结款)',
	];
	public function fetchButtons($row)
	{
			$addButtons[] = [
        'url'       =>  '/admin/finance/type',
        'type'      =>  'info',
        'name'      =>  '通过',
        'params'    =>  [
            'id'   =>  $row['id'],
            'type' => 2,
        ]
			];
			$addButtons[] = [
        'url'       =>  '/admin/finance/type',
        'type'      =>  'info',
        'name'      =>  '拒绝',
        'params'    =>  [
            'id'   =>  $row['id'],
            'type' => 3,
        ]
			];
			$addButtons[] = [
        'url'       =>  '/admin/finance/info',
        'type'      =>  'info',
        'name'      =>  '查看',
        'params'    =>  [
            'id'   =>  $row['id'],
        ]
			];
			$addButtons[] = [
        'url'       =>  '/admin/finance/delete',
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
		'teacher_id' => 'text',
		'start_time' => 'timestamp',
		'stop_time' => 'timestamp',
		'status' => ['convert', [1 => '进行中', 2 => '已结款', 3=>'已拒绝',4=>'未结款']],
	];

    public $updateFields = [
		'id' => ['text', null],
		'teacher_id' => ['text', null],
		'start_time' => ['text', null],
		'stop_time' => ['text', null],
	];

    public $addFields = [
		'id' => ['text', null],
		'teacher_id' => ['text', null],
		'start_time' => ['text', null],
		'stop_time' => ['text', null],
	];

    public $search = [
			'status' =>  ['number'],
			'teacher_id' =>  ['number'],
	];

    public $exportFields = [
		'id,序号',
		'teacher_id,教师id',
		'start_time,开始时间',
		'stop_time,结束时间',
	];

    public $importFields = [
		'id,序号',
		'teacher_id,教师id',
		'start_time,开始时间',
		'stop_time,结束时间',
	];
}
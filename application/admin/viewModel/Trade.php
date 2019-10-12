<?php
/**
 * 由代码生成工具自动生成
 * Date: 2019-05-13
 * Time: 01:17:37
 */

namespace app\admin\viewModel;


use app\common\ViewModel;

class Trade extends ViewModel
{
    public $fieldsName = [
		'id' => '序号',
		'price' => '金额',
		'time' => '时间',
		'student_id' => '学生id',
		'teacher_id' => '教师id',
		'class_id' => '课程id',
		'people_num' => '授课方式',
		'student_name' => '学生姓名',
		'class_name' => '课程名称',
		'teacher_name' => '教师名称',
		'value' => '课程数',
	];

	public function fetchButtons($row)
	{
			$addButtons[] = [
        'url'       =>  '/admin/trade/delete',
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
		'price' => 'text',
		'time' => 'timestrap',
		'student_id' => 'text',
		'teacher_id' => 'text',
		'class_id' => 'text',
		'people_num' => 'text',
		'student_name' => 'text',
		'class_name' => 'text',
		'teacher_name' => 'text',
		'value' => 'text',
	];

    public $updateFields = [
	];

    public $addFields = [
	];

    public $search = [	
		'student_id' => ['text', ],
		'teacher_id' => ['text', ],
		'class_id' => ['text', ],
		'people_num' => ['text', ],
		'student_name' => ['text', 'like'],
		'class_name' => ['text', 'like'],
		'teacher_name' => ['text', 'like'],
	];

    public $exportFields = [
		'id,序号',
		'price,金额',
	];

    public $importFields = [
		'id,序号',
		'price,金额',
	];
}
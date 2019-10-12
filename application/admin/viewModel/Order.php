<?php
/**
 * 由代码生成工具自动生成
 * Date: 2019-04-22
 * Time: 23:58:44
 */

namespace app\admin\viewModel;


use app\common\ViewModel;

class Order extends ViewModel
{
    public $fieldsName = [
		'id' => '序号',
		'class_id' => '课程名称',
		'student_id' => '用户id',
		'teacher_id' => '教师id',
		'type' => '状态',
		'price' => '价格',
		'punch' => '是否打卡',
		'notes_photo' => '笔记图片',
		'notes_video' => '笔记视频',
		'notes_content' => '笔记内容',
	];

    public $indexFields = [
		'id' => 'text',
		'class_id' => 'text',
		'student_id' => 'text',
		'teacher_id' => 'text',
		'type' => 'text',
		'price' => 'text',
		'punch' => 'text',
		'notes_photo' => 'img',
		'notes_video' => 'video',
		'notes_content' => 'text',
	];

    public $updateFields = [
		'id' => ['text', null],
		'class_id' => ['text', null],
		'student_id' => ['text', null],
		'teacher_id' => ['text', null],
		'type' => ['text', null],
		'price' => ['text', null],
		'punch' => ['text', null],
		'notes_photo' => ['image', null],
		'notes_video' => ['video', null],
		'notes_content' => ['text', null],
	];

    public $addFields = [
		'id' => ['text', null],
		'class_id' => ['text', null],
		'student_id' => ['text', null],
		'teacher_id' => ['text', null],
		'type' => ['text', null],
		'price' => ['text', null],
		'punch' => ['text', null],
		'notes_photo' => ['image', null],
		'notes_video' => ['video', null],
		'notes_content' => ['text', null],
	];

    public $search = [
	];

    public $exportFields = [
		'id,序号',
		'class_id,课程名称',
		'student_id,用户id',
		'teacher_id,教师id',
		'type,状态',
		'price,价格',
		'punch,是否打卡',
		'notes_photo,笔记图片',
		'notes_video,笔记视频',
		'notes_content,笔记内容',
	];

    public $importFields = [
		'id,序号',
		'class_id,课程名称',
		'student_id,用户id',
		'teacher_id,教师id',
		'type,状态',
		'price,价格',
		'punch,是否打卡',
		'notes_photo,笔记图片',
		'notes_video,笔记视频',
		'notes_content,笔记内容',
	];
}
<?php
/**
 * 由代码生成工具自动生成
 * Date: 2019-04-22
 * Time: 23:09:00
 */

namespace app\admin\viewModel;


use app\common\ViewModel;

class Classss extends ViewModel
{
    public $fieldsName = [
		'id' => '序号',
		'name' => '课程名称',
		'music_id' => '艺术总分类ID',
		'money' => '一对一每分钟价格',
		'money2' => '一对二每分钟价格',
		'money3' => '一对三每分钟价格',
		'content' => '简介',
		'mix_time_type' => '每节课最短时间',
		'max_time_type' => '每节课最长时间',
		'photo'=>'图片',
		'body'=>'内容',
		'environment_photo1'=>'教师风采1',
		'environment_photo2'=>'教师风采2',
		'environment_photo3'=>'教师风采3',
		'environment_photo4'=>'教师风采4',
		'environment_photo5'=>'教师风采5',
		'environment_photo6'=>'教师风采6',
	];

    public $indexFields = [
		'id' => 'text',
		'name' => 'text',
		'music_id' => 'text',
		'money' => 'text',
		'money2' => 'text',
		'money3' => 'text',
		'photo' => 'text',
		'mix_time_type' => 'text',
		'max_time_type' => 'text',
	];

    public $updateFields = [
		'name' => ['text', null],
		'music_id' => ['text', null],
		'money' => ['text', 0.00],
		'money2' => ['text', 0.00],
		'money3' => ['text', 0.00],
		'content' => ['text', null],
		'photo' => ['image', null],
		'mix_time_type' => ['text', null],
		'max_time_type' => ['text', null],
		'body' => ['text', null],
		'environment_photo1' => ['image', null],
		'environment_photo2' => ['image', null],
		'environment_photo3' => ['image', null],
		'environment_photo4' => ['image', null],
		'environment_photo5' => ['image', null],
		'environment_photo6' => ['image', null],
	];

    public $addFields = [
		'name' => ['text', null],
		'music_id' => ['text', null],
		'money' => ['text', null],
		'content' => ['text', null],
		'photo' => ['image', null],
		'mix_time_type' => ['text', null],
		'max_time_type' => ['text', null],
		'body' => ['text', null],
		'environment_photo1' => ['image', null],
		'environment_photo2' => ['image', null],
		'environment_photo3' => ['image', null],
		'environment_photo4' => ['image', null],
		'environment_photo5' => ['image', null],
		'environment_photo6' => ['image', null],
	];

    public $search = [
	];

    public $exportFields = [
		'id,序号',
		'name,课程名称',
		'music_id,艺术总分类名称',
		'money,每分钟价格',
		'content,简介',
		'photo,图片',
		'mix_time_type,每节课最短时间',
		'max_time_type,每节课最长时间',
		'body,内容',
	];

    public $importFields = [
		'id,序号',
		'name,课程名称',
		'music_id,艺术总分类名称',
		'money,每分钟价格',
		'content,简介',
		'photo,图片',
		'mix_time_type,每节课最短时间',
		'max_time_type,每节课最长时间',
		'body,内容',
	];
}
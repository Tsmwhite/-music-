<?php
/**
 * 由代码生成工具自动生成
 * Date: 2019-04-22
 * Time: 22:29:40
 */

namespace app\admin\viewModel;


use app\common\ViewModel;

class Feedback extends ViewModel
{
    public $fieldsName = [
		'id' => '编号',
		'user_id' => '用户id',
		'time' => '时间',
		'body' => '内容',
		'contact' => '联系方式',
	];

    public $indexFields = [
		'id' => 'text',
		'user_id' => 'text',
		'time' => 'timestamp',
		'body' => 'text',
		'contact' => 'text',
	];

    public $updateFields = [
		'id' => ['text', null],
		'user_id' => ['text', null],
		'time' => ['datetime', null],
		'body' => ['text', null],
		'contact' => ['text', null],
	];

    public $addFields = [
		'id' => ['text', null],
		'user_id' => ['text', null],
		'time' => ['datetime', null],
		'body' => ['text', null],
		'contact' => ['text', null],
	];

    public $search = [
	];

    public $exportFields = [
		'id,编号',
		'user_id,用户id',
		'time,时间',
		'body,内容',
		'contact,联系方式',
	];

    public $importFields = [
		'id,编号',
		'user_id,用户id',
		'time,时间',
		'body,内容',
		'contact,联系方式',
	];
}
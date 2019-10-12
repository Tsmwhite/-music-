<?php
/**
 * 由代码生成工具自动生成
 * Date: 2019-04-22
 * Time: 22:39:30
 */

namespace app\admin\viewModel;


use app\common\ViewModel;

class Music extends ViewModel
{
    public $fieldsName = [
		'id' => '序号',
		'name' => '名称',
		'photo' => '图片',
		'body' => '内容',
		'content' => '简介',
	];

    public $indexFields = [
		'id' => 'text',
		'name' => 'text',
		'photo' => 'img',
		'body' => 'text',
		'content' => 'text',
	];

    public $updateFields = [
		'name' => ['text', null],
		'photo' => ['image', null],
		'body' => ['text', null],
		'content' => ['text', null],
	];

    public $addFields = [
		'name' => ['text', null],
		'photo' => ['image', null],
		'body' => ['text', null],
		'content' => ['text', null],
	];

    public $search = [
	];

    public $exportFields = [
		'id,序号',
		'name,名称',
		'photo,图片',
		'body,内容',
		'content,简介',
	];

    public $importFields = [
		'id,序号',
		'name,名称',
		'photo,图片',
		'body,内容',
		'content,简介',
	];
}
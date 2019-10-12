<?php
/**
 * 由代码生成工具自动生成
 * Date: 2019-04-22
 * Time: 23:37:29
 */

namespace app\admin\viewModel;


use app\common\ViewModel;

class StudentCoupon extends ViewModel
{
    public $fieldsName = [
		'id' => '序号',
		'student_id' => '用户id',
		'end_time' => '到期时间',
		'price' => '价格（满xx）',
		'd_price' => '价格（减xx）',
		'type' => '类型 0为满减 1为打折',
		'discount' => '折扣（打几折，如 70 = 7折，60 = 6折）',
	];

    public $indexFields = [
		'id' => 'text',
		'student_id' => 'text',
		'end_time' => 'timestamp',
		'price' => 'text',
		'd_price' => 'text',
		'type' => ['convert', [0 => '满减', 1 => '打折']],
		'discount' => 'text',
	];

    public $updateFields = [
		'student_id' => ['text', null],
		'end_time' => ['datetime', null],
		'price' => ['text', null],
		'd_price' => ['text', null],
		'type' => ['text', null],
		'discount' => ['text', null],
	];

    public $addFields = [
		'student_id' => ['text', null],
		'end_time' => ['datetime', null],
		'price' => ['text', null],
		'd_price' => ['text', null],
		'type' => ['text', null],
		'discount' => ['text', null],
	];

    public $search = [
	];

    public $exportFields = [
		'id,序号',
		'student_id,用户id',
		'end_time,到期时间',
		'price,价格（满xx）',
		'd_price,价格（减xx）',
		'type,类型',
		'discount,折扣（打几折）',
	];

    public $importFields = [
		'id,序号',
		'student_id,用户id',
		'end_time,到期时间',
		'price,价格（满xx）',
		'd_price,价格（减xx）',
		'type,类型',
		'discount,折扣（打几折）',
	];
}
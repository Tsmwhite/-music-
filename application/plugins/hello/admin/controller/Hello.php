<?php
/**
 * Created by PhpStorm.
 * User: chenlongmingob@outlook.com
 * Date: 2018/12/18
 * Time: 19:43
 */

namespace app\plugins\hello\admin\controller;


use app\common\ViewModel;
use app\plugins\common\Controller;
use app\plugins\common\traits\Add;
use app\plugins\common\traits\Index;
use app\plugins\common\traits\Update;

/**
 * Class Hello
 * @property ViewModel $vm
 * @package app\plugins\hello\admin\controller
 */
class Hello extends Controller
{
    protected $vm = "\\app\\plugins\\hello\\admin\\viewModel\\Hello";

    use Index, Add, Update;
}
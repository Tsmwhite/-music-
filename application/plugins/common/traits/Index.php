<?php
/**
 * Created by PhpStorm.
 * User: chenlongmingob@outlook.com
 * Date: 2018/12/19
 * Time: 11:29
 */

namespace app\plugins\common\traits;

use app\common\ViewModel;

/**
 * 通用 Index 方法
 * Class Index
 * @property ViewModel $vm
 * @method self assign($variables)
 * @method fetch($template = null)
 * @package app\plugins\common\traits
 */
trait Index
{
    public function index ()
    {
        return $this->assign($this->vm->indexView())->fetch();
    }
}
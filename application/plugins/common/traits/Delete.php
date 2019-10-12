<?php
/**
 * Created by PhpStorm.
 * User: chenlongmingob@outlook.com
 * Date: 2018/12/19
 * Time: 13:43
 */

namespace app\plugins\common\traits;

use app\common\ViewModel;

/**
 * Trait Delete
 * @property ViewModel $vm
 * @method void redirect(string $url)
 * @package app\plugins\common\traits
 */
trait Delete
{
    public function delete ()
    {
        $this->vm->delete();
        $this->redirect(url('index'));
    }
}
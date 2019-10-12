<?php
/**
 * Created by PhpStorm.
 * User: chenlongmingob@outlook.com
 * Date: 2018/12/19
 * Time: 11:37
 */

namespace app\plugins\common\traits;


use app\common\ViewModel;
use think\Request;

/**
 * 通用 Add 方法
 * Class Index
 * @property ViewModel $vm
 * @property Request $request
 * @method self assign($variables)
 * @method fetch($template = null)
 * @method mixed redirect(string $url)
 * @package app\plugins\common\traits
 */
trait Add
{
    /**
     * @param \Closure $todo
     * @return string
     * @throws \Exception
     */
    public function add ($todo = null)
    {
        if ($this->request->param('action') === 'add') {
            $this->vm->add(null, $todo);
            $this->redirect(url('index'));
        } else {
            return $this->assign($this->vm->addView())->fetch();
        }
    }
}
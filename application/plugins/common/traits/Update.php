<?php
/**
 * Created by PhpStorm.
 * User: chenlongmingob@outlook.com
 * Date: 2018/12/19
 * Time: 11:41
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
trait Update
{
    /**
     * @param \Closure $todo
     * @return string
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function update ($todo = null)
    {
        if ($this->request->param('action') === 'update') {
            $this->vm->update(null, $todo);
            $this->redirect(url('index'));
        } else {
            return $this->assign($this->vm->updateView())->fetch();
        }
    }
}
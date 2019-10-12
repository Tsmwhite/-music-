<?php
/**
 * Created by PhpStorm.
 * User: chenlongmingob@outlook.com
 * Date: 2018/11/21
 * Time: 13:36
 */

namespace app\admin\controller;


use app\Controller;
use auth\RBAC;
use app\common\ViewModel;

class RoleNode extends Controller
{

    public function add($todo = null)
    {
        if ($this->request->param('action') === 'add') {
            $vm = ViewModel::instance($this->model);
            $vm->add(null, function ($params) use ($vm) {
                foreach (explode(',', $params['role_node.nid']) as $nid) {
                    $data[] = ['rid' => $params['role_node.rid'], 'nid' => $nid];
                }

                if (!empty($data)) {
                    $vm->getDb()->insertAll($data);
                }

                (new RBAC())->resetAuthInfo();
            });
            $this->redirect(url('index'));
        } else {
            return $this->view->assign(ViewModel::instance($this->model)->addView())->fetch();
        }
    }

    public function update($todo = null)
    {

        if ($this->request->param('action') === 'update') {

            (new RBAC())->resetAuthInfo();
        }

        return parent::update();
    }

    public function delete()
    {
        (new RBAC())->resetAuthInfo();
        parent::delete();
    }
}
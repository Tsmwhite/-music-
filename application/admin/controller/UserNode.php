<?php
/**
 * Created by PhpStorm.
 * User: chenlongmingob@outlook.com
 * Date: 2018/11/21
 * Time: 16:46
 */

namespace app\admin\controller;


use auth\RBAC;
use app\Controller;

class UserNode extends Controller
{
    public function add($todo = null)
    {
        if ($this->request->param('action') === 'add') {

            (new RBAC())->resetAuthInfo();
        }


        return parent::add();
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
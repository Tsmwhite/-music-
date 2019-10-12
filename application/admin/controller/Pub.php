<?php
/**
 * Created by PhpStorm.
 * User: chenlongmingob@outlook.com
 * Date: 2018/11/14
 * Time: 13:54
 */

namespace app\admin\controller;


use app\admin\validate\Login;
use app\common\model\Menu;
use auth\exception\LoginException;
use auth\RBAC;
use think\Controller;
use think\Cookie;

class Pub extends Controller
{
    public function login ()
    {
        $auth = getAuthClass();

        // 以获得认证信息, 直接进入首页
        if ($auth->checkAuth()) {
            $this->redirect('index/index');
        }

        if ($this->request->isPost()) {
            $params = $this->request->param();
            $v = new Login();
            if (!$v->check($params))
                throw new LoginException($v->getError());

            getAuthClass()->setAuthInfo();

            $this->redirect('index/index');
        } else {

            return $this->fetch();
        }
    }

    public function logout ()
    {
        getAuthClass()->removeAuth();
    }

    public function test ()
    {
//        dump(collection(Menu::getAccessMenus())->toArray());
        dump(Menu::fetch());
    }

}
<?php
namespace app\admin\controller;


use app\Controller;

class Index extends Controller
{
    public function index ()
    {
        return $this->assign(getSystemState())->fetch();
    }
}

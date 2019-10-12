<?php
/**
 * Created by PhpStorm.
 * User: chenlongmingob@outlook.com
 * Date: 2018/12/20
 * Time: 17:15
 */

namespace app\plugins\hello\api\controller;


use app\plugins\common\ApiController;

class Hello extends ApiController
{
    public function index ()
    {
        return [12, 34, 56];
    }
}
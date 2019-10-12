<?php
/**
 * Created by PhpStorm.
 * User: chenlongmingob@outlook.com
 * Date: 2018/12/24
 * Time: 15:12
 */

namespace app\api\controller;


use app\ApiController;
use app\FormController;
use think\exception\HttpException;

class Form extends ApiController
{
    public function submit ($form = null)
    {
        if ($form) {

            /** @var FormController $form */
            if (class_exists($form)) {
                return $form::submit();
            }
        }

        throw new HttpException(404, '表单控制器不存在');
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: chenl
 * Date: 2018/10/18
 * Time: 11:13
 */

namespace app\common\exception;


use auth\exception\LoginException;
use Exception;
use think\Cookie;
use think\Request;

class Handle extends \think\exception\Handle
{
    public function render(Exception $e)
    {
        $classNameArray = explode('\\', get_class($e));
        $className = end($classNameArray);
        $request = Request::instance();
        if (
            $request->isAjax() ||
            $request->has('_xhr') ||
            $request->header('x-requested-with') === 'XMLHttpRequest'
        ) {
            return json([
                'msg' => $e->getMessage(),
                'err' => 1,
                'errCode' => $e->getCode(),
                'errType' => $className
            ]);
        }

        if ($e instanceof LoginException) {
            return redirect('/admin/pub/login');
        }

        return parent::render($e);
    }
}
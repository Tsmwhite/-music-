<?php
/**
 * Created by PhpStorm.
 * User: chenlongmingob@outlook.com
 * Date: 2018/11/19
 * Time: 11:12
 */

namespace auth\exception;


use Throwable;

class LoginException extends \Exception
{
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        if (!$message) $message = '请先登录';
        cookie('login_msg', $message);
        parent::__construct($message, $code, $previous);
    }
}
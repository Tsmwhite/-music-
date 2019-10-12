<?php
/**
 * Created by PhpStorm.
 * User: chenlongmingob@outlook.com
 * Date: 2018/10/18
 * Time: 14:59
 */
namespace auth\exception;

use think\response\Redirect;
use Throwable;

class AuthException extends \Exception
{
    public function __construct(string $message = "无权操作", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
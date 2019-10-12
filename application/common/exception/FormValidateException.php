<?php
/**
 * Created by PhpStorm.
 * User: chenlongmingob@outlook.com
 * Date: 2018/11/1
 * Time: 18:27
 */

namespace app\common\exception;


use Throwable;

/**
 * 表单验证异常
 * Class FormValidateException
 * @package app\common\exception
 */
class FormValidateException extends \Exception
{
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        if (!$message) $message = '请确认表单填写是否正确';
        parent::__construct($message, $code, $previous);
    }
}
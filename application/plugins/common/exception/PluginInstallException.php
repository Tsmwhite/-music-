<?php
/**
 * Created by PhpStorm.
 * User: chenlongmingob@outlook.com
 * Date: 2018/12/19
 * Time: 19:11
 */

namespace app\plugins\common\exception;


use Throwable;

class PluginInstallException extends CliException
{
    public $pluginPath = false;

    /**
     * PluginInstallException constructor.
     * @param string $message       异常描述
     * @param string $pluginPath    目录
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message = '', string $pluginPath = null, int $code = 0, Throwable $previous = null)
    {
        $this->pluginPath = $pluginPath;
        parent::__construct($message, $code, $previous);
    }
}
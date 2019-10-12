<?php
/**
 * Created by PhpStorm.
 * User: chenlongmingob@outlook.com
 * Date: 2018/12/18
 * Time: 18:24
 */

namespace app\plugins\common;

use think\Request;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\Parser;
use think\Config;

class ApiController extends \think\Controller
{
    /** @var Token */
    public $token = null;

    /**
     * 插件名
     * @var string $pluginName
     */
    protected $pluginName;


    public function __construct($pluginName, Request $request = null)
    {
        parent::__construct($request);


        $this->pluginName = $pluginName;


        header('Access-Control-Allow-Origin: *');

        $tokenStr = $this->request->header('Authorization');

        // 解析客户端信息
        if ($tokenStr) {

            // 加密 token 字符串
            if (!empty(Config::get('jwt')['rsa'])) {
                $tokenStr = RsaDecrypt($tokenStr, true);
            }

            $token = (new Parser())->parse($tokenStr);

            // 获取验证通过的客户端信息
            if (checkToken($token)) {
                $this->token = $token;
            }
        }

        // 节省开销, options 请求直接忽略
        if (strtolower($_SERVER['REQUEST_METHOD']) === 'options') exit;
    }
}
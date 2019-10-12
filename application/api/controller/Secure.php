<?php
/**
 * Created by PhpStorm.
 * User: chenlongmingob@outlook.com
 * Date: 2018/12/13
 * Time: 15:35
 */

namespace app\api\controller;


use app\ApiController;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use phpseclib\Crypt\RSA;
use think\Config;
use think\Request;

class Secure extends ApiController
{
    /**
     * 重新生成 密钥
     * @throws \Exception
     * @return bool|string
     */
    public function resetRsaKey ()
    {
        $ip = Request::instance()->ip();
        if ($ip === '127.0.0.1' || $ip === '0.0.0.0') {

            $config = Config::get('rsa');
            $rsa = new RSA();
            $fs = new Filesystem(new Local($config['path']));
            $keys = $rsa->createKey();

            $fs->put($config['privateKeyFile'], $keys['privatekey']);
            $fs->put($config['publicKeyFile'], $keys['publickey']);
            return '密钥创建成功';
        } else {

            return '不允许外网访问, 你的IP地址: ' . $ip;
        }
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: chenlongmingob@outlook.com
 * Date: 2018/12/26
 * Time: 18:52
 */

namespace Wechat;


use EasyWeChat\Factory;
use think\Config;

class MiniProgram
{
    public $app;


    public function __construct()
    {
        $this->app = Factory::miniProgram(Config::get('wechat')['miniProgram']);
    }

    public function code2session (string $code)
    {
        return $this->app->auth->session($code);
    }


    public function decryptData ($signature, $iv, $data)
    {
        return $this->app->encryptor->decryptData($signature, $iv, $data);
    }


    public static function instance ()
    {
        return new static();
    }

    public static function toUser (array $wxUserInfo)
    {
        return [
            'mini_openid'   =>  $wxUserInfo['openId'],
            'avatar'        =>  $wxUserInfo['avatarUrl'],
            'sex'           =>  $wxUserInfo['gender'],
            'nickname'      =>  $wxUserInfo['nickName']
        ];
    }
}
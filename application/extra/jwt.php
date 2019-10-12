<?php
/**
 * Created by PhpStorm.
 * User: chenlongmingob@outlook.com
 * Date: 2018/12/3
 * Time: 13:42
 */

return [
    // api token 加密密码
    'apikey'            =>  md5('chenlongmingob@outlook.com'),

    // jwt 颁发者
    'issuer'            =>  null,

    // 登陆过期时间
    'expire'            =>  24 * 60 * 60,

    // 签名方式
    'signer'            =>  '\\Lcobucci\\JWT\\Signer\\Hmac\\Sha256',


    // 是否使用 rsa 加密 token
    'rsa'               =>  true
];
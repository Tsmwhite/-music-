<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件


// Convert 组件场景值 gender 性别
defined('CONVERT_SCENE_GENDER') or define('CONVERT_SCENE_GENDER', [
    1   =>  '男',
    2   =>  '女'
]);

define('MOBILE', '/^1[34578]\d{9}$/');
define('EMAIL', '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/');


// 预定义过滤规则: 仅排除构造方法
define('FILTER_CONST', ['__construct']);

// 预定义过滤规则: 排除通用控制器方法
define('FILTER_CONTROL', [
    '_initialize',
    'beforeAction',
    'fetch',
    'display',
    'assign',
    'engine',
    'validateFailException',
    'validate',
    'success',
    'error',
    'result',
    'redirect',
    'getResponseType',
    '__construct'
]);


/**
 * 返回网站基础设置
 * @return array
 */
function getSiteSetting()
{
    return \think\Config::get('site');
}

/**
 * 获取类中的所有方法
 * @param $class string | object    要获取的类名或者已实例化的对象
 * @param $onlySelf boolean         是否只获取自身的方法(排除继承的方法)
 * @param $filter mixed             过滤结果
 * @param $customFilter array       自定义过滤规则 FILTER_CONST|FILTER_CONTROL
 * @throws Exception
 * @return array
 */
function getClassMethods($class, $onlySelf = false, $filter = null, $customFilter = ['__construct'])
{
    $r = new ReflectionClass($class);


    if ($filter !== null)
        $methods = $r->getMethods($filter);
    else
        $methods = $r->getMethods();


    $formatted = [];
    foreach ($methods as $method) {
        if ((($onlySelf && $method->class == $r->getName()) || !$onlySelf) && !in_array($method->name, $customFilter)) {
            $formatted[] = $method->name;
        }
    }


    return $formatted;
}


/**
 * 获取认证类
 * @return \auth\RBAC
 */
function getAuthClass()
{
    return new \auth\RBAC();
}


/**
 * @param $collection   mixed
 * @return array
 */
function collectionToArray($collection)
{
    return collection($collection)->toArray();
}


/**
 * 获取对应的视图模型
 * @param $model
 * @return \app\common\ViewModel
 */
function getViewModel($model)
{
    /** @var \app\common\ViewModel $fullName */
    $fullName = 'app\\' . \think\Request::instance()->module() . '\\' . \think\Config::get('viewModel')['path'] . '\\' . $model;

    return $fullName::instance();
}


/**
 * 获取视图内容
 * @param $component    string      组件名称或路径
 * @param $data         array       数据如: ['arr', [1, 2, 3]] => assign('arr', [1,2,3])
 * @throws Exception
 * @return null|string
 */
function getViewContent($component, $data = null)
{
    $component = \think\Loader::parseName($component, 1);
    /** @var \app\common\component\Component $class */
    $class = "app\\common\\component\\${component}\\${component}";
    if (!class_exists($class)) {
        return null;
    }
    return $class::getContent($data);
}


/**
 * 获取视图组件
 * @param $component
 * @return \app\common\component\Component|\app\common\component\FormComponent|\app\common\component\SearchComponent|null
 */
function getViewComponent($component)
{
    $component = \think\Loader::parseName($component, 1);
    /** @var \app\common\component\Component|\app\common\component\FormComponent $class */
    $class = "app\\common\\component\\${component}\\${component}";
    if (!class_exists($class)) {
        return null;
    }

    return $class;
}


/**
 * 编码数组键名, 去除符号
 * @param array $array
 * @return array
 */
function encodeArray(array $array)
{
    $ret = [];
    foreach ($array as $key => $val) {
        $ret[encode($key)] = $val;
    }

    return $ret;
}


/**
 * 解码数组键名, 还原到原始数组
 * @param array $array
 * @return array
 */
function decodeArray(array $array)
{
    $ret = [];
    foreach ($array as $key => $val) {
        $ret[decode($key)] = $val;
    }
    return $ret;
}


function encode(string $str)
{
    return str_replace('=', '', base64_encode($str), $count) . $count;
}


function decode(string $str)
{

    $count = substr($str, strlen($str) - 1);
    return base64_decode(substr($str, 0, strlen($str) - 1) . str_repeat('=', $count));
}


function getMenuView()
{
    return \app\common\component\Menu\Menu::getContent(['list' => \app\common\model\Menu::fetch(), 'deep' => 1]);
}


function dp($val)
{
    dump($val);
    exit;
}

/**
 * 获取系统信息
 * @return mixed
 */
function getSystemState()
{
    $state['os'] = PHP_OS;
    $state['cwd'] = getcwd();
    $state['root_path'] = $state['os'] == 'WINNT' ? explode(DS, $state['cwd'])[0] : '/';
    $state['disk_free_space'] = disk_free_space($state['root_path']) / 1024 / 1024 / 1024;
    $state['disk_total_space'] = disk_total_space($state['root_path']) / 1024 / 1024 / 1024;


    return $state;
}


/**
 * 校验 token
 * @param \Lcobucci\JWT\Token $token
 * @return bool
 */
function checkToken (\Lcobucci\JWT\Token $token)
{
    $config = \think\Config::get('jwt');
    if (!$token->verify(new $config['signer'], $config['apikey'])){
        return false;
    } elseif ($token->isExpired()) {
        return false;
    }

    return true;
}


/**
 * 创建 token
 * @param array     $data       自定义内容
 * @param Closure   $closure    闭包处理 Token 实例
 * @return string
 */
function createToken (array $data, Closure $closure = null)
{
    $config = \think\Config::get('jwt');
    $jwt = new Lcobucci\JWT\Builder();
    $jwt->setIssuer($config['issuer'])
        ->setIssuedAt(time())
        ->setNotBefore(time())
        ->setExpiration(time() + $config['expire']);

    foreach ($data as $key => $value) {
        $jwt->set($key, $value);
    }

    if ($closure) {
        $closure($jwt);
    }

    $jwt->sign(new $config['signer'], $config['apikey']);

    return $config['rsa'] ? RsaEncrypt((string) $jwt->getToken(), true) : (string) $jwt->getToken();
}


/**
 * RSA 加密字符串
 * @param $str string       要加密的字符床
 * @param bool $base64      是否返回 base64 编码
 * @return string
 */
function RsaEncrypt (string $str, bool $base64 = false)
{
    $config = \think\Config::get('rsa');
    $rsa = new \phpseclib\Crypt\RSA();

    $rsa->loadKey(file_get_contents($config['path'] . DS . $config['privateKeyFile']));
    $rsa->setPrivateKeyFormat(\phpseclib\Crypt\RSA::PRIVATE_FORMAT_PKCS1);
    $rsa->setEncryptionMode(\phpseclib\Crypt\RSA::ENCRYPTION_PKCS1);

    return $base64 ? base64_encode($rsa->encrypt($str)) : $rsa->encrypt($str);
}


/**
 * RSA 解密
 * @param string $str       待解密的字符串
 * @param bool $base64      是否 base64 编码
 * @return string
 */
function RsaDecrypt (string $str, bool $base64 = false)
{
    $config = \think\Config::get('rsa');
    $rsa = new \phpseclib\Crypt\RSA();

    $rsa->loadKey(file_get_contents($config['path'] . DS . $config['publicKeyFile']));
    $rsa->setPublicKeyFormat(\phpseclib\Crypt\RSA::PUBLIC_FORMAT_PKCS1);
    $rsa->setEncryptionMode(\phpseclib\Crypt\RSA::ENCRYPTION_PKCS1);

    return $base64 ? $rsa->decrypt(base64_decode($str)) : $rsa->decrypt($str);
}


/**
 * 数字转字母
 * @param $num
 * @return bool|string
 */
function numToLetter($num) {
    $num = intval($num) + 1;
    if ($num <= 0) return false;
    $letterArr = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
    $letter = '';
    do {
        $key = ($num - 1) % 26;
        $letter = $letterArr[$key] . $letter;
        $num = floor(($num - $key) / 26);
    } while ($num > 0);
    return $letter;
}


function cellIndex ($cell) {
    preg_match('/(?<col>[A-Z]+)(?<row>\d+)/', $cell, $rs);
    return [
        'column' => $rs['col'],
        'row' => $rs['row']
    ];
}

function setSuccess($info='',$data=array()){
	$return = array(
		'status' => 1,
		'msg'	 => $info,
		'data'	 => $data
	);
	echo json_encode($return);
	exit;
}

function setError($info='',$data=array()){
	$return = array(
		'status' => 0,
		'msg'	 => $info,
		'data'	 => $data
	);
	echo json_encode($return);
	exit;
}
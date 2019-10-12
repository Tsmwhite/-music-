<?php
/**
 * Created by PhpStorm.
 * User: chenlongmingob@outlook.com
 * Date: 2018/11/5
 * Time: 20:22
 */

namespace app;


use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Token;
use think\Config;
use think\Request;
use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local;

class ApiController extends \think\Controller
{
    /** @var Token */
    public $token = null;
    public $role = 'student';
    public $token_val = null;
    public function __construct(Request $request = null)
    {
        header('Access-Control-Allow-Origin: *');

        $tokenStr = $request->header('Authorization');
        $this->role = $request->header('Role');
        $this->token_val = $tokenStr;
        #if($this->role != 'student' && $this->role != 'teacher'){
         #   echo json_encode(array('msg'=>'role error','err'=>'10041','errCode'=>0,'errType'=>'exception'));exit;
        #}
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
        parent::__construct($request);


        // 检查是否有匹配的插件
        $fs = new Filesystem(new Local(APP_PATH . DS . 'plugins'));
        $route = json_decode($fs->read('route.json'), true);

        if ($route) {
            foreach ($route as $url => $pluginName) {
                if (strtolower($this->request->path()) === strtolower($url)) {
                    $module = $this->request->module();
                    $controller = $this->request->controller();
                    $action = $this->request->action();
                    $class = "\\app\\plugins\\${pluginName}\\${module}\\controller\\${controller}";
                    // 如果匹配插件定义的 url 返回插件的页面
                    exit(json((new $class($pluginName))->$action())->send());
                }
            }
        }
    }

}
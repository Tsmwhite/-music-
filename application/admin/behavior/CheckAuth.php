<?php
/**
 * Created by PhpStorm.
 * User: chenlongmingob@outlook.com
 * Date: 2018/10/18
 * Time: 13:50
 */

namespace app\admin\behavior;


use app\common\model\Menu;
use auth\exception\LoginException;
use think\Config;
use think\Request;

class CheckAuth
{

    public function run ()
    {
        $request = Request::instance();
        $controller = strtolower($request->controller());
        $action = strtolower($request->action());


        // 当前请求是否处于公开的请求列表中 config.php: authFilters
        foreach (Config::get('authFilter') ?? [] as $filter) {
            $m = explode('/', $filter);
            if ($controller === strtolower($m[0]) || $m[0] === '*') {
                if (empty($m[1]) || $action === strtolower($m[1]) || $m[1] === '*') {
                    return;
                }
            }
        }

        // 验证当前请求权限
        $err = getAuthClass()->checkAccess();
        if (!empty($err)) throw $err;
        return;
    }
}
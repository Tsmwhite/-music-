<?php
/**
 * Created by PhpStorm.
 * User: chenlongmingob@outlook.com
 * Date: 2018/10/18
 * Time: 15:52
 */

namespace auth;


use app\common\model\Node;
use app\common\model\Role;
use app\common\model\User;
use auth\exception\AuthException;
use auth\exception\LoginException;
use think\Cookie;
use think\Loader;
use think\Request;

class RBAC
{
    /** @var string $sessionUserKey 用户信息的 session name */
    protected static $sessionUserKey = 'rbac_user';

    /** @var string $sessionNodeKey 权限节点的 session name */
    protected static $sessionNodeKey = 'rbac_node';


    /**
     * 检查当前节点是否有访问权限
     * @return AuthException|LoginException|null
     */
    public function checkAccess()
    {
        // 是否登陆
        if (!$this->checkAuth()) {
            return new LoginException();
        }

        // 检查是否有权访问当前节点
        $res = Request::instance();

        // 统一风格
        $module = Loader::parseName($res->module());
        $controller = Loader::parseName($res->controller());
        $action = Loader::parseName($res->action());

        $nodes = collection(session(static::$sessionNodeKey) ?? $this->getAccessNodes())->toArray();


        foreach ($nodes as $node) {
            if ($module === Loader::parseName($node['module']) &&
                $controller === Loader::parseName($node['controller']) &&
                $action === Loader::parseName($node['action'])) {
                $in_node = true;
            }
        }


        if (empty($in_node)) {
            return new AuthException('无权操作');
        }

        return null;
    }


    /**
     * 获取允许访问的节点
     * @param null $uid
     * @param bool $cache
     * @return array|mixed
     */
    public function getAccessNodes($uid = null, $cache = true)
    {
        $result = session(static::$sessionNodeKey);

        if (!empty($result) && $cache) {
            return $result;
        } else {
            $user = session(static::$sessionUserKey);
            if ($uid === null) $uid = $user['id'] ?? null;

            // 获取用户角色对应的权限
            $rid = $user['rid'] ?? Role::getUserRole($uid)['id'] ?? null;
            $role_node = Node::getRoleNode($rid) ?? [];

            // 获取用户对应的权限
            $admin_node = Node::getUserNode($uid) ?? [];

            // 合并权限
            $node = array_unique(array_merge($role_node, $admin_node));
            session(static::$sessionNodeKey, $node);


            return $node;
        }

    }


    /**
     * 获取认证信息
     * @param null $params
     * @return array|mixed
     * @throws LoginException
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function setAuthInfo($params = null)
    {
        if (empty($params)) $params = Request::instance()->param();
        $result = (new User())
            ->join('admin_role', 'admin.id=admin_role.uid', 'left')
            ->join('role', 'admin_role.rid=role.id', 'left')
            ->field('admin.*, role.name as role_name, rid')
            ->where([
                'admin.username' => $params['username'],
                'admin.password' => User::pwdEncode($params['password'])
            ])
            ->find();

        if (empty($result))
            throw new LoginException('用户名或密码错误');


        $auth_info = $result->toArray();

        session(static::$sessionUserKey, $auth_info);

        $this->getAccessNodes($auth_info['id'], false);

        Cookie::set('auth_token', createToken($auth_info));
        return $auth_info;
    }



    public function resetAuthInfo ()
    {
        $user = session(self::$sessionUserKey);
        if (empty($user))
            throw new LoginException('请先登录');

        $rs = (new User())
            ->join('admin_role', 'admin.id=admin_role.uid', 'left')
            ->join('role', 'admin_role.rid=role.id', 'left')
            ->field('admin.*, role.name as role_name, rid')
            ->where([
                'admin.id' => $user['id'],
            ])
            ->find();

        session(self::$sessionUserKey, $rs->toArray());

        $this->getAccessNodes($rs['id'], false);
    }


    /**
     * 获取认证信息
     * @return mixed
     */
    public function getAuthInfo()
    {
        return session(static::$sessionUserKey);
    }


    /**
     * @throws LoginException
     */
    public function removeAuth()
    {
        session(static::$sessionUserKey, null);
        session(static::$sessionNodeKey, null);


        Cookie::set('auth_token', null);
        throw new LoginException('退出登陆');
    }


    public function checkAuth()
    {
        $user = session(static::$sessionUserKey);
        if (empty($user)) {
            return false;
        }

        return $user;
    }
}
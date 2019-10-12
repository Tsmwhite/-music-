<?php
/**
 * Created by PhpStorm.
 * User: chenlongmingob@outlook.com
 * Date: 2018/10/22
 * Time: 14:37
 */

namespace app\common\model;



class Node extends Model
{

    /**
     * 获取角色权限节点
     * @param $rid
     * @return false|\PDOStatement|string|\think\Collection
     */
    public static function getRoleNode ($rid)
    {
        return (new static())
            ->join('role_node', 'role_node.nid=node.id')
            ->where('role_node.rid', 'eq', $rid)
            ->field('node.*')
            ->select();
    }


    /**
     * 获取用户权限节点
     * @param $uid
     * @return false|\PDOStatement|string|\think\Collection
     */
    public static function getUserNode ($uid)
    {
        return (new static())
            ->join('admin_node', 'admin_node.nid=node.id')
            ->where('admin_node.uid', 'eq', $uid)
            ->field('node.*')
            ->select();
    }

    /**
     * 添加节点
     * @param $module
     * @param $controller
     * @param $action
     * @return bool
     */
    public static function add ($module, $controller, $action)
    {
        $db = new static();

        $data = ['module' => $module, 'controller' => $controller, 'action' => $action];

        if (empty($db->where($data)->find())) {
            $db->insert($data);
        }

        return true;
    }
}
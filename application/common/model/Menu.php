<?php
/**
 * Created by PhpStorm.
 * User: chenlongmingob@outlook.com
 * Date: 2018/10/22
 * Time: 10:44
 */

namespace app\common\model;



class Menu extends Model
{
    /**
     * 获取允许访问的菜单列表
     * @return array
     * @throws \think\exception\DbException
     */
    public static function getAccessMenus ()
    {
        $nodes = getAuthClass()->getAccessNodes();
        $m = new self();
        $allMenus = $m->join('node', 'node.id = menu.nid', 'left', 'full')->select();
        $accessMenus = [];
        foreach ($allMenus as $menu) {
            if (empty($menu['menu_nid'])) {
                $accessMenus[] = $menu;
            } else {
                foreach ($nodes as $node) {
                    if ($node['id'] == $menu['menu_nid']) $accessMenus[] = $menu;
                }
            }

        }

        return $accessMenus;
    }


    /**
     * 获取格式化后的多级菜单
     * @param array|null $menus
     * @param int $pid
     * @return array
     * @throws \think\exception\DbException
     */
    public static function fetch (array $menus = null, $pid = 0)
    {
        if (is_array($menus) && count($menus) === 0) return null;
        if ($menus === null) $menus = self::getAccessMenus();
        $formatted = [];
        foreach ($menus as $key => $menu) {
            if ($menu['menu_pid'] == $pid) {
                unset($menus[$key]);

                $menu['child_menu'] = self::fetch($menus, $menu['menu_id']);

                // 无子菜单, 且无对应节点的菜单将不会展示
                if (!(empty($menu['child_menu']) && empty($menu['menu_nid']))) {
                    $formatted[] = $menu;
                }
            }
        }

        return \collection($formatted)->toArray();
    }
}
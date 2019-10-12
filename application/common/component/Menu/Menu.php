<?php
/**
 * Created by PhpStorm.
 * User: chenlongmingob@outlook.com
 * Date: 2018/11/16
 * Time: 18:41
 */

namespace app\common\component\Menu;


use app\common\component\Component;
use think\Loader;
use think\Request;

class Menu extends Component
{
    public static function getContent($data = null)
    {
        // 排序 order desc, id asc
        foreach ($data['list'] as $key => $row) {
            $ids[$key] = $row['menu_id'];
            $order[$key] = $row['menu_order'];
        }

        $res = Request::instance();

        $data['m'] = Loader::parseName($res->module());
        $data['c'] = Loader::parseName($res->controller());
        $data['a'] = Loader::parseName($res->action());

        if (!empty($ids) && !empty($order)) {
            array_multisort( $order, SORT_DESC, SORT_NUMERIC, $ids, SORT_ASC, SORT_NUMERIC, $data['list']);
        }
        return parent::getContent($data);
    }
}
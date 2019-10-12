<?php
/**
 * Created by PhpStorm.
 * User: chenlongmingob@outlook.com
 * Date: 2018/11/26
 * Time: 10:52
 */

namespace app\common\component\Filter;

use think\Request;
use think\View;
use app\common\component\Component;

class Filter extends Component
{
    public static function getContent($data = null)
    {
        $params = Request::instance()->param() ?? [];
        foreach ($data as &$item) {
            $item['url'] = url('index', array_merge($params, encodeArray($item['params'])));
        }
        return View::instance()->assign('list', $data)->fetch(self::getTemplate());
    }
}
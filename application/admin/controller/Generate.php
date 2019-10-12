<?php
/**
 * Created by PhpStorm.
 * User: chenl
 * Date: 2018/10/18
 * Time: 10:31
 */

namespace app\admin\controller;


use app\Controller;
use think\Db;

class Generate extends Controller
{
    public function index ()
    {
        $tables_in_db = Db::query('show tables;');

        foreach ($tables_in_db as $item) {
            $tables[] = array_values($item)[0];
        }

        !empty($tables) or $tables = [];

        return $this->assign([
            'tables' => $tables
        ])->fetch();
    }
}
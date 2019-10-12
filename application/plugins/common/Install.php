<?php
/**
 * Created by PhpStorm.
 * User: chenlongmingob@outlook.com
 * Date: 2018/12/19
 * Time: 13:49
 */

namespace app\plugins\common;



use think\db\Query;

interface Install
{
    // 已生成 install.lock.json 文件
    public static function lock ();


    /**
     * 设置数据库
     * @param Query $query
     */
    public static function set_database ($query);


    // 刷新 json 文件
    public static function refresh_json ();


    // 清理缓存, 删除锁, 完成安装
    public static function complate ();


    /**
     * 安装失败的回滚操作
     * @param Query $query
     */
    public static function rollback ($query);
}
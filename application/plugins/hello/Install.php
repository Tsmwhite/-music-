<?php
/**
 * Created by PhpStorm.
 * User: chenlongmingob@outlook.com
 * Date: 2018/12/20
 * Time: 13:17
 */

namespace app\plugins\hello;


use app\plugins\common\Cli;
use think\db\Query;

class Install implements \app\plugins\common\Install
{
    protected static $time;

    public static function lock()
    {
        self::$time = time();
        Cli::$climate->out('lock.json 已生成');
    }

    public static function refresh_json()
    {
        Cli::$climate->out('重新生成 plugins.json, route.json');
    }

    /**
     * @param Query $query
     * @throws \think\db\exception\BindParamException
     * @throws \think\exception\PDOException
     */
    public static function set_database($query)
    {
        $query->execute('DROP TABLE IF EXISTS `hello`;');
        $query->execute('CREATE TABLE `hello`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `update_time` datetime(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;');
        $query->execute('INSERT INTO `hello` VALUES (1, \'123\', \'1234\', \'2018-12-19 16:17:23\');');

        Cli::$climate->out('创建数据库成功');
    }


    public static function complate()
    {
        $long = (time() - self::$time) / 1000;
        Cli::$climate->out("安装成功, 用时 ${long}s.");
    }

    public static function rollBack($query)
    {
    }
}
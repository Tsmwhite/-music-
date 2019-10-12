<?php
/**
 * Created by PhpStorm.
 * User: chenlongmingob@outlook.com
 * Date: 2018/10/18
 * Time: 14:39
 */

// 应用行为扩展定义文件
return [
    // 应用初始化
    'app_init'     => [],
    // 应用开始
    'app_begin'    => [],
    // 模块初始化
    'module_init'  => [
        '\\app\\admin\\behavior\\CheckAuth' // 身份认证
    ],
    // 操作开始执行
    'action_begin' => [],
    // 视图内容过滤
    'view_filter'  => [],
    // 日志写入
    'log_write'    => [],
    // 应用结束
    'app_end'      => [],
];
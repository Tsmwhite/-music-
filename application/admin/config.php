<?php
//配置文件
return [
    'auth'  =>  '\\auth\\RBAC',            // 认证方式
    'authFilter'   =>  [                   // 公开的控制器，方法 格式: controller/action  "*" 为通配符
        'pub',
        '*/import'
    ]
];
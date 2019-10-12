<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:65:"C:\htdocs\yishu\public/../application/admin\view\index\index.html";i:1570550775;s:20:"./template/base.html";i:1570632108;}*/ ?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <title><?php echo $title ?? getSiteSetting()['title']; ?></title>
    <link rel="stylesheet" href="/static/common/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/static/common/layui/css/layui.css">
    <link rel="stylesheet" href="/static/css/admin.css">
    <!--检查是否为IE浏览器-->
    <script type="text/javascript">
        var userAgent = navigator.userAgent;
        if (userAgent.indexOf("compatible") > -1 && userAgent.indexOf("MSIE") > -1) {
            // IE 浏览器版本小于等于 10
            alert('不支持IE11以下版本的浏览器');
        }

        if (userAgent.indexOf('Trident') > -1 && userAgent.indexOf("rv:11.0") > -1) {
            // IE 11
        }
    </script>
    <script type="text/javascript" src="/static/js/polyfill.min.js"></script>
    <script type="text/javascript" src="/static/common/axios/axios.min.js"></script>
    <script type="text/javascript" src="/static/js/alert.js"></script>
    <script type="text/javascript" src="/static/common/layui/layui.js"></script>
    <script type="text/javascript" src="/static/common/ckeditor/translations/zh-cn.js"></script>
    <script type="text/javascript" src="/static/common/ckeditor/ckeditor.js"></script>
    <script type="text/javascript" src="/static/common/js.cookie.js"></script>
    <script type="text/javascript" src="/static/common/echarts/echarts.min.js"></script>
    <script type="text/javascript" src="/static/js/common.js"></script>

    
</head>
<body>
<div class="header">
    <ul class="layui-nav">
        <li class="logo-wp">
            <img class="nav-logo" src="<?php echo getSiteSetting()['logo']; ?>">
            <p class="site-name"><?php echo getSiteSetting()['title']; ?></p>
        </li>

        <li class="layui-nav-item" title="编辑用户信息">
            <a href="#">
                <img src="<?php echo $user['avatar']; ?>" class="layui-nav-img">
                <?php echo $user['username']; ?>
            </a>
        </li>

        <li class="layui-nav-item">
            <a href="/admin/pub/logout" onclick="return confirm('确定要退出当前用户吗?')">
                退出登陆
            </a>
        </li>
    </ul>
</div>
<div class="main-panel">
    <div class="left-nav">

        <!-- 生成菜单 -->
        <?php echo getMenuView(); ?>

    </div>
    <div class="content">
        <div class="container-fluid">
            

<div class="card">
    <div class="card-header">系统信息</div>
    <div class="card-body">
        <div class="row">
            <div class="col-3">
                <p class="card-title">平台</p>
                <p class="card-text"><?php echo isset($os)?$os: 'unknow'; ?></p>
            </div>

            <div class="col-3">
                <p class="card-title">系统目录</p>
                <p class="card-text"><?php echo isset($cwd)?$cwd: 'unknow'; ?></p>
            </div>

            <div class="col-3">
                <p class="card-title">系统根目录</p>
                <p class="card-text"><?php echo isset($root_path)?$root_path: 'unknow'; ?></p>
            </div>

            <div class="col-3">
                <p class="card-title">硬盘使用情况</p>
                <p class="card-text" style="font-weight: bold">
                    <a class="text-info" href="javascript:void(0);"><?php echo round($disk_total_space - $disk_free_space, 2) ?? '0'; ?>GB</a>
                    /
                    <a class="text-dark"><?php echo round($disk_total_space, 2) ?? '0'; ?>GB</a>
                </p>

                <p class="card-text">
                    剩余:
                    <a href="javascript: void(0);" class="text-info font-weight-bold"><?php echo round($disk_free_space, 2); ?>GB</a>
                </p>
            </div>
        </div>

    </div>
</div>


        </div>
    </div>
</div>


<!-- 图片加载失败处理 -->
<script type="text/javascript">
    elementMap('img', function (i) {
        i.addEventListener('error', function (e) {
            this.src = "/static/img/image_not_found.jpg";
            this.classList.add('border-danger', 'img-failed');
            this.title = '无法找到该图片';
        }, false);
    });
</script>

<!-- 自定义 js -->

</body>
</html>
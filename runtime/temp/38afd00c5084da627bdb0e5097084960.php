<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:83:"C:\htdocs\yishu\public/../application/common\component\SearchInput/SearchInput.html";i:1568192927;s:83:"C:\htdocs\yishu\public/../application/common\component\TableButton/TableButton.html";i:1568192928;s:64:"C:\htdocs\yishu\public/../application/admin\view\role\index.html";i:1568192921;s:20:"./template/base.html";i:1570632108;}*/ ?>
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
            


<div class="card search-card">

    <div class="card-body">
        <form>
            <div class="row">
                <?php echo $search; ?>
                <div class="search-btn-wrap">
                    <button class="btn btn-info text-right">搜索</button>
                </div>
            </div>

        </form>
    </div>
</div>

<div class="alert">
    <div class="row flex-vertical">
        <div class="col-sm-3 text-muted small">总计: <?php echo isset($count)?$count: 0; ?> 条记录</div>
        <div class="col-sm-9 text-right">
            <?php if($showExportButton): ?>
            <a class="btn btn-info btn-sm" href="<?php echo url('export', $request->param()); ?>">导出</a>
            <?php endif; ?>
            <a class="btn btn-success text-right btn-sm" href="<?php echo url('add'); ?>">添加</a>
        </div>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-hover col-nowrap" data-muti>
        <thead>
        <tr>
            <!-- 表头 -->
            <?php echo $tableHead; ?>
            <td>操作</td>
        </tr>
        </thead>
        <tbody>
        <?php echo $tableBody; ?>
        </tbody>
    </table>
</div>

<?php echo $page; ?>

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
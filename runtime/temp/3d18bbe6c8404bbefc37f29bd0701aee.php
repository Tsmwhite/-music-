<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:83:"C:\htdocs\yishu\public/../application/common\component\TableButton/TableButton.html";i:1568192928;s:67:"C:\htdocs\yishu\public/../application/admin\view\classss\index.html";i:1568192921;s:48:"C:\htdocs\yishu\application\admin\view\base.html";i:1570550651;}*/ ?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <title>222<?php echo $title ?? getSiteSetting()['title']; ?></title>
    <link rel="stylesheet" href="/yishu/public/static/common/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/yishu/public/static/common/layui/css/layui.css">
    <link rel="stylesheet" href="/yishu/public/static/css/admin.css">
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
    <script type="text/javascript" src="/yishu/public/static/js/polyfill.min.js"></script>
    <script type="text/javascript" src="/yishu/public/static/common/axios/axios.min.js"></script>
    <script type="text/javascript" src="/yishu/public/static/js/alert.js"></script>
    <script type="text/javascript" src="/yishu/public/static/common/layui/layui.js"></script>
    <script type="text/javascript" src="/yishu/public/static/common/ckeditor/translations/zh-cn.js"></script>
    <script type="text/javascript" src="/yishu/public/static/common/ckeditor/ckeditor.js"></script>
    <script type="text/javascript" src="/yishu/public/static/common/js.cookie.js"></script>
    <script type="text/javascript" src="/yishu/public/static/common/echarts/echarts.min.js"></script>
    <script type="text/javascript" src="/yishu/public/static/js/common.js"></script>

    
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
            

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo url('admin/index/index'); ?>">首页</a></li>
            <li class="breadcrumb-item active" aria-current="page">课程管理</li>
        </ol>
    </nav>

    <?php if($search): ?>
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
    <?php endif; ?>
    <div class="alert">
        <div class="row flex-vertical">
            <div class="col-sm-3 text-muted small">总计: <?php echo isset($count)?$count: 0; ?> 条记录</div>
            <div class="col-sm-9 text-right">
                <?php if($showImportForm): ?>
                    <form class="form import-form"
                          enctype="multipart/form-data"
                          action="<?php echo url('import'); ?>"
                          method="POST">
                        <input type="file" class="files" name="files" style="width: 180px;" required>
                        <button type="submit" class="btn btn-info text-right btn-sm">提交</button>
                    </form>
                <?php endif; if($showExportButton): ?>
                    <a class="btn btn-info btn-sm" href="<?php echo url('export', $request->param()); ?>">导出</a>
                <?php endif; ?>
                <a class="btn btn-success btn-sm" href="<?php echo url('add'); ?>">添加</a>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover col-nowrap" data-muti>
            <thead>
            <tr>
                <!-- 表头 -->
                <?php echo $tableHead; ?>
                <th>操作</th>
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

    <?php if($tree): ?>
        <script type="text/javascript">

            var th = document.createElement('th');
            th.classList.add('tree-th');

            var td = document.createElement('td');

            var theadRow = document.querySelector('thead tr');

            theadRow.insertBefore(th, theadRow.children[0]);

            elementMap('tbody tr', function (i) {
                var tree = td.cloneNode();

                // 方案一 :
                tree.style.cursor = 'pointer';
                tree.style.fontWeight = 'bolder';
                tree.style.fontSize = '20px';
                i.insertBefore(tree, i.children[0]);
                var level = parseInt(i.dataset.level);

                tree.style.paddingLeft = (level + 1) * 30 + 'px';
                if (level !== 0) {
                    i.style.display = 'none';
                }

                if (document.querySelectorAll('[data-parent="' + i.dataset.self + '"]').length) {
                    tree.innerText = '+';
                    tree.addEventListener('click', function () {
                        if (this.innerText === '+') {
                            this.innerText = '-';
                            showNextRow(this.parentElement.dataset.self);
                        } else {
                            this.innerText = '+';
                            hideNextRow(this.parentElement.dataset.self);
                        }
                    });
                } else {
                    tree.innerText = "|";
                }
            });

            function showNextRow(parent) {
                elementMap('tr', function (i) {
                    if (i.dataset.parent === parent) {
                        i.style.display = 'table-row';
                    }
                })
            }


            function hideNextRow(parent) {
                elementMap('tr', function (i) {

                    console.log(i.dataset.level, parent);
                    if (i.dataset.parent === parent) {
                        i.style.display = 'none';

                        if (i.childNodes[0].innerText === "-") {
                            i.childNodes[0].innerText = '+';
                            hideNextRow(i.dataset.self);
                        }
                    }


                })
            }

        </script>
    <?php endif; ?>

</body>
</html>
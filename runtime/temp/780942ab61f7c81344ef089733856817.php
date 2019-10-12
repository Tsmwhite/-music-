<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:63:"C:\htdocs\yishu\public/../application/admin\view\pub\login.html";i:1570550581;}*/ ?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <title><?php echo $title ?? getSiteSetting()['title']; ?></title>
    <link rel="stylesheet" href="/static/common/layui/css/layui.css">
    <link rel="stylesheet" href="/static/common/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/static/css/login.css">
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
    <script type="text/javascript" src="/static/common/layui/layui.js"></script>
    <script type="text/javascript" src="/static/common/js.cookie.js"></script>
    <script type="text/javascript" src="/static/js/login.js"></script>
</head>
<body>

    <div class="container form-container">
        <form method="post">
            <div class="form-group">
                <label for="username">用户名</label>
                <input type="text" id="username" name="username" class="form-control" placeholder="用户名" required>
            </div>
            <div class="form-group">
                <label for="password">密码</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="密码" required>
            </div>
            <div class="form-group">
                <label for="captcha">验证码</label>
                <div class="row captcha-row">
                    <div class="col-6 captcha">
                        <?php echo captcha_img(); ?>
                    </div>
                    <div class="col-6">
                        <input type="text" id="captcha" name="captcha" class="form-control" placeholder="验证码" required>
                    </div>
                </div>
            </div>
            <div class="form-group text-right">
                <button type="submit" class="btn btn-success layui-btn-fluid">登陆</button>
            </div>
        </form>
    </div>

</body>
</html>
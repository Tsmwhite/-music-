function loaded () {
    if (!window.layloaded || !window.isLoaded) return false;
    // 表单自动获得焦点
    document.querySelector('input').focus();

    // 验证码刷新
    document.querySelector('.captcha > img').addEventListener('click', function (e) {
        var url = this.src;
        this.src = url + '?' + parseInt(Math.random() * 10000);
    }, false);

    // 提示错误信息
    var loginMsg = Cookies.get('login_msg');
    if (loginMsg) {
        layer.msg(loginMsg);
        Cookies.remove('login_msg');
    }
}

layui.use(['layer'], function () {
    window.laydate = layui.laydate;
    window.layer = layui.layer;
    window.element = layui.element;

    window.layloaded = true;

    loaded();
});


window.onload = function () {
    window.isLoaded = true;

    loaded();
};
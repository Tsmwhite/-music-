<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:68:"C:\htdocs\yishu\public/../application/admin\view\generate\index.html";i:1568192923;s:48:"C:\htdocs\yishu\application\admin\view\base.html";i:1570632080;}*/ ?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <title>222<?php echo $title ?? getSiteSetting()['title']; ?></title>
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

    

<style type="text/css">
    .row {
        margin-bottom: 1rem;
    }

    label {
        margin-bottom: 0;
    }

    th, td {
        white-space: nowrap;
        overflow: hidden;
    }

    td > input.form-control-sm {
        min-width: 5rem;
    }
</style>


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
            

<div id="app">
    <div class="form-group">
        <label for="table">选择一个表</label>
        <hr>
        <select name="table" class="form-control" id="table" @change="getTableFields" v-model="table">
            <option value="__unset">请选择</option>
            <?php if(is_array($tables) || $tables instanceof \think\Collection || $tables instanceof \think\Paginator): $i = 0; $__LIST__ = $tables;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
            <option><?php echo $vo; ?></option>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </select>
    </div>

    <hr>

    <div class="form" v-if="fields && fields.length">
        <div class="row">
            <div class="col-3 flex-vertical text-nowrap">
                <label for="controller">控制器</label>
                <input placeholder="控制器" v-model="controller" class="form-control" id="controller">
            </div>

            <div class="col-3 flex-vertical text-nowrap">
                <label for="controllerName">控制器名称(展示名)</label>
                <input placeholder="控制器名称" v-model="controllerName" class="form-control" id="controllerName">
            </div>

            <div class="col-3 flex-vertical text-nowrap">
                <input v-model="create_node" type="checkbox" id="node">
                <label for="node">创建权限节点</label>
            </div>
        </div>
        <hr>
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>字段名</th>
                    <th>表格组件</th>
                    <th>表单组件</th>
                    <th>显示名</th>
                    <th>添加</th>
                    <th>添加规则</th>
                    <th>编辑</th>
                    <th>编辑规则</th>
                    <th>导出</th>
                    <th>导入</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(field, index) in fields">
                    <td>
                        <input class="form-control form-control-sm" placeholder="字段名" v-model="field.field" :id="'f' + index">
                    </td>
                    <td>
                        <input class="form-control form-control-sm" placeholder="表格组件" v-model="field.tc" :id="'tc' + index">
                    </td>
                    <td>
                        <input class="form-control form-control-sm" placeholder="表单组件" v-model="field.fc" :id="'fc' + index">
                    </td>
                    <td>
                        <input class="form-control form-control-sm" placeholder="显示名" v-model="field.name" :id="'fn' + index">
                    </td>
                    <td>
                        <input class="custom-checkbox" type="checkbox" :id="field.field.toLowerCase() + '_add'" v-model="field.add">
                        <label :for="field.field.toLowerCase() + '_add'"></label>
                    </td>
                    <td>
                        <input class="form-control form-control-sm" placeholder="添加规则" v-model="field.add_rule">
                    </td>
                    <td>
                        <input class="custom-checkbox" type="checkbox" :id="field.field.toLowerCase() + '_edit'" v-model="field.update">
                        <label :for="field.field.toLowerCase() + '_edit'"></label>
                    </td>
                    <td>
                        <input class="form-control form-control-sm" placeholder="编辑规则" v-model="field.update_rule">
                    </td>
                    <td>
                        <input class="custom-checkbox" type="checkbox" :id="field.field.toLowerCase() + '_export'" v-model="field.export">
                        <label :for="field.field.toLowerCase() + '_export'"></label>
                    </td>
                    <td>
                        <input class="custom-checkbox" type="checkbox" :id="field.field.toLowerCase() + '_import'" v-model="field.import">
                        <label :for="field.field.toLowerCase() + '_import'"></label>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-danger" @click="rmField(index)">删除</button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <hr/>
        <div class="row">
            <div class="col-4 text-center offset-4">
                <button class="btn btn-outline-info btn-sm" @click="addField">新增字段</button>
            </div>
        </div>

        <hr>
        <table class="table">
            <thead>
            <tr>
                <th>字段名</th>
                <th>组件名(类型)</th>
                <th>模糊搜索</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(s, i) in search">
                <td>
                    <select class="form-control-sm form-control" :id="'sf' + i" v-model="s.field" @change="checkSearch($event, i)">
                        <option value="__unset">选择字段</option>
                        <option v-for="sf in fields">{{ sf.field }}</option>
                    </select>
                </td>
                <td>
                    <input class="form-control-sm form-control" placeholder="组件名(类型)" v-model="s.type" :id="'st' + i">
                </td>
                <td class="flex-vertical">
                    <input placeholder="模糊搜索" type="checkbox" v-model="s.like" :id="'sl' + i">
                    <label :for="'sl' + i">模糊搜索</label>
                </td>
                <td>
                    <button class="btn btn-danger btn-sm" @click="rmSearch(i)">删除</button>
                </td>
            </tr>
            </tbody>
        </table>

        <hr>

        <div class="row">
            <div class="col-4 text-center offset-4">
                <button class="btn btn-outline-info btn-sm" @click="addSearch">新增搜索字段</button>
            </div>
        </div>

        <hr>

        <div class="form-group">
            <button class="btn btn-success" @click="submit">提交</button>
            <button class="btn btn-warning" @click="getTableFields">重置</button>
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

<script src="/static/common/vue.js"></script>
<script type="text/javascript">

    var app = new Vue({
        el: '#app',
        data: {
            fields: [],
            controller: '',
            controllerName: '',
            table: '__unset',
            create_node: true,
            search: []
        },

        methods: {
            /**
             * 获取字段信息
             */
            getTableFields () {
                if (this.table === '__unset') {
                    this.controller = '';
                    this.fields = [];
                    this.search = [];
                    return;
                }

                var val = this.table;

                var loading = layer.load(2);
                this.controller = val;
                var _this = this;
                api.get('/api/generate/table_fields', {
                    params: {
                        name: val
                    }
                }).then(function (response) {
                    var data = response.data;
                    if (data.err) throw data.msg;

                    _this.fields = data.map(function (i) {
                        return {
                            field: i.Field,
                            tc: 'text',
                            fc: 'text',
                            name: null,
                            add: true,
                            add_rule: '',
                            update: true,
                            update_rule: '',
                            export: true,
                            import: true
                        };
                    });
                    layer.close(loading);
                }).catch(function (err) {
                    layer.close(loading);
                    layer.open({
                        icon: 2,
                        title: '请求失败',
                        content: err instanceof String ? err : err.toString()
                    });
                })
            },

            /**
             * 新增字段
             */
            addField () {
                this.fields.push({
                    field: '',
                    tc: 'text',
                    fc: 'text',
                    name: null,
                    add: true,
                    update: true,
                    export: true,
                    import: true
                });
            },

            /**
             * 删除字段
             * @param i
             */
            rmField (i) {
                this.fields.splice(i, 1);
            },


            submit () {
                if (this.table === '__unset') {
                    layer.tips('请选择数据表', '#table', {
                        tips: 1
                    });
                    throw 'invalid table name';
                }

                if (this.controller === '' || !this.controller) {
                    layer.tips('请填写控制器', '#controller', {
                        tips: 1
                    });
                    throw 'invalid controller name';
                }

                if (this.controllerName === '' || !this.controllerName) {
                    layer.tips('请填写控制器名称(展示名)', '#controllerName', {
                        tips: 1
                    });
                    throw 'invalid controller name';
                }

                this.fields.forEach(function (i, n) {
                    if (!i.field || i.field === '') {
                        layer.tips('请填写该字段', '#f' + n);
                        throw 'invalid fields';
                    }

                    if (!i.name || i.name === '') {
                        layer.tips('请填写该字段', '#fn' + n);
                        throw 'invalid fields';
                    }

                    if (!i.fc || i.fc === '') {
                        layer.tips('请填写该字段', '#fc' + n);
                        throw 'invalid fields';
                    }
                });

                var _this = this;

                api.get('/api/generate/run', {params: _this.$data})
                    .then(function (response) {
                        console.log(response.data);
                        if (response.data === true) {
                            layer.msg('success');
                        } else {
                            throw response.data.msg
                        }
                    })
                    .catch(function (err) {
                        console.warn(err);
                    });
            },

            addSearch () {
                this.search.push({field: '__unset', type: 'text', like: false})
            },

            rmSearch (i) {
                this.search.splice(i, 1);
            },

            checkSearch ($event, i) {
                var _this = this;
                this.search.forEach(function (s, x) {
                    if (s.field === $event.target.value && x !== i && s.field !== '__unset') {
                        _this.search[i].field = '__unset';
                        layer.msg('不能选择重复的字段');
                    }
                })
            }
        }
    });
</script>

</body>
</html>
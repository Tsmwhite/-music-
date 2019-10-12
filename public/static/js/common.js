/**
 * 后台通用的 js 文件, 用于处理表单元素, 处理菜单相关的逻辑
 * 建议将自己写的代码添加到 loaded 方法末尾处
 * Created by PhpStorm.
 * User: chenlongmingob@outlook.com
 * Date: 2018/11/14
 * Time: 13:54
 */


/**
 * 遍历 ElementList
 * @param selector  选择器
 * @param callback  回调
 */
function elementMap(selector, callback) {
    Array.from(document.querySelectorAll(selector)).forEach(function (i, n) {
        callback(i);
    })
}

var headers = {
    'x-requested-with': 'XMLHttpRequest'
};

if (!Cookies.get('auth_token')) {
    location.href = '/admin/pub/logout'
} else {
    headers.Authorization = Cookies.get('auth_token');
}

var api = axios.create({
    headers: headers
});

/**
 * 文档加载完成且相应的 layui 组件也加载完成时将会调用该方法
 * @returns {boolean}
 */
function formLoaded () {
    if (!window.laydate || !window.layer || !window.element || !window.isLoaded) return false;

    // 生成批量操作的 checkbox
    var table = document.querySelector('table[data-muti]');
    if (table) {
        var headRow = table.querySelector('thead tr');
        var bodyRows = table.querySelectorAll('tbody tr');
        var checked = [];

        var _pk;
        if (document.querySelector('tbody tr')) {
            _pk = document.querySelector('tbody tr').dataset.pkName;
        }

        // 表格头部的 checkbox
        var headCheckbox = document.createElement('th');
        headCheckbox.innerHTML = '<input type="checkbox" id="muti-all">';
        headCheckbox.querySelector('#muti-all').addEventListener('change', function () {
            var _this = this;
            checked = [];
            Array.from(bodyRows).forEach(function (i, n) {
                i.querySelector('input[type="checkbox"]').checked = _this.checked;

                if (_this.checked) {
                    checked.push(i.dataset.pk);
                }
            });
        }, false);

        headRow.prepend(headCheckbox);

        Array.from(bodyRows).forEach(function (i, n) {
            var innerTd = document.createElement('td');
            var innerCheckbox = document.createElement('input');
            innerCheckbox.type = 'checkbox';
            innerTd.appendChild(innerCheckbox);
            innerCheckbox.addEventListener('change', function () {
                checked = [];
                var _all = true;
                Array.from(bodyRows).forEach(function (_i, _n) {
                    if (_i.querySelector('input[type="checkbox"]').checked) {
                        checked.push(_i.dataset.pk);
                    } else {
                        _all = false;
                    }
                });

                headCheckbox.querySelector('#muti-all').checked = _all;
            }, false);
            i.prepend(innerTd);
        });

        var deleteButton = document.createElement('button');
        deleteButton.innerText = '删除所选';
        deleteButton.classList.add('btn', 'btn-sm', 'btn-danger');
        deleteButton.addEventListener('click', function () {
            if (checked.length > 0 && confirm('确定要删除已选择的项目吗?(无法恢复)')) {
                window.location.href = 'delete?' + _pk + '=' + checked.join(',');
            } else if (checked.length <= 0) {
                alert('请先勾选要删除的项目');
            }
        }, false);
        document.querySelector('.container-fluid').appendChild(deleteButton);
    }

    // filter 组件, 判断激活状态
    elementMap('[data-filter-link]', function (i) {
        if (i.href === location.href) {
            i.classList.add('active');
        }
    });

    /**
     * 展开父菜单
     * @param i
     */
    function parentItemed (i) {
        if (i && i.parentElement) {
            var p = i.parentElement;
            if (p && p.tagName.toLowerCase() === 'dd') {
                p.classList.add('layui-nav-itemed');
            }

            if (p.parentElement) {
                parentItemed(p);
            }
        }
    }

    parentItemed(document.querySelector('.layui-nav-itemed'));

    // 初始化页面中的表单元素
    elementMap('[data-input-type="datetime"]', function (i) {
        laydate.render({
            elem: i,
            type: 'datetime',
            format: i.dataset.format ? i.dataset.format : 'yyyy-MM-dd HH:mm:ss',
            range: !!i.dataset.range
        });

        console.log(!!i.dataset.range)
    });
    elementMap('[data-input-type="date"]', function (i) {
        laydate.render({
            elem: i,
            type: 'date',
            format: i.dataset.format ? i.dataset.format : 'yyyy-MM-dd',
            range: !!i.dataset.range
        });
    });
    elementMap('[data-input-type="time"]', function (i) {
        laydate.render({
            elem: i,
            type: 'time',
            format: i.dataset.format ? i.dataset.format : 'HH:mm:ss',
            range: !!i.dataset.range
        })
    });

    // 图片点击放大
    elementMap('[data-img]', function (i) {
        i.addEventListener('click', function () {
            imgDialog(i.src)
        }, false)
    });


    elementMap('[data-form]', function (i) {
        if (i.dataset.form !== '') {
            i.addEventListener('click', function (e) {
                e.preventDefault();

                openUrl(i.dataset.form);
            })
        }
    });


    // 文件上传
    elementMap('[data-input-type="imgUpload"]', function (i) {
        var input = i.querySelector('input[type="file"]');
        var label = i.querySelector('.custom-file-label');
        var labelName = label.innerHTML;
        var progressWp = i.querySelector('.progress');
        var progress = i.querySelector('.progress-bar');
        var preview = i.querySelector('.preview-box');
        var realInput = i.querySelector('input[type="hidden"]');
        var value = '';

        // 创建预览图片的 dom
        var previewItem = document.createElement('div');
        previewItem.classList.add('preview-item');
        var previewImg = document.createElement('img');
        previewImg.classList.add('img-preview');
        var closeBtn = document.createElement('div');
        closeBtn.classList.add('img-upload-close', 'btn', 'btn-danger', 'btn-sm');
        closeBtn.innerText = '移除';
        previewItem.appendChild(previewImg);

        input.addEventListener('change', function () {
            if (input.files.length <= 0) {
                // 未选择任何内容

                return false;
            } else {
                preview.classList.remove('hide');

                var files = input.files;

                var data = new FormData();
                data.append('multiple', 'true');
                var names = [];
                Array.from(files).forEach(function (file, n) {
                    names.push('image' + n);
                    data.append('image' + n, file);

                });

                data.append('name', names.toString());

                progressWp.classList.remove('hide');
                progress.classList.remove('bg-success');
                progress.classList.remove('bg-danger');
                progress.classList.add('bg-warning');
                api.post('/api/file_upload/image', data, {

                    // 监听上传进度
                    onUploadProgress: function (pg) {
                        var v = parseInt(pg.loaded * 100 / pg.total);
                        progress.innerHTML = v + "%";
                        progress.style.width = v + '%';
                    }
                }).then(function (e) {
                    e = e.data;
                    if (!e || !e.uploaded) throw '上传失败';

                    if (realInput.value) {
                        var urls = realInput.value.split(',');
                        urls.push(e.url);
                        realInput.value = urls.join(',');
                    } else {
                        realInput.value = e.url;
                    }

                    label.innerHTML = '已选择 ' + realInput.value.split(',').length + ' 个文件';


                    e.url.split(',').forEach(function (file) {
                        var item = previewItem.cloneNode(true);
                        var btn = closeBtn.cloneNode(true);
                        btn.dataset.inputType = 'imageUploadClose';
                        btn.addEventListener('click', function () {
                            var url = file;

                            var prevElem = btn.parentNode;
                            var rootElem = prevElem.parentNode.parentNode;

                            var realInput = rootElem.querySelector('input[type="hidden"]');


                            var urls = realInput.value.split(',');
                            var i = urls.indexOf(url);

                            if (i !== -1) {
                                urls.splice(i, 1);
                                realInput.value = urls.join(',');
                            }

                            if (urls.length > 0) {
                                rootElem.querySelector('.custom-file-label').innerText = '已选择 ' + urls.length + ' 个文件';
                            } else {
                                rootElem.querySelector('.custom-file-label').innerText = rootElem.querySelector('.field-name').innerHTML;
                            }

                            prevElem.remove();
                        });
                        item.appendChild(btn);
                        item.querySelector('img').src = file;
                        preview.appendChild(item);
                    });
                    progress.classList.add('bg-success');
                    progress.classList.remove('bg-warning');
                    setTimeout(function () {
                        progressWp.classList.add('hide');
                    }, 1000);

                    // success
                    layer.msg('上传成功');

                }).catch(function (err) {
                    console.warn(err);
                    input.value = null;
                    progress.classList.add('bg-danger');
                    progress.classList.remove('bg-warning');
                    setTimeout(function () {
                        progressWp.classList.add('hide');
                    }, 1000);
                    if (err instanceof Object) err = err.msg || '上传失败';

                    // output error message
                    layer.open({
                        title: '上传失败',
                        content: err,
                        icon: 2
                    })
                })
            }
        }, false);
    });

    elementMap('[data-input-type="html"]', function (i) {
        ClassicEditor.create(i, {
            ckfinder: {
                uploadUrl: '/api/file_upload/image'
            },
            language: 'zh-cn'
        }).then(function (editor) {
        }).catch(function (err) {
            console.warn(err);
        })
    });



    // 城市选择器
    var province = document.querySelectorAll('[data-input-type="province"]');

    if (province && province.length) {
        api.get('/static/common/area.json').then(function (response) {
            var data = response.data;
            var optionEle = document.createElement('option');

            Array.from(province).forEach(function (i) {


                var city = findGroupCity(i.dataset.gp );
                var area = findGroupArea(i.dataset.gp );

                // 生成省份选择项
                for (var p in data.province_list) {
                    var opt = optionEle.cloneNode();
                    opt.value = p;
                    opt.innerText = data.province_list[p];

                    i.appendChild(opt);
                }

                // 生成初始值
                var pv = i.dataset.value;
                if (pv) {
                    Array.from(i.children).forEach(function (pi) {
                        if (pi.innerText === pv) {
                            pi.selected = true;

                            var cites = {};
                            var p_code = i.value.slice(0, 2);


                            for (var cc in data.city_list) {

                                // 获取省对应的城市
                                if (cc.slice(0, 2) === p_code) {

                                    // 重新生成选择项
                                    var opt = optionEle.cloneNode();
                                    opt.value = cc;
                                    opt.innerText = data.city_list[cc];

                                    if (city) {
                                        city.appendChild(opt);
                                    }
                                }
                            }


                            // 生成城市初始值
                            if (city) {
                                var cv = city.dataset.value;
                                Array.from(city.children).forEach(function (ci) {
                                    if (ci.innerText === cv) {
                                        ci.selected = true;

                                        var pc_code = city.value.slice(0, 4);
                                        for (var a in data.county_list) {
                                            if (pc_code === a.slice(0, 4)) {
                                                var opt3 = optionEle.cloneNode();
                                                opt3.value = a;
                                                opt3.innerText = data.county_list[a];

                                                if (area) {
                                                    area.appendChild(opt3);
                                                }
                                            }
                                        }


                                        // 生成地区初始值
                                        if (area) {
                                            var av = area.dataset.value;
                                            Array.from(area.children).forEach(function (ai) {
                                                if (ai.innerText === av) {
                                                    ai.selected = true;
                                                }
                                            })
                                        }
                                    }
                                })
                            }
                        }
                    })
                }

                // 监听 province change 事件
                i.addEventListener('change', function () {

                    // 值保存到隐藏域
                    i.parentElement.querySelector('input[type="hidden"]').value = i.querySelector('option[value="' + i.value + '"]').innerText;



                    if (city) {

                        // 清空原有的城市选择项
                        Array.from(city.childNodes).forEach(function (ogci) {
                            ogci.remove();
                        });

                        var opt1 = optionEle.cloneNode();
                        opt1.value = '__unset';
                        opt1.innerText = '请选择';

                        city.appendChild(opt1);
                    }


                    if (area) {

                        // 清空原有的地区选择项
                        Array.from(area.childNodes).forEach(function (ogai) {
                            ogai.remove();
                        });

                        var opt2 = optionEle.cloneNode();
                        opt2.value = '__unset';
                        opt2.innerText = '请选择';

                        area.appendChild(opt2);
                    }


                    if (i.value !== '__unset') {
                        var cites = {};
                        var p_code = i.value.slice(0, 2);


                        for (var cc in data.city_list) {

                            // 获取省对应的城市
                            if (cc.slice(0, 2) === p_code) {

                                // 重新生成选择项
                                var opt = optionEle.cloneNode();
                                opt.value = cc;
                                opt.innerText = data.city_list[cc];

                                if (city) {
                                    city.appendChild(opt);
                                }
                            }
                        }
                    }
                });


                if (city) {
                    // 监听 city change 事件
                    city.addEventListener('change', function () {

                        city.parentElement.querySelector('input[type="hidden"]').value = city.querySelector('option[value="' + city.value + '"]').innerText;


                        if (area) {

                            // 清空原有的地区选择项
                            Array.from(area.childNodes).forEach(function (ogai) {
                                ogai.remove();
                            });

                            var opt = optionEle.cloneNode();
                            opt.value = '__unset';
                            opt.innerText = '请选择';

                            area.appendChild(opt);
                        }


                        var pc_code = this.value.slice(0, 4);
                        for (var a in data.county_list) {
                            if (pc_code === a.slice(0, 4)) {
                                var opt3 = optionEle.cloneNode();
                                opt3.value = a;
                                opt3.innerText = data.county_list[a];

                                if (area) {
                                    area.appendChild(opt3);
                                }
                            }
                        }
                    })
                }


                if (area) {

                    // 监听 area change 事件
                    area.addEventListener('change', function () {

                        area.parentElement.querySelector('input[type="hidden"]').value = area.querySelector('option[value="' + area.value + '"]').innerText;
                    });
                }

            })
        })
    }



    // 获取同组的城市选择器
    function findGroupCity (gid) {
        return document.querySelector('[data-gp="' + gid + '"][data-input-type="city"]');
    }


    // 获取同组的地区选择器
    function findGroupArea (gid) {
        return document.querySelector('[data-gp="' + gid + '"][data-input-type="area"]');
    }


    elementMap('[data-input-type="imageUploadClose"]', function (el) {

        el.addEventListener('click', function () {
            var url = el.dataset.imgUrl;

            var prevElem = el.parentNode;
            var rootElem = prevElem.parentNode.parentNode;

            var realInput = rootElem.querySelector('input[type="hidden"]');


            var urls = realInput.value.split(',');
            var i = urls.indexOf(url);

            if (i !== -1) {
                urls.splice(i, 1);
                realInput.value = urls.join(',');
            }

            if (urls.length > 0) {
                rootElem.querySelector('.custom-file-label').innerText = '已选择 ' + urls.length + ' 个文件';
            } else {
                rootElem.querySelector('.custom-file-label').innerText = rootElem.querySelector('.field-name').innerHTML;
            }

            prevElem.remove();
        })
    });


    // 修改菜单样式
    Array.from(document.querySelectorAll('a[data-left]')).forEach(function (i) {
        i.style.paddingLeft = i.dataset.left + 'rem';
    });
}


layui.use(['laydate', 'layer', 'element'], function () {
    window.laydate = layui.laydate;
    window.layer = layui.layer;
    window.element = layui.element;

    formLoaded();
});

window.onload = function () {
    window.isLoaded = true;

    formLoaded();
};

/**
 * 关闭当前激活的页面层
 */
window.$closeActiveLayer = function () {
    if (window.$activeLayer) {
        layer.close(window.$activeLayer);
        window.$activeLayer = undefined;
    }
};


/**
 * 关闭当前激活的页面层(表单)
 * @param msg
 * @param reload
 */
window.$closeActiveForm = function (msg, reload) {
    $closeActiveLayer();
    if (msg) {
        layer.open({
            icon: 1,
            content: msg
        })
    }

    if (reload) {
        setTimeout(function () {
            window.location.reload();
        }, 2000);
    }
};

/**
 * 通过弹出层展示一个页面
 * @param url
 * @param title
 */
function openUrl (url, title) {
    window.$activeLayer = layer.open({
        type: 2,
        content: url,
        title: title ? title : '信息',
        area: [500, 400]
    })
}
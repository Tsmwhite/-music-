<!--
由代码生成工具自动生成
Date: __DATE__
Time: __TIME__
-->

{extend name="base" /}

{block name="content"}

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{:url('admin/index/index')}">首页</a></li>
            <li class="breadcrumb-item active" aria-current="page">__CONTROLLERNAME__</li>
        </ol>
    </nav>

    {if condition="$search"}
        <div class="card search-card">

            <div class="card-body">
                <form>
                    <div class="row">
                        {$search}
                        <div class="search-btn-wrap">
                            <button class="btn btn-info text-right">搜索</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    {/if}
    <div class="alert">
        <div class="row flex-vertical">
            <div class="col-sm-3 text-muted small">总计: {$count ?? 0} 条记录</div>
            <div class="col-sm-9 text-right">
                {if condition="$showImportForm"}
                    <form class="form import-form"
                          enctype="multipart/form-data"
                          action="{:url('import')}"
                          method="POST">
                        <input type="file" class="files" name="files" style="width: 180px;" required>
                        <button type="submit" class="btn btn-info text-right btn-sm">提交</button>
                    </form>
                {/if}
                {if condition="$showExportButton"}
                    <a class="btn btn-info btn-sm" href="{:url('export', $request->param())}">导出</a>
                {/if}
                <a class="btn btn-success btn-sm" href="{:url('add')}">添加</a>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover col-nowrap" data-muti>
            <thead>
            <tr>
                <!-- 表头 -->
                {$tableHead}
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            {$tableBody}
            </tbody>
        </table>
    </div>
    {$page}
{/block}


{block name='script'}
    {if condition="$tree"}
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
    {/if}
{/block}
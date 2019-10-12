## PowerBeast

PowerBeast 是基于 thinkphp5.0 的后台管理框架

1. 未经 PowerBeast 官方授权的用户，仅供从事学习研究之用，不具备商业运作的合法性；
2. 未获取 PowerBeast 官方授权而从事商业行为，PowerBeast 官方将保留对其使用系统停止升级、 关闭、甚至对其商业运作行为媒体曝光和追究法律责任的起诉权利。
3. 联系方式 电话：18888964864 微信同号 QQ：339628123

### 最新版本不再更新 vendor 目录, 从仓库克隆之后需要 cd 到项目目录中 运行 `composer install` 安装依赖 


### 使用前必看:

* 具备 PHP 基础知识，熟悉面向对象, 命名空间等概念, 不要连闭包是啥都不明白，可以参考知乎回答 [想要开发自己的PHP框架需要那些知识储备](http://www.zhihu.com/question/26635323/answer/33812516)
* 熟悉 thinkphp 5.0.*
* 熟悉 PHP 常见的知识：自动加载、composer 的使用、JSON 处理、Curl 的使用等
* 了解基本的 HTTP 协议，Header 头、请求方式（GET\POST\PUT\PATCH\DELETE）等
* 基本的 Debug 技能，查看 php 日志，nginx 日志等, 查看 thinkphp 日志

另外关于提问第技巧可以看参考以下链接:

* [提问的智慧](https://ruby-china.org/topics/24325)

请不要提出诸如以下的问题, 因为大多数人都没有办法回答你:

* "为啥我的不行啊，请问服务器日志怎么看啊？"
* "Linux 上怎么配置 Apache 环境啊?"
* "为什么我在这个页面上拿不到参数呀?"
* "xxx功能该怎么写啊?"
* ...

#### 特别建议使用前先看一下这个文档, 虽然还在完善中, 但是能对框架有个大致的概念

[文档地址](https://www.kancloud.cn/qdhonker/powerbeast/866048)


### 版本更新:


#### 1.3.9
...

#### 1.3.8
1. 废弃了 updateFields, addFields 中使用 nofetch 参数来使某字段的表单不渲染的功能
2. indexFields 中当字段对应的值为 `@hidden` 时该字段不展示在表格中


#### 1.3.7
1. 视图模型中 updateFields, addFields, indexFields, search 属性中, 允许使用模板来代替组件名, 这样可以直接渲染一个模板作为当前的字段的 Form 组件, 模板中允许使用和组件相同的变量
2. 修复 SearchComponent 中 search 方法不生效的 bug

#### 1.3.6
1. 修复了Input表单组件继承错误的问题
2. 现在支持在视图模型中使用 `fetchForm` 方法接管表单构建流程, 具体请参考 application\common\ViewModel.php 中的 fetchForm 方法注释

#### 1.3.5

支持通过插件拓展原有功能, 新增一个插件相关的命令行工具(application\plugins\plugin-cli.php), 查看 **[插件说明](application/plugins/readme.md)**


#### 1.3.3

1. 涉及权限的操作后, 会自动刷新当前登录用户的权限, 不再需要重新登陆
2. 优化控制器 update 方法, add 方法, 使用自定义的 更新 或 添加流程时 更加方便
3. ViewModel update 方法和 add 方法传入的第二个参数(匿名函数)得到的参数为 当前视图模型实例, update 方法中的查询条件变更为第三个参数


自定义更新和添加方法(Controller中)
```php
class Product extends Controller
{
    public $model = 'Product';


    // 更新数据
    public function update($todo = null)
    {
        /**
         * @var $params     array       表单获取的内容
         * @var $vm         ViewModel   当前操作的视图模型实例
         * @var $where      array       update 查询条件
         */
        return parent::update(function (array $params, ViewModel $vm, array $where) {
            // todo: 自定义更新数据的流程
        });
    }


    // 新增数据
    public function add($todo = null)
    {
        /**
         * @var $params     array       表单获取的内容
         * @var $vm         ViewModel   当前操作的视图模型实例
         */
        return parent::add(function (array $params, ViewModel $vm) {
            // todo: 自定义添加数据的流程
        });
    }
}
```


#### 1.3.1

1. 新增一个 `TableConvert` 表格组件, 用于转换数据
2. 视图模型的 `IndexFields` 属性中, 现在支持使用 `not_field` 参数, 将其不视为一个字段去获取
3. 引入 `phpseclib` 库
4. JWT token 可以配置使用 RSA 加密, 加密后允许在客户端存用户的隐私信息
5. 可以通过 `api\secure\resetRsaKey` 接口重置 RSA 密钥


#### 1.3.0 

1. 删除部分测试代码
2. 新增一个地区选择表单组件 `FormArea` 用于选择城市地区


#### 1.2.9
**安全更新**: 修复在没有开启强制路由的情况下可能的 getshell 漏洞

#### 1.2.8
1. Index 视图允许通过 配置 ViewModel 中的 tree 属性来生成树状的结构

```php
class Menu extends ViewModel
{
    ...
    
    // parent 是记录父级标识的字段, self 是自身的标识
    public $tree = [
        'parent' => 'pid',
        'self' => 'id'
    ];
    
    ...
```

#### 1.2.6
1. ViewModel 中允许通过 fetchButtons 方法注入按钮.
2. VIewModel 中的 buttons 属性添加了一个参数 params 用于携带跟多自定义参数

**fetchButtons 示例 :**
```php
    ...
    
    /**
     * 注入按钮, 在原有按钮的基础上 新增一个 测试按钮
     * @param $row  array   表格当前行的数据
     * @return array        返回一个 buttons 数组, 规则同 buttons 属性
     */
    public function fetchButtons ($row)
    {
        return array_merge($this->buttons, [[
            'url' => 'test',
            'params' => [
                'arg1' => 1
            ],
            'type' => 'danger',
            'name' => '测试按钮'
        ]]);
    }
    
    ...
```



#### 1.2.1

集成了 jwt 相关功能, api 控制器 `app\ApiController` 中加入了自动解析客户端传来的
`token`, 客户端在调用接口时需要在 header 中携带 `token` 放到 `Authorization` 中, 所有继承
 `app\ApiController` 的控制器可以通过访问自身的 `token` 属性来获取 `\Lcobucci\JWT\Token` 实例, 如

```php

class Index extends ApiController
{

    ...
    
    public function index ()
    {
        return $this->token ? $this->token->getClaims() : null;
    }
    
    ...

```


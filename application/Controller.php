<?php
/**
 * Created by PhpStorm.
 * User: chenl
 * Date: 2018/10/18
 * Time: 10:34
 */

namespace app;


use app\common\ViewModel;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use think\Loader;
use think\Request;

class Controller extends \think\Controller
{
    public $model = null;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);

        if (!$this->model) $this->model = $request->controller();
        $this->view->assign([
            'request'   =>  $request,
            'user'      =>  getAuthClass()->getAuthInfo()
        ]);

        // 检查是否有匹配的插件
        $fs = new Filesystem(new Local(APP_PATH . DS . 'plugins'));
        $route = json_decode($fs->read('route.json'), true);

        if ($route) {
            foreach ($route as $url => $pluginName) {
                if (strtolower($this->request->path()) === strtolower($url)) {
                    $module = $this->request->module();
                    $controller = $this->request->controller();
                    $action = Loader::parseName($this->request->action());

                    $class = "\\app\\plugins\\${pluginName}\\${module}\\controller\\${controller}";


                    // 如果匹配插件定义的 url 返回插件的页面
                    exit((new $class($pluginName, ViewModel::instance($this->model)))->$action());
                }
            }
        }
    }

    public function index ()
    {
        return $this->view->assign(ViewModel::instance($this->model)->indexView())->fetch();
    }


    /**
     * @param \Closure $todo
     * @return string
     * @throws \think\Exception
     */
    public function add ($todo = null)
    {
        if ($this->request->param('action') === 'add') {
            ViewModel::instance($this->model)->add(null, $todo);
            $this->redirect(url('index'));
        } else {
            return $this->view->assign(ViewModel::instance($this->model)->addView())->fetch();
        }
    }


    /**
     * @param \Closure $todo
     * @return string
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function update ($todo = null)
    {
        if ($this->request->param('action') === 'update') {
            ViewModel::instance($this->model)->update(null, $todo);
            $this->redirect(url('index'));
        } else {
            return $this->view->assign(ViewModel::instance($this->model)->updateView())->fetch();
        }
    }


    public function delete ()
    {
        ViewModel::instance($this->model)->delete();
        $this->redirect(url('index'));
    }


    public function export ()
    {
        ViewModel::instance($this->model)->export();
    }


    public function import ()
    {
        ViewModel::instance($this->model)->import();
        $this->redirect(url('index'));
    }
}
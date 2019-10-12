<?php
/**
 * Created by PhpStorm.
 * User: chenlongmingob@outlook.com
 * Date: 2018/11/26
 * Time: 19:23
 */

namespace app\api\controller;


use app\ApiController;
use app\common\model\Node;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use think\Db;
use think\db\Query;
use think\Loader;
use think\Request;

class Generate extends ApiController
{
    public function table_fields ($name)
    {
        return Db::query('describe `' . $name . '`');
    }


    /**
     * 执行代码生成
     * @param Request $request
     * @return bool
     */
    public function run (Request $request)
    {
        $data = $request->param();

        $baseVar = [
            'date'              =>  date('Y-m-d', time()),
            'time'              =>  date('H:i:s', time()),
            'controllerName'    =>  $data['controllerName']
        ];

        $tplPath = __DIR__ . DS . '..' . DS . '..' . DS . 'common' . DS . 'template' . DS;


        // 生成视图模型
        $modelTpl = $tplPath . 'ViewModel.tpl';
        $modelVar = array_merge($baseVar, [
            'namespace' =>  'app\\admin\\viewModel',
            'classname' =>  Loader::parseName($data['table'], 1),
            'search'    =>  '[',
            'fieldsname'=>  '[',
            'index'     =>  '[',
            'update'    =>  '[',
            'add'       =>  '[',
            'export'    =>  '[',
            'import'    =>  '[',
        ]);

        // 生成字段描述
        foreach ($data['fields'] as $field) {
            $field = json_decode($field, true);
            $modelVar['fieldsname'] .= "\n\t\t'${field['field']}' => '${field['name']}',";
            if (!empty($field['tc'])) {
                $modelVar['index'] .= "\n\t\t'${field['field']}' => '${field['tc']}',";
            }
            if ($field['add']) {
                $add_rule = $field['add_rule'] ? "'${field['add_rule']}'" : 'null';
                $modelVar['add'] .= "\n\t\t'${field['field']}' => ['${field['fc']}', ${add_rule}],";
            }

            if ($field['update']) {
                $update_rule = $field['update_rule'] ? "'${field['update_rule']}'" : 'null';
                $modelVar['update'] .= "\n\t\t'${field['field']}' => ['${field['fc']}', ${update_rule}],";
            }

            if ($field['export']) {
                $modelVar['export'] .= "\n\t\t'${field['field']},${field['name']}',";
            }

            if ($field['import']) {
                $modelVar['import'] .= "\n\t\t'${field['field']},${field['name']}',";
            }
        }

        // 生成搜索字段
        if (!empty($data['search'])) {
            foreach ($data['search'] as $search) {
                $search = json_decode($search, true);
                $like = $search['like'] ? "'like'" : null;
                $modelVar['search'] .= "\n\t\t'${search['field']}' => ['${search['type']}', ${like}],";
            }
        }


        $modelVar['fieldsname'] .= "\n\t]";
        $modelVar['index'] .= "\n\t]";
        $modelVar['update'] .= "\n\t]";
        $modelVar['add'] .= "\n\t]";
        $modelVar['search'] .= "\n\t]";
        $modelVar['export'] .= "\n\t]";
        $modelVar['import'] .= "\n\t]";

        // 解析模板
        $modelTemp = $this->parseTpl($modelTpl, $modelVar);
        

        $fs = new Filesystem(new Local(APP_PATH));
        $fs->put('admin' . DS . 'viewModel' . DS . Loader::parseName($data['table'], 1) . '.php', $modelTemp);


        // 生成控制器
        $ctrlVar = array_merge($baseVar, [
            'classname' =>  Loader::parseName($data['controller'], 1),
            'model'     =>  Loader::parseName($data['table'], 1)
        ]);

        $ctrlTpl = $tplPath . 'Controller.tpl';
        $fs->put('admin' . DS . 'controller' . DS . $ctrlVar['classname'] . '.php', $this->parseTpl($ctrlTpl, $ctrlVar));

        // 生成默认视图
        $viewPath = 'admin' . DS . 'view' . DS . Loader::parseName($data['controller']);

        // 生成 index 视图
        $indexTpl = $tplPath . 'index.tpl';
        $fs->put($viewPath . DS . 'index.html', $this->parseTpl($indexTpl, $baseVar));

        // 生成 add 视图
        $addTpl = $tplPath . 'add.tpl';
        $fs->put($viewPath . DS . 'add.html', $this->parseTpl($addTpl, $baseVar));

        // 生成 update 视图
        $updateTpl = $tplPath . 'update.tpl';
        $fs->put($viewPath . DS . 'update.html', $this->parseTpl($updateTpl, $baseVar));


        // 创建节点
        if (!empty($data['create_node'])) {
            Node::add('admin', Loader::parseName($data['controller']), 'index');
            Node::add('admin', Loader::parseName($data['controller']), 'add');
            Node::add('admin', Loader::parseName($data['controller']), 'update');
            Node::add('admin', Loader::parseName($data['controller']), 'delete');
            Node::add('admin', Loader::parseName($data['controller']), 'export');
            Node::add('admin', Loader::parseName($data['controller']), 'import');
        }

        return true;
    }


    /**
     * 文件模板解析
     * @param $filename
     * @param $var
     * @return bool|mixed|string
     */
    private function parseTpl ($filename, $var) 
    {
        $tpl = file_get_contents($filename);
        foreach ($var as $key => $value) {
            $key = '__' . strtoupper($key) . '__';
            $tpl = str_replace($key, $value, $tpl);
        }
        
        return $tpl;
    }
}
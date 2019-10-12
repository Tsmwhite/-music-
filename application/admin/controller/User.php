<?php
/**
 * Created by PhpStorm.
 * User: chenlongmingob@outlook.com
 * Date: 2018/11/19
 * Time: 13:50
 */

namespace app\admin\controller;

use app\common\ViewModel;
use app\Controller;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use think\Db;

class User extends Controller
{
    public function add($todo = null)
    {
        if ($this->request->param('action') === 'add') {
            ViewModel::instance($this->model)->add(null, function ($data) {

                // 先处理一下数据
                $admin_role['rid'] = $data['admin_role.rid'];

                unset($data['admin_role.rid']);

                // 多表操作 启用事务
                Db::transaction(function () use ($data, $admin_role) {
                    // 写入 用户表, 最新版本的 user 表 改为 admin 表
                    $admin_role['uid'] = Db::name('admin')->insertGetId($data);

                    // 写入 用户角色表, 最新版本的 user_role 表 改为 admin_role 表
                    Db::name('admin_role')->insert($admin_role);
                });
            });
            $this->redirect(url('index'));
        } else {
            return $this->view->assign(ViewModel::instance($this->model)->addView())->fetch();
        }
    }


    public function update($todo = null)
    {
        if ($this->request->param('action') === 'update') {
            ViewModel::instance($this->model)->update(null, function ($data) {
                $uid = $this->request->param('id');

                $rid = $data['admin_role.rid'];

                unset($data['admin_role.rid']);

                Db::transaction(function () use ($uid, $rid, $data) {
                    Db::name('admin')->where('id', 'eq', $uid)->update($data);

                    // 判断关联记录是否存在, 存在的话就修改, 不存在就创建
                    if (Db::name('admin_role')->where('uid', 'eq', $uid)->find()) {
                        Db::name('admin_role')->where('uid', 'eq', $uid)->update(['rid' => $rid]);
                    } else {
                        Db::name('admin_role')->insert([
                            'rid' => $rid,
                            'uid' => $uid
                        ]);
                    }
                });
            });
            $this->redirect(url('index'));
        } else {
            return $this->view->assign(ViewModel::instance($this->model)->updateView())->fetch();
        }
    }

    public function import ()
    {
        $file = $this->request->file('file');
        if (!$file)
            throw new \Exception('文件上传失败');

        $savePath = ROOT_PATH . 'runtime' . DS . 'import';
        $info = $file->move($savePath);

        if ($info->getError())
            throw new \Exception($info->getError());

        $type = IOFactory::identify($info->getRealPath());
        $reader = IOFactory::createReader($type);
        $worker = $reader->load($info->getRealPath());
        $sheet = $worker->setActiveSheetIndex(0);


        $vm = ViewModel::instance($this->model);
        $data = $vm->readFromRows($sheet);
    }
}
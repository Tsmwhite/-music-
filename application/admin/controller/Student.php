<?php
/**
 * 由代码生成工具自动生成
 * Date: 2019-04-22
 * Time: 00:05:54
 */


namespace app\admin\controller;


use app\Controller;
use think\Request;
use think\Db;
class Student extends Controller
{
    public $model = 'Student';

    

    public function user_info(){
        $id = $this->request->get('id');

        $user_info = Db::table('student')->where('id',$id)->find();
        
        $order = Db::table('trade')->where('student_id',$id)->select();

        return $this->assign([
            'order' => $order,
            'user_info' => $user_info
        ])->fetch();
    }
}
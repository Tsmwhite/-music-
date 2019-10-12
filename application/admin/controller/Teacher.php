<?php
/**
 * 由代码生成工具自动生成
 * Date: 2019-04-23
 * Time: 00:03:27
 */


namespace app\admin\controller;


use app\Controller;
use think\Request;
use think\Db;
class Teacher extends Controller
{
    public $model = 'Teacher';


    public function info(){
        $id = $this->request->get('id');

        $user_info = Db::table('teacher')->where('id',$id)->find();

        return $this->assign([
            'teacher_info' => $user_info
        ])->fetch();
    }

    public function type(){
        $id = $this->request->get('id');

        $user_info = Db::table('teacher')->where('id',$id)->find();

        return $this->assign([
            'teacher_info' => $user_info
        ])->fetch();
    }

    public function shenhe(){
        $id = $this->request->get('id');
        $type = $this->request->get('type');
        $user_info = Db::table('teacher')->where('id',$id)->find();

        Db::table('teacher')->where('id',$id)->update(array('type'=>$type));
        if($type == 1){
            $class_id = $user_info['class'];
            $class_list_id = explode(",",$class_id);
            if($class_list_id[0]){
                foreach($class_list_id as $k){
                    $class = explode("=",$k);
                    Db::table('music_teacher_list')->insert(array('music_id'=>$class[0],'music_sun_id'=>$class[1],'teacher_id'=>Db::name('teacher')->getLastInsID()));
                }
            }
        }

        $this->redirect("/admin/teacher/index");
    }

    public function renzheng(){
        $id = $this->request->get('id');
        $type = $this->request->get('type');
        Db::table('teacher')->where('id',$id)->update(array('type'=>$type));

        $this->redirect("/admin/teacher/index");
    }
    
}
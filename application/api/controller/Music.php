<?php
/**
 * Created by PhpStorm.
 * User: chenl
 * Date: 2018/10/18
 * Time: 11:24
 */

namespace app\api\controller;
use think\Request;
use think\Db;
class Music extends \think\Controller
{
    //获取乐器列表
    public function index(){
        $data = Db::table('music')->field('id,name,photo')->where('pid',0)->order('sort asc')->select();
        $this->re_json($data);
    }

    //获取乐器详情
    public function info(){
        $request = Request::instance();
        $music_id = $request->post('music_id',true);
        $data['info'] = Db::table('music')->field('name,photo,content')->where('id',$music_id)->find();
        $data['list'] = Db::table('classss')->field('id,name,photo,content')->where('music_id',$music_id)->select();
        foreach($data['list'] as $v=>$k){
            $list = Db::table('classss')->alias('a')->join('assess b','a.id = b.class_id')
                    ->field('sum(a.class_star) as star')->where('a.id',$k['id'])->find();
            $count = Db::table('classss')->alias('a')->join('assess b','a.id = b.class_id')->where('a.id',$k['id'])->count();
            $data['list'][$v]['star'] = (float) $list['star'] ? number_format($list['star']/$count, 1, '.','') : 0.0;
            $data['list'][$v]['teacher_count'] = Db::table('music_teacher_list')->where('music_sun_id',$k['id'])->group('teacher_id')->count();
        }
        $this->re_json($data);
    }

    //课程详情
    public function index_info(){
        $request = Request::instance();
        $class_id = $request->post('class_id',true);
        $data['info'] = Db::table('classss')->field('name,photo,content,body,mix_time_type,max_time_type,environment_photo1,environment_photo2,environment_photo3,environment_photo4,environment_photo5,environment_photo6')->where('id',$class_id)->find();
        if(!$data['info']){
            $this->re_json('');
        }
        $list = Db::table('classss')->alias('a')->join('assess b','a.id = b.class_id')
        ->field('sum(a.class_star) as star')->where('a.id',$class_id)->find();
        $count = Db::table('classss')->alias('a')->join('assess b','a.id = b.class_id')->where('a.id',$class_id)->count();
        $data['info']['star'] = (float) $list['star'] ? number_format($list['star']/$count, 1, '.','') : 0.0;

        $data['style'][] = $data['info']['environment_photo1'];
        $data['style'][] = $data['info']['environment_photo2'];
        $data['style'][] = $data['info']['environment_photo3'];
        $data['style'][] = $data['info']['environment_photo4'];
        $data['style'][] = $data['info']['environment_photo5'];
        $data['style'][] = $data['info']['environment_photo6'];
        unset($data['info']['environment_photo1']);
        unset($data['info']['environment_photo2']);
        unset($data['info']['environment_photo3']);
        unset($data['info']['environment_photo4']);
        unset($data['info']['environment_photo5']);
        unset($data['info']['environment_photo6']);
        $this->re_json($data);
    }

 public function class_sun_class(){
        $request = Request::instance();
        $class_sun_id = $request->post('class_sun_id',true);
        $data['class_sun'] = Db::table('class_list')->field('start_time,stop_time,class_id,teacher_id')->where('id',$class_sun_id)->find();
        if(!$data['class_sun']){
            $this->re_json('');
        }
        $teacher = Db::table('teacher')->field('name')->where('id',$data['class_sun']['teacher_id'])->find();
        $list = Db::table('classss')->alias('a')->join('assess b','a.id = b.class_id')
        ->field('sum(b.class_star) as star,a.name as class_name,a.photo as class_photo')->where('a.id',$data['class_sun']['class_id'])->find();
        $count = Db::table('classss')->alias('a')->join('assess b','a.id = b.class_id')->where('a.id',$data['class_sun']['class_id'])->count();
        $data['class_sun']['class_name'] = $list['class_name'];
        $data['class_sun']['class_photo'] = $list['class_photo'];
        $data['class_sun']['teacher_name'] = $teacher['name'];
        $data['class_sun']['star'] = (float) $list['star'] ? number_format($list['star']/$count, 1, '.','') : 0.0;
//几对几
        $num = Db::table('yaoqing')->field('people_num')->where('id','like','"%"'.$class_sun_id.'"%"')->find();
$data['class_sun']['people_num'] = $num['people_num']?:0;
        $this->re_json($data);
    }



    public function class_sun_teacher(){
        $request = Request::instance();
        $teacher_id = $request->post('teacher_id',true);
        $time = $request->post('time',true);
        $data = Db::table('class_list')->field('start_time,stop_time')->where('teacher_id',$teacher_id)->where(['start_time'=>['egt',strtotime(date('Y-m-d 00:00:00', $time))]])
                        ->where(['start_time'=>['elt',strtotime(date('Y-m-d 23:59:59', $time))]])->select();
        $this->re_json($data);
    }

    //获取教师列表
    public function  teacher_list(){
        $request = Request::instance();
        $class_id = $request->post('class_id',true);
        $list1 =  $request->post('list',true);
        $val =  $request->post('val',true);

        $class = Db::table('classss')->field('name,photo,content')->where('id',$class_id)->find();
        if(!$class){
            $this->re_json('');
        }

        $data['list'] = Db::table('music_teacher_list')->alias('a')->join('teacher b','a.teacher_id = b.id')->join('music c','b.music = c.id')
        ->field('b.id,b.photo,b.name,b.content,a.id as music_teacher_list_id,c.name as music_name')->where('a.music_sun_id',$class_id)->limit($list1*$val,$val)->select();
        $data['count'] = Db::table('music_teacher_list')->where('music_sun_id',$class_id)->count();

        foreach($data['list'] as $v=>$k){
            $list = Db::table('assess')->field('sum(teacher_star) as star')->where('teacher_id',$k['id'])->find();
            $count = Db::table('assess')->where('teacher_id',$k['id'])->count();
            $data['list'][$v]['star'] = (float) $list['star'] ? number_format($list['star']/$count, 1, '.','') : 0.0;
        }
        $this->re_json($data);
    }


    //获取评论列表
    public function  assess_list(){
        $request = Request::instance();
        $type = $request->post('type',true);
        $id = $request->post('id',true);
        $list1 =  $request->post('list',true);
        $val =  $request->post('val',true);

        if($type == 0){
            $filed = 'a.class_content as assess_content,a.class_star as assess_star';
            $where['a.class_id'] = $id;
        }else{
            $filed = 'a.teacher_content as assess_content,a.teacher_star as assess_star';
            $where['a.teacher_id'] = $id;
        }
        $assess = Db::table('assess')->alias('a')->join('student b','a.user_id = b.id')->join('classss c','a.class_id = c.id')
        ->field($filed.',a.time,b.name as student_name,b.id as student_id,b.photo,c.name as class_name')->where($where)->limit($list1*$val,$val)->select();
        $this->re_json($assess);
    }




    public function delete_class(){
        $request = Request::instance();
        $pass = $request->get('pass',true);
        if($pass != 'qwdhuiohj12ioh31d09Q*HEHKJHDFIWUHQIE9872193'){
            exit;
        }
        //创建的课程若未付款则删除该订单
        $class_list = Db::table('class_list')->where('type',1)->where('time','<',(time() - 1800))->select();
        foreach($class_list as $k){
            $order =  Db::table('order')->where('class_id',$k['id'])->find();
            if(!$order){
                Db::table('class_list')->where('id',$k['id'])->delete();
            }
        }


        //多人课程若开课一小时前人未齐则取消该课程
        $array = Db::table('class_list')->where('type',1)->where('start_time','<',(time() - 3600))->select();
        foreach ($array as $k) {
            $student_order = Db::table('order')->where('class_id',$k['id'])->select();
            foreach ($student_order as $r) {
                Db::table('order')->where('id',$r['id'])->delete();
                if($r['type'] == 1){
                    Db::name("student")->where('id',$r['student_id'])->update(['money'=>Db::raw('money+'.$r['price'])]);
                }
            }
            Db::table('class_list')->where('id',$k['id'])->update(array('type'=>2));
        }

        //课程结束后，改为订单为已完成
        $aa =Db::table('order')->alias('a')
        ->join('class_list b','a.class_id = b.id','left')->where('a.type',1)->where('b.stop_time','<',time())->update(array('a.type'=>2,'b.status'=>1));
    }


    public function re_json($data,$body='success',$type=1){
        echo json_encode(array('data'=>$data,'body'=>$body,'type'=>$type));exit;
    }
}

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
class Teacherclass extends AuthTeacher
{

    //老师笔记及学生作业
    public function  notes(){
        $request = Request::instance();
        $class_id =  $request->post('class_id',true);
        $find = Db::table('order')->where('student_id',$this->user['id'])->where('class_id',$class_id)->where('punch',1)->find();
        if(!$find){
            $this->er_json("This course cannot be punched",11046);
        }
        $body['teacher'] = Db::table('teacher_order')->field('notes_photo,notes_video,notes_content')->where('class_id',$class_id)->where('teacher_id',$find['teacher_id'])->find();
        $body['student'] = Db::table('order')->field('notes_photo,notes_video,notes_content')->where('class_id',$class_id)->where('student_id',$this->user['id'])->find();
        $this->re_json($body);
    }


  //老师笔记及学生作业
    public function  puch_type(){
        $request = Request::instance();
        $class_id =  $request->post('class_id',true);
        $find = Db::table('order')->alias('a')->join('class_list b','a.class_id = b.id')->join('student c','b.student_id = c.id')
        ->field('a.id,b.name as class_name,c.name as student_name,b.start_time,b.stop_time')->where('a.class_id',$class_id)->where('b.teacher_id',$this->user['id'])->where('a.punch',1)->where('a.punch_type',0)->find();
	if(!$find){
            $this->re_json('');
        }else{
	  Db::table('order')->where('class_id',$class_id)->update(array('punch_type'=>1));
	    $this->re_json($find);
        }
    }


    ////修改个人资料
    public function  update_info(){
        $request = Request::instance();
        $body['photo'] = $request->post('photo',true);
        $body['name'] = $request->post('name',true);
        $body['sex'] = $request->post('sex',true);
        $body['birthday'] = $request->post('birthday',true);
        $body['interest'] = $request->post('interest',true);
        $body['address'] = $request->post('address',true);
        Db::table('teacher')->where('id',$this->user['id'])->update($body);
        $this->re_json('');
    }

//根据时间课程列表
    public function  class_list_time(){
        $request = Request::instance();
        $time =   $request->post('time',true);
        $start_time =  strtotime(date('Y-m-d 00:00:00', $time));
        $end_time = strtotime(date('Y-m-d 23:59:59', $time));
        $list = Db::table('class_list')->alias('a')->join('teacher b','a.teacher_id = b.id')
                ->field('b.id,b.name,a.id as class_id,a.name as class_name,a.start_time,a.stop_time')->where('a.teacher_id',$this->user['id'])->where('a.type',3)
                ->where('a.start_time','>',$start_time)->where('a.start_time','<',$end_time)->select();
        $this->re_json($list);
    }



     //根据时间获取某个月内有课天数
     public function  class_list_m(){
        $request = Request::instance();
        $time =   $request->post('time',true);
        $start_time =  date('Y-m-01 00:00:00', $time);
        $end_time =  date('Y-m-01 00:00:00', strtotime("$start_time +1 month"));
        $list = Db::table('order')->alias('a')->join('class_list c','a.class_id = c.id')
                ->field("FROM_UNIXTIME(c.start_time,'%Y-%m-%d') as time1")->where('a.teacher_id',$this->user['id'])->where('a.type',3)
                ->where('c.start_time','>',strtotime($start_time))->where('c.start_time','<',strtotime($end_time))->group('time1')->select();
        $body = [];
        foreach($list as $k){
            $body[] = $k['time1'];
        }
        $this->re_json($body);
    }


    //老师个人信息
    public function  info(){
        $request = Request::instance();
        $find = Db::table('teacher')->field('*')->where('id',$this->user['id'])->find();
        unset($find['pass']);
        unset($find['token']);
        $this->re_json($find);
    }


    //我的课程
    public function  class_list(){
        $request = Request::instance();
        $status =  $request->post('status',true);
        $list =  $request->post('list',true);
        $val =  $request->post('val',true);
        $where = array(
            'status'=>$status,
            'teacher_id'=>$this->user['id'],
            'type'=>3,
        );
        if($status == 0){
            $where['start_time'] = ['<',time()];
        }
        $data['list'] = Db::table('class_list')->where($where)->limit($list*$val,$val)->order('id desc')->select();
        foreach($data['list'] as $v=>$k){
            $list =  Db::table('order')->alias('a')->field('b.id,b.name')->join('student b','a.student_id = b.id')->where('a.type',1)->where('a.class_id',$k['id'])->select();
            $data['list'][$v]['student_list'] = $list;
        }
        $data['count'] = Db::table('class_list')->where($where)->count();
        $this->re_json($data);
    }


     //财务管理
     public function  finance(){
        $request = Request::instance();
        $list =  $request->post('list',true);
        $val =  $request->post('val',true);

        $time =  $request->post('time',true);
        $s_year = date('Y-01-01 00:00:00', $time);
        $e_year =  date('Y-01-01 00:00:00', strtotime("$s_year +1 year"));

        $start_time = strtotime(date('Y-m-01 00:00:00',strtotime('-1 month')));
        $stop_time = strtotime(date("Y-m-d 23:59:59", strtotime(-date('d').'day')));
        $find = Db::table('finance')->where('teacher_id',$this->user['id'])->where('start_time',$start_time)->find();
        if(!$find){
            $data = $this->finance_count($start_time,$stop_time,$this->user['id']);
            Db::table('finance')->insert(array('teacher_id'=>$this->user['id'],'start_time'=>$start_time,'stop_time'=>$stop_time,'time'=>time(),'money'=>$data['money'],'student_num'=>$data['num'],'class_count'=>$data['class_count']));
        }
        $body['list'] = Db::table('finance')->where('teacher_id',$this->user['id'])->where('start_time','>=',strtotime($s_year))->where('start_time','<=',strtotime($e_year))->limit($list*$val,$val)->order('id desc')->select();
        $body['count'] = Db::table('finance')->where('teacher_id',$this->user['id'])->where('start_time','>=',strtotime($s_year))->where('start_time','<=',strtotime($e_year))->count();
        $this->re_json($body);
    }

    //财务详情
    public function  finance_info(){
        $request = Request::instance();
        $finance_id =  $request->post('finance_id',true);
        $find = Db::table('finance')->where('id',$finance_id)->where('teacher_id',$this->user['id'])->find();
        if(!$find){
            $this->er_json("The financial information was not found",11055);
        }
        $body['list'] = Db::table('class_list')->where('teacher_id',$this->user['id'])->where('status',1)->where('start_time','<',$find['start_time'])->where('stop_time','>',$find['stop_time'])->select();
        foreach($body['list'] as $v=>$k){
            $body['list'][$v]['money'] = Db::table('order')->field('sum(price) as price')->where('type',2)->where('class_id',$k['id'])->find();
        }
        $this->re_json($body);
    }

    //财务申请
    public function  finance_update(){
        $request = Request::instance();
        $finance_id =  $request->post('finance_id',true);
        $find = Db::table('finance')->where('id',$finance_id)->where('teacher_id',$this->user['id'])->find();
        if(!$find){
            $this->er_json("The financial information was not found",11055);
        }
        if($find['status'] == 1){
            $this->re_json('');
        }
        if($find['status'] == 2){
            $this->re_json('');
        }
        if($find['money'] == 0){
            $this->er_json("The financial assets of the month are 0 and cannot be applied for.",11056);
        }
        Db::table('finance')->where('id',$finance_id)->update(array('time'=>time(),'status'=>1));
        $this->re_json('');
    }

    //教师认证
    public function  teacher_approve(){
        $request = Request::instance();
        $data['approve_photo1'] =  $request->post('approve_photo1',true);
        $data['approve_photo2'] =  $request->post('approve_photo2',true);
        $find = Db::table('teacher')->where('id',$this->user['id'])->find();
        if( $find['ziliao_type'] == 1){
            $this->er_json("under review",11054);
        }
        if($find['ziliao_type'] == 2){
            $this->re_json(array('approve_photo1'=>$find['approve_photo1'],'approve_photo2'=>$find['approve_photo2']));
        }
        if( $find['ziliao_type'] == 4){
            $this->er_json("Audit failed",11054);
        }
        $data['update_time'] = time();
        $data['ziliao_type'] = 1;
        Db::table('teacher')->where('id',$this->user['id'])->update($data);
        $this->re_json('');
    }

    //资料上传
    public function  teacher_data(){
        $request = Request::instance();
        $data['ziliao_photo'] =  $request->post('data_photo',true);
        $find = Db::table('teacher')->where('id',$this->user['id'])->find();
        if($find['ziliao_type'] == 1 || $find['ziliao_type'] == 2){
            $this->er_json("Submitted, don't submit again",11054);
        }
        $data['update_time'] = time();
        $data['ziliao_type'] = 1;
        Db::table('teacher')->where('id',$this->user['id'])->update($data);
        $this->re_json('');
    }


    //教师消息列表
    public function  teacher_message(){
        $request = Request::instance();
        $list =  $request->post('list',true);
        $val =  $request->post('val',true);
        $body = Db::table('teacher_message')->where('teacher_id',$this->user['id'])->order('time desc')->limit($list*$val,$val)->select();
        $this->re_json($body);
    }


    //教师消息详情
    public function  message_info(){
        $request = Request::instance();
        $message_id =  $request->post('message_id',true);
        $find = Db::table('teacher_message')->where('teacher_id',$this->user['id'])->where('id',$message_id)->find();
        if(!$find){
            $this->er_json("The message was not found",11057);
        }
        $body = array();
        switch($find['type']){
            case 0://调课消息
                $body = Db::query('select a.*,b.name as class_name,b.id as class_id,c.name as student_name,c.photo,c.id as student_id from revision_class as a left join class_list as b on a.class_id = b.id left join student as c on a.student_id = c.id where a.class_id = '.$find['type_id']);
                break;
        }
        Db::table('teacher_message')->where('id',$find['id'])->update(array('status'=>1));
        $this->re_json($body);
    }

    //教师消息处理-调课
    public function  revision_update(){
        $request = Request::instance();
        $message_id =  $request->post('message_id',true);
        $revision_id =  $request->post('revision_id',true);
        $data =  $request->post('data',true);
        $type =  $request->post('type',true);
        if($type != 1 && $type != 2){
            $this->er_json("Parameter error",11059);
        }
        $find = Db::table('teacher_message')->where('teacher_id',$this->user['id'])->where('id',$message_id)->find();
        if(!$find){
            $this->er_json("The message was not found",11057);
        }
        $revision = Db::table('revision_class')->where('class_id',$find['type_id'])->where('id',$revision_id)->find();
        if(!$revision){
            $this->er_json("The message was not found",11057);
        }
        if($revision['type'] != 0){
            $this->er_json("This application has been reviewed",11060);
        }
        Db::table('revision_class')->where('id',$revision['id'])->update(array('type'=>$type,'data'=>$data));
        $student =  Db::table('order')->where('class_id',$find['type_id'])->where('type',2)->select();
        $array = json_encode(['type'=>'teacher','user_id'=>$this->user['id'],'name'=>$this->user['name'],'photo'=>$this->user['photo']]);
        $class = Db::table('class_list')->where('id',$find['type_id'])->find();
        if($type == 1){
            Db::table('class_list')->where('id',$revision['class_id'])->update(array('start_time'=>$revision['start_time'],'stop_time'=>$revision['end_time']));
            //更新消息
            $body = '老师同意申请关于《'.$class['name'].'》'.date('m月d日',$class['start_time']).'的调课申请';
            foreach($student as $k){
                Db::table('student_message')->where('id',$k['id'])->update(array('time'=>time(),'status'=>0,'data'=>$array,'body'=>$body));
            }
            Db::table('teacher_message')->where('id',$message_id)->update(array('data'=>$array,'body'=>$body));
        }else{
            //更新消息
            $body = '老师拒绝申请关于《'.$class['name'].'》'.date('m月d日',$class['start_time']).'的调课申请';
            foreach($student as $k){
                Db::table('student_message')->where('id',$k['id'])->update(array('time'=>time(),'status'=>0,'data'=>$array,'body'=>$body));
            }
            Db::table('teacher_message')->where('id',$message_id)->update(array('data'=>$array,'body'=>$body));
        }
        $this->re_json('');
    }




    private function finance_count($start_time,$stop_time,$teacher_id){
        $list = Db::table('class_list')->where('teacher_id',$this->user['id'])->where('status',1)->where('start_time','<',$start_time)->where('stop_time','>',$start_time)->select();
        $data['class_count'] = count($list);
        $money =  Db::query("select sum(b.price) as price from class_list as a inner join `order` as b on a.id = b.class_id where b.type = 2 && a.teacher_id = ".$teacher_id." && a.status = 1 && a.start_time > ".$start_time." && a.start_time < ".$stop_time);
        $data['money'] = $money[0]['price']?:0;
        $num = Db::query("select count(*) as num from class_list as a inner join `order` as b on a.id = b.class_id left join student as c on b.student_id = c.id where b.type = 2 && a.teacher_id = ".$teacher_id." && a.status = 1 && a.start_time > ".$start_time." && a.start_time < ".$stop_time." group by b.student_id");
        $data['num'] = count($num);
    }




    //修改登陆密码
    public function  update_pass(){
        $request = Request::instance();
        $pass = $request->post('pass',true);
        $new_pass = $request->post('new_pass',true);
        if(MD5($pass) == $this->user['pass']){
            Db::table('teacher')->where('id',$this->user['id'])->update(['pass'=>MD5($new_pass)]);
            $this->re_json('');
        }else{
            $this->er_json('pass error',11043);
        }
    }

    //修改邮箱
    public function  update_email(){
        $request = Request::instance();
        $code = $request->post('code',true);
        $new_email = $request->post('new_email',true);
        #if($code != Cache::get('email_' . $new_email)){
        #    $this->er_json('code error',11092);
        #}
        $info = Db::table('teacher')->field('id,pass')->where('email',$new_email)->find();
        if($info){
            $this->er_json('The mailbox has been used',11041);
        }
        if($code){
            Db::table('teacher')->where('id',$this->user['id'])->update(['email'=>$new_email]);
            $this->re_json('');
        }else{
            $this->er_json('pass error',11043);
        }
    }

    //绑定手机号
    public function  set_phone(){
        $request = Request::instance();
        $phone = $request->post('phone',true);
        $code = $request->post('code',true);
        #if($code != Cache::get('phone_' . $phone)){
        #    $this->er_json('code error',11092);
        #}
        $info = Db::table('teacher')->field('id,phone')->where('phone',$phone)->find();
        if($info){
            $this->er_json('The number has been bound',11045);
        }
        Db::table('teacher')->where('id',$this->user['id'])->update(['phone'=>$phone]);
        $this->re_json('');
    }

}

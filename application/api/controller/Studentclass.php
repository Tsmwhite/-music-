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

class Studentclass extends Auth
{
    //根据时间课程列表
    public function  class_list()
    {
        $request = Request::instance();
        $time = $request->post('time', true);
        $start_time =  strtotime(date('Y-m-d 00:00:00', $time));
        $end_time =  strtotime(date('Y-m-d 23:59:59', $time));
        $list = Db::table('order')->alias('a')->join('teacher b', 'a.teacher_id = b.id')->join('class_list c', 'a.class_id = c.id')
            ->field('b.id,b.name,c.id as class_id,c.name as class_name,c.start_time,c.stop_time,a.punch')->where('a.student_id', $this->user['id'])->where('a.type', 1)
            ->where('c.start_time', '>', $start_time)->where('c.start_time', '<', $end_time)->select();
        $this->re_json($list);
    }


    //根据时间获取某个月内有课天数
    public function  class_list_m()
    {
        $request = Request::instance();
        $time =   $request->post('time', true);
        $start_time =  date('Y-m-01 00:00:00', $time);
        $end_time =  date('Y-m-01 00:00:00', strtotime("$start_time +1 month"));
        $list = Db::table('order')->alias('a')->join('class_list c', 'a.class_id = c.id')
            ->field("FROM_UNIXTIME(c.start_time,'%Y-%m-%d') as time1")->where('a.student_id', $this->user['id'])->where('a.type', 1)
            ->where('c.start_time', '>', strtotime($start_time))->where('c.start_time', '<', strtotime($end_time))->group('time1')->select();
        $body = [];
        foreach ($list as $k) {
            $body[] = $k['time1'];
        }
        $this->re_json($body);
    }

    //充值
    public function  money_add()
    {
        $request = Request::instance();
        $money_id =  $request->post('money_id', true);
        $money = Db::table('money_shop')->where('id', $money_id)->find();
        if (!$money) {
            $this->er_json("The recharge item was not found", 11061);
        }
        $array = array(
            'money' => $money['full_money'],
            'give_money' => $money['give_money'],
            'time' => time(),
            'user_id' => $this->user['id'],
        );
        Db::table('paypal_list')->insert($array);
        $money_shop_id = Db::name('paypal_list')->getLastInsID();
        //var_dump($money_shop_id);
        $data['order_id'] = $money_shop_id; //input('order_num'); // 订单号不能重复
        $data['order_name'] = 'Balance recharge'; //input('order_name');// 商品名称
        $data['order_money_id'] = $money['id']; ///input('order_num');// 商品编号
        $data['order_money'] = $money['full_money']; //input('order_money');// 支付金额
        $pay = new Paypal();
        $val = $pay->pay($data);
        $this->re_json($val); ///echo $val;
    }

    //充值记录
    public function  money_list()
    {
        $request = Request::instance();
        $list =  $request->post('list', true);
        $val =  $request->post('val', true);
        $list = Db::table('paypal_list')->field('money,give_money,ok_time')->where('type', 1)->where('user_id', $this->user['id'])->order('ok_time desc')
            ->limit($list * $val, $val)->select();
        $this->re_json($list);
    }

    //消费记录
    public function consumption_list()
    {
        $request = Request::instance();
        $list =  $request->post('list', true);
        $val =  $request->post('val', true);
        $list = Db::table('user_money_log')->field('time,money,type')->where('user_id', $this->user['id'])->order('id desc')
            ->limit($list * $val, $val)->select();
        $this->re_json($list);
    }

    //优惠券列表
    public function coupom_list()
    {
        $request = Request::instance();
        $list1 =  $request->post('list', true);
        $val =  $request->post('val', 0);
        $type =  $request->post('type', false);
        $class_list_id =  $request->post('class_list_id', '');
        $class_list_array = explode(",", $class_list_id);
        $coupon_id = $request->post('coupon_id', false);
        //where
        $where['student_id'] = $this->user['id'];
        if ($type !== false) {
            $where['type'] = $type;
        }
        //单条
        if ($class_list_array[0]) {
            $class_list_body = Db::table('class_list')->where('type', 1)->whereIn('id', $class_list_array)->select();
            if (!$class_list_body) {
                $this->er_json("Please select a sub-course", 11047);
            }
            //是否为vip
            //vip 免费一节课
            $music_vip = $this->user['is_vip'] == 2 ? true : false;
            if ($music_vip && $class_list_body) {
                $music = Db::table('classss')->where('id', $class_list_body[0]['class_id'])->find();
                $vip_log = Db::table('vip_music')->where('music_id', $music['music_id'])->where('user_id', $this->user['id'])->find();
                if ($vip_log) {
                    $music_vip = false;
                }
            }
            $money = 0;
            foreach ($class_list_body as $v => $k) {
                if ($music_vip != true || $v != 0) {
                    $money += $k['price'];
                } else {
                    $k['money'] = 0;
                }
            }
            //根据价格获取最优优惠券
            if ($coupon_id) {
                $body = $this->good_coupom_find($money, $coupon_id);
                $this->re_json($body);
            }
            $where['price'] = ['<=', $money];
        }
        //多条
        $list = Db::table('student_coupon')->field('id,end_time,price,d_price,discount,type,name')->where($where)->where('status', 0)->where('end_time', '>', time())->order('id desc');
        if ($val) {
            $list = $list->limit($list1 * $val, $val);
        }
        $list = $list->select();
        $this->re_json($list);
    }

    //最优优惠券
    private function good_coupom_find($money, $coupon_id)
    {
        $body['d_price'] = 0;
        $body['coupom'] = [];
        //if($coupon_id !== false){
        $coupom_info = Db::table('student_coupon')->where(array('student_id' => $this->user['id'], 'id' => $coupon_id))->where('status', 0)->find();
        if ($coupom_info) {
            if ($coupom_info['price'] <= $money) {
                if ($coupom_info['type'] == 0) {
                    $body['coupom'] = $coupom_info;
                    $body['d_price'] = $coupom_info['d_price'];
                } else {
                    $d_price = $money * (1 - ($coupom_info['discount'] / 100));
                    $body['coupom'] = $coupom_info;
                    $body['d_price'] = $d_price;
                }
            }
        }
        $body['real_price'] = $money - $body['d_price'];
        $body['real_price'] = $body['real_price'] < 0 ? 0 : $body['real_price'];
        return $body;
        //}
        //从所有优惠券中判断
        /*$coupom = Db::table('student_coupon')->where(array('student_id'=>$this->user['id']))->where('price','<=',$money)->where('status',0)->where('end_time','>',time())->select();
        foreach ($coupom as $k) {
            if($k['price'] <= $money){
                if($k['type'] == 0){
                    if($k['d_price'] > $body['d_price']){
                        $body['coupom'] = $k;
                        $body['d_price'] = $k['d_price'];
                    }
                }else{
                    $k['d_price'] = ($money * (1 - ($k['discount']/100)));
                    if($k['d_price'] > $body['d_price']){
                        $body['coupom'] = $k;
                        $body['d_price'] = $k['d_price'];
                    }
                }
            }
        }
        $body['real_price'] = $money - $body['d_price'];
        $body['real_price'] = $body['real_price'] < 0?0:$body['real_price'];
        $body['coupom']['real_price'] = $body['real_price'];
        $body['coupom']['d_price'] = $body['d_price'];
        return $body['coupom'];*/
    }

    //根据状态列表
    public function  class_type()
    {
        $request = Request::instance();
        $type =  $request->post('type', true);
        $list =  $request->post('list', true);
        $val =  $request->post('val', true);
        $where = ['a.student_id' => $this->user['id']];
        if ($type != -1) {
            $where['a.type'] = $type;
        }
        $list = Db::table('order')->alias('a')->join('teacher b', 'a.teacher_id = b.id')->join('class_list c', 'a.class_id = c.id')->join('classss d', 'c.class_id = d.id')
            ->field('a.id as order_id,a.type,b.id,b.name as teacher_name,c.id as class_id,d.name as class_name,d.photo as class_photo,c.start_time,c.stop_time,a.punch')->where($where)
            ->limit($list * $val, $val)->select();
        $this->re_json($list);
    }

    //打卡
    public function  punch()
    {
        $request = Request::instance();
        $class_id =  $request->post('class_id', true);
        $find = Db::table('order')->where('student_id', $this->user['id'])->where('class_id', $class_id)->where('punch', 0)->find();
        if (!$find) {
            $this->er_json("This course cannot be punched", 11046);
        } else {
            $time = time();
            Db::table('order')->where('id', $find['id'])->update(array('punch' => 1));
            Db::table('teacher_order')->where('class_id', $class_id)->where('teacher_id', $find['teacher_id'])->update(array('punch' => 1));
            Db::table('punch')->insert(array('class_id' => $find['class_id'], 'student_id' => $this->user['id'], 'teacher_id' => $find['teacher_id'], 'time' => $time));
        }
        $punch =  Db::table('punch')->where(array('class_id' => $find['class_id'], 'student_id' => $this->user['id'], 'teacher_id' => $find['teacher_id'], 'time' => $time))->find();
        $teacher_info  = Db::table('teacher')->where('id', $find['teacher_id'])->find();
        $class_list_info  = Db::table('class_list')->where('id', $find['class_id'])->find();
        $class_info = Db::table('classss')->where('id', $class_list_info['class_id'])->find();
        $body['punch_id'] = $punch['id'];
        $body['teacher_name'] = $teacher_info['name'];
        $body['class_name'] = $class_info['name'];
        $body['start_time'] = $class_list_info['start_time'];
        $body['stop_time'] = $class_list_info['stop_time'];
        $this->re_json($body);
    }

    //打卡评价及星级
    public function  assess()
    {
        $request = Request::instance();
        $punch_id =  $request->post('punch_id', true);
        $data['content'] =  $request->post('content', true);
        $data['star'] =  $request->post('star', true);
        if ($data['star'] > 10 || $data['star'] < 0) {
            $this->er_json("Score is 0-10", 11052);
        }
        $find = Db::table('punch')->where('id', $punch_id)->where('student_id', $this->user['id'])->find();
        if (!$find) {
            $this->er_json("Did not find the specified punch information", 11053);
        }
        Db::table('punch')->where('id', $punch_id)->update($data);
        $this->re_json('');
    }


    //老师笔记及学生作业
    public function  notes()
    {
        $request = Request::instance();
        $class_id =  $request->post('class_id', true);
        $find = Db::table('order')->where('student_id', $this->user['id'])->where('class_id', $class_id)->where('punch', 1)->find();
        if (!$find) {
            $this->er_json("Did not find the course", 11046);
        }
        $body['teacher'] = Db::table('teacher_order')->field('notes_photo,notes_video,notes_content')->where('class_id', $class_id)->where('teacher_id', $find['teacher_id'])->find();
        $body['student'] = Db::table('order')->field('notes_photo,notes_video,notes_content')->where('class_id', $class_id)->where('student_id', $this->user['id'])->find();
        $this->re_json($body);
    }

    //学生提交作业
    public function  student_notes()
    {
        $request = Request::instance();
        $class_id =  $request->post('class_id', true);
        $data['notes_photo'] =  $request->post('notes_photo', true);
        $data['notes_video'] =  $request->post('notes_video', true);
        $data['notes_content'] =  $request->post('notes_content', true);
        $find = Db::table('order')->where('student_id', $this->user['id'])->where('class_id', $class_id)->where('punch', 1)->find();
        if (!$find) {
            $this->er_json("This course cannot be punched", 11046);
        }
        Db::table('order')->where('class_id', $class_id)->where('student_id', $this->user['id'])->update($data);
        $this->re_json('');
    }

    //学生消息列表
    public function  student_message()
    {
        $request = Request::instance();
        $list =  $request->post('list', true);
        $val =  $request->post('val', true);
        $body = Db::table('student_message')->where('student_id', $this->user['id'])->order('time desc')->limit($list * $val, $val)->select();
        $this->re_json($body);
    }

    //学生消息详情
    public function  message_info()
    {
        $request = Request::instance();
        $message_id =  $request->post('message_id', true);
        $find = Db::table('student_message')->where('student_id', $this->user['id'])->where('id', $message_id)->find();
        if (!$find) {
            $this->er_json("The message was not found", 11057);
        }
        $body = array();
        switch ($find['type']) {
            case 0: //调课消息
                $body = Db::query('select a.*,b.name as class_name,b.id as class_id,c.name as student_name,c.photo,c.id as student_id from revision_class as a left join class_list as b on a.class_id = b.id left join student as c on a.student_id = c.id where a.class_id = ' . $find['type_id']);
                break;
        }
        Db::table('student_message')->where('id', $find['id'])->update(array('status' => 1));
        $this->re_json($body);
    }




    //调课
    public function  revision_class()
    {
        $request = Request::instance();
        $data['class_id'] =  $request->post('class_id', true);
        $data['start_time'] =  $request->post('start_time', true);
        $data['body'] =  $request->post('body', true);
        $data['end_time'] =  $request->post('end_time', true);
        $order = Db::table('order')->where('type', 1)->where('student_id', $this->user['id'])->where('class_id', $data['class_id'])->find();
        if (!$order) {
            $this->er_json("The course was not found", 11059);
        }
        $class = Db::table('class_list')->where('id', $data['class_id'])->find();
        $find = Db::table('revision_class')->where('type', 0)->where('student_id', $this->user['id'])->where('class_id', $data['class_id'])->find();
        if ($find) {
            $this->er_json("Submitted, don't submit again", 11054);
        }
        $data['student_id'] =  $this->user['id'];
        $data['time'] = time();
        $find = Db::table('teacher_message')->where('type_id', $data['class_id'])->find();
        //$teacher = Db::table('class_list')->where('music_sun_id',$data['class_id'])->find();
        //$teacher = Db::table('music_teacher_list')->where('music_sun_id',$data['class_id'])->find();
        $student =  Db::table('order')->where('class_id', $data['class_id'])->where('type', 1)->select();
        $array = json_encode(['type' => 'student', 'user_id' => $this->user['id'], 'name' => $this->user['name'], 'photo' => $this->user['photo']]);
        $body = '"' . $this->user['name'] . '" 学生申请关于《' . $class['name'] . '》' . date('m月d日', $class['start_time']) . '的调课申请';
        if (!$find) {
            if ($class) {
                Db::table('teacher_message')->insert(array('teacher_id' => $class['teacher_id'], 'time' => time(), 'type_id' => $data['class_id'], 'type' => 0, 'data' => $array, 'body' => $body));
            }
            foreach ($student as $k) {
                if ($k['student_id'] != $this->user['id']) {
                    Db::table('student_message')->insert(array('student_id' => $k['student_id'], 'time' => time(), 'type_id' => $data['class_id'], 'type' => 0, 'data' => $array, 'body' => $body));
                } else {
                    Db::table('student_message')->insert(array('student_id' => $k['student_id'], 'time' => time(), 'type_id' => $data['class_id'], 'type' => 0, 'status' => 1, 'data' => $array, 'body' => $body));
                }
            }
        } else {
            if ($class) {
                Db::table('teacher_message')->where('id', $class['id'])->update(array('time' => time(), 'status' => 0, 'data' => $array, 'body' => $body));
            }
            foreach ($student as $k) {
                if ($k['student_id'] != $this->user['id']) {
                    Db::table('student_message')->where('id', $k['id'])->update(array('time' => time(), 'status' => 0, 'data' => $array, 'body' => $body));
                } else {
                    Db::table('student_message')->where('id', $k['id'])->update(array('data' => $array, 'body' => $body));
                }
            }
        }
        Db::table('revision_class')->insert($data);
        $this->re_json('');
    }


    //意见反馈
    public function  feed_back()
    {
        $request = Request::instance();
        $body =  $request->post('body', true);
        $contact =  $request->post('contact', true);
        Db::table('feedback')->insert(array('user_id' => $this->user['id'], 'time' => time(), 'body' => $body, 'contact' => $contact));
        $this->re_json('');
    }




    //评价
    public function  user_assess()
    {
        $request = Request::instance();
        $order_id =  $request->post('order_id', true);
        $data['class_content'] =  $request->post('class_content', true);
        $data['class_star'] =  $request->post('class_star', true);
        $data['teacher_content'] =  $request->post('teacher_content', true);
        $data['teacher_star'] =  $request->post('teacher_star', true);
        $data['teacher_teach_star'] =  $request->post('teacher_teach_star', true);
        $data['teacher_teach_mode_star'] =  $request->post('teacher_teach_mode_star', true);
        $data['teacher_teach_bearing_star'] =  $request->post('teacher_teach_bearing_star', true);
        if ($data['class_star'] > 10 || $data['class_star'] < 0 || $data['teacher_star'] > 10 || $data['teacher_star'] < 0 || $data['teacher_teach_star'] > 10 || $data['teacher_teach_star'] < 0 || $data['teacher_teach_mode_star'] > 10 || $data['teacher_teach_mode_star'] < 0 || $data['teacher_teach_bearing_star'] > 10 || $data['teacher_teach_bearing_star'] < 0) {
            $this->er_json("Score is 0-10", 11052);
        }
        $order = Db::table('order')->alias('a')->join('class_list b', 'a.class_id = b.id')->field('a.*,b.class_id as class_real_id')->where('a.id', $order_id)->where('a.student_id', $this->user['id'])->find();
        if (!$order) {
            $this->er_json("Did not find the course", 11066);
        }
        if ($order['type'] != 2) {
            $this->er_json("The course has not started", 11067);
        }
        if ($order['type'] == 3) {
            $this->er_json("The course has been evaluated", 11150);
        }
        $array = array(
            'class_id' => $order['class_real_id'],
            'order_id' => $order['id'],
            'user_id' => $this->user['id'],
            'class_list_id' => $order['class_id'],
            'teacher_id' => $order['teacher_id'],
            'time' => time(),
            'class_content' => $data['class_content'],
            'class_star' => $data['class_star'],
            'teacher_content' => $data['teacher_content'],
            'teacher_star' => $data['teacher_star'],
            'teacher_teach_star' => $data['teacher_teach_star'],
            'teacher_teach_mode_star' => $data['teacher_teach_mode_star'],
            'teacher_teach_bearing_star' => $data['teacher_teach_bearing_star']
        );
        Db::table('assess')->insert($array);
        Db::table('order')->where('id', $order['id'])->update(array('type' => 3));
        $this->re_json('');
    }

    //我的评价
    public function my_assess()
    {
        $request = Request::instance();
        $type =  $request->post('type', true);
        $list =  $request->post('list', true);
        $val =  $request->post('val', true);
        if ($type == 2) {
            $list = Db::table('order')->alias('a')->join('teacher b', 'a.teacher_id = b.id')->join('class_list c', 'a.class_id = c.id')
                ->field('b.id,a.id as order_id,a.type,b.name,c.id as class_id,c.name as class_name,c.start_time,c.stop_time,a.punch')->where('a.type', 2)->where('a.student_id', $this->user['id'])
                ->limit($list * $val, $val)->select();
        } else {
            $list = Db::table('order')->alias('a')->join('teacher b', 'a.teacher_id = b.id')->join('class_list c', 'a.class_id = c.id')->join('assess d', 'a.id = d.order_id')
                ->field('b.id,a.id as order_id,a.type,b.name,c.id as class_id,c.name as class_name,c.start_time,c.stop_time,a.punch')->where('a.type', 3)->where('a.student_id', $this->user['id'])
                ->limit($list * $val, $val)->select();
        }
        foreach ($list as $v => $k) {
            $body = Db::table('classss')->alias('a')->join('assess b', 'a.id = b.class_id')
                ->field('sum(class_star) as star')->where('a.id', $k['class_id'])->find();
            $count = Db::table('classss')->alias('a')->join('assess b', 'a.id = b.class_id')->where('a.id', $k['class_id'])->count();
            $list[$v]['class_star'] = (float)$body['star'] ? number_format($body['star'] / $count, 1, '.', '') : 0.0;
        }
        $this->re_json($list);
    }
}

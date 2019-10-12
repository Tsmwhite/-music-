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

class Userorder extends Auth
{
    //教师列表
    public function  teacher_list()
    {
        $request = Request::instance();
        $music_id =  $request->post('music_id', true);
        $music_sun_id =  $request->post('music_sun_id', true);
        $list1 =  $request->post('list', true);
        $val =  $request->post('val', true);
        $music_sun = Db::table('classss')->field('name,photo,content')->where('id', $music_sun_id)->find();

        $list = Db::table('classss')->alias('a')->join('assess b', 'a.id = b.class_id')
            ->field('sum(class_star) as star')->where('a.id', $music_sun_id)->find();
        $count = Db::table('classss')->alias('a')->join('assess b', 'a.id = b.class_id')->where('a.id', $music_sun_id)->count();
        $data['music_sun'] = (float)$list['star'] ? number_format($list['star'] / $count, 1, '.', '') : 0.0;

        $data['list'] = Db::table('music_teacher_list')->alias('a')->join('teacher b', 'a.teacher_id = b.id')->join('music c', 'b.music = c.id')
            ->field('b.id,b.photo,b.name,b.content,a.id as music_teacher_list_id,c.name as music_name')->where('a.music_id', $music_id)->where('a.music_sun_id', $music_sun_id)->limit($list1 * $val, $val)->select();
        $data['count'] = Db::table('music_teacher_list')->where('music_id', $music_id)->where('music_sun_id', $music_sun_id)->count();

        foreach ($data['list'] as $v => $k) {
            $list = Db::table('assess')->field('sum(teacher_star) as star')->where('teacher_id', $k['id'])->find();
            $count = Db::table('assess')->where('teacher_id', $k['id'])->count();
            $data['list'][$v]['star'] = (float)$list['star'] ? number_format($list['star'] / $count, 1, '.', '') : 0.0;
        }
        $this->re_json($data);
    }

    //教师详情
    public function  teacher_info()
    {
        $request = Request::instance();
        $teacher_id =  $request->post('teacher_id', true);
        $teacher['info'] = Db::table('teacher')->where('id', $teacher_id)->find();
        $teacher['list'] = Db::table('music_teacher_list')->alias('a')->join('teacher b', 'a.teacher_id = b.id')->join('music c', 'b.music = c.id')
            ->field('c.id,c.name,c.photo,c.content')->where('a.teacher_id', $teacher_id)->select();
        foreach ($teacher['list'] as $v => $k) {
            $list = Db::table('classss')->alias('a')->join('assess b', 'a.id = b.class_id')
                ->field('sum(class_star) as star')->where('a.id', $k['id'])->find();
            $count = Db::table('classss')->alias('a')->join('assess b', 'a.id = b.class_id')->where('a.id', $k['id'])->count();
            $teacher['list'][$v]['star'] = (float)$list['star'] ? number_format($list['star'] / $count, 1, '.', '') : 0.0;
            $teacher['list'][$v]['teacher_count'] = Db::table('music_teacher_list')->where('music_sun_id', $k['id'])->group('teacher_id')->count();
        }
        $music = Db::table('music')->where('id', $teacher['info']['music'])->find();
        $teacher['info']['music_name'] = $music['name'];

        $list = Db::table('assess')->field('sum(teacher_star) as star')->where('teacher_id', $teacher_id)->find();
        $count = Db::table('assess')->where('teacher_id', $teacher_id)->count();
        $teacher['info']['star'] = (float)$list['star'] ? number_format($list['star'] / $count, 1, '.', '') : 0.0;

        unset($teacher['info']['pass']);
        unset($teacher['info']['token']);
        $teacher['style'][] = $teacher['info']['teacher_style1_photo'];
        $teacher['style'][] = $teacher['info']['teacher_style2_photo'];
        $teacher['style'][] = $teacher['info']['teacher_style3_photo'];
        $teacher['style'][] = $teacher['info']['teacher_style4_photo'];
        $teacher['style'][] = $teacher['info']['teacher_style5_photo'];
        $teacher['style'][] = $teacher['info']['teacher_style6_photo'];
        unset($teacher['info']['teacher_style1_photo']);
        unset($teacher['info']['teacher_style2_photo']);
        unset($teacher['info']['teacher_style3_photo']);
        unset($teacher['info']['teacher_style4_photo']);
        unset($teacher['info']['teacher_style5_photo']);
        unset($teacher['info']['teacher_style6_photo']);
        $this->re_json($teacher);
    }

    //时间填写
    public function  add_time()
    {
        $request = Request::instance();
        $teacher_id =  $request->post('teacher_id', true);
        $music_sun_id =  $request->post('music_sun_id', true);
        $invite =  $request->post('invite', true);
        $invite_info  = Db::table('yaoqing')->where('id', $this->decode($invite))->find();
        if ($invite_info) {
            if ($invite_info['user_id'] == $this->user['id']) {
                $this->er_json("Can't invite yourself", 11111);
            }
            if ($invite_info['time'] < (time() - 3600)) {
                $this->er_json("Invitation code has expired", 11069);
            }
        }
        $is_invite = $invite_info ? 1 : 0;
        $body['type'] =  $is_invite;
        $body['people_num'] = 0;
        //获取已选择课程
        $body['list'] = [];
        if ($is_invite) {
            $body['people_num'] = $invite_info['people_num'];
            $body['list'] =  Db::table('class_list')->field('id,start_time,stop_time,teacher_id,class_id,price')->whereIn('id', json_decode($invite_info['body'], true))->where('type', 1)->select();
            if (!$body['list']) {
                $this->er_json("The number of invitees is full", 11069);
            }
            $teacher_id = $body['list'][0]['teacher_id'];
            $music_sun_id = $body['list'][0]['class_id'];
        } else {
            $body['list'] = Db::table('class_list')->field('id,start_time,stop_time,price')->whereIn('student_id', $this->user['id'])->where('type', 1)->select();
            //$body['list'] = array_merge($body['list'],$or);
            //查询该教师是否教该课程
            $is_teacher = Db::table('music_teacher_list')->where('teacher_id', $teacher_id)->where('music_sun_id', $music_sun_id)->find();
            if (!$is_teacher) {
                $this->er_json("Did not find the teacher's course", 11046);
            }
        }

        //剔除已报名
        foreach ($body['list'] as $v => $k) {
            $order = Db::table('order')->where('student_id', $this->user['id'])->where('class_id', $k['id'])->where('teacher_id', $teacher_id)->find();
            if ($order) {
                unset($body['list'][$v]);
            }
        }
        $body['list'] = array_values($body['list']);

        $body['class_info'] = Db::table('classss')->field('name,content,photo,mix_time_type,max_time_type')->where('id', $music_sun_id)->find();
        $list = Db::table('classss')->alias('a')->join('assess b', 'a.id = b.class_id')
            ->field('sum(class_star) as star')->where('a.id', $music_sun_id)->find();
        $count = Db::table('classss')->alias('a')->join('assess b', 'a.id = b.class_id')->where('a.id', $music_sun_id)->count();
        $body['class_info']['star'] = (float)$list['star'] ? number_format($list['star'] / $count, 1, '.', '') : 0.0;


        $body['teacher_info'] = Db::table('teacher')->field('name,content,photo')->where('id', $teacher_id)->find();
        $list = Db::table('assess')->field('sum(teacher_star) as star')->where('teacher_id', $teacher_id)->find();
        $count = Db::table('assess')->where('teacher_id', $teacher_id)->count();
        $body['teacher_info']['star'] = (float)$list['star'] ? number_format($list['star'] / $count, 1, '.', '') : 0.0;

        $body['is_invite'] = $is_invite;
        $this->re_json($body);
    }

    public function time_list()
    {
        $request = Request::instance();
        $class_id =  $request->post('class_id', true);
        $class = Db::table('classss')->where('id', $class_id)->find();
        $body = [];
        for ($i = $class['mix_time_type']; $i <= $class['max_time_type']; $i += 30) {
            $body[] = (int)$i;
        }
        $this->re_json($body);
    }

    //学生添加课程
    public function  add_class()
    {
        $request = Request::instance();
        $teacher_id =  $request->post('teacher_id', true);
        $music_sun_id =  $request->post('music_sun_id', true);
        $people_num =  $request->post('people_num', true);
        $start_time =  $request->post('start_time', true);
        $end_time =  $request->post('end_time', true);
        //查询该教师是否教该课程
        $is_teacher = Db::table('music_teacher_list')->where('teacher_id', $teacher_id)->where('music_sun_id', $music_sun_id)->find();
        if (!$is_teacher) {
            $this->er_json("Did not find the teacher's course", 11046);
        }
        $where = ' teacher_id = ' . $teacher_id . ' && ((start_time <= ' . $start_time . ' && stop_time >= ' . $start_time . ')|| (start_time <= ' . $end_time . ' && stop_time >= ' . $end_time . '))';
        $find = Db::table('class_list')->where('teacher_id', $teacher_id)->where($where)->find();
        if ($find) {
            $this->er_json("The teacher is busy during this time", 11068);
        } else {
            $class =  Db::table('classss')->where('id', $music_sun_id)->find();
            if ($people_num !== true) {
                if ($people_num != 0) {
                    $class['money'] = $class['money' . ($people_num + 1)];

                    //$os = Db::table('class_list')->where('class_id',$music_sun_id)->where('type',1)->select();

                }
            }
            $price = ($class['money'] * ($end_time - $start_time) / 60);
            Db::table('class_list')->insert(array('class_id' => $music_sun_id, 'student_id' => $this->user['id'], 'name' => $class['name'], 'start_time' => $start_time, 'stop_time' => $end_time, 'time' => time(), 'teacher_id' => $teacher_id, 'price' => $price));
            $this->re_json(array('id' => Db::name('class_list')->getLastInsID(), 'start_time' => $start_time, 'stop_time' => $end_time, 'price' => $price));
        }
    }



    //学生删除课程
    public function  delete_class()
    {
        $request = Request::instance();
        $class_id =  $request->post('class_id', true);
        $class_id_array = explode(",", $class_id);
        foreach ($class_id_array as $k) {
            //查询该教师是否教该课程
            $class = Db::table('class_list')->where('type', 1)->where('id', $k)->where('student_id', $this->user['id'])->find();
            if (!$class) {
                continue;
            }
            Db::table('class_list')->where('id', $k)->delete();
        }
        $this->re_json('');
    }


    // 单独支付一节课
    public function one_add_order()
    {
        $request = Request::instance();
        $class_list_id =  $request->post('class_list_id', true);
        $class_list =  Db::table('class_list')->where('student_id', $this->user['id'])->where('type', 1)->whereIn('id', $class_list_id)->find();
        if (!$class_list) {
            $this->er_json("No specified course found", 12020);
        }
        //vip 免费一节课
        $music_vip = $this->user['is_vip'] == 2 ? false : true;
        if (!$music_vip) {
            $this->er_json("Non-members can purchase", 12021);
        }
        $array = array(
            'money' => $class_list['price'],
            'time' => time(),
            'user_id' => $this->user['id'],
        );
        Db::table('one_paypal_list')->insert($array);
        $money_shop_id = Db::name('paypal_list')->getLastInsID();
        $data['order_id'] = $money_shop_id; //input('order_num'); // 订单号不能重复
        $data['order_name'] = 'Course payment'; //input('order_name');// 商品名称
        $data['order_money_id'] = $class_list['id']; ///input('order_num');// 商品编号
        $data['order_money'] = $class_list['price']; //input('order_money');// 支付金额
        $pay = new Paypal();
        $val = $pay->onepay($data);
        $this->re_json($val);
    }




    //确认课程
    public function  add_order()
    {
        $request = Request::instance();
        $class_list_id =  $request->post('class_list_id', true);
        $teacher_id =  $request->post('teacher_id', true);
        $music_sun_id =  $request->post('music_sun_id', true);
        $people_num =  $request->post('people_num', true);
        $coupon_id =  $request->post('coupon_id', true);
        $class_list_id = explode(",", $class_list_id);
        //是否为邀请课程
        $invite =  $request->post('invite', true);
        $invite_info  = Db::table('yaoqing')->where('id', $this->decode($invite))->find();
        if ($invite_info) {
            if ($invite_info['time'] < (time() - 3600)) {
                $this->er_json("Invitation code expired", 11069);
            }
            $class_list_id = json_decode($invite_info['body']);
            $people_num = $invite_info['people_num'];
            $class_list_all =  Db::table('class_list')->field('id,start_time,stop_time,teacher_id,class_id,price')->whereIn('id', json_decode($invite_info['body'], true))->where('type', 1)->select();
            if (!$class_list_all) {
                $this->er_json("Invitation code expired", 11069);
            }
            $teacher_id = $class_list_all[0]['teacher_id'];
            $music_sun_id = $class_list_all[0]['class_id'];
        }

        //邀请参数替换结束
        $teacher = Db::table('teacher')->where('id', $teacher_id)->find();
        if (!$teacher) {
            $this->er_json("Please select a sub-course", 11047);
        }

        if (!$class_list_id[0]) {
            $this->er_json("Please select a sub-course", 11047);
        }
        //vip 免费一节课
        $music_vip = $this->user['is_vip'] == 2 ? true : false;
        if ($music_vip) {
            $music = Db::table('classss')->where('id', $music_sun_id)->find();
            $vip_log = Db::table('vip_music')->where('music_id', $music['music_id'])->where('user_id', $this->user['id'])->find();
            if ($vip_log) {
                $music_vip = false;
            }
        }
        if ($people_num > 1) {
            $is_num  = true;
        }

        $list =  Db::table('class_list')->where('class_id', $music_sun_id)->where('teacher_id', $teacher_id)->where('type', 1)->whereIn('id', $class_list_id)->select();
        $data = [];
        $money = 0;
        foreach ($list as $v => $k) {
            $class_info = Db::table('order')->where('class_id', $k['id'])->where('teacher_id', $k['teacher_id'])->where('student_id', $this->user['id'])->find();
            if (!$class_info || $k['type'] != 1) {
                if ($music_vip != true || $v != 0) {
                    $money += $k['price'];
                } else {
                    $k['money'] = 0;
                }
                $data[] = $k;
            }
        }
        if ($data) {
            $coupon = $this->coupon($money, $coupon_id); //var_dump($coupon);exit;
            $real_money = ($money - $coupon['coupon_money']) < 0 ? 0 : ($money - $coupon['coupon_money']);
            if ($this->user['money'] < $real_money) {
                $this->er_json("Insufficient balance", 11062);
            } else {

                Db::name("student")->where('id', $this->user['id'])->update(['money' => Db::raw('money-' . $real_money), 'integral' => Db::raw('integral+' . $real_money)]);
                Db::table('user_money_log')->insert(array('user_id' => $this->user['id'], 'time' => time(), 'money' => $real_money, 'coupon_id' => $coupon['id'], 'coupon_money' => $coupon['coupon_money']));
                foreach ($data as $r) {
                    //判断是否人齐
                    $num_val = Db::table('order')->where('class_id', $k['id'])->where('teacher_id', $k['teacher_id'])->count();
                    if ($num_val >= $people_num) {
                        Db::table('class_list')->where('id', $r['id'])->update(array('type' => 3));
                    } else {
                        //生成邀请码
                        if ($people_num != 0) {
                            $yqm[] = $r['id'];
                        }
                    }
                    Db::table('order')->insert(array('class_id' => $r['id'], 'student_id' => $this->user['id'], 'teacher_id' => $r['teacher_id'], 'type' => 1, 'time' => time(), 'price' => $r['price']));
                }
                if ($music_vip) {
                    Db::table('vip_music')->insert(array('music_id' => $music['music_id'], 'user_id' => $this->user['id'], 'time' => time()));
                }

                $body = '';
                if (isset($yqm)) {
                    $yqm = json_encode($yqm);
                    Db::table('yaoqing')->insert(array('user_id' => $this->user['id'], 'body' => $yqm, 'time' => time(), 'people_num' => $people_num));
                    $body = $this->encode(Db::name('yaoqing')->getLastInsID());
                }

                //支付订单
                $peo_name = '';
                if ($people_num == 0) {
                    $peo_name = '一对一';
                }
                if ($people_num == 1) {
                    $peo_name = '一对二';
                }
                if ($people_num == 2) {
                    $peo_name = '一对三';
                }
                $trade['price'] = $real_money;
                $trade['time'] = time();
                $trade['student_id'] = $this->user['id'];
                $trade['class_list_id'] = json_encode($class_list_id);
                $trade['teacher_id'] = $teacher_id;
                $trade['class_id'] = $music_sun_id;
                $trade['people_num'] = $peo_name;
                $trade['student_name'] = $this->user['name'];
                $trade['class_name'] = $music['name'];
                $trade['teacher_name'] = $teacher['name'];
                $trade['value'] = count($data);
                Db::table('trade')->insert($trade);
                $this->re_json($body);
            }
        } else {
            $this->er_json("No class available", 11063);
        }
    }



    //使用优惠券
    private function coupon($money, $coupon_id)
    {
        $coupon = Db::name("student_coupon")->where('student_id', $this->user['id'])->where('end_time', '>', time())->where('id', $coupon_id)->where('status', 0)->find();
        $data['coupon_money'] = 0;
        $data['id'] = '';
        if ($coupon) {
            if ($coupon['type'] == 0) {
                if ($money >= $coupon['price']) {
                    $data['coupon_money'] += $coupon['d_price'];
                    Db::name("student_coupon")->where('id', $coupon['id'])->update(array('status' => 1, 's_time' => time()));
                } else {
                    $data['id'] = '';
                }
            } else if ($coupon['type'] == 1) {
                $data['coupon_money'] = $money * (1 - ($coupon['discount'] / 100));
                Db::name("student_coupon")->where('id', $coupon['id'])->update(array('status' => 1, 's_time' => time()));
            }
        }
        return $data;
    }



    //加密函数
    function encode($txt, $key = 'maxinyu')
    {
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-=+";
        $nh = rand(0, 64);
        $ch = $chars[$nh];
        $mdKey = md5($key . $ch);
        $mdKey = substr($mdKey, $nh % 8, $nh % 8 + 7);
        $txt = base64_encode($txt);
        $tmp = '';
        $i = 0;
        $j = 0;
        $k = 0;
        for ($i = 0; $i < strlen($txt); $i++) {
            $k = $k == strlen($mdKey) ? 0 : $k;
            $j = ($nh + strpos($chars, $txt[$i]) + ord($mdKey[$k++])) % 64;
            $tmp .= $chars[$j];
        }
        return urlencode($ch . $tmp);
    }
    //解密函数
    function decode($txt, $key = 'maxinyu')
    {
        $txt = urldecode($txt);
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-=+";
        $ch = $txt[0];
        $nh = strpos($chars, $ch);
        $mdKey = md5($key . $ch);
        $mdKey = substr($mdKey, $nh % 8, $nh % 8 + 7);
        $txt = substr($txt, 1);
        $tmp = '';
        $i = 0;
        $j = 0;
        $k = 0;
        for ($i = 0; $i < strlen($txt); $i++) {
            $k = $k == strlen($mdKey) ? 0 : $k;
            $j = strpos($chars, $txt[$i]) - $nh - ord($mdKey[$k++]);
            while ($j < 0) $j += 64;
            $tmp .= $chars[$j];
        }
        return base64_decode($tmp);
    }
}

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
class Friend extends AuthTeacherStudent
{
    //获取朋友圈内容
    public function  list(){
        $request = Request::instance();
        $val = $request->post('val',true)?:20;
        $list = $request->post('list',true)?:0;
        $friend = Db::table('friend_message')->limit(($list*$val),$val)->order('id desc')->select();
        foreach($friend as $v=>$k){
            if($k['type'] == 1){
                $info = Db::table('student')->field('photo,name')->where('id',$k['people_id'])->find();
            }else{
                $info = Db::table('teacher')->field('photo,name')->where('id',$k['people_id'])->find();
            }
            $friend[$v]['people_photo'] = $info['photo'];
            $friend[$v]['people_name'] = $info['name'];
            $friend[$v]['praise_num'] = Db::table('praise')->where('message_id',$k['id'])->count();
            $friend[$v]['message_num'] = Db::table('message')->where('message_id',$k['id'])->count();
            $friend[$v]['forward_num'] = Db::table('forward')->where('message_id',$k['id'])->count();
	//我是否点赞过
        $type = $this->role == 'student'?1:2;
        $praise = Db::table('praise')->where('people_id',$this->user['id'])->where('message_id',$k['id'])->where('type',$type)->find();
        $friend[$v]['is_praise'] = $praise ? 1:0;
        $forward = Db::table('forward')->where('people_id',$this->user['id'])->where('message_id',$k['id'])->where('type',$type)->find();
        $friend[$v]['is_forward'] = $forward ? 1:0;
        }
        $this->re_json($friend);
    }


    //刷新token
    public function  token_refresh(){
        $request = Request::instance();
        $token = createToken(array($this->role.'_id'=>$this->user['id']));
        if($this->role == 'student'){
            Db::table('student')->where('id',$this->user['id'])->update(['token' => $token,'update_time'=>time()]);
        }else{
            Db::table('teacher')->where('id',$this->user['id'])->update(['token' => $token,'update_time'=>time()]);
        }
        $this->re_json(array('token'=>$token));
    }

    //获取朋友圈内容
    public function  info(){
        $request = Request::instance();
        $friend_id =  $request->post('friend_id',true);
        $val = $request->post('val',true)?:20;
        $list = $request->post('list',true)?:0;
        $friend = Db::table('friend_message')->where('id',$friend_id)->find();
        if($friend['type'] == 1){
            $info = Db::table('student')->field('photo,name')->where('id',$friend['people_id'])->find();
        }else{
            $info = Db::table('teacher')->field('photo,name')->where('id',$friend['people_id'])->find();
        }
        $friend['people_photo'] = $info['photo'];
        $friend['people_name'] = $info['name'];
        $friend['praise'] = Db::table('praise')->where('message_id',$friend_id)->count();
        $friend['message'] = Db::table('message')->where('message_id',$friend_id)->count();
        $friend['forward'] = Db::table('forward')->where('message_id',$friend_id)->count();
        $message_list = Db::table('message')->where('message_id',$friend_id)->order('id desc')->limit(($list*$val),$val)->select();
        foreach($message_list as $v=>$k){
            if($k['type'] == 1){
                $info = Db::table('student')->field('photo,name')->where('id',$k['people_id'])->find();
            }else{
                $info = Db::table('teacher')->field('photo,name')->where('id',$k['people_id'])->find();
            }
            $message_list[$v]['people_photo'] = $info['photo'];
            $message_list[$v]['people_name'] = $info['name'];
        }

        //我是否点赞过
        $type = $this->role == 'student'?1:2;
        $praise = Db::table('praise')->where('people_id',$this->user['id'])->where('message_id',$friend_id)->where('type',$type)->find();
        $friend['is_praise'] = $praise ? 1:0;
        $forward = Db::table('forward')->where('people_id',$this->user['id'])->where('message_id',$friend_id)->where('type',$type)->find();
        $friend['is_forward'] = $forward ? 1:0;
        $friend['list'] = $message_list;
        $this->re_json($friend);
    }

    //发表朋友圈
    public function  add_friend(){
        $request = Request::instance();
        $body['body'] =  $request->post('body',true);
        $body['video'] =  $request->post('video',true);
        $body['people_id'] = $this->user['id'];
        $body['type'] = $this->role == 'student'?1:2;
        $body['add_time'] = time();
        Db::table('friend_message')->insert($body);
        $this->re_json('');
    }

    //点赞/取消
    public function  praise(){
        $request = Request::instance();
        $friend_id =  $request->post('friend_id',true);
        $type = $this->role == 'student'?1:2;
        $praise = Db::table('praise')->where('people_id',$this->user['id'])->where('message_id',$friend_id)->where('type',$type)->find();
        if($praise){
            Db::table('praise')->where('id',$praise['id'])->delete();
        }else{
            Db::table('praise')->insert(array('people_id'=>$this->user['id'],'message_id'=>$friend_id,'type'=>$type,'time'=>time()));
        }
        $this->re_json('');
    }
    //转发
    public function  forward(){
        $request = Request::instance();
        $friend_id =  $request->post('friend_id',true);
        $type = $this->role == 'student'?1:2;
        $praise = Db::table('forward')->where('people_id',$this->user['id'])->where('message_id',$friend_id)->where('type',$type)->find();
        if(!$praise){
            Db::table('forward')->insert(array('people_id'=>$this->user['id'],'message_id'=>$friend_id,'type'=>$type,'time'=>time()));
        }
        $this->re_json('');
    }
    //评论
    public function  message(){
        $request = Request::instance();
        $friend_id =  $request->post('friend_id',true);
        $body =  $request->post('body',true);
        $type = $this->role == 'student'?1:2;
        Db::table('message')->insert(array('people_id'=>$this->user['id'],'body'=>$body,'message_id'=>$friend_id,'type'=>$type,'time'=>time()));
        $this->re_json('');
    }
}

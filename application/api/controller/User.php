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
class User extends Auth
{
    //查询个人资料
    public function  info(){
        $info = Db::table('student')->where('id',$this->user['id'])->find();
		if(empty($this->user['name'])){
			$this->user['name'] = $this->user['email'];
		}
        unset($this->user['pass']);
        unset($this->user['token']);
        $this->re_json($this->user);
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
        Db::table('student')->where('id',$this->user['id'])->update($body);
        $this->re_json('');
    }

    //修改登陆密码
    public function  update_pass(){
        $request = Request::instance();
        $pass = $request->post('pass',true);
        $new_pass = $request->post('new_pass',true);
        if(MD5($pass) == $this->user['pass']){
            Db::table('student')->where('id',$this->user['id'])->update(['pass'=>MD5($new_pass)]);
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
        $info = Db::table('student')->field('id,pass')->where('email',$new_email)->find();
        if($info){
            $this->er_json('The mailbox has been used',11041);
        }
        if($code){
            Db::table('student')->where('id',$this->user['id'])->update(['email'=>$new_email]);
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
        $info = Db::table('student')->field('id,phone')->where('phone',$phone)->find();
        if($info){
            $this->er_json('The number has been bound',11045);
        }
        Db::table('student')->where('id',$this->user['id'])->update(['phone'=>$phone]);
        $this->re_json('');
    }

    //获取充值列表
    public function  money_list(){
        $data = Db::table('money_shop')->select();
        $this->re_json($data);
    }


}

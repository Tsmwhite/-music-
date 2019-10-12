<?php
/**
 * Created by PhpStorm.
 * User: chenl
 * Date: 2018/10/18
 * Time: 11:24
 */

namespace app\api\controller;
use app\ApiController;
use think\Request;
use think\Db;
use think\Cache;
class Index extends ApiController
{

 public function  savor(){
        $data = Db::table('savor')->select();
        echo json_encode(array('type'=>1,'data'=>$data,'body'=>''));exit;
    }
    public function registered(){
        $request = Request::instance();
        $email = $request->post('email',true);
        $pass = $request->post('pass',true);
        $code = $request->post('code',true);
        if($this->role == 'student'){
            $info = Db::table('student')->field('id,pass')->where('email',$email)->find();
        }else{
            $info = Db::table('teacher')->field('id,pass')->where('email',$email)->find();
        }
        if($info){
            echo json_encode(array('msg'=>'The mailbox has been used','err'=>11041,'errCode'=>0,'errType'=>'exception'));exit;
        }
        #$return = $this->checkRegSms($email, $code);
        #if($return){
        #    echo json_encode(array('msg'=>'Verification code error','err'=>11065,'errCode'=>0,'errType'=>'exception'));exit;
        #}
        if($this->role == 'student'){
            Db::name('student')->insert(array('email'=>$email,'pass'=>MD5($pass),'add_time'=>time()));
            $Id = Db::name('student')->getLastInsID();
        }else{
           # Db::name('teacher')->insert(array('email'=>$email,'pass'=>MD5($pass),'add_time'=>time()));
           # $Id = Db::name('teacher')->getLastInsID();
        }
        echo json_encode(array('type'=>1,'data'=>'success','body'=>''));exit;
    }

    public function login(){
        $request = Request::instance();
        $email = $request->post('email',true);
        $pass = $request->post('pass',true);
        if($this->role == 'student'){
            $info = Db::table('student')->field('id,pass')->where('email',$email)->whereOr('phone',$email)->find();
        }else{
            $info = Db::table('teacher')->field('id,pass,type')->where('email',$email)->whereOr('phone',$email)->find();
            if($info['type'] != 1){
                echo json_encode(array('msg'=>'Teacher review failed','err'=>11048,'errCode'=>0,'errType'=>'exception'));exit;
            }
        }
        if(!$info){
            echo json_encode(array('msg'=>'Did not find the specified user','err'=>11042,'errCode'=>0,'errType'=>'exception'));exit;
        }
        if($info['pass'] != MD5($pass)){
            echo json_encode(array('msg'=>'pass error','err'=>11043,'errCode'=>0,'errType'=>'exception'));exit;
        }
        $token = createToken(array($this->role.'_id'=>$info['id']));
        if($this->role == 'student'){
            Db::table('student')->where('id',$info['id'])->update(['token' => $token,'update_time'=>time()]);
            echo json_encode(array('data'=>'success','type'=>1,'body'=>array('token'=>$token)));exit;
        }else{
            Db::table('teacher')->where('id',$info['id'])->update(['token' => $token,'update_time'=>time()]);
            echo json_encode(array('data'=>'success','type'=>2,'body'=>array('token'=>$token)));exit;
        }

    }

    public function photo_add(){
        $file = request()->file('file');
        if($file){
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if($info){
                echo json_encode(array('data'=>'success','type'=>1,'body'=>array('photo'=>'http://'.$_SERVER['SERVER_NAME'].'/uploads/'.$info->getSaveName())));exit;
            }else{
                echo json_encode(array('msg'=>$file->getError(),'err'=>11044,'errCode'=>0,'errType'=>'exception'));exit;
            }
        }else{
            echo json_encode(array('msg'=>'Did not get the photo','err'=>11044,'errCode'=>0,'errType'=>'exception'));exit;
        }
    }



    public function pass_back(){
        $request = Request::instance();
        $email = $request->post('email',true);
        $code = $request->post('code',true);
        $new_pass = $request->post('new_pass',true);
        $table = $this->role == 'student'?'student':'teacher';
        $user = Db::table($table)->field('id')->where('email',$email)->find();
        if($user){
            #if($code != Cache::get('email_' . $email)){
                #    $this->er_json('code error',11092);
            #}
        }else{
            $user = Db::table($table)->field('id')->where('phone',$email)->find();
            if(!$user){
                echo json_encode(array('msg'=>'Did not find the specified user','err'=>11042,'errCode'=>0,'errType'=>'exception'));exit;
            }
            #if($code != Cache::get('phone_' . $email)){
                #    $this->er_json('code error',11092);
            #}
        }
        Db::table($table)->where('id',$user['id'])->update(array('pass'=>MD5($new_pass)));
        echo json_encode(array('data'=>'success','type'=>1,'body'=>''));exit;
    }

    public function teacher_registered(){
        $request = Request::instance();
		//拦截非post请求
		!$request->isPost() && setError('请求错误');
		//获取所有参数
		$param = $request->param();
		if(empty($param['name']))
			return setError('请填写用户名称');
			
		if(empty($param['phone']))
			return setError('请输入11位手机号码');
			
		if(!preg_match(MOBILE,$param['phone']))
			return setError('手机号格式不正确');
			
		if(empty($param['email']))
			return setError('请输入邮箱');
			
		if(!preg_match(EMAIL,$param['email']))
			return setError('邮箱格式不正确');
			
		dump($request);die;
		$DB = Db::table('teacher');
		//查询手机号是否存在
// 		$map = array(
// 			'phone' =
// 		);
// 		$res = 
		
		
        $data['name'] = $request->post('name',true);
        $data['sex'] = $request->post('sex',true);
        $data['birthday'] = $request->post('birthday',true);
        $data['address'] = $request->post('address',true);
        $data['phone'] = $request->post('phone',true);
        $data['email'] = $request->post('email',true);
        $data['ABN'] = $request->post('ABN',true);
        $data['culture'] = $request->post('culture',true);
        $data['card'] = $request->post('card',true);
        $data['gz_s_time'] = $request->post('gz_s_time',true);
        $data['gz_d_time'] = $request->post('gz_d_time',true);
        $data['j_photo'] = $request->post('j_photo',true);
        //$class_id = $request->post('class',true);
        $data['is_number'] = $request->post('is_number',true);
        $data['class'] = $request->post('class',true);
        $info = Db::table('teacher')->where('email',$data['email'])->whereOr('phone',$data['phone'])->whereOr('card',$data['card'])->find();
		if($info){
            if($info['type'] == 1){
                echo json_encode(array('msg'=>'Teacher review failed','err'=>11048,'errCode'=>0,'errType'=>'exception'));exit;
            }else if($info['type'] == 2){
                echo json_encode(array('msg'=>'Is registered','err'=>11049,'errCode'=>0,'errType'=>'exception'));exit;
            }else{
                echo json_encode(array('msg'=>'The mobile phone number or email or ID number has been registered','err'=>11050,'errCode'=>0,'errType'=>'exception'));exit;
            }
        }
        Db::table('teacher')->insert($data);
        /*$class_list_id = explode(",",$class_id);
        if($class_list_id[0]){
            foreach($class_list_id as $k){
                $class = explode("=",$k);
                Db::table('music_teacher_list')->insert(array('music_id'=>$class[0],'music_sun_id'=>$class[1],'teacher_id'=>Db::name('teacher')->getLastInsID()));
            }
        }*/
        echo json_encode(array('type'=>1,'data'=>'success','body'=>''));exit;
    }


    public function email_code(){
        #include './Senda.php';
        $request = Request::instance();
        $email = $request->post('email',true);
        $send = new Senda();
        $code = rand(100000,999999);
        $return = $send->send_email(
            'Your verification code is:'.$code,
            $email
        );
        if(!$return){
            //echo json_encode(array('msg'=>'Failed to send','err'=>11064,'errCode'=>0,'errType'=>'exception'));exit;
			setError('Failed to send');
        }
        Cache::set('email_' . $email, $code, 300);
        //echo json_encode(array('type'=>1,'data'=>'success','body'=>'验证码已发送请注意查收'));exit;
		setSuccess('验证码已发送请注意查收');
    }




    public function phone_code(){
        #include './Senda.php';
        $request = Request::instance();
        $phone = $request->post('phone',true);
        $send = new Senda();
        $code = rand(100000,999999);
        $return = $send->sendSMS(
            $code,
            $phone
        );
        if($return['code'] != 1){
            echo json_encode(array('msg'=>'Failed to send','err'=>11064,'errCode'=>0,'errType'=>'exception'));exit;
        }
        Cache::set('phone_' . $phone, $code, 300);
        echo json_encode(array('type'=>1,'data'=>'success','body'=>''));exit;
    }




    protected function checkRegSms($mobile, $code = false)
    {
        if (!$mobile) return false;
        if ($code === false) {   //判断60秒以内是否重复发送
            if (!Cache::has('email_' . $mobile)) return true;
            if (Cache::get('email_' . $mobile)['times'] > time()) {
                return false;
            } else {
                return true;
            }
        } else {  //判断验证码是否输入正确
            if (!Cache::has('email_' . $mobile)) return false;
            if (Cache::get('email_' . $mobile)['code'] == $code) {
                return true;
            } else {
                return false;
            }
        }
    }


    public function  about_us(){
        $data = Db::table('article')->find();
        echo json_encode(array('type'=>1,'data'=>$data,'body'=>''));exit;
    }


}

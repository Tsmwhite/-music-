<?php
/**
 * Created by PhpStorm.
 * User: chenl
 * Date: 2018/10/18
 * Time: 11:24
 */

namespace app\api\controller;
use think\Request;
use app\ApiController;
use think\Db;
class AuthTeacher extends ApiController
{
    public $user = array();
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
    }
    public function  _initialize(){
        if(!$this->token_val){
            $this->er_json('Token invalid',10400);
        }
        if($this->role != 'teacher'){
            $this->er_json('role error',10041);
        }
        $this->user = Db::table('teacher')->where('token',$this->token_val)->find();
        if(!$this->user){
            $this->er_json('Token expired',10010);
        }
    }

    public function re_json($data,$body='success',$type=1){
        echo json_encode(array('data'=>$data,'body'=>$body,'type'=>$type));exit;
    }

    public function er_json($msg,$err,$code=0,$type='exception'){
        echo json_encode(array('msg'=>$msg,'err'=>$err,'errCode'=>$code,'errType'=>$type));exit;
    }
}
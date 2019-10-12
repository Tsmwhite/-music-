<?php
/**
 * Created by PhpStorm.
 * User: chenl
 * Date: 2018/10/18
 * Time: 11:24
 */

namespace app\api\controller;

use app\ApiController;
class Auth_teacher extends ApiController
{
    public function  _initialize(){
        if(!$this->token){
            $this->er_json('Token invalid',10400);
        }
        if(!$this->role != 'teacher'){
            $this->er_json('role error',10041);
        }
    }

    public function re_json($array){
        echo json_encode($array);exit;
    }

    public function er_json($msg,$err,$code=0,$type='exception'){
        echo json_encode(array('msg'=>$msg,'err'=>$err,'errCode'=>$code,'errType'=>$type));exit;
    }
}
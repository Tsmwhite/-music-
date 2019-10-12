<?php
/**
 * 由代码生成工具自动生成
 * Date: 2019-04-22
 * Time: 23:49:06
 */


namespace app\admin\controller;
use think\Request;
use think\Db;

use app\Controller;

class Finance extends Controller
{
    public $model = 'Finance';

    public function type(){
        $id = $this->request->get('id');
        $type = $this->request->get('type');
        Db::table('finance')->where('id',$id)->update(array('status'=>$type));

        $this->redirect("/admin/finance/index");
    }

   
    public function info(){
        $id = $this->request->get('id');

        $finance_info = Db::table('finance')->where('id',$id)->find();
        $trade_info = Db::table('trade')->where('teacher_id',$finance_info['teacher_id'])->where('time','>',$finance_info['start_time'])->where('time','<',$finance_info['stop_time'])->SUM('price');
        return $this->assign([
            'finance_info' => $finance_info,
            'price' => $trade_info
        ])->fetch();
    }
    
}
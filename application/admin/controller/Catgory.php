<?php
namespace app\admin\controller;

use app\common\controller\Adminbase;
use think\Request;

class Catgory extends Adminbase
{
	public function catgory(Request $request){

        $params = $request->param();
        if($params){
            $page = $params['page'];
            $start = ($page-1)*$params['limit'];             
            $data = model('Catgory')->where('parent',0)->limit($start,$params['limit'])->select();
            $count = model('Catgory')->where('parent',0)->count();
            $list = json(['code'=>0,'msg'=>'','data'=>$data,'count'=>$count]);
            return $list; 
        }
         
       	return $this->fetch();
   	}

    public function catgoryadd(Request $request){
        $params = $request->param();
        if($params){
            $data = json_decode($params['data']);
            $res = model('Catgory')->save($data);
            if($res){
                return json(['code'=>200,'msg'=>'添加成功']);
            }else{
                return json(['code'=>201,'msg'=>'添加失败']);
            }
        }
    }
}
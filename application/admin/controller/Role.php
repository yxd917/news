<?php
namespace app\admin\controller;
use think\Controller;
use tink\Request;
use app\common\controller\Adminbase;

class Role extends Adminbase
{
	public function index(){
		return view();
	}

    public function loginlog(){
        return view();
    }

	public function getlogs(){
		if(request()->isGet()){
			$params = request()->param();
            $page = $params['page'];
            $start = ($page-1)*$params['limit'];
            $data = model('LoginLog')->order('id','desc')->limit($start,$params['limit'])->select();
            foreach ($data as $key => $value) {
                $data[$key]['nickname'] = model('admin')->where('userid',$value['userid'])->value('nickname');
            }
            $count = model('LoginLog')->count();
            return json(['code'=>0,'msg'=>'','count'=>$count,'data'=>$data]);
        }       
	}

    public function getroles(){
        if(request()->isGet()){
            $params = request()->param();
            $page = $params['page'];
            $start = ($page-1)*$params['limit'];
            $data = model('Role')->order('id','desc')->limit($start,$params['limit'])->select();           
            $count = model('Role')->count();
            return json(['code'=>0,'msg'=>'','count'=>$count,'data'=>$data]);
        }
    }
}
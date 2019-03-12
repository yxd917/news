<?php
namespace app\admin\controller;

use app\common\controller\Adminbase;


class Auths extends Adminbase
{
	public function users(){		
        return view();
	}

    public function userslist(){
        if(request()->isPost()){
            $params = request()->param();
            $map=[];
            $page = $params['page'];
            $start = ($page-1)*$params['limit'];
            if(isset($params['username'])){
                $map[] = ['username','like',"%".$params['username']."%"];
            }            
            $list = model('admin')->where($map)->limit($start,$params['limit'])->select();
            $count = model('admin')->where($map)->count();
            foreach ($list as $key => $value) {
                $list[$key]['group'] = model('role')->where('id',$value['groupid'])->value('name');
            }
            return json(['code'=>0,'msg'=>'','count'=>$count,'data'=>$list]);
        }
    }
}
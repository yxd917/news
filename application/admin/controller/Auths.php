<?php
namespace app\admin\controller;

use app\common\controller\Adminbase;


class Auths extends Adminbase
{
	public function users(){
		if(request()->isAjax()){
            $val=input('val');
            $url['val'] = $val;
            $this->assign('testval',$val);
            $map='';
            if($val){
                $map['username']= array('like',"%".$val."%");
            }
            if (session('aid')!=1){
                $map='userid='.session('aid');
            }
            $list = model('admin')->select();
            foreach ($list as $key => $value) {
                $list[$key]['group'] = model('role')->where('id',$value['groupid'])->value('name');
            }
            return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$list,'rel'=>1];
        }
        return view();
	}
}
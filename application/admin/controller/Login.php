<?php
namespace app\admin\controller;
use think\Controller;
use tink\Request;

class Login extends Controller
{
    public function initialize(){
        if (session('aid')) {
            $this->redirect('/admin/index');
        }
    }
    public function index(){
    	$request = request();
        if(request()->isPost()) {
        	$params = $request->param();
            $userid = model('admin')->login($params);            
            if($userid){                
                model('LoginLog')->save(['userid'=>$userid,'ip'=>$request->ip()]);
                return json(['code' => 1, 'msg' => '登录成功!', 'url' => url('/admin/index')]);
            }else {
                return json(array('code' => 0, 'msg' => '用户名或者密码错误，重新输入!'));
            }
        }else{
            return $this->fetch();
        }
    }    
}
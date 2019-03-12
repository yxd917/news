<?php
namespace app\admin\controller;

use app\common\controller\Adminbase;
use think\Session;
use think\Loader;

class System extends Adminbase
{
	public function _initialize(){		
		$this->aid = session('aid');
	}

    public function index() 
    {
        return view();
    } 

    public function myset(){
    	$user = model('Admin')->where('userid',$this->aid)->find();
    	$this->assign('userinfo',$user);   	
        return view();
    }
} 
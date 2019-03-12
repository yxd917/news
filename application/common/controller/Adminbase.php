<?php
namespace app\common\controller;

use think\Controller;

class Adminbase extends Controller 
{
	public function initialize()
    {	

        if (!session('aid')) {
            $this->redirect('/admin/login/index');
        }

        define('MODULE_NAME',strtolower(request()->controller()));
        define('ACTION_NAME',strtolower(request()->action()));

        $this->rule = cache('AuthRule');
    }

    //空操作
    public function _empty(){
        return $this->error('空操作，返回上次访问页面中...');
    }
    
} 
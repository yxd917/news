<?php
namespace app\admin\model;

use think\Model;

class Admin extends Model
{
    protected $autoWriteTimestamp = true;

    public function login($data){
        $user = $this->where('username',$data['username'])->find();
        if($user){            
            if($user['password'] == md5(md5($data['password']).$user['encrypt'])){
                session('username',$user['username']);
                session('nickname',$user['nickname']);
                session('aid',$user['userid']);                
                return $user['userid'];
            }else{
                return false;
            }
        }
    }
}
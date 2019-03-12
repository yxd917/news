<?php

namespace app\index\validate;
use think\Validate;

class Login extends Validate
{
    protected $rule =   [
        'username'  => 'require|max:25|alphaNum',
        'password'   => 'require|max:25',
    ];
    
    protected $message  =   [
        'username.require' => '用户名必填',
        'username.max'     => '用户名太长最多25个字符',
        'username.alphaNum'=> '用户名只能是字母或数字',
        'password.require' => '密码名必填',
        'password.max'     => '密码太长最多25个字符',
    ];
    
    protected $scene = [
        'login'  =>  ['username','password'],
    ];
    
}
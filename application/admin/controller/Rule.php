<?php
namespace app\admin\controller;

use app\common\controller\Adminbase;

class Rule extends Adminbase
{
    public function index() 
    {

        return view();  
    }

    public function getallrules(){
    	if(request()->isPost()){
    		$arr = cache('authRuleList');
    		if(!$arr){
    			$arr = model('AuthRule')->order('pid asc,sort asc')->select();
    			foreach($arr as $k=>$v){
                    $arr[$k]['lay_is_open']=false;
                }
    			cache('authRuleList', $arr, 3600);
    		}
    		return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$arr,'is'=>true,'tip'=>'操作成功'];
    	}
    }

    public function ruleAdd(){
        if(request()->isPost()){
            $data = input('post.');
            model('AuthRule')->create($data);
            cache('authRule', NULL);
            cache('authRuleList', NULL);
            cache('addAuthRuleList', NULL);
            return $result = ['code'=>1,'msg'=>'权限添加成功!','url'=>url('/admin/rule/index')];
        }else{
            $this->assign('menus',$this->getmenus());
            return view();
        }
    }

    public function ruleOrder(){
        $auth_rule=model('AuthRule');
        $data = input('post.');
        if($auth_rule->update($data)!==false){
            cache('authRuleList', NULL);
            cache('authRule', NULL);
            cache('addAuthRuleList', NULL);
            return $result = ['code'=>1,'msg'=>'排序更新成功!','url'=>url('/admin/rule/index')];
        }else{
            return $result = ['code'=>0,'msg'=>'排序更新失败!'];
        }
    }

    //设置权限菜单显示或者隐藏
    public function ruleState(){
        $id=input('post.id');
        $menustatus=input('post.menustatus');
        if(model('AuthRule')->where('id='.$id)->update(['menustatus'=>$menustatus])!==false){
            cache('authRule', NULL);
            cache('authRuleList', NULL);
            cache('addAuthRuleList', NULL);
            return ['status'=>1,'msg'=>'设置成功!'];
        }else{
            return ['status'=>0,'msg'=>'设置失败!'];
        }
    }

    //设置权限是否验证
    public function ruleTz(){
        $id=input('post.id');
        $authopen=input('post.authopen');
        if(model('AuthRule')->where('id='.$id)->update(['authopen'=>$authopen])!==false){
            cache('authRule', NULL);
            cache('authRuleList', NULL);
            cache('addAuthRuleList', NULL);
            return ['status'=>1,'msg'=>'设置成功!'];
        }else{
            return ['status'=>0,'msg'=>'设置失败!'];
        }
    }

    public function ruleDel(){
        model('AuthRule')->destroy(['id'=>input('param.id')]);
        cache('authRule', NULL);
        cache('authRuleList', NULL);
        cache('addAuthRuleList', NULL);
        return $result = ['code'=>1,'msg'=>'删除成功!'];
    }

    public function ruleEdit(){
        if(request()->isPost()) {
            $datas = input('post.');
            if(model('AuthRule')->update($datas)) {
                cache('authRule', NULL);
                cache('authRuleList', NULL);
                cache('addAuthRuleList', NULL);
                return json(['code' => 1, 'msg' => '保存成功!', 'url' => url('/admin/rule/index')]);
            } else {
                return json(['code' => 0, 'msg' =>'保存失败！']);
            }
        }else{
        	$this->assign('menus',$this->getmenus());
            $admin_rule = model('AuthRule')->get(function($query){
                $query->where(['id'=>input('id')])->field('id,href,title,icon,sort,menustatus,pid');
            });
            $this->assign('rule',$admin_rule);
            return view();
        }
    }

    public function getmenus(){
        $authRule = cache('authRule');
        if(!$authRule){
            $authRule = db('auth_rule')->where('menustatus=1')->order('sort')->select();
            cache('authRule', $authRule, 3600);
        }
        $menus = array();
        foreach ($authRule as $key=>$val){
            $authRule[$key]['href'] = url($val['href']);
            if($val['pid']==0){
                if(session('aid')!=1){
                    if(in_array($val['id'],$this->adminRules)){
                        $menus[] = $val;
                    }
                }else{
                    $menus[] = $val;
                }
            }
        }
        foreach ($menus as $k=>$v){
            foreach ($authRule as $kk=>$vv){
                if($v['id']==$vv['pid']){
                    if(session('aid')!=1) {
                        if (in_array($vv['id'], $this->adminRules)) {
                            $menus[$k]['children'][] = $vv;
                        }
                    }else{
                        $menus[$k]['children'][] = $vv;
                    }
                }
            }
        }
        return $menus;
    } 
} 
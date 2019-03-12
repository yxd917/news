<?php
namespace app\admin\controller;

use app\common\controller\Adminbase;
use think\Loader;
use think\Request;

class News extends Adminbase
{
    public function index()     {   
        $request = request();
        $params = $request->param();
        if($params){
            $page = $params['page'];
            $start = ($page-1)*$params['limit'];
            $data = model('news')->order('id','desc')->limit($start,$params['limit'])->select();
            foreach ($data as $key => $value) {
                $data[$key]['catename'] = model('Catgory')->where('id',$value['catid'])->value('name');
            }
            $count = model('news')->count();
            return json(['code'=>0,'msg'=>'','count'=>$count,'data'=>$data]);
        }
        $cats = model('Catgory')->field('id,name')->where('parent',0)->where('status',1)->select();
        $this->assign('cats',$cats);
        return $this->fetch();
    }
    public function newsearch(){
        $request = request();        
        $params = $request->param();
        if($params){
            $map = [];
            $page = $params['page'];
            $start = ($page-1)*$params['limit'];
            if($params['id']){
                $map[] = ['id','eq',$params['id']];
            }
            if($params['title']){
                $map[] = ['title','like',"%".$params['title']."%"];
            }
            if($params['catid']){
                $map[] = ['catid','eq',$params['catid']];
            }
            $data = model('news')->order('id','desc')->where($map)->limit($start,$params['limit'])->select();
            foreach ($data as $key => $value) {
                $data[$key]['catename'] = model('Catgory')->where('id',$value['catid'])->value('name');
            }
            $count = model('news')->where($map)->count();
            return json(['code'=>0,'msg'=>'','count'=>$count,'data'=>$data]);
        }
    }
   	public function newsadd(){
        $request = request();        
        $params = $request->param();
        if($params){
            $data = json_decode($params['data'],true);
            $arr['content'] = $data['content'];
            unset($data['image']);
            unset($data['content']);

            if(isset($data['status'])){
                if($data['status'] =="on"){
                    $data['status'] = 1;
                }
            }

            if(isset($data['islink'])){
                if($data['islink']=="on"){
                    $data['islink'] = 1;
                }
            }else{
                unset($data['link']);
            }
            
            $res = model('news')->create($data);            
            if($res){
                $arr['id'] = $res->id;
                model('NewsData')->create($arr);
                return json(['code'=>200,'msg'=>'添加成功']);
            }
        }        
        $cats = model('Catgory')->field('id,name')->where('parent',0)->where('status',1)->select();
        $this->assign('cats',$cats);
   		return $this->fetch('add');
   	}

    public function newsedit(){
        $request = request();
        $params = $request->param();
        if($params){
            $data = json_decode($params['data'],true);            
            unset($data['image']);
            unset($data['file']);            
            if($data['status'] == "on"){
                $data['status'] = 1;
            }else{
                $data['status'] = 0;
            }
            
            $news = array(
                'id' => $data['id'], 
                'title' => $data['title'],
                'catid' => $data['catid'],
                'link' => $data['link'],
                'status' => $data['status'],
                'listorder' => $data['listorder'],
                'thumb' => $data['thumb'],
                'update_time' => time(),
            );       
            $res = model('news')->isUpdate(true)->save($news);
            if($res){
                $newsdata = array(
                    'id' => $data['id'], 
                    'content' => $data['content']
                );
                $r = model('NewsData')->isUpdate(true)->save($newsdata);
                return json(['code'=>200,'msg'=>'更新成功']);
            }else{
                return json(['code'=>201,'msg'=>'更新失败']);
            }
        }
    }  	
    

    public function getcatgory(Request $request){

        $params = $request->param();
        if($request->isGet()){            
            $list = model('Catgory')->field('id,name')->where('parent',0)->where('status',1)->select();
            return json($list);
        }
    }

    public function upload(){
        $file = request()->file('image');
        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->validate(['size'=>9004800,'ext'=>'jpg,png,gif,jpeg'])->move('uploads');
        if($info){
            $src = '/uploads/'.$info->getSaveName();
            return json(['code'=>0,'msg'=>'上传成功','data'=>$src]);
        }else{
            // 上传失败获取错误信息
            $error = $file->getError();
            return json(['code'=>2,'msg'=>'上传失败','data'=>$error]);
        }
    }

    public function uploadImage(){
        $file = request()->file('file');
        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->validate(['size'=>9004800,'ext'=>'jpg,png,gif,jpeg'])->move('uploads');
        if($info){
            $src = '/uploads/'.$info->getSaveName();
            return json(['code'=>0,'msg'=>'上传成功','data'=>['src'=>$src,'title'=>$info->getFilename()]]);            
        }else{
            // 上传失败获取错误信息
            $error = $file->getError();
            return json(['code'=>2,'msg'=>'上传失败','data'=>$error]);
        }
    }

    public function edit(){
        $id=input('get.id');
        $data = model('news')::get($id);
        $cats = model('Catgory')->field('id,name')->where('parent',0)->where('status',1)->select();
        $this->assign('data',$data);
        $this->assign('cats',$cats);
        return view();
    }
} 
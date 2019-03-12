<?php
namespace app\index\model;

use think\Model;

class News extends Model
{
    protected $autoWriteTimestamp = true;

    public function profile()
    {
        return $this->hasOne('NewsData','id');
    }

    public function catgory()
    {
        return $this->hasMany('catgory','catid','id');
    }
}
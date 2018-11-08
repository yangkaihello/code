<?php

namespace app\index\model;

use think\Model;

class User extends Model
{

    protected $insert = [ 'update_time', 'create_time' ];
    protected $update = [ 'update_time' ];

    protected function setUpdateTimeAttr(){
        return time();
    }

    protected function setCreateTimeAttr(){
        return time();
    }

    protected function setPasswordAttr($value){
        return md5($value);
    }

    public function passwordVerify($value)
    {
        return $this->setPasswordAttr($value) == $this->password;
    }

    public function search($request)
    {
        
        if( !empty($request->get('keyword','')) )
        {
            $this->where('username','like','%' . trim($request->get('keyword')) . '%');
        }

        if( !empty($request->get('startDate','')) )
        {
            $this->where('create_time','>',strtotime($request->get('startDate')));
        }

        if( !empty($request->get('endDate','')) )
        {
            $this->where('create_time','<',strtotime($request->get('endDate'))+24*3600-1 );
        }

    }

    public function createOrUpdate($request)
    {
        $postData   = $request->post();

        if($request->has('id'))
        {
            $model  = self::find($request->post('id'));
        }else{
            $model  = $this;
        }

        unset($postData['id']);

        $postData['tel'] = $postData['account'];    //临时储存手机号
        
        foreach($postData as $key=>$value)
        {
            $model->setAttr($key,$value);   
        }

        return $model;
    }
}
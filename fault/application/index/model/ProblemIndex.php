<?php

namespace app\index\model;

use think\Model;

class ProblemIndex extends Model
{

    const DEFAULT_GROUP = 1;

    protected $insert = [ 'update_time', 'create_time' ];
    protected $update = [ 'update_time' ];

    protected function setUpdateTimeAttr(){
        return time();
    }

    protected function setCreateTimeAttr(){
        return time();
    }

    public function search($request)
    {

        if( !empty($request->get('keyword','')) )
        {
            $this->where('title','like','%' . trim($request->get('keyword')) . '%');
        }

        if( !empty($request->get('startDate','')) )
        {
            $this->where('create_time','>',strtotime($request->get('startDate')));
        }

        if( !empty($request->get('endDate','')) )
        {
            $this->where('create_time','<',strtotime($request->get('endDate'))+24*3600-1 );
        }

        if( $request->get('source','all') !== 'all' )
        {
            $this->where('source','=',$request->get('source'));
        }

        if( $request->get('group',static::DEFAULT_GROUP) !== 'all' )
        {
            $this->where('group','=',$request->get('group',static::DEFAULT_GROUP));
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

        unset($postData['images']);
        unset($postData['id']);

        foreach($postData as $key=>$value)
        {
            $model->setAttr($key,$value);   
        }

        return $model;
    }
}
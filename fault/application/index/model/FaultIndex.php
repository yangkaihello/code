<?php

namespace app\index\model;

use think\Model;

class FaultIndex extends Model
{

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
            $this->where('date','>=',$request->get('startDate'));
        }

        if( !empty($request->get('endDate','')) )
        {
            $this->where('date','<=',$request->get('endDate'));
        }

        if( $request->get('source','all') !== 'all' )
        {
            $this->where('source','=',$request->get('source'));
        }

        if( $request->get('range','all') !== 'all' )
        {
            $this->where('range','=',$request->get('range'));
        }

        if( $request->get('isRenovate','all') !== 'all' )
        {
            $this->where('isRenovate','=',$request->get('isRenovate'));
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
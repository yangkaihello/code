<?php
/**
 * Created by PhpStorm.
 * User: yangkai
 * Date: 2018/10/28
 * Time: 下午2:07
 */

namespace app\index\model;

use think\Model;

class DomainIndex extends Model
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

        if( !empty($request->get('keywords','')) )
        {
            $this->where('entire','like','%' . trim($request->get('keywords')) . '%');
        }

        if( $request->get('product','all') !== 'all' )
        {
            $this->where('product','=',$request->get('product'));
        }

        if( $request->get('type','all') !== 'all' )
        {
            $this->where('type','=',$request->get('type'));
        }

        if( $request->get('status','all') !== 'all' )
        {
            $this->where('status','=',$request->get('status'));
        }
        
    }

    public function createOrUpdate($data)
    {
        unset($data['id']);
        foreach($data as $key=>$value)
        {
            $this->setAttr($key,$value);
        }

        $this->domainSplit();
        return $this;
    }

    protected function domainSplit()
    {
        
        $split = explode(".",$this->entire);

        if( count($split) === 3 )
        {
            $this->front    = $split[0];
            $this->centre   = $split[1];
            $this->later    = $split[2];
        }elseif(count($split) === 2){
            $this->centre   = $split[0];
            $this->later    = $split[1];
        }
        
    }

}
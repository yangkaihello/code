<?php

namespace app\index\model;

use think\Model;

class ImageLink extends Model
{
    const SOURCE_TYPE_FAULT_CAPTURE = 'fault.capture';
    const SOURCE_TYPE_PROBLEM_CAPTURE = 'problem.capture';

    public function created($imageIds = [])
    {
        ImageLink::where(['source_type' => $this->source_type,'source_id' => $this->source_id])->delete();

        if( empty($imageIds) ) return false;
        
        foreach($imageIds as $imageId)
        {
            $data[] = [
                'image_id'      => $imageId,
                'source_id'     => $this->source_id,
                'source_type'   => $this->source_type,
            ];
        }

        $this->insertAll($data);
    }

    public function image(){
        return $this->hasOne("Image",'id','image_id');
    }
}
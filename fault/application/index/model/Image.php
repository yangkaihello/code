<?php

namespace app\index\model;

use think\Model;

class Image extends Model
{
    private $_pathinfo;

    public function pathinfo()
    {
        $this->_pathinfo = pathinfo($this->path);
    }

    public function getKey()
    {
       $this->key = $this->_pathinfo['filename'];
    }

    public function created($path)
    {
        $this->path = $path;
        $this->pathinfo();
        $this->getKey();

        if( $image = self::get(['key'=>$this->key]) )
        {
            return $image->id;
        }else{
            $this->save();
            return $this->id;
        }

    }

}
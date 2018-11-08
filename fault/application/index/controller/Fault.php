<?php
namespace app\index\controller;

use think\Db;
use think\Loader;
use app\index\model\FaultIndex;
use app\index\model\Image;
use app\index\model\ImageLink;

class Fault extends Common
{
    public function list()
    {   
        $request    = request();
        $model      = (new FaultIndex);

        $model->search($request);

        return $this->fetch('list',[
            'title' => '故障库列表',
            'range' => config('fault.range'),
            'source' => config('fault.source'),
            'isRenovate' => config('fault.isRenovate'),
            'model' => $model->order('date desc')->paginate(30,false, ['query' => $request->param()]),
        ]);
    }

    public function show()
    {
        $request = request();
        if($request->has('id'))
        {
            $images = ImageLink::where([
                'source_id'     => $request->param('id'),
                'source_type'   => ImageLink::SOURCE_TYPE_FAULT_CAPTURE,
            ])->with(['image'])->select();

            foreach($images as $image)
            {
                $path[] = $image->image->path;
            }

            $this->assign( 'path' , $path ?? [] );
            $this->assign( 'images' , $images );
            $this->assign( 'data' ,FaultIndex::get($request->param('id')) );
        }
        return $this->fetch('show',[
            'title' => '故障登记',
            'range' => config('fault.range'),
            'source' => config('fault.source'),
            'isRenovate' => config('fault.isRenovate'),
        ]);
    }

    public function info()
    {
        $request = request();
        $data = FaultIndex::get($request->post('id'));
        
        return json([
            'cause'     => htmlImgAddHref($data->cause),
            'solution'  => htmlImgAddHref($data->solution),
        ]);
    }

    public function handle()
    {
        $request    = request();      
        $model      = new FaultIndex();  
        $image      = new Image();
        $link       = new ImageLink();
        $validate   = Loader::validate('Fault');

        

        $scene  = $request->has('id') ? 'edit' : 'add';
        if( !$validate->batch()->scene($scene)->check($request->post()) )
        {
            $data = $validate->getError();
            $code = 404;
        }else{
            $model = $model->createOrUpdate($request);
            $model->save();   
            
            $imageUrl = array_filter( explode(",",$request->post('images',"")) );

            foreach($imageUrl as $path)
            {
                $id = $image->created($path);
                $imageIds[] = $id;
            }
            
            $link->source_type  = $link::SOURCE_TYPE_FAULT_CAPTURE;
            $link->source_id    = $model->id;
            $link->created($imageIds);

        }

        return json($data,$code ?? 200);
    }

    public function delete()
    {
        return FaultIndex::destroy(request()->post('ids'));
    }

}

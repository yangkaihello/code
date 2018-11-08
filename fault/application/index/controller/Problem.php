<?php
namespace app\index\controller;

use think\Db;
use think\Loader;
use app\index\model\ProblemIndex;
use app\index\model\Image;
use app\index\model\ImageLink;

class Problem extends Common
{
    public function list()
    {
        $request    = request();
        $model      = (new ProblemIndex);

        $model->search($request);

    	return $this->fetch("list",[
    		'title' => '常见问题',
            'source' => config('problem.source'),
            'group' => config('problem.group'),
            'defaultGroup' => (string)$model::DEFAULT_GROUP,
            'model' => $model->order('create_time desc')->paginate(30,false, ['query' => $request->param()]),
    	]);
    }

    public function show()
    {

        $request = request();
        if($request->has('id'))
        {
            $images = ImageLink::where([
                'source_id'     => $request->param('id'),
                'source_type'   => ImageLink::SOURCE_TYPE_PROBLEM_CAPTURE,
            ])->with(['image'])->select();

            foreach($images as $image)
            {
                $path[] = $image->image->path;
            }

            $this->assign( 'path' , $path ?? [] );
            $this->assign( 'images' , $images );
            $this->assign( 'data' ,ProblemIndex::get($request->param('id')) );
        }

    	return $this->fetch("show",[
    		'title' => '新增问题',
            'source' => config('problem.source'),
            'group' => config('problem.group'),
    	]);
    }

    public function info()
    {
        $request = request();
        $data = ProblemIndex::get($request->post('id'));
        
        return json([
            'description'   => htmlImgAddHref($data->description),
            'answer'        => htmlImgAddHref($data->answer),
        ]);
    }

    public function handle()
    {

        $request    = request();      
        $model      = new ProblemIndex();  
        $image      = new Image();
        $link       = new ImageLink();
        $validate   = Loader::validate('Problem');
        
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
            
            $link->source_type  = $link::SOURCE_TYPE_PROBLEM_CAPTURE;
            $link->source_id    = $model->id;
            $link->created($imageIds);

        }

        return json($data,$code ?? 200);

    }

    public function delete()
    {
        return ProblemIndex::destroy(request()->post('ids'));
    }

}

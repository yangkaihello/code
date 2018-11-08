<?php
namespace app\index\controller;

use think\Db;
use think\Loader;
use app\index\model\User as UserModel;
use app\index\model\Image;
use app\index\model\ImageLink;

class User extends Common
{
    public function list()
    {   
        $request    = request();
        $model      = (new UserModel);

        $model->search($request);
        
        $model->where('role_id','<>','1');

        return $this->fetch('list',[
            'title' => '用户列表',
            'model' => $model->order('create_time desc')->paginate(30,false, ['query' => $request->param()]),
        ]);
    }

    public function show()
    {
        $request = request();

        if($request->has('id'))
        {
            $this->assign( 'data' ,UserModel::get($request->param('id')) );
        }

        return $this->fetch('show',[
            'title' => '创建用户',
        ]);
    }

    public function handle()
    {
        $request    = request();      
        $model      = new UserModel();  
        $validate   = Loader::validate('User');

        $scene  = $request->has('id') ? 'edit' : 'add';
        if( !$validate->batch()->scene($scene)->check($request->post()) )
        {
            $data = $validate->getError();
            $code = 404;
        }else{

            $model = $model->createOrUpdate($request);
            if($scene == 'edit' && empty($request->post('password')))
            {
                unset($model->password);    
            }
            
            $model->save();   

        }

        return json($data,$code ?? 200);
    }

    public function delete()
    {
        return UserModel::destroy(request()->post('ids'));
    }

}

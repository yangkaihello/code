<?php
namespace app\index\controller;

use think\Controller;
use think\Validate;

class Common extends Controller
{

    public function _initialize()
    {
        error_reporting(E_ALL^E_NOTICE);

        if( !session('data.account') )
            $this->redirect( url('General/login') );

        $config = config('permissions');
        //$auth   = $config[session('data.role_id')];  //得到配置文件本角色对应的权限

        $nickname = request()->controller().'/'.request()->action();

        //if(!in_array($nickname,$auth))
        //    $this->error('当前页面没有管理权限',url('Resource/getList'));

        $this->assign('nickname',$nickname);
        $this->assign('rolename',(string)session('data.role_id'));
    }
}

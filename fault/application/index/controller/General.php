<?php
namespace app\index\controller;
use app\index\model\User;
use think\Controller;
use think\Loader;
use think\Request;
use think\Validate;

class General extends Controller
{
    /*
     * 管理员登录
     */
    public function login()
    {
        if( session('data.account') ){

            $this->redirect( url('fault/list') );
        }

        if( !request()->post() ){

            $info = array('account'=>'', 'account_pass'=>'', 'remember'=>'');

            if( cookie('account') ){

                $info['account_pass']   = sha1_dencrypt( cookie('account_pass') ,false);
                $info['account']        = cookie('account');
                $info['remember']       = cookie('remember');
            }

            $this->assign('info', $info);
            $this->assign('title','管理员登录');
            return $this->fetch();
        }

        $field      = ['account','account_pass','remember'];
        $post_data  = request()->only($field,'post');

        $validate   = Loader::validate('General');

        if(!$validate->batch()->check($post_data)){

            $msg = array( 'code'=>'failed', 'message' => array_values($validate->getError()) );
            return json($msg);
        }

        /* 检测数据库是否存在用户 */
        $user = User::get([
            'account'   => $post_data['account'],
        ]);

        if($user)
        {
            if($user->passwordVerify($post_data['account_pass'])){
                $this->setSessionLogin([
                    'account'   => $user->account,
                    'username'  => $user->username,
                    'role_id'   => config("user.role")[$user->role_id],
                ]);
                return json( array( 'code'=>'ok', 'message' =>'登录成功', 'url'=>url('fault/list') ) );
            }
        }

        //配置文件超级管理员检测
        $config = config('account_info');
        
        if(isset($config) && $post_data['account'] == $config['username'] && $post_data['account_pass'] == $config['userpass'] )
        {
            $this->setSessionLogin([
                'account'   => $post_data['account'],
                'username'  => $post_data['account'],
                'role_id'   => config("user.role")[$config['role_id']],
            ]);

            return json( array( 'code'=>'ok', 'message' =>'登录成功', 'url'=>url('fault/list') ) );
        }else{
            return json( array( 'code'=>'failed', 'message' =>['账号或密码错误'] ) );
        }

        /*$url        = config('interface_domain')."index/api_manager/checklogin";
        $result     = request_post($url,$post_data);
        $response   = json_decode($result,true);

        if($response['status'] != 1)
            return json( array( 'code'=>'failed', 'message' =>$response['message'] ) );

        if($response['data']['rolename'] == 'admin' && $post_data['account'] != 'mtkadmin'){
            return json( array( 'code'=>'failed', 'message' =>['该用户没有权限登录'] ) );
        }

        $data               = $response['data'];
        $data['account']    = $post_data['account'];

        session('data', $data);

        if( isset($post_data['remember']) && $post_data['remember'] == 1 ){
            cookie('remember', 1 );
            cookie('account', $post_data['account'] );
            cookie('account_pass', sha1_dencrypt( $post_data['account_pass'] ) );
        }else{
            cookie('account',null);
            cookie('account_pass',null);
            cookie('remember',null);
        }

        return json( array( 'code'=>'ok', 'message' =>'登录成功', 'url'=>url('fault/list') ) );*/
    }

    public function setSessionLogin($arr)
    {
        session('data',$arr);
    }

    /*
     * 退出登录
     */
    public function signOut()
    {
        if( session('data.account') )
        {
            session('data',null);
            $this->redirect( url('General/login') );
        }
    }


    /*
     * 上传临时图片
     */
    public function upload_picture()
    {

        $file   = request()->file('file');
        $upload = file_upload($file,'temp');

        if($upload['code'] != 100)
            return array( 'code' => 'failed', 'message' => $upload['message']);

        $msg['code'] = 'ok';
        $msg['path'] = $upload['file_url'];

        return json($msg);
    }

    /*
     * 操作提示页面
     */
    public function promptPage()
    {
        $this->assign('param', request()->param() );
        return $this->fetch('prompt');
    }

    /*
     * 上传临时文件
     */
    public function upload_file()
    {

        $file   = request()->file('file');
        $upload = file_upload($file,'temp','xlsx,csv,xls');

        if($upload['code'] != 100)
            return array( 'code' => 'failed', 'message' => $upload['message']);

        $msg['code']        = 'ok';
        $msg['path']        = $upload['file_url'];
        $msg['file_name']   = $upload['file_name'];

        return json($msg);
    }

    /*
     * 表单数据处理返回前端
     */
    public function data_storage()
    {
        $post_data = request()->post();

        return json( array('code'=>'ok','content'=> $post_data) );
    }

    /*
     * 获取城市区域下级区域
     */
    public function getArea()
    {
        error_reporting(E_ALL^E_NOTICE);

        $post_data = request()->post();

        $result = array('list'=>array());

        if($post_data['keywords']){
            $param['keywords'] = $post_data['keywords'];
            $result = getArea($post_data['keywords']);
        }

        $area_city = $area_district = array();

        if($post_data['province'])
            $area_city = getArea($post_data['province']);
        if($post_data['city'])
            $area_district = getArea($post_data['city']);

        return json( array('code'=>'ok', 'list'=>$result['list'], 'area_city'=>$area_city['list'], 'area_district'=>$area_district['list']) );
    }

}
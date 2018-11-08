<?php
namespace app\index\validate;

use think\Validate;

class User extends Validate
{
    //'require|file|fileExt:jpg,png,gif,jpeg|fileSize:3145728'
    protected $rule = [
        'id'                => 'require|gt:0',
        'account'           => 'require|mobile|unique:user',
        'username'          => 'require|unique:user',
        'password'          => 'require', 
        'tel'               => 'require',
    ];

    protected $message = [
        'id.require'                => '操作错误',
        'id.gt'                     => '操作错误',
        'account'                   => '账号不能为空',
        'account.mobile'            => '账号必须是正确格式的手机号',
        'account.unique'            => '账号已被注册',
        'username'                  => '用户名不能为空',
        'username.unique'           => '用户名已存在',
        'password'                  => '密码不能为空',
        'tel'                       => '手机号不能为空',
    ];

    protected $scene = [

        'add'   => [ 'account' , 'username' , 'password' ],

        'edit'  => [ 'id' , 'account', 'username' ],

    ];

    /**
     * 验证是否等于某个值
     * @access protected
     * @param mixed     $value  字段值
     * @param mixed     $rule  验证规则
     * @return bool
     */
    protected function mobile($value, $rule)
    {
        if(preg_match("/^1[345678]{1}\d{9}$/",$value)){
            return true;
        }else{
            return false;
        }
    }
}
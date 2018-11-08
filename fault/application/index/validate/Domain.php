<?php
/**
 * Created by PhpStorm.
 * User: yangkai
 * Date: 2018/10/28
 * Time: 下午2:28
 */

namespace app\index\validate;

use think\Validate;
use think\Loader;

class Domain extends Validate
{
    protected $rule = [
        'id'                => 'require|gt:0',
        'product'           => 'require',
        'type'              => 'require', //'require|file|fileExt:jpg,png,gif,jpeg|fileSize:3145728'
        'status'            => 'require',
        'entire'            => 'require|domainVerify|unique:DomainIndex',
    ];

    protected $message = [
        'id.require'                => '操作错误',
        'id.gt'                     => '操作错误',
        'product.require'           => '所属产品不能为空',
        'type.require'              => '域名类型不能为空',
        'status.require'            => '域名状态不能为空',
        'entire.require'            => '域名不能为空',
        'entire.domainVerify'       => '域名格式错误',
        'entire.unique'             => '域名不能重复',
    ];

    protected $scene = [

        'add'   => [ 'product', 'type', 'status', 'entire' ],

        'edit'  => [ 'id' , 'product', 'type', 'status', 'entire' ],

    ];

    // 自定义验证规则
    protected function domainVerify($value,$rule)
    {
        $domain = array_filter(explode(".",$value));
        if( count($domain) === 3 || count($domain) === 2 )
        {
            return true;
        }else{
            return false;
        }
    }

}
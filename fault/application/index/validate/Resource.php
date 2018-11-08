<?php
namespace app\index\validate;

use think\Validate;

class Resource extends Validate
{
    protected $rule = [
        'id'                => 'require|gt:0',
        'platform_type'     => 'require|number',
        'avatar'            => 'require', //'require|file|fileExt:jpg,png,gif,jpeg|fileSize:3145728'
        'account_type'      => 'require|number',
        'auth_status'       => 'require|number',
        'subject_type'      => 'require|number',
        'customer_type'     => 'require|number',
        'content_type'      => 'require|number',
        'account_name'      => 'require',
        'account_id'        => 'require',
        'tag'               => 'require',
        'province'          => 'require',
        'city'              => 'require',
        'read_number'       => 'require|number'

    ];

    protected $message = [
        'id.require'                => '媒体ID不能为空',
        'id.gt'                     => '媒体ID不能为空',
        'platform_type.require'     => '请选择平台类型',
        'platform_type.number'      => '请选择平台类型',
        'avatar.require'            => '请上传头像',
        'avatar.file'               => '头像不是一个文件',
        'avatar.fileExt'            => '头像图片格式非法',
        'avatar.fileSize'           => '头像图片大小不能超过3M',
        'account_type.require'      => '请选择账号类型',
        'account_type.number'       => '请选择账号类型',
        'auth_status.require'       => '请选择认证状态',
        'auth_status.number'        => '请选择认证状态',
        'subject_type.require'      => '请选择主体类型',
        'subject_type.number'       => '请选择主体类型',
        'customer_type.require'     => '请选择用户类型',
        'customer_type.number'      => '请选择用户类型',
        'content_type.require'      => '请选择内容内型',
        'content_type.number'       => '请选择内容内型',
        'account_name.require'      => '公众号名称不能为空',
        'account_id.require'        => '公众号ID不能为空',
        'tag.require'               => '账号特色不能为空',
        'province.require'          => '请选择所在地区省份',
        'city.require'              => '请选择所在地区市区',
        'read_number.require'       => '昨日头条阅读数不能为空',
        'read_number.number'        => '昨日头条阅读数数值类型非法'

    ];

    protected $scene = [

        'add'   => [ 'account_name', 'account_id', 'account_type', 'customer_type', 'tag', 'subject_type' ],

        'edit'  => [ 'id', 'account_name', 'account_id', 'account_type', 'customer_type', 'tag', 'subject_type' ],

    ];
}
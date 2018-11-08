<?php
namespace app\index\validate;

use think\Validate;

class Problem extends Validate
{
    protected $rule = [
        'id'                => 'require|gt:0',
        'source'            => 'require|number',
        'group'             => 'require|number',
        'title'             => 'require|unique:problem_index',
        'description'       => 'require',
        'answer'            => 'require',
        'remark'            => 'require',
    ];

    protected $message = [
        'id.require'                => '操作错误',
        'id.gt'                     => '操作错误',
        'source'                    => '类型对象不能为空',
        'group'                     => '问题类型不能为空',
        'title'                     => '问题标题不能为空',
        'title.unique'              => '已存在该问题',
        'description'               => '问题描述不能为空',
        'answer'                    => '问题回答不能为空',
        'remark'                    => '处理备注不能为空',
    ];

    protected $scene = [

        'add'   => [ 'source' , 'group' , 'title' , 'description' , 'answer' , 'remark' ],

        'edit'  => [ 'id' , 'source' , 'group' , 'title' , 'description' , 'answer' , 'remark' ],

    ];
}
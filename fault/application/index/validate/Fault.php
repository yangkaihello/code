<?php
namespace app\index\validate;

use think\Validate;

class Fault extends Validate
{
    protected $rule = [
        'id'                => 'require|gt:0',
        'date'              => 'require',
        'minute'            => 'require', //'require|file|fileExt:jpg,png,gif,jpeg|fileSize:3145728'
        'range'             => 'require|number',
        'range_reason'      => 'max:30',
        'source'            => 'require|number',
        'source_reason'     => 'max:30',
        'title'             => 'require|unique:fault_index',
        'cause'             => 'require',
        'solution'          => 'require',
        'isRenovate'        => 'require|number',
    ];

    protected $message = [
        'id.require'                => '操作错误',
        'id.gt'                     => '操作错误',
        'date.require'              => '故障日期不能为空',
        'minute.require'            => '故障时长不能为空',
        'range'                     => '影响范围不能为空',
        'source'                    => '故障来源不能为空',
        'range_reason'              => '影响范围描述不能大于30字符',
        'source_reason'             => '故障来源描述不能大于30字符',
        'title'                     => '故障标题不能为空',
        'title.unique'              => '已存在该故障',
        'cause'                     => '故障原因不能为空',
        'solution'                  => '解决办法不能为空',
        'isRenovate'                => '是否解决不能为空',
    ];

    protected $scene = [

        'add'   => [ 'date', 'minute', 'range', 'range_reason', 'source', 'source_reason' , 'title' , 'cause' , 'solution' , 'isRenovate' ],

        'edit'  => [ 'id' , 'date', 'minute', 'range', 'range_reason', 'source', 'source_reason' , 'title' , 'cause' , 'solution' , 'isRenovate' ],

    ];
}
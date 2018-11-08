<?php
namespace app\index\validate;

use think\Validate;

class General extends Validate
{
    protected $rule = [
        'account'       => 'require',
        'account_pass'  => 'require',
    ];

    protected $message = [
        'account.require'           => '用户名不能为空',
        'account_pass.require'      => '登录密码不能为空',
    ];
}
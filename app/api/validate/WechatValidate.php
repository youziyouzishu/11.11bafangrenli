<?php
// +----------------------------------------------------------------------
// | 快速开发前后端分离管理后台（PHP版）
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 开源版本可自由商用，可去除界面版权logo
// | gitee下载：
// | github下载：

// | imadmin团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------
// | author: imadmin
// +----------------------------------------------------------------------

namespace app\api\validate;

use app\common\validate\BaseValidate;

/**
 * 微信验证器
 * Class WechatValidate
 * @package app\api\validate
 */
class WechatValidate extends BaseValidate
{
    public $rule = [
        'url' => 'require'
    ];

    public $message = [
        'url.require' => '请提供url'
    ];

    public function sceneJsConfig()
    {
        return $this->only(['url']);
    }
}

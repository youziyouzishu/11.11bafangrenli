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

namespace app\common\model;


use app\common\model\BaseModel;



/**
 * PersonalVerification模型
 * Class PersonalVerification
 * @package app\common\model
 */
class PersonalVerification extends BaseModel
{

    protected $name = 'personal_verification';

    protected $append = [
        'realname_status_text'
    ];
    function getRealnameStatusTextAttr($value)
    {
        $value = $value ? $value : $this->realname_status;
        $status = [
            0 => '待实名',
            1 => '已实名',
            2 => '待授权',
            3 => '重新授权',
            4 => '授权有效',
        ];
        return $status[$value];
    }
}

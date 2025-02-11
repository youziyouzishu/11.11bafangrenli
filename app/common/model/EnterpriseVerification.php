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


use app\common\model\auth\Admin;
use app\common\model\BaseModel;



/**
 * EnterpriseVerification模型
 * Class EnterpriseVerification
 * @package app\common\model
 */
class EnterpriseVerification extends BaseModel
{

    protected $name = 'enterprise_verification';

    function admin()
    {
        return $this->belongsTo(Admin::class, 'user_id', 'id');
    }
}

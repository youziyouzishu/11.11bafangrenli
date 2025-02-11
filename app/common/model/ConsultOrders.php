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
use think\model\concern\SoftDelete;


/**
 * Consult模型
 * Class Consult
 * @package app\common\model
 */
class ConsultOrders extends BaseModel
{
    use SoftDelete;
    protected $name = 'consult_orders';
    protected $deleteTime = 'delete_time';

    function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id', 'id');
    }

    
}
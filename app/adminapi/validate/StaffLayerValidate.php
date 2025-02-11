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

namespace app\adminapi\validate;


use app\common\validate\BaseValidate;


/**
 * StaffLayer验证器
 * Class StaffLayerValidate
 * @package app\adminapi\validate
 */
class StaffLayerValidate extends BaseValidate
{

     /**
      * 设置校验规则
      * @var string[]
      */
    protected $rule = [
        'id' => 'require',
        'staff_id' => 'require',
        'admin_id' => 'require',
    ];


    /**
     * 参数描述
     * @var string[]
     */
    protected $field = [
        'id' => 'id',
        'staff_id' => '员工ID',
        'admin_id' => '管理员ID',
    ];


    /**
     * @notes 添加场景
     * @return StaffLayerValidate
     * @author ideaadmin
     * @date 2024/12/17 09:27
     */
    public function sceneAdd()
    {
        return $this->only(['staff_id','admin_id']);
    }


    /**
     * @notes 编辑场景
     * @return StaffLayerValidate
     * @author ideaadmin
     * @date 2024/12/17 09:27
     */
    public function sceneEdit()
    {
        return $this->only(['id','staff_id','admin_id']);
    }


    /**
     * @notes 删除场景
     * @return StaffLayerValidate
     * @author ideaadmin
     * @date 2024/12/17 09:27
     */
    public function sceneDelete()
    {
        return $this->only(['id']);
    }


    /**
     * @notes 详情场景
     * @return StaffLayerValidate
     * @author ideaadmin
     * @date 2024/12/17 09:27
     */
    public function sceneDetail()
    {
        return $this->only(['id']);
    }

}
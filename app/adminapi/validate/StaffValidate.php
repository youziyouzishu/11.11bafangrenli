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
 * Staff验证器
 * Class StaffValidate
 * @package app\adminapi\validate
 */
class StaffValidate extends BaseValidate
{

     /**
      * 设置校验规则
      * @var string[]
      */
    protected $rule = [
        'id' => 'require',
        'name' => 'require',
    ];


    /**
     * 参数描述
     * @var string[]
     */
    protected $field = [
        'id' => 'id',
        'admin_id' => '所属业务公司',
        'name' => '业务员名称',
    ];


    /**
     * @notes 添加场景
     * @return StaffValidate
     * @author ideaadmin
     * @date 2024/12/16 18:09
     */
    public function sceneAdd()
    {
        return $this->only(['name']);
    }


    /**
     * @notes 编辑场景
     * @return StaffValidate
     * @author ideaadmin
     * @date 2024/12/16 18:09
     */
    public function sceneEdit()
    {
        return $this->only(['id','name']);
    }


    /**
     * @notes 删除场景
     * @return StaffValidate
     * @author ideaadmin
     * @date 2024/12/16 18:09
     */
    public function sceneDelete()
    {
        return $this->only(['id']);
    }


    /**
     * @notes 详情场景
     * @return StaffValidate
     * @author ideaadmin
     * @date 2024/12/16 18:09
     */
    public function sceneDetail()
    {
        return $this->only(['id']);
    }

}
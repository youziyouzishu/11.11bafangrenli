<?php
// +----------------------------------------------------------------------
// | likeadmin快速开发前后端分离管理后台（PHP版）
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 开源版本可自由商用，可去除界面版权logo
// | gitee下载：https://gitee.com/likeshop_gitee/likeadmin
// | github下载：https://github.com/likeshop-github/likeadmin
// | 访问官网：https://www.likeadmin.cn
// | likeadmin团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------
// | author: likeadminTeam
// +----------------------------------------------------------------------

namespace app\adminapi\validate;


use app\common\validate\BaseValidate;


/**
 * MyStaff验证器
 * Class MyStaffValidate
 * @package app\adminapi\validate
 */
class StaffsValidate extends BaseValidate
{

     /**
      * 设置校验规则
      * @var string[]
      */
    protected $rule = [
        'id' => 'require',
        'admin_id' => 'require',
        'name' => 'require',
        'invitecode' => 'require',
    ];


    /**
     * 参数描述
     * @var string[]
     */
    protected $field = [
        'id' => 'id',
        'admin_id' => '后台',
        'name' => '员工',
        'invitecode' => '员工邀请码',
    ];


    /**
     * @notes 添加场景
     * @return StaffsValidate
     * @author likeadmin
     * @date 2025/02/20 16:14
     */
    public function sceneAdd()
    {
        return $this->only(['name']);
    }


    /**
     * @notes 编辑场景
     * @return StaffsValidate
     * @author likeadmin
     * @date 2025/02/20 16:14
     */
    public function sceneEdit()
    {
        return $this->only(['id','name']);
    }


    /**
     * @notes 删除场景
     * @return StaffsValidate
     * @author likeadmin
     * @date 2025/02/20 16:14
     */
    public function sceneDelete()
    {
        return $this->only(['id']);
    }


    /**
     * @notes 详情场景
     * @return StaffsValidate
     * @author likeadmin
     * @date 2025/02/20 16:14
     */
    public function sceneDetail()
    {
        return $this->only(['id']);
    }

}
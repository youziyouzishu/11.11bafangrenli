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
 * ProjectTasksAuditSettle验证器
 * Class ProjectTasksAuditSettleValidate
 * @package app\adminapi\validate
 */
class ProjectTasksAuditSettleValidate extends BaseValidate
{

     /**
      * 设置校验规则
      * @var string[]
      */
    protected $rule = [
        'id' => 'require',
        'admin_id' => 'require',
        'user_id' => 'require',
        'project_tasks_audit_id' => 'require',
        'name' => 'require',
        'amount' => 'require',
        'num' => 'require',
        'bank' => 'require',
        'bank_card' => 'require',
        'status' => 'require',
    ];


    /**
     * 参数描述
     * @var string[]
     */
    protected $field = [
        'id' => 'id',
        'admin_id' => '后台ID',
        'user_id' => '用户ID',
        'project_tasks_audit_id' => '用工ID',
        'name' => '名称',
        'amount' => '结算金额',
        'num' => '在岗人数',
        'bank' => '开户行',
        'bank_card' => '银行卡号',
        'status' => '状态:0=待确认,1=待审核,2=已确认',
    ];


    /**
     * @notes 添加场景
     * @return ProjectTasksAuditSettleValidate
     * @author likeadmin
     * @date 2025/01/10 10:29
     */
    public function sceneAdd()
    {
        return $this->only(['admin_id','user_id','project_tasks_audit_id','name','amount','num','bank','bank_card','status']);
    }


    /**
     * @notes 编辑场景
     * @return ProjectTasksAuditSettleValidate
     * @author likeadmin
     * @date 2025/01/10 10:29
     */
    public function sceneEdit()
    {
        return $this->only(['id','admin_id','user_id','project_tasks_audit_id','name','amount','num','bank','bank_card','status']);
    }


    /**
     * @notes 删除场景
     * @return ProjectTasksAuditSettleValidate
     * @author likeadmin
     * @date 2025/01/10 10:29
     */
    public function sceneDelete()
    {
        return $this->only(['id']);
    }


    /**
     * @notes 详情场景
     * @return ProjectTasksAuditSettleValidate
     * @author likeadmin
     * @date 2025/01/10 10:29
     */
    public function sceneDetail()
    {
        return $this->only(['id']);
    }

}
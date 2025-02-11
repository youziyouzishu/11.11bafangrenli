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

namespace app\adminapi\lists;


use app\adminapi\lists\BaseAdminDataLists;
use app\common\model\ProjectTasksAuditSettle;
use app\common\lists\ListsSearchInterface;


/**
 * ProjectTasksAuditSettle列表
 * Class ProjectTasksAuditSettleLists
 * @package app\adminapi\lists
 */
class ProjectTasksAuditSettleLists extends BaseAdminDataLists implements ListsSearchInterface
{


    /**
     * @notes 设置搜索条件
     * @return \string[][]
     * @author likeadmin
     * @date 2025/01/10 10:29
     */
    public function setSearch(): array
    {
        return [
            '=' => ['admin_id', 'user_id', 'project_tasks_audit_id', 'name', 'amount', 'num', 'bank', 'bank_card', 'status'],
        ];
    }


    /**
     * @notes 获取列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author likeadmin
     * @date 2025/01/10 10:29
     */
    public function lists(): array
    {
        return ProjectTasksAuditSettle::where($this->searchWhere)
            ->with(['audit'=>function ($query) {
                $query->with(['projectTasks']);
            },'admin','user'=>function ($query) {
                $query->with(['personalVerification']);
            }])
            ->limit($this->limitOffset, $this->limitLength)
            ->when(in_array(1,$this->adminInfo['role_id']),function ($query){
                $query->where('admin_id', $this->adminInfo['admin_id']);
            })
            ->order(['id' => 'desc'])
            ->select()
            ->toArray();
    }


    /**
     * @notes 获取数量
     * @return int
     * @author likeadmin
     * @date 2025/01/10 10:29
     */
    public function count(): int
    {
        return ProjectTasksAuditSettle::where($this->searchWhere)->when(in_array(1,$this->adminInfo['role_id']),function ($query){
            $query->where('admin_id', $this->adminInfo['admin_id']);
        })->count();
    }

}
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

namespace app\adminapi\logic;


use app\common\model\ProjectTasksAudit;
use app\common\model\ProjectTasksAuditSettle;
use app\common\logic\BaseLogic;
use think\facade\Db;


/**
 * ProjectTasksAuditSettle逻辑
 * Class ProjectTasksAuditSettleLogic
 * @package app\adminapi\logic
 */
class ProjectTasksAuditSettleLogic extends BaseLogic
{


    /**
     * @notes 添加
     * @param array $params
     * @return bool
     * @author likeadmin
     * @date 2025/01/10 10:29
     */
    public static function add(array $params): bool
    {
        Db::startTrans();
        try {
            $project_tasks_audit = ProjectTasksAudit::where(['id'=>$params['project_tasks_audit_id']])->find();
            ProjectTasksAuditSettle::create([
                'admin_id' => $project_tasks_audit->creator,
                'user_id' => $project_tasks_audit->user_id,
                'project_tasks_audit_id' => $params['project_tasks_audit_id'],
                'name' => $params['name'],
                'amount' => $params['amount'],
                'num' => $params['num'],
            ]);

            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            self::setError($e->getMessage());
            return false;
        }
    }


    /**
     * @notes 编辑
     * @param array $params
     * @return bool
     * @author likeadmin
     * @date 2025/01/10 10:29
     */
    public static function edit(array $params): bool
    {
        Db::startTrans();
        try {
            ProjectTasksAuditSettle::where('id', $params['id'])->update([
                'name' => $params['name'],
                'amount' => $params['amount'],
                'num' => $params['num'],
                'bank' => $params['bank'],
                'bank_card' => $params['bank_card'],
                'status' => $params['status']
            ]);

            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            self::setError($e->getMessage());
            return false;
        }
    }


    /**
     * @notes 删除
     * @param array $params
     * @return bool
     * @author likeadmin
     * @date 2025/01/10 10:29
     */
    public static function delete(array $params): bool
    {
        return ProjectTasksAuditSettle::destroy($params['id']);
    }


    /**
     * @notes 获取详情
     * @param $params
     * @return array
     * @author likeadmin
     * @date 2025/01/10 10:29
     */
    public static function detail($params): array
    {
        return ProjectTasksAuditSettle::findOrEmpty($params['id'])->toArray();
    }
}
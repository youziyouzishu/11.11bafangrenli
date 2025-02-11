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


namespace app\adminapi\controller;


use app\adminapi\controller\BaseAdminController;
use app\adminapi\lists\ProjectTasksAuditSettleLists;
use app\adminapi\logic\ProjectTasksAuditSettleLogic;
use app\adminapi\validate\ProjectTasksAuditSettleValidate;


/**
 * ProjectTasksAuditSettle控制器
 * Class ProjectTasksAuditSettleController
 * @package app\adminapi\controller
 */
class ProjectTasksAuditSettleController extends BaseAdminController
{


    /**
     * @notes 获取列表
     * @return \think\response\Json
     * @author likeadmin
     * @date 2025/01/10 10:29
     */
    public function lists()
    {
        return $this->dataLists(new ProjectTasksAuditSettleLists());
    }


    /**
     * @notes 添加
     * @return \think\response\Json
     * @author likeadmin
     * @date 2025/01/10 10:29
     */
    public function add()
    {
        $params = request()->post();
        $result = ProjectTasksAuditSettleLogic::add($params);
        if (true === $result) {
            return $this->success('添加成功', [], 1, 1);
        }
        return $this->fail(ProjectTasksAuditSettleLogic::getError());
    }


    /**
     * @notes 编辑
     * @return \think\response\Json
     * @author likeadmin
     * @date 2025/01/10 10:29
     */
    public function edit()
    {
        $params = request()->post();
        $result = ProjectTasksAuditSettleLogic::edit($params);
        if (true === $result) {
            return $this->success('编辑成功', [], 1, 1);
        }
        return $this->fail(ProjectTasksAuditSettleLogic::getError());
    }


    /**
     * @notes 删除
     * @return \think\response\Json
     * @author likeadmin
     * @date 2025/01/10 10:29
     */
    public function delete()
    {
        $params = (new ProjectTasksAuditSettleValidate())->post()->goCheck('delete');
        ProjectTasksAuditSettleLogic::delete($params);
        return $this->success('删除成功', [], 1, 1);
    }


    /**
     * @notes 获取详情
     * @return \think\response\Json
     * @author likeadmin
     * @date 2025/01/10 10:29
     */
    public function detail()
    {
        $params = (new ProjectTasksAuditSettleValidate())->goCheck('detail');
        $result = ProjectTasksAuditSettleLogic::detail($params);
        return $this->data($result);
    }


}
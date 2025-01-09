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


namespace app\adminapi\controller;


use app\adminapi\controller\BaseAdminController;
use app\adminapi\lists\MemberAllLists;
use app\common\model\auth\Admin;
use app\common\model\ProjectTasksAudit;
use app\common\model\user\User;
use app\common\http\esign\Config;
use app\Request;
use think\db\Query;

/**
 * ProjectTasksAudit控制器
 * Class ProjectTasksAuditController
 * @package app\adminapi\controller
 */
class MembersController extends BaseAdminController
{
    /**
     * @notes 获取列表
     * @return \think\response\Json
     * @author ideaadmin
     * @date 2024/01/07 18:30
     */
    public function lists()
    {
        return $this->dataLists(new MemberAllLists());
    }

    function getProjectAuditListByUser(Request $request)
    {
        $param = $request->param();
        $user = User::with(['personalVerification'=>function (Query $query) {
            $query->where('realname_status',4);
        }])->find($param['user_id']);
        if ($user->isEmpty()){
            return $this->fail('用户不存在');
        }
        $rows = ProjectTasksAudit::where(['user_id'=>$param['user_id']])
            ->with(['projectTasks','enterpriseVerification'])

            ->where(['status'=>2])
            ->select();

        return $this->data(['user'=>$user,'list'=>$rows]);
    }

    /**
     * @notes 获取列表
     * @return \think\response\Json
     * @author ideaadmin
     * @date 2024/01/07 18:30
     */
    public function toim()
    {
        $tmp = request()->post();
        $id = intval($tmp['id']);
        $user = User::findOrEmpty($id);
        if ($user->isEmpty()){
            return $this->fail('用户不存在');
        }
        // 获取管理员记录
        $admin = Admin::findOrEmpty($this->adminId); // 假设是ID为1的管理员

        if (!$admin) {
            return $this->fail('管理员记录不存在');
        }

        $has = $admin->consultLog()->whereTime('create_time', 'today')->where('user_id', $user->id)->find();
        if (!$has) {
            if ($admin->consultLog()->whereTime('create_time', 'today')->count() >= 30 && in_array(1, $this->adminInfo['role_id'])) {
                if ($admin->consult_times <= 0) {
                    //只有人力公司才限制次数
                    return $this->fail('每日可主动发起沟通超过30次，已达到上限');
                } else {
                    $admin->consult_times -= 1;
                    $admin->save();
                }
            }

            $admin->consultLog()->save(['user_id' => $user->id]);
        }

        $protocol = request()->scheme();
        $host = $_SERVER['HTTP_HOST'];
        $url = $protocol . '://' . $host . '/business/im?conversationId=C2Ccl_' . $user['sn'];
        $data = ['url' => $url];
        return $this->data($data);
    }
}

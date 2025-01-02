<?php
declare (strict_types = 1);

namespace app\api\controller;

use app\common\model\auth\Admin;
use app\common\model\auth\AdminRole;
use app\common\model\EnterpriseVerification;
use app\common\model\ProjectTasks;
use app\common\model\ProjectTasksAudit;
use app\common\model\ProjectTasksAutoAssess;
use think\db\Query;
use think\facade\Cache;
use think\Request;

class BusinessController extends BaseApiController
{
    #获取人力公司列表
    function getBusinesslist(Request $request)
    {
        $admin_ids = AdminRole::where(['role_id'=>1])->column('admin_id');#获取人力公司admin_ids
        $verification_ids = EnterpriseVerification::whereIn('user_id',$admin_ids)->where('realname_status',6)->column('user_id');
        $rows = Admin::whereIn('id',$verification_ids)
            ->with(['enterpriseVerification','projectTasks'])
            ->withCount('projectTasks')
            ->withCount('userHr')
            ->withCount('userStationed')
            ->paginate()
            ->items();
        return $this->data($rows);
    }

    #获取人力公司详情
    function getBusinessDetail(Request $request)
    {
        $admin_id = $request->param('admin_id');
        $row = Admin::with(['enterpriseVerification'])
            ->withCount('projectTasks')
            ->withCount('userHr')
            ->withCount('userStationed')
            ->with(['projectTasks'=>function (Query $query) {
                $query->order('id','desc')->limit(5);
            }])
            ->find($admin_id);
        $assess = [
            'grade'=>ProjectTasksAutoAssess::where(['admin_id'=>$row->id])->avg('grade'),
            'total_count'=>ProjectTasksAutoAssess::where(['admin_id'=>$row->id])->count(),
            'one_count'=>ProjectTasksAutoAssess::where(['admin_id'=>$row->id,'grade'=>1])->count(),
            'two_count'=>ProjectTasksAutoAssess::where(['admin_id'=>$row->id,'grade'=>2])->count(),
            'three_count'=>ProjectTasksAutoAssess::where(['admin_id'=>$row->id,'grade'=>3])->count(),
            'four_count'=>ProjectTasksAutoAssess::where(['admin_id'=>$row->id,'grade'=>4])->count(),
            'five_count'=>ProjectTasksAutoAssess::where(['admin_id'=>$row->id,'grade'=>5])->count(),
            'good_count'=>ProjectTasksAutoAssess::where(['admin_id'=>$row->id])->where('grade',5)->count(),
            'normal_count'=>ProjectTasksAutoAssess::where(['admin_id'=>$row->id])->whereBetween('grade',[2,4])->count(),
            'bad_count'=>ProjectTasksAutoAssess::where(['admin_id'=>$row->id])->where('grade',1)->count(),
            'list'=>[
                'all'=>ProjectTasksAutoAssess::where(['admin_id'=>$row->id])->with(['user'])->order('id','desc')->limit(5)->select(),
                'good'=>ProjectTasksAutoAssess::where(['admin_id'=>$row->id])->where('grade',5)->with(['user'])->order('id','desc')->limit(5)->select(),
                'normal'=>ProjectTasksAutoAssess::where(['admin_id'=>$row->id])->whereBetween('grade',[2,4])->with(['user'])->order('id','desc')->limit(5)->select(),
                'bad'=>ProjectTasksAutoAssess::where(['admin_id'=>$row->id])->where('grade',1)->with(['user'])->order('id','desc')->limit(5)->select(),
            ]
        ];
        $row->setAttr('assess',$assess);
        $row = $row->toArray();
        return $this->data($row);
    }
    #获取人力公司项目
    function getProjectByBusiness(Request $request)
    {
        $admin_id = $request->param('admin_id');
        $rows = ProjectTasks::where(['creator'=>$admin_id])
            ->with(['enterpriseVerification'])
            ->select();
        return $this->data($rows);
    }

    #发起合同申请
    function contract(Request $request)
    {
        $job_id = $request->param('job_id');
        $type = $request->param('type');
        $price = $request->param('price');
        $project = ProjectTasks::find($job_id);
        $audit = ProjectTasksAudit::where(['user_id' =>$this->userId])->where(['project_id' => $project->id])->find();
        if ($audit) {
            return $this->fail("已申请项目，等待人力公司发起合同签署");
        }

        $ProjectTasks = ProjectTasksAudit::create([
            'creator' => $project->creator,
            'project_id' => $project->id,
            'user_id' => $this->userId,
            'type' => $this->userInfo['role'],
            'status' =>  0,
            'remarks' => '客户端发起合同申请',
        ]);
        Cache::set("ProjectTasksAudit-{$ProjectTasks->id}",[
            'type'=>$type,
            'price'=>$price,
        ]);
        return  $this->success('');
    }

    function assess(Request $request)
    {
        $project_tasks_audit_id = $request->param('project_tasks_audit_id');
        $grade = $request->param('grade');
        $images = $request->param('images');
        $content = $request->param('content');
        $row = ProjectTasksAudit::find($project_tasks_audit_id);
        if (!$row){
            return $this->fail('用工记录不存在');
        }
        if ($row->status != 2 || $row->assess_status != 1){
            return $this->fail('用工记录状态异常');
        }

        ProjectTasksAutoAssess::create([
            'project_id'=>$row->project_id,
            'project_tasks_audit_id'=>$row->id,
            'admin_id'=>$row->creator,
            'user_id'=>$row->user_id,
            'grade'=>$grade,
            'images'=>$images,
            'content'=>$content,
        ]);
        return $this->success('评价成功');
    }

}

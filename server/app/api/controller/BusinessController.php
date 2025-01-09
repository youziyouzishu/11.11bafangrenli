<?php
declare (strict_types=1);

namespace app\api\controller;

use app\common\model\auth\Admin;
use app\common\model\auth\AdminRole;
use app\common\model\EnterpriseVerification;
use app\common\model\ProjectReport;
use app\common\model\ProjectReportDetail;
use app\common\model\ProjectTasks;
use app\common\model\ProjectTasksAudit;
use app\common\model\ProjectTasksAutoAssess;
use app\common\model\user\User;
use app\common\service\ConfigService;
use think\db\Query;
use think\facade\Cache;
use think\Request;

class BusinessController extends BaseApiController
{
    public array $notNeedLogin = ['createReport','getConfigs'];
    #获取人力公司列表
    function getBusinesslist(Request $request)
    {
        $admin_ids = AdminRole::where(['role_id' => 1])->column('admin_id');#获取人力公司admin_ids
        $verification_ids = EnterpriseVerification::whereIn('user_id', $admin_ids)->where('realname_status', 6)->column('user_id');
        $rows = Admin::whereIn('id', $verification_ids)
            ->with(['enterpriseVerification'])
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
        $row = Admin::where(['id'=>$admin_id])
            ->withCount('projectTasks')
            ->withCount('userHr')
            ->withCount('userStationed')
            ->with(['enterpriseVerification','projectTasks' => function (Query $query) {
                $query->with(['enterpriseVerification','creator'])->order('id', 'desc')->limit(5);
            }])
            ->find();

        $assess = [
            'grade' => ProjectTasksAutoAssess::where(['admin_id' => $row->id])->avg('grade'),
            'total_count' => ProjectTasksAutoAssess::where(['admin_id' => $row->id])->count(),
            'one_count' => ProjectTasksAutoAssess::where(['admin_id' => $row->id, 'grade' => 1])->count(),
            'two_count' => ProjectTasksAutoAssess::where(['admin_id' => $row->id, 'grade' => 2])->count(),
            'three_count' => ProjectTasksAutoAssess::where(['admin_id' => $row->id, 'grade' => 3])->count(),
            'four_count' => ProjectTasksAutoAssess::where(['admin_id' => $row->id, 'grade' => 4])->count(),
            'five_count' => ProjectTasksAutoAssess::where(['admin_id' => $row->id, 'grade' => 5])->count(),
            'good_count' => ProjectTasksAutoAssess::where(['admin_id' => $row->id])->where('grade', 5)->count(),
            'normal_count' => ProjectTasksAutoAssess::where(['admin_id' => $row->id])->whereBetween('grade', [2, 4])->count(),
            'bad_count' => ProjectTasksAutoAssess::where(['admin_id' => $row->id])->where('grade', 1)->count(),
            'list' => [
                'all' => ProjectTasksAutoAssess::where(['admin_id' => $row->id])->with(['user'])->order('id', 'desc')->limit(5)->select(),
                'good' => ProjectTasksAutoAssess::where(['admin_id' => $row->id])->where('grade', 5)->with(['user'])->order('id', 'desc')->limit(5)->select(),
                'normal' => ProjectTasksAutoAssess::where(['admin_id' => $row->id])->whereBetween('grade', [2, 4])->with(['user'])->order('id', 'desc')->limit(5)->select(),
                'bad' => ProjectTasksAutoAssess::where(['admin_id' => $row->id])->where('grade', 1)->with(['user'])->order('id', 'desc')->limit(5)->select(),
            ],
        ];
        $row->setAttr('assess',$assess);
        $row->setAttr('imid', "C2Chr_" . $row->sn);
        return $this->data($row);
    }

    #获取人力公司项目
    function getProjectList(Request $request)
    {
        $admin_id = $request->param('admin_id');
        $rows = ProjectTasks::where(['creator' => $admin_id])
            ->with(['enterpriseVerification','creator'])
            ->select();
        return $this->data($rows);
    }


    #发起合同申请
    function contract(Request $request)
    {
        $job_id = $request->param('job_id');
        $type = $request->param('type');#
        $price = $request->param('price');
        $project = ProjectTasks::find($job_id);
        $audit = ProjectTasksAudit::where(['user_id' => $this->userId])->where(['project_id' => $project->id])->find();
        if ($audit) {
            return $this->fail("已申请项目，等待人力公司发起合同签署");
        }

        $ProjectTasks = ProjectTasksAudit::create([
            'creator' => $project->creator,
            'project_id' => $project->id,
            'user_id' => $this->userId,
            'type' => $this->userInfo['role'],
            'status' => 0,
            'remarks' => '客户端发起合同申请',
        ]);
        Cache::set("ProjectTasksAudit-{$ProjectTasks->id}", [
            'type' => $type,
            'price' => $price,
        ]);
        return $this->success('成功');
    }

    function assess(Request $request)
    {
        $project_tasks_audit_id = $request->param('project_tasks_audit_id');
        $grade = $request->param('grade');
        $images = $request->param('images');
        $content = $request->param('content');
        $row = ProjectTasksAudit::find($project_tasks_audit_id);
        if (!$row) {
            return $this->fail('用工记录不存在');
        }
        if ($row->status != 2 || $row->assess_status != 1) {
            return $this->fail('用工记录状态异常');
        }

        ProjectTasksAutoAssess::create([
            'project_id' => $row->project_id,
            'project_tasks_audit_id' => $row->id,
            'admin_id' => $row->creator,
            'user_id' => $row->user_id,
            'grade' => $grade,
            'images' => $images,
            'content' => $content,
        ]);
        return $this->success('评价成功');
    }

    function getProjectTasksAuditList(Request $request)
    {
        $rows = ProjectTasksAudit::where(['user_id' => $this->userId])
            ->with(['projectTasks'=>function ($query) {
                $query->with(['enterpriseVerification','creator']);
            }])
            ->paginate()
            ->items();
        return $this->data($rows);
    }

    #获取评价列表
    function getAssessList(Request $request)
    {
        $rows = ProjectTasksAutoAssess::where(['user_id' => $this->userId])
            ->paginate()
            ->items();
        return $this->data($rows);
    }

    #更新日报
    function updateReport(Request $request)
    {
        $project_tasks_id = $request->param('project_tasks_id');
        $project = ProjectTasks::find($project_tasks_id);
        if (!$project) {
            return $this->fail('项目不存在');
        }
        $report = ProjectReport::where(['project_tasks_id'=>$project_tasks_id,'date'=>date('Y-m-d')])->find();
        if (!$report){
            return $this->fail('系统未生成日报');
        }

        $user = User::find($this->userId);
        if ($user->role != 3){
            return $this->fail('无权限');
        }
        $daogang_num = ProjectTasksAudit::where(['project_id'=>$project_tasks_id,'type'=>2])->count();
        $jiesuan_amount = $daogang_num * 1000;
        $report->user_id = $this->userId;
        $report->daogang_num = $daogang_num;
        $report->jiesuan_amount = $jiesuan_amount;
        $report->daidaogang_num = ProjectTasksAudit::where(['project_id'=>$project_tasks_id,'type'=>1])->count();
        $report->liushi_num = ProjectTasksAudit::where(['project_id'=>$project_tasks_id,'type'=>3])->count();
        $report->lizhi_num = ProjectTasksAudit::where(['project_id'=>$project_tasks_id,'type'=>4])->count();
        $report->mianshi_num = ProjectTasksAudit::where(['project_id'=>$project_tasks_id,'type'=>5])->count();
        $report->ruzhi_num = ProjectTasksAudit::where(['project_id'=>$project_tasks_id,'type'=>6])->count();
        $report->company_amount = 0 - $jiesuan_amount;
        $report->jiesuan_amount = $jiesuan_amount;
        $report->status = 1;
        $report->save();
        //每次更新日报都把确认记录删除
        $report->detail()->delete();
        //每次更新日报都把反馈改为已审核
        $report->mark()->where(['status'=>0])->update(['status'=>1]);
        return $this->success('成功');
    }

    #获取日报列表
    function getReportList(Request $request)
    {
        $project_tasks_id = $request->param('project_tasks_id');
        $rows = ProjectReport::where(['project_tasks_id'=>$project_tasks_id])
            ->withCount(['detail'=>function ($query) {
                $query->where('status',0);
            }])
            ->order('id','desc')
            ->filter(function ($item){
                if ($item->date != date('Y-m-d')){
                    $item->setAttr('is_today',0);
                }else{
                    $item->setAttr('is_today',1);
                }
            })
            ->paginate()
            ->items();
        return $this->data($rows);
    }

    #定时任务  每天凌晨创建日报
    function createReport()
    {
        $rows = ProjectTasks::select();
        foreach ($rows as $row){
            $report = ProjectReport::where(['project_tasks_id'=>$row->id])->whereTime('create_time','today')->find();
            if (!$report){
                //创建日报
                ProjectReport::create([
                    'project_tasks_id' => $row->id,
                    'date' => date('Y-m-d'),
                    'mianshi_num'=>0,
                    'ruzhi_num'=>0,
                    'daogang_num' => 0,
                    'daidaogang_num'=>0,
                    'liushi_num'=>0,
                    'lizhi_num'=>0,
                    'company_amount'=>0,
                    'jiesuan_amount'=>0
               ]);
            }
        }
    }

    #确认日报
    function confirmReport(Request $request)
    {
        $report_id = $request->param('report_id');
        $row = ProjectReport::find($report_id);
        $user = User::find($this->userId);
        if ($user->role != 2){
            return $this->fail('无权限');
        }
        if ($row->status != 1){
            return $this->fail('日报状态异常');
        }

        if ($row->date != date('Y-m-d')){
            return $this->fail('只能确认当天日报');
        }

        $detail = $row->detail()->where(['user_id'=>$this->userId])->select();
        if (!$detail->isEmpty()){
            return $this->fail('不能重复确认');
        }
        //添加确认信息
        $row->detail()->save([
            'user_id'=>$this->userId,
        ]);
        //获取已签合同的招聘顾问
        $hr_count = ProjectTasksAudit::where(['project_id'=>$row->project_tasks_id,'type'=>2,'status'=>2])->count();
        if ($row->detail()->count() >= $hr_count){
            $row->status = 2;
            $row->save();
        }
        return $this->success('成功');
    }

    #日报反馈
    function markReport(Request $request)
    {
        $report_id = $request->param('report_id');
        $mark = $request->param('mark');
        $row = ProjectReport::find($report_id);
        $user = User::find($this->userId);
        if ($user->role != 2){
            return $this->fail('无权限');
        }
        if ($row->status != 1){
            return $this->fail('日报状态异常');
        }
        if ($row->date != date('Y-m-d')){
            return $this->fail('只能反馈当天日报');
        }
        $row->mark()->save([
            'user_id'=>$this->userId,
            'mark'=>$mark
        ]);
        return $this->success('成功');
    }

    #获取反馈列表
    function getReportMarkList(Request $request)
    {
        $report_id = $request->param('report_id');
        $row = ProjectReport::find($report_id);
        $rows = $row->mark()->with('user')->order('id','desc')->select();
        return $this->data($rows);
    }


    #获取配置
    function getConfigs()
    {
        $data = ConfigService::get('configs','configs');
        return $this->data($data);
    }
    
    





}

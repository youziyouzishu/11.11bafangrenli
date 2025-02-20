<?php

namespace app\common\model;

use app\common\model\user\User;
use think\model\concern\SoftDelete;

class ProjectReport extends BaseModel
{
    use SoftDelete;
    protected $name = 'project_report';
    protected $deleteTime = 'delete_time';

    protected $append = ['status_text'];

    public function getStatusTextAttr($value)
    {
        $value = $this->status;
        $list = [
            0 => '待更新',
            1 => '待确认',
            2 => '已确认',
            3 => '日报缺失',
        ];
        return $list[$value] ?? '';
    }

    function detail()
    {
        return $this->hasMany(ProjectReportDetail::class,'project_report_id','id');
    }

    function mark()
    {
        return $this->hasMany(ProjectReportMark::class,'project_report_id','id');
    }

    function projectTasks()
    {
        return $this->belongsTo(ProjectTasks::class,'project_tasks_id','id');
    }

    function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }


}
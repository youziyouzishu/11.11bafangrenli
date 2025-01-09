<?php

namespace app\common\model;

use think\model\concern\SoftDelete;

class ProjectReport extends BaseModel
{
    use SoftDelete;
    protected $name = 'project_report';
    protected $deleteTime = 'delete_time';

    function detail()
    {
        return $this->hasMany(ProjectReportDetail::class,'project_report_id','id');
    }

    function mark()
    {
        return $this->hasMany(ProjectReportMark::class,'project_report_id','id');
    }


}
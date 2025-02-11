<?php

namespace app\common\model;

use think\model\concern\SoftDelete;

class ProjectReportDetail extends BaseModel
{
    use SoftDelete;
    protected $name = 'project_report_detail';
    protected $deleteTime = 'delete_time';


}
<?php

namespace app\common\model;

use app\common\model\user\User;
use think\model\concern\SoftDelete;

class ProjectReportMark extends BaseModel
{
    use SoftDelete;
    protected $name = 'project_report_mark';
    protected $deleteTime = 'delete_time';

    function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

}
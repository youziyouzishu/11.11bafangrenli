<?php

namespace app\common\model;

use app\common\model\auth\Admin;
use app\common\model\user\User;
use think\model\concern\SoftDelete;

class ProjectTasksAuditSettle extends BaseModel
{
    use SoftDelete;
    protected $name = 'project_tasks_audit_settle';
    protected $deleteTime = 'delete_time';

    function audit()
    {
        return $this->belongsTo(ProjectTasksAudit::class, 'project_tasks_audit_id', 'id');
    }

    function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id', 'id');
    }

    function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

}
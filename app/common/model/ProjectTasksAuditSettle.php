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

    protected $append = [
        'status_text'
    ];
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

    function getStatusTextAttr($value, $data)
    {
        $value = $value ?: $data['status'];
        $list = [
            0 => '待用户确认',
            1 => '用户拒绝',
            2 => '待管理员审核',
            3 => '管理员审核通过',
            4 => '管理员审核拒绝',
            5 => '人力公司通过',
            6 => '人力公司拒绝',
        ];
        return $list[$value];
    }

}
import request from '@/utils/request'

// 邀请日志列表
export function apiProjectTasksAuditShareLists(params: any) {
    return request.get({ url: '/project_tasks_audit_share/lists', params })
}

// 添加邀请日志
export function apiProjectTasksAuditShareAdd(params: any) {
    return request.post({ url: '/project_tasks_audit_share/add', params })
}

// 编辑邀请日志
export function apiProjectTasksAuditShareEdit(params: any) {
    return request.post({ url: '/project_tasks_audit_share/edit', params })
}

// 删除邀请日志
export function apiProjectTasksAuditShareDelete(params: any) {
    return request.post({ url: '/project_tasks_audit_share/delete', params })
}

// 邀请日志详情
export function apiProjectTasksAuditShareDetail(params: any) {
return request.get({ url: '/project_tasks_audit_share/detail', params })
}
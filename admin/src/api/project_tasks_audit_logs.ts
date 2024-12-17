import request from '@/utils/request'

// 项目任务审计表列表
export function apiProjectTasksAuditLogsLists(params: any) {
    return request.get({ url: '/project_tasks_audit_logs/lists', params })
}

// 添加项目任务审计表
export function apiProjectTasksAuditLogsAdd(params: any) {
    return request.post({ url: '/project_tasks_audit_logs/add', params })
}

// 编辑项目任务审计表
export function apiProjectTasksAuditLogsEdit(params: any) {
    return request.post({ url: '/project_tasks_audit_logs/edit', params })
}

// 删除项目任务审计表
export function apiProjectTasksAuditLogsDelete(params: any) {
    return request.post({ url: '/project_tasks_audit_logs/delete', params })
}

// 项目任务审计表详情
export function apiProjectTasksAuditLogsDetail(params: any) {
    return request.get({ url: '/project_tasks_audit_logs/detail', params })
}
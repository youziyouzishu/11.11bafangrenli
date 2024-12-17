import request from "@/utils/request";

// 招聘任务审核列表
export function apiProjectTasksAuditLists(params: any) {
  return request.get({ url: "/project_tasks_audit/lists", params });
}

// 添加招聘任务审核
export function apiProjectTasksAuditAdd(params: any) {
  return request.post({ url: "/project_tasks_audit/add", params });
}

// 编辑招聘任务审核
export function apiProjectTasksAuditEdit(params: any) {
  return request.post({ url: "/project_tasks_audit/edit", params });
}

// 删除招聘任务审核
export function apiProjectTasksAuditDelete(params: any) {
  return request.post({ url: "/project_tasks_audit/delete", params });
}

// 招聘任务审核
export function apiProjectTasksAudited(params: any) {
  return request.post({ url: "/project_tasks_audit/audited", params });
}

// 招聘任务审核
export function apiProjectTasksWorkAudited(params: any) {
  return request.post({ url: "/project_tasks_audit/workAudited", params });
}

// 招聘任务审核
export function apiProjectTaskChangeOnsiteUser(params: any) {
  return request.post({ url: "/project_tasks_audit/changeOnsiteUser", params });
}

// 招聘任务审核详情
export function apiProjectTasksAuditDetail(params: any) {
  return request.get({ url: "/project_tasks_audit/detail", params });
}

// 获取招聘经理或驻场名称
export function apiGetNamesByType(params: any) {
  return request.post({ url: "/project_tasks_audit/getNamesByType", params });
}

// 获取招聘经理或驻场名称
export function apiGetMembers(params: any) {
  return request.post({ url: "/project_tasks_audit/getMembers", params });
}

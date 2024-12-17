import request from "@/utils/request";

// 项目任务表列表
export function apiProjectTasksLists(params: any) {
  return request.get({ url: "/project_tasks/lists", params });
}

// 添加项目任务表
export function apiProjectTasksAdd(params: any) {
  return request.post({ url: "/project_tasks/add", params });
}

// 编辑项目任务表
export function apiProjectTasksEdit(params: any) {
  return request.post({ url: "/project_tasks/edit", params });
}

// 删除项目任务表
export function apiProjectTasksDelete(params: any) {
  return request.post({ url: "/project_tasks/delete", params });
}

// 关闭项目任务表
export function apiProjectTasksClose(params: any) {
  return request.post({ url: "/project_tasks/close", params });
}

// 审核项目任务表
export function apiProjectTasksAudit(params: any) {
  return request.post({ url: "/project_tasks/audit", params });
}

// 项目任务表详情
export function apiProjectTasksDetail(params: any) {
  return request.get({ url: "/project_tasks/detail", params });
}

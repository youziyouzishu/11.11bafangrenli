import request from "@/utils/request";

// 消息中心列表
export function apiAdminMsgLists(params: any) {
  return request.get({ url: "/admin_msg/lists", params });
}

// 添加消息中心
export function apiAdminMsgAdd(params: any) {
  return request.post({ url: "/admin_msg/add", params });
}

// 编辑消息中心
export function apiAdminMsgEdit(params: any) {
  return request.post({ url: "/admin_msg/edit", params });
}

// 删除消息中心
export function apiAdminMsgDelete(params: any) {
  return request.post({ url: "/admin_msg/delete", params });
}

// 阅读消息
export function apiAdminMsgReview(params: any) {
  return request.post({ url: "/admin_msg/review", params });
}

// 消息中心详情
export function apiAdminMsgDetail(params: any) {
  return request.get({ url: "/admin_msg/detail", params });
}

export function apiAdminMsgNotificationList(params: any) {
  return request.post({ url: "/admin_msg/notificationList", params });
}

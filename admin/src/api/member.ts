import request from "@/utils/request";

// 招聘任务审核列表
export function apiMembers(params: any) {
  return request.get({ url: "/members/lists", params });
}

// 招聘任务审核列表
export function apiToIm(params: any) {
  return request.post({ url: "/members/toim", params });
}

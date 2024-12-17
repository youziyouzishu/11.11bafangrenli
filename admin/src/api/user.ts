import config from "@/config";
import request from "@/utils/request";

// 登录
export function login(params: Record<string, any>) {
  return request.post({
    url: "/login/account",
    params: { ...params, terminal: config.terminal },
  });
}

export function register(params: Record<string, any>) {
  return request.post({
    url: "/login/register",
    params: { ...params, terminal: config.terminal },
  });
}
// im登录
export function imlogin() {
  return request.post({ url: "/login/imLogin" });
}

// 退出登录
export function logout() {
  return request.post({ url: "/login/logout" });
}

// 用户信息
export function getUserInfo() {
  return request.get({ url: "/auth.admin/mySelf" });
}

// 编辑管理员信息
export function setUserInfo(params: any) {
  return request.post({ url: "/auth.admin/editSelf", params });
}

// 编辑管理员信息
export function setAuth(params: any) {
  return request.post({ url: "/auth.admin/auth", params });
}

// 编辑管理员信息
export function signContract(params: any) {
  return request.post({ url: "/auth.admin/signContract", params });
}

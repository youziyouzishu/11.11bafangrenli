import request from "@/utils/request";

export function getRechargeConfig() {
  return request.get({ url: "/recharge.recharge/getConfig" });
}

// 设置
export function setRechargeConfig(params: any) {
  return request.post({ url: "/recharge.recharge/setConfig", params });
}

//充值
export function recharge(data: any) {
  return request.post({ url: "/recharge/recharge", data });
}

//充值记录
export function rechargeRecord(data: any) {
  return request.get({ url: "/recharge/lists", data });
}

// 充值配置
export function rechargeConfig() {
  return request.get({ url: "/recharge/config" });
}

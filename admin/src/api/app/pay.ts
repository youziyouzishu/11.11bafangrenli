import request from "@/utils/request";

//支付方式
export function getPayWay(data: any) {
  return request.post({ url: "/pay/payWay", data });
}

// 预支付
export function prepay(data: any) {
  return request.post({ url: "/pay/prepay", data });
}

// 预支付
export function getPayResult(data: any) {
  return request.get({ url: "/pay/payStatus", data });
}

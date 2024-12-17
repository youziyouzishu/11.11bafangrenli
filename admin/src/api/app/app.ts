import request from "@/utils/request";

//发送短信
export function smsSend(params: any) {
  return request.post({ url: "/sms/sendCode", params });
}

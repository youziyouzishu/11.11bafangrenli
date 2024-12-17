import request from "@/utils/request";

// 合同表列表
export function apiEContractLists(params: any) {
  return request.get({ url: "/e_contract/lists", params });
}

// 添加合同表
export function apiEContractAdd(params: any) {
  return request.post({ url: "/e_contract/add", params });
}

// 编辑合同表
export function apiEContractEdit(params: any) {
  return request.post({ url: "/e_contract/edit", params });
}

// 删除合同表
export function apiEContractDelete(params: any) {
  return request.post({ url: "/e_contract/delete", params });
}

// 合同表详情
export function apiEContractDetail(params: any) {
  return request.get({ url: "/e_contract/detail", params });
}

export function apiSignContractUrl(params: any) {
  return request.post({ url: "/e_contract/signContractUrl", params });
}

export function apiDownloadContractUrl(params: any) {
  return request.post({ url: "/e_contract/downloadContractUrl", params });
}

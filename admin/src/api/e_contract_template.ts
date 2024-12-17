import request from "@/utils/request";

// 合同模版列表
export function apiEContractTemplateLists(params: any) {
  return request.get({ url: "/e_contract_template/lists", params });
}

// 添加合同模版
export function apiEContractTemplateAdd(params: any) {
  return request.post({ url: "/e_contract_template/add", params });
}

// 编辑合同模版
export function apiEContractTemplateEdit(params: any) {
  return request.post({ url: "/e_contract_template/edit", params });
}

// 删除合同模版
export function apiEContractTemplateDelete(params: any) {
  return request.post({ url: "/e_contract_template/delete", params });
}

// 合同模版详情
export function apiEContractTemplateDetail(params: any) {
  return request.get({ url: "/e_contract_template/detail", params });
}

// 模版编辑链接
export function apiEContractTemplateEditUrl(params: any) {
  return request.get({ url: "/e_contract_template/templateEditUrl", params });
}

import request from '@/utils/request'

// 企业实名认证列表
export function apiEnterpriseVerificationLists(params: any) {
    return request.get({ url: '/enterprise_verification/lists', params })
}

// 添加企业实名认证
export function apiEnterpriseVerificationAdd(params: any) {
    return request.post({ url: '/enterprise_verification/add', params })
}

// 编辑企业实名认证
export function apiEnterpriseVerificationEdit(params: any) {
    return request.post({ url: '/enterprise_verification/edit', params })
}

// 删除企业实名认证
export function apiEnterpriseVerificationDelete(params: any) {
    return request.post({ url: '/enterprise_verification/delete', params })
}

// 企业实名认证详情
export function apiEnterpriseVerificationDetail(params: any) {
    return request.get({ url: '/enterprise_verification/detail', params })
}
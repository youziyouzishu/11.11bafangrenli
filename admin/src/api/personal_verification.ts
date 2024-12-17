import request from '@/utils/request'

// 个人实名认证表列表
export function apiPersonalVerificationLists(params: any) {
    return request.get({ url: '/personal_verification/lists', params })
}

// 添加个人实名认证表
export function apiPersonalVerificationAdd(params: any) {
    return request.post({ url: '/personal_verification/add', params })
}

// 编辑个人实名认证表
export function apiPersonalVerificationEdit(params: any) {
    return request.post({ url: '/personal_verification/edit', params })
}

// 删除个人实名认证表
export function apiPersonalVerificationDelete(params: any) {
    return request.post({ url: '/personal_verification/delete', params })
}

// 个人实名认证表详情
export function apiPersonalVerificationDetail(params: any) {
    return request.get({ url: '/personal_verification/detail', params })
}
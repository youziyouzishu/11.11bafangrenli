import request from '@/utils/request'

// 沟通次数列表
export function apiConsultLists(params: any) {
    return request.get({ url: '/consult/lists', params })
}

// 添加沟通次数
export function apiConsultAdd(params: any) {
    return request.post({ url: '/consult/add', params })
}

// 编辑沟通次数
export function apiConsultEdit(params: any) {
    return request.post({ url: '/consult/edit', params })
}

// 删除沟通次数
export function apiConsultDelete(params: any) {
    return request.post({ url: '/consult/delete', params })
}

// 沟通次数详情
export function apiConsultDetail(params: any) {
    return request.get({ url: '/consult/detail', params })
}
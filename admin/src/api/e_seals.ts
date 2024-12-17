import request from '@/utils/request'

// 印章信息表列表
export function apiESealsLists(params: any) {
    return request.get({ url: '/e_seals/lists', params })
}

// 添加印章信息表
export function apiESealsAdd(params: any) {
    return request.post({ url: '/e_seals/add', params })
}

// 编辑印章信息表
export function apiESealsEdit(params: any) {
    return request.post({ url: '/e_seals/edit', params })
}

// 删除印章信息表
export function apiESealsDelete(params: any) {
    return request.post({ url: '/e_seals/delete', params })
}

// 印章信息表详情
export function apiESealsDetail(params: any) {
    return request.get({ url: '/e_seals/detail', params })
}
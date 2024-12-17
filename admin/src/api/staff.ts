import request from '@/utils/request'

// 业务公司员工列表
export function apiStaffLists(params: any) {
    return request.get({ url: '/staff/lists', params })
}

// 添加业务公司员工
export function apiStaffAdd(params: any) {
    return request.post({ url: '/staff/add', params })
}

// 编辑业务公司员工
export function apiStaffEdit(params: any) {
    return request.post({ url: '/staff/edit', params })
}

// 删除业务公司员工
export function apiStaffDelete(params: any) {
    return request.post({ url: '/staff/delete', params })
}

// 业务公司员工详情
export function apiStaffDetail(params: any) {
    return request.get({ url: '/staff/detail', params })
}
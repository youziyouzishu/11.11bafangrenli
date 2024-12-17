import request from '@/utils/request'

// 员工邀请记录列表
export function apiStaffLayerLists(params: any) {
    return request.get({ url: '/staff_layer/lists', params })
}

// 添加员工邀请记录
export function apiStaffLayerAdd(params: any) {
    return request.post({ url: '/staff_layer/add', params })
}

// 编辑员工邀请记录
export function apiStaffLayerEdit(params: any) {
    return request.post({ url: '/staff_layer/edit', params })
}

// 删除员工邀请记录
export function apiStaffLayerDelete(params: any) {
    return request.post({ url: '/staff_layer/delete', params })
}

// 员工邀请记录详情
export function apiStaffLayerDetail(params: any) {
    return request.get({ url: '/staff_layer/detail', params })
}
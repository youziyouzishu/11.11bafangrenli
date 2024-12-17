import request from '@/utils/request'

// 评价表列表
export function apiReviewTableLists(params: any) {
    return request.get({ url: '/review_table/lists', params })
}

// 添加评价表
export function apiReviewTableAdd(params: any) {
    return request.post({ url: '/review_table/add', params })
}

// 编辑评价表
export function apiReviewTableEdit(params: any) {
    return request.post({ url: '/review_table/edit', params })
}

// 删除评价表
export function apiReviewTableDelete(params: any) {
    return request.post({ url: '/review_table/delete', params })
}

// 评价表详情
export function apiReviewTableDetail(params: any) {
    return request.get({ url: '/review_table/detail', params })
}
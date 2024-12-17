import request from '@/utils/request'

// 项目日报列表
export function apiProjectDailyReportLists(params: any) {
    return request.get({ url: '/project_daily_report/lists', params })
}

// 添加项目日报
export function apiProjectDailyReportAdd(params: any) {
    return request.post({ url: '/project_daily_report/add', params })
}

// 编辑项目日报
export function apiProjectDailyReportEdit(params: any) {
    return request.post({ url: '/project_daily_report/edit', params })
}

// 删除项目日报
export function apiProjectDailyReportDelete(params: any) {
    return request.post({ url: '/project_daily_report/delete', params })
}

// 项目日报详情
export function apiProjectDailyReportDetail(params: any) {
    return request.get({ url: '/project_daily_report/detail', params })
}
<?php
// +----------------------------------------------------------------------
// | likeadmin快速开发前后端分离管理后台（PHP版）
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 开源版本可自由商用，可去除界面版权logo
// | gitee下载：https://gitee.com/likeshop_gitee/likeadmin
// | github下载：https://github.com/likeshop-github/likeadmin
// | 访问官网：https://www.likeadmin.cn
// | likeadmin团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------
// | author: likeadminTeam
// +----------------------------------------------------------------------

namespace app\adminapi\lists;


use app\adminapi\lists\BaseAdminDataLists;
use app\common\model\ProjectReport;
use app\common\lists\ListsSearchInterface;


/**
 * ProjectReport列表
 * Class ProjectReportLists
 * @package app\adminapi\lists
 */
class ProjectReportLists extends BaseAdminDataLists implements ListsSearchInterface
{


    /**
     * @notes 设置搜索条件
     * @return \string[][]
     * @author likeadmin
     * @date 2025/02/18 18:01
     */
    public function setSearch(): array
    {
        return [
            '=' => ['admin_id', 'user_id', 'project_tasks_id', 'date', 'mianshi_num', 'ruzhi_num', 'daidaogang_num', 'daogang_num', 'liushi_num', 'lizhi_num', 'company_amount', 'jiesuan_amount', 'status'],
        ];
    }


    /**
     * @notes 获取列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author likeadmin
     * @date 2025/02/18 18:01
     */
    public function lists(): array
    {
        return ProjectReport::where($this->searchWhere)
            ->field(['id', 'admin_id', 'user_id', 'project_tasks_id', 'date', 'mianshi_num', 'ruzhi_num', 'daidaogang_num', 'daogang_num', 'liushi_num', 'lizhi_num', 'company_amount', 'jiesuan_amount', 'status'])
            ->limit($this->limitOffset, $this->limitLength)
            ->order(['id' => 'desc'])
            ->select()
            ->toArray();
    }


    /**
     * @notes 获取数量
     * @return int
     * @author likeadmin
     * @date 2025/02/18 18:01
     */
    public function count(): int
    {
        return ProjectReport::where($this->searchWhere)->count();
    }

}
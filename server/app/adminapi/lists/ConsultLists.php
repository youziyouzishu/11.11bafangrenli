<?php
// +----------------------------------------------------------------------
// | 快速开发前后端分离管理后台（PHP版）
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 开源版本可自由商用，可去除界面版权logo
// | gitee下载：
// | github下载：

// | imadmin团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------
// | author: imadmin
// +----------------------------------------------------------------------

namespace app\adminapi\lists;


use app\adminapi\lists\BaseAdminDataLists;
use app\common\model\Consult;
use app\common\lists\ListsSearchInterface;


/**
 * Consult列表
 * Class ConsultLists
 * @package app\adminapi\lists
 */
class ConsultLists extends BaseAdminDataLists implements ListsSearchInterface
{


    /**
     * @notes 设置搜索条件
     * @return \string[][]
     * @author ideaadmin
     * @date 2025/01/02 14:50
     */
    public function setSearch(): array
    {
        return [
            '=' => ['name'],
        ];
    }


    /**
     * @notes 获取列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ideaadmin
     * @date 2025/01/02 14:50
     */
    public function lists(): array
    {
        return Consult::where($this->searchWhere)
            ->field(['id', 'name', 'num', 'times', 'price'])
            ->limit($this->limitOffset, $this->limitLength)
            ->order(['id' => 'desc'])
            ->select()
            ->toArray();
    }


    /**
     * @notes 获取数量
     * @return int
     * @author ideaadmin
     * @date 2025/01/02 14:50
     */
    public function count(): int
    {
        return Consult::where($this->searchWhere)->count();
    }

}
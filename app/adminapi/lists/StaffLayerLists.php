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
use app\common\model\recharge\RechargeOrder;
use app\common\model\StaffLayer;
use app\common\lists\ListsSearchInterface;


/**
 * StaffLayer列表
 * Class StaffLayerLists
 * @package app\adminapi\lists
 */
class StaffLayerLists extends BaseAdminDataLists implements ListsSearchInterface
{


    /**
     * @notes 设置搜索条件
     * @return \string[][]
     * @author ideaadmin
     * @date 2024/12/17 09:27
     */
    public function setSearch(): array
    {
        return [
            '=' => ['staff_id', 'admin_id'],
        ];
    }



    /**
     * @notes 获取列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ideaadmin
     * @date 2024/12/17 09:27
     */
    public function lists(): array
    {
        return StaffLayer::where($this->searchWhere)
            ->with(['admin'])
            ->filter(function ($item){
                $item->consume_amount = RechargeOrder::where(['user_id'=>$item->admin_id,'type'=>'ORG','pay_status'=>1,'refund_status'=>0])->sum('order_amount');
            })
            ->where(['staff_id'=>$this->params['staff_id']])
            ->limit($this->limitOffset, $this->limitLength)
            ->order(['id' => 'desc'])
            ->select()
            ->toArray();
    }


    /**
     * @notes 获取数量
     * @return int
     * @author ideaadmin
     * @date 2024/12/17 09:27
     */
    public function count(): int
    {
        return StaffLayer::where($this->searchWhere)->where(['staff_id'=>$this->params['staff_id']])->count();
    }

}
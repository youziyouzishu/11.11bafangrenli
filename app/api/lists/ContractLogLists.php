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

namespace app\api\lists;

use app\common\enum\user\AccountLogEnum;
use app\common\model\EContract;

/**
 * 账户流水列表
 * Class AccountLogLists
 * @package app\shopapi\lists
 */
class ContractLogLists extends BaseApiDataLists
{

    /**
     * @notes 搜索条件
     * @return array
     * @author 张晓科
     * @date 2023/2/24 14:43
     */
    public function queryWhere()
    {
        // 指定用户
        $where[] = ['to_user_id', '=', $this->userId];
        $where[] = ['project_id', '>', 0];
        // // 用户月明细
        // if (isset($this->params['type']) && $this->params['type'] == 'um') {
        //     $where[] = ['change_type', 'in', AccountLogEnum::getUserMoneyChangeType()];
        // }

        // 变动类型
        if ($this->params['action'] !== "") {
            $where[] = ['status', '=', $this->params['action']];
        }

        return $where;
    }


    /**
     * @notes 获取列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author 张晓科
     * @date 2023/2/24 14:43
     */
    public function lists(): array
    {

        $lists = EContract::with(['projectinfo', 'enterpriseVerification'])
            ->where($this->queryWhere())
            ->order('id', 'desc')
            ->limit($this->limitOffset, $this->limitLength)
            ->select()
            ->toArray();

        // foreach ($lists as &$item) {
        //     $item['type_desc'] = AccountLogEnum::getChangeTypeDesc($item['change_type']);
        //     $symbol = $item['action'] == AccountLogEnum::DEC ? '-' : '+';
        //     $item['change_amount_desc'] = $symbol . $item['change_amount'];
        // }

        return $lists;
    }


    /**
     * @notes 获取数量
     * @return int
     * @author 张晓科
     * @date 2023/2/24 14:44
     */
    public function count(): int
    {
        return EContract::where($this->queryWhere())->count();
    }
}

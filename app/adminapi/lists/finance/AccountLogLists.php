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

namespace app\adminapi\lists\finance;

use app\adminapi\lists\BaseAdminDataLists;
use app\common\enum\user\AccountLogEnum;
use app\common\lists\ListsSearchInterface;
use app\common\model\user\UserAccountLog;
use app\common\service\FileService;


/**
 * 账记流水列表
 * Class AccountLogLists
 * @package app\adminapi\lists\finance
 */
class AccountLogLists extends BaseAdminDataLists implements ListsSearchInterface
{

    /**
     * @notes 搜索条件
     * @return array
     * @author 张晓科
     * @date 2023/2/24 15:26
     */
    public function setSearch(): array
    {
        return [
            '=' => ['al.change_type'],
        ];
    }


    /**
     * @notes 搜索条件
     * @author 张晓科
     * @date 2023/2/24 15:26
     */
    public function queryWhere()
    {
        $where = [];
        if (!in_array($this->adminId, $this->adminIds)) {
            $where[] = ['u.id', '=', $this->adminId];
        }
        // 用户余额
        if (isset($this->params['type']) && $this->params['type'] == 'um') {
            $where[] = ['change_type', 'in', AccountLogEnum::getUserMoneyChangeType()];
        }

        if (!empty($this->params['user_info'])) {
            $where[] = ['u.sn|e.org_name|e.psn_mobile', 'like', '%' . $this->params['user_info'] . '%'];
        }

        if (!empty($this->params['start_time'])) {
            $where[] = ['al.create_time', '>=', strtotime($this->params['start_time'])];
        }

        if (!empty($this->params['end_time'])) {
            $where[] = ['al.create_time', '<=', strtotime($this->params['end_time'])];
        }

        return $where;
    }


    /**
     * @notes 获取列表
     * @return array
     * @author 张晓科
     * @date 2023/2/24 15:31
     */
    public function lists(): array
    {
        $field = 'e.org_name as nickname,u.sn,u.avatar,e.psn_mobile as mobile,al.action,al.change_amount,al.left_amount,al.change_type,al.source_sn,al.create_time,al.remark';
        $lists = UserAccountLog::alias('al')
            ->join('admin u', 'u.id = al.user_id')
            ->join('enterprise_verification e', 'e.user_id = al.user_id')
            ->field($field)
            ->where($this->searchWhere)
            ->where($this->queryWhere())
            ->order('al.id', 'desc')
            ->limit($this->limitOffset, $this->limitLength)
            ->select()
            ->toArray();

        foreach ($lists as &$item) {
            $item['avatar'] = FileService::getFileUrl($item['avatar']);
            $item['change_type_desc'] = AccountLogEnum::getChangeTypeDesc($item['change_type']);
            $symbol = $item['action'] == AccountLogEnum::INC ? '+' : '-';
            $item['change_amount'] = $symbol . $item['change_amount'];
        }

        return $lists;
    }


    /**
     * @notes 获取数量
     * @return int
     * @author 张晓科
     * @date 2023/2/24 15:36
     */
    public function count(): int
    {
        return UserAccountLog::alias('al')
            ->join('admin u', 'u.id = al.user_id')
            ->join('enterprise_verification e', 'e.user_id = al.user_id')
            ->where($this->queryWhere())
            ->where($this->searchWhere)
            ->count();
    }
}

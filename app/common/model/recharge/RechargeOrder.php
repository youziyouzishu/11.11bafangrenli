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

namespace app\common\model\recharge;

use app\common\enum\PayEnum;
use app\common\model\BaseModel;
use think\model\concern\SoftDelete;

/**
 * 充值订单模型
 * Class RechargeOrder
 * @package app\common\model
 */
class RechargeOrder extends BaseModel
{
    use SoftDelete;

    protected $deleteTime = 'delete_time';


    /**
     * @notes 支付方式
     * @param $value
     * @return string|string[]
     * @author 张晓科
     * @date 2023/2/23 18:32
     */
    public function getPayWayTextAttr($value)
    {
        $value = $value ? $value : $this->pay_way;
        $list = [
            1=>'云闪付',
            2=>'微信',
            3=>'支付宝',
        ];
        return $list[$value];
    }

    public function getRefundStatusTextAttr($value)
    {
        $value = $value ? $value : $this->refund_status;
        $list = [
            0=>'未退款',
            1=>'已退款',
            3=>'申请退款',
        ];
        return $list[$value];
    }



    /**
     * @notes 支付状态
     * @param $value
     * @return string|string[]
     * @author 张晓科
     * @date 2023/2/23 18:32
     */
    public function getPayStatusTextAttr($value, $data)
    {
        return PayEnum::getPayStatusDesc($data['pay_status']);
    }
}

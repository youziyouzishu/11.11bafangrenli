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

namespace app\adminapi\logic\setting\pay;


use app\common\enum\PayEnum;
use app\common\logic\BaseLogic;
use app\common\model\pay\PayConfig;
use app\common\service\ConfigService;

/**
 * 支付配置
 * Class PayConfigLogic
 * @package app\adminapi\logic\setting\pay
 */
class PayConfigLogic extends BaseLogic
{

    /**
     * @notes 设置配置
     * @param $params
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author 张晓科
     * @date 2023/2/23 16:16
     */
    public static function setConfig($params)
    {
        $payConfig = PayConfig::find($params['id']);

        $config = '';
        if ($payConfig['pay_way'] == PayEnum::WECHAT_PAY) {
            $config = [
                'interface_version' => $params['config']['interface_version'],
                'merchant_type' => $params['config']['merchant_type'],
                'mch_id' => $params['config']['mch_id'],
                'pay_sign_key' => $params['config']['pay_sign_key'],
                'apiclient_cert' => $params['config']['apiclient_cert'],
                'apiclient_key' => $params['config']['apiclient_key'],
            ];
        }
        if ($payConfig['pay_way'] == PayEnum::ALI_PAY) {
            $config = [
                'mode' => $params['config']['mode'],
                'merchant_type' => $params['config']['merchant_type'],
                'app_id' => $params['config']['app_id'],
                'private_key' => $params['config']['private_key'],
                'ali_public_key' => $params['config']['ali_public_key'],
            ];
        }

        $payConfig->name = $params['name'];
        $payConfig->icon = $params['icon'];
        $payConfig->sort = $params['sort'];
        $payConfig->config = $config;
        $payConfig->remark = $params['remark'] ?? '';
        return $payConfig->save();
    }


    /**
     * @notes 获取配置
     * @param $params
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author 张晓科
     * @date 2023/2/23 16:16
     */
    public static function getConfig($params)
    {
        $payConfig = PayConfig::find($params['id'])->toArray();
        $payConfig['domain'] = request()->domain();
        return $payConfig;
    }

    /**
     * @notes 获取ios支付
     * @return array|int|mixed|string
     * @author cjhao
     * @date 2023/7/25 17:10
     */
    public static function getIosPayConfig()
    {
        $iosPay = ConfigService::get('pay_config', 'ios_pay');
        $iosPay['is_open'] = (int)$iosPay['is_open'];
        return $iosPay;
    }


    /**
     * @notes 设置ios支付
     * @author cjhao
     * @date 2023/7/25 17:11
     */
    public static function setIosPayConfig(array $params)
    {
        ConfigService::set('pay_config', 'ios_pay', $params);
    }
}

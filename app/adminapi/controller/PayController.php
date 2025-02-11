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

namespace app\adminapi\controller;


use app\api\validate\PayValidate;
use app\common\enum\user\UserTerminalEnum;
use app\common\logic\PaymentLogic;
use app\common\service\pay\AliPayService;
use app\common\service\pay\EpayService;
use app\adminapi\controller\BaseAdminController;
use app\common\service\pay\WeChatPayService;
use think\facade\Log;

/**
 * 支付
 * Class PayController
 * @package app\api\controller
 */
class PayController extends BaseAdminController
{

    public array $notNeedLogin = ['notifyMnp', 'notifyOa', 'aliNotify', 'notifyApp', 'notifyEpay'];


    /**
     * @notes 获取ios支付配置
     * @return mixed
     * @author cjhao
     * @date 2023/7/26 11:48
     */
    public function iosPayConfig()
    {
        $config = PaymentLogic::iosPayConfig();
        return $this->data($config);
    }
    /**
     * @notes 支付方式
     * @return \think\response\Json
     * @author 张晓科
     * @date 2023/2/24 17:54
     */
    public function payWay()
    {
        $params = (new PayValidate())->post()->goCheck('payway');

        $result = PaymentLogic::getPayWay($this->adminId, 4, $params);
        //return $this->fail(json_encode($result));
        if ($result === false) {
            return $this->fail(PaymentLogic::getError());
        }
        return $this->data($result);
    }


    /**
     * @notes 预支付
     * @return \think\response\Json
     * @author 张晓科
     * @date 2023/2/28 14:21
     */
    public function prepay()
    {
        $params = (new PayValidate())->post()->goCheck();
        //订单信息
        $order = PaymentLogic::getPayOrderInfo($params, 4);
        if (false === $order) {
            return $this->fail(PaymentLogic::getError(), $params);
        }

        //支付流程
        $redirectUrl = $params['redirect'] ?? '/pages/payment/payment';
        $device = $params['device'] ?? 'pc';
        $result = PaymentLogic::pay($params['pay_way'], $params['from'], $order, 4, $redirectUrl, $device);
        if (false === $result) {
            return $this->fail(PaymentLogic::getError(), $params);
        }
        return $this->success('', $result);
    }




    /**
     * @notes 获取支付状态
     * @return \think\response\Json
     * @author 张晓科
     * @date 2023/3/1 16:23
     */
    public function payStatus()
    {
        $params = (new PayValidate())->goCheck('status', ['user_id' => $this->userId]);
        $result = PaymentLogic::getPayStatus($params);
        if ($result === false) {
            return $this->fail(PaymentLogic::getError());
        }
        return $this->data($result);
    }


    /**
     * @notes 小程序支付回调
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     * @throws \ReflectionException
     * @throws \Throwable
     * @author 张晓科
     * @date 2023/2/28 14:21
     */
    public function notifyMnp()
    {
        return (new WeChatPayService(UserTerminalEnum::WECHAT_MMP))->notify();
    }


    /**
     * @notes 公众号支付回调
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     * @throws \ReflectionException
     * @throws \Throwable
     * @author 张晓科
     * @date 2023/2/28 14:21
     */
    public function notifyOa()
    {
        return (new WeChatPayService(UserTerminalEnum::WECHAT_OA))->notify();
    }

    /**
     * @notes 支付宝回调
     * @return bool
     * @author 张晓科
     * @date 2021/8/13 14:16
     */
    public function aliNotify()
    {
        $params = $this->request->post();
        $result = (new AliPayService())->notify($params);
        if (true === $result) {
            echo 'success';
        } else {
            echo 'fail';
        }
    }



    /**
     * @notes app支付回调
     * @author 张晓科
     * @date 2021/8/13 14:16
     */
    public function notifyApp()
    {
        return (new WeChatPayService(UserTerminalEnum::IOS))->notify();
    }
}

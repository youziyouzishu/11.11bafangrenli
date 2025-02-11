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

namespace app\api\controller;


use app\adminapi\service\AccountBookCreate;
use app\api\validate\PayValidate;
use app\common\enum\PayEnum;
use app\common\enum\user\AccountLogEnum;
use app\common\enum\user\UserTerminalEnum;
use app\common\http\esign\comm\EsignLogHelper;
use app\common\logic\AccountLogLogic;
use app\common\logic\PaymentLogic;
use app\common\model\auth\Admin;
use app\common\model\ConsultOrders;
use app\common\model\recharge\RechargeOrder;
use app\common\model\user\User;
use app\common\service\pay\AliPayService;
use app\common\service\pay\WeChatPayService;
use app\Request;
use EasyWeChat\MiniApp\Application;
use think\facade\Db;
use think\facade\Log;
use Yansongda\Pay\Pay;

/**
 * 支付
 * Class PayController
 * @package app\api\controller
 */
class PayController extends BaseApiController
{

    public array $notNeedLogin = ['notifyMnp', 'notifyOa', 'aliNotify', 'notifyApp', 'notifyEpay','alipay', 'wechat', 'balance'];


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
        $params = (new PayValidate())->goCheck('payway');
        $result = PaymentLogic::getPayWay($this->userId, $this->userInfo['terminal'], $params);
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
        $order = PaymentLogic::getPayOrderInfo($params, $this->userInfo['terminal']);
        if (false === $order) {
            return $this->fail(PaymentLogic::getError(), $params);
        }
        //支付流程
        $redirectUrl = $params['redirect'] ?? '/pages/payment/payment';
        $device = $params['device'] ?? 'pc';
        $result = PaymentLogic::pay($params['pay_way'], $params['from'], $order, $this->userInfo['terminal'], $redirectUrl, $device);
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


    function alipay(Request $request)
    {
        $request->paytype = 'alipay';
        try {
            $this->pay($request);
        } catch (\Throwable $e) {
            return $this->fail($e->getMessage());
        }
        return response('success');
    }

    function wechat(Request $request)
    {
        $request->paytype = 'wechat';
        try {
            $this->pay($request);
        } catch (\Throwable $e) {
            return json(['code' => 'FAIL', 'message' => $e->getMessage()]);
        }
        return json(['code' => 'SUCCESS', 'message' => '成功']);
    }

    function balance(Request $request)
    {
        $request->paytype = 'balance';
        try {
            $this->pay($request);
        } catch (\Throwable $e) {
            return $this->fail($e->getMessage());
        }
        return $this->success();
    }

    /**
     * 接受回调
     * @throws \Throwable
     */
    private function pay(Request $request)
    {
        Db::startTrans();
        try {
            $paytype = $request->paytype;
            $config = config('payment');
            switch ($paytype) {
                case 'wechat':
                    $pay = Pay::wechat($config);
                    $res = $pay->callback();
                    $res = $res->resource;
                    $res = $res['ciphertext'];
                    $out_trade_no = $res['out_trade_no'];
                    $attach = $res['attach'];
                    $mchid = $res['mchid'];
                    $transaction_id = $res['transaction_id'];
                    $openid = $res['payer']['openid'] ?? '';
                    break;
                case 'alipay':
                    $pay = Pay::alipay($config);
                    $res = $pay->callback();
                    EsignLogHelper::writeLog(json_encode($res), 'alipay');
                    if (isset($res->external_agreement_no) && $res->status == 'NORMAL'){
                        $admin = Admin::where(['id'=>$res->external_agreement_no])->find();
                        if ($admin){
                            Pay::config(config('payment'));
                            $params = array();
                            // 设置个人签约产品码
                            $params['merchant_user_id'] = $admin->id;
                            $params['merchant_user_type'] = 'BUSINESS_ORGANIZATION';
                            $params['scene_code'] = 'SATF_FUND_BOOK';
                            $params['ext_info'] = ['agreement_no'=>$res->agreement_no];
                            $allPlugins = Pay::alipay()->mergeCommonPlugins([AccountBookCreate::class]);
                            $result = Pay::alipay()->pay($allPlugins, $params);
                            $result = $result->get();
                            if ($result['code'] == '10000'){
                                $admin->agreement_no = $res->agreement_no;
                                $admin->account_book_id = $result['account_book_id'];;
                                $admin->save();
                            }
                        }
                        $attach = 'thanks';
                    }else{
                        $out_trade_no = $res->out_trade_no;
                        $attach = $res->passback_params;
                        $transaction_id = $res->trade_no;
                    }

                    break;
                case 'balance':
                    $out_trade_no = $request->param('out_trade_no');
                    $attach = $request->param('attach');
                    break;
                default:
                    throw new \Exception('支付类型错误');
            }

            switch ($attach) {
                case 'business_recharge':
                    $order = RechargeOrder::where(['sn' => $out_trade_no, 'pay_status' => 0])->find();
                    if (!$order) {
                        throw new \Exception('订单不存在');
                    }
                    #增加余额
                    // 增加用户累计充值金额及用户余额
                    $user = Admin::findOrEmpty($order->user_id);
                    $user->total_recharge_amount += $order->order_amount;
                    $user->user_money += $order->order_amount;
                    $user->save();

                    // 记录账户流水
                    AccountLogLogic::add(
                        $order->user_id,
                        AccountLogEnum::UM_INC_RECHARGE,
                        AccountLogEnum::INC,
                        $order->order_amount,
                        $order->sn,
                        '人力公司充值'
                    );

                    // 更新充值订单状态
                    $order->transaction_id = $transaction_id;
                    $order->pay_status = PayEnum::ISPAID;
                    $order->pay_time = time();
                    $order->save();
                    break;
                case 'consult':
                    $order = ConsultOrders::where(['ordersn' => $out_trade_no, 'status' => 0])->find();
                    if (!$order) {
                        throw new \Exception('订单不存在');
                    }
                    $order->status = 1;
                    $order->pay_time = date('Y-m-d H:i:s');
                    $order->num = $order->total;
                    $order->save();
                    $order->admin->consult_times += $order->total;
                    $order->admin->save();
                    break;
                case 'thanks':
                    break;
                default:
                    throw new \Exception('回调错误');
            }
            Db::commit();
        } catch (\Throwable $e) {
            Db::rollback();
            throw new \Exception($e->getMessage());
        }
    }



}

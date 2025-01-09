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

use app\api\lists\recharge\RechargeLists;
use app\api\logic\RechargeLogic;
use app\api\service\PayService;
use app\api\validate\RechargeValidate;
use app\adminapi\controller\BaseAdminController;
use app\common\enum\PayEnum;
use app\common\model\recharge\RechargeOrder;
use app\Request;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;

/**
 * 充值控制器
 * Class RechargeController
 * @package app\shopapi\controller
 */
class RechargeController extends BaseAdminController
{

    /**
     * @notes 获取充值列表
     * @return \think\response\Json
     * @author 张晓科
     * @date 2023/2/23 18:55
     */
    public function lists()
    {
        return $this->dataLists(new RechargeLists());
    }


    /**
     * @notes 充值
     * @return \think\response\Json
     * @author 张晓科
     * @date 2023/2/23 18:56
     */
    public function recharge()
    {
        $params = (new RechargeValidate())->post()->goCheck('recharge', [
            'user_id' => $this->adminId,
            'terminal' => $this->adminInfo['terminal'],
            'type' => 'ORG',
        ]);
        $result = RechargeLogic::recharge($params);;
        //return $this->fail(json_encode($result));
        if (false === $result) {
            return $this->fail(RechargeLogic::getError());
        }
        return $this->data($result);
    }


    function payRecharge(Request $request)
    {
        $amount = $request->param('amount');
        $pay_way = $request->param('pay_way');#支付方式:1=云闪付,2=微信,3=支付宝
        $ordersn = generate_sn(RechargeOrder::class, 'sn');

        try {
            $data = [
                'sn' => $ordersn,
                'user_id' => $this->adminId,
                'order_amount' => $amount,
                'type' => 'ORG',
            ];
            RechargeOrder::create($data);
            $result = PayService::pay($pay_way, $amount,$ordersn,'余额充值','business_recharge','pc');

            // 使用构建器创建 QR Code
            $writer = new PngWriter();
            $qrCode = new QrCode(
                data: $result->code_url,
                encoding: new Encoding('UTF-8'),
                errorCorrectionLevel: ErrorCorrectionLevel::Low,
                size: 100,
                margin: 10,
                roundBlockSizeMode: RoundBlockSizeMode::Margin,
                foregroundColor: new Color(0, 0, 0),
                backgroundColor: new Color(255, 255, 255)
            );
            $result = $writer->write($qrCode)->getDataUri();

        }catch (\Throwable $e){
            return $this->fail($e->getMessage());
        }
        return  $this->data(['base64'=>$result]);
    }







    /**
     * @notes 充值配置
     * @return \think\response\Json
     * @author 张晓科
     * @date 2023/2/24 16:56
     */
    public function config()
    {
        return $this->data(RechargeLogic::config($this->adminId));
    }
}

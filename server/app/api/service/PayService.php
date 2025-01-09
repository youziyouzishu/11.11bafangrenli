<?php

namespace app\api\service;

use Exception;

class PayService
{
    /**
     * 支付
     * @param $pay_type *支付类型:1=微信,2=支付宝
     * @param  $pay_amount
     * @param  $order_no
     * @param $mark
     * @param $attach
     */
    public static function pay($pay_type,$pay_amount,$order_no,$mark,$attach,$client)
    {
        $config = config('payment');
        switch ($pay_type){
            case 2:
                if ($client == 'app'){
                    $result =  \Yansongda\Pay\Pay::wechat($config)->app([
                        'out_trade_no' => $order_no,
                        'description' => $mark,
                        'amount' => [
                            'total' => (int)bcmul($pay_amount, 100, 2),
                            'currency' => 'CNY',
                        ],
                        'attach' => $attach
                    ]);
                }elseif ($client == 'pc'){

                    $result =  \Yansongda\Pay\Pay::wechat($config)->scan([
                        'out_trade_no' => $order_no,
                        'description' => $mark,
                        'amount' => [
                            'total' => (int)bcmul($pay_amount, 100, 2),
                            'currency' => 'CNY',
                        ],
                        'attach' => $attach
                    ]);
                }else{
                    throw new Exception('客户端来源错误');
                }
                break;
            case 3:
                if ($client == 'app'){
                    $result =  \Yansongda\Pay\Pay::alipay($config)->app([
                        'out_trade_no' => $order_no,
                        'total_amount' => $pay_amount,
                        'subject' => $mark,
                        'passback_params' => urlencode($attach)
                    ])->getBody()->getContents();
                }elseif ($client == 'pc'){
                    $result =  \Yansongda\Pay\Pay::alipay($config)->scan([
                        'out_trade_no' => $order_no,
                        'total_amount' => $pay_amount,
                        'subject' => $mark,
                        'passback_params' => urlencode($attach)
                    ]);
                }else{
                    throw new Exception('客户端来源错误');
                }
                break;
            default:
                throw new Exception('支付类型错误');
        }

        return $result;
    }
}
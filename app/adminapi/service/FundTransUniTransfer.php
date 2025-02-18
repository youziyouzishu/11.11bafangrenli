<?php

namespace app\adminapi\service;

use Yansongda\Artful\Contract\PluginInterface;
use Yansongda\Artful\Logger;
use Yansongda\Artful\Rocket;
use Closure;
class FundTransUniTransfer implements PluginInterface
{


    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[Alipay][Fund][Trans][Uni][Transfer] 插件开始装载', ['rocket' => $rocket]);

        $rocket->mergePayload([
            'method' => 'alipay.fund.trans.uni.transfer',
            'biz_content' => $rocket->getParams(),
        ]);

        Logger::info('[Alipay][Fund][Trans][Uni][Transfer] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }

}
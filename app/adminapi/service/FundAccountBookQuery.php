<?php

namespace app\adminapi\service;

use Yansongda\Artful\Contract\PluginInterface;
use Yansongda\Artful\Direction\ResponseDirection;
use Yansongda\Artful\Logger;
use Yansongda\Artful\Rocket;
use Closure;
class FundAccountBookQuery implements PluginInterface
{

    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[Alipay][Fund][Account][Book][Query] 插件开始装载', ['rocket' => $rocket]);


        $rocket->mergePayload([
            'method' => 'alipay.fund.accountbook.query',
            'biz_content' => $rocket->getParams(),
        ]);

        Logger::info('[Alipay][Fund][Account][Book][Query] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }

}
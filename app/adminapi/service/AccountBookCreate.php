<?php

namespace app\adminapi\service;

use Yansongda\Artful\Contract\PluginInterface;
use Yansongda\Artful\Logger;
use Yansongda\Artful\Rocket;
use Closure;
class AccountBookCreate implements PluginInterface
{


    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[Alipay][Fund][Agreement][Accountbook][Create] 插件开始装载', ['rocket' => $rocket]);

        $rocket->mergePayload([
            'method' => 'alipay.fund.accountbook.create',
            'biz_content' => $rocket->getParams(),
        ]);

        Logger::info('[Alipay][Fund][Agreement][Accountbook][Create] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }

}
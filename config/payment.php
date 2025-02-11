<?php

use Yansongda\Pay\Pay;

return [
    'alipay' => [
        'default' => [
            // 「必填」支付宝分配的 app_id
            'app_id' => '2021005112680993',
            // 「必填」应用私钥 字符串或路径
            // 在 https://open.alipay.com/develop/manage 《应用详情->开发设置->接口加签方式》中设置
            'app_secret_cert' => 'MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDP7p61LaE1H2Fi3c/m+PiitRZ9dfEWDhb1XQmdyKLiU9vENKVTRjS41fNqe7yZz2QhxFudpNnwqwcjp9MuDYtnsv/PbbjBcGL+3bCN6nxgVbnblnmDpe8bIWwSQjaSXMDYyR9tJwHr9h1Jxc4vwJgE5R29jMLmrFmbP0f+M7UZVWOpjvcBA7QfwPS7p7ShLPXPWw4eDs9NTH16/nEIkjj4JFdDj1Fr6uU7dA0p4tp31bRVQ0+hii6bbK0DjgKg/HQTRegw5UBkcLAm8GhTmZfJlMMuw+SjKyLvK4zmjjT+9/rsd9S2nvN4PeGZLoQwrbxpOXO7Xq/TNFGW+SzVR6t7AgMBAAECggEAGZKDOk0OUnY1h+83rnRYP8p9pZhnugqpkCuNDzapsaQWlx7SZldwDHtjoGjvXQtAiVaY3d50X0Dpc90j/7nytyIwi9Y2rrOfuTCSkiGQgliIrxTmhOZXbcLCg8xY4c8+eGrjzozJk7eFCpmlLgJFr/Rtn+472hrAOnOh6wV49FBvXVQKGlUrRawfBUU3n1ord+qO/MqkpqsDmqsGW4u+Ei36o6/foA62PzIkty3yA52tRtQiE15HMQDXl3AuAg70F3DPrPKsVo3vfqSS5558sXGNvWR4YMOwfOdEma7BDexQ4cEW2+E8WdxTTFpC4TvdBOQ+sJNnXZ2u3vbgXn9YgQKBgQDnR5ykAW+9PVN4WIIkKLUPdqS8gQwTZ0dT/iTmGGYxf/MfQqiUxSbIGryyd0OcrmqDV/lA0hvg1MCh/91xGgiUxNdRXYZp+GUdu80iTBvpCVRnvhWR6SHJseR6aRJgHRk+sXSZwRJPB4y1Dl7I5UdIrscI7GMFUwrtGkqlYFQZMQKBgQDmKCi37OzYrx6x1HcZqdUwmlTICrpFcu0T9pq/tUhPMKnCr6NsfKOkwOkIVES+8bPVf9lWm5WbbLi6layVhYZKE7Ug3rrn6mKIvpsHpJqjsziiHKHxfxWh5DmvPHTBzcDKRFn76bsiX2RmlMxz6PqEeXxXFxRQpVpq0Z7sampkawKBgE+eHnVzLSVHVFHMnliWTuZY4JMoFaEkX7Cxf8BaS+vkcSykxOYaDKS8V5NUbUkl3fMDOQIwknpOo1XhgDjsTmHwdXNEL/6RDTY0LYUOEFmj5ZOlI0XMrN4DSHU/cJz3x3YGnu5DQetE3RKDNOwRyf1idZBD2D6//LXs5B+UTr8xAoGBANOroW8uPdE2lUM1ROfcUs5lUY28l/1hKfrPNRkU71CtT6NWhzUyGApgxLC9V5BImHzFHSxS4K/VuLsZBmVpLM0Z/N4jGcBKxp4+tjO8ReBB5tbpAGMq3slKLWclcbf/s8wSOrO9DTcahir1tGbxoNyPIjSybCOTP2m4KbICWKDLAoGARM/4XmFu92DPIpzBXroMEOjus46W120MXfZsOoewCeoJO7dhQqGUxhgRZG9ZRnytrJdul0ovlMZYZqon9fUN6g8UcrG6vOedjlOCyQfPaeYUI6rlMAxTqVCVUrn0NfsO3qL8erQLHAVzsUvMm/q3wyVtZrvjKj6UPRDkzb1b7FA=',
            // 「必填」应用公钥证书 路径
            // 设置应用私钥后，即可下载得到以下3个证书
            'app_public_cert_path' => __DIR__.'/cert/appCertPublicKey_2021005112680993.crt',
            // 「必填」支付宝公钥证书 路径
            'alipay_public_cert_path' => __DIR__.'/cert/alipayCertPublicKey_RSA2.crt',
            // 「必填」支付宝根证书 路径
            'alipay_root_cert_path' => __DIR__.'/cert/alipayRootCert.crt',
            'return_url' => 'https://bf.sf0000.com.cn/business/finance/recharge_record',
            'notify_url' => 'https://bf.sf0000.com.cn/api/pay/alipay',
            // 「选填」第三方应用授权token
            'app_auth_token' => '',
            // 「选填」服务商模式下的服务商 id，当 mode 为 Pay::MODE_SERVICE 时使用该参数
            'service_provider_id' => '',
            // 「选填」默认为正常模式。可选为： MODE_NORMAL, MODE_SANDBOX, MODE_SERVICE
            'mode' => Pay::MODE_NORMAL,
        ]
    ],
    'wechat' => [
        'default' => [
            // 「必填」商户号，服务商模式下为服务商商户号
            // 可在 https://pay.weixin.qq.com/ 账户中心->商户信息 查看
            'mch_id' => '1679908545',
            // 「选填」v2商户私钥
            'mch_secret_key_v2' => '',
            // 「必填」v3 商户秘钥
            // 即 API v3 密钥(32字节，形如md5值)，可在 账户中心->API安全 中设置
            'mch_secret_key' => '1mT8taQHUoatL2k1WkevdG8oioEjEIij',
            // 「必填」商户私钥 字符串或路径
            // 即 API证书 PRIVATE KEY，可在 账户中心->API安全->申请API证书 里获得
            // 文件名形如：apiclient_key.pem
            'mch_secret_cert' =>  __DIR__.'/cert/apiclient_key.pem',
            // 「必填」商户公钥证书路径
            // 即 API证书 CERTIFICATE，可在 账户中心->API安全->申请API证书 里获得
            // 文件名形如：apiclient_cert.pem
            'mch_public_cert_path' => __DIR__.'/cert/apiclient_cert.pem',
            // 「必填」微信回调url
            // 不能有参数，如?号，空格等，否则会无法正确回调
            'notify_url' => 'https://bf.sf0000.com.cn/api/pay/wechat',
            // 「选填」公众号 的 app_id
            // 可在 mp.weixin.qq.com 设置与开发->基本配置->开发者ID(AppID) 查看
            'mp_app_id' => 'wxbd2893accf9988ab',
            // 「选填」小程序 的 app_id
            'mini_app_id' => '',
            // 「选填」app 的 app_id
            'app_id' => 'wxbd2893accf9988ab',
            // 「选填」服务商模式下，子公众号 的 app_id
            'sub_mp_app_id' => '',
            // 「选填」服务商模式下，子 app 的 app_id
            'sub_app_id' => '',
            // 「选填」服务商模式下，子小程序 的 app_id
            'sub_mini_app_id' => '',
            // 「选填」服务商模式下，子商户id
            'sub_mch_id' => '',
            // 「选填」（适用于 2024-11 及之前开通微信支付的老商户）微信平台公钥证书路径，强烈建议 php-fpm 模式下配置此参数
            // 「必填」微信支付公钥路径，key 填写形如 PUB_KEY_ID_0000000000000024101100397200000006 的公钥id，见 https://pay.weixin.qq.com/doc/v3/merchant/4013053249
            'wechat_public_cert_path' => [
                '45F59D4DABF31918AFCEC556D5D2C6E376675D57' => __DIR__.'/cert/wechatPublicKey.crt',
                'PUB_KEY_ID_0000000000000024101100397200000006' => __DIR__.'/cert/publickey.pem',
            ],
            // 「选填」默认为正常模式。可选为： MODE_NORMAL, MODE_SERVICE
            'mode' => Pay::MODE_NORMAL,
        ]
    ],
    'unipay' => [
        'default' => [
            // 「必填」商户号
            'mch_id' => '777290058167151',
            // 「选填」商户密钥：为银联条码支付综合前置平台配置：https://up.95516.com/open/openapi?code=unionpay
            'mch_secret_key' => '979da4cfccbae7923641daa5dd7047c2',
            // 「必填」商户公私钥
            'mch_cert_path' => __DIR__.'/Cert/unipayAppCert.pfx',
            // 「必填」商户公私钥密码
            'mch_cert_password' => '000000',
            // 「必填」银联公钥证书路径
            'unipay_public_cert_path' => __DIR__.'/Cert/unipayCertPublicKey.cer',
            // 「必填」
            'return_url' => 'https://yansongda.cn/unipay/return',
            // 「必填」
            'notify_url' => 'https://yansongda.cn/unipay/notify',
            'mode' => Pay::MODE_NORMAL,
        ],
    ],
    'douyin' => [
        'default' => [
            // 「选填」商户号
            // 抖音开放平台 --> 应用详情 --> 支付信息 --> 产品管理 --> 商户号
            'mch_id' => '73744242495132490630',
            // 「必填」支付 Token，用于支付回调签名
            // 抖音开放平台 --> 应用详情 --> 支付信息 --> 支付设置 --> Token(令牌)
            'mch_secret_token' => 'douyin_mini_token',
            // 「必填」支付 SALT，用于支付签名
            // 抖音开放平台 --> 应用详情 --> 支付信息 --> 支付设置 --> SALT
            'mch_secret_salt' => 'oDxWDBr4U7FAAQ8hnGDm29i4A6pbTMDKme4WLLvA',
            // 「必填」小程序 app_id
            // 抖音开放平台 --> 应用详情 --> 支付信息 --> 支付设置 --> 小程序appid
            'mini_app_id' => 'tt226e54d3bd581bf801',
            // 「选填」抖音开放平台服务商id
            'thirdparty_id' => '',
            // 「选填」抖音支付回调地址
            'notify_url' => 'https://yansongda.cn/douyin/notify',
        ],
    ],
    'jsb' => [
        'default' => [
            // 服务代码
            'svr_code' => '',
            // 「必填」合作商ID
            'partner_id' => '',
            // 「必填」公私钥对编号
            'public_key_code' => '00',
            // 「必填」商户私钥(加密签名)
            'mch_secret_cert_path' => '',
            // 「必填」商户公钥证书路径(提供江苏银行进行验证签名用)
            'mch_public_cert_path' => '',
            // 「必填」江苏银行的公钥(用于解密江苏银行返回的数据)
            'jsb_public_cert_path' => '',
            // 支付通知地址
            'notify_url' => '',
            // 「选填」默认为正常模式。可选为： MODE_NORMAL:正式环境, MODE_SANDBOX:测试环境
            'mode' => Pay::MODE_NORMAL,
        ],
    ],
    'logger' => [
        'enable' => true,
        'file' => '/pay.log',
        'level' => 'info', // 建议生产环境等级调整为 info，开发环境为 debug
        'type' => 'single', // optional, 可选 daily.
        'max_file' => 30, // optional, 当 type 为 daily 时有效，默认 30 天
    ],
    'http' => [ // optional
        'timeout' => 5.0,
        'connect_timeout' => 5.0,
        // 更多配置项请参考 [Guzzle](https://guzzle-cn.readthedocs.io/zh_CN/latest/request-options.html)
    ],
];
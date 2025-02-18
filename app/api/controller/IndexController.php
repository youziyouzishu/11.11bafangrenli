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
use app\adminapi\service\Alipay;
use app\adminapi\service\FundAccountBookQuery;
use app\adminapi\service\FundTransPagePay;
use app\api\logic\IndexLogic;
use app\adminapi\logic\ConfigLogic;
use app\api\service\PayService;
use app\common\model\ProjectReport;
use app\common\model\ProjectTasks;
use app\common\model\ProjectTasksAudit;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Random;
use think\response\Json;
use app\common\model\user\User;
use app\common\cache\UserTokenCache;
use app\common\http\esign\comm\EsignLogHelper;
use Yansongda\Pay\Pay;
use Yansongda\Pay\Plugin\Alipay\V2\Marketing\Redpack\WebPayPlugin;
use Yansongda\Pay\Plugin\Alipay\V2\Pay\Agreement\Sign\QueryPlugin;
use Yansongda\Pay\Plugin\Alipay\V2\Pay\Agreement\Sign\SignPlugin;
use Yansongda\Pay\Plugin\Alipay\V2\ResponseHtmlPlugin;

/**
 * index
 * Class IndexController
 * @package app\api\controller
 */
class IndexController extends BaseApiController
{


    public array $notNeedLogin = ['index', 'config', 'policy', 'decorate', 'dict', 'log','test','aaa','create','pay','query','ccccc'];


    function query()
    {
        Pay::config(config('payment'));
        // 设置个人签约产品码
        $params['account_book_id'] = '2088860043164244';
        $params['scene_code'] = 'SATF_FUND_BOOK';
        $params['ext_info'] = json_encode(['agreement_no'=>'20255011166799197005']);

        $allPlugins = Pay::alipay()->mergeCommonPlugins([FundAccountBookQuery::class]);
        $result = Pay::alipay()->pay($allPlugins, $params);
        $result = $result->get();
        dump($result);
        if ($result['code'] == '10000'){
            //成功
            dump($result['available_amount']);
            dump($result['msg']);
        }else {
            dump($result['msg']);
        }


    }

    function create()
    {
        Pay::config(config('payment'));
        $params = array();
        // 设置个人签约产品码
        $params['merchant_user_id'] = 1;
        $params['merchant_user_type'] = 'BUSINESS_ORGANIZATION';
        $params['scene_code'] = 'SATF_FUND_BOOK';
        $params['ext_info'] = ['agreement_no'=>'20255010166843568005'];
        $allPlugins = Pay::alipay()->mergeCommonPlugins([AccountBookCreate::class]);
        $result = Pay::alipay()->pay($allPlugins, $params);
        $result = $result->get();
        dump($result);
        if ($result['code'] == '10000'){
            //成功
            dump($result['msg']);
        }else {
            dump($result['msg']);
        }
    }
    function aaa()
    {
        Pay::config(config('payment'));
        $params = array();
        // 设置个人签约产品码
        $params['personal_product_code'] = 'FUND_SAFT_SIGN_WITHHOLDING_P';
        $params['sign_scene'] = 'INDUSTRY|SATF_ACC';
        $params['product_code'] = 'FUND_SAFT_SIGN_WITHHOLDING';
        $params['external_agreement_no'] = 1;
        $allPlugins = Pay::alipay()->mergeCommonPlugins([QueryPlugin::class]);
        $result = Pay::alipay()->pay($allPlugins, $params);
        $result = $result->get();
        dump($result);
        if ($result['code'] == '10000'){
            //成功
            dump($result['msg']);
        }else {
            dump($result['msg']);
        }


    }

    function pay()
    {

        Pay::config(config('payment'));
        $params['_method'] = 'GET';
        $params['out_biz_no'] = date('Ymd') . mb_strtoupper(uniqid());
        $params['trans_amount'] = 0.01;
        $params['product_code'] = 'FUND_ACCOUNT_BOOK';
        $params['biz_scene'] = 'SATF_DEPOSIT';
        $params['time_expire'] = date('Y-m-d H:i', time() + 3600);
        $params['payee_info'] = ['identity_type'=>'ACCOUNT_BOOK_ID','identity'=>'2088860043164244','ext_info'=>json_encode(['agreement_no'=>'20255011166799197005'])];

        $allPlugins = Pay::alipay()->mergeCommonPlugins([FundTransPagePay::class, ResponseHtmlPlugin::class]);
        $result = Pay::alipay()->pay($allPlugins, $params);
        $url = $result->getHeader('Location')[0];

        return redirect($url);

    }
    function ccccc()
    {


//
//        Pay::config(config('payment'));
//        $params = array();
//        // 设置个人签约产品码
//        $params['personal_product_code'] = 'FUND_SAFT_SIGN_WITHHOLDING_P';
//        $params['sign_scene'] = 'INDUSTRY|SATF_ACC';
//        $params['access_params'] = ['channel'=>'QRCODE'];
//        $params['product_code'] = 'FUND_SAFT_SIGN_WITHHOLDING';
//        $params['external_agreement_no'] = 1;
//        $params['third_party_type'] = 'PARTNER';
//        $params['_method'] = 'GET';
//        $allPlugins = Pay::alipay()->mergeCommonPlugins([SignPlugin::class, ResponseHtmlPlugin::class]);
//        $result = Pay::alipay()->pay($allPlugins, $params);
//        $url = $result->getHeader('Location')[0];
//
//        return redirect($url);
    }

    /**
     * @notes 首页数据
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author 张晓科
     * @date 2023/9/21 19:15
     */
    public function index()
    {
        $result = IndexLogic::getIndexData();
        return $this->data($result);
    }

    /**
     * @notes 根据类型获取字典数据
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author 张晓科
     * @date 2023/9/27 19:10
     */
    public function dict()
    {
        $type = $this->request->get('type', '');
        $data = ConfigLogic::getDictByType($type);
        return $this->data($data);
    }

    /**
     * @notes 全局配置
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author 张晓科
     * @date 2023/9/21 19:41
     */
    public function config()
    {
        $result = IndexLogic::getConfigData(!empty($this->userInfo["role"]) ? $this->userInfo["role"] : 2);
        return $this->data($result);
    }


    /**
     * @notes 政策协议
     * @return Json
     * @author 张晓科
     * @date 2023/9/20 20:00
     */
    public function policy()
    {
        $type = $this->request->get('type/s', '');
        $result = IndexLogic::getPolicyByType($type);
        return $this->data($result);
    }


    /**
     * @notes 装修信息
     * @return Json
     * @author 张晓科
     * @date 2023/9/21 18:37
     */
    public function decorate()
    {
        $id = $this->request->get('id/d');
        $result = IndexLogic::getDecorate($id, $this->userInfo['role']);
        return $this->data($result);
    }

    public function log()
    {
        $body = file_get_contents("php://input");
        if (!empty($body)) {
            EsignLogHelper::writeLog(json_encode($body, JSON_UNESCAPED_UNICODE), 'message.log');
        }
    }
}

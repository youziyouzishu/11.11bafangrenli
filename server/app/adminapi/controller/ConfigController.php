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

use app\adminapi\logic\auth\AuthLogic;
use app\adminapi\logic\ConfigLogic;
use app\api\logic\IndexLogic;
use app\common\service\ConfigService;

/**
 * 配置控制器
 * Class ConfigController
 * @package app\adminapi\controller
 */
class ConfigController extends BaseAdminController
{
    public array $notNeedLogin = ['getConfig', 'dict', 'policy'];


    /**
     * @notes 基础配置
     * @return \think\response\Json
     * @author 张晓科
     * @date 2021/12/31 11:01
     */
    public function getConfig()
    {
        $data = ConfigLogic::getConfig();
        return $this->data($data);
    }

    function getConfigs()
    {
        $data = ConfigService::get('configs','configs');
        return $this->data($data);
    }

    public function setConfigs()
    {
        $value = $this->request->post('value');# 获取前端传过来的值
        ConfigService::set('configs','configs',$value);
        return $this->success();
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
}

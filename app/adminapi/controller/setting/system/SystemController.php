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

namespace app\adminapi\controller\setting\system;

use app\adminapi\controller\BaseAdminController;
use app\adminapi\logic\setting\system\SystemLogic;


/**
 * 系统维护
 * Class SystemController
 * @package app\adminapi\controller\setting\system
 */
class SystemController extends BaseAdminController
{

    /**
     * @notes 获取系统环境信息
     * @return \think\response\Json
     * @author 张晓科
     * @date 2021/12/28 18:36
     */
    public function info()
    {
        $result = SystemLogic::getInfo();
        return $this->data($result);
    }
}

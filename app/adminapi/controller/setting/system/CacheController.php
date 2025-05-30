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
use app\adminapi\logic\setting\system\CacheLogic;

/**
 * 系统缓存
 * Class CacheController
 * @package app\adminapi\controller\setting\system
 */
class CacheController extends BaseAdminController
{

    /**
     * @notes 清除系统缓存
     * @return \think\response\Json
     * @author 张晓科
     * @date 2023/4/8 16:34
     */
    public function clear()
    {
        CacheLogic::clear();
        return $this->success('清除成功', [], 1, 1);
    }
}

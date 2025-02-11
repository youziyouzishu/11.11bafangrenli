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


use app\adminapi\controller\BaseAdminController;
use app\adminapi\lists\StaffLayerLists;
use app\adminapi\logic\StaffLayerLogic;
use app\adminapi\validate\StaffLayerValidate;


/**
 * StaffLayer控制器
 * Class StaffLayerController
 * @package app\adminapi\controller
 */
class StaffLayerController extends BaseAdminController
{


    /**
     * @notes 获取列表
     * @return \think\response\Json
     * @author ideaadmin
     * @date 2024/12/17 09:27
     */
    public function lists()
    {
        return $this->dataLists(new StaffLayerLists());
    }


    /**
     * @notes 添加
     * @return \think\response\Json
     * @author ideaadmin
     * @date 2024/12/17 09:27
     */
    public function add()
    {
        $params = (new StaffLayerValidate())->post()->goCheck('add');
        $result = StaffLayerLogic::add($params);
        if (true === $result) {
            return $this->success('添加成功', [], 1, 1);
        }
        return $this->fail(StaffLayerLogic::getError());
    }


    /**
     * @notes 编辑
     * @return \think\response\Json
     * @author ideaadmin
     * @date 2024/12/17 09:27
     */
    public function edit()
    {
        $params = (new StaffLayerValidate())->post()->goCheck('edit');
        $result = StaffLayerLogic::edit($params);
        if (true === $result) {
            return $this->success('编辑成功', [], 1, 1);
        }
        return $this->fail(StaffLayerLogic::getError());
    }


    /**
     * @notes 删除
     * @return \think\response\Json
     * @author ideaadmin
     * @date 2024/12/17 09:27
     */
    public function delete()
    {
        $params = (new StaffLayerValidate())->post()->goCheck('delete');
        StaffLayerLogic::delete($params);
        return $this->success('删除成功', [], 1, 1);
    }


    /**
     * @notes 获取详情
     * @return \think\response\Json
     * @author ideaadmin
     * @date 2024/12/17 09:27
     */
    public function detail()
    {
        $params = (new StaffLayerValidate())->goCheck('detail');
        $result = StaffLayerLogic::detail($params);
        return $this->data($result);
    }


}
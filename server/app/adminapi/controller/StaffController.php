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
use app\adminapi\lists\StaffLists;
use app\adminapi\logic\StaffLogic;
use app\adminapi\validate\StaffValidate;


/**
 * Staff控制器
 * Class StaffController
 * @package app\adminapi\controller
 */
class StaffController extends BaseAdminController
{


    /**
     * @notes 获取列表
     * @return \think\response\Json
     * @author ideaadmin
     * @date 2024/12/16 18:09
     */
    public function lists()
    {
        return $this->dataLists(new StaffLists());
    }


    /**
     * @notes 添加
     * @return \think\response\Json
     * @author ideaadmin
     * @date 2024/12/16 18:09
     */
    public function add()
    {
        $params = (new StaffValidate())->post()->goCheck('add');
        $result = StaffLogic::add($params);
        if (true === $result) {
            return $this->success('添加成功', [], 1, 1);
        }
        return $this->fail(StaffLogic::getError());
    }


    /**
     * @notes 编辑
     * @return \think\response\Json
     * @author ideaadmin
     * @date 2024/12/16 18:09
     */
    public function edit()
    {
        $params = (new StaffValidate())->post()->goCheck('edit');
        $result = StaffLogic::edit($params);
        if (true === $result) {
            return $this->success('编辑成功', [], 1, 1);
        }
        return $this->fail(StaffLogic::getError());
    }


    /**
     * @notes 删除
     * @return \think\response\Json
     * @author ideaadmin
     * @date 2024/12/16 18:09
     */
    public function delete()
    {
        $params = (new StaffValidate())->post()->goCheck('delete');
        StaffLogic::delete($params);
        return $this->success('删除成功', [], 1, 1);
    }


    /**
     * @notes 获取详情
     * @return \think\response\Json
     * @author ideaadmin
     * @date 2024/12/16 18:09
     */
    public function detail()
    {
        $params = (new StaffValidate())->goCheck('detail');
        $result = StaffLogic::detail($params);
        return $this->data($result);
    }


}
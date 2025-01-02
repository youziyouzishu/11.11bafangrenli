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
use app\adminapi\lists\ConsultLists;
use app\adminapi\logic\ConsultLogic;
use app\adminapi\validate\ConsultValidate;


/**
 * Consult控制器
 * Class ConsultController
 * @package app\adminapi\controller
 */
class ConsultController extends BaseAdminController
{


    /**
     * @notes 获取列表
     * @return \think\response\Json
     * @author ideaadmin
     * @date 2025/01/02 14:50
     */
    public function lists()
    {
        return $this->dataLists(new ConsultLists());
    }


    /**
     * @notes 添加
     * @return \think\response\Json
     * @author ideaadmin
     * @date 2025/01/02 14:50
     */
    public function add()
    {
        $params = (new ConsultValidate())->post()->goCheck('add');
        $result = ConsultLogic::add($params);
        if (true === $result) {
            return $this->success('添加成功', [], 1, 1);
        }
        return $this->fail(ConsultLogic::getError());
    }


    /**
     * @notes 编辑
     * @return \think\response\Json
     * @author ideaadmin
     * @date 2025/01/02 14:50
     */
    public function edit()
    {
        $params = (new ConsultValidate())->post()->goCheck('edit');
        $result = ConsultLogic::edit($params);
        if (true === $result) {
            return $this->success('编辑成功', [], 1, 1);
        }
        return $this->fail(ConsultLogic::getError());
    }


    /**
     * @notes 删除
     * @return \think\response\Json
     * @author ideaadmin
     * @date 2025/01/02 14:50
     */
    public function delete()
    {
        $params = (new ConsultValidate())->post()->goCheck('delete');
        ConsultLogic::delete($params);
        return $this->success('删除成功', [], 1, 1);
    }


    /**
     * @notes 获取详情
     * @return \think\response\Json
     * @author ideaadmin
     * @date 2025/01/02 14:50
     */
    public function detail()
    {
        $params = (new ConsultValidate())->goCheck('detail');
        $result = ConsultLogic::detail($params);
        return $this->data($result);
    }

    public function buy()
    {
        $params = (new ConsultValidate())->post()->goCheck('buy');
        $result = ConsultLogic::buy($params);
    }


}
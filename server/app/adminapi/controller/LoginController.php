<?php

namespace app\adminapi\controller;

use app\adminapi\logic\LoginLogic;
use app\adminapi\validate\LoginValidate;
use app\adminapi\validate\auth\AdminValidate;
use app\adminapi\logic\auth\AdminLogic;
use app\common\{model\auth\Admin, service\sms\SmsDriver};

/**
 * 管理员登录控制器
 * Class LoginController
 * @package app\adminapi\controller
 */
class LoginController extends BaseAdminController
{
    public array $notNeedLogin = ['account', 'register'];

    /**
     * @notes 账号登录
     * @date 2021/6/30 17:01
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author 令狐冲
     */
    public function account()
    {
        $params = (new LoginValidate())->post()->goCheck();
        //sleep(20);
        return $this->data((new LoginLogic())->login($params));
    }

    /**
     * @notes 账号登录
     * @date 2021/6/30 17:01
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author 令狐冲
     */
    public function register()
    {

        $tmp = request()->post();
        if ($tmp['role_id'] == 1){
            $tmp['role_id'] = [1];
        }elseif($tmp['role_id'] == 2){
            $tmp['role_id'] = [2];
        }elseif($tmp['role_id'] == 3){
            $tmp['role_id'] = [3];
        }else{
            return $this->fail("请选择角色");
        }
        $invitecode = $tmp['invitecode'] ?? '';
        if (!empty($invitecode)){
            $admin = Admin::where(['invitecode'=>$invitecode])->find();
            if (!$admin){
                return $this->fail("邀请码错误");
            }
            $tmp['pid'] = $admin['id'];
        }
        $tmp['name'] = $tmp['account'] ?? '';
        $tmp['disable'] = '0';
        $tmp['multipoint_login'] = "1"; //多点登录
        $tmp['company_info'] = '';

        request()->withPost($tmp);
        // 校验短信
        $checkSmsCode = (new SmsDriver())->verify($tmp['account'], $tmp['code'], 101);
        if (!$checkSmsCode) {
            return $this->fail("验证码错误");
        }
        $params = (new AdminValidate())->post()->goCheck('add');
        $result = AdminLogic::add($params);


        if (true === $result) {
            return $this->success('注册成功', [], 1, 1);
        }
        return $this->fail(AdminLogic::getError());
    }




    /**
     * @notes 退出登录
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author 令狐冲
     * @date 2021/7/8 00:36
     */
    public function logout()
    {
        //退出登录情况特殊，只有成功的情况，也不需要token验证
        (new LoginLogic())->logout($this->adminInfo);
        return $this->success();
    }

}

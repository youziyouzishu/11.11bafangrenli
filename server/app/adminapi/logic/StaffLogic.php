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

namespace app\adminapi\logic;


use app\common\model\Staff;
use app\common\logic\BaseLogic;
use app\common\tool\Util;
use think\facade\Db;


/**
 * Staff逻辑
 * Class StaffLogic
 * @package app\adminapi\logic
 */
class StaffLogic extends BaseLogic
{


    /**
     * @notes 添加
     * @param array $params
     * @return bool
     * @author ideaadmin
     * @date 2024/12/16 18:09
     */
    public static function add(array $params): bool
    {
        Db::startTrans();
        try {
            Staff::create([
                'admin_id' => $params['admin_id'],
                'name' => $params['name'],
                'invitecode' => Util::generateAdminInvitecode()
            ]);

            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            self::setError($e->getMessage());
            return false;
        }
    }


    /**
     * @notes 编辑
     * @param array $params
     * @return bool
     * @author ideaadmin
     * @date 2024/12/16 18:09
     */
    public static function edit(array $params): bool
    {
        Db::startTrans();
        try {
            Staff::where('id', $params['id'])->update([
                'admin_id' => $params['admin_id'],
                'name' => $params['name'],
                'invitecode' => $params['invitecode'],
            ]);

            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            self::setError($e->getMessage());
            return false;
        }
    }


    /**
     * @notes 删除
     * @param array $params
     * @return bool
     * @author ideaadmin
     * @date 2024/12/16 18:09
     */
    public static function delete(array $params): bool
    {
        return Staff::destroy($params['id']);
    }


    /**
     * @notes 获取详情
     * @param $params
     * @return array
     * @author ideaadmin
     * @date 2024/12/16 18:09
     */
    public static function detail($params): array
    {
        return Staff::findOrEmpty($params['id'])->toArray();
    }
}
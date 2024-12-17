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


use app\common\model\StaffLayer;
use app\common\logic\BaseLogic;
use think\facade\Db;


/**
 * StaffLayer逻辑
 * Class StaffLayerLogic
 * @package app\adminapi\logic
 */
class StaffLayerLogic extends BaseLogic
{


    /**
     * @notes 添加
     * @param array $params
     * @return bool
     * @author ideaadmin
     * @date 2024/12/17 09:27
     */
    public static function add(array $params): bool
    {
        Db::startTrans();
        try {
            StaffLayer::create([
                'staff_id' => $params['staff_id'],
                'admin_id' => $params['admin_id'],
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
     * @date 2024/12/17 09:27
     */
    public static function edit(array $params): bool
    {
        Db::startTrans();
        try {
            StaffLayer::where('id', $params['id'])->update([
                'staff_id' => $params['staff_id'],
                'admin_id' => $params['admin_id'],
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
     * @date 2024/12/17 09:27
     */
    public static function delete(array $params): bool
    {
        return StaffLayer::destroy($params['id']);
    }


    /**
     * @notes 获取详情
     * @param $params
     * @return array
     * @author ideaadmin
     * @date 2024/12/17 09:27
     */
    public static function detail($params): array
    {
        return StaffLayer::findOrEmpty($params['id'])->toArray();
    }
}
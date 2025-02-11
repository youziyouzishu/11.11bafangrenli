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


use app\common\model\AdminMsg;
use app\common\logic\BaseLogic;
use think\facade\Db;


/**
 * AdminMsg逻辑
 * Class AdminMsgLogic
 * @package app\adminapi\logic
 */
class AdminMsgLogic extends BaseLogic
{


    /**
     * @notes 添加
     * @param array $params
     * @return bool
     * @author ideaadmin
     * @date 2024/01/09 20:18
     */
    public static function add(array $params): bool
    {
        Db::startTrans();
        try {
            AdminMsg::create([
                'user_id' => $params['user_id'],
                'msg_type' => $params['msg_type'],
                'title' => $params['title'],
                'message' => $params['message']
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
     * @date 2024/01/09 20:18
     */
    public static function edit(array $params): bool
    {
        Db::startTrans();
        try {
            AdminMsg::where('id', $params['id'])->update([
                'review_time' => time(),
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
     * @date 2024/01/09 20:18
     */
    public static function delete(array $params): bool
    {
        return AdminMsg::destroy($params['id']);
    }


    /**
     * @notes 获取详情
     * @param $params
     * @return array
     * @author ideaadmin
     * @date 2024/01/09 20:18
     */
    public static function detail($params): array
    {
        return AdminMsg::findOrEmpty($params['id'])->toArray();
    }

    /**
     * @notes 阅读
     * @param array $params
     * @return bool
     * @author ideaadmin
     * @date 2024/01/09 20:18
     */
    public static function review(array $params): bool
    {
        Db::startTrans();
        try {
            AdminMsg::where('id', $params['id'])->update([
                'review_time' => time(),
            ]);
            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            self::setError($e->getMessage());
            return false;
        }
    }
}

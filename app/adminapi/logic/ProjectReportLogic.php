<?php
// +----------------------------------------------------------------------
// | likeadmin快速开发前后端分离管理后台（PHP版）
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 开源版本可自由商用，可去除界面版权logo
// | gitee下载：https://gitee.com/likeshop_gitee/likeadmin
// | github下载：https://github.com/likeshop-github/likeadmin
// | 访问官网：https://www.likeadmin.cn
// | likeadmin团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------
// | author: likeadminTeam
// +----------------------------------------------------------------------

namespace app\adminapi\logic;


use app\common\model\ProjectReport;
use app\common\logic\BaseLogic;
use think\facade\Db;


/**
 * ProjectReport逻辑
 * Class ProjectReportLogic
 * @package app\adminapi\logic
 */
class ProjectReportLogic extends BaseLogic
{


    /**
     * @notes 添加
     * @param array $params
     * @return bool
     * @author likeadmin
     * @date 2025/02/18 18:01
     */
    public static function add(array $params): bool
    {
        Db::startTrans();
        try {
            ProjectReport::create([
                'admin_id' => $params['admin_id'],
                'user_id' => $params['user_id'],
                'project_tasks_id' => $params['project_tasks_id'],
                'date' => $params['date'],
                'mianshi_num' => $params['mianshi_num'],
                'ruzhi_num' => $params['ruzhi_num'],
                'daidaogang_num' => $params['daidaogang_num'],
                'daogang_num' => $params['daogang_num'],
                'liushi_num' => $params['liushi_num'],
                'lizhi_num' => $params['lizhi_num'],
                'company_amount' => $params['company_amount'],
                'jiesuan_amount' => $params['jiesuan_amount'],
                'status' => $params['status']
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
     * @author likeadmin
     * @date 2025/02/18 18:01
     */
    public static function edit(array $params): bool
    {
        Db::startTrans();
        try {
            ProjectReport::where('id', $params['id'])->update([
                'admin_id' => $params['admin_id'],
                'user_id' => $params['user_id'],
                'project_tasks_id' => $params['project_tasks_id'],
                'date' => $params['date'],
                'mianshi_num' => $params['mianshi_num'],
                'ruzhi_num' => $params['ruzhi_num'],
                'daidaogang_num' => $params['daidaogang_num'],
                'daogang_num' => $params['daogang_num'],
                'liushi_num' => $params['liushi_num'],
                'lizhi_num' => $params['lizhi_num'],
                'company_amount' => $params['company_amount'],
                'jiesuan_amount' => $params['jiesuan_amount'],
                'status' => $params['status']
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
     * @author likeadmin
     * @date 2025/02/18 18:01
     */
    public static function delete(array $params): bool
    {
        return ProjectReport::destroy($params['id']);
    }


    /**
     * @notes 获取详情
     * @param $params
     * @return array
     * @author likeadmin
     * @date 2025/02/18 18:01
     */
    public static function detail($params): array
    {
        return ProjectReport::findOrEmpty($params['id'])->toArray();
    }
}
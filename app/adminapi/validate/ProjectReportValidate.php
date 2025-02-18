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

namespace app\adminapi\validate;


use app\common\validate\BaseValidate;


/**
 * ProjectReport验证器
 * Class ProjectReportValidate
 * @package app\adminapi\validate
 */
class ProjectReportValidate extends BaseValidate
{

     /**
      * 设置校验规则
      * @var string[]
      */
    protected $rule = [
        'id' => 'require',
        'admin_id' => 'require',
        'user_id' => 'require',
        'project_tasks_id' => 'require',
        'mianshi_num' => 'require',
        'ruzhi_num' => 'require',
        'daidaogang_num' => 'require',
        'daogang_num' => 'require',
        'liushi_num' => 'require',
        'lizhi_num' => 'require',
        'company_amount' => 'require',
        'jiesuan_amount' => 'require',
        'status' => 'require',
    ];


    /**
     * 参数描述
     * @var string[]
     */
    protected $field = [
        'id' => 'id',
        'admin_id' => '后台',
        'user_id' => '驻场ID',
        'project_tasks_id' => '项目ID',
        'mianshi_num' => '面试人数',
        'ruzhi_num' => '入职人数',
        'daidaogang_num' => '待到岗人数',
        'daogang_num' => '已到刚人数',
        'liushi_num' => '流失人员',
        'lizhi_num' => '离职人员',
        'company_amount' => '公司资金',
        'jiesuan_amount' => '结算数据',
        'status' => '状态:0=待更新,1=待确认,2=已确认,3=日报缺失',
    ];


    /**
     * @notes 添加场景
     * @return ProjectReportValidate
     * @author likeadmin
     * @date 2025/02/18 18:01
     */
    public function sceneAdd()
    {
        return $this->only(['admin_id','user_id','project_tasks_id','mianshi_num','ruzhi_num','daidaogang_num','daogang_num','liushi_num','lizhi_num','company_amount','jiesuan_amount','status']);
    }


    /**
     * @notes 编辑场景
     * @return ProjectReportValidate
     * @author likeadmin
     * @date 2025/02/18 18:01
     */
    public function sceneEdit()
    {
        return $this->only(['id','admin_id','user_id','project_tasks_id','mianshi_num','ruzhi_num','daidaogang_num','daogang_num','liushi_num','lizhi_num','company_amount','jiesuan_amount','status']);
    }


    /**
     * @notes 删除场景
     * @return ProjectReportValidate
     * @author likeadmin
     * @date 2025/02/18 18:01
     */
    public function sceneDelete()
    {
        return $this->only(['id']);
    }


    /**
     * @notes 详情场景
     * @return ProjectReportValidate
     * @author likeadmin
     * @date 2025/02/18 18:01
     */
    public function sceneDetail()
    {
        return $this->only(['id']);
    }

}
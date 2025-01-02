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

namespace app\adminapi\validate;


use app\common\validate\BaseValidate;


/**
 * Consult验证器
 * Class ConsultValidate
 * @package app\adminapi\validate
 */
class ConsultValidate extends BaseValidate
{

     /**
      * 设置校验规则
      * @var string[]
      */
    protected $rule = [
        'id' => 'require',
        'name' => 'require',
        'num' => 'require',
        'times' => 'require',
        'price' => 'require',
    ];


    /**
     * 参数描述
     * @var string[]
     */
    protected $field = [
        'id' => 'id',
        'name' => '名称',
        'num' => '月数',
        'times' => '次数',
        'price' => '价格',
    ];


    /**
     * @notes 添加场景
     * @return ConsultValidate
     * @author ideaadmin
     * @date 2025/01/02 14:50
     */
    public function sceneAdd()
    {
        return $this->only(['name','num','times','price']);
    }


    /**
     * @notes 编辑场景
     * @return ConsultValidate
     * @author ideaadmin
     * @date 2025/01/02 14:50
     */
    public function sceneEdit()
    {
        return $this->only(['id','name','num','times','price']);
    }


    /**
     * @notes 删除场景
     * @return ConsultValidate
     * @author ideaadmin
     * @date 2025/01/02 14:50
     */
    public function sceneDelete()
    {
        return $this->only(['id']);
    }


    /**
     * @notes 详情场景
     * @return ConsultValidate
     * @author ideaadmin
     * @date 2025/01/02 14:50
     */
    public function sceneDetail()
    {
        return $this->only(['id']);
    }

    /**
     * @notes 购买场景
     * @return ConsultValidate
     * @author ideaadmin
     * @date 2025/01/02 14:50
     */
    public function sceneBuy()
    {
        return $this->only(['id']);
    }

}
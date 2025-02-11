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


namespace app\common\model\user;


use app\common\enum\user\UserEnum;
use app\common\model\BaseModel;
use app\common\model\PersonalVerification;
use app\common\service\FileService;
use think\model\concern\SoftDelete;

/**
 * 用户模型
 * Class User
 * @package app\common\model\user
 */
class User extends BaseModel
{
    use SoftDelete;

    protected $deleteTime = 'delete_time';

    public $append = ['role_text','status_text'];

    /**
     * @notes 关联用户授权模型
     * @return \think\model\relation\HasOne
     * @author 张晓科
     * @date 2023/9/22 16:03
     */
    public function userAuth()
    {
        return $this->hasOne(UserAuth::class, 'user_id');
    }


    /**
     * @notes 搜索器-用户信息
     * @param $query
     * @param $value
     * @param $data
     * @author 张晓科
     * @date 2023/9/22 16:12
     */
    public function searchKeywordAttr($query, $value, $data)
    {
        if ($value) {
            $query->where('sn|nickname|mobile', 'like', '%' . $value . '%');
        }
    }


    /**
     * @notes 搜索器-注册来源
     * @param $query
     * @param $value
     * @param $data
     * @author 张晓科
     * @date 2023/9/22 16:13
     */
    public function searchChannelAttr($query, $value, $data)
    {
        if ($value) {
            $query->where('channel', '=', $value);
        }
    }


    /**
     * @notes 搜索器-注册时间
     * @param $query
     * @param $value
     * @param $data
     * @author 张晓科
     * @date 2023/9/22 16:13
     */
    public function searchCreateTimeStartAttr($query, $value, $data)
    {
        if ($value) {
            $query->where('create_time', '>=', strtotime($value));
        }
    }


    /**
     * @notes 搜索器-注册时间
     * @param $query
     * @param $value
     * @param $data
     * @author 张晓科
     * @date 2023/9/22 16:13
     */
    public function searchCreateTimeEndAttr($query, $value, $data)
    {
        if ($value) {
            $query->where('create_time', '<=', strtotime($value));
        }
    }


    /**
     * @notes 头像获取器 - 用于头像地址拼接域名
     * @param $value
     * @return string
     * @author Tab
     * @date 2021/7/17 14:28
     */
    public function getAvatarAttr($value)
    {
        return trim($value) ? FileService::getFileUrl($value) : '';
    }

    function getRoleTextAttr($value)
    {
        $value = $value ?: ($this->role ?? '');
        $list = $this->getRoleList();
        return $list[$value] ?? '';
    }

    public function getRoleList()
    {
        return [
            2 => '招聘顾问',
            3 => '驻场经理',
            4 => '求职者'
        ];
    }

    function getStatusTextAttr($value)
    {
        $value = $value ?: ($this->status ?? '');
        $list = [
            0 => '禁用',
            1 => '启用'
        ];
        return $list[$value] ?? '';
    }


    /**
     * @notes 获取器-性别描述
     * @param $value
     * @param $data
     * @return string|string[]
     * @author 张晓科
     * @date 2023/9/7 15:15
     */
    public function getSexAttr($value, $data)
    {
        return UserEnum::getSexDesc($value);
    }


    /**
     * @notes 登录时间
     * @param $value
     * @return string
     * @author 张晓科
     * @date 2023/9/23 18:15
     */
    public function getLoginTimeAttr($value)
    {
        return $value ? date('Y-m-d H:i:s', $value) : '';
    }

    /**
     * @notes 生成用户编码
     * @param string $prefix
     * @param int $length
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author 张晓科
     * @date 2023/9/16 10:33
     */
    public static function createUserSn($prefix = '', $length = 8)
    {
        $rand_str = '';
        for ($i = 0; $i < $length; $i++) {
            $rand_str .= mt_rand(0, 9);
        }
        $sn = $prefix . $rand_str;
        if (User::where(['sn' => $sn])->find()) {
            return self::createUserSn($prefix, $length);
        }
        return $sn;
    }

    /**
     * @notes 头像获取器 - 头像路径添加域名
     * @param $value
     * @return string
     * @author Tab
     * @date 2021/7/13 11:35
     */
    public function getCompanyImgAttr($value)
    {
        return empty($value) ? "" : FileService::getFileUrl(trim($value, '/'));
    }

    /**
     * @notes 头像获取器 - 头像路径添加域名
     * @param $value
     * @return string
     * @author Tab
     * @date 2021/7/13 11:35
     */
    public function getLdLicenceImgAttr($value)
    {
        return empty($value) ? "" : FileService::getFileUrl(trim($value, '/'));
    }
    /**
     * @notes 头像获取器 - 头像路径添加域名
     * @param $value
     * @return string
     * @author Tab
     * @date 2021/7/13 11:35
     */
    public function getHrLicenceImgAttr($value)
    {
        return empty($value) ? "" : FileService::getFileUrl(trim($value, '/'));
    }

    function personalVerification()
    {
        return $this->hasOne(PersonalVerification::class,'user_id','id');
    }

    
}

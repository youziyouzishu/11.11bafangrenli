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

namespace app\adminapi\lists;


use app\adminapi\lists\BaseAdminDataLists;
use app\common\model\Staff;
use app\common\lists\ListsSearchInterface;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;


/**
 * Staff列表
 * Class StaffLists
 * @package app\adminapi\lists
 */
class StaffLists extends BaseAdminDataLists implements ListsSearchInterface
{


    /**
     * @notes 设置搜索条件
     * @return \string[][]
     * @author ideaadmin
     * @date 2024/12/16 18:09
     */
    public function setSearch(): array
    {
        return [
            '=' => ['admin_id', 'name', 'invitecode'],
        ];
    }


    /**
     * @notes 获取列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ideaadmin
     * @date 2024/12/16 18:09
     */
    public function lists(): array
    {
        return Staff::where($this->searchWhere)
            ->field(['id', 'admin_id', 'name', 'invitecode'])
            ->limit($this->limitOffset, $this->limitLength)
            ->when(in_array(2,$this->adminInfo['role_id']),function ($query){
                //如果是业务人员，只能看到自己所属的
                $query->where('admin_id',$this->adminInfo['admin_id']);
            })
            ->order(['id' => 'desc'])
            ->select()
            ->each(function ($item){
                $writer = new PngWriter();
                $qrCode = new QrCode(
                    data: 'https://bf.sf0000.com.cn/business/register',
                    encoding: new Encoding('UTF-8'),
                    errorCorrectionLevel: ErrorCorrectionLevel::Low,
                    size: 100,
                    margin: 10,
                    roundBlockSizeMode: RoundBlockSizeMode::Margin,
                    foregroundColor: new Color(0, 0, 0),
                    backgroundColor: new Color(255, 255, 255)
                );
                $result = $writer->write($qrCode)->getDataUri();
                $item->setAttr('base64',$result);
            })
            ->toArray();
    }


    /**
     * @notes 获取数量
     * @return int
     * @author ideaadmin
     * @date 2024/12/16 18:09
     */
    public function count(): int
    {
        return Staff::where($this->searchWhere)
            ->when(in_array(2,$this->adminInfo['role_id']),function ($query){
            //如果是业务人员，只能看到自己所属的
            $query->where('admin_id',$this->adminInfo['admin_id']);
        })->count();
    }

}
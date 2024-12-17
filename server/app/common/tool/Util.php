<?php

namespace app\common\tool;

use app\common\model\auth\Admin;
use Throwable;


class Util
{


    public static function generateOrdersn()
    {
        return date('Ymd') . mb_strtoupper(uniqid());
    }

    /**
     * 生成管理员邀请码
     * @return string
     */
    public static function generateAdminInvitecode()
    {
        do {
            $invitecode = self::alnum();
        } while (Admin::where(['invitecode' => $invitecode])->exists());
        return $invitecode;
    }

    /**
     * 密码哈希
     * @param $password
     * @param string $algo
     * @return false|string|null
     */
    public static function passwordHash($password, string $algo = PASSWORD_DEFAULT)
    {
        return password_hash($password, $algo);
    }

    /**
     * 验证密码哈希
     * @param $password
     * @param $hash
     * @return bool
     */
    public static function passwordVerify(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }



    /**
     * 获取语义化时间
     * @param $time
     * @return false|string
     */
    public static function humanDate($time)
    {
        $timestamp = is_numeric($time) ? $time : strtotime($time);
        $dur = time() - $timestamp;
        if ($dur < 0) {
            return date('Y-m-d', $timestamp);
        } else {
            if ($dur < 60) {
                return $dur . '秒前';
            } else {
                if ($dur < 3600) {
                    return floor($dur / 60) . '分钟前';
                } else {
                    if ($dur < 86400) {
                        return floor($dur / 3600) . '小时前';
                    } else {
                        if ($dur < 2592000) { // 30天内
                            return floor($dur / 86400) . '天前';
                        } else {
                            return date('Y-m-d', $timestamp);
                        }
                    }
                }
            }
        }
    }

    /**
     * 格式化文件大小
     * @param $file_size
     * @return string
     */
    public static function formatBytes($file_size): string
    {
        $size = sprintf("%u", $file_size);
        if($size == 0) {
            return("0 Bytes");
        }
        $size_name = array(" Bytes", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB");
        return round($size/pow(1024, ($i = floor(log($size, 1024)))), 2) . $size_name[$i];
    }


    /**
     * 生成数字和字母
     *
     * @param int $len 长度
     * @return string
     */
    public static function alnum($len = 6)
    {
        return self::build('alnum', $len);
    }

    /**
     * 仅生成字符
     *
     * @param int $len 长度
     * @return string
     */
    public static function alpha($len = 6)
    {
        return self::build('alpha', $len);
    }

    /**
     * 生成指定长度的随机数字
     *
     * @param int $len 长度
     * @return string
     */
    public static function numeric($len = 4)
    {
        return self::build('numeric', $len);
    }

    /**
     * 生成指定长度的无0随机数字
     *
     * @param int $len 长度
     * @return string
     */
    public static function nozero($len = 4)
    {
        return self::build('nozero', $len);
    }

    /**
     * 能用的随机数生成
     * @param string $type 类型 alpha/alnum/numeric/nozero/unique/md5/encrypt/sha1
     * @param int    $len  长度
     * @return string
     */
    public static function build($type = 'alnum', $len = 8)
    {
        switch ($type) {
            case 'alpha':
            case 'alnum':
            case 'numeric':
            case 'nozero':
                switch ($type) {
                    case 'alpha':
                        $pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                        break;
                    case 'alnum':
                        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                        break;
                    case 'numeric':
                        $pool = '0123456789';
                        break;
                    case 'nozero':
                        $pool = '123456789';
                        break;
                }
                return substr(str_shuffle(str_repeat($pool, ceil($len / strlen($pool)))), 0, $len);
            case 'unique':
            case 'md5':
                return md5(uniqid(mt_rand()));
            case 'encrypt':
            case 'sha1':
                return sha1(uniqid(mt_rand(), true));
        }
    }

    /**
     * 获取全球唯一标识
     * @return string
     */
    public static function uuid()
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }

}

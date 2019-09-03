<?php

namespace ParamsValidateMicroServices\tool;

/**
 * Class StringTool
 * @package ParamsValidateMicroServices\tool
 */
class StringTool
{
    /**
     * 生成随机数
     *
     * @param int $length
     * @return string
     */
    public static function createNonceString($length = 16)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $nonce = '';
        for ($i = 0; $i < $length; $i++) {
            $nonce .= $chars{rand(0, 61)};
        }
        return $nonce;
    }

    /**
     * 创建纯数字字符串
     *
     * @param int $length
     * @return string
     */
    public static function createFigureString($length = 16)
    {
        $chars = "0123456789";
        $nonce = '';
        for ($i = 0; $i < $length; $i++) {
            $nonce .= $chars{rand(0, 9)};
        }
        return $nonce;
    }

    /**
     * 创建纯字母字符串
     *
     * @param int $length
     * @return string
     */
    public static function createLetterString($length = 16)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $nonce = '';
        for ($i = 0; $i < $length; $i++) {
            $nonce .= $chars{rand(0, 51)};
        }
        return $nonce;
    }

    /**
     * 创建大写字母字符串
     *
     * @param int $length
     * @return string
     */
    public static function upperLetterString($length = 16)
    {
        return strtoupper(self::createLetterString($length));
    }

    /**
     * 创建小写字母字符串
     *
     * @param int $length
     * @return string
     */
    public static function lowerLetterString($length = 16)
    {
        return strtolower(self::createLetterString($length));
    }

    /**
     * 创建大写字符串
     *
     * @param int $length
     * @return string
     */
    public static function upperString($length = 16)
    {
        return strtoupper(self::createNonceString($length));
    }

    /**
     * 创建小写字符串
     *
     * @param int $length
     * @return string
     */
    public static function lowerString($length = 16)
    {
        return strtolower(self::createNonceString($length));
    }

    /**
     * 是否全是中文
     *
     * @param $string
     * @return bool
     */
    public static function isAllChinese($string)
    {
        return preg_match('/^[\x7f-\xff]+$/', $string) ? true : false;
    }

    /**
     * 是否为中国人名字
     * 
     * @param $string
     * @return bool
     */
    public static function isChineseName($string)
    {
        if (strpos($string, '·')) {
            $string = str_replace("·", '', $string);
        }
        return preg_match('/^[\x7f-\xff]+$/', $string) ? true : false;
    }

    /**
     * 检测是否是邮箱格式
     *
     * @param $email
     * @return bool
     */
    public static function isEmailAddress($email)
    {
        $mode = '/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/';
        return preg_match($mode, $email) ? true : false;
    }

    /**
     * 检测是否ip地址
     *
     * @param $ip
     * @return bool
     */
    public static function isIpAddress($ip)
    {
        return filter_var($ip, FILTER_VALIDATE_IP) ? true : false;

    }
}
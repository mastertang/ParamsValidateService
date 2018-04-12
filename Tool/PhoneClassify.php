<?php

namespace ParamsValidateMicroServices\Services;
class PhoneClassify
{
    /**
     * 检查是否是大陆的电话号码
     * @param $phone
     * @return bool|int
     */
    public static function is_PRC_Phone($phone)
    {
        $pattern = "^[1][3-8]\\d{9}$";
        return self::patternValidate($pattern, $phone);
    }

    /**
     * 检查是否是台湾的电话号码
     * @param $phone
     * @return int
     */
    public static function is_TW_Phone($phone)
    {
        $pattern = "^[0][9]\\d{8}$";
        return self::patternValidate($pattern, $phone);
    }

    /**
     * 检查是否是香港的电话号码
     * @param $phone
     * @return int
     */
    public static function is_HK_Phone($phone)
    {
        $pattern = "^([6|9])\\d{7}";
        return self::patternValidate($pattern, $phone);
    }

    /**
     * 检查是否是澳门的电话号码
     * @param $phone
     * @return int
     */
    public static function is_AM_Phone($phone)
    {
        $pattern = "^[6]([8|6])\\d{5}$";
        return self::patternValidate($pattern, $phone);
    }

    /**
     * 检查是否是中国的电话
     * @param $phone
     * @return bool
     */
    public static function is_China_Phone($phone)
    {
        $resultPRC = self::is_PRC_Phone($phone);
        $resultHK  = self::is_HK_Phone($phone);
        $resultAM  = self::is_AM_Phone($phone);
        return ($resultPRC || $resultHK || $resultAM);
    }

    /**
     * 所有手机号码都可以
     * @param $phone
     * @return bool
     */
    public static function is_All_Phone($phone)
    {
        if (empty($phone) || (strlen($phone) <= 5)) {
            return false;
        }
        return true;
    }

    /**
     * 检查是否是中国移动的电话号码
     * @param $phone
     * @return bool
     */
    public static function is_CMCC_Phone($phone)
    {
        $pattern = "^[1]([34|35|36|37|38|39|50|51|57|58|59|87|88])\\d{8}$";
        return self::patternValidate($pattern, $phone);
    }

    /**
     * 检查是否是中国联通的电话号码
     * @param $phone
     * @return bool
     */
    public static function is_CUCC_Phone($phone)
    {
        $pattern = "^[1]([30|31|32|52|55|56|85|86])\\d{8}$";
        return self::patternValidate($pattern, $phone);
    }

    /**
     * 检查是否是移动的电话号码
     * @param $phone
     * @return bool
     */
    public static function is_CTCC_Phone($phone)
    {
        $pattern = "^[1]([33|53|80|89])\\d{8}$";
        return self::patternValidate($pattern, $phone);
    }

    /**
     * Pattern验证统一函数
     * @param $pattern
     * @param $phone
     * @return int
     */
    protected static function patternValidate($pattern, $phone)
    {
        if (empty($phone)) {
            return false;
        }
        return preg_match($pattern, $phone);
    }
}
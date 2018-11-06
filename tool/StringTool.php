<?php

namespace ParamsValidateMicroServices\tool;

class StringTool
{
    //生成随机数
    public static function createNonceString($length = 16)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $nonce = '';
        for ($i = 0; $i < $length; $i++) {
            $nonce .= $chars{rand(0, 61)};
        }
        return $nonce;
    }

    /*
     * 创建纯数字字符串
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

    /*
     * 创建纯字母字符串
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

    /*
     * 创建大写字母字符串
     */
    public static function upperLetterString($length = 16)
    {
        return strtoupper(self::createLetterString($length));
    }

    /*
     * 创建小写字母字符串
     */
    public static function lowerLetterString($length = 16)
    {
        return strtolower(self::createLetterString($length));
    }

    /*
     * 创建大写字符串
     */
    public static function upperString($length = 16)
    {
        return strtoupper(self::createNonceString($length));
    }

    /*
     * 创建小写字符串
     */
    public static function lowerString($length = 16)
    {
        return strtolower(self::createNonceString($length));
    }
    
    
}
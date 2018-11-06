<?php

namespace ParamsValidateMicroServices\tool;

class MoneyTool
{
    /*
     * 元转角
     */
    public static function yuanToJiao($yuan)
    {
        return number_format($yuan * 10, 1, '.', '');
    }

    /*
     * 元转角
     */
    public static function yuanToFen($yuan)
    {
        return (int)($yuan * 100);
    }

    /*
     * 角转元
     */
    public static function jiaoToYuan($jiao)
    {
        return number_format($jiao / 10, 2, '.', '');
    }

    /*
     * 角转分
     */
    public static function jiaoToFen($jiao)
    {
        return (int)($jiao * 10);
    }

    /*
     * 分转元
     */
    public static function fenToYuan($fen)
    {
        return number_format($fen / 100, 2, '.', '');
    }

    /*
     * 分转角
     */
    public static function fenToJiao($fen)
    {
        return number_format($fen / 10, 1, '.', '');
    }

    /*
     * 更改数字的格式
     */
    public static function changeNumberFormat($number, $length = 0, $thousandsSep = ',', $decPoint = '.')
    {
        return number_format($number, $length, $decPoint, $thousandsSep);
    }
    
}
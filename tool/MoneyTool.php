<?php

namespace ParamsValidateMicroServices\tool;

class MoneyTool
{
    /*
     * 元转角
     */
    public static function yuanToJiao($yuan)
    {
        return round($yuan * 10, 1);
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
        return round($jiao / 10, 2);
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
        return round($fen / 100, 2);
    }

    /*
     * 分转角
     */
    public static function fenToJiao($fen)
    {
        return round($fen / 10, 1);
    }
}
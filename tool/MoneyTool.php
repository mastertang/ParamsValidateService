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
    public static function changeNumberFormat($number, $length = 0, $thousandsSep = ',', $decPoint = '.', $pad = false)
    {
        $number         = '' . $number;
        $pointPostition = strpos($number, '.');
        if ($pointPostition === false) {
            $number = (int)$number;
            if(!$pad){
                $length = 0;
            }
        } else {
            $tail = substr($number, $pointPostition + 1);
            $head = substr($number, 0, $pointPostition);
            if ($length === 0) {
                $number = (int)$head;
                if(!$pad){
                    $length = 0;
                }
            } else {
                if($pad){
                    if($length > strlen($tail)){
                        str_pad($tail,$length - strlen($tail),"0");
                    }
                }else {
                    if ($length > strlen($tail)) {
                        $length = strlen($tail);
                    }
                }
                $tail   = substr($tail, 0, $length);
                $number = (float)("{$head}.{$tail}");
            }
        }
        return number_format($number, $length, $decPoint, $thousandsSep);
    }

}
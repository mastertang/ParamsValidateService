<?php

namespace ParamsValidateMicroServices\tool;

class DateTool
{
    //判断是否是星期六
    public static function isSaturday($date)
    {
        if (date('w', strtotime($date)) === '6') {
            return true;
        }
        return false;
    }

    //判断是否是星期日
    public static function isSunday($date)
    {
        if (date('w', strtotime($date)) === '0') {
            return true;
        }
        return false;
    }

    //判断是否是周末
    public static function isWeekend($date)
    {
        if (self::isSaturday($date) || self::isSaturday($date)) {
            return true;
        }
        return false;
    }
    
    //获取今天结束时间戳
    public static function getTodayEndStamp()
    {
        $ymd = date('Y-m-d ', time());
        return strtotime($ymd . '24:00:00');
    }

    //获取今天开始时间戳
    public static function getTodayStartStamp()
    {
        $ymd = date('Y-m-d ', time());
        return strtotime($ymd . '00:00:00');
    }

    //获取今天结束日期
    public static function getTodayEndDate()
    {
        return date('Y-m-d H:i:s', self::getTodayEndStamp());
    }

    //获取今天开始日期
    public static function getTodayStartDate()
    {
        return date('Y-m-d H:i:s', self::getTodayStartStamp());
    }

    //获取日期年月日
    public static function turnToYmd($date)
    {
        return $date('Y-m-d', strtotime($date));
    }

    //改变日期格式
    public static function changeDateFormat($foramt, $date)
    {
        return date($foramt, strtotime($date));
    }

    const SEARCH_MIN    = 0x1;
    const SEARCH_MAX    = 0x2;
    const SEARCH_NORAML = 0x3;

    //区间
    public static function dateInRegion($region, $nowTime, $searType = self::SEARCH_NORAML)
    {
        if (is_string($nowTime)) {
            $nowTime = strtotime($nowTime);
        }
        if ($nowTime === false || !is_array($region) || empty($region)) {
            return false;
        }
        $lastKey      = false;
        $sectionCount = 0;
        foreach ($region as $key => $subRegion) {
            if (is_array($subRegion) && isset($subRegion[0], $subRegion[1])) {
                $tempStart = strtotime($subRegion[0]);
                $tempEnd   = strtotime($subRegion[1]);
                if ($tempStart === false || $tempEnd === false) {
                    continue;
                }
                if ($nowTime <= $tempEnd && $nowTime >= $tempStart) {
                    $tempSection = $tempEnd - $tempStart;
                    switch ($searType) {
                        case self::SEARCH_MIN:
                            if ($sectionCount === 0 || $tempSection < $sectionCount) {
                                $sectionCount = $tempSection;
                                $lastKey      = $key;
                            }
                            break;
                        case self::SEARCH_MAX:
                            if ($sectionCount === 0 || $tempSection > $sectionCount) {
                                $sectionCount = $tempSection;
                                $lastKey      = $key;
                            }
                            break;
                        case self::SEARCH_NORAML:
                        default:
                            return $key;
                            break;
                    }
                }
            }
        }
        return $lastKey;
    }
}
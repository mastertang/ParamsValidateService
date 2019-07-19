<?php

namespace ParamsValidateMicroServices\tool;

class DateTool
{
    /**
     * 判断是否是星期六
     *
     * @param $date
     * @return bool
     */
    public static function isSaturday($date)
    {
        if (date('w', strtotime($date)) === '6') {
            return true;
        }
        return false;
    }

    /**
     * 判断是否是星期日
     *
     * @param $date
     * @return bool
     */
    public static function isSunday($date)
    {
        if (date('w', strtotime($date)) === '0') {
            return true;
        }
        return false;
    }

    /**
     * 判断是否是周末
     *
     * @param $date
     * @return bool
     */
    public static function isWeekend($date)
    {
        if (self::isSaturday($date) || self::isSaturday($date)) {
            return true;
        }
        return false;
    }

    /**
     * 获取今天结束时间戳
     *
     * @return false|int
     */
    public static function getTodayEndStamp()
    {
        return strtotime('24:00:00');
    }

    /**
     * 获取今天开始时间戳
     *
     * @return false|int
     */
    public static function getTodayStartStamp()
    {
        return strtotime('00:00:00');
    }

    /**
     * 获取今天结束日期
     *
     * @return false|string
     */
    public static function getTodayEndDate()
    {
        return date('Y-m-d H:i:s', self::getTodayEndStamp());
    }

    /**
     * 获取今天开始日期
     *
     * @return false|string
     */
    public static function getTodayStartDate()
    {
        return date('Y-m-d H:i:s', self::getTodayStartStamp());
    }

    /**
     * 获取日期年月日
     *
     * @param $date
     * @return mixed
     */
    public static function turnToYmd($date)
    {
        return $date('Y-m-d', strtotime($date));
    }

    /**
     * 改变日期格式
     *
     * @param $foramt
     * @param $date
     * @return false|string
     */
    public static function changeDateFormat($foramt, $date)
    {
        return date($foramt, strtotime($date));
    }

    /**
     * 判断日期是否在区间中
     *
     * @param $nowDate
     * @param $dateStart
     * @param $dateEnd
     * @return bool
     */
    public static function dateInSection($nowDate, $dateStart, $dateEnd)
    {
        $dateStartStamp = strtotime($dateStart);
        $dateEndStamp   = strtotime($dateEnd);
        $nowStamp       = strtotime($nowDate);
        if ($dateStartStamp === false || $dateStartStamp === false || $nowStamp === false) {
            return false;
        }
        if ($nowStamp < $dateStartStamp || $nowStamp > $dateEndStamp) {
            return false;
        } else {
            return true;
        }
    }

    const SEARCH_MIN    = 0x1;
    const SEARCH_MAX    = 0x2;
    const SEARCH_NORAML = 0x3;

    /**
     * 区间
     *
     * @param $region
     * @param $nowTime
     * @param int $searType
     * @return bool|int|string
     */
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

    /**
     * 获取毫秒时间戳
     *
     * @return bool|string
     */
    public static function getMillisecond()
    {
        list($msec, $sec) = explode(' ', microtime());
        $msectime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
        return $msectimes = substr($msectime, 0, 13);
    }

    /**
     * 毫秒转为日期字符串格式
     *
     * @param $millisecond
     * @return string
     */
    public static function millisecondToDate($millisecond)
    {
        $millisecondString = "" . $millisecond;
        $millisecond       = substr($millisecondString, 10);
        $dateStamp         = substr($millisecondString, 0, 10);
        $date              = date('Y-m-d H:i:s', (int)$dateStamp);
        return $date . ".{$millisecond}";
    }
}
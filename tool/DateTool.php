<?php

namespace ParamsValidateMicroServices\tool;

/**
 * Class DateTool
 * @package ParamsValidateMicroServices\tool
 */
class DateTool
{
    /**
     * 获取实时日期
     * 
     * @param bool $milli
     * @return false|string
     */
    public static function getCurrectDate($milli = false)
    {
        $nowStamp = self::getMillisecond();
        $second = substr($nowStamp,0,10);
        $milli = substr($nowStamp,10);
        $date = date("Y-m-d H:i:s",$second);
        if($milli){
            $date .= ".{$milli}";
        }
        return $date;
    }

    /**
     * 分离日期和毫秒
     *
     * @param $date
     * @param $reDate
     * @param $reMillisecond
     * @return bool
     */
    public static function spiltDateAndMillisecond($date, &$reDate, &$reMillisecond)
    {
        $millisecondIndex = strrpos($date, '.');
        if ($millisecondIndex === false) {
            $reDate        = $date;
            $reMillisecond = '';
            return true;
        } else {
            $reDate        = substr($date, 0, $millisecondIndex);
            $reMillisecond = substr($date, $millisecondIndex + 1);
            if (empty($reMillisecond)) {
                $reMillisecond = '';
            }
            return true;
        }
    }

    /**
     * 分离时间戳和毫秒
     *
     * @param $stamp
     * @param $reStamp
     * @param $reMillisecond
     * @return bool
     */
    public static function spiltStampAndMillisecond($stamp, &$reStamp, &$reMillisecond)
    {
        $stamp = "" . $stamp;
        if (strlen($stamp) < 10) {
            return false;
        }
        $reStamp       = (int)substr($stamp, 0, 10);
        $reMillisecond = substr($stamp, 10);
        if (empty($reMillisecond)) {
            $reMillisecond = '';
        } else {
            $reMillisecond = (int)$reMillisecond;
        }
        return true;
    }

    /**
     * 日期长度转换
     *
     * @param $date 格式
     * @param $toBit 转换位数，如果bit小于10就直接返回原数据
     * @return string
     */
    public static function timeBitTransform($date, $toBit)
    {
        if ($toBit < 10) {
            return $date;
        }
        $millisecondIndex = strrpos($date, '.');
        $millisecond      = '';
        $dateString       = '';
        if ($millisecondIndex !== false) {
            $millisecond = substr($date, $millisecondIndex + 1);
            $dateString  = substr($date, 0, $millisecondIndex);
        } else {
            $dateString = $date;
        }
        $dateStamp       = strtotime($dateString);
        $dateStampString = $dateStamp . $millisecond;
        if (($dateStampString) == $toBit) {
            return $date;
        }
        if ($toBit > strlen($dateStampString)) {
            $rest = $toBit - strlen($dateStampString);
            for ($i = 0; $i < $rest; $i++) {
                $millisecond .= '0';
            }
            return $dateString . "." . $millisecond;
        } else {
            $rest        = strlen($dateStampString) - $toBit;
            $millisecond = substr($millisecond, 0, strlen($millisecond) - $rest);
            return $dateString . (empty($millisecond) ? "" : ".{$millisecond}");
        }
    }

    /**
     * 时间戳位数转换
     *
     * @param $timeStamp
     * @param $toBit
     * @return bool|int|string
     */
    public static function timeStampTransform($timeStamp, $toBit)
    {
        if ($toBit < 10) {
            return $timeStamp;
        }
        $dateStamp       = substr("" . $timeStamp, 0, 10);
        $dateStampString = "" . $timeStamp;
        $millisecond     = substr("" . $timeStamp, 10);
        if ($toBit == 10) {
            return $dateStamp;
        } else if (strlen($dateStampString) > $toBit) {
            $rest = strlen($dateStampString) - (strlen($dateStampString) - $toBit);
            return (int)substr($dateStampString, 0, $rest);
        } else if (strlen($dateStampString) < $toBit) {
            $rest = $toBit - strlen($dateStampString);
            for ($i = 0; $i < $rest; $i++) {
                $dateStampString .= '0';
            }
            return (int)$dateStampString;
        }
    }

    /**
     * 判断是否是星期六
     *
     * @param $date
     * @return bool
     */
    public static function isSaturday($date)
    {
        $date = self::timeBitTransform($date, 10);
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
        $date = self::timeBitTransform($date, 10);
        if (date('w', strtotime($date)) === '0') {
            return true;
        }
        return false;
    }

    /**
     * 是否星期一
     *
     * @param $date
     * @return bool
     */
    public static function isMonday($date)
    {
        $date = self::timeBitTransform($date, 10);
        if (date('w', strtotime($date)) === '1') {
            return true;
        }
        return false;
    }

    /**
     * 是否星期二
     *
     * @param $date
     * @return bool
     */
    public static function isTuesday($date)
    {
        $date = self::timeBitTransform($date, 10);
        if (date('w', strtotime($date)) === '2') {
            return true;
        }
        return false;
    }

    /**
     * 是否星期三
     *
     * @param $date
     * @return bool
     */
    public static function isWednesday($date)
    {
        $date = self::timeBitTransform($date, 10);
        if (date('w', strtotime($date)) === '3') {
            return true;
        }
        return false;
    }

    /**
     * 是否星期四
     *
     * @param $date
     * @return bool
     */
    public static function isThursday($date)
    {
        $date = self::timeBitTransform($date, 10);
        if (date('w', strtotime($date)) === '4') {
            return true;
        }
        return false;
    }

    /**
     * 是否星期五
     *
     * @param $date
     * @return bool
     */
    public static function isFriday($date)
    {
        $date = self::timeBitTransform($date, 10);
        if (date('w', strtotime($date)) === '5') {
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
    public static function getTodayEndStamp($millisecondBit = 0)
    {
        $millsecond = '';
        if ($millisecondBit > 0) {
            for ($i = 0; $i < $millisecondBit; $i++) {
                $millsecond .= "0";
            }
        }
        return (int)(strtotime('24:00:00') . $millsecond);
    }

    /**
     * 获取今天开始时间戳
     *
     * @return false|int
     */
    public static function getTodayStartStamp($millisecondBit = 0)
    {
        $millsecond = '';
        if ($millisecondBit > 0) {
            for ($i = 0; $i < $millisecondBit; $i++) {
                $millsecond .= "0";
            }
        }
        return (int)(strtotime('00:00:00') . $millisecond);
    }

    /**
     * 获取今天结束日期
     *
     * @return false|string
     */
    public static function getTodayEndDate($millisecondBit = 0)
    {
        $millsecond = '';
        if ($millisecondBit > 0) {
            for ($i = 0; $i < $millisecondBit; $i++) {
                $millsecond .= "0";
            }
        }
        $dateString = date('Y-m-d H:i:s', self::getTodayEndStamp(0));
        $dateString .= $millsecond === '' ? "" : ".{$millsecond}";
        return $dateString;
    }

    /**
     * 获取今天开始日期
     *
     * @return false|string
     */
    public static function getTodayStartDate($millisecondBit = 0)
    {
        $millsecond = '';
        if ($millisecondBit > 0) {
            for ($i = 0; $i < $millisecondBit; $i++) {
                $millsecond .= "0";
            }
        }
        $dateString = date('Y-m-d H:i:s', self::getTodayStartStamp(0));
        $dateString .= $millsecond === '' ? "" : ".{$millsecond}";
        return $dateString;
    }

    /**
     * 获取今天结束时间戳
     *
     * @return false|int
     */
    public static function getDateEndStamp($date)
    {
        self::spiltStampAndMillisecond($date, $reDate, $reMillisecond);
        $dateYmd   = date('Y-m-d', strtotime($reDate));
        $dateStamp = strtotime($dateYmd . ' 24:00:00');
        if ($reMillisecond !== '') {
            return (int)($dateStamp . $reMillisecond);
        } else {
            return $dateStamp;
        }
    }

    /**
     * 获取今天开始时间戳
     *
     * @return false|int
     */
    public static function getDateStartStamp($date)
    {
        self::spiltStampAndMillisecond($date, $reDate, $reMillisecond);
        $dateYmd   = date('Y-m-d', strtotime($reDate));
        $dateStamp = strtotime($dateYmd . ' 00:00:00');
        if ($reMillisecond !== '') {
            return (int)($dateStamp . $reMillisecond);
        } else {
            return $dateStamp;
        }
    }

    /**
     * 获取今天结束日期
     *
     * @return false|string
     */
    public static function getDateEndDate($date)
    {
        self::spiltDateAndMillisecond($date, $reDate, $reMillisecond);
        $dateStamp  = self::getDateEndStamp($reDate);
        $dateString = date('Y-m-d H:i:s', $dateStamp);
        $dateString .= $reMillisecond === '' ? '' : ".{$reMillisecond}";
        return $dateString;
    }

    /**
     * 获取今天开始日期
     *
     * @return false|string
     */
    public static function getDateStartDate($date)
    {
        self::spiltDateAndMillisecond($date, $reDate, $reMillisecond);
        $dateStamp  = self::getDateStartStamp($reDate);
        $dateString = date('Y-m-d H:i:s', $dateStamp);
        $dateString .= $reMillisecond === '' ? '' : ".{$reMillisecond}";
        return $dateString;
    }

    /**
     * 获取日期年月日
     *
     * @param $date
     * @return mixed
     */
    public static function turnToYmd($date)
    {
        self::spiltDateAndMillisecond($date, $reDate, $reMillisecond);
        return $date('Y-m-d', strtotime($reDate));
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
        self::spiltDateAndMillisecond($date, $reDate, $reMillisecond);
        $dateString = date($foramt, strtotime($reDate));
        $dateString .= $reMillisecond === '' ? '' : ".{$reMillisecond}";
        return $dateString;
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
        return $msectimes = (int)substr($msectime, 0, 13);
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
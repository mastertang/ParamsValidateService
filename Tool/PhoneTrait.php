<?php

namespace ParamsValidateMicroServices\Services;
trait PhoneTrait
{
    public function phoneHandler($condition, $phone)
    {
        if (empty($condition) ||
            (!is_string($condition) && !is_array($condition)) ||
            !is_string($phone)
        ) {
            return false;
        }
        if (!is_array($condition)) {
            $condition = [$condition];
        }
        foreach ($condition as $subCondition) {
            $result       = false;
            $subCondition = strtoupper(trim($subCondition, " "));
            switch ($subCondition) {
                case "PRC":
                    $result = PhoneClassify::is_PRC_Phone($phone);
                    break;
                case "TW":
                    $result = PhoneClassify::is_TW_Phone($phone);
                    break;
                case "HK":
                    $result = PhoneClassify::is_HK_Phone($phone);
                    break;
                case "AM":
                    $result = PhoneClassify::is_AM_Phone($phone);
                    break;
                case "CHINA":
                    $result = PhoneClassify::is_China_Phone($phone);
                    break;
                case "ALL":
                    $result = PhoneClassify::is_All_Phone($phone);
                    break;
                case "CMCC":
                    $result = PhoneClassify::is_CMCC_Phone($phone);
                    break;
                case "CUCC":
                    $result = PhoneClassify::is_CUCC_Phone($phone);
                    break;
                case "CTCC":
                    $result = PhoneClassify::is_CTCC_Phone($phone);
                    break;
                default:
                    break;
            }
            if ($result) {
                return true;
            }
        }
        return false;
    }
}
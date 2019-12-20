<?php

namespace ParamsValidateMicroServices\tool;

/**
 * Class ConditionJudge
 * @package ParamsValidateMicroServices\tool
 */
class ConditionJudge
{
    /**
     * isset 判断
     *
     * @param $data
     * @param $keys
     * @return bool
     */
    public static function issetJudge($data, $keys)
    {
        if (is_string($keys)) {
            return isset($data[$keys]);
        } else if (is_array($keys)) {
            $result = true;
            foreach ($keys as $key) {
                if (!isset($data[$key])) {
                    $result = false;
                    break;
                }
            }
            return $result;
        }
    }

    /**
     * isset且非空
     *
     * @param $data
     * @param $keys
     * @return bool
     */
    public static function issetAndNotEmpty($data, $keys)
    {
        if (is_string($keys)) {
            return isset($data[$keys]) && !empty($data[$keys]);
        } else if (is_array($keys)) {
            $result = true;
            foreach ($keys as $key) {
                if (!isset($data[$key])) {
                    $result = false;
                    break;
                }
                if (empty($data[$key])) {
                    $result = false;
                    break;
                }
            }
            return $result;
        }
    }

    /**
     * 相等
     * @param $data1
     * @param $data2
     * @return bool
     */
    public static function equal($data1, $data2)
    {
        return $data1 == $data2;
    }

    /**
     * 全等
     *
     * @param $data1
     * @param $data2
     * @return bool
     */
    public static function allEqual($data1, $data2)
    {
        return $data1 === $data2;
    }

    /**
     * 等于false
     *
     * @param $data
     * @return bool
     */
    public static function equalFalse($data)
    {
        return $data === false;
    }

    /**
     * 等于true
     *
     * @param $data
     * @return bool
     */
    public static function equalTrue($data)
    {
        return $data === true;
    }

    /**
     * 不相等
     * @param $data1
     * @param $data2
     * @return bool
     */
    public static function notEqual($data1, $data2)
    {
        return $data1 != $data2;
    }

    /**
     * 不全等
     *
     * @param $data1
     * @param $data2
     * @return bool
     */
    public static function notAllEqual($data1, $data2)
    {
        return $data1 !== $data2;
    }

    /**
     * 不等于false
     *
     * @param $data
     * @return bool
     */
    public static function notEqualFalse($data)
    {
        return $data !== false;
    }

    /**
     * 不等于true
     *
     * @param $data
     * @return bool
     */
    public static function notEqualTrue($data)
    {
        return $data !== true;
    }

    /**
     * 全空
     *
     * @param $data
     * @return bool
     */
    public static function totalEmpty($data)
    {
        if (is_numeric($data)) {
            return false;
        }
        return empty($data);
    }

    /**
     * 不全空
     *
     * @param $data
     * @return bool
     */
    public static function totalNotEmpty($data)
    {
        if (is_numeric($data)) {
            return true;
        }
        return empty($data);
    }
}
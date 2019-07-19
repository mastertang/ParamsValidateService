<?php

namespace ParamsValidateMicroServices\services;

use frame\Request;
use ParamsValidateMicroServices\tool\Common;
use ParamsValidateMicroServices\tool\Validate;

/**
 * Class PValService
 * @package ParamsValidateMicroServices\services
 */
class PValService extends Common
{
    /**
     * @var string 错误信息
     */
    public $errMessage = '';

    /**
     * @var null 异常类
     */
    public $exception = null;

    /**
     * @var array 原始数据
     */
    public $originData = [];

    /**
     * @var array 处理数据
     */
    public $handleData = [];

    /**
     * @var array 头信息数据
     */
    public $headerData = [];

    /**
     * @var string 全局方法
     */
    public $globalMethod = Validate::REQUEST_POST;

    /**
     * PValService constructor.
     * @param string $globalMethod
     */
    public function __construct($globalMethod = '')
    {
        $this->globalMethod = $globalMethod;
    }

    /**
     * 服务开始
     *
     * @param $instances
     * @return bool
     */
    public function serviceStart($instances)
    {
        $result = true;
        foreach ($instances as $instance) {
            if ($instance instanceof Validate) {
                try {
                    $result = $instance->validateStart($this->globalMethod);
                    if (!is_null($instance->getHandleBase64Header())) {
                        $this->headerData[$instance->getName()] = $instance->getHandleBase64Header();
                    }
                    $this->originData[$instance->getName()] = $instance->getOriginData();
                    $this->handleData[$instance->getName()] = $instance->getHandleData();
                } catch (\Exception $exception) {
                    $this->errMessage = $exception->getMessage();
                    $this->exception  = $exception;
                    $result           = false;
                    break;
                }
            }
        }
        return $result;
    }

    /**
     * 创建固定验证
     *
     * @param $name
     * @param bool $empty
     * @return Validate
     */
    public static function stringValidate($name, $empty = false)
    {
        $validate = new Validate();
        $validate->name($name);
        $validate->type(Common::T_STRING);
        $validate->length('>', 0);
        if ($empty) {
            $validate->isEmpty();
        }
        return $validate;
    }

    /**
     * 创建固定数字验证
     *
     * @param $name
     * @param bool $empty
     * @return Validate
     */
    public static function digitValidate($name, $empty = false)
    {
        $validate = new Validate();
        $validate->name($name);
        $validate->type(Common::T_INT);
        $validate->range('>', 0);
        if ($empty) {
            $validate->isEmpty();
        }
        return $validate;
    }

    /**
     * 创建固定rawjson数据
     *
     * @param $name
     * @param bool $empty
     * @return Validate
     */
    public static function stringValidateRawJson($name, $empty = false)
    {
        $validate = new Validate();
        $validate->name($name);
        $validate->type(Common::T_STRING);
        $validate->length('>', 0);
        $validate->method(Validate::REQUEST_RAW_JSON);
        if ($empty) {
            $validate->isEmpty();
        }
        return $validate;
    }

    /**
     * 创建固定头信息数据
     *
     * @param $name
     * @param bool $empty
     * @return Validate
     */
    public static function stringValidateHeader($name, $empty = false)
    {
        $validate = new Validate();
        $validate->name($name);
        $validate->type(Common::T_STRING);
        $validate->method(Validate::REQUEST_HEADER);
        $validate->length('>', 0);
        if ($empty) {
            $validate->isEmpty();
        }
        return $validate;
    }


    /**
     * 创建固定rawjson数字验证
     *
     * @param $name
     * @param bool $empty
     * @return Validate
     */
    public static function digitValidateRawJson($name, $empty = false)
    {
        $validate = new Validate();
        $validate->name($name);
        $validate->type(Common::T_INT);
        $validate->method(Validate::REQUEST_RAW_JSON);
        $validate->range('>', 0);
        if ($empty) {
            $validate->isEmpty();
        }
        return $validate;
    }

    /**
     * 创建固定头信息数字验证
     *
     * @param $name
     * @param bool $empty
     * @return Validate
     */
    public static function digitValidateHeader($name, $empty = false)
    {
        $validate = new Validate();
        $validate->name($name);
        $validate->type(Common::T_INT);
        $validate->method(Validate::REQUEST_HEADER);
        $validate->range('>', 0);
        if ($empty) {
            $validate->isEmpty();
        }
        return $validate;
    }
}
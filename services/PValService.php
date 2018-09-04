<?php

namespace ParamsValidateMicroServices\services;

use ParamsValidateMicroServices\tool\Common;
use ParamsValidateMicroServices\tool\Validate;

class PValService extends Common
{
    public $errMessage   = '';
    public $exception    = null;
    public $originData   = [];
    public $handleData   = [];
    public $headerData   = [];
    public $globalMethod = '';

    /*
     * 构造函数
     */
    public function __construct($globalMethod = '')
    {
        $this->globalMethod = $globalMethod;
    }

    /*
     * 构造函数
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

    /*
     * 创建固定验证
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

    /*
     * 创建固定数字验证
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
}
<?php

namespace ParamsValidateMicroServices\services;

use ParamsValidateMicroServices\tool\Common;
use ParamsValidateMicroServices\tool\Validate;

class PValService extends Common
{
    public $errMessage = '';
    public $exception  = null;
    public $originData = [];
    public $handleData = [];
    public $headerData = [];

    //开始处理数据
    public function serviceStart($instances)
    {
        $result = true;
        foreach ($instances as $instance) {
            if ($instance instanceof Validate) {
                try {
                    $result = $instance->validateStart();
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
}
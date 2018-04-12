<?php

namespace ParamsValidateMicroServices\Services;
trait EmptyTrait
{
    public function emptyHandler($condition, $data)
    {
        if (!$condition) {
            return ($data != "0" && $data != false && empty($data)) ? false : true;
        }
        return true;
    }
}
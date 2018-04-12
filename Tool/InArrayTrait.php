<?php

namespace ParamsValidateMicroServices\Services;
trait InArrayTrait
{
    public function inArrayHandler($condition, $data)
    {
        if (!is_array($condition)) {
            $condition = [$condition];
        }
        return in_array($data, $condition);
    }
}
<?php
namespace ParamsValidateMicroServices\Services;
trait NotInArrayTrait
{
    public function notInArrayHandler($condition, $data)
    {
        if (!is_array($condition)) {
            $condition = [$condition];
        }
        return !in_array($data, $condition);
    }
}
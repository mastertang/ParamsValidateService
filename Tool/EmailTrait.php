<?php
namespace ParamsValidateMicroServices\Services;
trait EmailTrait
{
    public function emailHandler($condition, $data)
    {
        if (!is_string($data) || empty($data)) {
            return false;
        }
        $pattern = "/^[a-z]([a-z0-9]*[-_]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[\\.][a-z]{2,3}([\\.][a-z]{2})?$/i";
        return preg_match($pattern,$data);
    }
}
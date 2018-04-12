<?php

namespace ParamsValidateMicroServices\Services;
trait TypeTrait
{

    public function typeHandler($condition)
    {
        $condition = strtolower(trim($condition, " "));
        $result    = true;
        switch ($condition) {
            case "int":
                $result = is_int($condition);
                break;
            case "float":
                $result = is_float($condition);
                break;
            case "double":
                $result = is_double($condition);
                break;
            case "string":
                $result = is_string($condition);
                break;
            case "bool":
            case "boolean":
                $result = is_bool($condition);
                break;
            case "json":
                $data = json_decode($condition);
                if ($data === false) {
                    $result = false;
                }
                break;
            case "numberic":
                $result = is_numeric($condition);
                break;
            default:
                $result = false;
                break;
        }
        return $result;
    }
}
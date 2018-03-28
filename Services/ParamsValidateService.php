<?php

namespace ParamsValidateMicroServices\Services;
class ParamsValidateService
{
    use TypeTrait;
    use EmptyTrait;
    use EmailTrait;
    use PhoneTrait;
    use RangeTrait;
    use LengthTrait;
    use InArrayTrait;
    use NotInArrayTrait;

    /**
     * $config = [
     *      "paramsName" => [
     *          "name1"=>["condition" => ["type"=>"int|float|double|numberic","empty"=>true,"range"=>["<:10",">=:20"],"in"=>[1,2,3,4],"!in"=>[1,2,3,4]],"tail_handle"=>function()],
     *          "name2"=>["condition" => ["type"=>"string","len"=>["=:10",">:100"],"trim"=>" ","phone"=>true,"email"=>true,"http"=>"post"],"tail_handle"=>function()],
     *          "name3"=>["condition" => ["type"=>"bool|boolean","http"=>"get"],"tail_handle"=>function()]],
     *          "name4"=>["condition" => ["type"=>"json","decode"=>"array|obj"],"tail_handle"=>function()]],
     *      ],
     *      "data" => []
     * ]
     * @param $config
     * @return boolean
     */
    public function serviceStart($config)
    {
        $return = ["pass" => true, "message" => "All pass!"];
        if (!isset($config["paramsName"]) ||
            !is_array($config["paramsName"]) ||
            empty($config["paramsName"])
        ) {
            $return["pass"]    = false;
            $return["message"] = "Config params can not be empty!";
            return $return;
        }
        $defaultData = isset($config["data"]) ? $config["data"] : [];
        if (!is_array($defaultData)) {
            $defaultData = [];
        }
        $paramsCondition = $config["paramsName"];
        $newData         = [];
        foreach ($paramsCondition as $paramName => $subParam) {
            if ($subParam instanceof \Closure) {
                $result = call_user_func_array($subParam, [$paramName]);
                if ($result === false) {
                    $return = [
                        "pass"    => false,
                        "message" => "Param '{$paramName}' validate failed!"
                    ];
                    break;
                }
            } else {
                if (isset($subParam["condition"])) {
                    $data = "";
                    $condition = $subParam["condition"];
                    if (!empty($defaultData) && isset($defaultData[$paramName])) {
                        $data = $defaultData[$paramName];
                    } else {
                        $type = "post";
                        if (isset($condition["http"]) && in_array(strtolower($condition["http"]), ["post", "get"])) {
                            $type = strtolower($condition["http"]);
                        }
                        if ($type == "post") {
                            $data = $_POST[$paramName];
                        } else {
                            $data = $_GET[$paramName];
                        }
                    }
                    if (isset($condition["trim"])) {
                        $data = trim($data, $condition["trim"]);
                        unset($condition["trim"]);
                    }
                    $newData[$paramName] = $data;
                    if (is_array($condition) && !empty($condition)) {
                        foreach ($condition as $key => $value) {
                            $result = $this->conditionHandler($key, $value, $data);
                            if ($result === false) {
                                $return = [
                                    "pass"    => false,
                                    "message" => "Param '{$paramName}' error at validate '{$key}'"
                                ];
                                break;
                            }
                        }
                    }
                    if (isset($subParam["tail_handle"]) && $subParam["tail_handle"] instanceof \Closure) {
                        $newData[$paramName] = call_user_func_array($subParam["tail_handle"], [$paramName, $data]);
                    }
                }
            }
        }
        if ($return["pass"]) {
            $return["newData"] = $newData;
        }
        return $return;
    }

    /**
     * @param $key
     * @param $condition
     * @return bool
     */
    public function conditionHandler($key, $condition, $data)
    {
        $result = true;
        switch ($key) {
            case "type":
                $result = $this->typeHandler($condition);
                break;
            case "empty":
                $result = $this->emptyHandler($condition, $data);
                break;
            case "range":
                $result = $this->rangeHandler($condition, $data);
                break;
            case "in":
                $result = $this->inArrayHandler($condition, $data);
                break;
            case "!in":
                $result = $this->notInArrayHandler($condition, $data);
                break;
            case "len":
                $result = $this->lengthHandler($condition, $data);
                break;
            case "phone":
                $result = $this->phoneHandler($condition, $data);
                break;
            case "email":
                $result = $this->emailHandler($condition, $data);
                break;
            default:
                break;
        }
        return $result;
    }
}
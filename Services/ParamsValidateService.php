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
     *          "name1"=>["type"=>"int|float|double|numberic","empty"=>true,"range"=>["<:10",">=:20"],"in"=>[1,2,3,4],"!in"=>[1,2,3,4]],
     *          "name2"=>["type"=>"string","len"=>["=:10",">:100"],"trim"=>" ","phone"=>true,"email"=>true,],
     *          "name3"=>["type"=>"bool|boolean"],
     *          "name4"=>["type"=>"json"],
     *      ],
     *      "type"=> "post|get"
     * ]
     * @param $config
     * @return boolean
     */
    public function serviceStart($config)
    {
        if (!isset($config["paramsName"]) ||
            !is_array($config["paramsName"]) ||
            empty($config["paramsName"]) ||
            !isset($config["type"])
        ) {
            return false;
        }
        $type = strtolower(trim($config["type"]));
        if (!in_array($type, ["post", "get"])) {
            return false;
        }
        $paramsCondition = $config["paramsName"];
        foreach ($paramsCondition as $paramName => $condition) {
            $data = "";
            if ($type == "post") {
                $data = $_POST[$paramName];
            } elseif ($type == "get") {
                $data = $_GET[$paramName];
            }
            if (isset($condition["trim"])) {
                $data = trim($data, $condition["trim"]);
                unset($condition["trim"]);
            }
            $result = true;
            if (is_array($condition) && !empty($condition)) {
                foreach ($condition as $key => $value) {
                    $result = $this->conditionHandler($key, $value, $data);
                    if ($result === false) {
                        return false;
                        break;
                    }
                }
            }
        }
        return true;
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
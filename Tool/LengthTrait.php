<?php
namespace ParamsValidateMicroServices\Services;
trait LengthTrait
{
    public function lengthHandler($condition, $data)
    {
        /**
         * "range"=>["<"=>[10,20,60],">="=>[25,63,5.213]]
         */
        if (!is_array($condition) || empty($condition) || !is_string($data)) {
            return false;
        }
        $data = strlen($data);
        $result = true;
        foreach ($condition as $co => $limits) {
            if (!is_array($limits)) {
                $limits = [$limits];
            }
            $result = true;
            foreach ($limits as $subLimit) {
                if (is_numeric($subLimit)) {
                    switch ($co) {
                        case "=":
                            $result = ($data == $subLimit);
                            break;
                        case "===":
                            $result = ($data === $subLimit);
                            break;
                        case "<":
                            $result = ($data < $subLimit);
                            break;
                        case "<=":
                            $result = ($data <= $subLimit);
                            break;
                        case ">":
                            $result = ($data > $subLimit);
                            break;
                        case ">=":
                            $result = ($data >= $subLimit);
                            break;
                        case "!=":
                            $result = ($data != $subLimit);
                            break;
                        case "<>":
                            $result = ($data <> $subLimit);
                            break;
                        default:
                            break;
                    }
                    if (!$result) {
                        break;
                    }
                }
            }
            if (!$result) {
                return $result;
            }
        }
        return $result;
    }
}
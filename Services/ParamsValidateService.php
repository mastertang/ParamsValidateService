<?php

namespace ParamsValidateMicroServices\Services;

use ParamsValidateMicroServices\Tool\Common;

class ParamsValidateService extends Common
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
     *      "params' => [
     *          'name1'=>['c' => ['type'=>'int|float|double|numberic','empty'=>true,'range'=>['<:10','>=:20'],'in'=>[1,2,3,4],'!in'=>[1,2,3,4]],'t'=>function()],
     *          'name2'=>['c' => ['type'=>'string','len'=>['=:10','>:100'],'trim'=>' ','phone'=>true,'email'=>true,'http'=>'post'],'t'=>function()],
     *          'name3'=>['c' => ['type'=>'bool|boolean','http'=>'get'],'t'=>function()]],
     *          'name4'=>['c' => ['type'=>'json'],'t'=>function()]],
     *      ],
     *      'data' => []
     * ]
     * @param $config
     * @return array
     */
    public function serviceStart($config)
    {
        $return = ['pass' => true, 'message' => 'All pass!'];
        if (!isset($config['params']) ||
            !is_array($config['params']) ||
            empty($config['params'])
        ) {
            $return['pass']    = false;
            $return['message'] = 'Config params can not be empty!';
            return $return;
        }
        $defaultData = isset($config['data']) ? $config['data'] : [];
        if (!is_array($defaultData)) {
            $defaultData = [];
        }
        $paramsCondition = $config['params'];
        $newData         = [];
        foreach ($paramsCondition as $paramName => $subParam) {
            if ($subParam instanceof \Closure) {
                $result = call_user_func_array($subParam, [$paramName]);
                if ($result === false) {
                    $return = [
                        'pass'    => false,
                        'message' => "Param '{$paramName}' validate failed!"
                    ];
                    break;
                }
            } else {
                if (isset($subParam['c'])) {
                    $data      = '';
                    $condition = $subParam['c'];
                    if (!empty($defaultData) && isset($defaultData[$paramName])) {
                        $data = $defaultData[$paramName];
                    } else {
                        $type = 'post';
                        if (isset($condition['http']) && in_array(strtolower($condition['http']), ['post', 'get'])) {
                            $type = strtolower($condition['http']);
                        }
                        if ($type == 'post') {
                            $data = $_POST[$paramName];
                        } else {
                            $data = $_GET[$paramName];
                        }
                    }
                    if (isset($condition['trim'])) {
                        $data = trim($data, $condition['trim']);
                        unset($condition['trim']);
                    }
                    $newData[$paramName] = $data;
                    if (is_array($condition) && !empty($condition)) {
                        foreach ($condition as $key => $value) {
                            $result = $this->conditionHandler($key, $value, $data);
                            if ($result === false) {
                                $return = [
                                    'pass'    => false,
                                    'message' => "Param '{$paramName}' error at validate '{$key}'"
                                ];
                                break;
                            }
                        }
                    }
                    if (isset($subParam['t']) && $subParam['t'] instanceof \Closure) {
                        $newData[$paramName] = call_user_func_array($subParam['t'], [$paramName, $data]);
                    }
                }
            }
        }
        if ($return['pass']) {
            $return['newData'] = $newData;
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
            case self::C_TYPE:
                $result = $this->typeHandler($condition);
                break;
            case self::C_EMPTY:
                $result = $this->emptyHandler($condition, $data);
                break;
            case self::C_RANGE:
                $result = $this->rangeHandler($condition, $data);
                break;
            case self::C_IN:
                $result = $this->inArrayHandler($condition, $data);
                break;
            case self::C_NOT_IN:
                $result = $this->notInArrayHandler($condition, $data);
                break;
            case self::C_LEN:
                $result = $this->lengthHandler($condition, $data);
                break;
            case self::C_PHONE:
                $result = $this->phoneHandler($condition, $data);
                break;
            case self::C_EMAIL:
                $result = $this->emailHandler($condition, $data);
                break;
            default:
                break;
        }
        return $result;
    }
}
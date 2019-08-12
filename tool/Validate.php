<?php

namespace ParamsValidateMicroServices\tool;

/**
 * Class Validate
 * @package ParamsValidateMicroServices\tool
 */
class Validate extends ValidateBase
{

    /**
     * 开始验证参数
     *
     * @param $httpMethod
     * @return bool
     * @throws \Exception
     */
    public function validateStart($httpMethod)
    {
        if (empty($this->name)) {
            throw new \Exception("参数名不能为空！");
        }
        $this->achieveParams($httpMethod);
        //判断是否为空
        if ($this->empty === true) {
            if (is_null($this->data) || $this->data === '') {
                if (!is_null($this->defaultData)) {
                    $this->handleData = $this->data = $this->defaultData;
                }
                if (is_null($this->data)) {
                    $this->data = $this->handleData = '';
                }
                return true;
            }
        } else {
            if (is_null($this->data) || $this->data === '') {
                if (!is_null($this->defaultData)) {
                    $this->handleData = $this->data = $this->defaultData;
                    return true;
                }
                throw new \Exception($this->name . " 数据不能为空！");
            }
        }
        $this->handleData = $this->data;
        //前置处理
        if ($this->offensiveHandle instanceof \Closure) {
            $this->handleData = call_user_func_array($this->offensiveHandle, [$this->handleData]);
        }
        //判断类型
        if (!empty($this->type)) {
            $this->paramsTypeCheck();
        }
        //解码base64
        if ($this->base64DecodeBefore === true) {
            $this->decodeBase64();
        }
        //判断长度
        if (!empty($this->length)) {
            $this->lengthCheck();
        }
        //url解码数据
        if ($this->urlDecode === true) {
            $decodeUrl = urldecode($this->handleData);
            if ($this->urlDecodeHandle instanceof \Closure) {
                call_user_func_array($this->urlDecodeHandle, [$decodeUrl]);
            } else {
                $this->handleData = $decodeUrl;
            }
        }
        //判断值范围
        if (!empty($this->range)) {
            $this->rangeCheck();
        }
        //处理字符串中的一些特殊字符
        if (!empty($this->trim)) {
            $this->trimChar();
        }
        //检测在值数组中
        if (!empty($this->inArray)) {
            $this->inValueArray();
        }
        //检测不在在值数组中
        if (!empty($this->notInArray)) {
            $this->notInValueArray();
        }
        //检测是否是ip地址
        if ($this->ip === true) {
            $this->isIpString();
        }
        //检测是否是邮箱
        if ($this->email === true) {
            $this->isEmailString();
        }
        //检测是否是网络地址
        if ($this->url === true) {
            $this->isUrlString();
        }
        //检测电话格式
        if (!empty($this->phone)) {
            $this->isPhoneString();
        }
        //截取base64数据的头部
        if ($this->spiltBase64Header === true) {
            $this->getBase64Header();
        }
        //解码base64
        if ($this->base64DecodeBehind === true) {
            $this->decodeBase64();
        }
        //是否要解析json
        if ($this->jsonDecode === true) {
            $jsonData = json_decode($this->handleData, true);
            if ($jsonData === false || $jsonData == null) {
                throw new \Exception($this->name . " not a correct json string!");
            }
            if ($this->decodeJsonHandle instanceof \Closure) {
                call_user_func_array($this->decodeJsonHandle, [$jsonData]);
            } else {
                $this->handleData = $jsonData;
            }
        }
        //文件保存处理
        if ($this->saveFileData === true) {
            if ($this->saveFileHandle instanceof \Closure) {
                call_user_func_array($this->saveFileHandle, $this->handleData);
            }
        }
        //后置处理
        if ($this->defensiveHandle instanceof \Closure) {
            $this->handleData = call_user_func_array($this->defensiveHandle, [$this->handleData]);
        }
        return true;
    }

    /**
     * 处理字符串中的一些特殊字符
     */
    protected function trimChar()
    {
        $this->handleData = trim($this->handleData, $this->trim);
    }

    /**
     * 获取base64Header
     *
     */
    protected function getBase64Header()
    {
        $index = strpos($this->handleData, ',');
        if ($index !== false) {
            $this->base64Header = substr($this->handleData, 0, $index);
            if ($this->spiltBase64HeaderHandle instanceof \Closure) {
                call_user_func_array($this->spiltBase64HeaderHandle, [$this->base64Header]);
            }
            $this->handleData = substr($this->handleData, $index + 1);
        }
    }

    /**
     * 解码base64
     *
     * @throws \Exception
     */
    protected function decodeBase64()
    {
        $decodeData = base64_decode($this->handleData);
        if ($this->base64DecodeHandle instanceof \Closure) {
            call_user_func_array($this->base64DecodeHandle, [$decodeData]);
        } else {
            $this->handleData = $decodeData;
        }
        if ($this->handleData === false) {
            throw new \Exception($this->name . " decode base64 falied!");
        }
    }

    /**
     * 检测是否是否手机格式
     *
     * @throws \Exception
     */
    public function isPhoneString()
    {
        if (empty($this->phone)) {
            throw new \Exception($this->name . "'s phone condition empty!");
        }
        $phoneType = $this->phone;
        if (!is_array($phoneType)) {
            $phoneType = [$this->phone];
        }
        $result = false;
        foreach ($phoneType as $subCondition) {
            switch ($subCondition) {
                case Common::P_PRC:
                    $result = PhoneClassify::is_PRC_Phone($this->handleData);
                    break;
                case Common::P_TW:
                    $result = PhoneClassify::is_TW_Phone($this->handleData);
                    break;
                case Common::P_HK:
                    $result = PhoneClassify::is_HK_Phone($this->handleData);
                    break;
                case Common::P_AM:
                    $result = PhoneClassify::is_AM_Phone($this->handleData);
                    break;
                case Common::P_CHINA:
                    $result = PhoneClassify::is_China_Phone($this->handleData);
                    break;
                case Common::P_ALL:
                    $result = PhoneClassify::is_All_Phone($this->handleData);
                    break;
                case Common::P_CMCC:
                    $result = PhoneClassify::is_CMCC_Phone($this->handleData);
                    break;
                case Common::P_CUCC:
                    $result = PhoneClassify::is_CUCC_Phone($this->handleData);
                    break;
                case Common::P_CTCC:
                    $result = PhoneClassify::is_CTCC_Phone($this->handleData);
                    break;
                default:
                    break;
            }
        }
        if ($result === false) {
            throw new \Exception($this->name . " not a phone number!");
        }
    }

    /**
     * 获取原始数据
     *
     * @return null
     */
    public function getOriginData()
    {
        return $this->data;
    }

    /**
     * 获取处理后的数据
     *
     * @return null
     */
    public function getHandleData()
    {
        return $this->handleData;
    }

    /**
     * 检测在值数组中
     *
     * @throws \Exception
     */
    protected function inValueArray()
    {
        if (!is_array($this->inArray) || !in_array($this->handleData, $this->inArray)) {
            throw  new \Exception($this->name . " not in the default array!");
        }
    }

    /**
     * 检测不在在值数组中
     *
     * @throws \Exception
     */
    protected function notInValueArray()
    {
        if (!is_array($this->notInArray) || in_array($this->handleData, $this->notInArray)) {
            throw  new \Exception($this->name . " in the default array!");
        }
    }

    /**
     * url格式检测
     *
     * @throws \Exception
     */
    protected function isUrlString()
    {
        if (filter_var($this->handleData, FILTER_VALIDATE_URL) === false) {
            throw  new \Exception($this->name . " not a url address!");
        }
    }

    /**
     * 邮箱格式检测
     *
     * @throws \Exception
     */
    protected function isEmailString()
    {
        if (filter_var($this->handleData, FILTER_VALIDATE_EMAIL) === false) {
            throw  new \Exception($this->name . " not a email!");
        }
    }

    /**
     * ip地址格式检测
     *
     * @throws \Exception
     */
    protected function isIpString()
    {
        $pat = "/^(((1?\d{1,2})|(2[0-4]\d)|(25[0-5]))\.){3}((1?\d{1,2})|(2[0-4]\d)|(25[0-5]))$/";
        if (preg_match($pat, $this->handleData) == 0) {
            throw new \Exception($this->name . " not ip!");
        }
    }

    /**
     * 长度检测
     *
     * @throws \Exception
     */
    protected function lengthCheck()
    {
        if (!is_array($this->length)) {
            throw new \Exception($this->name . " length condition error!");
        }
        foreach ($this->length as $row) {
            $result = true;
            if (!isset($row['operator'], $row['value'])) {
                throw new \Exception($this->name . " length condition error!");
            }
            $value  = $row['value'];
            $length = strlen($this->handleData);
            switch ($row['operator']) {
                case Common::O_EQ:
                    if (!($value == $length)) $result = false;
                    break;
                case Common::O_ALL_EQ:
                    if (!($value === $length)) $result = false;
                    break;
                case Common::O_NO_EQ:
                    if ($value == $length) $result = false;
                    break;
                case Common::O_ALL_NO_EQ:
                    if ($value === $length) $result = false;
                    break;
                case Common::O_BIG:
                    if (!($length > $value)) $result = false;
                    break;
                case Common::O_BIG_AN_EQ:
                    if (!($length >= $value)) $result = false;
                    break;
                case Common::O_SMALL:
                    if (!($length < $value)) $result = false;
                    break;
                case Common::O_SMALL_AN_EQ:
                    if (!($length <= $value)) $result = false;
                    break;
                case Common::O_BS_EQ:
                    if ($value == $length || $value === $length) $result = false;
                    break;
                default:
                    break;
            }
            if ($result === false) {
                throw new \Exception($this->name . " length error!");
            }
        }
    }

    /**
     * 检测数值访问
     *
     * @throws \Exception
     */
    protected function rangeCheck()
    {
        if (!is_array($this->range)) {
            throw new \Exception($this->name . " range condition error!");
        }
        foreach ($this->range as $row) {
            $result = true;
            if (!isset($row['operator'], $row['value'])) {
                throw new \Exception($this->name . " range condition error!");
            }
            $value = $row['value'];
            switch ($row['operator']) {
                case Common::O_EQ:
                    if (!($value == $this->handleData)) $result = false;
                    break;
                case Common::O_ALL_EQ:
                    if (!($value === $this->handleData)) $result = false;
                    break;
                case Common::O_NO_EQ:
                    if ($value == $this->handleData) $result = false;
                    break;
                case Common::O_ALL_NO_EQ:
                    if ($value === $this->handleData) $result = false;
                    break;
                case Common::O_BIG:
                    if (!($this->handleData > $value)) $result = false;
                    break;
                case Common::O_BIG_AN_EQ:
                    if (!($this->handleData >= $value)) $result = false;
                    break;
                case Common::O_SMALL:
                    if (!($this->handleData < $value)) $result = false;
                    break;
                case Common::O_SMALL_AN_EQ:
                    if (!($this->handleData <= $value)) $result = false;
                    break;
                case Common::O_BS_EQ:
                    if ($value == $this->handleData || $value === $this->handleData) $result = false;
                    break;
                default:
                    break;
            }
            if ($result === false) {
                throw new \Exception($this->name . " range error!");
            }
        }
    }

    /**
     * 类型检测
     *
     * @return float|int
     * @throws \Exception
     */
    protected function paramsTypeCheck()
    {
        switch ($this->type) {
            case Common::T_INT:
                if (!is_numeric($this->handleData) || $this->handleData{0} == '.' || !is_int($this->handleData = $this->stringToNumeric($this->handleData))) {
                    throw new \Exception($this->name . "'s type not is int!");
                }
                break;
            case Common::T_FLOAT:
                if (!is_numeric($this->handleData) || $this->handleData{0} == '.' || !is_float($this->handleData = $this->stringToNumeric($this->handleData))) {
                    throw new \Exception($this->name . "'s type not is float!");
                }
                break;
            case Common::T_DOUBLE:
                if (!is_numeric($this->handleData) || $this->handleData{0} == '.' || !is_float($this->handleData = $this->stringToNumeric($this->handleData))) {
                    throw new \Exception($this->name . "'s type not is double!");
                }
                break;
            case Common::T_NUMBERIC:
                if (!is_numeric($this->handleData) || $this->handleData{0} == '.') {
                    throw new \Exception($this->name . "'s type not is int or float or double or numberic!");
                }
                return $this->stringToNumeric($this->handleData);
                break;
            case Common::T_STRING:
                if (!is_string($this->handleData)) {
                    throw new \Exception($this->name . "'s type not is string!");
                }
                break;
            case Common::T_BOOL:
            case Common::T_BOOLEAN:
                if ($this->handleData == '1' || $this->handleData == 1) {
                    $this->handleData = true;
                }
                if ($this->handleData === '0' || $this->handleData === 0) {
                    $this->handleData = false;
                }
                if (!is_bool($this->handleData)) {
                    throw new \Exception($this->name . "'s type not is boolean!");
                }
                break;
            default:
                throw new \Exception("type condition error!");
                break;
        }
    }

    /**
     * 字符串转数组
     *
     * @param $value
     * @return float|int
     */
    protected function stringToNumeric($value)
    {
        $potPosition = strpos($value, '.');
        if ($potPosition === false) {
            return (int)$value;
        } else {
            return (float)$value;
        }
    }
}
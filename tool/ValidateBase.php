<?php

namespace ParamsValidateMicroServices\tool;

/**
 * Class ValidateBase
 * @package ParamsValidateMicroServices\tool
 */
class ValidateBase
{
    /**
     * 请求方法post
     */
    const REQUEST_POST = 'POST';

    /**
     * 请求方法get
     */
    const REQUEST_GET = 'GET';

    /**
     * 请求方法json
     */
    const REQUEST_RAW_JSON = 'RAW_JSON';

    /**
     * 请求头信息
     */
    const REQUEST_HEADER = 'HEADER';

    // ip判定
    protected $ip = false;
    // 网络地址
    protected $url = false;
    // 参数名称
    protected $name = '';
    // 参数数据
    protected $data = null;
    // 处理数据
    protected $handleData = null;
    // base64数据头部
    protected $base64Header = null;
    // 类型
    protected $type = '';
    // 处理字符串中的一些特殊字符
    protected $trim = false;
    // 值范围
    protected $range = false;
    // 是否空
    protected $empty = false;
    // 邮箱判定
    protected $email = false;
    // 电话判定
    protected $phone = false;
    // 长度
    protected $length = false;
    // 方法类型
    protected $method = '';
    // 在值数组内
    protected $inArray = false;
    // 默认值
    protected $defaultData = null;
    // 不在值数组内
    protected $notInArray = false;
    // decode Json 字符串
    protected $jsonDecode = false;
    // 解json字符串处理
    protected $decodeJsonHandle = null;
    // 是否要进行urlDecode
    protected $urlDecode = false;
    // url解码后数据处理
    protected $urlDecodeHandle = null;
    // 是否要base64decode
    protected $base64DecodeBefore = false;
    // 是否要base64decode
    protected $base64DecodeBehind = false;
    // 处理decode后的数据
    protected $base64DecodeHandle = null;
    // 截取base64数据头部
    protected $spiltBase64Header = false;
    // 截取头部处理
    protected $spiltBase64HeaderHandle = null;
    // 预先处理
    protected $offensiveHandle = null;
    // 验证后处理
    protected $defensiveHandle = null;
    // 处理分隔符字符串
    protected $spiltString = false;
    // 分隔符
    protected $spiltNeedle = '';
    // 分隔后数据处理
    protected $spiltStringHandle = null;
    // 保存文件数据
    protected $saveFileData = false;
    // 保存文件处理函数
    protected $saveFileHandle = null;
    // 检查是否中文姓名
    protected $chineseName = false;
    // 检查是否是身份证号
    protected $idcode = false;
    // 是否addslashes过滤sql注入
    protected $addslashes = false;
    // 是否html过滤xss劫持
    protected $htmlspecialchars = false;

    /**
     * 是否检测html劫持
     *
     * @return $this
     */
    public function checkHtmlInjection()
    {
        $this->htmlspecialchars = true;
        return $this;
    }

    /**
     * 是否检测sql注入
     *
     * @return $this
     */
    public function checkSqlInjection()
    {
        $this->addslashes = true;
        return $this;
    }

    /**
     * 检查是否是中文姓名
     *
     * @return $this
     */
    public function checkChineseName()
    {
        $this->chineseName = true;
        return $this;
    }

    /**
     * 检查是否是身份证号
     *
     * @return $this
     */
    public function checkIdcode()
    {
        $this->idcode = true;
        return $this;
    }

    /**
     * 解码json字符串
     *
     * @return mixed
     */
    public function decodeJson()
    {
        return json_decode($this->handleData, true);
    }

    /**
     * 获取base64header
     *
     * @return null
     */
    public function getHandleBase64Header()
    {
        return $this->base64Header;
    }

    /**
     * 是否保存文件数据
     *
     * @param null $saveHandle
     * @return $this
     */
    public function isSaveFile($saveHandle = null)
    {
        if ($saveHandle instanceof \Closure) {
            $this->saveFileData = true;
        }
        return $this;
    }

    /**
     * 是否处理分隔字符串
     *
     * @param string $needle
     * @param null $spiltHandle
     * @return $this
     */
    public function isSpiltString($needle = '', $spiltHandle = null)
    {
        $this->spiltNeedle = $needle;
        $this->spiltString = true;
        if ($spiltHandle instanceof \Closure) {
            $this->spiltStringHandle = $spiltHandle;
        }
        return $this;
    }

    /**
     * 获取参数名字
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * 处理字符串中的一些特殊字符
     *
     * @param $needle
     * @return $this
     */
    public function trimString($needle)
    {
        $this->trim = $needle;
        return $this;
    }

    /**
     * 是否截取base64数据的头部
     *
     * @param null $headerHandle
     * @return $this
     */
    public function base64Header($headerHandle = null)
    {
        if ($headerHandle instanceof \Closure) {
            $this->spiltBase64HeaderHandle = $headerHandle;
        }
        $this->spiltBase64Header = true;
        return $this;
    }

    /**
     * 长度限制
     *
     * @param $loperator
     * @param $lvalue
     * @param string $roperator
     * @param string $rvalue
     * @return $this
     */
    public function length($loperator, $lvalue, $roperator = '', $rvalue = '')
    {
        $data = [
            ['operator' => $loperator, 'value' => $lvalue]
        ];
        if (!empty($roperator)) {
            $data[] = [
                'operator' => $roperator,
                'value'    => $rvalue
            ];
        }
        $this->length = $data;
        return $this;
    }

    /**
     * base64解码
     *
     * @param null $decodeHandle
     * @return $this
     */
    public function base64DecodeBefore($decodeHandle = null)
    {
        $this->base64DecodeBefore = true;
        if ($decodeHandle instanceof \Closure) {
            $this->base64DecodeHandle = $decodeHandle;
        }
        return $this;
    }

    /**
     * base64解码
     *
     * @param null $decodeHandle
     * @return $this
     */
    public function base64DecodeBehind($decodeHandle = null)
    {
        if ($decodeHandle instanceof \Closure) {
            $this->base64DecodeHandle = $decodeHandle;
        }
        $this->base64DecodeBehind = true;
        return $this;
    }

    /**
     * 值访问限制
     *
     * @param $loperator
     * @param $lvalue
     * @param string $roperator
     * @param string $rvalue
     * @return $this
     */
    public function range($loperator, $lvalue, $roperator = '', $rvalue = '')
    {
        $data = [
            ['operator' => $loperator, 'value' => $lvalue]
        ];
        if (!empty($roperator)) {
            $data[] = [
                'operator' => $roperator,
                'value'    => $rvalue
            ];
        }
        $this->range = $data;
        return $this;
    }

    /**
     * 检测url
     *
     * @return $this
     */
    public function urlCheck()
    {
        $this->url = true;
        return $this;
    }

    /**
     * 是否可以为空
     *
     * @return $this
     */
    public function isEmpty()
    {
        $this->empty = true;
        return $this;
    }

    /**
     * 检测邮箱格式
     *
     * @return $this
     */
    public function emailCheck()
    {
        $this->email = true;
        return $this;
    }

    /**
     * 默认值
     *
     * @param $value
     * @return $this
     */
    public function defaultData($value)
    {
        $this->defaultData = $value;
        return $this;
    }

    /**
     * 是否在数组中
     *
     * @param $array
     * @return $this
     */
    public function inArray($array)
    {
        if (is_array($array)) {
            $this->inArray = $array;
        }
        return $this;
    }

    /**
     * 是否不在数组中
     *
     * @param $array
     * @return $this\
     */
    public function notInArray($array)
    {
        if (is_array($array)) {
            $this->notInArray = $array;
        }
        return $this;
    }

    /**
     * 是否要进行urldecode
     *
     * @param null $urlHandle
     * @return $this
     */
    public function urlDecode($urlHandle = null)
    {
        $this->urlDecode = true;
        if ($urlHandle instanceof \Closure) {
            $this->urlDecodeHandle = $urlHandle;
        }
        return $this;
    }

    /**
     * 是否要用jsonDecode
     *
     * @param null $jsonHandle
     * @return $this
     */
    public function jsonDecode($jsonHandle = null)
    {
        if ($jsonHandle instanceof \Closure) {
            $this->decodeJsonHandle = $jsonHandle;
        }
        $this->jsonDecode = true;
        return $this;
    }

    /**
     * 检测手机格式
     *
     * @param $phoneArea
     * @return $this
     */
    public function phoneCheck($phoneArea)
    {
        $this->phone = $phoneArea;
        return $this;
    }

    /**
     * 设置参数名字
     *
     * @param $name
     * @return $this
     */
    public function name($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * 设置类型
     *
     * @param $type
     * @return $this
     */
    public function type($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * 请求方法
     *
     * @param $method
     * @return $this
     */
    public function method($method)
    {
        $this->method = strtoupper($method);
        return $this;
    }

    /**
     * 获取参数值
     *
     * @param $httpMethod
     */
    public function achieveParams($httpMethod)
    {
        $methodDefaultList = [
            self::REQUEST_POST,
            self::REQUEST_GET,
            self::REQUEST_RAW_JSON,
            self::REQUEST_HEADER
        ];
        if (empty($this->method) || !in_array($this->method, $methodDefaultList)) {
            if (!empty($httpMethod) && in_array(strtoupper($httpMethod), $methodDefaultList)) {
                $this->method = strtoupper($httpMethod);
            }
        }
        switch ($this->method) {
            case self::REQUEST_GET:
                if (isset($_GET[$this->name])) {
                    $this->data = $_GET[$this->name];
                }
                break;
            case self::REQUEST_RAW_JSON:
                $raw = json_decode(file_get_contents("php://input"), true);
                if (is_array($raw) && isset($raw[$this->name])) {
                    $this->data = $raw[$this->name];
                }
                break;
            case self::REQUEST_HEADER:
                $headerMsg = NetTool::getHttpHeader($this->name);
                if ($headerMsg !== false) {
                    $this->data = $headerMsg;
                }
                break;
            case self::REQUEST_POST:
            default:
                if (isset($_POST[$this->name])) {
                    $this->data = $_POST[$this->name];
                }
                break;
        }
    }

    /**
     * 设置data先置处理
     *
     * @param $handle
     * @return $this
     */
    public function offensiveHandle($handle)
    {
        if ($handle instanceof \Closure) {
            $this->offensiveHandle = $handle;
        }
        return $this;
    }

    /**
     * 设置data后置处理
     *
     * @param $handle
     * @return $this
     */
    public function defensiveHandle($handle)
    {
        if ($handle instanceof \Closure) {
            $this->defensiveHandle = $handle;
        }
        return $this;
    }

    /**
     * 判断ip
     *
     * @return $this
     */
    public function ipCheck()
    {
        $this->ip = true;
        return $this;
    }
}
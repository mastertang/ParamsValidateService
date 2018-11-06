<?php

namespace ParamsValidateMicroServices\tool;

class NetTool
{
    /*
     * 为地址添加版本号
     */
    public static function urlAddVersion($url, $paramsName = 'v')
    {
        return $url . '?' . $paramsName . '=' . uniqid();
    }
    
    //获取客户端ip地址
    public static function getClientIpAddress()
    {
        if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
            $ip = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
            $ip = getenv('REMOTE_ADDR');
        } elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        $res = preg_match('/[\d\.]{7,15}/', $ip, $matches) ? $matches [0] : false;
        return $res;
    }

    //curl请求
    public static function curlRequest($host, $method, $querys, $body, $headers, $timeOut = 20)
    {
        $url  = self::urlAppend($host, $querys);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_TIMEOUT, $timeOut);
        if (1 == strpos("$" . $host, "https://")) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        if (strtoupper($method) == 'POST') {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
        }
        $dataString = curl_exec($curl);
        $errorCode  = curl_errno($curl);
        if (empty($dataString))
            return false;
        else {
            if ($errorCode === 0) {
                return $dataString;
            } else
                return false;
        }
    }

    //地址添加Get参数
    public static function urlAppend($url, $data)
    {
        if (!is_array($data)) {
            return $url;
        }
        $query = urldecode(http_build_query($data));
        $url   .= (strpos($url, '?') === false) ? "?{$query}" : "&{$query}";
        return $url;
    }

    //地址由http转为https
    public static function httpToHttps($url)
    {
        $url = ltrim($url, ' ');
        if (strpos($url, 'http://') === 0) {
            $url = 'https://' . substr($url, 7);
        }
        return $url;
    }

    //地址由https转为http
    public static function httpsToHttp($url)
    {
        $url = ltrim($url, ' ');
        if (strpos($url, 'https://') === 0) {
            $url = 'http://' . substr($url, 8);
        }
        return $url;
    }

    //获取当前域名地址，GGI模式
    public static function getDomainAddress($entrancePath = '')
    {
        if (isset($_SERVER['REQUEST_SCHEME'], $_SERVER['HTTP_HOST'], $_SERVER['SCRIPT_NAME'])) {
            $domain = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'];
            if (!empty($entrancePath)) {
                return $domain;
            }
            $index = strpos($_SERVER['SCRIPT_NAME'], $entrancePath);
            if ($index === false || $index === 0) {
                return $domain;
            } else {
                return $domain . substr($_SERVER['SCRIPT_NAME'], 0, $index - 1);
            }
        }
        return false;
    }

    const BROWSER_NORMAL = 0x01;
    const IE             = 'MSIE';
    const FIRE_FOX       = 'Firefox';
    const GOOGLE_CHROME  = 'Chrome';
    const SAFARI         = 'Safari';
    const OPERA          = 'Opera';
    const WECHAT         = 'MicroMessenger';
    const WEIBO          = 'Weibo';
    const ALIPAY         = 'AlipayClient';
    const IPHONE         = 'iPhone';
    const IPAD           = 'iPad';
    const ANDROID        = 'Android';

    //检查浏览器类型
    public static function clientCheck($type = self::BROWSER_NORMAL)
    {
        if ($type == self::BROWSER_NORMAL) {
            if (strpos($_SERVER["HTTP_USER_AGENT"], "MSIE")) {
                return "Internet Explorer";
            } else if (strpos($_SERVER["HTTP_USER_AGENT"], "Firefox")) {
                return "Firefox";
            } else if (strpos($_SERVER["HTTP_USER_AGENT"], "Chrome")) {
                return "Google Chrome";
            } else if (strpos($_SERVER["HTTP_USER_AGENT"], "Safari")) {
                return "Safari";
            } else if (strpos($_SERVER["HTTP_USER_AGENT"], "Opera")) {
                return "Opera";
            } else if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger')) {
                return "Wechat";
            } else if (strpos($_SERVER["HTTP_USER_AGENT"], "Weibo")) {
                return "Weibo";
            } else if (strpos($_SERVER['HTTP_USER_AGENT'], 'AlipayClient')) {
                return "Alipay";
            } else if (strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone')) {
                return "IOS";
            } else if (strpos($_SERVER['HTTP_USER_AGENT'], 'iPad')) {
                return "IOS";
            } else if (strpos($_SERVER['HTTP_USER_AGENT'], 'Android')) {
                return "Android";
            }
            return false;
        } else {
            return strpos($_SERVER["HTTP_USER_AGENT"], $type) ? true : false;
        }
    }
}
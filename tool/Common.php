<?php

namespace ParamsValidateMicroServices\tool;

/**
 * Class Common
 * @package ParamsValidateMicroServices\tool
 */
class Common
{
    const T_INT      = 'int'; // int 整数类型
    const T_BOOL     = 'bool'; // bool 布尔类型
    const T_FLOAT    = 'float'; // float 浮点数类型
    const T_DOUBLE   = 'double'; // double 双精度类型
    const T_STRING   = 'string'; // string 字符串类型
    const T_BOOLEAN  = 'boolean'; // boolean 布尔类型
    const T_NUMBERIC = 'numberic'; // numberic 数字类型

    // 运算符
    const O_EQ          = '=='; // 等于
    const O_ALL_EQ      = '==='; // 全等于
    const O_NO_EQ       = '!='; // 不等于
    const O_ALL_NO_EQ   = '!=='; // 不全等于
    const O_BIG         = '>'; // 大于
    const O_BIG_AN_EQ   = '>='; // 大于和等于
    const O_SMALL       = '<'; // 小于
    const O_SMALL_AN_EQ = '<='; // 小于和等于
    const O_BS_EQ       = '<>'; // 不等于

    // 电话地区
    const P_PRC   = 'PRC'; // 大陆地区
    const P_TW    = 'TW'; // 台湾地区
    const P_HK    = 'HK'; // 香港地区
    const P_AM    = 'AM'; // 美国地区
    const P_CHINA = 'CHINA'; // 中国地区
    const P_ALL   = 'ALL'; // 所有地区
    const P_CMCC  = 'CMCC'; // 中国移动
    const P_CUCC  = 'CUCC'; // 中国联通
    const P_CTCC  = 'CTCC'; // 中国电信
}
<?php

namespace ParamsValidateMicroServices\tool;

/**
 * Class Common
 * @package ParamsValidateMicroServices\tool
 */
class Common
{
    // Type 类型
    const T_INT      = 'int';
    const T_BOOL     = 'bool';
    const T_FLOAT    = 'float';
    const T_DOUBLE   = 'double';
    const T_STRING   = 'string';
    const T_BOOLEAN  = 'boolean';
    const T_NUMBERIC = 'numberic';

    // 运算符
    const O_EQ          = '==';
    const O_ALL_EQ      = '===';
    const O_NO_EQ       = '!=';
    const O_ALL_NO_EQ   = '!==';
    const O_BIG         = '>';
    const O_BIG_AN_EQ   = '>=';
    const O_SMALL       = '<';
    const O_SMALL_AN_EQ = '<=';
    const O_BS_EQ       = '<>';

    // 电话地区
    const P_PRC   = 'PRC';
    const P_TW    = 'TW';
    const P_HK    = 'HK';
    const P_AM    = 'AM';
    const P_CHINA = 'CHINA';
    const P_ALL   = 'ALL';
    const P_CMCC  = 'CMCC';
    const P_CUCC  = 'CUCC';
    const P_CTCC  = 'CTCC';
}
<?php

namespace ParamsValidateMicroServices\Tool;
class Common
{
    //Type 类型
    const TYPE_INT      = 'int';
    const TYPE_BOOL     = 'bool';
    const TYPE_JSON     = 'json';
    const TYPE_FLOAT    = 'float';
    const TYPE_DOUBLE   = 'double';
    const TYPE_STRING   = 'string';
    const TYPE_BOOLEAN  = 'boolean';
    const TYPE_NUMBERIC = 'numberic';

    //条件名
    const C_TYPE   = 'type';
    const C_EMPTY  = 'empty';
    const C_RANGE  = 'range';
    const C_IN     = 'in';
    const C_NOT_IN = '!in';
    const C_LEN    = 'len';
    const C_PHONE  = 'phone';
    const C_EMAIL  = 'email';
    const C_TRIM   = 'trim';
    const C_HTTP   = 'http';
}
<?php

namespace ParamsValidateMicroServices\tool;

class DirTool
{
    const CLASSIFY_INT    = 1;
    const CLASSIFY_STRING = 2;
    const OS_LINUS        = 'linux';
    const OS_WIN          = 'win';

    //清理路径字符串
    public static function dirClean($path, $os = self::OS_LINUS)
    {
        $path = trim($path, ' ');
        switch ($os) {
            case self::OS_WIN:
                $path = preg_replace("/\//", '\\', $path);
                while (strpos($path, "\\\\") !== false) {
                    $path = preg_replace("/\\\\\\\/", '\\', $path);
                }
                break;
            case self::OS_LINUS:
            default:
                $path = preg_replace("/\\\/", '/', $path);
                while (strpos($path, "//") !== false) {
                    $path = preg_replace("/\/\//", '/', $path);
                }
                break;
        }
        return $path;
    }

    //创建文件夹
    public static function dirCreate($path, $mode = 0775)
    {
        $name = basename($path);
        if (strpos($name, '.') !== false) {
            $path = dirname($path);
        }
        if (!is_dir($path)) {
            return mkdir($path, $mode, true);
        }
        return true;
    }

    /*
     * 路径分级
     */
    public static function classifyDir($path, $classify, $type = self::CLASSIFY_STRING)
    {
        if ($type == self::CLASSIFY_INT) {
            $append = '';
            if ($classify < 1000) {
                $append = '0_1000';
            } else {
                $rest   = $classify % 1000;
                $start  = $classify - $rest;
                $end    = $start + 1000;
                $append = "{$start}_{$end}";
            }
            $path = "{$path}/{$append}/{$classify}";
        } else {
            $append = substr($classify, strlen($classify) - 4);
            $path   = "{$path}/{$append}/{$classify}";
        }
        $result = self::dirCreate($path);
        return $result === false ? false : $path;
    }

}
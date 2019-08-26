<?php

namespace ParamsValidateMicroServices\tool;

/**
 * Class DirTool
 * @package ParamsValidateMicroServices\tool
 */
class DirTool
{
    // 数字类型
    const CLASSIFY_INT = 1;

    // 字符串类型
    const CLASSIFY_STRING = 2;

    // linux系统
    const OS_LINUS = 'linux';

    // window系统
    const OS_WIN = 'win';

    /**
     * 清理路径字符串
     *
     * @param $path
     * @param string $os
     * @return null|string|string[]
     */
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

    /**
     * 创建文件夹
     *
     * @param $path
     * @param int $mode
     * @return bool
     */
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

    /**
     * 路径分级
     *
     * @param $path
     * @param $classify
     * @param int $type
     * @return bool|string
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

    /**
     * 复制文件夹及文件夹下内容
     *
     * @param $oldDir
     * @param $newDir
     * @return bool
     */
    public static function copyDir($oldDir, $newDir)
    {
        if (!is_dir($oldDir)) {
            return false;
        }
        if ($oldDir == $newDir) {
            return true;
        }
        $result = self::copyDirSubFunc($oldDir, $newDir);
        return $result;
    }

    /**
     * 重命名文件夹
     *
     * @param $oldDir
     * @param $newDir
     * @return bool
     */
    public static function renameDir($oldDir, $newDir)
    {
        if (!is_dir($oldDir)) {
            return false;
        }
        if ($oldDir == $newDir) {
            return true;
        }
        $result = self::copyDirSubFunc($oldDir, $newDir);
        if ($result === false) {
            return false;
        }
        return self::deleteDir($oldDir);
    }

    /**
     * 删除文件夹及文件下的内容
     *
     * @param $dirPath
     * @return bool
     */
    public static function deleteDir($dirPath)
    {
        if (!is_dir($dirPath)) {
            return false;
        }
        $result = self::deleteDirSubFunc($dirPath);
        if ($result === false) {
            return false;
        }
        return rmdir($dirPath);
    }

    /**
     * 删除文件夹及文件夹下内容的递归函数
     *
     * @param $dirPath
     * @return bool
     */
    public static function deleteDirSubFunc($dirPath)
    {
        $dirHandle = opendir($dirPath);
        while ($file = readdir($dirHandle)) {
            if ($file != "." && $file != "..") {
                $fullPath = $oldDir . "/" . $file;
                if (!is_dir($fullPath)) {
                    $result = unlink($fullPath);
                    if ($result === false) {
                        return false;
                    }
                } else {
                    $result = self::deleteDirSubFunc($fullPath);
                    if ($result === false) {
                        return false;
                    }
                    $result = rmdir($fullPath);
                    if ($result === false) {
                        return false;
                    }
                }
            }
        }
        return true;
    }

    /**
     * 复制文件夹及文件夹下内容的递归函数
     *
     * @param $dirPath
     * @param $newDir
     * @return bool
     */
    public static function copyDirSubFunc($dirPath, $newDir)
    {
        $dirHandle = opendir($dirPath);
        while ($file = readdir($dirHandle)) {
            if ($file != "." && $file != "..") {
                $fullPath    = $dirPath . "/" . $file;
                $newFullPath = $newDir . "/" . $file;
                if (!is_dir($fullPath)) {
                    $result = FileTool::copyFile($fullPath, $newFullPath);
                    if ($result === false) {
                        return false;
                    }
                } else {
                    $result = self::dirCreate($newFullPath);
                    if ($result === false) {
                        return false;
                    }
                    $result = self::copyDirSubFunc($fullPath, $newFullPath);
                    if ($result === false) {
                        return false;
                    }
                }
            }
        }
        return true;
    }

}
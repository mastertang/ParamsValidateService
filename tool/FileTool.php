<?php

namespace ParamsValidateMicroServices\tool;

class FileTool
{
    /*
     * 根据路径获取文件的后缀
     */
    public static function getFileSuffixByPath($filePath)
    {
        $baseName = basename($filePath);
        $index    = strpos($baseName, '.');
        if ($index === false) {
            return "";
        }
        return substr($baseName, $index + 1);
    }

    /*
     * 上传文，与thinkphp框架结合使用
     */
    public static function uploadFile($dirPath, $fileParamName, $fileName = '', $suffix = '', &$message = '')
    {
        $file = \request()->file($fileParamName);
        if (!$file) {
            $message = '文件没有上传失败';
            return false;
        }
        if (empty($suffix)) {
            $fileType   = $_FILES[$fileParamName]['type'];
            $fileSuffix = strtolower(substr($fileType, strrpos($fileType, '/') + 1));
        } else {
            $fileSuffix = $suffix;
        }
        if (empty($fileName)) {
            $fileName = uniqid() . ".{$fileSuffix}";
        } else {
            $fileName .= ".{$fileSuffix}";
        }
        $info = $file->move($dirPath, $fileName);
        if (!$info) {
            $message = '文件移动失败';
            return false;
        }
        return !$info ? false : $fileName;
    }

    /*
     * 移动文件
     */
    public static function moveFile($oldFilePath, $newFilePath)
    {
        if (!is_file($oldFilePath)) {
            return false;
        }
        $newFileDir = dirname($newFilePath);
        if (!is_dir($newFileDir)) {
            $mkResult = mkdir($newFileDir, 0775, true);
            if ($mkResult === false) {
                return false;
            }
        }
        return rename($oldFilePath, $newFilePath);
    }

    /*
     * 复制文件
     */
    public static function copyFile($filePath, $newFileDir, $newName = '')
    {
        if (!is_file($filePath)) {
            return false;
        }
        if (is_string($newName) && !empty($newName)) {
            $fileName = $newName;
        } else {
            $fileName = basename($filePath);
        }
        if (!is_dir($newFileDir)) {
            $mkdirResult = mkdir($newFileDir, 0775, true);
            if ($mkdirResult === false) {
                return false;
            }
        }
        return copy($filePath, "{$newFileDir}/{$fileName}");
    }

    /*
     * 文件路径
     */
    public static function deleteFiles($filePaths)
    {
        if (is_string($filePaths)) {
            $filePaths = [$filePaths];
        }
        if (is_array($filePaths) && !empty($filePaths)) {
            foreach ($filePaths as $filePath) {
                if (is_file($filePath)) {
                    unlink($filePath);
                }
            }
        }
        return true;
    }

}
<?php

namespace ParamsValidateMicroServices\tool;

/**
 * Class FileTool
 * @package ParamsValidateMicroServices\tool
 */
class FileTool
{
    // 获取文件kb大小
    const FILESIZE_KB = 0x1;

    // 获取文件mb大小
    const FILESIZE_MB = 0x2;

    // 获取文件gb大小
    const FILESIZE_GB = 0x3;

    // 获取文件tb大小
    const FILESIZE_TB = 0x4;

    /**
     * 修改文件后缀
     *
     * @param $filePath
     * @param $newSuffix
     * @return bool|string
     */
    public static function changeFileSuffix($filePath, $newSuffix)
    {
        $baseName    = basename($filePath);
        $lastDsIndex = strrpos($filePath, DIRECTORY_SEPARATOR);
        if ($lastDsIndex === false || $lastDsIndex == (strlen($filePath) - 1)) {
            return false;
        }
        $dir        = substr($filePath, 0, $lastDsIndex + 1);
        $pointIndex = strrpos($baseName, '.');
        if ($pointIndex === false) {
            return $dir . $baseName . ".{$newSuffix}";
        } else {
            $baseName = substr($baseName, 0, $pointIndex);
            return $dir . $baseName . ".{$newSuffix}";
        }
    }

    /**
     * 根据路径获取文件的后缀
     *
     * @param $filePath
     * @return bool|string
     */
    public static function getFileSuffixByPath($filePath)
    {
        $baseName = basename($filePath);
        $index    = strrpos($baseName, '.');
        if ($index === false) {
            return "";
        }
        return substr($baseName, $index + 1);
    }

    /**
     * 上传文，与thinkphp框架结合使用
     *
     * @param $dirPath
     * @param $fileParamName
     * @param string $fileName
     * @param string $suffix
     * @param string $message
     * @return bool|string
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

    /**
     * 移动文件
     *
     * @param $oldFilePath
     * @param $newFilePath
     * @return bool
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

    /**
     * 复制文件
     *
     * @param $filePath
     * @param $newFileDir
     * @param string $newName
     * @return bool
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

    /**
     * 文件路径
     *
     * @param $filePaths
     * @return bool
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

    /**
     * 修改文件名字
     *
     * @param $filePath
     * @param $newName
     * @param bool $suffix
     * @return string
     */
    public static function changeFileName($filePath, $newName, $suffix = true)
    {
        $oldName = basename($filePath);
        $dir     = substr($filePath, 0, strrpos($filePath, $oldName));
        if ($dir{strlen($dir) - 1} != DS) {
            $dir .= DS;
        }
        if ($suffix) {
            $lastPosition = strrpos($oldName, '.');
            if ($lastPosition !== false) {
                $oldSuffix = substr($oldName, $lastPosition);
            } else {
                $oldSuffix = '';
            }
            return $dir . $newName . $oldSuffix;
        } else {
            return $dir . $newName;
        }
    }

    /**
     * 获取文件的大小
     *
     * @param $filePath
     * @param int $sizeType
     * @return bool|float|int
     */
    public static function getFileSize($filePath, $sizeType = self::FILESIZE_KB)
    {
        if (!is_file($filePath)) {
            return false;
        }
        $fileSize = filesize($filePath);
        switch ($sizeType) {
            case self::FILESIZE_MB:
                return $fileSize / 1024;
                break;
            case self::FILESIZE_GB:
                return ($fileSize / 1024) / 1024;
                break;
            case self::FILESIZE_TB:
                return (($fileSize / 1024) / 1024) / 1024;
                break;
            case self::FILESIZE_KB:
            default:
                return $fileSize;
                break;
        }
    }
}
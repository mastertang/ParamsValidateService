<?php

namespace ParamsValidateMicroServices\tool;

class FileTool
{
    /*
     * 上传文，与thinkphp框架结合使用
     */
    public static function uploadFile($dirPath, $fileParamName, $fileName = '', &$message = '')
    {
        $file = \request()->file($fileParamName);
        if (!$file) {
            $message = '文件没有上传失败';
            return false;
        }
        $fileType   = $_FILES[$fileParamName]['type'];
        $fileSuffix = strtolower(substr($fileType, strrpos($fileType, '/') + 1));
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


}
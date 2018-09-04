<?php

namespace ParamsValidateMicroServices\tool;

class PictureTool
{
    //从base64头获取图片后缀
    public static function getPicSuffixFromBase64String($base64String)
    {
        $startPosition = strpos($base64String, 'data:image/') + 11;
        $endPosition   = strpos($base64String, ';');
        if ($startPosition === false || $endPosition === false) {
            return false;
        }
        $suffix = substr($base64String, $startPosition, $endPosition - $startPosition);
        switch ($suffix) {
            case 'gif':
            case 'png':
            case 'jpg':
                return $suffix;
                break;
            case 'jpeg':
                return 'jpg';
                break;
            default:
                return false;
                break;
        }
    }

    //去除base64数据的头信息
    public static function reduceBase64StringHead($base64String)
    {
        return substr($base64String, strpos($base64String, ',') + 1);
    }

}
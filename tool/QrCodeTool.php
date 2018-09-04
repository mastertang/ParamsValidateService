<?php

namespace ParamsValidateMicroServices\tool;

use PHPQRCode\Constants;
use PHPQRCode\QRcode;

class QrCodeTool
{
    public static function createNormalQrCode($content, $path, $size = 3, $padding = 2)
    {
        $baseName = basename($path);
        if (strpos($baseName, '.') === false || empty($baseName)) {
            return false;
        }
        if(DirTool::dirCreate(dirname($path),0775) === false){
            return false;
        }
        $result = QRcode::png($content, $path, Constants::QR_ECLEVEL_Q, $size, $padding);
        if ($result === false) {
            Status::throwException(Status::SYSTEM, "生成二维码失败", true);
        }
        return true;
    }

}
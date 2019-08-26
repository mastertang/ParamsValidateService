<?php

namespace ParamsValidateMicroServices\tool;

use Endroid\QrCode\QrCode;

/**
 * Class QrCodeTool
 * @package ParamsValidateMicroServices\tool
 */
class QrCodeTool
{

    /**
     * 创建二维码
     *
     * @param $content
     * @param $path
     * @param int $size
     * @param int $margin
     * @param string $foregroundColor
     * @param string $backgroundColor
     * @param string $logoPath
     * @param string $logoSize
     * @param bool $roundBlockSize
     * @return mixed
     */
    public static function createQrCode(
        $content,
        $path,
        $size = 300,
        $margin = 10,
        $foregroundColor = "0|0|0|0",
        $backgroundColor = "255|255|255|0",
        $logoPath = "",
        $logoSize = "100|100",
        $roundBlockSize = false
    )
    {
        $baseName = basename($path);
        if (strpos($baseName, '.') === false || empty($baseName)) {
            return false;
        }
        if (DirTool::dirCreate(dirname($path), 0775) === false) {
            return false;
        }
        if ($size < 50) {
            $size = 50;
        }
        if ($margin < 0) {
            $margin = 0;
        }
        list($fr, $fg, $fb, $fa) = explode('|', $foregroundColor);
        list($br, $bg, $bb, $ba) = explode('|', $backgroundColor);
        $fr = $fr < 0 ? 0 : ($fr > 255 ? 255 : (int)$fr);
        $fg = $fg < 0 ? 0 : ($fg > 255 ? 255 : (int)$fg);
        $fb = $fb < 0 ? 0 : ($fb > 255 ? 255 : (int)$fb);
        $fa = $fa < 0 ? 0 : ($fa > 255 ? 255 : (int)$fa);

        $br = $br < 0 ? 0 : ($br > 255 ? 255 : (int)$br);
        $bg = $bg < 0 ? 0 : ($bg > 255 ? 255 : (int)$bg);
        $bb = $bb < 0 ? 0 : ($bb > 255 ? 255 : (int)$bb);
        $ba = $ba < 0 ? 0 : ($ba > 255 ? 255 : (int)$ba);

        list($logoWith, $logoHeight) = explode('|', $logoSize);
        $logoWith   = $logoWith < 0 ? 0 : $logoWith;
        $logoHeight = $logoHeight < 0 ? 0 : $logoHeight;

        $qrCode = new QrCode($content);
        $qrCode->setSize($size);

        $qrCode->setWriterByName('png');
        $qrCode->setMargin($margin);
        $qrCode->setEncoding('UTF-8');
        $qrCode->setForegroundColor(['r' => $fr, 'g' => $fg, 'b' => $fb, 'a' => $fa]);
        $qrCode->setBackgroundColor(['r' => $br, 'g' => $bg, 'b' => $bb, 'a' => $ba]);
        if (is_file($logoPath)) {
            $qrCode->setLogoPath(realpath($logoPath));
            $qrCode->setLogoSize($logoWith, $logoHeight);
        }
        $qrCode->setRoundBlockSize($roundBlockSize);
        return $qrCode->writeFile($path);
    }
}
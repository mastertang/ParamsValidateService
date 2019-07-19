<?php

namespace ParamsValidateMicroServices\tool;

/**
 * Class PictureTool
 * @package ParamsValidateMicroServices\tool
 */
class PictureTool
{
    /**
     * @var array gd库图片索引列表
     */
    public static $gdPictureIndexList = [
        1  => 'GIF',
        2  => 'JPEG',
        3  => 'PNG',
        4  => 'SWF',
        5  => 'PSD',
        6  => 'BMP',
        7  => 'TIFF_II',
        8  => 'TIFF_MM',
        9  => 'JPC',
        10 => 'JP2',
        11 => 'JPX',
        12 => 'JB2',
        13 => 'SWC',
        14 => 'IFF',
        15 => 'WBMP',
        16 => 'XBM',
        17 => 'ICO',
        18 => 'COUNT',
        19 => 'GD',
        20 => 'GD2',
        21 => 'XPM',
        23 => 'JPG'
    ];

    /**
     * 从base64头获取图片后缀
     *
     * @param $base64String
     * @return bool|string
     */
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

    /**
     * 去除base64数据的头信息
     *
     * @param $base64String
     * @return bool|string
     */
    public static function reduceBase64StringHead($base64String)
    {
        return substr($base64String, strpos($base64String, ',') + 1);
    }

    /**
     * 使用gd库获取图片内容
     *
     * @param $picturePath
     * @param string $width
     * @param string $height
     * @param string $type
     * @param string $attr
     * @param string $bits
     * @param string $channels
     * @param string $mime
     * @return array|bool
     */
    public static function getPictureInfoByGd(
        $picturePath,
        &$width = '',
        &$height = '',
        &$type = '',
        &$attr = '',
        &$bits = '',
        &$channels = '',
        &$mime = ''
    )
    {
        if (!is_file($picturePath) && empty($picturePath)) {
            return false;
        }
        if (is_file($picturePath)) {
            if (($imageInfo = getimagesize($picturePath)) === false) {
                return false;
            }
        } else {
            if (($imageInfo = getimagesizefromstring($picturePath)) === false) {
                return false;
            }
        }
        list($width, $height, $type, $attr) = $imageInfo;
        $bits     = $imageInfo['bits'];
        $channels = $imageInfo['channels'];
        $mime     = $imageInfo['mime'];
        return $imageInfo;
    }

    /**
     * 根据图片类型索引获取图片后缀类型
     *
     * @param $typeIndex
     * @return bool|string
     */
    public static function getGDPictureType($typeIndex)
    {
        if (!isset(self::$gdPictureIndexList[$typeIndex])) {
            return false;
        }
        return strtolower(self::$gdPictureIndexList[$typeIndex]);
    }

    /**
     * 根据图片类型获取图片资源
     *
     * @param $picturePath
     * @param $typeIndex
     * @return bool|resource
     */
    public static function getGDPictureResource(
        $picturePath,
        $typeIndex
    )
    {
        if (empty($picturePath)) {
            return false;
        }
        $suffix = false;
        if (is_int($typeIndex)) {
            if (isset(self::$gdPictureIndexList[$typeIndex])) {
                $suffix = self::$gdPictureIndexList[$typeIndex];
            }
        } elseif (is_string($typeIndex)) {
            $typeIndex  = strtoupper($typeIndex);
            $indexValue = array_values(self::$gdPictureIndexList);
            if (in_array($typeIndex, $indexValue)) {
                $suffix = $typeIndex;
            }
        }
        $imageResource = false;
        switch ($suffix) {
            case 'GIF':
                $imageResource = imagecreatefromgif($picturePath);
                break;
            case 'JPG':
            case 'JPEG':
                $imageResource = imagecreatefromjpeg($picturePath);
                break;
            case 'PNG':
                $imageResource = imagecreatefrompng($picturePath);
                break;
            case 'WBMP':
                $imageResource = imagecreatefromwbmp($picturePath);
                break;
            case 'XBM':
                $imageResource = imagecreatefromxbm($picturePath);
                break;
            case 'XPM':
                $imageResource = imagecreatefromxpm($picturePath);
                break;
            case 'STRING':
            default:
                $imageResource = imagecreatefromstring($picturePath);
                break;
        }
        return $imageResource;
    }

    /**
     * 根据路径获取图片的类型
     *
     * @param $filePath
     * @return bool|string
     */
    public static function getPictureTypeByPath($filePath)
    {
        $suffix = FileTool::getFileSuffixByPath($filePath);
        if (!in_array(strtoupper($suffix), self::$gdPictureIndexList)) {
            return false;
        }
        return $suffix;
    }

    /**
     * 使用gd保存图片
     *
     * @param $typeIndex
     * @param $filePath
     * @param $resource
     * @param $quality
     * @return bool
     */
    public static function saveGDPicture(
        $typeIndex,
        $filePath,
        $resource,
        $quality
    )
    {
        $typeIndex = strtoupper($typeIndex);
        $result    = false;
        switch ($typeIndex) {
            case 'GIF':
                $result = imagegif($resource, $filePath);
                break;
            case 'PNG':
                $result = imagepng($resource, $filePath, $quality <= 0 ? 9 : $quality);
                break;
            case 'WBMP':
                $result = imagewbmp($resource, $filePath);
                break;
            case 'XBM':
                $result = imagexbm($resource, $filePath);
                break;
            case 'GD':
                $result = imagegd($resource, $filePath);
                break;
            case 'GD2':
                $result = imagegd2($resource, $filePath);
                break;
            case 'XPM':
            case 'COUNT':
            case 'JPG':
            case 'SWF':
            case 'PSD':
            case 'BMP':
            case 'TIFF_II':
            case 'TIFF_MM':
            case 'JPC':
            case 'JP2':
            case 'JPX':
            case 'JB2':
            case 'SWC':
            case 'IFF':
            case 'JPEG':
            case 'ICO':
                $result = imagejpeg($resource, $filePath, $quality <= 0 ? 100 : $quality);
                break;
        }
        imagedestroy($resource);
        return $result;
    }

    /**
     * 修改图片尺寸
     *
     * @param $picturePath
     * @param $savePath
     * @param null $reWidth
     * @param null $reHeight
     * @param null $ratio
     * @param int $quality
     * @param null $saveType
     * @param bool $deleteOld
     * @return bool
     */
    public static function resizeImage(
        $picturePath,
        $savePath,
        $reWidth = null,
        $reHeight = null,
        $ratio = null,
        $quality = 0,
        $saveType = null,
        $deleteOld = false
    )
    {
        $saveSuffix = self::getPictureTypeByPath($savePath);
        if ($saveSuffix === false) {
            return false;
        }
        $imageResult = self::getPictureInfoByGd(
            $picturePath,
            $imageWidth,
            $imageHeight,
            $typeIndex
        );
        if ($imageResult === false) {
            return false;
        }
        $imageResource = self::getGDPictureResource($picturePath, $typeIndex);
        if ($imageResource === false) {
            return false;
        }
        $newImageWidth  = $imageWidth;
        $newImageHeight = $imageHeight;
        if (!empty($ratio) && is_float($ratio)) {
            $newImageWidth  *= $ratio;
            $newImageHeight *= $ratio;
        } else {
            if (is_int($reWidth) && $reWidth > 1) {
                $newImageWidth = $reWidth;
            }
            if (is_int($reHeight) && $reHeight > 1) {
                $newImageHeight = $reHeight;
            }
        }
        $newImageResource = imagecreatetruecolor($newImageWidth, $newImageHeight);
        $result           = imagecopyresampled(
            $newImageResource, $imageResource,
            0, 0, 0, 0,
            $newImageWidth, $newImageHeight,
            $imageWidth, $imageHeight
        );
        imagedestroy($imageResource);
        if (!$result) {
            imagedestroy($newImageResource);
            return false;
        }
        if (is_string($saveType) && !empty($saveType)) {
            $typeList = array_values(self::$gdPictureIndexList);
            $saveType = strtoupper($saveType);
            if (in_array($saveType, $typeList)) {
                $savePath   = str_replace('.' . $saveSuffix, '.' . strtolower($saveType), $savePath);
                $saveSuffix = $saveType;
            }
        }
        $result = self::saveGDPicture($saveSuffix, $savePath, $newImageResource, $quality);
        if ($result && $deleteOld) {
            unlink($picturePath);
        }
        return $result;
    }

    /**
     * 裁剪图片
     *
     * @param $picturePath
     * @param $savePath
     * @param $cutWidth
     * @param $cutHeight
     * @param $cutX
     * @param $cutY
     * @param int $quality
     * @param null $saveType
     * @param bool $deleteOld
     * @return bool
     */
    public static function cutImage(
        $picturePath,
        $savePath,
        $cutWidth,
        $cutHeight,
        $cutX,
        $cutY,
        $quality = 0,
        $saveType = null,
        $deleteOld = false
    )
    {
        $saveSuffix = self::getPictureTypeByPath($savePath);
        if ($saveSuffix === false) {
            return false;
        }
        $imageResult = self::getPictureInfoByGd(
            $picturePath,
            $imageWidth,
            $imageHeight,
            $typeIndex
        );
        if ($imageResult === false ||
            ($cutWidth <= 0 || $cutHeight <= 0 || $cutX >= $imageWidth || $cutY >= $imageHeight)
        ) {
            return false;
        }
        if (($cutX + $cutWidth) > $imageWidth) {
            $cutWidth = $imageWidth - $cutX;
        }
        if (($cutY + $cutHeight) > $imageHeight) {
            $cutHeight = $imageHeight - $cutY;
        }

        $imageResource = self::getGDPictureResource($picturePath, $typeIndex);
        if ($imageResource === false) {
            return false;
        }
        $newImageResource = imagecreatetruecolor($cutWidth, $cutHeight);
        $result           = imagecopyresampled(
            $newImageResource, $imageResource,
            0, 0, $cutX, $cutY,
            $cutWidth, $cutHeight,
            $cutWidth, $cutHeight
        );
        imagedestroy($imageResource);
        if (!$result) {
            imagedestroy($newImageResource);
            return false;
        }
        if (is_string($saveType) && !empty($saveType)) {
            $typeList = array_values(self::$gdPictureIndexList);
            $saveType = strtoupper($saveType);
            if (in_array($saveType, $typeList)) {
                $saveSuffix = $saveType;
            }
        }
        $result = self::saveGDPicture($saveSuffix, $savePath, $newImageResource, $quality);
        if ($result && $deleteOld) {
            unlink($picturePath);
        }
        return $result;
    }
}
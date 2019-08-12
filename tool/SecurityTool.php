<?php

namespace ParamsValidateMicroServices\tool;

/**
 * Class SecurityTool
 * @package ParamsValidateMicroServices\tool
 */
class SecurityTool
{
    /**
     * 验证签名模式
     *
     * @param $data
     * @param null $clientSignature
     * @param null $dataMerge
     * @return bool|string
     */
    public static function signatureCheckMode1(
        $data,
        $clientSignature = null,
        $dataMerge = null
    )
    {
        if ($dataMerge instanceof \Closure) {
            $result = call_user_func_array($dataMerge, []);
            if (is_array($result) && !empty($result)) {
                $data = array_merge($data, $result);
            }
        }
        ksort($data);
        $unpackagedString = '';
        foreach ($data as $key => $value) {
		    if($value !== ''){
            		$unpackagedString .= "&{$key}={$value}";
			}
        }
        $unpackagedString = substr($unpackagedString, 1);
        $packageString    = sha1($unpackagedString);
        if (empty($clientSignature)) {
            return $packageString;
        }
        return $packageString == $clientSignature;
    }
}
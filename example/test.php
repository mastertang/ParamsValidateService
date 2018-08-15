<?php
require_once __DIR__ . "/../tool/Common.php";
require_once __DIR__ . "/../tool/ValidateBase.php";
require_once __DIR__ . "/../services/PValServiceService.php";
require_once __DIR__ . "/../tool/PhoneClassify.php";
require_once __DIR__ . "/../tool/Validate.php";
use ParamsValidateMicroServices\tool\Validate;
use ParamsValidateMicroServices\tool\Common;
$a      = [
    (new Validate())->isEmpty()->name('a')->type(Common::T_STRING)
    ->method('GET')
];
$b      = new \ParamsValidateMicroServices\services\PValService();
$result = $b->serviceStart($a);

var_dump("结果: " . $result);
var_dump("错误: " . $b->errMessage);
var_dump($b->originData);
var_dump($b->handleData);
var_dump($b->headerData);
<?php
require_once __DIR__ . "/../tool/NetTool.php";

use ParamsValidateMicroServices\tool\NetTool;

var_dump(NetTool::urlAddVersion('https://aa.www.com/a/c?ssss=1000','ssss'));
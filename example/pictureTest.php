<?php
require_once __DIR__ . "/../tool/PictureTool.php";
require_once __DIR__ . "/../tool/FileTool.php";

use ParamsValidateMicroServices\tool\PictureTool;

var_dump(PictureTool::cutImage(
    '/Users/mastertom/Desktop/ParamsValidateService/timg.jpeg',
    '/Users/mastertom/Desktop/ParamsValidateService/timg2.jpeg',
    1190,
    200,
        600,
    400)
);
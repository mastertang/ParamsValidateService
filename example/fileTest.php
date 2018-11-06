<?php
require_once __DIR__ . "/../tool/FileTool.php";

use ParamsValidateMicroServices\tool\FileTool;

var_dump(FileTool::getFileSuffixByPath(''));

var_dump(FileTool::deleteFiles(
    [
        '/Users/mastertom/Desktop/ParamsValidateService/timg2.jpeg',
        '/Users/mastertom/Desktop/ParamsValidateService/timg3.jpeg'
    ]
)
);
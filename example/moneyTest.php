<?php
require_once __DIR__ . "/../tool/MoneyTool.php";

use ParamsValidateMicroServices\tool\MoneyTool;

var_dump(MoneyTool::changeNumberFormat('100000',9,'','.',true));
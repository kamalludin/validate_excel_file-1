<?php

require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload
use kamalludin\ValidateExcelFile\Validate;

$index = new Validate();
$filename = "../Type_B.xls";
$result = $index->validateFile($filename);
echo "<pre>";
print_r($result);
echo "</pre>";
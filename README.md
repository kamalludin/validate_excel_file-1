# validate_excel_file-1
Package for validate excel file based on header rules
## Installation
```
composer require kamalludin/validate-excel-file-1 dev-master
```
## How to use
```
Create PHP file (example test.php)
```
### Copy paste this code
```
require_once __DIR__ . '/vendor/autoload.php'; // Autoload files using Composer autoload
use kamalludin\ValidateExcelFile\Validate;

$index = new Validate();
$filename = "Type_B.xls";
$result = $index->validateFile($filename);
echo "<pre>";
print_r($result);
echo "</pre>";
```

<?php

namespace kamalludin\ValidateExcelFile;
use PHPExcel_IOFactory;

class Validate {

    public function validateFile($filename) {
    
        try {

            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            if ($ext != ("xls" || "xlsx")) {
                $result = [
                    "status" => "error",
                    "message" => 'File format must be "xls" or "xlsx" !'
                ];
                return $result;
            }
            
            $type = PHPExcel_IOFactory::identify($filename);
            $objReader = PHPExcel_IOFactory::createReader($type);
            $objPHPExcel = $objReader->load($filename);

            foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
                $worksheets[$worksheet->getTitle()] = $worksheet->toArray();
            }

            $sheet1 = $worksheets["Sheet1"];
            $rulesHeader = $this->createRulesHeader($sheet1[0]);

            $resultValidate = [];
            for ($i = 1; $i < count($sheet1); $i++) {
                $error = "";
                for ($j = 0; $j < count($sheet1[$i]); $j++) {
                    switch ($rulesHeader[$j]){
                        case "not-space":
                            if (!$this->notSpace($sheet1[$i][$j])) {
                                $error .= preg_replace('/[^a-zA-Z0-9_.]/', '', $sheet1[0][$j]) . " should not contain any space, ";
                            };
                        break;
                        case "not-empty":
                            if (!$this->notEmpty($sheet1[$i][$j])) {
                                $error .= " Missing value in " . preg_replace('/[^a-zA-Z0-9_.]/', '', $sheet1[0][$j]) . ", ";
                            };
                        break;
                    }
                }
                if (!empty($error)) {
                    $resultValidate[] = [
                        "row" => $i,
                        "error" => rtrim($error, ", ")
                    ];
                }
            }

            $result = [
                "status" => "success",
                "result" => $resultValidate
            ];

            return $result;

        } catch (Exception $e) {

            $result = [
                "status" => "error",
                "message" => 'Caught exception: ',  $e->getMessage()
            ];

            return $result;

        }

    }

    private function createRulesHeader($header){
        $rules = [];
        for ($i = 0; $i < count($header); $i++){
            if (substr($header[$i], 0, 1) == "#") {
                $rules[] = "not-space";
            } else if (substr($header[$i], -1) == "*") {
                $rules[] = "not-empty";
            } else {
                $rules[] = "no-rule";
            }
        }
        return $rules;
    }

    private function notSpace($value) {
        $findSpace = strrpos($value," ");
        if (!empty($findSpace)){
            return false;
        }
        return true;
    }

    private function notEmpty($value) {
        if (empty($value)){
            return false;
        }
        return true;
    }

}
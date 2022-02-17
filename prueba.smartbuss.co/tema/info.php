<?php


header('Content-type: application/json');

require_once("../php/restrict.php");

require_once('../libraries/PhpSpreadsheet/src/PhpSpreadsheet/Spreadsheet.php');

// use PhpOffice\PhpSpreadsheet\Spreadsheet;
// use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'Hello World !');

$writer = new Xlsx($spreadsheet);
$writer->save('prueba.xlsx');


// use PhpOffice\PhpSpreadsheet\IOFactory;
// use PhpOffice\PhpSpreadsheet\Spreadsheet;
// $spreadsheet = new Spreadsheet();
// $spreadsheet->setActiveSheetIndex(0)
//     ->setCellValue('A1', 'Hola')
//     ->setCellValue('B2', 'Mundo!')
//     ;
// $writer = IOFactory::createWriter($spreadsheet, 'Xls');
// $writer->save('salida.xls');

print_r('ingreso');
?>
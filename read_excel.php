<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$inputFileName = 'inputexcel/Data Sektor Udara Q1 -  Q4 2025.xlsx';

$spreadsheet = IOFactory::load($inputFileName);
$worksheet = $spreadsheet->getActiveSheet();
$rows = [];
$i = 0;
foreach ($worksheet->getRowIterator() as $row) {
    $cellIterator = $row->getCellIterator();
    $cellIterator->setIterateOnlyExistingCells(FALSE); 
    $cells = [];
    foreach ($cellIterator as $cell) {
        $val = $cell->getValue();
        if ($val instanceof \PhpOffice\PhpSpreadsheet\RichText\RichText) {
            $cells[] = $val->getPlainText();
        } else {
            $cells[] = $val;
        }
    }
    $rows[] = $cells;
    $i++;
    if ($i >= 30) break; // Read first 30 rows
}

file_put_contents('excel_dump.json', json_encode($rows, JSON_PRETTY_PRINT));

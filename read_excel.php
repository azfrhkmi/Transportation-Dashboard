<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$inputFileName = 'inputexcel/Data Sektor Udara Q1 - Q4 2014.xlsx';

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
    if ($i >= 10) break; // Read first 10 rows
}

print_r($rows);

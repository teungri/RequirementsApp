<?php
// ReqHeap - a simple requirement management program.
//
//    Copyright (C) 2007 Slav Peev , Matthias Gunter
//    Programmed by i-nature.com
//
//    This program is free software: you can redistribute it and/or modify
//    it under the terms of the GNU Affero General Public License as
//    published by the Free Software Foundation, either version 3 of the
//    License, or (at your option) any later version.
//
//    This program is distributed in the hope that it will be useful,
//    but WITHOUT ANY WARRANTY; without even the implied warranty of
//    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//    GNU Affero General Public License for more details.
//
//    You should have received a copy of the GNU Affero General Public License
//    along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
// -----------------------------------------------------------------
//
// Page: "Create XLS" - creating excels using /xls scripts

//default language
if ($_chlang!="") $_SESSION['chlang']=$_chlang;
if (!$_SESSION['chlang']) $_SESSION['chlang']="en";
include ("ini/lng/".$_SESSION['chlang'].".php");//include language file

/** Error reporting */
//error_reporting(E_ALL);

/** Include path **/
//set_include_path(get_include_path() . PATH_SEPARATOR . '../Classes/');
set_include_path('xls/Classes/');

/** PHPExcel */
require_once 'PHPExcel.php';

/** PHPExcel_RichText */
require_once 'PHPExcel/RichText.php';

/** PHPExcel_RichText */
require_once 'PHPExcel//Writer/Excel5.php';

// Create new PHPExcel object
//echo date('H:i:s') . " Create new PHPExcel object\n";
$objPHPExcel = new PHPExcel();

// Set properties
//echo date('H:i:s') . " Set properties\n";
$objPHPExcel->getProperties()->setCreator("ReqHeap");
$objPHPExcel->getProperties()->setLastModifiedBy("ReqHeap");
$objPHPExcel->getProperties()->setTitle("Office 2007 XLSX ReqHeap Document");
$objPHPExcel->getProperties()->setSubject("Office 2007 XLSX ReqHeap Document");
$objPHPExcel->getProperties()->setDescription("ReqHeap document for Office 2007 XLSX, generated using PHP classes.");
$objPHPExcel->getProperties()->setKeywords("office 2007 openxml php");
$objPHPExcel->getProperties()->setCategory("ReqHeap result file");


// Create a first sheet, representing sales data
//echo date('H:i:s') . " Add some data\n";

$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
$objPHPExcel->getActiveSheet()->setCellValue('A1', $lng[2][1].' test');
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);

$objPHPExcel->getActiveSheet()->setCellValue('A3', 'description');
$objPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$objPHPExcel->getActiveSheet()->setCellValue('A4', date("d.m.Y"));
$objPHPExcel->getActiveSheet()->getStyle('A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('A4')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);

$objPHPExcel->getActiveSheet()->setCellValue('A6', 'ID');
$objPHPExcel->getActiveSheet()->setCellValue('B6', 'Tree');
$objPHPExcel->getActiveSheet()->setCellValue('C6', 'Requirement');
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->setCellValue('D6', 'Weight');
$objPHPExcel->getActiveSheet()->setCellValue('E6', 'Supplier A');
$objPHPExcel->getActiveSheet()->setCellValue('F6', '');
$objPHPExcel->getActiveSheet()->setCellValue('G6', 'Supplier B');
$objPHPExcel->getActiveSheet()->setCellValue('H6', '');
$objPHPExcel->getActiveSheet()->setCellValue('I6', 'Supplier C');
$objPHPExcel->getActiveSheet()->setCellValue('J6', '');
$objPHPExcel->getActiveSheet()->setCellValue('K6', 'Supplier D');
$objPHPExcel->getActiveSheet()->setCellValue('L6', '');

$objPHPExcel->getActiveSheet()->setCellValue('E7', 'points');
$objPHPExcel->getActiveSheet()->getStyle('E7')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('E7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->setCellValue('F7', 'weigthed points');
$objPHPExcel->getActiveSheet()->getStyle('F7')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('F7')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('F7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->setCellValue('G7', 'points');
$objPHPExcel->getActiveSheet()->getStyle('G7')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('G7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->setCellValue('H7', 'weigthed points');
$objPHPExcel->getActiveSheet()->getStyle('H7')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('H7')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('H7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->setCellValue('I7', 'points');
$objPHPExcel->getActiveSheet()->getStyle('I7')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('I7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->setCellValue('J7', 'weigthed points');
$objPHPExcel->getActiveSheet()->getStyle('J7')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('J7')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('J7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->setCellValue('K7', 'points');
$objPHPExcel->getActiveSheet()->getStyle('K7')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('K7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->setCellValue('L7', 'weigthed points');
$objPHPExcel->getActiveSheet()->getStyle('L7')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('L7')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('L7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


/** PHPExcel_IOFactory */
include 'PHPExcel/IOFactory.php';

//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
//$objWriter->save(str_replace('.php', '.xls', '11.xls'));

header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");;
header("Content-Disposition: attachment;filename=testfile.xls"); 
header("Content-Transfer-Encoding: binary ");

$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);    //  (I want the output for 2003)
$objWriter->save('php://output');
?>
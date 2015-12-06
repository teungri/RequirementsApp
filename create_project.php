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
// Page: "create project" - creating a xls file for projects
 
//creating xls file 

ob_start();
set_time_limit(10);

function escapeInputs($str)
 {
  $str=(stripslashes($str));
  return $str;
 }

require_once "class.writeexcel_workbook.inc.php";
require_once "class.writeexcel_worksheet.inc.php";

$fname = tempnam("", "simple.xls");
$workbook = &new writeexcel_workbook($fname);
$worksheet = &$workbook->addworksheet();

// Some formats
$left  =& $workbook->addformat(array('align' => 'left'));
$right  =& $workbook->addformat(array('align' => 'right'));

$heading =& $workbook->addformat();
$heading->set_align('left');
$heading->set_text_wrap();
$heading->set_bold();
$heading->set_merge();
$heading->set_align('vcenter');

$format2 =& $workbook->addformat();
$format2->set_align('left');
$format2->set_text_wrap();
$format2->set_merge();
$format2->set_align('vcenter');

$yellow =& $workbook->addformat();
$yellow->set_fg_color('yellow');
$yellow->set_border('1');
$yellow->set_border_color('black');
$yellow->set_align('right');

$yellow2 =& $workbook->addformat();
$yellow2->set_fg_color('yellow');
$yellow2->set_border('1');
$yellow2->set_border_color('black');
$yellow2->set_align('left');
$yellow2->set_text_wrap();

//$format2->set_bold();
//$format2->set_size(15);

# Set the column width for columns 1, 2 and 3
$worksheet->set_column(0, 0, 25);
$worksheet->set_column(2, 2, 15);

//Write some text
$worksheet->write(0, 0,  escapeInputs($_POST['cell1_1']), $heading);
$worksheet->write(2, 0,  escapeInputs($_POST['cell3_1']), $format2);
$worksheet->write(3, 0,  escapeInputs($_POST['cell4_1']), $right);

if ($_POST['cnt']>8)
 {
  $worksheet->write(5, 0,  "ID", $left);
  $worksheet->write(5, 1,  "Tree", $left);
  $worksheet->write(5, 2,  "Requirement", $left);
  $worksheet->write(5, 3,  "Weight", $left);
  $worksheet->write(5, 4,  "Supplier A", $left);
  $worksheet->write(5, 6,  "Supplier B", $left);
  $worksheet->write(5, 8,  "Supplier C", $left);
  $worksheet->write(5, 10,  "Supplier D", $left);

  $worksheet->write(6, 4,  "points", $format2);
  $worksheet->write(6, 5,  "weigthed points", $format2);
  $worksheet->write(6, 6,  "points", $format2);
  $worksheet->write(6, 7,  "weigthed points", $format2);
  $worksheet->write(6, 8,  "points", $format2);
  $worksheet->write(6, 9,  "weigthed points", $format2);
  $worksheet->write(6, 10,  "points", $format2);
  $worksheet->write(6, 11,  "weigthed points", $format2);

  for ($i=8;$i<$_POST['cnt'];$i++)
   {
    $tmp1="cell".$i."_1";
    $worksheet->write($i, 0,  escapeInputs($$tmp1), $right);
    $tmp2="cell".$i."_2";
    $worksheet->write($i, 1,  escapeInputs($$tmp2), $left);
    $tmp3="cell".$i."_3";
    $worksheet->write($i, 2,  escapeInputs($$tmp3), $left);
    $tmp4="cell".$i."_4";
    $worksheet->write($i, 3,  escapeInputs($$tmp4), $right);
    $worksheet->write($i, 4,  '', $yellow);
    $worksheet->write($i, 5,  '=D'.($i+1).' * E'.($i+1).'', $right);
    $worksheet->write($i, 6,  '', $yellow);
    $worksheet->write($i, 7,  '=D'.($i+1).' * G'.($i+1).'', $right);
    $worksheet->write($i, 8,  '', $yellow);
    $worksheet->write($i, 9,  '=D'.($i+1).' * I'.($i+1).'', $right);
    $worksheet->write($i, 10,  '', $yellow);
    $worksheet->write($i, 11,  '=D'.($i+1).' * K'.($i+1).'', $right);
   }
 
  $worksheet->write($i+1, 3,  '=SUM(D8:D'.($i+1).')', $right);
  $worksheet->write($i+1, 5,  '=SUM(F8:F'.($i+1).')', $right);
  $worksheet->write($i+1, 7,  '=SUM(H8:H'.($i+1).')', $right);
  $worksheet->write($i+1, 9,  '=SUM(J8:J'.($i+1).')', $right);
  $worksheet->write($i+1, 11,  '=SUM(L8:L'.($i+1).')', $right);
  $worksheet->write($i+4, 0,  '* only fill in values between 0 (not at all) - 10 (perfect fit)', $yellow2);
 }
 
$workbook->close();

header("Content-Type: application/x-msexcel; charset=iso-8859-1; name=\"".$_POST['filename']."\"");
header("Content-Disposition: inline; filename=\"".$_POST['filename']."\"");
$fh=fopen($fname, "rb");
fpassthru($fh);
unlink($fname);
 

?>
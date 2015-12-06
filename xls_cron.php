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

ini_set("memory_limit","-1");
include ("admin/inc/conn.php");//include settings file
include ("admin/inc/func.php");//include functions file
include ("ini/params.php");//include configuration file
$p_id=22;
$description=true;
$project=true;
$subproject=true;
$release=true;
$test_case=true;
$stakeholder=true;
$glossary=true;
$state=true;
$type=true;
$priority=true;
$assign_to=true;
$rid=true;
$version=true;
$component=true;
$source=true;
$risk=true;
$complexity=true;
$open_points=true;
$keywords=true;
$satisfaction=true;
$dissatisfaction=true;
$depends=true;
$conflicts=true;
$author=true;
$url=true;
$parent=true;
$userfields=true;
$creation_date=true;
$last_change=true;
$accepted_date=true;
$accepted_user=true;
$comments=true;
$weight=true;

if ($p_id=="") die("No project selected");

function getExcelLetters($no)
 {
  $out1 = '';$out2 = '';
  if ($no<26) $out1=chr($no+65);
  if ($no>=26 && $no<52) {$out1='A';$out2=chr($no+65-26);}
  if ($no>=52 && $no<78) {$out1='B';$out2=chr($no+65-52);}
  if ($no>=78 && $no<104) {$out1='C';$out2=chr($no+65-78);}
  return $out1.$out2;
 }
 
//default language
if ($_chlang!="") $_SESSION['chlang']=$_chlang;
if (!$_SESSION['chlang']) $_SESSION['chlang']="en";
include ("ini/lng/".$_SESSION['chlang'].".php");//include language file


include_once("ini/txts/".$_SESSION['chlang']."/complexity.php");
include_once("ini/txts/".$_SESSION['chlang']."/state.php");
include_once("ini/txts/".$_SESSION['chlang']."/type.php");
include_once("ini/txts/".$_SESSION['chlang']."/risk.php");

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


  //getting ids from filter 
  if ($ids=="" && $srch!="") 
   {
    $query="select r.r_id from requirements r left outer join projects p on r.r_p_id=p.p_id left outer join users u on r.r_u_id=u.u_id left outer join users u2 on r.r_assigned_u_id=u2.u_id where r.r_p_id=".$p_id." ".stripslashes(stripslashes(stripslashes(stripslashes(stripslashes($srch)))));
    $rs = mysql_query($query) or die(mysql_error());
    while($row=mysql_fetch_array($rs)) 
     {
      $ids.=$row[0].",";
     }
   }



//getting data for project
$query="select * from projects where p_id=".$p_id;
$rs = mysql_query($query) or die(mysql_error());
if($row=mysql_fetch_array($rs)) 
 {
  $p_name=htmlspecialchars($row['p_name']);
  $p_desc=$row['p_desc'];
  //$p_desc=str_replace("<br />","\n",$p_desc);
  //$p_desc=str_replace("<br />"," ",$p_desc);
  //$p_desc=str_replace("<br/>"," ",$p_desc);
  //$p_desc=str_replace("<br>"," ",$p_desc);
  //$p_desc=str_replace("<p>"," ",$p_desc);
  $p_desc=strip_tags($p_desc);
  $p_desc=str_replace("&amp;","&",$p_desc);
  $p_desc=str_replace("&amp;","&",$p_desc);
  $p_desc=str_replace("\r","",$p_desc);
  //$p_desc=str_replace("\n","",$p_desc);
 }

$filename=str_replace(",","",$p_name);
$filename=str_replace("'","",$filename);
$filename=str_replace("\"","",$filename);
$filename=str_replace(" ","_",$filename);
$filename.="_" . date("d-m-Y").".xls";


// Create a first sheet, representing sales data
//echo date('H:i:s') . " Add some data\n";

$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
$objPHPExcel->getActiveSheet()->setCellValue('A1', $lng[2][1].' '.$p_name);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);

$objPHPExcel->getActiveSheet()->setCellValue('A3', $p_desc);
$objPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setWrapText(true);

$objPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$objPHPExcel->getActiveSheet()->setCellValue('A4', date("d.m.Y"));
$objPHPExcel->getActiveSheet()->getStyle('A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('A4')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);

$objPHPExcel->getActiveSheet()->setCellValue('A6', 'ID');
$objPHPExcel->getActiveSheet()->setCellValue('B6', 'Tree');
$objPHPExcel->getActiveSheet()->setCellValue('C6', 'Requirement');
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);

if ($weight) 
 {
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
 }

//getting tree array
//if ($ids=="") $query="select * from requirements where r_p_id=".$p_id." and r_parent_id=0 order by r_pos asc";
//else $query="select * from requirements where r_p_id=".$p_id." and r_parent_id=0 and r_id in (".$ids."0) order by r_pos asc";

//if ($ids=="") $query="select * from requirements where r_p_id=".$p_id." order by r_pos asc";
//else $query="select * from requirements where r_p_id=".$p_id." and r_id in (".$ids."0) order by r_pos asc";

$query="select * from requirements where r_p_id=".$p_id." and r_parent_id=0 order by r_pos asc";
$rs = mysql_query($query) or die(mysql_error());
$cnt=0;
while($row=mysql_fetch_array($rs)) 
 {
  $cnt++;
  $arr[]=$cnt."|".$row['r_id'];
  getTree2($row['r_id'],$cnt,$arr);
 }

$i=8;$flag=1;
while ($cnt>0 && list ($key, $val) = each ($arr)) 
 {
  $fields=3;
  $query="select r.*, date_format(r.r_creation_date, '%d.%m.%Y %H:%i') as d1, date_format(r.r_change_date, '%d.%m.%Y %H:%i') as d2, date_format(r.r_accept_date, '%d.%m.%Y %H:%i') as d3, u.u_name, p.p_name, p.p_id, sp.s_id, sp.s_name from requirements r left outer join users u on r.r_u_id=u.u_id left outer join projects p on r.r_p_id=p.p_id left outer join subprojects sp on r.r_s_id=sp.s_id where r.r_id=".substr($val,strpos($val,"|")+1);
  $rs = mysql_query($query) or die(mysql_error());
  while($row=mysql_fetch_array($rs)) 
   {
    $glossary_ids="";$cases_ids="";

    if ($ids=="" || ($ids!="" && strstr(",".$ids.",",",".$row['r_id'].","))) 
    {
    $objPHPExcel->getActiveSheet()->setCellValue('A'.$i, $row["r_id"]);
    $objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
    $objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

    $objPHPExcel->getActiveSheet()->setCellValue('B'.$i, substr($val,0,strpos($val,"|")));
    $objPHPExcel->getActiveSheet()->getStyle('B'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    $objPHPExcel->getActiveSheet()->getStyle('B'.$i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

    $objPHPExcel->getActiveSheet()->setCellValue('C'.$i, $row["r_name"]);
    $objPHPExcel->getActiveSheet()->getStyle('C'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    $objPHPExcel->getActiveSheet()->getStyle('C'.$i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    
    if ($weight) 
     {
      $objPHPExcel->getActiveSheet()->setCellValue('D'.$i, $row["r_weight"]);
      $objPHPExcel->getActiveSheet()->getStyle('D'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
      $objPHPExcel->getActiveSheet()->getStyle('D'.$i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

      $objPHPExcel->getActiveSheet()->getStyle('E'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
      $objPHPExcel->getActiveSheet()->getStyle('E'.$i)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
      $objPHPExcel->getActiveSheet()->getStyle('E'.$i)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
      $objPHPExcel->getActiveSheet()->getStyle('E'.$i)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
      $objPHPExcel->getActiveSheet()->getStyle('E'.$i)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
      $objPHPExcel->getActiveSheet()->getStyle('E'.$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $objPHPExcel->getActiveSheet()->getStyle('E'.$i)->getFill()->getStartColor()->setARGB('FFFFFF00');
      $objPHPExcel->getActiveSheet()->getStyle('E'.$i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

      $objPHPExcel->getActiveSheet()->setCellValue('F'.$i, '=D'.$i.'*E'.$i);
      $objPHPExcel->getActiveSheet()->getStyle('F'.$i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

      $objPHPExcel->getActiveSheet()->getStyle('G'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
      $objPHPExcel->getActiveSheet()->getStyle('G'.$i)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
      $objPHPExcel->getActiveSheet()->getStyle('G'.$i)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
      $objPHPExcel->getActiveSheet()->getStyle('G'.$i)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
      $objPHPExcel->getActiveSheet()->getStyle('G'.$i)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
      $objPHPExcel->getActiveSheet()->getStyle('G'.$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $objPHPExcel->getActiveSheet()->getStyle('G'.$i)->getFill()->getStartColor()->setARGB('FFFFFF00');
      $objPHPExcel->getActiveSheet()->getStyle('G'.$i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

      $objPHPExcel->getActiveSheet()->setCellValue('H'.$i, '=D'.$i.'*G'.$i);
      $objPHPExcel->getActiveSheet()->getStyle('H'.$i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

      $objPHPExcel->getActiveSheet()->getStyle('I'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
      $objPHPExcel->getActiveSheet()->getStyle('I'.$i)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
      $objPHPExcel->getActiveSheet()->getStyle('I'.$i)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
      $objPHPExcel->getActiveSheet()->getStyle('I'.$i)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
      $objPHPExcel->getActiveSheet()->getStyle('I'.$i)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
      $objPHPExcel->getActiveSheet()->getStyle('I'.$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $objPHPExcel->getActiveSheet()->getStyle('I'.$i)->getFill()->getStartColor()->setARGB('FFFFFF00');
      $objPHPExcel->getActiveSheet()->getStyle('I'.$i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

      $objPHPExcel->getActiveSheet()->setCellValue('J'.$i, '=D'.$i.'*I'.$i);
      $objPHPExcel->getActiveSheet()->getStyle('J'.$i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

      $objPHPExcel->getActiveSheet()->getStyle('K'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
      $objPHPExcel->getActiveSheet()->getStyle('K'.$i)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
      $objPHPExcel->getActiveSheet()->getStyle('K'.$i)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
      $objPHPExcel->getActiveSheet()->getStyle('K'.$i)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
      $objPHPExcel->getActiveSheet()->getStyle('K'.$i)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
      $objPHPExcel->getActiveSheet()->getStyle('K'.$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $objPHPExcel->getActiveSheet()->getStyle('K'.$i)->getFill()->getStartColor()->setARGB('FFFFFF00');
      $objPHPExcel->getActiveSheet()->getStyle('K'.$i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

      $objPHPExcel->getActiveSheet()->setCellValue('L'.$i, '=D'.$i.'*K'.$i);
      $objPHPExcel->getActiveSheet()->getStyle('L'.$i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $fields+=9;$flag=0;
     } 
     
    if ($description) 
     {
      if ($i==8) 
       {
        $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-2), $lng[15][5]);
        $objPHPExcel->getActiveSheet()->getColumnDimension(getExcelLetters($fields))->setAutoSize(true);
       } 
      $r_desc=$row['r_desc'];
      $r_desc=strip_tags($r_desc);
      $r_desc=str_replace("&amp;","&",$r_desc);
      $r_desc=str_replace("&amp;","&",$r_desc);
      $r_desc=str_replace("\r","",$r_desc);

      //$r_desc=str_replace("<br />"," ",$r_desc);
      //$r_desc=str_replace("<br/>"," ",$r_desc);
      //$r_desc=str_replace("<br>"," ",$r_desc);
      //$r_desc=str_replace("<p>"," ",$r_desc);
      //$r_desc=strip_tags($r_desc);
          
      $objPHPExcel->getActiveSheet()->getStyle(getExcelLetters($fields).($i))->getAlignment()->setWrapText(true);
      //$objPHPExcel->getActiveSheet()->getStyle(getExcelLetters($fields).($i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i), $r_desc);
      $fields++;
     } 
    if ($project) 
     {
      if ($i==8) $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-2), $lng[15][3]);
      //$objPHPExcel->getActiveSheet()->getStyle(getExcelLetters($fields).($i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i), $row['p_name']);
      $fields++;
     } 
    if ($subproject) 
     {
      if ($i==8) $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-2), $lng[15][97]);
      //$objPHPExcel->getActiveSheet()->getStyle(getExcelLetters($fields).($i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i), $row['s_name']);
      $fields++;
     } 
    if ($release) 
     {
      if ($i==8) $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-2), $lng[15][24]);
      //$objPHPExcel->getActiveSheet()->getStyle(getExcelLetters($fields).($i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $tmp_str="";
      $query22="select r.*, date_format(r.r_date, '%d.%m.%Y') as d1, date_format(r.r_released_date, '%d.%m.%Y') as d2 from project_releases pr left outer join releases r on pr.pr_r_id=r.r_id where pr.pr_p_id='".$row['p_id']."' order by r.r_name asc";
      $rs22 = mysql_query($query22) or die(mysql_error());
      while($row22=mysql_fetch_array($rs22))
       {
        $tmp_str.=htmlspecialchars($row22['r_name'])." (".$row22['d1'].")";
        if ($row22['d2']!="00.00.0000") $tmp_str.=" - ".$row22['d2'];	      
        $tmp_str.="; ";
       }
      $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i), $tmp_str);
      $fields++;
     } 
    if ($test_case) 
     {
      if ($i==8) $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-2), $lng[15][103]);
      //$objPHPExcel->getActiveSheet()->getStyle(getExcelLetters($fields).($i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $tmp_str="";
      $query22="select * from cases where c_id in (".$row['r_c_id']."0) order by c_name asc";
      $rs22 = mysql_query($query22) or die(mysql_error());
      while($row22=mysql_fetch_array($rs22))
       {
	$cases_ids.=$row22['c_id'].",";
        $tmp_str.=htmlspecialchars($row22['c_name']);
        $tmp_str.="; ";
       }
      $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i), $tmp_str);
      $fields++;
     } 
    if ($stakeholder) 
     {
      if ($i==8) $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-2), $lng[15][76]);
      //$objPHPExcel->getActiveSheet()->getStyle(getExcelLetters($fields).($i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $tmp_str="";
      $query22="select * from stakeholders where s_id in (".$row['r_stakeholder']."0) order by s_name asc";
      $rs22 = mysql_query($query22) or die(mysql_error());
      while($row22=mysql_fetch_array($rs22))
       {
        $tmp_str.=htmlspecialchars($row22['s_name']);
        $tmp_str.="; ";
       }
      $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i), $tmp_str);
      $fields++;
     } 
    if ($glossary) 
     {
      if ($i==8) $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-2), $lng[15][86]);
      //$objPHPExcel->getActiveSheet()->getStyle(getExcelLetters($fields).($i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $tmp_str="";
      $query22="select g.* from project_glossary pg left outer join glossary g on pg.pg_g_id=g.g_id where pg.pg_p_id='".$row['p_id']."' order by g.g_id asc";
      $rs22 = mysql_query($query22) or die(mysql_error());
      while($row22=mysql_fetch_array($rs22))
       {
	$glossary_ids.=$row22['g_id'].",";
	for ($k=0;$k<6-strlen($row22['g_id']);$k++) $tmp_str.="0";$tmp_str.=htmlspecialchars($row22['g_id']);
        $tmp_str.="; ";
       }
      $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i), $tmp_str);
      $fields++;
     } 
    if ($state) 
     {
      if ($i==8) $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-2), $lng[15][10]);
      //$objPHPExcel->getActiveSheet()->getStyle(getExcelLetters($fields).($i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i), $state_array[$row['r_state']]);
      $fields++;
     } 
    if ($type) 
     {
      if ($i==8) $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-2), $lng[15][11]);
      //$objPHPExcel->getActiveSheet()->getStyle(getExcelLetters($fields).($i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i), $type_array[$row['r_type_r']]);
      $fields++;
     } 
    if ($priority) 
     {
      if ($i==8) $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-2), $lng[15][13]);
      //$objPHPExcel->getActiveSheet()->getStyle(getExcelLetters($fields).($i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i), $row['r_priority']);
      $fields++;
     } 
    if ($assign_to) 
     {
      if ($i==8) $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-2), $lng[15][30]);
      //$objPHPExcel->getActiveSheet()->getStyle(getExcelLetters($fields).($i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

	       if ($row['r_assigned_u_id']!=0)
	        {
	         $query4="select * from users where u_id=".$row['r_assigned_u_id'];
 		 $rs4 = mysql_query($query4) or die(mysql_error());
  		 if($row4=mysql_fetch_array($rs4)) $r_assigned_u_id=" ".htmlspecialchars($row4['u_name']);
  		} 
  	       else $r_assigned_u_id=" -";	


      $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i), $r_assigned_u_id);
      $fields++;
     } 
    if ($rid) 
     {
      if ($i==8) $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-2), $lng[15][45]);
      //$objPHPExcel->getActiveSheet()->getStyle(getExcelLetters($fields).($i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i), $row['r_id']);
      $fields++;
     } 
    if ($version) 
     {
      if ($i==8) $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-2), $lng[15][34]);
      //$objPHPExcel->getActiveSheet()->getStyle(getExcelLetters($fields).($i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i), $row['r_version']);
      $fields++;
     } 
    if ($component) 
     {
      $tmp_str="";
      if ($i==8) $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-2), $lng[15][40]);
      //$objPHPExcel->getActiveSheet()->getStyle(getExcelLetters($fields).($i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $r_component_arr=explode(",",$row['r_component']);
      while (list ($key, $val) = each ($r_component_arr)) if ($component_array[$val]!="") $tmp_str.=$component_array[$val]."; ";
      $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i), $tmp_str);
      $fields++;
     } 
    if ($source) 
     {
      if ($i==8) $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-2), $lng[15][41]);
      //$objPHPExcel->getActiveSheet()->getStyle(getExcelLetters($fields).($i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i), $row['r_source']);
      $fields++;
     } 
    if ($risk) 
     {
      if ($i==8) $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-2), $lng[15][42]);
      //$objPHPExcel->getActiveSheet()->getStyle(getExcelLetters($fields).($i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i), $risk_array[$row['r_risk']]);
      $fields++;
     } 
    if ($complexity) 
     {
      if ($i==8) $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-2), $lng[15][43]);
      //$objPHPExcel->getActiveSheet()->getStyle(getExcelLetters($fields).($i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i), $complexity_array[$row['r_complexity']]);
      $fields++;
     } 
    if ($open_points) 
     {
      if ($i==8) $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-2), $lng[15][44]);
      //$objPHPExcel->getActiveSheet()->getStyle(getExcelLetters($fields).($i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i), $row['r_points']);
      $fields++;
     } 
    if ($keywords) 
     {
      $tmp_str="";
      if ($i==8) $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-2), $lng[15][107]);
      //$objPHPExcel->getActiveSheet()->getStyle(getExcelLetters($fields).($i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	
	      $query456="select k_id, k_name from keywords where k_id in (".$row['r_keywords']."0)";
              $rs456 = mysql_query($query456) or die(mysql_error());	        
	      while($row456=mysql_fetch_array($rs456)) $tmp_str.=htmlspecialchars($row456['k_name'])."; ";

      $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i), $tmp_str);
      $fields++;
     } 
    if ($satisfaction) 
     {
      if ($i==8) $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-2), $lng[15][82]);
      //$objPHPExcel->getActiveSheet()->getStyle(getExcelLetters($fields).($i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i), $row['r_satisfaction']);
      $fields++;
     } 
    if ($dissatisfaction) 
     {
      if ($i==8) $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-2), $lng[15][84]);
      //$objPHPExcel->getActiveSheet()->getStyle(getExcelLetters($fields).($i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i), $row['r_dissatisfaction']);
      $fields++;
     } 
    if ($depends) 
     {
      if ($i==8) $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-2), $lng[15][78]);
      //$objPHPExcel->getActiveSheet()->getStyle(getExcelLetters($fields).($i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $tmp_str="";
      
      	      $query456="select r_id, r_name from requirements where r_id in (".$row['r_depends']."0)";
              $rs456 = mysql_query($query456) or die(mysql_error());	        
	      while($row456=mysql_fetch_array($rs456)) $tmp_str.=htmlspecialchars($row456['r_name'])."; ";
	        
      $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i), $tmp_str);
      $fields++;
     } 
    if ($conflicts) 
     {
      if ($i==8) $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-2), $lng[15][80]);
      //$objPHPExcel->getActiveSheet()->getStyle(getExcelLetters($fields).($i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $tmp_str="";
      
      	      $query456="select r_id, r_name from requirements where r_id in (".$row['r_conflicts']."0)";
              $rs456 = mysql_query($query456) or die(mysql_error());	        
	      while($row456=mysql_fetch_array($rs456)) $tmp_str.=htmlspecialchars($row456['r_name'])."; ";
	        
      $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i), $tmp_str);
      $fields++;
     } 
    if ($author) 
     {
      if ($i==8) $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-2), $lng[15][16]);
      //$objPHPExcel->getActiveSheet()->getStyle(getExcelLetters($fields).($i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $tmp_str="";
      
	       if ($row['r_u_id']!=0)
	        {
	         $query4="select * from users where u_id=".$row['r_u_id'];
 		 $rs4 = mysql_query($query4) or die(mysql_error());
 		 //echo $query4;
  		 if($row4=mysql_fetch_array($rs4)) $tmp_str=" ".htmlspecialchars($row4['u_name']);
  		} 
  	       else $tmp_str=" -";	
      $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i), $tmp_str);
      $fields++;
     } 
    if ($url) 
     {
      if ($i==8) $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-2), $lng[15][14]);
      //$objPHPExcel->getActiveSheet()->getStyle(getExcelLetters($fields).($i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i), $row['r_link']);
      $fields++;
     } 
    if ($parent) 
     {
      if ($i==8) $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-2), $lng[15][38]);
      //$objPHPExcel->getActiveSheet()->getStyle(getExcelLetters($fields).($i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $tmp_str="";
      
      	      $query456="select r_name from requirements where r_id=".$row['r_parent_id'];
              $rs456 = mysql_query($query456) or die(mysql_error());	        
	      while($row456=mysql_fetch_array($rs456)) $tmp_str.=htmlspecialchars($row456['r_name'])."; ";
	        
      $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i), $tmp_str);
      $fields++;
     } 
     /*
    if ($position) 
     {
      if ($i==8) $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-2), $lng[15][39]);
      //$objPHPExcel->getActiveSheet()->getStyle(getExcelLetters($fields).($i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i), $row['r_pos']);
      $fields++;
     }*/ 
    if ($userfields) 
     {
	  $cnt_u=0;
	  $query41="select count(*) from user_fields where uf_name_en<>''";
	  $rs41=mysql_query($query41) or die(mysql_error());
	  if($row41=mysql_fetch_array($rs41)) $cnt_num=$row41[0];
		   
	  $query41="select * from user_fields order by uf_id asc";
	  $rs41=mysql_query($query41) or die(mysql_error());
	  while($row41=mysql_fetch_array($rs41))
	   {
	    $uf_name[]=htmlspecialchars($row41['uf_name_'.$_SESSION['chlang']]);
	    $uf_text[]=htmlspecialchars($row41['uf_text_'.$_SESSION['chlang']]);
           } 
     
      for($k=0;$k<$cnt_num;$k++)
       {
        if ($i==8) $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-2), $uf_name[$k]);
      //$objPHPExcel->getActiveSheet()->getStyle(getExcelLetters($fields).($i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i), $row['r_userfield'.($k+1)]);
        $fields++;       
       }

     } 
    if ($creation_date) 
     {
      if ($i==8) $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-2), $lng[15][17]);
      //$objPHPExcel->getActiveSheet()->getStyle(getExcelLetters($fields).($i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      if ($row['r_creation_date']=="00.00.0000 00:00") $r_creation_date="-"; else $r_creation_date=$row['r_creation_date'];
      $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i), $r_creation_date);
      $fields++;
     } 
    if ($last_change) 
     {
      if ($i==8) $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-2), $lng[15][18]);
      //$objPHPExcel->getActiveSheet()->getStyle(getExcelLetters($fields).($i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      if ($row['r_change_date']=="00.00.0000 00:00") $r_change_date="-"; else $r_change_date=$row['r_change_date'];
      $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i), $r_change_date);
      $fields++;
     } 
    if ($accepted_date) 
     {
      if ($i==8) $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-2), $lng[15][19]);
      //$objPHPExcel->getActiveSheet()->getStyle(getExcelLetters($fields).($i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      if ($row['r_accept_date']=="00.00.0000 00:00") $r_accept_date="-"; else $r_accept_date=$row['r_accept_date'];
      $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i), $r_accept_date);
      $fields++;
     } 
    if ($accepted_user) 
     {
      if ($i==8) $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-2), $lng[15][20]);
      //$objPHPExcel->getActiveSheet()->getStyle(getExcelLetters($fields).($i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $tmp_str="";
      
	       if ($row['r_accept_user']!=0)
	        {
	         $query4="select * from users where u_id=".$row['r_accept_user'];
 		 $rs4 = mysql_query($query4) or die(mysql_error());
  		 if($row4=mysql_fetch_array($rs4)) $tmp_str=" ".htmlspecialchars($row4['u_name']);
  		} 
  	       else $tmp_str=" -";	
	        
      $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i), $tmp_str);
      $fields++;
     } 
    if ($comments) 
     {
      if ($i==8) $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-2), $lng[15][114]);
      $objPHPExcel->getActiveSheet()->getStyle(getExcelLetters($fields).($i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $query44="select c.*, u.u_name, date_format(c.c_date, '%d.%m.%Y %H:%i') as d1 from comments c left outer join users u on c.c_u_id=u.u_id where c.c_r_id=".$row["r_id"]." order by c_date desc";
      $rs44 = mysql_query($query44) or die(mysql_error());
      while($row44=mysql_fetch_array($rs44))
       {
        $tmp_str=htmlspecialchars($row44['u_name']);
        if ($row44['c_date']!="00.00.0000") $tmp_str.=" (".$row44['c_date'].")";
        $c_text=str_replace("&nbsp;"," ",$row44['c_text']);
        $tmp_str=strip_tags($c_text);
        //if ($i==8) $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-2), $lng[15][114]);
        $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i), $tmp_str);
        $fields++;
       } 
     } 
    
    
    $i++; 
     }
   }
 } 
 
if ($weight) 
 {
  $objPHPExcel->getActiveSheet()->setCellValue('D'.($i+1), '=SUM(D8:D'.$i.')');
  $objPHPExcel->getActiveSheet()->setCellValue('F'.($i+1), '=SUM(F8:F'.$i.')');
  $objPHPExcel->getActiveSheet()->setCellValue('H'.($i+1), '=SUM(H8:H'.$i.')');
  $objPHPExcel->getActiveSheet()->setCellValue('J'.($i+1), '=SUM(J8:J'.$i.')');
  $objPHPExcel->getActiveSheet()->setCellValue('L'.($i+1), '=SUM(L8:L'.$i.')');

  $objPHPExcel->getActiveSheet()->setCellValue('A'.($i+4), '* only fill in values between 0 (not at all) - 10 (perfect fit)');
  $objPHPExcel->getActiveSheet()->getStyle('A'.($i+4))->getAlignment()->setWrapText(true);
  $objPHPExcel->getActiveSheet()->getStyle('A'.($i+4))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
  $objPHPExcel->getActiveSheet()->getStyle('A'.($i+4))->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
  $objPHPExcel->getActiveSheet()->getStyle('A'.($i+4))->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
  $objPHPExcel->getActiveSheet()->getStyle('A'.($i+4))->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
  $objPHPExcel->getActiveSheet()->getStyle('A'.($i+4))->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
  $objPHPExcel->getActiveSheet()->getStyle('A'.($i+4))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
  $objPHPExcel->getActiveSheet()->getStyle('A'.($i+4))->getFill()->getStartColor()->setARGB('FFFFFF00');
  $objPHPExcel->getActiveSheet()->getStyle('A'.($i+4))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
  $objPHPExcel->getActiveSheet()->getStyle('A'.($i+4))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
  $i+=5;
 }
 $i+=3;
 
    if ($glossary) 
     {
      $fields=0;
      $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-1), $lng[24][1].":");
      $query44="select * from glossary where g_id in (".$glossary_ids."0) order by g_name asc";
      $rs44 = mysql_query($query44) or die(mysql_error());
      while($row44=mysql_fetch_array($rs44))
       {
        $fields=0;
        $g_id="";
        for ($k=0;$k<6-strlen($row44['g_id']);$k++) $g_id.="0";$g_id.=$row44['g_id'];
        $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields+1).($i), $g_id);
        $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields+2).($i), $row44['g_abbreviation']);
        $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields+3).($i), htmlspecialchars($row44['g_term']));
        $g_desc=str_replace("&nbsp;"," ",$row44['g_desc']);
        $g_desc=strip_tags($g_desc);
        $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields+4).($i), $g_desc);
        $i++;
       } 
     } 
     
    if ($test_case) 
     {
      $i+=2;
      $fields=0;
      $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-1), $lng[15][103].":");
      $query44="select * from cases where c_id in (".$cases_ids."0) order by c_name asc";
      $rs44 = mysql_query($query44) or die(mysql_error());
      while($row44=mysql_fetch_array($rs44))
       {
        $fields=0;
        $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields+1).($i), htmlspecialchars($row44['c_name']));
        if ($row44['c_status']==0) $c_stat=$lng[31][6];
        elseif ($row44['c_status']==1) $c_stat=$lng[31][7];
        $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields+2).($i), $c_stat);
        $c_desc=str_replace("&nbsp;"," ",$row44['c_desc']);
        $c_desc=strip_tags($c_desc);
        $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields+3).($i), $c_desc);
        $c_result=str_replace("&nbsp;"," ",$row44['c_result']);
        $c_result=strip_tags($c_result);
        $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields+4).($i), $c_result);
       } 
     } 

$query="select * from requirements where r_p_id=".$p_id." and r_parent_id=0 order by r_pos asc";
$rs = mysql_query($query) or die(mysql_error());
$cnt=0;
while($row=mysql_fetch_array($rs)) 
 {
  $cnt++;
  $arr[]=$cnt."|".$row['r_id'];
  getTree2($row['r_id'],$cnt,$arr);
 }

$i=8;$flag=1;
while ($cnt>0 && list ($key, $val) = each ($arr)) 
 {
  $fields=3;
  $query="select r.*, date_format(r.r_creation_date, '%d.%m.%Y %H:%i') as d1, date_format(r.r_change_date, '%d.%m.%Y %H:%i') as d2, date_format(r.r_accept_date, '%d.%m.%Y %H:%i') as d3, u.u_name, p.p_name, p.p_id, sp.s_id, sp.s_name from requirements r left outer join users u on r.r_u_id=u.u_id left outer join projects p on r.r_p_id=p.p_id left outer join subprojects sp on r.r_s_id=sp.s_id where r.r_id=".substr($val,strpos($val,"|")+1);
  $rs = mysql_query($query) or die(mysql_error());
  while($row=mysql_fetch_array($rs)) 
   {
    $glossary_ids="";$cases_ids="";

    if ($ids=="" || ($ids!="" && strstr(",".$ids.",",",".$row['r_id'].","))) 
    {
    $objPHPExcel->getActiveSheet()->setCellValue('A'.$i, $row["r_id"]);
    $objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
    $objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

    $objPHPExcel->getActiveSheet()->setCellValue('B'.$i, substr($val,0,strpos($val,"|")));
    $objPHPExcel->getActiveSheet()->getStyle('B'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    $objPHPExcel->getActiveSheet()->getStyle('B'.$i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

    $objPHPExcel->getActiveSheet()->setCellValue('C'.$i, $row["r_name"]);
    $objPHPExcel->getActiveSheet()->getStyle('C'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    $objPHPExcel->getActiveSheet()->getStyle('C'.$i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    
    if ($weight) 
     {
      $objPHPExcel->getActiveSheet()->setCellValue('D'.$i, $row["r_weight"]);
      $objPHPExcel->getActiveSheet()->getStyle('D'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
      $objPHPExcel->getActiveSheet()->getStyle('D'.$i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

      $objPHPExcel->getActiveSheet()->getStyle('E'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
      $objPHPExcel->getActiveSheet()->getStyle('E'.$i)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
      $objPHPExcel->getActiveSheet()->getStyle('E'.$i)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
      $objPHPExcel->getActiveSheet()->getStyle('E'.$i)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
      $objPHPExcel->getActiveSheet()->getStyle('E'.$i)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
      $objPHPExcel->getActiveSheet()->getStyle('E'.$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $objPHPExcel->getActiveSheet()->getStyle('E'.$i)->getFill()->getStartColor()->setARGB('FFFFFF00');
      $objPHPExcel->getActiveSheet()->getStyle('E'.$i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

      $objPHPExcel->getActiveSheet()->setCellValue('F'.$i, '=D'.$i.'*E'.$i);
      $objPHPExcel->getActiveSheet()->getStyle('F'.$i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

      $objPHPExcel->getActiveSheet()->getStyle('G'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
      $objPHPExcel->getActiveSheet()->getStyle('G'.$i)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
      $objPHPExcel->getActiveSheet()->getStyle('G'.$i)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
      $objPHPExcel->getActiveSheet()->getStyle('G'.$i)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
      $objPHPExcel->getActiveSheet()->getStyle('G'.$i)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
      $objPHPExcel->getActiveSheet()->getStyle('G'.$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $objPHPExcel->getActiveSheet()->getStyle('G'.$i)->getFill()->getStartColor()->setARGB('FFFFFF00');
      $objPHPExcel->getActiveSheet()->getStyle('G'.$i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

      $objPHPExcel->getActiveSheet()->setCellValue('H'.$i, '=D'.$i.'*G'.$i);
      $objPHPExcel->getActiveSheet()->getStyle('H'.$i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

      $objPHPExcel->getActiveSheet()->getStyle('I'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
      $objPHPExcel->getActiveSheet()->getStyle('I'.$i)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
      $objPHPExcel->getActiveSheet()->getStyle('I'.$i)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
      $objPHPExcel->getActiveSheet()->getStyle('I'.$i)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
      $objPHPExcel->getActiveSheet()->getStyle('I'.$i)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
      $objPHPExcel->getActiveSheet()->getStyle('I'.$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $objPHPExcel->getActiveSheet()->getStyle('I'.$i)->getFill()->getStartColor()->setARGB('FFFFFF00');
      $objPHPExcel->getActiveSheet()->getStyle('I'.$i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

      $objPHPExcel->getActiveSheet()->setCellValue('J'.$i, '=D'.$i.'*I'.$i);
      $objPHPExcel->getActiveSheet()->getStyle('J'.$i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

      $objPHPExcel->getActiveSheet()->getStyle('K'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
      $objPHPExcel->getActiveSheet()->getStyle('K'.$i)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
      $objPHPExcel->getActiveSheet()->getStyle('K'.$i)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
      $objPHPExcel->getActiveSheet()->getStyle('K'.$i)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
      $objPHPExcel->getActiveSheet()->getStyle('K'.$i)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
      $objPHPExcel->getActiveSheet()->getStyle('K'.$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $objPHPExcel->getActiveSheet()->getStyle('K'.$i)->getFill()->getStartColor()->setARGB('FFFFFF00');
      $objPHPExcel->getActiveSheet()->getStyle('K'.$i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

      $objPHPExcel->getActiveSheet()->setCellValue('L'.$i, '=D'.$i.'*K'.$i);
      $objPHPExcel->getActiveSheet()->getStyle('L'.$i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $fields+=9;$flag=0;
     } 
     
    if ($description) 
     {
      if ($i==8) $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-2), $lng[15][5]);
      $r_desc=$row['r_desc'];
      $r_desc=strip_tags($r_desc);
      $r_desc=str_replace("&amp;","&",$r_desc);
      $r_desc=str_replace("&amp;","&",$r_desc);
      $r_desc=str_replace("\r","",$r_desc);

      //$r_desc=str_replace("<br />"," ",$r_desc);
      //$r_desc=str_replace("<br/>"," ",$r_desc);
      //$r_desc=str_replace("<br>"," ",$r_desc);
      //$r_desc=str_replace("<p>"," ",$r_desc);
      //$r_desc=strip_tags($r_desc);
          
      $objPHPExcel->getActiveSheet()->getStyle(getExcelLetters($fields).($i))->getAlignment()->setWrapText(true);
      //$objPHPExcel->getActiveSheet()->getStyle(getExcelLetters($fields).($i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i), $r_desc);
      $fields++;
     } 
    if ($project) 
     {
      if ($i==8) $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-2), $lng[15][3]);
      //$objPHPExcel->getActiveSheet()->getStyle(getExcelLetters($fields).($i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i), $row['p_name']);
      $fields++;
     } 
    if ($subproject) 
     {
      if ($i==8) $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-2), $lng[15][97]);
      //$objPHPExcel->getActiveSheet()->getStyle(getExcelLetters($fields).($i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i), $row['s_name']);
      $fields++;
     } 
    if ($release) 
     {
      if ($i==8) $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-2), $lng[15][24]);
      //$objPHPExcel->getActiveSheet()->getStyle(getExcelLetters($fields).($i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $tmp_str="";
      $query22="select r.*, date_format(r.r_date, '%d.%m.%Y') as d1, date_format(r.r_released_date, '%d.%m.%Y') as d2 from project_releases pr left outer join releases r on pr.pr_r_id=r.r_id where pr.pr_p_id='".$row['p_id']."' order by r.r_name asc";
      $rs22 = mysql_query($query22) or die(mysql_error());
      while($row22=mysql_fetch_array($rs22))
       {
        $tmp_str.=htmlspecialchars($row22['r_name'])." (".$row22['d1'].")";
        if ($row22['d2']!="00.00.0000") $tmp_str.=" - ".$row22['d2'];	      
        $tmp_str.="; ";
       }
      $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i), $tmp_str);
      $fields++;
     } 
    if ($test_case) 
     {
      if ($i==8) $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-2), $lng[15][103]);
      //$objPHPExcel->getActiveSheet()->getStyle(getExcelLetters($fields).($i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $tmp_str="";
      $query22="select * from cases where c_id in (".$row['r_c_id']."0) order by c_name asc";
      $rs22 = mysql_query($query22) or die(mysql_error());
      while($row22=mysql_fetch_array($rs22))
       {
	$cases_ids.=$row22['c_id'].",";
        $tmp_str.=htmlspecialchars($row22['c_name']);
        $tmp_str.="; ";
       }
      $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i), $tmp_str);
      $fields++;
     } 
    if ($stakeholder) 
     {
      if ($i==8) $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-2), $lng[15][76]);
      //$objPHPExcel->getActiveSheet()->getStyle(getExcelLetters($fields).($i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $tmp_str="";
      $query22="select * from stakeholders where s_id in (".$row['r_stakeholder']."0) order by s_name asc";
      $rs22 = mysql_query($query22) or die(mysql_error());
      while($row22=mysql_fetch_array($rs22))
       {
        $tmp_str.=htmlspecialchars($row22['s_name']);
        $tmp_str.="; ";
       }
      $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i), $tmp_str);
      $fields++;
     } 
    if ($glossary) 
     {
      if ($i==8) $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-2), $lng[15][86]);
      //$objPHPExcel->getActiveSheet()->getStyle(getExcelLetters($fields).($i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $tmp_str="";
      $query22="select g.* from project_glossary pg left outer join glossary g on pg.pg_g_id=g.g_id where pg.pg_p_id='".$row['p_id']."' order by g.g_id asc";
      $rs22 = mysql_query($query22) or die(mysql_error());
      while($row22=mysql_fetch_array($rs22))
       {
	$glossary_ids.=$row22['g_id'].",";
	for ($k=0;$k<6-strlen($row22['g_id']);$k++) $tmp_str.="0";$tmp_str.=htmlspecialchars($row22['g_id']);
        $tmp_str.="; ";
       }
      $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i), $tmp_str);
      $fields++;
     } 
    if ($state) 
     {
      if ($i==8) $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-2), $lng[15][10]);
      //$objPHPExcel->getActiveSheet()->getStyle(getExcelLetters($fields).($i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i), $state_array[$row['r_state']]);
      $fields++;
     } 
    if ($type) 
     {
      if ($i==8) $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-2), $lng[15][11]);
      //$objPHPExcel->getActiveSheet()->getStyle(getExcelLetters($fields).($i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i), $type_array[$row['r_type_r']]);
      $fields++;
     } 
    if ($priority) 
     {
      if ($i==8) $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-2), $lng[15][13]);
      //$objPHPExcel->getActiveSheet()->getStyle(getExcelLetters($fields).($i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i), $row['r_priority']);
      $fields++;
     } 
    if ($assign_to) 
     {
      if ($i==8) $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-2), $lng[15][30]);
      //$objPHPExcel->getActiveSheet()->getStyle(getExcelLetters($fields).($i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

	       if ($row['r_assigned_u_id']!=0)
	        {
	         $query4="select * from users where u_id=".$row['r_assigned_u_id'];
 		 $rs4 = mysql_query($query4) or die(mysql_error());
  		 if($row4=mysql_fetch_array($rs4)) $r_assigned_u_id=" ".htmlspecialchars($row4['u_name']);
  		} 
  	       else $r_assigned_u_id=" -";	


      $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i), $r_assigned_u_id);
      $fields++;
     } 
    if ($rid) 
     {
      if ($i==8) $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-2), $lng[15][45]);
      //$objPHPExcel->getActiveSheet()->getStyle(getExcelLetters($fields).($i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i), $row['r_id']);
      $fields++;
     } 
    if ($version) 
     {
      if ($i==8) $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-2), $lng[15][34]);
      //$objPHPExcel->getActiveSheet()->getStyle(getExcelLetters($fields).($i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i), $row['r_version']);
      $fields++;
     } 
    if ($component) 
     {
      $tmp_str="";
      if ($i==8) $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-2), $lng[15][40]);
      //$objPHPExcel->getActiveSheet()->getStyle(getExcelLetters($fields).($i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $r_component_arr=explode(",",$row['r_component']);
      while (list ($key, $val) = each ($r_component_arr)) if ($component_array[$val]!="") $tmp_str.=$component_array[$val]."; ";
      $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i), $tmp_str);
      $fields++;
     } 
    if ($source) 
     {
      if ($i==8) $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-2), $lng[15][41]);
      //$objPHPExcel->getActiveSheet()->getStyle(getExcelLetters($fields).($i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i), $row['r_source']);
      $fields++;
     } 
    if ($risk) 
     {
      if ($i==8) $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-2), $lng[15][42]);
      //$objPHPExcel->getActiveSheet()->getStyle(getExcelLetters($fields).($i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i), $risk_array[$row['r_risk']]);
      $fields++;
     } 
    if ($complexity) 
     {
      if ($i==8) $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-2), $lng[15][43]);
      //$objPHPExcel->getActiveSheet()->getStyle(getExcelLetters($fields).($i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i), $complexity_array[$row['r_complexity']]);
      $fields++;
     } 
    if ($open_points) 
     {
      if ($i==8) $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-2), $lng[15][44]);
      //$objPHPExcel->getActiveSheet()->getStyle(getExcelLetters($fields).($i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i), $row['r_points']);
      $fields++;
     } 
    if ($keywords) 
     {
      $tmp_str="";
      if ($i==8) $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-2), $lng[15][107]);
      //$objPHPExcel->getActiveSheet()->getStyle(getExcelLetters($fields).($i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	
	      $query456="select k_id, k_name from keywords where k_id in (".$row['r_keywords']."0)";
              $rs456 = mysql_query($query456) or die(mysql_error());	        
	      while($row456=mysql_fetch_array($rs456)) $tmp_str.=htmlspecialchars($row456['k_name'])."; ";

      $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i), $tmp_str);
      $fields++;
     } 
    if ($satisfaction) 
     {
      if ($i==8) $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-2), $lng[15][82]);
      //$objPHPExcel->getActiveSheet()->getStyle(getExcelLetters($fields).($i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i), $row['r_satisfaction']);
      $fields++;
     } 
    if ($dissatisfaction) 
     {
      if ($i==8) $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-2), $lng[15][84]);
      //$objPHPExcel->getActiveSheet()->getStyle(getExcelLetters($fields).($i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i), $row['r_dissatisfaction']);
      $fields++;
     } 
    if ($depends) 
     {
      if ($i==8) $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-2), $lng[15][78]);
      //$objPHPExcel->getActiveSheet()->getStyle(getExcelLetters($fields).($i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $tmp_str="";
      
      	      $query456="select r_id, r_name from requirements where r_id in (".$row['r_depends']."0)";
              $rs456 = mysql_query($query456) or die(mysql_error());	        
	      while($row456=mysql_fetch_array($rs456)) $tmp_str.=htmlspecialchars($row456['r_name'])."; ";
	        
      $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i), $tmp_str);
      $fields++;
     } 
    if ($conflicts) 
     {
      if ($i==8) $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-2), $lng[15][80]);
      //$objPHPExcel->getActiveSheet()->getStyle(getExcelLetters($fields).($i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $tmp_str="";
      
      	      $query456="select r_id, r_name from requirements where r_id in (".$row['r_conflicts']."0)";
              $rs456 = mysql_query($query456) or die(mysql_error());	        
	      while($row456=mysql_fetch_array($rs456)) $tmp_str.=htmlspecialchars($row456['r_name'])."; ";
	        
      $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i), $tmp_str);
      $fields++;
     } 
    if ($author) 
     {
      if ($i==8) $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-2), $lng[15][16]);
      //$objPHPExcel->getActiveSheet()->getStyle(getExcelLetters($fields).($i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $tmp_str="";
      
	       if ($row['r_u_id']!=0)
	        {
	         $query4="select * from users where u_id=".$row['r_u_id'];
 		 $rs4 = mysql_query($query4) or die(mysql_error());
 		 //echo $query4;
  		 if($row4=mysql_fetch_array($rs4)) $tmp_str=" ".htmlspecialchars($row4['u_name']);
  		} 
  	       else $tmp_str=" -";	
      $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i), $tmp_str);
      $fields++;
     } 
    if ($url) 
     {
      if ($i==8) $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-2), $lng[15][14]);
      //$objPHPExcel->getActiveSheet()->getStyle(getExcelLetters($fields).($i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i), $row['r_link']);
      $fields++;
     } 
    if ($parent) 
     {
      if ($i==8) $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-2), $lng[15][38]);
      //$objPHPExcel->getActiveSheet()->getStyle(getExcelLetters($fields).($i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $tmp_str="";
      
      	      $query456="select r_name from requirements where r_id=".$row['r_parent_id'];
              $rs456 = mysql_query($query456) or die(mysql_error());	        
	      while($row456=mysql_fetch_array($rs456)) $tmp_str.=htmlspecialchars($row456['r_name'])."; ";
	        
      $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i), $tmp_str);
      $fields++;
     } 
     /*
    if ($position) 
     {
      if ($i==8) $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-2), $lng[15][39]);
      //$objPHPExcel->getActiveSheet()->getStyle(getExcelLetters($fields).($i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i), $row['r_pos']);
      $fields++;
     }*/ 
    if ($userfields) 
     {
	  $cnt_u=0;
	  $query41="select count(*) from user_fields where uf_name_en<>''";
	  $rs41=mysql_query($query41) or die(mysql_error());
	  if($row41=mysql_fetch_array($rs41)) $cnt_num=$row41[0];
		   
	  $query41="select * from user_fields order by uf_id asc";
	  $rs41=mysql_query($query41) or die(mysql_error());
	  while($row41=mysql_fetch_array($rs41))
	   {
	    $uf_name[]=htmlspecialchars($row41['uf_name_'.$_SESSION['chlang']]);
	    $uf_text[]=htmlspecialchars($row41['uf_text_'.$_SESSION['chlang']]);
           } 
     
      for($k=0;$k<$cnt_num;$k++)
       {
        if ($i==8) $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-2), $uf_name[$k]);
      //$objPHPExcel->getActiveSheet()->getStyle(getExcelLetters($fields).($i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i), $row['r_userfield'.($k+1)]);
        $fields++;       
       }

     } 
    if ($creation_date) 
     {
      if ($i==8) $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-2), $lng[15][17]);
      //$objPHPExcel->getActiveSheet()->getStyle(getExcelLetters($fields).($i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      if ($row['r_creation_date']=="00.00.0000 00:00") $r_creation_date="-"; else $r_creation_date=$row['r_creation_date'];
      $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i), $r_creation_date);
      $fields++;
     } 
    if ($last_change) 
     {
      if ($i==8) $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-2), $lng[15][18]);
      //$objPHPExcel->getActiveSheet()->getStyle(getExcelLetters($fields).($i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      if ($row['r_change_date']=="00.00.0000 00:00") $r_change_date="-"; else $r_change_date=$row['r_change_date'];
      $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i), $r_change_date);
      $fields++;
     } 
    if ($accepted_date) 
     {
      if ($i==8) $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-2), $lng[15][19]);
      //$objPHPExcel->getActiveSheet()->getStyle(getExcelLetters($fields).($i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      if ($row['r_accept_date']=="00.00.0000 00:00") $r_accept_date="-"; else $r_accept_date=$row['r_accept_date'];
      $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i), $r_accept_date);
      $fields++;
     } 
    if ($accepted_user) 
     {
      if ($i==8) $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-2), $lng[15][20]);
      //$objPHPExcel->getActiveSheet()->getStyle(getExcelLetters($fields).($i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $tmp_str="";
      
	       if ($row['r_accept_user']!=0)
	        {
	         $query4="select * from users where u_id=".$row['r_accept_user'];
 		 $rs4 = mysql_query($query4) or die(mysql_error());
  		 if($row4=mysql_fetch_array($rs4)) $tmp_str=" ".htmlspecialchars($row4['u_name']);
  		} 
  	       else $tmp_str=" -";	
	        
      $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i), $tmp_str);
      $fields++;
     } 
    if ($comments) 
     {
      if ($i==8) $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-2), $lng[15][114]);
      //$objPHPExcel->getActiveSheet()->getStyle(getExcelLetters($fields).($i))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $query44="select c.*, u.u_name, date_format(c.c_date, '%d.%m.%Y %H:%i') as d1 from comments c left outer join users u on c.c_u_id=u.u_id where c.c_r_id=".$row["r_id"]." order by c_date desc";
      $rs44 = mysql_query($query44) or die(mysql_error());
      while($row44=mysql_fetch_array($rs44))
       {
        $tmp_str=htmlspecialchars($row44['u_name']);
        if ($row44['c_date']!="00.00.0000") $tmp_str.=" (".$row44['c_date'].")";
        $c_text=str_replace("&nbsp;"," ",$row44['c_text']);
        $tmp_str=strip_tags($c_text);
        //if ($i==8) $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-2), $lng[15][114]);
        $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i), $tmp_str);
        $fields++;
       } 
     } 
    
    
    $i++; 
     }
   }
 } 
 
if ($weight) 
 {
  $objPHPExcel->getActiveSheet()->setCellValue('D'.($i+1), '=SUM(D8:D'.$i.')');
  $objPHPExcel->getActiveSheet()->setCellValue('F'.($i+1), '=SUM(F8:F'.$i.')');
  $objPHPExcel->getActiveSheet()->setCellValue('H'.($i+1), '=SUM(H8:H'.$i.')');
  $objPHPExcel->getActiveSheet()->setCellValue('J'.($i+1), '=SUM(J8:J'.$i.')');
  $objPHPExcel->getActiveSheet()->setCellValue('L'.($i+1), '=SUM(L8:L'.$i.')');

  $objPHPExcel->getActiveSheet()->setCellValue('A'.($i+4), '* only fill in values between 0 (not at all) - 10 (perfect fit)');
  $objPHPExcel->getActiveSheet()->getStyle('A'.($i+4))->getAlignment()->setWrapText(true);
  $objPHPExcel->getActiveSheet()->getStyle('A'.($i+4))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
  $objPHPExcel->getActiveSheet()->getStyle('A'.($i+4))->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
  $objPHPExcel->getActiveSheet()->getStyle('A'.($i+4))->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
  $objPHPExcel->getActiveSheet()->getStyle('A'.($i+4))->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
  $objPHPExcel->getActiveSheet()->getStyle('A'.($i+4))->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
  $objPHPExcel->getActiveSheet()->getStyle('A'.($i+4))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
  $objPHPExcel->getActiveSheet()->getStyle('A'.($i+4))->getFill()->getStartColor()->setARGB('FFFFFF00');
  $objPHPExcel->getActiveSheet()->getStyle('A'.($i+4))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
  $objPHPExcel->getActiveSheet()->getStyle('A'.($i+4))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
  $i+=5;
 }
 $i+=3;
 
    if ($glossary) 
     {
      $fields=0;
      $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-1), $lng[24][1].":");
      $query44="select * from glossary where g_id in (".$glossary_ids."0) order by g_name asc";
      $rs44 = mysql_query($query44) or die(mysql_error());
      while($row44=mysql_fetch_array($rs44))
       {
        $fields=0;
        $g_id="";
        for ($k=0;$k<6-strlen($row44['g_id']);$k++) $g_id.="0";$g_id.=$row44['g_id'];
        $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields+1).($i), $g_id);
        $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields+2).($i), $row44['g_abbreviation']);
        $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields+3).($i), htmlspecialchars($row44['g_term']));
        $g_desc=str_replace("&nbsp;"," ",$row44['g_desc']);
        $g_desc=strip_tags($g_desc);
        $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields+4).($i), $g_desc);
        $i++;
       } 
     } 
     
    if ($test_case) 
     {
      $i+=2;
      $fields=0;
      $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields).($i-1), $lng[15][103].":");
      $query44="select * from cases where c_id in (".$cases_ids."0) order by c_name asc";
      $rs44 = mysql_query($query44) or die(mysql_error());
      while($row44=mysql_fetch_array($rs44))
       {
        $fields=0;
        $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields+1).($i), htmlspecialchars($row44['c_name']));
        if ($row44['c_status']==0) $c_stat=$lng[31][6];
        elseif ($row44['c_status']==1) $c_stat=$lng[31][7];
        $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields+2).($i), $c_stat);
        $c_desc=str_replace("&nbsp;"," ",$row44['c_desc']);
        $c_desc=strip_tags($c_desc);
        $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields+3).($i), $c_desc);
        $c_result=str_replace("&nbsp;"," ",$row44['c_result']);
        $c_result=strip_tags($c_result);
        $objPHPExcel->getActiveSheet()->setCellValue(getExcelLetters($fields+4).($i), $c_result);
       } 
     } 

 
 
/** PHPExcel_IOFactory 
include 'PHPExcel/IOFactory.php';

//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
//$objWriter->save(str_replace('.php', '.xls', '11.xls'));


header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");;

$agent = getenv("HTTP_USER_AGENT");
if (preg_match("/MSIE/i", $agent)) header("Content-Disposition: attachment;filename=".rawurlencode($filename)); 
else header("Content-Disposition: attachment;filename=".$filename); 

header("Content-Transfer-Encoding: binary ");
*/
$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);    //  (I want the output for 2003)
//$objWriter->save('php://output');
$objWriter->save('/home/inat/public_html/reqheaptest/test'.time().'.xls');
//echo date('H:i:s') . " Peak memory usage: " . (memory_get_peak_usage(true) / 1024 / 1024) . " MB\r\n";
//echo getcwd();
?>
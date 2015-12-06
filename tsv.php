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
// Page: "csv" - creating a csv file for projects

session_start();

include ("admin/inc/conn.php");//include settings file
include ("admin/inc/func.php");//include functions file
include ("ini/params.php");//include configuration file


if ($p_id=="") die("No project selected");

//setting referer if not logged
if ($_SESSION['uid']=="" && $_SERVER['QUERY_STRING']!="" && !strstr($_SERVER['QUERY_STRING'],'login'))
{
 $_SESSION['http_ref']=$_SERVER['QUERY_STRING'];
} 

//default language
if ($_chlang!="") $_SESSION['chlang']=$_chlang;
if (!$_SESSION['chlang']) $_SESSION['chlang']="en";
include ("ini/lng/".$_SESSION['chlang'].".php");//include language file


//start creating CSV file
$query="select * from projects where p_id=".$p_id;
$rs = mysql_query($query) or die(mysql_error());
if($row=mysql_fetch_array($rs)) 
 {
  $p_name=($row['p_name']);
  $p_desc=strip_tags($row['p_desc']);
 }

$filename=str_replace(",","",$p_name);
$filename=str_replace("'","",$filename);
$filename=str_replace("\"","",$filename);
$filename=str_replace(" ","_",$filename);
$filename.="_" . date("d-m-Y").".csv";

//creating CSV headers
//header("Content-type: application/vnd.ms-excel; charset=iso-8859-1");
//header("Expires: 0");
//header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
//header("content-disposition: attachment;filename=".$filename);


header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
header("Content-Type: application/force-download; ");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");
//header("charset=utf-8");

$agent = getenv("HTTP_USER_AGENT");
if (preg_match("/MSIE/i", $agent)) 
 {
 
  header("Content-Disposition: attachment;filename=".rawurlencode($filename)); 
 }
else header("Content-Disposition: attachment;filename=".$filename); 

header("Content-Transfer-Encoding: binary ");


//table header
$csv_output="\"Project ".$p_name."\"\n\n";
$csv_output.="\"".$p_desc."\"\n";
$csv_output.=date("d.m.Y")."\n\n";
$csv_output.="ID\t Tree\t Requirement\t Weight\t Supplier A\t \t Supplier B\t \t Supplier C\t \t Supplier D\t \t\n";
$csv_output.="\t \t \t \t points\t weigthed points\t points\t weigthed points\t points\t weigthed points\t points\t weigthed points\t\n\n";

//getting ids from filter 
if ($ids2!="") $ids=$ids2;
if ($ids=="" && $srch!="") 
 {
  $query="select r.r_id from requirements r left outer join projects p on r.r_p_id=p.p_id left outer join users u on r.r_u_id=u.u_id left outer join users u2 on r.r_assigned_u_id=u2.u_id where r.r_p_id=".$p_id." ".stripslashes(stripslashes(stripslashes(stripslashes(stripslashes($srch)))));
  $rs = mysql_query($query) or die(mysql_error());
  while($row=mysql_fetch_array($rs)) 
   {
    $ids.=$row[0].",";
   }
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
 
while ($cnt>0 && list ($key, $val) = each ($arr)) 
 {
  $query="select * from requirements where r_id=".substr($val,strpos($val,"|")+1);
  $rs = mysql_query($query) or die(mysql_error());
  if($row=mysql_fetch_array($rs)) 
   {
    if ($ids=="" || ($ids!="" && strstr(",".$ids.",",",".$row['r_id'].","))) $csv_output.=$row["r_id"]."\tA ".substr($val,0,strpos($val,"|"))."\t\"".($row["r_name"])."\"\t ".($row["r_weight"])."\t\t\t\t\t\t\t\t\t\n";   
   }
 } 

$csv_output.="\n\nPlease, only fill in values between 0 (not at all) - 10 (perfect fit)\n\n";


$csv_output=str_replace("&amp;","&",$csv_output);
$csv_output=exc($csv_output);
  
print $csv_output;
exit; 
?>
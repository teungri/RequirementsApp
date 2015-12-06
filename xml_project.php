<?
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
// Page: "creating project XML" - creates a xml file containing data for the project, users and release
?>
<?
include ("admin/inc/conn.php");//include settings file
include ("admin/inc/func.php");//include functions file
include ("ini/params.php");//include configuration file

//default language
if ($_lng=="") $_lng="en";
include ("ini/lng/".$_lng.".php");//include language file

if ($p_id=="") $p_id=0;
$query="select p.*, date_format(p_date, '%d.%m.%Y') as d1, u_name from projects p left outer join users u on p.p_leader=u.u_id where p.p_id=".$p_id;
$rs = mysql_query($query) or die(mysql_error());
if($row=mysql_fetch_array($rs)) 
 {
  $p_name=htmlspecialchars($row['p_name']);
  $p_desc=htmlspecialchars($row['p_desc']);
  $p_phase=htmlspecialchars($row['p_phase']);
  $p_status=htmlspecialchars($row['p_status']);
  $p_leader=htmlspecialchars($row['p_leader']);
  $u_name=htmlspecialchars($row['u_name']);
  $p_date=htmlspecialchars($row['d1']);
 }

//phase
switch($p_phase)
 {
  case "0":$p_phase=$lng[9][8];break;
  case "1":$p_phase=$lng[9][9];break;
  case "2":$p_phase=$lng[9][10];break;
  case "3":$p_phase=$lng[9][32];break;
  case "4":$p_phase=$lng[9][33];break;
  default:$p_phase=$lng[9][8];     
 }

//status
switch($p_status)
 {
  case "0":$p_status=$lng[9][11];break;
  case "1":$p_status=$lng[9][12];break;
  case "2":$p_status=$lng[9][14];break;
  default:$p_status=$lng[9][11];     
 } 


$filename=str_replace(",","",$p_name);
$filename=str_replace("'","",$filename);
$filename=str_replace("\"","",$filename);
$filename=str_replace(" ","_",$filename);
$filename.="_" . date("d-m-Y").".xml";
 
//creating CSV headers
header("Content-type: application/xml; charset=utf-8");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
//header("content-disposition: attachment;filename=".$filename);

$agent = getenv("HTTP_USER_AGENT");
if (preg_match("/MSIE/i", $agent)) 
 {
 
  header("Content-Disposition: attachment;filename=".rawurlencode($filename)); 
 }
else header("Content-Disposition: attachment;filename=".$filename); 

$xml_output = '<?xml version="1.0" encoding="utf-8"?>';
$xml_output .= '<'.$lng[20][1].'>';
$xml_output .= '<'.$lng[20][2].'>'.$p_name.'</'.$lng[20][2].'>';
$xml_output .= '<'.$lng[20][3].'>'.$p_desc.'</'.$lng[20][3].'>';
$xml_output .= '<'.$lng[20][4].'>'.$p_phase.'</'.$lng[20][4].'>';
$xml_output .= '<'.$lng[20][5].'>'.$p_status.'</'.$lng[20][5].'>';
$xml_output .= '<'.$lng[20][6].'>'.$u_name.'</'.$lng[20][6].'>';
$xml_output .= '<'.$lng[20][7].'>'.$p_date.'</'.$lng[20][7].'>';

$query2="select r.*, date_format(r.r_date, '%d.%m.%Y') as d1, date_format(r.r_released_date, '%d.%m.%Y') as d2 from project_releases pr left outer join releases r on pr.pr_r_id=r.r_id where pr.pr_p_id='".$row['p_id']."' order by r.r_name asc";
$rs2 = mysql_query($query2) or die(mysql_error());
while($row2=mysql_fetch_array($rs2))
 {
  $xml_output .= '<'.$lng[20][8].'>';
  $xml_output .= '<'.$lng[20][9].'>'.htmlspecialchars($row2['r_name']).' ('.$row2['d1'].')';
  if ($row2['d2']!="00.00.0000") $xml_output .= " - ".$row2['d2'];	      
  $xml_output .= '</'.$lng[20][9].'>';
  $xml_output .= '</'.$lng[20][8].'>';
 }

$query2="select c.* from project_components pco left outer join components c on pco.pco_c_id=c.c_id where pco.pco_p_id='".$row['p_id']."' order by c.c_name asc";
$rs2 = mysql_query($query2) or die(mysql_error());
while($row2=mysql_fetch_array($rs2))
 {
  $xml_output .= '<'.$lng[20][12].'>';
  $xml_output .= '<'.$lng[20][13].'>'.htmlspecialchars($row2['c_name']);
  $xml_output .= '</'.$lng[20][13].'>';
  $xml_output .= '</'.$lng[20][12].'>';
 }

$query2="select u.u_name from project_users pu left outer join users u on pu.pu_u_id=u.u_id where pu.pu_p_id='".$p_id."' order by u.u_name asc";
$rs2 = mysql_query($query2) or die(mysql_error());
while($row2=mysql_fetch_array($rs2))
 {
  $xml_output .= '<'.$lng[20][10].'>';
  $xml_output .= '<'.$lng[20][11].'>'.htmlspecialchars($row2['u_name']);
  $xml_output .= '</'.$lng[20][11].'>';
  $xml_output .= '</'.$lng[20][10].'>';
 }
	    
$xml_output .= '</'.$lng[20][1].'>';

//$xml_output=str_replace("&amp;","&",$xml_output);
//$xml_output=exc($xml_output);

print $xml_output; 
exit;
?>

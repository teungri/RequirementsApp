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
// Page: "pdf case" - converting case into pdf file 

session_start();
include ("admin/inc/conn.php");//include settings file
include ("admin/inc/func.php");//include functions file

if ($c_id=="") echo "No case selected";
else
 {
  $query="select c_name from cases where c_id=".$c_id;
  $rs = mysql_query($query) or die(mysql_error());
  if($row=mysql_fetch_array($rs)) $pdf_name=htmlspecialchars($row['c_name']);
  $pdf_name=substr($pdf_name,0,30);
  if (strstr(rawurlencode($pdf_name.".pdf"),'%')) $pdf_name="case";
  $pdf_name="case";
   
  include ("ini/params.php");//include configuration file
  $filename = PROJECT_URL."/print_case.php?c_id=".$c_id."|".$_SESSION['chlang'];
  $url = PDF_SCRIPT_URL."dompdf.php?input_file=".$filename."&paper=letter&orientation=portrait&output_file=" . rawurlencode($pdf_name.".pdf");
  //header("Location:".$url);
  //die($url);
  ?>
<script>document.location.href='<?=$url?>'</script>
<?}?>

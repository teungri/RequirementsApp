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
// Page: "pdf review" - converting project into review file 

session_start();
include ("admin/inc/conn.php");//include settings file
include ("admin/inc/func.php");//include functions file


if ($r_id=="") echo "No review selected";
else
 {
  //getting which fields to display
  $fields="";
  if ($description==1) $fields.="1";else $fields.="0";
  if ($project==1) $fields.="1";else $fields.="0";
  if ($subproject==1) $fields.="1";else $fields.="0";
  if ($release==1) $fields.="1";else $fields.="0";
  if ($test_case==1) $fields.="1";else $fields.="0";
  if ($stakeholder==1) $fields.="1";else $fields.="0";
  if ($glossary==1) $fields.="1";else $fields.="0";
  if ($state==1) $fields.="1";else $fields.="0";
  if ($type==1) $fields.="1";else $fields.="0";
  if ($priority==1) $fields.="1";else $fields.="0";
  if ($assign_to==1) $fields.="1";else $fields.="0";
  if ($rid==1) $fields.="1";else $fields.="0";
  if ($version==1) $fields.="1";else $fields.="0";
  if ($component==1) $fields.="1";else $fields.="0";
  if ($source==1) $fields.="1";else $fields.="0";
  if ($risk==1) $fields.="1";else $fields.="0";
  if ($complexity==1) $fields.="1";else $fields.="0";
  if ($weight==1) $fields.="1";else $fields.="0";
  if ($open_points==1) $fields.="1";else $fields.="0";
  if ($keywords==1) $fields.="1";else $fields.="0";
  if ($satisfaction==1) $fields.="1";else $fields.="0";
  if ($dissatisfaction==1) $fields.="1";else $fields.="0";
  if ($depends==1) $fields.="1";else $fields.="0";
  if ($conflicts==1) $fields.="1";else $fields.="0";
  if ($author==1) $fields.="1";else $fields.="0";
  if ($url==1) $fields.="1";else $fields.="0";
  if ($parent==1) $fields.="1";else $fields.="0";
  if ($position==1) $fields.="1";else $fields.="0";
  if ($userfields==1) $fields.="1";else $fields.="0";
  if ($creation_date==1) $fields.="1";else $fields.="0";
  if ($last_change==1) $fields.="1";else $fields.="0";
  if ($accepted_date==1) $fields.="1";else $fields.="0";
  if ($accepted_user==1) $fields.="1";else $fields.="0";
  if ($comments==1) $fields.="1";else $fields.="0";

  
  $query="select r_name from reviews where r_id=".$r_id;
  $rs = mysql_query($query) or die(mysql_error());
  if($row=mysql_fetch_array($rs)) $pdf_name=htmlspecialchars($row['r_name']);
  $pdf_name=substr($pdf_name,0,25)."_".date("d_m_Y");
  if (strstr(rawurlencode($pdf_name.".pdf"),'%')) $pdf_name="project_".date("d_m_Y");
  
  if ($mode=="") $mode="landscape";
   
  include ("ini/params.php");//include configuration file
   $filename = PROJECT_URL."/print_review.php?r_id=".$r_id."|".$filter1."|".$filter2."|".$filter4."|".$filter5."|".$filter6."|".$filter7."|".$filter8."|".$order."|".$_SESSION['chlang']."|".$ids."|".$fields;
  
  
  //$url = PDF_SCRIPT_URL."dompdf.php?input_file=".PROJECT_URL."/test4.html&save_file=1&paper=letter&orientation=".$mode."&output_file=" . rawurlencode($pdf_name.".pdf");
  //$filename="../test1.html";
  $url = PDF_SCRIPT_URL."dompdf.php?input_file=".$filename."&paper=letter&orientation=".$mode."&output_file=" . rawurlencode($pdf_name.".pdf");
  header("Location:".$url);
  ?>
<script>document.location.href='<?=$url?>'</script>
<?}?>

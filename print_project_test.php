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
// Page: "print" - converting the requirement tree into pdf file
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', dirname(__FILE__) . '/error_log.txt');
error_reporting(E_ALL^E_NOTICE);
set_time_limit(0);
session_start();
include_once ("admin/inc/conn.php");//include_once settings file
include_once ("admin/inc/func.php");//include_once functions file
include_once ("ini/params.php");//include_once configuration file

//default language
$_SESSION['chlang']=$_lng;

if (!$_SESSION['chlang']) $_SESSION['chlang']="en";
include_once ("ini/lng/".$_SESSION['chlang'].".php");//include_once language file

include_once("ini/txts/".$_SESSION['chlang']."/state.php");
include_once("ini/txts/".$_SESSION['chlang']."/type.php");
include_once("ini/txts/".$_SESSION['chlang']."/risk.php");
include_once("ini/txts/".$_SESSION['chlang']."/complexity.php");
      	      	      	      
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="description" content="<?=$lng[1][2]?>"/>
	<meta name="keywords" content="<?=$lng[1][3]?>"/>
	<title><?=$lng[1][1]?></title>
</head>
<body>
<a href="index.php"><img src="img/logo.jpg" border="0"></a>
<?
//parse url vaiables
$_vars = explode("|", $r_p_id);
$filter1=$_vars[1];
$filter2=$_vars[2];
$filter4=$_vars[3];
$filter5=$_vars[4];
$filter6=$_vars[5];
$filter7=$_vars[6];
$filter8=$_vars[7];
$order=str_replace("_desc"," desc",$_vars[8]);
$order=str_replace("_asc"," asc",$order);
$_lng=$_vars[9];
$r_p_id=$_vars[0];
$ids=$_vars[10];
$fields=$_vars[11];

  //getting which fields to display
  $description=$fields[0];
  $project=$fields[1];
  $subproject=$fields[2];
  $release=$fields[3];
  $test_case=$fields[4];
  $stakeholder=$fields[5];
  $glossary=$fields[6];
  $state=$fields[7];
  $type=$fields[8];
  $priority=$fields[9];
  $assign_to=$fields[10];
  $rid=$fields[11];
  $version=$fields[12];
  $component=$fields[13];
  $source=$fields[14];
  $risk=$fields[15];
  $complexity=$fields[16];
  $weight=$fields[17];
  $open_points=$fields[18];
  $keywords=$fields[19];
  $satisfaction=$fields[20];
  $dissatisfaction=$fields[21];
  $depends=$fields[22];
  $conflicts=$fields[23];
  $author_=$fields[24];
  $url=$fields[25];
  $parent=$fields[26];
  $position=$fields[27];
  $userfields=$fields[28];
  $creation_date=$fields[29];
  $last_change=$fields[30];
  $accepted_date=$fields[31];
  $accepted_user=$fields[32];
  $comments=$fields[33];


$p_id=$r_p_id;
$query2="select p.*, date_format(p.p_date, '%d.%m.%Y') as d1, u.u_name from projects p left outer join users u on p.p_leader=u.u_id where p.p_id=".$r_p_id;
$rs2 = mysql_query($query2) or die(mysql_error());
if($row2=mysql_fetch_array($rs2)) 
 {
  $p_name=$row2['p_name'];      
  $p_desc=$row2['p_desc'];      
  $p_phase=htmlspecialchars($row2['p_phase']);      
  $p_status=htmlspecialchars($row2['p_status']);      
  $p_leader=htmlspecialchars($row2['u_name']);      
  $p_date=htmlspecialchars($row2['d1']);      
 }     
 

$query="select * from requirements where r_p_id=".$p_id." and r_parent_id=0 order by r_pos asc";
$rs = mysql_query($query) or die(mysql_error());
$cnt45=0;
while($row=mysql_fetch_array($rs)) 
 {
  $cnt45++;
  $arr45[]=$cnt45."|".$row['r_id'];
  $arr45_1[]=$row['r_id'];
  getTree2_1($row['r_id'],$cnt45,$arr45,$arr45_1);
 }
 

?>


<?
//sortable columns
if ($order=="") $order="r_change_date desc";

//search
$filter5=escapeChars(stripslashes($filter5));
	  
/*
if ($filter1!="") $search.=" and r.r_u_id=".$filter1;
if ($filter2!="") $search.=" and r.r_state=".$filter2;
if ($filter4!="") $search.=" and r.r_priority=".$filter4;
if ($filter5!="") $search.=" and (r.r_desc like ('%".escapeChars($filter5)."%') or r.r_source like ('%".escapeChars($filter5)."%') or r.r_name like ('%".escapeChars($filter5)."%'))";
if ($filter6!="") $search.=" and r.r_assigned_u_id=".$filter6;
if ($filter7!="") 
 {
  $query4="select * from project_releases where pr_r_id=".$filter7;
  $rs4 = mysql_query($query4) or die(mysql_error());
  $new_pr="(0";
  while($row4=mysql_fetch_array($rs4)) 
   {
    $new_pr.=",".$row4['pr_p_id'];
   } 
  $new_pr.=")";	 
  $search.=" and r.r_p_id in ".$new_pr;
 } 
 if ($filter8!="") $search.=" and r.r_component=".$filter8;
*/

if ($ids!="") $search.=" and r.r_id in (".$ids."0)";

//getting requirements - recently modified
while ($cnt45>0 && list ($key, $val) = each ($arr45)) 
 {
  $query="select r.*, date_format(r.r_creation_date, '%d.%m.%Y %H:%i') as d1, date_format(r.r_change_date, '%d.%m.%Y %H:%i') as d2, date_format(r.r_accept_date, '%d.%m.%Y %H:%i') as d3, u.u_name, p.p_name, p.p_id, sp.s_id, sp.s_name from requirements r left outer join users u on r.r_u_id=u.u_id left outer join projects p on r.r_p_id=p.p_id left outer join subprojects sp on r.r_s_id=sp.s_id where r.r_id=".substr($val,strpos($val,"|")+1);
//$query="select r.*, date_format(r.r_creation_date, '%d.%m.%Y %H:%i') as d1, date_format(r.r_change_date, '%d.%m.%Y %H:%i') as d2, date_format(r.r_accept_date, '%d.%m.%Y %H:%i') as d3, p.p_id,p.p_name,u.u_name,u2.u_name as u_name2, sp.s_name from requirements r left outer join projects p on r.r_p_id=p.p_id left outer join users u on r.r_u_id=u.u_id left outer join users u2 on r.r_assigned_u_id=u2.u_id left outer join subprojects sp on r.r_s_id=sp.s_id where r.r_p_id in (".$r_p_id.") ".$search." order by ".$order.", r_change_date desc";
$rs = mysql_query($query) or die(mysql_error());
if($row=mysql_fetch_array($rs)) if ($ids=="" || ($ids!="" && strstr(",".$ids.",",",".$row['r_id'].","))) 
   {
    $r_id=htmlspecialchars($row['r_id']);$r_id_tmp=$r_id;    
    $r_name=htmlspecialchars($row['r_name']);$r_name_tmp=$r_name;    
    $ta=$row['r_desc'];$ta_tmp=$ta;
    $s_id=htmlspecialchars($row['s_id']);$s_id_tmp=$s_id;
    $r_release=htmlspecialchars($row['r_release']);$r_release_tmp=$r_release;
    $r_c_id=htmlspecialchars($row['r_c_id']);$r_c_id_tmp=$r_c_id;
    $r_stakeholder=htmlspecialchars($row['r_stakeholder']);$r_stakeholder_tmp=$r_stakeholder;
    $r_glossary=htmlspecialchars($row['r_glossary']);$r_glossary_tmp=$r_glossary;
    $s_name=htmlspecialchars($row['s_name']);$s_name_tmp=$s_name;
    $r_state=$row['r_state'];$r_state_tmp=$r_state;
    $r_type_r=$row['r_type_r'];$r_type_r_tmp=$r_type_r;
    $p_name=htmlspecialchars($row['p_name']);$p_name_tmp=$p_name;
    $p_id=htmlspecialchars($row['r_p_id']);
    $r_assigned_u_id=$row['r_assigned_u_id'];$r_assigned_u_id_tmp=$r_assigned_u_id;
    $author=htmlspecialchars($row['u_name']);$author_tmp=$author;
    $r_priority=$row['r_priority'];$r_priority_tmp=$r_priority;
    $r_link=htmlspecialchars($row['r_link']);$r_link_tmp=$r_link;
    $r_satisfaction=htmlspecialchars($row['r_satisfaction']);$r_satisfaction_tmp=$r_satisfaction;
    $r_dissatisfaction=htmlspecialchars($row['r_dissatisfaction']);$r_dissatisfaction_tmp=$r_dissatisfaction;
    $r_conflicts=htmlspecialchars($row['r_conflicts']);$r_conflicts_tmp=$r_conflicts;
    $r_depends=htmlspecialchars($row['r_depends']);$r_depends_tmp=$r_depends;
    $r_component=$row['r_component'];$r_component_tmp=$r_component;  
    $r_source=htmlspecialchars($row['r_source']);$r_source_tmp=$r_source; 
    $r_risk=$row['r_risk'];$r_risk_tmp=$r_risk;    
    $r_complexity=$row['r_complexity'];$r_complexity_tmp=$r_complexity;     
    $r_weight=$row['r_weight'];$r_weight_tmp=$r_weight;      
    $r_points=$row['r_points'];$r_points_tmp=$r_points;      
    $r_creation_date=$row['d1'];$r_creation_date_tmp=$r_creation_date;
    $r_change_date=$row['d2'];$r_change_date_tmp=$r_change_date;
    $r_accept_date=$row['d3'];$r_accept_date_tmp=$r_accept_date;
    $r_accept_user=$row['r_accept_user'];$r_accept_user_tmp=$r_accept_user;
    $r_version=$row['r_version'];$r_version_tmp=$r_version;
    $r_parent_id=$row['r_parent_id'];$r_parent_id_tmp=$r_parent_id; 
    $r_pos=$row['r_pos'];$r_pos_tmp=$r_pos; 
    $r_keywords=$row['r_keywords'];$r_keywords_tmp=$r_keywords; 
    $r_userfield1=htmlspecialchars($row['r_userfield1']);$r_userfield1_tmp=$r_userfield1;
    $r_userfield2=htmlspecialchars($row['r_userfield2']);$r_userfield2_tmp=$r_userfield2;    
    $r_userfield3=htmlspecialchars($row['r_userfield3']);$r_userfield3_tmp=$r_userfield3;    
    $r_userfield4=htmlspecialchars($row['r_userfield4']);$r_userfield4_tmp=$r_userfield4;    
    $r_userfield5=htmlspecialchars($row['r_userfield5']);$r_userfield5_tmp=$r_userfield5;   
    $r_userfield6=htmlspecialchars($row['r_userfield6']);$r_userfield6_tmp=$r_userfield6;    

$glossary_ids="";$cases_ids="";
?>
<br/><br/>
<table border="1" cellpadding="0" cellspacing="0" class="content" width="100%">
	  <tr class="gray" valign="top">
	    <td class="gray" align="right" >&nbsp;<?=$lng[15][4]?>:&nbsp;&nbsp;</td>
	    <td class="gray" colspan="7" width="100%"><b>&nbsp;<?=$r_name?></b></td>
	  </tr>
	  <?if ($description) {?>
	  <tr class="gray" valign="top">
	    <td class="gray" align="right" >&nbsp;<?=$lng[15][5]?>:&nbsp;&nbsp;</td>
	    <td class="gray" align="left" colspan="7" width="100%"><?=$ta?> 
	    </td>
	  </tr> 
	  <?}else {echo '<tr class="gray" valign="top"><td class="gray">&nbsp;</td><td class="gray" colspan="7">&nbsp;</td></tr> ';}?>
	  <tr class="gray" valign="top">
	    <?if ($project) {?>
	    <td class="gray" align="right"  width="15%">&nbsp;<?=$lng[15][3]?>:&nbsp;&nbsp;</td>
	    <td class="gray"><?=$p_name?></td>
	    <?}else {echo '<td class="gray" width="15%">&nbsp;</td><td class="gray">&nbsp;</td>';}?>
	    <?if ($subproject) {?>
	    <td class="gray" align="right" >&nbsp;<?=$lng[15][97]?>:&nbsp;&nbsp;</td>
	    <td class="gray"><?=$s_name?></td>
	    <?}else {echo '<td class="gray">&nbsp;</td><td class="gray">&nbsp;</td>';}?>
	    <?if ($release) {?>
	    <td class="gray" align="right" >&nbsp;<?=$lng[15][24]?>:&nbsp;&nbsp;</td>
	    <td class="gray" colspan="3">
	    <?
	    $query22="select r.*, date_format(r.r_date, '%d.%m.%Y') as d1, date_format(r.r_released_date, '%d.%m.%Y') as d2 from releases r where r.r_id in (".$r_release."0) order by r.r_name asc";
	    //$query22="select r.*, date_format(r.r_date, '%d.%m.%Y') as d1, date_format(r.r_released_date, '%d.%m.%Y') as d2 from project_releases pr left outer join releases r on pr.pr_r_id=r.r_id where pr.pr_p_id='".$p_id."' order by r.r_name asc";
	    $rs22 = mysql_query($query22) or die(mysql_error());
	    while($row22=mysql_fetch_array($rs22))
	     {
	      echo htmlspecialchars($row22['r_name'])." (".$row22['d1'].")";
	      if ($row22['d2']!="00.00.0000") echo " - ".$row22['d2'];	      
	      echo "<br/>";
	     }
	    ?>
	    </td>
	    <?}else {echo '<td class="gray">&nbsp;</td><td class="gray" colspan="3">&nbsp;</td>';}?>	    
	  </tr>
	  <tr class="gray" valign="top">
	    <?if ($test_case) {?>
	    <td class="gray" align="right" >&nbsp;<?=$lng[15][103]?>:&nbsp;&nbsp;</td>
	    <td class="gray">
	    <?
	    $query26="select * from cases where c_id in (".$r_c_id."0) order by c_name asc";
	    $rs26 = mysql_query($query26) or die(mysql_error());
	    while($row26=mysql_fetch_array($rs26))
	     {
	      $cases_ids.=$row26['c_id'].",";
	      echo htmlspecialchars($row26['c_name']);
	      echo "<br/>";
	     }
	    ?>	      
	    </td> 	    
	    <?}else {echo '<td class="gray">&nbsp;</td><td class="gray">&nbsp;</td>';}?>
	    <?if ($stakeholder) {?>
	    <td class="gray" align="right" >&nbsp;<?=$lng[15][76]?>:&nbsp;&nbsp;</td>
	    <td class="gray">
	    <?
	    $query26="select * from stakeholders where s_id in (".$r_stakeholder."0) order by s_name asc";
	    $rs26 = mysql_query($query26) or die(mysql_error());
	    while($row26=mysql_fetch_array($rs26))
	     {
	      echo htmlspecialchars($row26['s_name']);
	      echo "<br/>";
	     }
	    ?>	      
	    </td>
	    <?}else {echo '<td class="gray">&nbsp;</td><td class="gray">&nbsp;</td>';}?>
	    <?if ($glossary) {?>
	    <td class="gray" align="right" >&nbsp;<?=$lng[15][86]?>:&nbsp;&nbsp;</td>
	    <td class="gray" colspan="3">
	    <?
	    $query26="select g.* from glossary g where g.g_id in (".$r_glossary."0) order by g.g_id asc";
	    //$query26="select g.* from project_glossary pg left outer join glossary g on pg.pg_g_id=g.g_id where pg.pg_p_id='".$p_id."' order by g.g_id asc";
	    $rs26 = mysql_query($query26) or die(mysql_error());
	    while($row26=mysql_fetch_array($rs26))
	     {
	      $glossary_ids.=$row26['g_id'].",";
	      $tmp_g="";
	      for ($i=0;$i<6-strlen($row26['g_id']);$i++) $tmp_g.="0";$tmp_g.=$row26['g_id'];
	      echo $tmp_g;
	      echo "<br/>";
	     }
	    ?>	      
	    </td> 	    
	    <?}else {echo '<td class="gray">&nbsp;</td><td class="gray" colspan="3">&nbsp;</td>';}?>
	  </tr>
	  <tr class="gray" valign="top">
	    <?if ($state) {?>
	    <td class="gray" align="right" >&nbsp;<?=$lng[15][10]?>:&nbsp;&nbsp;</td>
	    <td class="gray">
	      <?
	      echo $state_array[$r_state];
	      ?>
	    </td> 
	    <?}else {echo '<td class="gray">&nbsp;</td><td class="gray">&nbsp;</td>';}?>
	    <?if ($type) {?>
	    <td class="gray" align="right" >&nbsp;<?=$lng[15][11]?>:&nbsp;&nbsp;</td>
	    <td class="gray" colspan="5">
	      <?
	      echo $type_array[$r_type_r];
	      ?>
	    </td>	    
	    <?}else {echo '<td class="gray">&nbsp;</td><td class="gray" colspan="5">&nbsp;</td>';}?>
	  </tr>
	  <tr class="gray" valign="top">
	    <?if ($priority) {?>
	    <td class="gray" align="right" >&nbsp;<?=$lng[15][13]?>:&nbsp;&nbsp;</td>
	    <td class="gray"><?=$r_priority?></td>
	    <!--td class="gray" align="right" >&nbsp;<?=$lng[15][15]?>:&nbsp;&nbsp;</td>
	    <td class="gray">
	      <?
	        if ($r_valid==0) echo $lng[15][31];
	        elseif ($r_valid==1) echo $lng[15][32];
	        elseif ($r_valid==2) echo $lng[15][33];
	      ?>	       	    
	    </td-->
	    <?}else {echo '<td class="gray">&nbsp;</td><td class="gray">&nbsp;</td>';}?>
	    <?if ($assign_to) {?>
	    <td class="gray" align="right" >&nbsp;<?=$lng[15][30]?>:&nbsp;&nbsp;</td>
	    <td class="gray">
	    <?
	       if ($r_assigned_u_id!=0)
	        {
	         $query4="select * from users where u_id=".$r_assigned_u_id;
 		 $rs4 = mysql_query($query4) or die(mysql_error());
  		 if($row4=mysql_fetch_array($rs4)) echo "&nbsp;".htmlspecialchars($row4['u_name']);
  		} 
  	       else echo "&nbsp;-";	
	      ?>
	    </td>
	    <?}else {echo '<td class="gray">&nbsp;</td><td class="gray">&nbsp;</td>';}?>
	    <?if ($rid) {?>
	    <td class="gray" align="right" >&nbsp;<?=$lng[15][45]?>:&nbsp;&nbsp;</td>
	    <td class="gray">&nbsp;<?=$r_id?></td>
	    <?}else {echo '<td class="gray">&nbsp;</td><td class="gray">&nbsp;</td>';}?>
	    <?if ($version) {?>
	    <td class="gray" align="right" >&nbsp;<?=$lng[15][34]?>:&nbsp;&nbsp;</td>
	    <td class="gray">&nbsp;<?=$r_version?></td>
	    <?}else {echo '<td class="gray">&nbsp;</td><td class="gray">&nbsp;</td>';}?>
	  </tr>  
	  <tr class="gray" valign="top">
	    <?if ($component) {?>
	    <td class="gray" align="right" >&nbsp;<?=$lng[15][40]?>:&nbsp;&nbsp;</td>
	    <td class="gray">
	    <?
	    $query26="select * from components where c_id in (".$r_component."0) order by c_name asc";
	    $rs26 = mysql_query($query26) or die(mysql_error());
	    while($row26=mysql_fetch_array($rs26))
	     {
	      echo htmlspecialchars($row26['c_name']);
	      echo "<br/>";
	     }
	    ?>
	    </td>
	    <?}else {echo '<td class="gray">&nbsp;</td><td class="gray">&nbsp;</td>';}?>
	    <?if ($source) {?>
	    <td class="gray" align="right" >&nbsp;<?=$lng[15][41]?>:&nbsp;&nbsp;</td>
	    <td class="gray"><?=$r_source?></td>
	    <?}else {echo '<td class="gray">&nbsp;</td><td class="gray">&nbsp;</td>';}?>
	    <?if ($risk) {?>
	    <td class="gray" align="right" >&nbsp;<?=$lng[15][42]?>:&nbsp;&nbsp;</td>
	    <td class="gray">
	      <?
	      echo $risk_array[$r_risk];
	      ?>
	    </td>
	    <?}else {echo '<td class="gray">&nbsp;</td><td class="gray">&nbsp;</td>';}?>
	    <?if ($complexity) {?>
	    <td class="gray" align="right" >&nbsp;<?=$lng[15][43]?>:&nbsp;&nbsp;</td>
	    <td class="gray">
	      <?
	      echo $complexity_array[$r_complexity];
	      ?>
	    </td>	    
	    <?}else {echo '<td class="gray">&nbsp;</td><td class="gray">&nbsp;</td>';}?>
	  </tr>  	  
	  <tr class="gray" valign="top">
	    <?if ($weight) {?>
	    <td class="gray" align="right" >&nbsp;<?=$lng[15][88]?>:&nbsp;&nbsp;</td>
	    <td class="gray" align="left">&nbsp;<?=$r_weight?></td>
	    <?}else {echo '<td class="gray">&nbsp;</td><td class="gray">&nbsp;</td>';}?>
	    <?if ($open_points) {?>
	    <td class="gray" align="right" >&nbsp;<?=$lng[15][44]?>:&nbsp;&nbsp;</td>
	    <td class="gray" align="left">&nbsp;<?=$r_points?></td> 
	    <?}else {echo '<td class="gray">&nbsp;</td><td class="gray">&nbsp;</td>';}?>
	    <?if ($keywords) {?>
	    <td class="gray" align="right" >&nbsp;<?=$lng[15][107]?>:&nbsp;&nbsp;</td>
	    <td class="gray" align="left" colspan="3">
	    <?
	      $query456="select k_id, k_name from keywords where k_id in (".$r_keywords."0)";
              $rs456 = mysql_query($query456) or die(mysql_error());	        
	      while($row456=mysql_fetch_array($rs456)) 
	       {
	        ?>
	        <?=htmlspecialchars($row456['k_name'])?><br/>
	      <?}?>
	    </td>
	    <?}else {echo '<td class="gray">&nbsp;</td><td class="gray" colspan="3">&nbsp;</td>';}?>
	  </tr> 
	  <tr class="gray" valign="top">
	    <?if ($satisfaction) {?>
	    <td class="gray" align="right" >&nbsp;<?=$lng[15][82]?>:&nbsp;&nbsp;</td>
	    <td class="gray" align="left" >&nbsp;<?=$r_satisfaction?></td>
	    <?}else {echo '<td class="gray">&nbsp;</td><td class="gray">&nbsp;</td>';}?>
	    <?if ($dissatisfaction) {?>
	    <td class="gray" align="right" >&nbsp;<?=$lng[15][84]?>:&nbsp;&nbsp;</td>
	    <td class="gray" align="left" >&nbsp;<?=$r_dissatisfaction?></td>
	    <?}else {echo '<td class="gray">&nbsp;</td><td class="gray">&nbsp;</td>';}?>
	    <?if ($depends) {?>
	    <td class="gray" align="right" >&nbsp;<?=$lng[15][78]?>:&nbsp;&nbsp;</td>
	    <td class="gray" align="left" >
	      <?
	      $query456="select r_id, r_name from requirements where r_id in (".$r_depends."0)";
              $rs456 = mysql_query($query456) or die(mysql_error());	        
	      while($row456=mysql_fetch_array($rs456)) 
	       {
	        ?>
	        &nbsp;<?=htmlspecialchars($row456['r_name'])?><br/>
	      <?}?>
	    </td>
	    <?}else {echo '<td class="gray">&nbsp;</td><td class="gray">&nbsp;</td>';}?>
	    <?if ($conflicts) {?>
	    <td class="gray" align="right" >&nbsp;<?=$lng[15][80]?>:&nbsp;&nbsp;</td>
	    <td class="gray" align="left" >
	      <?
	      $query456="select r_id, r_name from requirements where r_id in (".$r_conflicts."0)";
              $rs456 = mysql_query($query456) or die(mysql_error());	        
	      while($row456=mysql_fetch_array($rs456)) 
	       {
	        ?>
	        &nbsp;<?=htmlspecialchars($row456['r_name'])?><br/>
	      <?}?>
	    </td>
	    <?}else {echo '<td class="gray">&nbsp;</td><td class="gray">&nbsp;</td>';}?>
	  </tr> 
	  <tr class="gray" valign="top">
	    <?if ($author_) {?>
	    <td class="gray" align="right" >&nbsp;<?=$lng[15][16]?>:&nbsp;&nbsp;</td>
	    <td class="gray">&nbsp;<?=$author?></td>
	    <?}else {echo '<td class="gray">&nbsp;</td><td class="gray">&nbsp;</td>';}?>
	    <?if ($url) {?>
	    <td class="gray" align="right" >&nbsp;<?=$lng[15][14]?>:&nbsp;&nbsp;</td>
	    <td class="gray">&nbsp;<a href="<?=$r_link?>" target="_blank">
	    <?
	     for ($i=0;$i<strlen($r_link);$i+=40) 
	      echo substr($r_link,$i,40)." ";
	    ?>
	    </a></td>
	    <?}else {echo '<td class="gray">&nbsp;</td><td class="gray">&nbsp;</td>';}?>
	    <?if ($parent) {?>
	    <td class="gray" align="right" >&nbsp;<?=$lng[15][38]?>:&nbsp;&nbsp;</td>
	    <td class="gray">
	      <?
	      $query456="select r_name from requirements where r_id=".$r_parent_id;
              $rs456 = mysql_query($query456) or die(mysql_error());	        
	      if($row456=mysql_fetch_array($rs456)) 
	       {
	        ?>
	        &nbsp;<?=htmlspecialchars($row456['r_name'])?>
	      <?}?>
	    </td>	    
	    <?}else {echo '<td class="gray">&nbsp;</td><td class="gray">&nbsp;</td>';}?>
	    <?if ($position)
	       {
		  if (sizeof($arr45)>0) $r_pos=substr($arr45[array_search($r_id,$arr45_1)],0,strpos($arr45[array_search($r_id,$arr45_1)],"|")); 
                ?>
	    <td class="gray" align="right" >&nbsp;<?=$lng[15][39]?>:&nbsp;&nbsp;</td>
	    <td class="gray">&nbsp;<?=$r_pos?></td>	    
	    <?}else {echo '<td class="gray">&nbsp;</td><td class="gray">&nbsp;</td>';}?>
	  </tr>  
	  <?if ($userfields) {?>
	  <?
	  $cnt_u=0;
	  $query41="select count(*) from user_fields where uf_name_en<>''";
	  $rs41=mysql_query($query41) or die(mysql_error());
	  if($row41=mysql_fetch_array($rs41)) $cnt_num=$row41[0];
	  
	  unset($uf_name);
	  unset($uf_text);	   
	  if ($cnt_num>0) 
	   {
	    $query41="select * from user_fields order by uf_id asc";
	    $rs41=mysql_query($query41) or die(mysql_error());
	    while($row41=mysql_fetch_array($rs41))
	     {
	      $uf_name[]=htmlspecialchars($row41['uf_name_'.$_SESSION['chlang']]);
	      $uf_text[]=htmlspecialchars($row41['uf_text_'.$_SESSION['chlang']]);
             }
	    ?>
	  <tr class="gray" valign="top">
	    <?if ($cnt_num>0) {?>
	      <td align="right"  class="gray">&nbsp;<?=$uf_name[0]?>:&nbsp;&nbsp;</td>
	      <td align="left" class="gray" <?if ($cnt_num==1) echo "colspan='7'";?>><?=$r_userfield1?></td>
	    <?}?>
	    <?if ($cnt_num>1) {?>
	      <td align="right" class="gray" >&nbsp;<?=$uf_name[1]?>:&nbsp;&nbsp;</td>
	      <td align="left" class="gray" <?if ($cnt_num==2) echo "colspan=5";?>><?=$r_userfield2?></td>
	    <?}?>
	    <?if ($cnt_num>2) {?>
	      <td align="right" class="gray" >&nbsp;<?=$uf_name[2]?>:&nbsp;&nbsp;</td>
	      <td align="left" class="gray" colspan="3"><?=$r_userfield3?></td>
	    <?}?>
	  </tr> 
	  <?
	   }
	  if ($cnt_num>3) 
	   {
	    ?>
	  <tr class="gray" valign="top">
	    <?if ($cnt_num>3) {?>
	      <td align="right" class="gray" >&nbsp;<?=$uf_name[3]?>:&nbsp;&nbsp;</td>
	      <td align="left" class="gray" <?if ($cnt_num==4) echo "colspan='7'";?>><?=$r_userfield4?></td>
	    <?}?>
	    <?if ($cnt_num>4) {?>
	      <td align="right" class="gray" >&nbsp;<?=$uf_name[4]?>:&nbsp;&nbsp;</td>
	      <td align="left" class="gray" <?if ($cnt_num==5) echo "colspan=5";?>><?=$r_userfield5?></td>
	    <?}?>
	    <?if ($cnt_num>5) {?>
	      <td align="right" class="gray" >&nbsp;<?=$uf_name[5]?>:&nbsp;&nbsp;</td>
	      <td align="left" class="gray" colspan="3"><?=$r_userfield6?></td>
	    <?}?>
	  </tr> 
	  <?}?>
	  <?}?>
	  <tr class="gray" valign="top">
	  <?if ($creation_date) {?>
	    <td class="gray" align="right" >&nbsp;<?=$lng[15][17]?>:&nbsp;&nbsp;</td>
	    <td class="gray" >&nbsp;<?=($r_creation_date=="00.00.0000 00:00")?"-":$r_creation_date?></td>
	    <?}else {echo '<td class="gray">&nbsp;</td><td class="gray">&nbsp;</td>';}?>
	  <?if ($last_change) {?>
	    <td class="gray" align="right" >&nbsp;<?=$lng[15][18]?>:&nbsp;&nbsp;</td>
	    <td class="gray" >&nbsp;<?=($r_change_date=="00.00.0000 00:00")?"-":$r_change_date?></td>
	    <?}else {echo '<td class="gray">&nbsp;</td><td class="gray">&nbsp;</td>';}?>
	  <?if ($accepted_date) {?>
	    <td class="gray" align="right" >&nbsp;<?=$lng[15][19]?>:&nbsp;&nbsp;</td>
	    <td class="gray" >&nbsp;<?=($r_accept_date=="00.00.0000 00:00")?"-":$r_accept_date?></td>
	    <?}else {echo '<td class="gray">&nbsp;</td><td class="gray">&nbsp;</td>';}?>
	  <?if ($accepted_user) {?>
	    <td class="gray" align="right" >&nbsp;<?=$lng[15][20]?>:&nbsp;&nbsp;</td>
	    <td class="gray" >
	      <?
	       if ($r_accept_user!=0)
	        {
	         $query4="select * from users where u_id=".$r_accept_user;
 		 $rs4 = mysql_query($query4) or die(mysql_error());
  		 if($row4=mysql_fetch_array($rs4)) echo "&nbsp;".htmlspecialchars($row4['u_name']);
  		} 
  	       else echo "&nbsp;-";	
	      ?>
	    </td>
	  <?}else {echo '<td class="gray">&nbsp;</td><td class="gray">&nbsp;</td>';}?>
	  </tr>  
</table>

	  
<?}?>
<?}?>

</body>
</html>


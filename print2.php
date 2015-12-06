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

set_time_limit(0);
session_start();
include ("admin/inc/conn.php");//include settings file
include ("admin/inc/func.php");//include functions file
include ("ini/params.php");//include configuration file

//default language
$_SESSION['chlang']=$_lng;

if (!$_SESSION['chlang']) $_SESSION['chlang']="en";
include ("ini/lng/".$_SESSION['chlang'].".php");//include language file
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="description" content="<?=$lng[1][2]?>"/>
	<meta name="keywords" content="<?=$lng[1][3]?>"/>
	<title><?=$lng[1][1]?></title>
</head>
<body>
<?
//parse url vaiables
$_vars = explode("|", $r_id);
$history=$_vars[1];
$tree=$_vars[2];
$comments=$_vars[3];
$_lng=$_vars[4];
$project_list=$_vars[5];
$r_id=$_vars[0];
$fields=$_vars[6];

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


/*if ($r_id!="")
 {
  //check if logged
  if ($_SESSION['uid']=="")
   {
    //authorization check
    $query="select r.* from requirements r, projects p where r.r_id=".$r_id." and ((r.r_p_id=p.p_id and p.p_status=1) OR r.r_p_id=0)";
    $rs = mysql_query($query) or die(mysql_error());
    if($row=mysql_fetch_array($rs)) ;
    else header("Location:index.php");
   }
  else
   {
    //authorization check
    $query="select r.* from requirements r, projects p where r.r_id=".$r_id." and ((r.r_p_id=p.p_id and p.p_id in (".$project_list.")) OR r.r_p_id=0)";
    $rs = mysql_query($query) or die(mysql_error());
    if($row=mysql_fetch_array($rs)) ;
    else header("Location:index.php");
   } 
 }
*/ 
if ($r_id!="") 
 {
  $query="select r.*, date_format(r.r_creation_date, '%d.%m.%Y %H:%i') as d1, date_format(r.r_change_date, '%d.%m.%Y %H:%i') as d2, date_format(r.r_accept_date, '%d.%m.%Y %H:%i') as d3, u.u_name, p.p_name, p.p_id, sp.s_id, sp.s_name from requirements r left outer join users u on r.r_u_id=u.u_id left outer join projects p on r.r_p_id=p.p_id left outer join subprojects sp on r.r_s_id=sp.s_id where r.r_id=".$r_id;
  $rs = mysql_query($query) or die(mysql_error());
  if($row=mysql_fetch_array($rs)) 
   {
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
   }
  $query44="select c.*, u.u_name, date_format(c.c_date, '%d.%m.%Y %H:%i') as d1 from comments c left outer join users u on c.c_u_id=u.u_id where c.c_r_id=".$r_id." order by c_date desc";
  $rs44 = mysql_query($query44) or die(mysql_error());
 
 } 

$glossary_ids="";$cases_ids="";

$query2="select p.*, date_format(p.p_date, '%d.%m.%Y') as d1, u.u_name from projects p left outer join users u on p.p_leader=u.u_id where p.p_id=".$p_id;
$rs2 = mysql_query($query2) or die(mysql_error());
if($row2=mysql_fetch_array($rs2)) 
   {
    $p_desc=$row2['p_desc'];      
    $p_phase=htmlspecialchars($row2['p_phase']);      
    $p_status=htmlspecialchars($row2['p_status']);      
    $p_leader=htmlspecialchars($row2['u_name']);      
    $p_date=htmlspecialchars($row2['d1']);      
   }     
 ?>
      <table border="1" cellpadding="0" cellspacing="0" width="100%">
	<tr><td colspan="8">
	<table border="0" cellpadding="3" cellspacing="3" width="100%">
	  <tr>
	    <td align="center" colspan=8><b><?=$lng[17][7]?></b></td>
	  </tr>
	</table></td></tr> 
	<tr><td colspan="8"><table border="0" cellpadding="3" cellspacing="3" width="100%">
	  <tr valign="top">
	    <td align="right" width="15%">&nbsp;<?=$lng[17][22]?>:&nbsp;&nbsp;</td>
	    <td><?=$p_name?></td>
	    <td align="right" >&nbsp;<?=$lng[15][99]?>:&nbsp;&nbsp;</td>
	    <td>
	    <?
	    $query22="select * from subprojects where s_p_id='".$p_id."' order by s_name asc";
	    $rs22 = mysql_query($query22) or die(mysql_error());
	    while($row22=mysql_fetch_array($rs22))
	     {
	      echo htmlspecialchars($row22['s_name']);
	      echo "<br>";
	     }
	    ?>
	    </td>
	    <td align="right" >&nbsp;<?=$lng[15][24]?>:&nbsp;&nbsp;</td>
	    <td colspan="3">
	    <?
	    $query22="select r.*, date_format(r.r_date, '%d.%m.%Y') as d1, date_format(r.r_released_date, '%d.%m.%Y') as d2 from project_releases pr left outer join releases r on pr.pr_r_id=r.r_id where pr.pr_p_id='".$p_id."' order by r.r_name asc";
	    $rs22 = mysql_query($query22) or die(mysql_error());
	    while($row22=mysql_fetch_array($rs22))
	     {
	      echo htmlspecialchars($row22['r_name'])." (".$row22['d1'].")";
	      if ($row22['d2']!="00.00.0000") echo " - ".$row22['d2'];	      
	      echo "<br>";
	     }
	    ?>
	    </td>

	  </tr>
	</table></td></tr> 
	<tr><td colspan="8"><table border="0" cellpadding="3" cellspacing="3" width="100%">
	  <tr valign="top">
	    <td align="right">&nbsp;<?=$lng[15][103]?>:&nbsp;&nbsp;</td>
	    <td>
	    <?
	    $query26="select c.* from project_cases pc left outer join cases c on pc.pc_c_id=c.c_id where pc.pc_p_id='".$p_id."' order by c.c_name asc";
	    $rs26 = mysql_query($query26) or die(mysql_error());
	    while($row26=mysql_fetch_array($rs26))
	     {
              echo htmlspecialchars($row26['c_name']);
	      echo "<br>";
	     }
	    ?>	      
	    </td>
	    <td align="right" >&nbsp;<?=$lng[15][76]?>:&nbsp;&nbsp;</td>
	    <td>
	    <?
	    $query26="select s.* from project_stakeholders ps left outer join stakeholders s on ps.ps_s_id=s.s_id where ps.ps_p_id='".$p_id."' order by s.s_name asc";
	    $rs26 = mysql_query($query26) or die(mysql_error());
	    while($row26=mysql_fetch_array($rs26))
	     {
	      echo htmlspecialchars($row26['s_name']);
	      echo "<br>";
	     }
	    ?>	      
	    </td>
	    <td align="right" >&nbsp;<?=$lng[15][86]?>:&nbsp;&nbsp;</td>
	    <td colspan="3">
	    <?
	    $query26="select g.* from project_glossary pg left outer join glossary g on pg.pg_g_id=g.g_id where pg.pg_p_id='".$p_id."' order by g.g_id asc";
	    $rs26 = mysql_query($query26) or die(mysql_error());
	    while($row26=mysql_fetch_array($rs26))
	     {
	      $tmp_g="";
	      for ($i=0;$i<6-strlen($row26['g_id']);$i++) $tmp_g.="0";$tmp_g.=$row26['g_id'];
	      echo $tmp_g;
	      echo "<br>";
	     }
	    ?>	      
	    </td>

	  </tr>
	</table></td></tr> 
	<tr><td colspan="8"><table border="0" cellpadding="3" cellspacing="3" width="100%">
	  <tr valign="top">
	    <td align="right" >&nbsp;<?=$lng[15][115]?>:&nbsp;&nbsp;</td>
	    <td>
	    <?
	    $query26="select c.* from project_components pco left outer join components c on pco.pco_c_id=c.c_id where pco.pco_p_id='".$p_id."' order by c.c_name asc";
	    $rs26 = mysql_query($query26) or die(mysql_error());
	    while($row26=mysql_fetch_array($rs26))
	     {
	      echo htmlspecialchars($row26['c_name']);
	      echo "<br>";
	     }
	    ?>	      
	    </td>
	    <td align="right" >&nbsp;<?=$lng[15][16]?>:&nbsp;&nbsp;</td>
	    <td>&nbsp;<?=$author?></td>
	    <td align="right" >&nbsp;<?=$lng[17][19]?>:&nbsp;&nbsp;</td>
	    <td colspan="3">&nbsp;<?=$p_leader?></td>
	  </tr>
	</table></td></tr> 
	<tr><td colspan="8"><table border="0" cellpadding="3" cellspacing="3" width="100%">
	  <tr valign="top">
	    <td align="right"  width="15%">&nbsp;<?=$lng[17][23]?>:&nbsp;&nbsp;</td>
	    <td><?if ($p_phase==0) echo $lng[9][8];?><?if ($p_phase==1) echo $lng[9][9];?><?if ($p_phase==2) echo $lng[9][10];?><?if ($p_phase==3) echo $lng[9][32];?><?if ($p_phase==4) echo $lng[9][33];?></td>
	    <td align="right"  width="15%">&nbsp;<?=$lng[17][24]?>:&nbsp;&nbsp;</td>
	    <td><?if ($p_status==0) echo $lng[9][11];?><?if ($p_status==1) echo $lng[9][12];?><?if ($p_status==2) echo $lng[9][14];?></td>
	    <td align="right"  width="15%">&nbsp;<?=$lng[17][20]?>:&nbsp;&nbsp;</td>
	    <td colspan=3>&nbsp;<?=$p_date?></td>	    
	  </tr>
	</table></td></tr> 
	<tr><td colspan="8">
	<table border="0" cellpadding="9" cellspacing="3" width="100%">
	  <tr valign="top">
	    <td align="right"  width="15%">&nbsp;<?=$lng[17][21]?>:&nbsp;&nbsp;</td>
	    <td colspan=7>&nbsp;<?=$p_desc?></td>	    
	  </tr>
	</table>
	</td></tr> 
	  
	  
	</table>



<table border="0" cellpadding="0" cellspacing="0" width="100%">
	 <tr valign="top">
	    <td align="center">&nbsp;</td>
	    <td align="center" colspan=7 ><b><?=$lng[16][1]?></b></td>
	  </tr>
	  <tr valign="top">
	    <td align="right">&nbsp;<?=$lng[15][4]?>:&nbsp;&nbsp;</td>
	    <td colspan="7" width="100%"><b>&nbsp;<?=$r_name?></b></td>
	  </tr>
	  <?if ($description) {?>
	  <tr valign="top">
	    <td align="right" >&nbsp;<?=$lng[15][5]?>:&nbsp;&nbsp;</td>
	    <td align="left" colspan=7 width="100%"><?=$ta?></td>
	  </tr> 
	  <?}else {echo '<tr valign="top"><td>&nbsp;</td><td colspan=7>&nbsp;</td></tr> ';}?>
	  <tr valign="top">
	    <?if ($release) {?>
	    <td align="right" >&nbsp;<?=$lng[15][24]?>:&nbsp;&nbsp;</td>
	    <td>
	    <?
	    $query26="select r.*, date_format(r.r_date, '%d.%m.%Y') as d1, date_format(r.r_released_date, '%d.%m.%Y') as d2 from releases r where r.r_id in (".$r_release."0) order by r.r_name asc";
	    $rs26 = mysql_query($query26) or die(mysql_error());
	    while($row26=mysql_fetch_array($rs26))
	     {
	      echo htmlspecialchars($row26['r_name'])." (".$row26['d1'].")";
	      if ($row26['d2']!="00.00.0000") echo " - ".$row26['d2'];	      
	      echo "<br>";
	     }
	    ?>	      
	    </td> 
	    <?}else {echo '<td>&nbsp;</td><td>&nbsp;</td>';}?>
	  </tr>
	  <tr valign="top">
	    <?if ($test_case) {?>
	    <td align="right" >&nbsp;<?=$lng[15][103]?>:&nbsp;&nbsp;</td>
	    <td>
	    <?
	    $query26="select * from cases where c_id in (".$r_c_id."0) order by c_name asc";
	    $rs26 = mysql_query($query26) or die(mysql_error());
	    while($row26=mysql_fetch_array($rs26))
	     {
	      $cases_ids.=$row26['c_id'].",";
	      echo htmlspecialchars($row26['c_name']);
	      echo "<br>";	     
	     }
	    ?>	      
	    </td> 
	    <?}else {echo '<td>&nbsp;</td><td>&nbsp;</td>';}?>
	    <?if ($stakeholder) {?>
	    <td align="right" >&nbsp;<?=$lng[15][76]?>:&nbsp;&nbsp;</td>
	    <td>
	    <?
	    $query26="select * from stakeholders where s_id in (".$r_stakeholder."0) order by s_name asc";
	    $rs26 = mysql_query($query26) or die(mysql_error());
	    while($row26=mysql_fetch_array($rs26))
	     {
	      echo htmlspecialchars($row26['s_name']);
	      echo "<br>";
	     }
	    ?>	      
	    </td> 
	    <?}else {echo '<td>&nbsp;</td><td>&nbsp;</td>';}?>
	    <?if ($glossary) {?>
	    <td align="right" >&nbsp;<?=$lng[15][86]?>:&nbsp;&nbsp;</td>
	    <td colspan="3">
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
	      echo "<br>";
	     }
	    ?>	      
	    </td> 
	    <?}else {echo '<td>&nbsp;</td><td colspan="3">&nbsp;</td>';}?>
	  </tr>
	  <tr valign="top">
	    <?if ($state) {?>
	    <td align="right" >&nbsp;<?=$lng[15][10]?>:&nbsp;&nbsp;</td>
	    <td>
	      <?
	      include("ini/txts/".$_SESSION['chlang']."/state.php");
	      echo $state_array[$r_state];
	      ?>
	    </td> 
	    <?}else {echo '<td>&nbsp;</td><td>&nbsp;</td>';}?>
	    <?if ($type) {?>
	    <td align="right" >&nbsp;<?=$lng[15][11]?>:&nbsp;&nbsp;</td>
	    <td colspan="5">
	      <?
	      include("ini/txts/".$_SESSION['chlang']."/type.php");
	      echo $type_array[$r_type_r];
	      ?>
	    </td>	    
	    <?}else {echo '<td>&nbsp;</td><td colspan="5">&nbsp;</td>';}?>
	  </tr>
	  <tr valign="top">
	    <?if ($priority) {?>
	    <td align="right" >&nbsp;<?=$lng[15][13]?>:&nbsp;&nbsp;</td>
	    <td><?=$r_priority?></td>
	    <!--td align="right" >&nbsp;<?=$lng[15][15]?>:&nbsp;&nbsp;</td>
	    <td>
	      <?
	        if ($r_valid==0) echo $lng[15][31];
	        elseif ($r_valid==1) echo $lng[15][32];
	        elseif ($r_valid==2) echo $lng[15][33];
	      ?>	       	    
	    </td-->
	    <?}else {echo '<td>&nbsp;</td><td>&nbsp;</td>';}?>
	    <?if ($assign_to) {?>
	    <td align="right" >&nbsp;<?=$lng[15][30]?>:&nbsp;&nbsp;</td>
	    <td>
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
	    <?}else {echo '<td>&nbsp;</td><td>&nbsp;</td>';}?>
	    <?if ($rid) {?>
	    <td align="right" >&nbsp;<?=$lng[15][45]?>:&nbsp;&nbsp;</td>
	    <td>&nbsp;<?=$r_id?></td>
	    <?}else {echo '<td>&nbsp;</td><td>&nbsp;</td>';}?>
	    <?if ($version) {?>
	    <td align="right" >&nbsp;<?=$lng[15][34]?>:&nbsp;&nbsp;</td>
	    <td>&nbsp;<?=$r_version?></td>
	    <?}else {echo '<td>&nbsp;</td><td>&nbsp;</td>';}?>
	  </tr>  
	  <tr valign="top">
	    <?if ($component) {?>
	    <td align="right" >&nbsp;<?=$lng[15][40]?>:&nbsp;&nbsp;</td>
	    <td>
	    <?
	    $query26="select * from components where c_id in (".$r_component."0) order by c_name asc";
	    $rs26 = mysql_query($query26) or die(mysql_error());
	    while($row26=mysql_fetch_array($rs26))
	     {
	      echo htmlspecialchars($row26['c_name']);
	      echo "<br>";
	     }
	    ?>
	    </td>
	    <?}else {echo '<td>&nbsp;</td><td>&nbsp;</td>';}?>
	    <?if ($source) {?>
	    <td align="right" >&nbsp;<?=$lng[15][41]?>:&nbsp;&nbsp;</td>
	    <td><?=$r_source?></td>
	    <?}else {echo '<td>&nbsp;</td><td>&nbsp;</td>';}?>
	    <?if ($risk) {?>
	    <td align="right" >&nbsp;<?=$lng[15][42]?>:&nbsp;&nbsp;</td>
	    <td>
	      <?
	      include("ini/txts/".$_SESSION['chlang']."/risk.php");
	      echo $risk_array[$r_risk];
	      ?>
	    </td>
	    <?}else {echo '<td>&nbsp;</td><td>&nbsp;</td>';}?>
	    <?if ($complexity) {?>
	    <td align="right" >&nbsp;<?=$lng[15][43]?>:&nbsp;&nbsp;</td>
	    <td>
	      <?
	      include("ini/txts/".$_SESSION['chlang']."/complexity.php");
	      echo $complexity_array[$r_complexity];
	      ?>
	    </td>	    
	    <?}else {echo '<td>&nbsp;</td><td>&nbsp;</td>';}?>
	  </tr>  	  
	  <tr valign="top">
	    <?if ($weight) {?>
	    <td align="right" >&nbsp;<?=$lng[15][88]?>:&nbsp;&nbsp;</td>
	    <td align="left">&nbsp;<?=$r_weight?></td>
	    <?}else {echo '<td>&nbsp;</td><td>&nbsp;</td>';}?>
	    <?if ($open_points) {?>
	    <td align="right" >&nbsp;<?=$lng[15][44]?>:&nbsp;&nbsp;</td>
	    <td align="left">&nbsp;<?=$r_points?></td>
	    <?}else {echo '<td>&nbsp;</td><td>&nbsp;</td>';}?>
	    <?if ($keywords) {?>
	    <td align="right" >&nbsp;<?=$lng[15][107]?>:&nbsp;&nbsp;</td>
	    <td align="left" colspan=3>
	    <?
	      $query456="select k_id, k_name from keywords where k_id in (".$r_keywords."0)";
              $rs456 = mysql_query($query456) or die(mysql_error());	        
	      while($row456=mysql_fetch_array($rs456)) 
	       {
	        ?>
	        <?=htmlspecialchars($row456['k_name'])?><br>
	      <?}?>
	    </td>
	    <?}else {echo '<td>&nbsp;</td><td colspan=3>&nbsp;</td>';}?>
	  </tr> 
	  <tr valign="top">
	    <?if ($satisfaction) {?>
	    <td align="right" >&nbsp;<?=$lng[15][82]?>:&nbsp;&nbsp;</td>
	    <td align="left" >&nbsp;<?=$r_satisfaction?></td>
	    <?}else {echo '<td>&nbsp;</td><td>&nbsp;</td>';}?>
	    <?if ($dissatisfaction) {?>
	    <td align="right" >&nbsp;<?=$lng[15][84]?>:&nbsp;&nbsp;</td>
	    <td align="left" >&nbsp;<?=$r_dissatisfaction?></td>
	    <?}else {echo '<td>&nbsp;</td><td>&nbsp;</td>';}?>
	    <?if ($depends) {?>
	    <td align="right" >&nbsp;<?=$lng[15][78]?>:&nbsp;&nbsp;</td>
	    <td align="left" >
	      <?
	      $query456="select r_id, r_name from requirements where r_id in (".$r_depends."0)";
              $rs456 = mysql_query($query456) or die(mysql_error());	        
	      while($row456=mysql_fetch_array($rs456)) 
	       {
	        ?>
	        &nbsp;<?=htmlspecialchars($row456['r_name'])?><br>
	      <?}?>
	    </td>
	    <?}else {echo '<td>&nbsp;</td><td>&nbsp;</td>';}?>
	    <?if ($conflicts) {?>
	    <td align="right" >&nbsp;<?=$lng[15][80]?>:&nbsp;&nbsp;</td>
	    <td align="left" >
	      <?
	      $query456="select r_id, r_name from requirements where r_id in (".$r_conflicts."0)";
              $rs456 = mysql_query($query456) or die(mysql_error());	        
	      while($row456=mysql_fetch_array($rs456)) 
	       {
	        ?>
	        &nbsp;<?=htmlspecialchars($row456['r_name'])?><br>
	      <?}?>
	    </td>
	    <?}else {echo '<td>&nbsp;</td><td>&nbsp;</td>';}?>
	  </tr> 
	  <tr valign="top">
	    <?if ($author_) {?>
	    <td align="right" >&nbsp;<?=$lng[15][16]?>:&nbsp;&nbsp;</td>
	    <td>&nbsp;<?=$author?></td>
	    <?}else {echo '<td>&nbsp;</td><td>&nbsp;</td>';}?>
	    <?if ($url) {?>
	    <td align="right" >&nbsp;<?=$lng[15][14]?>:&nbsp;&nbsp;</td>
	    <td>&nbsp;<a href="<?=$r_link?>" target="_blank">
	    <?
	     for ($i=0;$i<strlen($r_link);$i+=40) 
	      echo substr($r_link,$i,40)." ";
	    ?>
	    </a></td>
	    <?}else {echo '<td>&nbsp;</td><td>&nbsp;</td>';}?>
	    <?if ($parent) {?>
	    <td align="right" >&nbsp;<?=$lng[15][38]?>:&nbsp;&nbsp;</td>
	    <td>
	      <?
	      $query456="select r_name from requirements where r_id=".$r_parent_id;
              $rs456 = mysql_query($query456) or die(mysql_error());	        
	      if($row456=mysql_fetch_array($rs456)) 
	       {
	        ?>
	        &nbsp;<?=htmlspecialchars($row456['r_name'])?>
	      <?}?>
	    </td>	    
	    <?}else {echo '<td>&nbsp;</td><td>&nbsp;</td>';}?>
	    <?if ($position) {?>
	    <td align="right" >&nbsp;<?=$lng[15][39]?>:&nbsp;&nbsp;</td>
	    <td>&nbsp;<?=$r_pos?></a></td>	    
	    <?}else {echo '<td>&nbsp;</td><td>&nbsp;</td>';}?>
	  </tr>  
	  <?if ($userfields) {?>
	  <?
	  $cnt_u=0;
	  $query41="select count(*) from user_fields where uf_name_en<>''";
	  $rs41=mysql_query($query41) or die(mysql_error());
	  if($row41=mysql_fetch_array($rs41)) $cnt_num=$row41[0];
		   
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
	  <tr valign="top">
	    <?if ($cnt_num>0) {?>
	      <td align="right" >&nbsp;<?=$uf_name[0]?>:&nbsp;&nbsp;</td>
	      <td align="left" <?if ($cnt_num==1) echo "colspan=7";?>><?=$r_userfield1?></td>
	    <?}?>
	    <?if ($cnt_num>1) {?>
	      <td align="right" >&nbsp;<?=$uf_name[1]?>:&nbsp;&nbsp;</td>
	      <td align="left" <?if ($cnt_num==2) echo "colspan=5";?>><?=$r_userfield2?></td>
	    <?}?>
	    <?if ($cnt_num>2) {?>
	      <td align="right" >&nbsp;<?=$uf_name[2]?>:&nbsp;&nbsp;</td>
	      <td align="left" colspan=3><?=$r_userfield3?></td>
	    <?}?>
	  </tr> 
	  <?
	   }
	  if ($cnt_num>3) 
	   {
	    ?>
	  <tr valign="top">
	    <?if ($cnt_num>3) {?>
	      <td align="right" >&nbsp;<?=$uf_name[3]?>:&nbsp;&nbsp;</td>
	      <td align="left" <?if ($cnt_num==4) echo "colspan=7";?>><?=$r_userfield4?></td>
	    <?}?>
	    <?if ($cnt_num>4) {?>
	      <td align="right" >&nbsp;<?=$uf_name[4]?>:&nbsp;&nbsp;</td>
	      <td align="left" <?if ($cnt_num==5) echo "colspan=5";?>><?=$r_userfield5?></td>
	    <?}?>
	    <?if ($cnt_num>5) {?>
	      <td align="right" >&nbsp;<?=$uf_name[5]?>:&nbsp;&nbsp;</td>
	      <td align="left" colspan=3><?=$r_userfield6?></td>
	    <?}?>
	  </tr> 
	  <?}?>
	  <?}?>
	  <tr valign="top">
	  <?if ($creation_date) {?>
	    <td align="right" >&nbsp;<?=$lng[15][17]?>:&nbsp;&nbsp;</td>
	    <td >&nbsp;<?=($r_creation_date=="00.00.0000 00:00")?"-":$r_creation_date?></td>
	    <?}else {echo '<td>&nbsp;</td><td>&nbsp;</td>';}?>
	  <?if ($last_change) {?>
	    <td align="right" >&nbsp;<?=$lng[15][18]?>:&nbsp;&nbsp;</td>
	    <td >&nbsp;<?=($r_change_date=="00.00.0000 00:00")?"-":$r_change_date?></td>
	    <?}else {echo '<td>&nbsp;</td><td>&nbsp;</td>';}?>
	  <?if ($accepted_date) {?>
	    <td align="right" >&nbsp;<?=$lng[15][19]?>:&nbsp;&nbsp;</td>
	    <td >&nbsp;<?=($r_accept_date=="00.00.0000 00:00")?"-":$r_accept_date?></td>
	    <?}else {echo '<td>&nbsp;</td><td>&nbsp;</td>';}?>
	  <?if ($accepted_user) {?>
	    <td align="right" >&nbsp;<?=$lng[15][20]?>:&nbsp;&nbsp;</td>
	    <td >
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
	  <?}else {echo '<td>&nbsp;</td><td>&nbsp;</td>';}?>
	  </tr> 
</table>

<?//if history to be shown
if ($history==1){?>

<?
$cnt=0;

$query2="select r_id from requirements_history where r_parent_id=".$r_id." order by r_save_date desc";
$rs2 = mysql_query($query2) or die(mysql_error());
while($row2=mysql_fetch_array($rs2)) 
 {
  if ($row2['r_id']!="") 
   {
    $cnt++; 
    $query="select r.*, date_format(r.r_creation_date, '%d.%m.%Y %H:%i') as d1, date_format(r.r_change_date, '%d.%m.%Y %H:%i') as d2, date_format(r.r_accept_date, '%d.%m.%Y %H:%i') as d3, date_format(r.r_save_date, '%d.%m.%Y %H:%i') as d6, u.u_name, p.p_name, p.p_id, sp.s_id, sp.s_name from requirements_history r left outer join users u on r.r_u_id=u.u_id left outer join projects p on r.r_p_id=p.p_id left outer join subprojects sp on r.r_s_id=sp.s_id where r.r_id=".$row2['r_id'];
    $rs = mysql_query($query) or die(mysql_error());
    if($row=mysql_fetch_array($rs)) 
     {
      $p_name=htmlspecialchars($row['p_name']);
      if ($p_name==$p_name_tmp) $cl1="gray";else $cl1="red";$p_name_tmp=$p_name;
      $p_id=htmlspecialchars($row['p_id']);
      $s_id=htmlspecialchars($row['s_id']);
      if ($s_id==$s_id_tmp) $cl36="gray";else $cl36="red";$s_id_tmp=$s_id;
      $r_release=htmlspecialchars($row['r_release']);
      if ($r_release==$r_release_tmp) $cl40="gray";else $cl40="red";$r_release_tmp=$r_release;
      $r_c_id=htmlspecialchars($row['r_c_id']);
      if ($r_c_id==$r_c_id_tmp) $cl37="gray";else $cl37="red";$r_c_id_tmp=$r_c_id;
      $r_stakeholder=htmlspecialchars($row['r_stakeholder']);
      if ($r_stakeholder==$r_stakeholder_tmp) $cl39="gray";else $cl39="red";$r_stakeholder_tmp=$r_stakeholder;
      $r_glossary=htmlspecialchars($row['r_glossary']);
      if ($r_glossary==$r_glossary_tmp) $cl41="gray";else $cl41="red";$r_glossary_tmp=$r_glossary;
      $s_name=htmlspecialchars($row['s_name']);
      $r_name=htmlspecialchars($row['r_name']);
      if ($r_name==$r_name_tmp) $cl3="gray";else $cl3="red";$r_name_tmp=$r_name;
      $r_state=$row['r_state'];
      if ($r_state==$r_state_tmp) $cl4="gray";else $cl4="red";$r_state_tmp=$r_state;
      $r_type_r=$row['r_type_r'];
      if ($r_type_r==$r_type_r_tmp) $cl5="gray";else $cl5="red";$r_type_r_tmp=$r_type_r;
      $r_priority=$row['r_priority'];
      if ($r_priority==$r_priority_tmp) $cl6="gray";else $cl6="red";$r_priority_tmp=$r_priority;
      $r_assigned_u_id=$row['r_assigned_u_id'];
      if ($r_assigned_u_id==$r_assigned_u_id_tmp) $cl8="gray";else $cl8="red";$r_assigned_u_id_tmp=$r_assigned_u_id;
      $author=htmlspecialchars($row['u_name']);
      if ($author==$author_tmp) $cl10="gray";else $cl10="red";$author_tmp=$author;
      $r_version=htmlspecialchars($row['r_version']);
      if ($r_version==$r_version_tmp) $cl9="gray";else $cl9="gray";$r_version_tmp=$r_version;
      $r_link=htmlspecialchars($row['r_link']);
      if ($r_link==$r_link_tmp) $cl11="gray";else $cl11="red";$r_link_tmp=$r_link;
      $r_satisfaction=htmlspecialchars($row['r_satisfaction']);
      if ($r_satisfaction==$r_satisfaction_tmp) $cl26="gray";else $cl26="red";$r_satisfaction_tmp=$r_satisfaction;
      $r_dissatisfaction=htmlspecialchars($row['r_dissatisfaction']);
      if ($r_dissatisfaction==$r_dissatisfaction_tmp) $cl27="gray";else $cl27="red";$r_dissatisfaction_tmp=$r_dissatisfaction;
      $r_conflicts=htmlspecialchars($row['r_conflicts']);
      if ($r_conflicts==$r_conflicts_tmp) $cl28="gray";else $cl28="red";$r_conflicts_tmp=$r_conflicts;
      $r_depends=htmlspecialchars($row['r_depends']);
      if ($r_depends==$r_depends_tmp) $cl25="gray";else $cl25="red";$r_depends_tmp=$r_depends;
      $r_component=htmlspecialchars($row['r_component']);
      if ($r_component==$r_component_tmp) $cl20="gray";else $cl20="red";$r_component_tmp=$r_component;
      $r_source=htmlspecialchars($row['r_source']);
      if ($r_source==$r_source_tmp) $cl21="gray";else $cl21="red";$r_source_tmp=$r_source;
      $r_risk=htmlspecialchars($row['r_risk']);
      if ($r_risk==$r_risk_tmp) $cl22="gray";else $cl22="red";$r_risk_tmp=$r_risk;
      $r_complexity=htmlspecialchars($row['r_complexity']);
      if ($r_complexity==$r_complexity_tmp) $cl23="gray";else $cl23="red";$r_complexity_tmp=$r_complexity;
      $r_weight=htmlspecialchars($row['r_weight']);
      if ($r_weight==$r_weight_tmp) $cl29="gray";else $cl29="red";$r_weight_tmp=$r_weight;
      $r_points=htmlspecialchars($row['r_points']);
      if ($r_points==$r_points_tmp) $cl24="gray";else $cl24="red";$r_points_tmp=$r_points;
      $ta=$row['r_desc'];
      if ($ta==$ta_tmp) $cl12="gray";else $cl12="red";$ta_tmp=$ta;
      $r_creation_date=$row['d1'];
      if ($r_creation_date==$r_creation_date_tmp) $cl13="gray";else $cl13="red";$r_creation_date_tmp=$r_creation_date;
      $r_change_date=$row['d2'];
      if ($r_change_date==$r_change_date_tmp) $cl14="gray";else $cl14="gray";$r_change_date_tmp=$r_change_date;
      $r_accept_date=$row['d3'];
      if ($r_accept_date==$r_accept_date_tmp) $cl15="gray";else $cl15="red";$r_accept_date_tmp=$r_accept_date;
      $r_accept_user=$row['r_accept_user'];  
      if ($r_accept_user==$r_accept_user_tmp) $cl16="gray";else $cl16="red";$r_accept_user_tmp=$r_accept_user;
      $r_save_date=$row['d6'];
      $r_save_user=$row['r_save_user'];
      $r_parent_id=$row['r_parent_id2'];  
      if ($r_parent_id==$r_parent_id_tmp) $cl17="gray";else $cl17="red";$r_parent_id_tmp=$r_parent_id;
      $r_pos=$row['r_pos'];  
      if ($r_pos==$r_pos_tmp) $cl18="gray";else $cl18="red";$r_pos_tmp=$r_pos;
      $r_keywords=$row['r_keywords'];  
      if ($r_keywords==$r_keywords_tmp) $cl38="gray";else $cl38="red";$r_keywords_tmp=$r_keywords;
      $r_userfield1=htmlspecialchars($row['r_userfield1']);  
      if ($r_userfield1==$r_userfield1_tmp) $cl30="gray";else $cl30="red";$r_userfield1_tmp=$r_userfield1;
      $r_userfield2=htmlspecialchars($row['r_userfield2']);  
      if ($r_userfield2==$r_userfield2_tmp) $cl31="gray";else $cl31="red";$r_userfield2_tmp=$r_userfield2;
      $r_userfield3=htmlspecialchars($row['r_userfield3']);  
      if ($r_userfield3==$r_userfield3_tmp) $cl32="gray";else $cl32="red";$r_userfield3_tmp=$r_userfield3;
      $r_userfield4=htmlspecialchars($row['r_userfield4']);  
      if ($r_userfield4==$r_userfield4_tmp) $cl33="gray";else $cl33="red";$r_userfield4_tmp=$r_userfield4;
      $r_userfield5=htmlspecialchars($row['r_userfield5']);  
      if ($r_userfield5==$r_userfield5_tmp) $cl34="gray";else $cl34="red";$r_userfield5_tmp=$r_userfield5;
      $r_userfield6=htmlspecialchars($row['r_userfield6']);  
      if ($r_userfield6==$r_userfield6_tmp) $cl35="gray";else $cl35="red";$r_userfield6_tmp=$r_userfield6;
     }
   }   
 ?>
<table border="0" width="100%">
  <tr valign="top">
    <td>
      <table border="0" cellpadding="0" cellspacing="0">
	<tr><td colspan="8"><table border="0" cellpadding="3" cellspacing="3" width="100%">
	  <tr valign="top">
	    <td colspan=2>
	      &nbsp;
	      <?=$r_save_date?>
	      <?
	       if ($r_save_user!=0)
	        {
	         $query4="select * from users where u_id=".$r_save_user;
 		 $rs4 = mysql_query($query4) or die(mysql_error());
  		 if($row4=mysql_fetch_array($rs4)) echo "&nbsp;".htmlspecialchars($row4['u_name']);
  		} 
  	       ?>	
	    </td>
	    <td align="center" colspan=6><b><?if ($cnt==1){?><?=$lng[16][6]?><?}?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
	  </tr>
	</table></td></tr> 
	  
	<tr><td colspan="8"><table border="0" cellpadding="3" cellspacing="3" width="100%">
	  <tr valign="top">
	    <td class="<?=$cl3?>" align="right" >&nbsp;<?=$lng[15][4]?>:&nbsp;&nbsp;</td>
	    <td class="<?=$cl3?>" colspan=7 width="100%"><b><?=$r_name?></b></td>
	  </tr>	  
	</table></td></tr> 
	<tr><td colspan="8"><table border="0" cellpadding="9" cellspacing="3" width="100%">
	  <?if ($description) {?>
	  <tr valign="top">
	    <td class="<?=$cl12?>" align="right" >&nbsp;<?=$lng[15][5]?>:&nbsp;&nbsp;</td>
	    <td class="<?=$cl12?>" align="left" colspan=7 width="100%"><?=$ta?> 
	    </td>
	  </tr> 
	  <?}else {echo '<tr valign="top"><td>&nbsp;</td><td colspan=7>&nbsp;</td></tr>';}?>
	</table></td></tr> 
	<tr><td colspan="8"><table border="0" cellpadding="3" cellspacing="3" width="100%">
	  <tr valign="top">
	  <?if ($project) {?>
	    <td class="<?=$cl1?>" align="right"  width="15%">&nbsp;<?=$lng[15][3]?>:&nbsp;&nbsp;</td>
	    <td class="<?=$cl1?>"><?=$p_name?></td>
	  <?}else {echo '<td width="15%">&nbsp;</td><td>&nbsp;</td>';}?>
	  <?if ($subproject) {?>
	    <td class="<?=$cl36?>" >&nbsp;<?=$lng[15][97]?>:&nbsp;&nbsp;</td>
	    <td class="<?=$cl36?>"><?=$s_name?></td>
	  <?}else {echo '<td>&nbsp;</td><td>&nbsp;</td>';}?>
	  <?if ($release) {?>
	    <td class="<?=$cl40?>" align="right" >&nbsp;<?=$lng[15][24]?>:&nbsp;&nbsp;</td>
	    <td class="<?=$cl40?>" colspan="3">
	    <?
	    $query22="select r.*, date_format(r.r_date, '%d.%m.%Y') as d1, date_format(r.r_released_date, '%d.%m.%Y') as d2 from project_releases pr left outer join releases r on pr.pr_r_id=r.r_id where pr.pr_p_id='".$p_id."' order by r.r_name asc";
	    $rs22 = mysql_query($query22) or die(mysql_error());
	    while($row22=mysql_fetch_array($rs22))
	     {
	      echo htmlspecialchars($row22['r_name'])." (".$row22['d1'].")";
	      if ($row22['d2']!="00.00.0000") echo " - ".$row22['d2'];	      
	      echo "<br>";
	     }
	    ?>
	    </td>
	  <?}else {echo '<td>&nbsp;</td><td colspan=3>&nbsp;</td>';}?>
	  </tr>
	</table></td></tr> 
	<tr><td colspan="8"><table border="0" cellpadding="3" cellspacing="3" width="100%">
	  <tr valign="top">
	  <?if ($release) {?>
	    <td class="<?=$cl40?>" align="right" >&nbsp;<?=$lng[15][24]?>:&nbsp;&nbsp;</td>
	    <td class="<?=$cl40?>">
	    <?
	    $query26="select r.*, date_format(r.r_date, '%d.%m.%Y') as d1, date_format(r.r_released_date, '%d.%m.%Y') as d2 from releases r where r.r_id in (".$r_release."0) order by r.r_name asc";
	    $rs26 = mysql_query($query26) or die(mysql_error());
	    while($row26=mysql_fetch_array($rs26))
	     {
	      echo htmlspecialchars($row26['r_name'])." (".$row26['d1'].")";
	      if ($row26['d2']!="00.00.0000") echo " - ".$row26['d2'];	      
	      echo "<br>";
	     }
	    ?>	      
	    </td> 
	  <?}else {echo '<td>&nbsp;</td><td>&nbsp;</td>';}?>

	  </tr>
	</table></td></tr> 
	<tr><td colspan="8"><table border="0" cellpadding="3" cellspacing="3" width="100%">
	  <tr valign="top">
	  <?if ($test_case) {?>
	    <td class="<?=$cl37?>" align="right" >&nbsp;<?=$lng[15][103]?>:&nbsp;&nbsp;</td>
	    <td class="<?=$cl37?>">
	    <?
	    $query26="select * from cases where c_id in (".$r_c_id."0) order by c_name asc";
	    $rs26 = mysql_query($query26) or die(mysql_error());
	    while($row26=mysql_fetch_array($rs26))
	     {
	      $cases_ids.=$row26['c_id'].",";
	      echo htmlspecialchars($row26['c_name']);
	      echo "<br>";
	     }
	    ?>	      
	    </td> 
	  <?}else {echo '<td>&nbsp;</td><td>&nbsp;</td>';}?>
	  <?if ($stakeholder) {?>
	    <td class="<?=$cl39?>" align="right" >&nbsp;<?=$lng[15][76]?>:&nbsp;&nbsp;</td>
	    <td class="<?=$cl39?>">
	    <?
	    $query26="select * from stakeholders where s_id in (".$r_stakeholder."0) order by s_name asc";
	    $rs26 = mysql_query($query26) or die(mysql_error());
	    while($row26=mysql_fetch_array($rs26))
	     {
	      echo htmlspecialchars($row26['s_name']);
	      echo "<br>";
	     }
	    ?>	      
	    </td> 
	  <?}else {echo '<td>&nbsp;</td><td>&nbsp;</td>';}?>
	  <?if ($glossary) {?>
	    <td class="<?=$cl41?>" align="right" >&nbsp;<?=$lng[15][86]?>:&nbsp;&nbsp;</td>
	    <td class="<?=$cl41?>" colspan="3">
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
	      echo "<br>";
	     }
	    ?>	      
	    </td> 
	  <?}else {echo '<td>&nbsp;</td><td colspan="3">&nbsp;</td>';}?>
	  </tr>
	</table></td></tr> 
	<tr><td colspan="8"><table border="0" cellpadding="3" cellspacing="3" width="100%">
	  <tr valign="top">
	  <?if ($state) {?>
	    <td class="<?=$cl4?>" align="right" >&nbsp;<?=$lng[15][10]?>:&nbsp;&nbsp;</td>
	    <td class="<?=$cl4?>">
	      <?
	      include("ini/txts/".$_SESSION['chlang']."/state.php");
	      echo $state_array[$r_state];
	      ?>
	    </td> 
	  <?}else {echo '<td>&nbsp;</td><td>&nbsp;</td>';}?>
	  <?if ($type) {?>
	    <td class="<?=$cl5?>" align="right" >&nbsp;<?=$lng[15][11]?>:&nbsp;&nbsp;</td>
	    <td class="<?=$cl5?>" colspan="5">
	      <?
	      include("ini/txts/".$_SESSION['chlang']."/type.php");
	      echo $type_array[$r_type_r];
	      ?>
	    </td>
	  <?}else {echo '<td>&nbsp;</td><td colspan="5">&nbsp;</td>';}?>
	  </tr>
	</table></td></tr> 
	<tr><td colspan="8"><table border="0" cellpadding="3" cellspacing="3" width="100%">
	  <tr valign="top">
	  <?if ($priority) {?>
	    <td class="<?=$cl6?>" align="right" >&nbsp;<?=$lng[15][13]?>:&nbsp;&nbsp;</td>
	    <td class="<?=$cl6?>"><?=$r_priority?></td>
	    <!--td class="<?=$cl7?>" align="right" >&nbsp;<?=$lng[15][15]?>:&nbsp;&nbsp;</td>
	    <td class="<?=$cl7?>">
	      <?
	        if ($r_valid==0) echo $lng[15][31];
	        elseif ($r_valid==1) echo $lng[15][32];
	        elseif ($r_valid==2) echo $lng[15][33];
	      ?>	       	    
	    </td-->
	  <?}else {echo '<td>&nbsp;</td><td>&nbsp;</td>';}?>
	  <?if ($assign_to) {?>
	    <td class="<?=$cl8?>" align="right" >&nbsp;<?=$lng[15][30]?>:&nbsp;&nbsp;</td>
	    <td class="<?=$cl8?>">
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
	  <?}else {echo '<td>&nbsp;</td><td>&nbsp;</td>';}?>
	  <?if ($rid) {?>
	    <td align="right" >&nbsp;<?=$lng[15][45]?>:&nbsp;&nbsp;</td>
	    <td>&nbsp;<?=$r_id?></td>
	  <?}else {echo '<td>&nbsp;</td><td>&nbsp;</td>';}?>
	  <?if ($version) {?>
	    <td align="right" >&nbsp;<?=$lng[15][34]?>:&nbsp;&nbsp;</td>
	    <td>&nbsp;<?=$r_version?></td>
	  <?}else {echo '<td>&nbsp;</td><td>&nbsp;</td>';}?>
	  </tr>  
	</table></td></tr> 
	<tr><td colspan="8"><table border="0" cellpadding="3" cellspacing="3" width="100%">
	  <tr valign="top">
	  <?if ($component) {?>
	    <td class="<?=$cl20?>" align="right" >&nbsp;<?=$lng[15][40]?>:&nbsp;&nbsp;</td>
	    <td class="<?=$cl20?>">
	    <?
	    $query26="select * from components where c_id in (".$r_component."0) order by c_name asc";
	    $rs26 = mysql_query($query26) or die(mysql_error());
	    while($row26=mysql_fetch_array($rs26))
	     {
	      echo htmlspecialchars($row26['c_name']);
	      echo "<br>";
	     }
	    ?>
	    </td>
	  <?}else {echo '<td>&nbsp;</td><td>&nbsp;</td>';}?>
	  <?if ($source) {?>
	    <td class="<?=$cl21?>" align="right" >&nbsp;<?=$lng[15][41]?>:&nbsp;&nbsp;</td>
	    <td class="<?=$cl21?>"><?=$r_source?></td>
	  <?}else {echo '<td>&nbsp;</td><td>&nbsp;</td>';}?>
	  <?if ($risk) {?>
	    <td class="<?=$cl22?>" align="right" >&nbsp;<?=$lng[15][42]?>:&nbsp;&nbsp;</td>
	    <td class="<?=$cl22?>">
	      <?
	      include("ini/txts/".$_SESSION['chlang']."/risk.php");
	      echo $risk_array[$r_risk];
	      ?>
	    </td>
	  <?}else {echo '<td>&nbsp;</td><td>&nbsp;</td>';}?>
	  <?if ($complexity) {?>
	    <td class="<?=$cl23?>" align="right" >&nbsp;<?=$lng[15][43]?>:&nbsp;&nbsp;</td>
	    <td class="<?=$cl23?>">
	      <?
	      include("ini/txts/".$_SESSION['chlang']."/complexity.php");
	      echo $complexity_array[$r_complexity];
	      ?>
	    </td>
	  <?}else {echo '<td>&nbsp;</td><td>&nbsp;</td>';}?>
	  </tr>  
	</table></td></tr> 
	<tr><td colspan="8"><table border="0" cellpadding="9" cellspacing="3" width="100%">
	  <tr valign="top">
	  <?if ($weight) {?>
	    <td class="<?=$cl29?>" align="right" >&nbsp;<?=$lng[15][88]?>:&nbsp;&nbsp;</td>
	    <td class="<?=$cl29?>" align="left"><?=$r_weight?> 
	  <?}else {echo '<td>&nbsp;</td><td>&nbsp;</td>';}?>
	  <?if ($open_points) {?>
	    <td class="<?=$cl24?>" align="right" >&nbsp;<?=$lng[15][44]?>:&nbsp;&nbsp;</td>
	    <td class="<?=$cl24?>" align="left"><?=$r_points?> 
	  <?}else {echo '<td>&nbsp;</td><td>&nbsp;</td>';}?>
	  <?if ($keywords) {?>
	    <td class="<?=$cl24?>" align="right" >&nbsp;<?=$lng[15][107]?>:&nbsp;&nbsp;</td>
	    <td class="<?=$cl24?>" align="left" colspan=3>
	    <?
	      $query456="select k_id, k_name from keywords where k_id in (".$r_keywords."0)";
              $rs456 = mysql_query($query456) or die(mysql_error());	        
	      while($row456=mysql_fetch_array($rs456)) 
	       {
	        ?>
	        <?=htmlspecialchars($row456['k_name'])?><br>
	      <?}?>
	    </td>
	  <?}else {echo '<td>&nbsp;</td><td colspan=3>&nbsp;</td>';}?>
	  </tr> 
	</table></td></tr> 
	<tr><td colspan="8"><table border="0" cellpadding="3" cellspacing="3" width="100%">
	  <tr valign="top">
	  <?if ($satisfaction) {?>
	    <td class="<?=$cl26?>" align="right" >&nbsp;<?=$lng[15][82]?>:&nbsp;&nbsp;</td>
	    <td class="<?=$cl26?>" align="left">&nbsp;<?=$r_satisfaction?></td>
	  <?}else {echo '<td>&nbsp;</td><td>&nbsp;</td>';}?>
	  <?if ($dissatisfaction) {?>
	    <td class="<?=$cl27?>" align="right" >&nbsp;<?=$lng[15][84]?>:&nbsp;&nbsp;</td>
	    <td class="<?=$cl27?>" align="left">&nbsp;<?=$r_dissatisfaction?></td>
	  <?}else {echo '<td>&nbsp;</td><td>&nbsp;</td>';}?>
	  <?if ($depends) {?>
	    <td class="<?=$cl25?>" align="right" >&nbsp;<?=$lng[15][78]?>:&nbsp;&nbsp;</td>
	    <td class="<?=$cl25?>" align="left">
	      <?
	      $query456="select r_id, r_name from requirements where r_id in (".$r_depends."0)";
              $rs456 = mysql_query($query456) or die(mysql_error());	        
	      while($row456=mysql_fetch_array($rs456)) 
	       {
	        ?>
	        &nbsp;<?=htmlspecialchars($row456['r_name'])?><br>
	      <?}?>
	    </td>
	  <?}else {echo '<td>&nbsp;</td><td>&nbsp;</td>';}?>
	  <?if ($conflicts) {?>
	    <td class="<?=$cl28?>" align="right" >&nbsp;<?=$lng[15][80]?>:&nbsp;&nbsp;</td>
	    <td class="<?=$cl28?>" align="left">
	      <?
	      $query456="select r_id, r_name from requirements where r_id in (".$r_conflicts."0)";
              $rs456 = mysql_query($query456) or die(mysql_error());	        
	      while($row456=mysql_fetch_array($rs456)) 
	       {
	        ?>
	        &nbsp;<?=htmlspecialchars($row456['r_name'])?><br>
	      <?}?>
	    </td>
	  <?}else {echo '<td>&nbsp;</td><td>&nbsp;</td>';}?>
	  </tr> 
	</table></td></tr> 
	<tr><td colspan="8"><table border="0" cellpadding="3" cellspacing="3" width="100%">
	  <tr valign="top">
	  <?if ($author_) {?>
	    <td class="<?=$cl10?>" align="right" >&nbsp;<?=$lng[15][16]?>:&nbsp;&nbsp;</td>
	    <td class="<?=$cl10?>">&nbsp;<?=$author?></td>
	  <?}else {echo '<td>&nbsp;</td><td>&nbsp;</td>';}?>
	  <?if ($url) {?>
	    <td class="<?=$cl11?>" align="right" >&nbsp;<?=$lng[15][14]?>:&nbsp;&nbsp;</td>
	    <td class="<?=$cl11?>">&nbsp;<a href="<?=$r_link?>" target="_blank">
	    <?
	     for ($i=0;$i<strlen($r_link);$i+=40) 
	      echo substr($r_link,$i,40)." ";
	    ?>
	    </a></td>
	  <?}else {echo '<td>&nbsp;</td><td>&nbsp;</td>';}?>
	  <?if ($parent) {?>
	    <td class="<?=$cl17?>" align="right" >&nbsp;<?=$lng[15][38]?>:&nbsp;&nbsp;</td>
	    <td class="<?=$cl17?>">
	      <?
	      $query456="select r_name from requirements where r_id=".$r_parent_id;
              $rs456 = mysql_query($query456) or die(mysql_error());	        
	      if($row456=mysql_fetch_array($rs456)) 
	       {
	        ?>
	        &nbsp;<?=htmlspecialchars($row456['r_name'])?>
	      <?}?>
	    </td>	    
	  <?}else {echo '<td>&nbsp;</td><td>&nbsp;</td>';}?>
	  <?if ($position) {?>
	    <td class="<?=$cl18?>" align="right" >&nbsp;<?=$lng[15][39]?>:&nbsp;&nbsp;</td>
	    <td class="<?=$cl18?>">&nbsp;<?=$r_pos?></a></td>	    
	  <?}else {echo '<td>&nbsp;</td><td>&nbsp;</td>';}?>
	  </tr>  
	</table></td></tr> 
	  <?if ($userfields) {?>
	  <?
	  $cnt_u=0;
	  $query41="select count(*) from user_fields where uf_name_en<>''";
	  $rs41=mysql_query($query41) or die(mysql_error());
	  if($row41=mysql_fetch_array($rs41)) $cnt_num=$row41[0];
		   
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
	<tr><td colspan="8"><table border="0" cellpadding="3" cellspacing="3" width="100%">
	  <tr valign="top">
	    <?if ($cnt_num>0) {?>
	      <td class="<?=$cl30?>" align="right"  title="<?=$uf_text[0]?>">&nbsp;<?=$uf_name[0]?>:&nbsp;&nbsp;</td>
	      <td class="<?=$cl30?>" align="left" <?if ($cnt_num==1) echo "colspan=7";?>><?=$r_userfield1?></td>
	    <?}?>
	    <?if ($cnt_num>1) {?>
	      <td class="<?=$cl31?>" align="right"  title="<?=$uf_text[1]?>">&nbsp;<?=$uf_name[1]?>:&nbsp;&nbsp;</td>
	      <td class="<?=$cl31?>" align="left" <?if ($cnt_num==2) echo "colspan=5";?>><?=$r_userfield2?></td>
	    <?}?>
	    <?if ($cnt_num>2) {?>
	      <td class="<?=$cl32?>" align="right"  title="<?=$uf_text[2]?>">&nbsp;<?=$uf_name[2]?>:&nbsp;&nbsp;</td>
	      <td class="<?=$cl32?>" align="left" colspan=3><?=$r_userfield3?></td>
	    <?}?>
	  </tr> 
	</table></td></tr> 
	  <?
	   }
	  if ($cnt_num>3) 
	   {
	    ?>
	<tr><td colspan="8"><table border="0" cellpadding="3" cellspacing="3" width="100%">
	  <tr valign="top">
	    <?if ($cnt_num>3) {?>
	      <td class="<?=$cl33?>" align="right"  title="<?=$uf_text[3]?>">&nbsp;<?=$uf_name[3]?>:&nbsp;&nbsp;</td>
	      <td class="<?=$cl33?>" align="left" <?if ($cnt_num==4) echo "colspan=7";?>><?=$r_userfield4?></td>
	    <?}?>
	    <?if ($cnt_num>4) {?>
	      <td class="<?=$cl34?>" align="right"  title="<?=$uf_text[4]?>">&nbsp;<?=$uf_name[4]?>:&nbsp;&nbsp;</td>
	      <td class="<?=$cl34?>" align="left" <?if ($cnt_num==5) echo "colspan=5";?>><?=$r_userfield5?></td>
	    <?}?>
	    <?if ($cnt_num>5) {?>
	      <td class="<?=$cl35?>" align="right"  title="<?=$uf_text[5]?>">&nbsp;<?=$uf_name[5]?>:&nbsp;&nbsp;</td>
	      <td class="<?=$cl35?>" align="left" colspan=3><?=$r_userfield6?></td>
	    <?}?>
	  </tr> 
	</table></td></tr> 
	  <?}?>
	  <?}?>
	<tr><td colspan="8"><table border="0" cellpadding="3" cellspacing="3" width="100%">
	  <tr>
	  <?if ($creation_date) {?>
	    <td class="<?=$cl13?>" align="right" >&nbsp;<?=$lng[15][17]?>:&nbsp;&nbsp;</td>
	    <td class="<?=$cl13?>">&nbsp;<?=($r_creation_date=="00.00.0000 00:00")?"-":$r_creation_date?></td>
	  <?}else {echo '<td>&nbsp;</td><td>&nbsp;</td>';}?>
	  <?if ($last_change) {?>
	    <td class="<?=$cl14?>" align="right" >&nbsp;<?=$lng[15][18]?>:&nbsp;&nbsp;</td>
	    <td class="<?=$cl14?>">&nbsp;<?=($r_change_date=="00.00.0000 00:00")?"-":$r_change_date?></td>
	  <?}else {echo '<td>&nbsp;</td><td>&nbsp;</td>';}?>
	  <?if ($accepted_date) {?>
	    <td class="<?=$cl15?>" align="right" >&nbsp;<?=$lng[15][19]?>:&nbsp;&nbsp;</td>
	    <td class="<?=$cl15?>">&nbsp;<?=($r_accept_date=="00.00.0000 00:00")?"-":$r_accept_date?></td>
	  <?}else {echo '<td>&nbsp;</td><td>&nbsp;</td>';}?>
	  <?if ($accepted_user) {?>
	    <td class="<?=$cl16?>" align="right" >&nbsp;<?=$lng[15][20]?>:&nbsp;&nbsp;</td>
	    <td class="<?=$cl16?>">
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
	  <?}else {echo '<td>&nbsp;</td><td>&nbsp;</td>';}?>
	  </tr>  	  
	</table></td></tr> 
	</table>
     </td>
   </tr>  	
</table>
  <?}?>
<?}?>

<br/>
<?//if tree to be shown
if ($tree==1){?>
<table border="0" width="100%">
  <tr valign="top">
    <td>
      <table border="0" cellpadding="8" cellspacing="8" width="100%">
	  <tr>
	    <td align="center" width="100%"><b><?=$lng[16][17]?></b></td>
	  </tr>
	  <?
	    $r_parent_tmp=1;
	    while ($r_parent_tmp!=0)
	     {
	      if ($tmp_id=="") $tmp_id=$r_id;
	      else $tmp_id=$r_parent_tmp;
	      $query3="select r_id, r_name, r_parent_id from requirements where r_id=".$tmp_id;
  	      $rs3 = mysql_query($query3) or die(mysql_error());
  	      if($row3=mysql_fetch_array($rs3))
  	       {
  	        $r_parent_tmp=$row3['r_parent_id'];
  	        $parent=$row3['r_id'];
  	        $parent_name=htmlspecialchars($row3['r_name']);
  	       }
	     } 
	    ?>
	    <?
	      $query3="select r_id, r_name, r_parent_id from requirements where r_id=".$parent;
  	      $rs3 = mysql_query($query3) or die(mysql_error());
  	      if($row3=mysql_fetch_array($rs3))
  	       {
  	        ?>
	        <tr>
	           <?if ($row3['r_id']==$r_id) {?> 
	           <td>&nbsp;+<?=$row3['r_name']?></td>
                   <?}else {?>
	           <td>&nbsp;+<a href="index.php?inc=view_requirement&r_id=<?=$row3['r_id']?>"><?=$row3['r_name']?></a></td>
                   <?}?>
	        </tr>
	      <?
	       }
	      //displaying tree
  	      if (checkTree($r_id)!=-1) getTree($parent,$r_id);
  	    ?> 
	</table>
    </td> 	 
  </tr>
</table>
<?}?>

<?//if comments to be shown
if ($comments==1){?>
<?if (mysql_num_rows($rs44)>0) {?>
<table border="0"  cellpadding="0" cellspacing="0" width="100%">
  <tr valign="top">
    <td>
      <table border="0" cellpadding="3" cellspacing="3" width="100%">
	  <tr>
	    <td align="center" colspan="4"><b><?=$lng[16][7]?></b></td>
	  </tr>
	  <?
	  while($row44=mysql_fetch_array($rs44)) 
	   {	  
	    ?>
	  <tr>
	    <td align="right" >&nbsp;<?=$lng[16][9]?>:&nbsp;&nbsp;</td>
	    <td>&nbsp;<?=htmlspecialchars($row44['u_name'])?></td>
	    <td align="right" >&nbsp;<?=$lng[16][10]?>:&nbsp;&nbsp;</td>
	    <td>&nbsp;<?=($row44['c_date']!="00.00.0000")?$row44['c_date']:""?></td>
	  </tr>  
	  <tr class="light_blue">
	    <td colspan="4">&nbsp;<?=$row44['c_text']?></td>
	    
	  </tr>  
	  <?}?>
	</table>
    </td> 	 
  </tr>
</table>
  <?}?>
<?}?>

<?//if glossary to be shown
if ($glossary){?>
<?
  $query44="select * from glossary where g_id in (".$glossary_ids."0) order by g_name asc";
  $rs44 = mysql_query($query44) or die(mysql_error());
  if (mysql_num_rows($rs44)>0) 
   {?>
	<table border="0" width="100%">
	  <tr valign="top">
	    <td>
	      <table border="0" cellpadding="3" cellspacing="3" width="100%">
		  <tr>
		    <td align="center" colspan="6" width="100%"><b><?=$lng[24][1]?></b></td>
		  </tr>
		  <?
		  while($row44=mysql_fetch_array($rs44)) 
		   {	  
		    ?>
		  <tr>
		    <td align="right" >&nbsp;<?=$lng[25][3]?>:&nbsp;&nbsp;</td>
		    <td>&nbsp;<?for ($i=0;$i<6-strlen($row44['g_id']);$i++) echo "0";echo $row44['g_id'];?></td>
		    <td align="right" >&nbsp;<?=$lng[25][5]?>:&nbsp;&nbsp;</td>
		    <td>&nbsp;<?=htmlspecialchars($row44['g_term'])?></td>
		    <td align="right" >&nbsp;<?=$lng[25][6]?>:&nbsp;&nbsp;</td>
		    <td>&nbsp;<?=htmlspecialchars($row44['g_abbreviation'])?></td>
		  </tr>  
		  <tr class="light_blue">
		    <td align="right" >&nbsp;<?=$lng[25][12]?>:&nbsp;&nbsp;</td>
		    <td colspan="5">&nbsp;<?=$row44['g_desc']?></td>
		  </tr>  
		  <tr class="light_blue">
		    <td align="right" >&nbsp;</td>
		    <td colspan="5"><br/></td>
		  </tr>  
		  <?}?>
		</table>
	    </td> 	 
	  </tr>
	</table>
     <?}?>	
<?}?>

<?//if test cases to be shown
if ($test_case){?>
<?
  $query44="select * from cases where c_id in (".$cases_ids."0) order by c_name asc";
  $rs44 = mysql_query($query44) or die(mysql_error());
  if (mysql_num_rows($rs44)>0) 
   {?>
	<table border="0" width="100%">
	  <tr valign="top">
	    <td>
	      <table border="0" cellpadding="3" cellspacing="3" width="100%">
		  <tr>
		    <td align="center" colspan="4" width="100%"><b><?=$lng[31][1]?></b></td>
		  </tr>
		  <?
		  while($row44=mysql_fetch_array($rs44)) 
		   {	  
		    ?>
		  <tr>
		    <td align="right" >&nbsp;<?=$lng[31][2]?>:&nbsp;&nbsp;</td>
		    <td>&nbsp;<?=htmlspecialchars($row44['c_name'])?></td>
		    <td align="right" >&nbsp;<?=$lng[31][5]?>:&nbsp;&nbsp;</td>
		    <td>&nbsp;
		    <?
		      if ($row44['c_status']==0) echo $lng[31][6];
		      elseif ($row44['c_status']==1) echo $lng[31][7];
		    ?>  
		    </td>
		  </tr>  
		  <tr class="light_blue">
		    <td align="right" >&nbsp;<?=$lng[31][3]?>:&nbsp;&nbsp;</td>
		    <td colspan="3">&nbsp;<?=$row44['c_desc']?></td>
		  </tr>  
		  <tr class="light_blue">
		    <td align="right" >&nbsp;<?=$lng[31][4]?>:&nbsp;&nbsp;</td>
		    <td colspan="3">&nbsp;<?=$row44['c_result']?></td>
		  </tr>  
		  <tr class="light_blue">
		    <td align="right" >&nbsp;</td>
		    <td colspan="3"><br/></td>
		  </tr>  
		  <?}?>
		</table>
	    </td> 	 
	  </tr>
	</table>
     <?}?>	
<?}?>
</body>
</html>


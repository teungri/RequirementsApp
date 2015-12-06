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
// Page: "add/edit requirement" - editing/adding/deleting requirements

//checking what view type was selected previuosly
if ($viewtypefl=="y") $_SESSION['viewtype']=1;
if ($_SESSION['viewtype']=="" || $_SESSION['viewtype']==0) header("Location:index.php?inc=view_requirement&r_id=".$r_id);

//reverting to old version
if ($r_id_old!="" && $r_id!="")
 {
  //add to history
  
  $query="select * from requirements where r_id=".$r_id;
  $rs = mysql_query($query) or die(mysql_error());
  if($row=mysql_fetch_array($rs)) 
   {
    $query="insert into requirements_history (r_parent_id, r_p_id, r_release, r_c_id, r_s_id, r_stakeholder, r_glossary, r_keyword, r_u_id, r_assigned_u_id, r_name, r_desc, r_state, r_type_r, r_priority, r_valid, r_link, r_satisfaction, r_dissatisfaction, r_conflicts, r_depends, r_component, r_source, r_risk, r_complexity, r_weight, r_points, r_creation_date, r_change_date, r_accept_date, r_accept_user, r_version, r_save_date, r_save_user, r_parent_id2, r_pos, r_stub, r_keywords, r_userfield1, r_userfield2, r_userfield3, r_userfield4, r_userfield5, r_userfield6) values ('".$r_id."','".escapeChars($row['r_p_id'])."','".escapeChars($row['r_release'])."','".escapeChars($row['r_c_id'])."','".escapeChars($row['r_s_id'])."','".escapeChars($row['r_stakeholder'])."','".escapeChars($row['r_glossary'])."','".escapeChars($row['r_keyword'])."','".escapeChars($row['r_u_id'])."','".$row['r_assigned_u_id']."','".escapeChars($row['r_name'])."','".escapeChars($row['r_desc'])."','".escapeChars($row['r_state'])."','".escapeChars($row['r_type_r'])."','".escapeChars($row['r_priority'])."','".escapeChars($row['r_valid'])."','".escapeChars($row['r_link'])."','".escapeChars($row['r_satisfaction'])."','".escapeChars($row['r_dissatisfaction'])."','".escapeChars($row['r_conflicts'])."','".escapeChars($row['r_depends'])."','".escapeChars($row['r_component'])."','".escapeChars($row['r_source'])."','".escapeChars($row['r_risk'])."','".escapeChars($row['r_complexity'])."','".escapeChars($row['r_weight'])."','".escapeChars($row['r_points'])."','".escapeChars($row['r_creation_date'])."',now(),'".escapeChars($row['r_accept_date'])."','".escapeChars($row['r_accept_user'])."','".escapeChars($row['r_version'])."',DATE_ADD( NOW( ) , INTERVAL - ".TIME_DIFF_HOURS." HOUR),'".$_SESSION['uid']."','".escapeChars($row['r_parent_id'])."','".escapeChars($row['r_pos'])."','".escapeChars($row['r_stub'])."','".escapeChars($row['r_keywords'])."','".escapeChars($row['r_userfield1'])."','".escapeChars($row['r_userfield2'])."','".escapeChars($row['r_userfield3'])."','".escapeChars($row['r_userfield4'])."','".escapeChars($row['r_userfield5'])."','".escapeChars($row['r_userfield6'])."')";
    mysql_query($query) or die(mysql_error());
   }   

  //update the record with the old one
  $query="select * from requirements_history where r_id=".$r_id_old;
  $rs = mysql_query($query) or die(mysql_error());
  if($row=mysql_fetch_array($rs)) 
   {
    $query="update requirements set r_name='".escapeChars($row['r_name'])."', r_desc='".escapeChars($row['r_desc'])."', r_p_id='".escapeChars($row['r_p_id'])."', r_release='".escapeChars($row['r_release'])."', r_c_id='".escapeChars($row['r_c_id'])."', r_s_id='".escapeChars($row['r_s_id'])."', r_stakeholder='".escapeChars($row['r_stakeholder'])."', r_glossary='".escapeChars($row['r_glossary'])."',  r_keyword='".escapeChars($row['r_keyword'])."', r_assigned_u_id='".escapeChars($row['r_assigned_u_id'])."',  r_state='".escapeChars($row['r_state'])."', r_type_r='".escapeChars($row['r_type_r'])."',  r_priority='".escapeChars($row['r_priority'])."', r_link='".escapeChars($row['r_link'])."',  r_satisfaction='".escapeChars($row['r_satisfaction'])."', r_dissatisfaction='".escapeChars($row['r_dissatisfaction'])."',  r_conflicts='".escapeChars($row['r_conflicts'])."', r_depends='".escapeChars($row['r_depends'])."',  r_component='".escapeChars($row['r_component'])."', r_source='".escapeChars($row['r_source'])."',  r_risk='".escapeChars($row['r_risk'])."', r_complexity='".escapeChars($row['r_complexity'])."',  r_weight='".escapeChars($row['r_weight'])."', r_points='".escapeChars($row['r_points'])."', r_creation_date='".escapeChars($row['r_creation_date'])."', r_change_date='".escapeChars($row['r_change_date'])."', r_accept_date='".escapeChars($row['r_accept_date'])."', r_accept_user='".escapeChars($row['r_accept_user'])."', r_keywords='".escapeChars($row['r_keywords'])."', r_userfield1='".escapeChars($row['r_userfield1'])."', r_userfield2='".escapeChars($row['r_userfield2'])."', r_userfield3='".escapeChars($row['r_userfield3'])."', r_userfield4='".escapeChars($row['r_userfield4'])."', r_userfield5='".escapeChars($row['r_userfield5'])."', r_userfield6='".escapeChars($row['r_userfield6'])."', r_stub='".escapeChars($row['r_stub'])."', r_version=r_version+1 where r_id=".$r_id; 
    mysql_query($query) or die(mysql_error());
   }   
 }

if ($r_id!="")
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
    else header("Location:index.php?err=yes");
   } 
 }
 
if ($action=="delete" && $c_id!="" && ($_SESSION['rights']=="5" || $_SESSION['rights']=="4")) 
 {
  $flag_as=0;
  if ($_SESSION['rights']=="5") $flag_as=1;
  if ($_SESSION['rights']=="4") 
   {
    $query_project44="select * from projects p left outer join project_users pu on p.p_id=pu.pu_p_id where (pu.pu_u_id=".$_SESSION['uid']." or p_leader=".$_SESSION['uid'].")";
    $rs_project44 = mysql_query($query_project44) or die(mysql_error());
    if($row_project44=mysql_fetch_array($rs_project44)) $flag_as=1;  	          
   }
  if ($flag_as)
   {
    $query="delete from comments where c_id=".$c_id." and c_r_id=".$r_id;
    mysql_query($query,$link) or die('select failed: <b>'.mysql_error().'</b>'."<br>\n");
   } 
 }  

if ($action=="update" && $c_id!="" && ($_SESSION['rights']=="5" || $_SESSION['rights']=="4")) 
 {
  $flag_as=0;
  if ($_SESSION['rights']=="5") $flag_as=1;
  if ($_SESSION['rights']=="4") 
   {
    $query_project44="select * from projects p left outer join project_users pu on p.p_id=pu.pu_p_id where (pu.pu_u_id=".$_SESSION['uid']." or p_leader=".$_SESSION['uid'].")";
    $rs_project44 = mysql_query($query_project44) or die(mysql_error());
    if($row_project44=mysql_fetch_array($rs_project44)) $flag_as=1;  	          
   }
  if ($flag_as)
   {
    $query="update comments set c_question='".$ch."' where c_id=".$c_id;
    mysql_query($query,$link) or die('select failed: <b>'.mysql_error().'</b>'."<br>\n");
   } 
 }  
 
if ($r_id!="") 
 {
  $query="select r.*, date_format(r.r_creation_date, '%d.%m.%Y %H:%i') as d1, date_format(r.r_change_date, '%d.%m.%Y %H:%i') as d2, date_format(r.r_accept_date, '%d.%m.%Y %H:%i') as d3, u.u_name, p.p_name, p.p_id, sp.s_id, sp.s_name from requirements r left outer join users u on r.r_u_id=u.u_id left outer join projects p on r.r_p_id=p.p_id left outer join subprojects sp on r.r_s_id=sp.s_id where r.r_id=".$r_id;
  $rs = mysql_query($query) or die(mysql_error());
  if($row=mysql_fetch_array($rs)) 
   {
    $r_name=htmlspecialchars($row['r_name']);$r_name_tmp=$r_name;    
    $ta=$row['r_desc'];$ta_tmp=$ta;
    $r_release=htmlspecialchars($row['r_release']);$r_release_tmp=$r_release;
    $r_c_id=htmlspecialchars($row['r_c_id']);$r_c_id_tmp=$r_c_id;
    $r_stakeholder=htmlspecialchars($row['r_stakeholder']);$r_stakeholder_tmp=$r_stakeholder;
    $r_glossary=htmlspecialchars($row['r_glossary']);$r_glossary_tmp=$r_glossary;
    $s_id=htmlspecialchars($row['s_id']);$s_id_tmp=$s_id;
    $s_name=htmlspecialchars($row['s_name']);$s_name_tmp=$s_name;
    $r_state=$row['r_state'];$r_state_tmp=$r_state;
    $r_type_r=$row['r_type_r'];$r_type_r_tmp=$r_type_r;
    $p_name=htmlspecialchars($row['p_name']);$p_name_tmp=$p_name;
    $p_id=htmlspecialchars($row['p_id']);
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
    $r_version=$row['r_version'];
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
  fixPos($r_id,$p_id); //fixing requirement position in the tree after moving elements (if needed)

  $query44="select c.*, u.u_name, date_format(c.c_date, '%d.%m.%Y %H:%i') as d1 from comments c left outer join users u on c.c_u_id=u.u_id where c.c_r_id=".$r_id." order by c_date desc";
  $rs44 = mysql_query($query44) or die(mysql_error());
 } 
 
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
 
 <table border="0" width="100%">
  <tr valign="top">
    <td>
      <table border="0" cellpadding="2" cellspacing="2" class="content" width="100%">
	  <tr class="gray">
	    <td align="center" colspan=8><b><?=$lng[17][7]?></b></td>
	  </tr>
	  <tr class="gray" valign="top">
	    <td align="right" nowrap width="15%" title="<?=$lng[9][16]?>">&nbsp;<?=$lng[17][22]?>&nbsp;:&nbsp;</td>
	    <td><?=$p_name?></td>
	    <td align="right" nowrap title="<?=$lng[15][100]?>">&nbsp;<?=$lng[15][99]?>&nbsp;:&nbsp;</td>
	    <td>
	    <?
	    $query22="select * from subprojects where s_p_id='".$p_id."' order by s_name asc";
	    $rs22 = mysql_query($query22) or die(mysql_error());
	    while($row22=mysql_fetch_array($rs22))
	     {
	      echo "<a href='index.php?inc=view_subproject&s_id=".$s_id."'>".htmlspecialchars($row22['s_name'])."</a>";
	      echo "<br>";
	     }
	    ?>
	    </td>
	    <td align="right" nowrap title="<?=$lng[9][22]?>">&nbsp;<?=$lng[15][24]?>&nbsp;:&nbsp;</td>
	    <td colspan="3">
	    <?
	    $query2="select r.*, date_format(r.r_date, '%d.%m.%Y') as d1, date_format(r.r_released_date, '%d.%m.%Y') as d2 from project_releases pr left outer join releases r on pr.pr_r_id=r.r_id where pr.pr_p_id='".$p_id."' order by r.r_name asc";
	    $rs2 = mysql_query($query2) or die(mysql_error());
	    while($row2=mysql_fetch_array($rs2))
	     {
	      echo "<a href='index.php?inc=view_release&r_id=".$row2['r_id']."'>".htmlspecialchars($row2['r_name'])."</a> (".$row2['d1'].")";
	      if ($row2['d2']!="00.00.0000") echo " - ".$row2['d2'];	      
	      echo "<br>";
	     }
	    ?>
	    </td>
	  </tr>
	  <tr class="gray" valign="top">	    
	    <td align="right" nowrap title="<?=$lng[15][104]?>">&nbsp;<?=$lng[15][103]?>&nbsp;:&nbsp;</td>
	    <td>
	    <?
	    $query26="select c.* from project_cases pc left outer join cases c on pc.pc_c_id=c.c_id where pc.pc_p_id='".$p_id."' order by c.c_name asc";
	    $rs26 = mysql_query($query26) or die(mysql_error());
	    while($row26=mysql_fetch_array($rs26))
	     {
	      echo "<a href='index.php?inc=view_case&c_id=".$row26['c_id']."'>".htmlspecialchars($row26['c_name'])."</a>";
	      echo "<br>";
	     }
	    ?>	      
	    </td>
	    <td align="right" nowrap title="<?=$lng[15][77]?>">&nbsp;<?=$lng[15][76]?>&nbsp;:&nbsp;</td>
	    <td>
	    <?
	    $query26="select s.* from project_stakeholders ps left outer join stakeholders s on ps.ps_s_id=s.s_id where ps.ps_p_id='".$p_id."' order by s.s_name asc";
	    $rs26 = mysql_query($query26) or die(mysql_error());
	    while($row26=mysql_fetch_array($rs26))
	     {
	      echo "<a href='index.php?inc=view_stakeholder&s_id=".$row26['s_id']."'>".htmlspecialchars($row26['s_name'])."</a>";
	      echo "<br>";
	     }
	    ?>	      
	    </td>
	    <td align="right" nowrap title="<?=$lng[15][87]?>">&nbsp;<?=$lng[15][86]?>&nbsp;:&nbsp;</td>
	    <td colspan=3>
	    <?
	    $query26="select g.* from project_glossary pg left outer join glossary g on pg.pg_g_id=g.g_id where pg.pg_p_id='".$p_id."' order by g.g_id asc";
	    $rs26 = mysql_query($query26) or die(mysql_error());
	    while($row26=mysql_fetch_array($rs26))
	     {
	      $tmp_g="";
	      for ($i=0;$i<6-strlen($row26['g_id']);$i++) $tmp_g.="0";$tmp_g.=$row26['g_id'];
	      echo "<a href='index.php?inc=view_glossary&g_id=".$row26['g_id']."'>".$tmp_g."</a>";
	      echo "<br>";
	     }
	    ?>	      
	    </td> 
	  </tr>
	  <tr class="gray" valign="top">
	    <td align="right" nowrap title="<?=$lng[15][116]?>">&nbsp;<?=$lng[15][115]?>&nbsp;:&nbsp;</td>
	    <td>
	    <?
	    $query26="select c.* from project_components pco left outer join components c on pco.pco_c_id=c.c_id where pco.pco_p_id='".$p_id."' order by c.c_name asc";
	    $rs26 = mysql_query($query26) or die(mysql_error());
	    while($row26=mysql_fetch_array($rs26))
	     {
	      echo "<a href='index.php?inc=view_component&c_id=".$row26['c_id']."'>".htmlspecialchars($row26['c_name'])."</a>";
	      echo "<br>";
	     }
	    ?>	      
	    </td>
	    <td align="right" nowrap title="<?=$lng[9][23]?>">&nbsp;<?=$lng[15][16]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;<?=$author?></td>
	    <td align="right" nowrap title="<?=$lng[9][19]?>">&nbsp;<?=$lng[17][19]?>&nbsp;:&nbsp;</td>
	    <td colspan="3">&nbsp;<?=$p_leader?></td>
	  </tr>
	  <tr class="gray" valign="top">
	    <td align="right" nowrap width="15%" title="<?=$lng[9][17]?>">&nbsp;<?=$lng[17][23]?>&nbsp;:&nbsp;</td>
	    <td><?if ($p_phase==0) echo $lng[9][8];?><?if ($p_phase==1) echo $lng[9][9];?><?if ($p_phase==2) echo $lng[9][10];?><?if ($p_phase==3) echo $lng[9][32];?><?if ($p_phase==4) echo $lng[9][33];?></td>
	    <td align="right" nowrap width="15%" title="<?=$lng[9][18]?>">&nbsp;<?=$lng[17][24]?>&nbsp;:&nbsp;</td>
	    <td><?if ($p_status==0) echo $lng[9][11];?><?if ($p_status==1) echo $lng[9][12];?><?if ($p_status==2) echo "<span class='error'>".$lng[9][14]."</span>";?></td>
	    <td align="right" nowrap width="15%" title="<?=$lng[9][20]?>">&nbsp;<?=$lng[17][20]?>&nbsp;:&nbsp;</td>
	    <td colspan=3>&nbsp;<?=$p_date?></td>	    
	  </tr>
	  <tr class="gray" valign="top">
	    <td align="right" nowrap width="15%" title="<?=$lng[9][21]?>">&nbsp;<?=$lng[17][21]?>&nbsp;:&nbsp;</td>
	    <td colspan=7>&nbsp;<?=$p_desc?></td>	    
	  </tr>
	  
	  
	</table>
      </td> 
   </tr> 
</table>

<table border="0" width="100%">
  <tr valign="top">
    <td>
      <table border="0" cellpadding="2" cellspacing="2" class="content" width="100%">
	  <tr class="gray">
	    <td align="left" colspan="8"><input type="button" value="<?=$lng[16][5]?>" onclick="document.location.href='index.php?inc=view_requirement&viewtypefl=y&r_id=<?=$r_id?>'">&nbsp;<?if ($_SESSION['uid']!="" && $_SESSION['rights']!=0){?><input type="button" value=" <?=$lng[16][2]?> " onclick="document.location.href='index.php?inc=edit_requirement&ref=long&r_id=<?=$r_id?>'"><?}?>&nbsp;<input type="button" value="<?=$lng[16][15]?>" onclick="pdf2('<?=$r_id?>','<?=$project_list?>','landscape');">&nbsp;<input type="button" value="<?=$lng[16][21]?>" onclick="pdf2('<?=$r_id?>','<?=$project_list?>','portrait');"><img src="img/x.gif" width="200" height="1"><b><?=$lng[16][1]?></b></td>
	  </tr>
	  <tr class="blue" valign="top">
	    <td align="right" nowrap title="<?=$lng[15][53]?>">&nbsp;<?=$lng[15][4]?>&nbsp;:&nbsp;</td>
	    <td colspan="7"><b>&nbsp;<?=$r_name?></b></td>
	  </tr>
	  <tr class="blue" valign="top">
	    <td align="right" nowrap title="<?=$lng[15][57]?>">&nbsp;<?=$lng[15][5]?>&nbsp;:&nbsp;</td>
	    <td align="left" colspan=7><?=$ta?> 
	    </td>
	  </tr> 
	  <tr class="blue" valign="top">
	    <td align="right" nowrap width="15%" title="<?=$lng[15][51]?>">&nbsp;<?=$lng[15][3]?>&nbsp;:&nbsp;</td>
	    <td><?=$p_name?></td>
	    <td align="right" nowrap title="<?=$lng[15][98]?>">&nbsp;<?=$lng[15][97]?>&nbsp;:&nbsp;</td>
	    <td><a href="index.php?inc=view_subproject&s_id=<?=$s_id?>"><?=$s_name?></a></td>
	    <td align="right" nowrap title="<?=$lng[15][52]?>">&nbsp;<?=$lng[15][24]?>&nbsp;:&nbsp;</td>
	    <td colspan="3">
	    <?
	    $query22="select r.*, date_format(r.r_date, '%d.%m.%Y') as d1, date_format(r.r_released_date, '%d.%m.%Y') as d2 from releases r where r.r_id in (".$r_release."0) order by r.r_name asc";
	    //$query22="select r.*, date_format(r.r_date, '%d.%m.%Y') as d1, date_format(r.r_released_date, '%d.%m.%Y') as d2 from project_releases pr left outer join releases r on pr.pr_r_id=r.r_id where pr.pr_p_id='".$p_id."' order by r.r_name asc";
	    $rs22 = mysql_query($query22) or die(mysql_error());
	    while($row22=mysql_fetch_array($rs22))
	     {
	      echo "<a href='index.php?inc=view_release&r_id=".$row22['r_id']."'>".htmlspecialchars($row22['r_name'])."</a> (".$row22['d1'].")";
	      if ($row22['d2']!="00.00.0000") echo " - ".$row22['d2'];	      
	      echo "<br>";
	     }
	    ?>
	    </td>
	  </tr>
	  <tr class="blue" valign="top">
	    <td align="right" nowrap title="<?=$lng[15][104]?>">&nbsp;<?=$lng[15][103]?>&nbsp;:&nbsp;</td>
	    <td>
	    <?
	    $query26="select * from cases where c_id in (".$r_c_id."0) order by c_name asc";
	    $rs26 = mysql_query($query26) or die(mysql_error());
	    while($row26=mysql_fetch_array($rs26))
	     {
	      echo "<a href='index.php?inc=view_case&c_id=".$row26['c_id']."'>".htmlspecialchars($row26['c_name'])."</a>";
	      echo "<br>";
	     }
	    ?>	      
	    </td> 
	    <td align="right" nowrap title="<?=$lng[15][77]?>">&nbsp;<?=$lng[15][76]?>&nbsp;:&nbsp;</td>
	    <td>
	    <?
	    $query26="select * from stakeholders where s_id in (".$r_stakeholder."0) order by s_name asc";
	    $rs26 = mysql_query($query26) or die(mysql_error());
	    while($row26=mysql_fetch_array($rs26))
	     {
	      echo "<a href='index.php?inc=view_stakeholder&s_id=".$row26['s_id']."'>".htmlspecialchars($row26['s_name'])."</a>";
	      echo "<br>";
	     }
	    ?>	      
	    </td>
	    <td align="right" nowrap title="<?=$lng[15][87]?>">&nbsp;<?=$lng[15][86]?>&nbsp;:&nbsp;</td>
	    <td colspan="3">
	    <?
	    $query26="select g.* from glossary g where g.g_id in (".$r_glossary."0) order by g.g_id asc";
	    //$query26="select g.* from project_glossary pg left outer join glossary g on pg.pg_g_id=g.g_id where pg.pg_p_id='".$p_id."' order by g.g_id asc";
	    $rs26 = mysql_query($query26) or die(mysql_error());
	    while($row26=mysql_fetch_array($rs26))
	     {
	      $tmp_g="";
	      for ($i=0;$i<6-strlen($row26['g_id']);$i++) $tmp_g.="0";$tmp_g.=$row26['g_id'];
	      echo "<a href='index.php?inc=view_glossary&g_id=".$row26['g_id']."'>".$tmp_g."</a>";
	      echo "<br>";
	     }
	    ?>	      
	    </td> 

	  </tr>
	  <tr class="blue" valign="top">
	    <td align="right" nowrap title="<?=$lng[15][58]?>">&nbsp;<?=$lng[15][10]?>&nbsp;:&nbsp;</td>
	    <td>
	      <?
	      include("ini/txts/".$_SESSION['chlang']."/state.php");
	      echo $state_array[$r_state];
	      ?>
	    </td>
	    <td align="right" nowrap title="<?=$lng[15][59]?>">&nbsp;<?=$lng[15][11]?>&nbsp;:&nbsp;</td>
	    <td colspan="5">
	      <?
	      include("ini/txts/".$_SESSION['chlang']."/type.php");
	      echo $type_array[$r_type_r];
	      ?>
	    </td>
	  </tr>
	  <tr class="blue" valign="top">
	    <td align="right" nowrap title="<?=$lng[15][60]?>">&nbsp;<?=$lng[15][13]?>&nbsp;:&nbsp;</td>
	    <td><?=$r_priority?></td>
	    <!--td align="right" nowrap>&nbsp;<?=$lng[15][15]?>&nbsp;:&nbsp;</td>
	    <td>
	      <?
	        if ($r_valid==0) echo $lng[15][31];
	        elseif ($r_valid==1) echo $lng[15][32];
	        elseif ($r_valid==2) echo $lng[15][33];
	      ?>	       	    
	    </td-->
	    <td align="right" nowrap title="<?=$lng[15][56]?>">&nbsp;<?=$lng[15][30]?>&nbsp;:&nbsp;</td>
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
	    <td align="right" nowrap title="<?=$lng[15][67]?>">&nbsp;<?=$lng[15][45]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;<?=$r_id?></td>
	    <td align="right" nowrap title="<?=$lng[15][69]?>">&nbsp;<?=$lng[15][34]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;<?=$r_version?></td>	    
	  </tr>  
	  <tr class="blue" valign="top">
	    <td align="right" nowrap title="<?=$lng[15][62]?>">&nbsp;<?=$lng[15][40]?>&nbsp;:&nbsp;</td>
	    <td>
	    <?
	    $query26="select * from components where c_id in (".$r_component."0) order by c_name asc";
	    $rs26 = mysql_query($query26) or die(mysql_error());
	    while($row26=mysql_fetch_array($rs26))
	     {
	      echo "<a href='index.php?inc=view_component&c_id=".$row26['c_id']."'>".htmlspecialchars($row26['c_name'])."</a>; ";
	     }
	    ?>
	    </td>
	    <td align="right" nowrap title="<?=$lng[15][63]?>">&nbsp;<?=$lng[15][41]?>&nbsp;:&nbsp;</td>
	    <td><?=$r_source?></td>
	    <td align="right" nowrap title="<?=$lng[15][64]?>">&nbsp;<?=$lng[15][42]?>&nbsp;:&nbsp;</td>
	    <td>
	      <?
	      include("ini/txts/".$_SESSION['chlang']."/risk.php");
	      echo $risk_array[$r_risk];
	      ?>
	    </td>
	    <td align="right" nowrap title="<?=$lng[15][65]?>">&nbsp;<?=$lng[15][43]?>&nbsp;:&nbsp;</td>
	    <td>
	      <?
	      include("ini/txts/".$_SESSION['chlang']."/complexity.php");
	      echo $complexity_array[$r_complexity];
	      ?>
	    </td>	    
	  </tr>  	  
	  <tr class="blue" valign="top">
	    <td align="right" nowrap title="<?=$lng[15][89]?>">&nbsp;<?=$lng[15][88]?>&nbsp;:&nbsp;</td>
	    <td align="left">&nbsp;<?=$r_weight?></td>
	    <td align="right" nowrap title="<?=$lng[15][66]?>">&nbsp;<?=$lng[15][44]?>&nbsp;:&nbsp;</td>
	    <td align="left">&nbsp;<?=$r_points?></td>
	    <td align="right" nowrap title="<?=$lng[15][108]?>">&nbsp;<?=$lng[15][107]?>&nbsp;:&nbsp;</td>
	    <td align="left" colspan="3">
	    <?
	      $query456="select k.* from keywords k where k.k_id in (".$r_keywords."0) order by k.k_name asc";
	      //$query456="select k_id, k_name from keywords where k_id in (".$r_keywords."0)";
              $rs456 = mysql_query($query456) or die(mysql_error());	        
	      while($row456=mysql_fetch_array($rs456)) 
	       {
	        ?>
	        <?=htmlspecialchars($row456['k_name'])?>;
	      <?}?>
	    </td>
	  </tr> 
	  <tr class="blue" valign="top">
	    <td align="right" nowrap title="<?=$lng[15][83]?>">&nbsp;<?=$lng[15][82]?>&nbsp;:&nbsp;</td>
	    <td align="left">&nbsp;<?=$r_satisfaction?></td>
	    <td align="right" nowrap title="<?=$lng[15][85]?>">&nbsp;<?=$lng[15][84]?>&nbsp;:&nbsp;</td>
	    <td align="left">&nbsp;<?=$r_dissatisfaction?></td>
	    <td align="right" nowrap title="<?=$lng[15][79]?>">&nbsp;<?=$lng[15][78]?>&nbsp;:&nbsp;</td>
	    <td align="left">
	      <?
	      $query456="select r_id, r_name from requirements where r_id in (".$r_depends."0)";
              $rs456 = mysql_query($query456) or die(mysql_error());	        
	      while($row456=mysql_fetch_array($rs456)) 
	       {
	        ?>
	        &nbsp;<a href="index.php?inc=view_requirement&r_id=<?=$row456['r_id']?>"><?=htmlspecialchars($row456['r_name'])?></a><br>
	      <?}?>
	    </td>
	    <td align="right" nowrap title="<?=$lng[15][81]?>">&nbsp;<?=$lng[15][80]?>&nbsp;:&nbsp;</td>
	    <td align="left">
	      <?
	      $query456="select r_id, r_name from requirements where r_id in (".$r_conflicts."0)";
              $rs456 = mysql_query($query456) or die(mysql_error());	        
	      while($row456=mysql_fetch_array($rs456)) 
	       {
	        ?>
	        &nbsp;<a href="index.php?inc=view_requirement&r_id=<?=$row456['r_id']?>"><?=htmlspecialchars($row456['r_name'])?></a><br>
	      <?}?>
	    </td>
	  </tr> 
	  <tr class="blue" valign="top">
	    <td align="right" nowrap title="<?=$lng[15][68]?>">&nbsp;<?=$lng[15][16]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;<?=$author?></td>
	    <td align="right" nowrap title="<?=$lng[15][61]?>">&nbsp;<?=$lng[15][14]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;<a href="<?=$r_link?>" target="_blank">
	    <?
	     for ($i=0;$i<strlen($r_link);$i+=40) 
	      echo substr($r_link,$i,40)." ";
	    ?>
	    </a></td>
	    <td align="right" nowrap title="<?=$lng[15][54]?>">&nbsp;<?=$lng[15][38]?>&nbsp;:&nbsp;</td>
	    <td>
	      <?
	      $query456="select r_name from requirements where r_id=".$r_parent_id;
              $rs456 = mysql_query($query456) or die(mysql_error());	        
	      if($row456=mysql_fetch_array($rs456)) 
	       {
	        ?>
	        &nbsp;<a href="index.php?inc=view_requirement&r_id=<?=$r_parent_id?>"><?=htmlspecialchars($row456['r_name'])?></a>
	      <?}?>
	    </td>	    
	    <td align="right" nowrap title="<?=$lng[15][55]?>">&nbsp;<?=$lng[15][39]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;<?=$r_pos?></a></td>	    
	  </tr>  
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
	  <tr class="blue" valign="top">
	    <?if ($cnt_num>0) {?>
	      <td align="right" nowrap title="<?=$uf_text[0]?>">&nbsp;<?=$uf_name[0]?>&nbsp;:&nbsp;</td>
	      <td align="left" <?if ($cnt_num==1) echo "colspan=7";?>><?=$r_userfield1?></td>
	    <?}?>
	    <?if ($cnt_num>1) {?>
	      <td align="right" nowrap title="<?=$uf_text[1]?>">&nbsp;<?=$uf_name[1]?>&nbsp;:&nbsp;</td>
	      <td align="left" <?if ($cnt_num==2) echo "colspan=5";?>><?=$r_userfield2?></td>
	    <?}?>
	    <?if ($cnt_num>2) {?>
	      <td align="right" nowrap title="<?=$uf_text[2]?>">&nbsp;<?=$uf_name[2]?>&nbsp;:&nbsp;</td>
	      <td align="left" colspan=3><?=$r_userfield3?></td>
	    <?}?>
	  </tr> 
	  <?
	   }
	  if ($cnt_num>3) 
	   {
	    ?>
	  <tr class="blue" valign="top">
	    <?if ($cnt_num>3) {?>
	      <td align="right" nowrap title="<?=$uf_text[3]?>">&nbsp;<?=$uf_name[3]?>&nbsp;:&nbsp;</td>
	      <td align="left" <?if ($cnt_num==4) echo "colspan=7";?>><?=$r_userfield4?></td>
	    <?}?>
	    <?if ($cnt_num>4) {?>
	      <td align="right" nowrap title="<?=$uf_text[4]?>">&nbsp;<?=$uf_name[4]?>&nbsp;:&nbsp;</td>
	      <td align="left" <?if ($cnt_num==5) echo "colspan=5";?>><?=$r_userfield5?></td>
	    <?}?>
	    <?if ($cnt_num>5) {?>
	      <td align="right" nowrap title="<?=$uf_text[5]?>">&nbsp;<?=$uf_name[5]?>&nbsp;:&nbsp;</td>
	      <td align="left" colspan=3><?=$r_userfield6?></td>
	    <?}?>
	  </tr> 
	  <?}?>
	   

	  <tr class="blue" valign="top">
	    <td align="right" nowrap title="<?=$lng[15][70]?>">&nbsp;<?=$lng[15][17]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;<?=($r_creation_date=="00.00.0000 00:00")?"-":$r_creation_date?></td>
	    <td align="right" nowrap title="<?=$lng[15][71]?>">&nbsp;<?=$lng[15][18]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;<?=($r_change_date=="00.00.0000 00:00")?"-":$r_change_date?></td>
	    <td align="right" nowrap title="<?=$lng[15][72]?>">&nbsp;<?=$lng[15][19]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;<?=($r_accept_date=="00.00.0000 00:00")?"-":$r_accept_date?></td>
	    <td align="right" nowrap title="<?=$lng[15][73]?>">&nbsp;<?=$lng[15][20]?>&nbsp;:&nbsp;</td>
	    <td>
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
	  </tr>  	  
	</table>
    </td> 	 
  </tr>
</table>
<div align="left"><a href="#" onclick="if (document.getElementById('tr1').style.display=='')  document.getElementById('tr1').style.display='none';else document.getElementById('tr1').style.display='';">&nbsp;-<?=$lng[16][14]?></a></div>
<table border="0" width="100%">
<tbody id="tr1" style="display:">
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
      $r_id_old=htmlspecialchars($row['r_id']);
      $p_name=htmlspecialchars($row['p_name']);
      if ($p_name==$p_name_tmp) $cl1="blue";else $cl1="red";$p_name_tmp=$p_name;
      $s_id=htmlspecialchars($row['s_id']);
      if ($s_id==$s_id_tmp) $cl36="blue";else $cl36="red";$s_id_tmp=$s_id;
      $r_release=htmlspecialchars($row['r_release']);
      if ($r_release==$r_release_tmp) $cl40="blue";else $cl40="red";$r_release_tmp=$r_release;
      $r_glossary=htmlspecialchars($row['r_glossary']);
      if ($r_glossary==$r_glossary_tmp) $cl41="blue";else $cl41="red";$r_glossary_tmp=$r_glossary;
      $r_c_id=htmlspecialchars($row['r_c_id']);
      if ($r_c_id==$r_c_id_tmp) $cl37="blue";else $cl37="red";$r_c_id_tmp=$r_c_id;
      $r_stakeholder=htmlspecialchars($row['r_stakeholder']);
      if ($r_stakeholder==$r_stakeholder_tmp) $cl39="blue";else $cl39="red";$r_stakeholder_tmp=$r_stakeholder;
      $s_name=htmlspecialchars($row['s_name']);
      $p_id=htmlspecialchars($row['p_id']);
      $r_name=htmlspecialchars($row['r_name']);
      if ($r_name==$r_name_tmp) $cl3="blue";else $cl3="red";$r_name_tmp=$r_name;
      $r_state=$row['r_state'];
      if ($r_state==$r_state_tmp) $cl4="blue";else $cl4="red";$r_state_tmp=$r_state;
      $r_type_r=$row['r_type_r'];
      if ($r_type_r==$r_type_r_tmp) $cl5="blue";else $cl5="red";$r_type_r_tmp=$r_type_r;
      $r_priority=$row['r_priority'];
      if ($r_priority==$r_priority_tmp) $cl6="blue";else $cl6="red";$r_priority_tmp=$r_priority;
      $r_assigned_u_id=$row['r_assigned_u_id'];
      if ($r_assigned_u_id==$r_assigned_u_id_tmp) $cl8="blue";else $cl8="red";$r_assigned_u_id_tmp=$r_assigned_u_id;
      $author=htmlspecialchars($row['u_name']);
      if ($author==$author_tmp) $cl10="blue";else $cl10="red";$author_tmp=$author;
      $r_link=htmlspecialchars($row['r_link']);
      if ($r_link==$r_link_tmp) $cl11="blue";else $cl11="red";$r_link_tmp=$r_link;
      $r_satisfaction=htmlspecialchars($row['r_satisfaction']);
      if ($r_satisfaction==$r_satisfaction_tmp) $cl26="blue";else $cl26="red";$r_satisfaction_tmp=$r_satisfaction;
      $r_dissatisfaction=htmlspecialchars($row['r_dissatisfaction']);
      if ($r_dissatisfaction==$r_dissatisfaction_tmp) $cl27="blue";else $cl27="red";$r_dissatisfaction_tmp=$r_dissatisfaction;
      $r_conflicts=htmlspecialchars($row['r_conflicts']);
      if ($r_conflicts==$r_conflicts_tmp) $cl28="blue";else $cl28="red";$r_conflicts_tmp=$r_conflicts;
      $r_depends=htmlspecialchars($row['r_depends']);
      if ($r_depends==$r_depends_tmp) $cl25="blue";else $cl25="red";$r_depends_tmp=$r_depends;
      $r_component=htmlspecialchars($row['r_component']);
      if ($r_component==$r_component_tmp) $cl20="blue";else $cl20="red";$r_component_tmp=$r_component;
      $r_source=htmlspecialchars($row['r_source']);
      if ($r_source==$r_source_tmp) $cl21="blue";else $cl21="red";$r_source_tmp=$r_source;
      $r_risk=htmlspecialchars($row['r_risk']);
      if ($r_risk==$r_risk_tmp) $cl22="blue";else $cl22="red";$r_risk_tmp=$r_risk;
      $r_complexity=htmlspecialchars($row['r_complexity']);
      if ($r_complexity==$r_complexity_tmp) $cl23="blue";else $cl23="red";$r_complexity_tmp=$r_complexity;
      $r_weight=htmlspecialchars($row['r_weight']);
      if ($r_weight==$r_weight_tmp) $cl29="blue";else $cl29="red";$r_weight_tmp=$r_weight;
      $r_points=htmlspecialchars($row['r_points']);
      if ($r_points==$r_points_tmp) $cl24="blue";else $cl24="red";$r_points_tmp=$r_points;
      $ta=$row['r_desc'];
      if ($ta==$ta_tmp) $cl12="blue";else $cl12="red";$ta_tmp=$ta;
      $r_creation_date=$row['d1'];
      if ($r_creation_date==$r_creation_date_tmp) $cl13="blue";else $cl13="red";$r_creation_date_tmp=$r_creation_date;
      $r_change_date=$row['d2'];
      if ($r_change_date==$r_change_date_tmp) $cl14="blue";else $cl14="blue";$r_change_date_tmp=$r_change_date;
      $r_accept_date=$row['d3'];
      if ($r_accept_date==$r_accept_date_tmp) $cl15="blue";else $cl15="red";$r_accept_date_tmp=$r_accept_date;
      $r_accept_user=$row['r_accept_user'];  
      if ($r_accept_user==$r_accept_user_tmp) $cl16="blue";else $cl16="red";$r_accept_user_tmp=$r_accept_user;
      $r_save_date=$row['d6'];
      $r_save_user=$row['r_save_user'];
      $r_version=$row['r_version'];  
      $r_parent_id=$row['r_parent_id2'];  
      if ($r_parent_id==$r_parent_id_tmp) $cl17="blue";else $cl17="red";$r_parent_id_tmp=$r_parent_id;
      $r_pos=$row['r_pos'];  
      if ($r_pos==$r_pos_tmp) $cl18="blue";else $cl18="red";$r_pos_tmp=$r_pos;
      $r_keywords=$row['r_keywords'];  
      if ($r_keywords==$r_keywords_tmp) $cl38="blue";else $cl38="red";$r_keywords_tmp=$r_keywords;
      $r_userfield1=htmlspecialchars($row['r_userfield1']);  
      if ($r_userfield1==$r_userfield1_tmp) $cl30="blue";else $cl30="red";$r_userfield1_tmp=$r_userfield1;
      $r_userfield2=htmlspecialchars($row['r_userfield2']);  
      if ($r_userfield2==$r_userfield2_tmp) $cl31="blue";else $cl31="red";$r_userfield2_tmp=$r_userfield2;
      $r_userfield3=htmlspecialchars($row['r_userfield3']);  
      if ($r_userfield3==$r_userfield3_tmp) $cl32="blue";else $cl32="red";$r_userfield3_tmp=$r_userfield3;
      $r_userfield4=htmlspecialchars($row['r_userfield4']);  
      if ($r_userfield4==$r_userfield4_tmp) $cl33="blue";else $cl33="red";$r_userfield4_tmp=$r_userfield4;
      $r_userfield5=htmlspecialchars($row['r_userfield5']);  
      if ($r_userfield5==$r_userfield5_tmp) $cl34="blue";else $cl34="red";$r_userfield5_tmp=$r_userfield5;
      $r_userfield6=htmlspecialchars($row['r_userfield6']);  
      if ($r_userfield6==$r_userfield6_tmp) $cl35="blue";else $cl35="red";$r_userfield6_tmp=$r_userfield6;
     }
   }   
 ?>
  <tr valign="top">
    <td>
      <table border="0" cellpadding="2" cellspacing="2" class="content" width="100%">
	  <tr class="gray">
	    <td colspan=2>&nbsp;<?if ($p_status!=2){?><input type="button" value="<?=$lng[16][18]?>" onclick="document.location.href='index.php?inc=view_requirement_long&viewtypefl=<?=$viewtypefl?>&r_id=<?=$r_id?>&r_id_old=<?=$r_id_old?>'"><?}?>	
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
	  <tr class="blue" valign="top">
	    <td class="<?=$cl3?>" align="right" nowrap title="<?=$lng[15][53]?>">&nbsp;<?=$lng[15][4]?>&nbsp;:&nbsp;</td>
	    <td class="<?=$cl3?>" colspan=7><b><?=$r_name?></b></td>
	  </tr>	  
	  <tr valign="top">
	    <td class="<?=$cl12?>" align="right" nowrap title="<?=$lng[15][57]?>">&nbsp;<?=$lng[15][5]?>&nbsp;:&nbsp;</td>
	    <td class="<?=$cl12?>" align="left" colspan=7><?=$ta?> 
	  </tr> 
	  <tr valign="top">
	    <td class="<?=$cl1?>" align="right" nowrap width="15%" title="<?=$lng[15][51]?>">&nbsp;<?=$lng[15][3]?>&nbsp;:&nbsp;</td>
	    <td class="<?=$cl1?>"><?=$p_name?></td>
	    <td class="<?=$cl36?>" nowrap title="<?=$lng[15][98]?>">&nbsp;<?=$lng[15][97]?>&nbsp;:&nbsp;</td>
	    <td class="<?=$cl36?>"><a href="index.php?inc=view_subproject&s_id=<?=$s_id?>"><?=$s_name?></a></td>
	    <td class="<?=$cl40?>" align="right" nowrap title="<?=$lng[15][52]?>">&nbsp;<?=$lng[15][24]?>&nbsp;:&nbsp;</td>
	    <td class="<?=$cl40?>" colspan="3">
	    <?
	    $query22="select r.*, date_format(r.r_date, '%d.%m.%Y') as d1, date_format(r.r_released_date, '%d.%m.%Y') as d2 from releases r where r.r_id in (".$r_release."0) order by r.r_name asc";
	    //$query22="select r.*, date_format(r.r_date, '%d.%m.%Y') as d1, date_format(r.r_released_date, '%d.%m.%Y') as d2 from project_releases pr left outer join releases r on pr.pr_r_id=r.r_id where pr.pr_p_id='".$p_id."' order by r.r_name asc";
	    $rs22 = mysql_query($query22) or die(mysql_error());
	    while($row22=mysql_fetch_array($rs22))
	     {
	      echo "<a href='index.php?inc=view_release&r_id=".$row22['r_id']."'>".htmlspecialchars($row22['r_name'])."</a> (".$row22['d1'].")";
	      if ($row22['d2']!="00.00.0000") echo " - ".$row22['d2'];	      
	      echo "<br>";
	     }
	    ?>
	    </td>

	  </tr>
	  <tr valign="top">
	    <td class="<?=$cl37?>" align="right" nowrap title="<?=$lng[15][104]?>">&nbsp;<?=$lng[15][103]?>&nbsp;:&nbsp;</td>
	    <td class="<?=$cl37?>">
	    <?
	    $query26="select * from cases where c_id in (".$r_c_id."0) order by c_name asc";
	    $rs26 = mysql_query($query26) or die(mysql_error());
	    while($row26=mysql_fetch_array($rs26))
	     {
	      echo "<a href='index.php?inc=view_case&c_id=".$row26['c_id']."'>".htmlspecialchars($row26['c_name'])."</a>";
	      echo "<br>";
	     }
	    ?>	      
	    </td> 
	    <td class="<?=$cl39?>" align="right" nowrap title="<?=$lng[15][77]?>">&nbsp;<?=$lng[15][76]?>&nbsp;:&nbsp;</td>
	    <td class="<?=$cl39?>">
	    <?
	    $query26="select * from stakeholders where s_id in (".$r_stakeholder."0) order by s_name asc";
	    $rs26 = mysql_query($query26) or die(mysql_error());
	    while($row26=mysql_fetch_array($rs26))
	     {
	      echo "<a href='index.php?inc=view_stakeholder&s_id=".$row26['s_id']."'>".htmlspecialchars($row26['s_name'])."</a>";
	      echo "<br>";
	     }
	    ?>	      
	    </td> 
	    <td class="<?=$cl41?>" align="right" nowrap title="<?=$lng[15][87]?>">&nbsp;<?=$lng[15][86]?>&nbsp;:&nbsp;</td>
	    <td class="<?=$cl41?>" colspan="3">
	    <?
	    $query26="select g.* from glossary g where g.g_id in (".$r_glossary."0) order by g.g_id asc";
	    //$query26="select g.* from project_glossary pg left outer join glossary g on pg.pg_g_id=g.g_id where pg.pg_p_id='".$p_id."' order by g.g_id asc";
	    $rs26 = mysql_query($query26) or die(mysql_error());
	    while($row26=mysql_fetch_array($rs26))
	     {
	      $tmp_g="";
	      for ($i=0;$i<6-strlen($row26['g_id']);$i++) $tmp_g.="0";$tmp_g.=$row26['g_id'];
	      echo "<a href='index.php?inc=view_glossary&g_id=".$row26['g_id']."'>".$tmp_g."</a>";
	      echo "<br>";
	     }
	    ?>	      
	    </td> 

	  </tr>
	  <tr valign="top">
	    <td class="<?=$cl4?>" align="right" nowrap title="<?=$lng[15][58]?>">&nbsp;<?=$lng[15][10]?>&nbsp;:&nbsp;</td>
	    <td class="<?=$cl4?>">
	      <?
	      include("ini/txts/".$_SESSION['chlang']."/state.php");
	      echo $state_array[$r_state];
	      ?>
	    </td> 
	    <td class="<?=$cl5?>" align="right" nowrap title="<?=$lng[15][59]?>">&nbsp;<?=$lng[15][11]?>&nbsp;:&nbsp;</td>
	    <td class="<?=$cl5?>" colspan="5">
	      <?
	      include("ini/txts/".$_SESSION['chlang']."/type.php");
	      echo $type_array[$r_type_r];
	      ?>
	    </td>
	  </tr>
	  <tr valign="top">
	    <td class="<?=$cl6?>" align="right" nowrap title="<?=$lng[15][60]?>">&nbsp;<?=$lng[15][13]?>&nbsp;:&nbsp;</td>
	    <td class="<?=$cl6?>"><?=$r_priority?></td>
	    <!--td class="<?=$cl7?>" align="right" nowrap>&nbsp;<?=$lng[15][15]?>&nbsp;:&nbsp;</td>
	    <td class="<?=$cl7?>">
	      <?
	        if ($r_valid==0) echo $lng[15][31];
	        elseif ($r_valid==1) echo $lng[15][32];
	        elseif ($r_valid==2) echo $lng[15][33];
	      ?>	       	    
	    </td-->
	    <td class="<?=$cl8?>" align="right" nowrap title="<?=$lng[15][56]?>">&nbsp;<?=$lng[15][30]?>&nbsp;:&nbsp;</td>
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
	    <td class="blue" align="right" nowrap title="<?=$lng[15][67]?>">&nbsp;<?=$lng[15][45]?>&nbsp;:&nbsp;</td>
	    <td class="blue">&nbsp;<?=$r_id?></td>
	    <td class="blue" align="right" nowrap title="<?=$lng[15][69]?>">&nbsp;<?=$lng[15][34]?>&nbsp;:&nbsp;</td>
	    <td class="blue">&nbsp;<?=$r_version?></td>
	  </tr>  
	  <tr valign="top">
	    <td class="<?=$cl20?>" align="right" nowrap title="<?=$lng[15][62]?>">&nbsp;<?=$lng[15][40]?>&nbsp;:&nbsp;</td>
	    <td class="<?=$cl20?>">
	    <?
	    $query23="select * from components where c_id in (".$r_component."0) order by c_name asc";
	    $rs23 = mysql_query($query23) or die(mysql_error());
	    while($row23=mysql_fetch_array($rs23))
	     {
	      echo "<a href='index.php?inc=view_component&c_id=".$row23['c_id']."'>".htmlspecialchars($row23['c_name'])."</a>; ";
	     }
	    ?>
	    </td>
	    <td class="<?=$cl21?>" align="right" nowrap title="<?=$lng[15][63]?>">&nbsp;<?=$lng[15][41]?>&nbsp;:&nbsp;</td>
	    <td class="<?=$cl21?>"><?=$r_source?></td>
	    <td class="<?=$cl22?>" align="right" nowrap title="<?=$lng[15][64]?>">&nbsp;<?=$lng[15][42]?>&nbsp;:&nbsp;</td>
	    <td class="<?=$cl22?>">
	      <?
	      include("ini/txts/".$_SESSION['chlang']."/risk.php");
	      echo $risk_array[$r_risk];
	      ?>
	    </td>
	    <td class="<?=$cl23?>" align="right" nowrap title="<?=$lng[15][65]?>">&nbsp;<?=$lng[15][43]?>&nbsp;:&nbsp;</td>
	    <td class="<?=$cl23?>">
	      <?
	      include("ini/txts/".$_SESSION['chlang']."/complexity.php");
	      echo $complexity_array[$r_complexity];
	      ?>
	    </td>
	  </tr>  
	  <tr valign="top">
	    <td class="<?=$cl29?>" align="right" nowrap title="<?=$lng[15][89]?>">&nbsp;<?=$lng[15][88]?>&nbsp;:&nbsp;</td>
	    <td class="<?=$cl29?>" align="left"><?=$r_weight?> 
	    <td class="<?=$cl24?>" align="right" nowrap title="<?=$lng[15][66]?>">&nbsp;<?=$lng[15][44]?>&nbsp;:&nbsp;</td>
	    <td class="<?=$cl24?>" align="left"><?=$r_points?> 
	    <td class="<?=$cl38?>" align="right" nowrap title="<?=$lng[15][108]?>">&nbsp;<?=$lng[15][107]?>&nbsp;:&nbsp;</td>
	    <td class="<?=$cl38?>" align="left" colspan=3>
	    <?
	      $query456="select k.* from keywords k where k.k_id in (".$r_keywords."0) order by k.k_name asc";
	      //$query456="select k_id, k_name from keywords where k_id in (".$r_keywords."0)";
              $rs456 = mysql_query($query456) or die(mysql_error());	        
	      while($row456=mysql_fetch_array($rs456)) 
	       {
	        ?>
	        <?=htmlspecialchars($row456['k_name'])?>;
	      <?}?>
	    </td>
	  </tr> 
	  <tr valign="top">
	    <td class="<?=$cl26?>" align="right" nowrap title="<?=$lng[15][83]?>">&nbsp;<?=$lng[15][82]?>&nbsp;:&nbsp;</td>
	    <td class="<?=$cl26?>" align="left">&nbsp;<?=$r_satisfaction?></td>
	    <td class="<?=$cl27?>" align="right" nowrap title="<?=$lng[15][85]?>">&nbsp;<?=$lng[15][84]?>&nbsp;:&nbsp;</td>
	    <td class="<?=$cl27?>" align="left">&nbsp;<?=$r_dissatisfaction?></td>
	    <td class="<?=$cl25?>" align="right" nowrap title="<?=$lng[15][79]?>">&nbsp;<?=$lng[15][78]?>&nbsp;:&nbsp;</td>
	    <td class="<?=$cl25?>" align="left">
	      <?
	      $query456="select r_id, r_name from requirements where r_id in (".$r_depends."0)";
              $rs456 = mysql_query($query456) or die(mysql_error());	        
	      while($row456=mysql_fetch_array($rs456)) 
	       {
	        ?>
	        &nbsp;<a href="index.php?inc=view_requirement&r_id=<?=$row456['r_id']?>"><?=htmlspecialchars($row456['r_name'])?></a><br>
	      <?}?>
	    </td>
	    <td class="<?=$cl28?>" align="right" nowrap title="<?=$lng[15][81]?>">&nbsp;<?=$lng[15][80]?>&nbsp;:&nbsp;</td>
	    <td class="<?=$cl28?>" align="left">
	      <?
	      $query456="select r_id, r_name from requirements where r_id in (".$r_conflicts."0)";
              $rs456 = mysql_query($query456) or die(mysql_error());	        
	      while($row456=mysql_fetch_array($rs456)) 
	       {
	        ?>
	        &nbsp;<a href="index.php?inc=view_requirement&r_id=<?=$row456['r_id']?>"><?=htmlspecialchars($row456['r_name'])?></a><br>
	      <?}?>
	    </td>
	  </tr> 
	  <tr valign="top">
	    <td class="<?=$cl10?>" align="right" nowrap title="<?=$lng[15][68]?>">&nbsp;<?=$lng[15][16]?>&nbsp;:&nbsp;</td>
	    <td class="<?=$cl10?>">&nbsp;<?=$author?></td>
	    <td class="<?=$cl11?>" align="right" nowrap title="<?=$lng[15][61]?>">&nbsp;<?=$lng[15][14]?>&nbsp;:&nbsp;</td>
	    <td class="<?=$cl11?>">&nbsp;<a href="<?=$r_link?>" target="_blank">
	    <?
	     for ($i=0;$i<strlen($r_link);$i+=40) 
	      echo substr($r_link,$i,40)." ";
	    ?>
	    </a></td>
	    <td class="<?=$cl17?>" nowrap title="<?=$lng[15][54]?>">&nbsp;<?=$lng[15][38]?>&nbsp;:&nbsp;</td>
	    <td class="<?=$cl17?>">
	      <?
	      $query456="select r_name from requirements where r_id=".$r_parent_id;
              $rs456 = mysql_query($query456) or die(mysql_error());	        
	      if($row456=mysql_fetch_array($rs456)) 
	       {
	        ?>
	        &nbsp;<a href="index.php?inc=view_requirement&r_id=<?=$r_parent_id?>"><?=htmlspecialchars($row456['r_name'])?></a>
	      <?}?>
	    </td>	    
	    <td class="<?=$cl18?>" nowrap title="<?=$lng[15][55]?>">&nbsp;<?=$lng[15][39]?>&nbsp;:&nbsp;</td>
	    <td class="<?=$cl18?>">&nbsp;<?=$r_pos?></a></td>	    
	    </td>
	  </tr>  
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
	      <td class="<?=$cl30?>" align="right" nowrap title="<?=$uf_text[0]?>">&nbsp;<?=$uf_name[0]?>&nbsp;:&nbsp;</td>
	      <td class="<?=$cl30?>" align="left" <?if ($cnt_num==1) echo "colspan=7";?>><?=$r_userfield1?></td>
	    <?}?>
	    <?if ($cnt_num>1) {?>
	      <td class="<?=$cl31?>" align="right" nowrap title="<?=$uf_text[1]?>">&nbsp;<?=$uf_name[1]?>&nbsp;:&nbsp;</td>
	      <td class="<?=$cl31?>" align="left" <?if ($cnt_num==2) echo "colspan=5";?>><?=$r_userfield2?></td>
	    <?}?>
	    <?if ($cnt_num>2) {?>
	      <td class="<?=$cl32?>" align="right" nowrap title="<?=$uf_text[2]?>">&nbsp;<?=$uf_name[2]?>&nbsp;:&nbsp;</td>
	      <td class="<?=$cl32?>" align="left" colspan=3><?=$r_userfield3?></td>
	    <?}?>
	  </tr> 
	  <?
	   }
	  if ($cnt_num>3) 
	   {
	    ?>
	  <tr valign="top">
	    <?if ($cnt_num>3) {?>
	      <td class="<?=$cl33?>" align="right" nowrap title="<?=$uf_text[3]?>">&nbsp;<?=$uf_name[3]?>&nbsp;:&nbsp;</td>
	      <td class="<?=$cl33?>" align="left" <?if ($cnt_num==4) echo "colspan=7";?>><?=$r_userfield4?></td>
	    <?}?>
	    <?if ($cnt_num>4) {?>
	      <td class="<?=$cl34?>" align="right" nowrap title="<?=$uf_text[4]?>">&nbsp;<?=$uf_name[4]?>&nbsp;:&nbsp;</td>
	      <td class="<?=$cl34?>" align="left" <?if ($cnt_num==5) echo "colspan=5";?>><?=$r_userfield5?></td>
	    <?}?>
	    <?if ($cnt_num>5) {?>
	      <td class="<?=$cl35?>" align="right" nowrap title="<?=$uf_text[5]?>">&nbsp;<?=$uf_name[5]?>&nbsp;:&nbsp;</td>
	      <td class="<?=$cl35?>" align="left" colspan=3><?=$r_userfield6?></td>
	    <?}?>
	  </tr> 
	  <?}?>

	  <tr valign="top">
	    <td class="<?=$cl13?>" align="right" nowrap title="<?=$lng[15][70]?>">&nbsp;<?=$lng[15][17]?>&nbsp;:&nbsp;</td>
	    <td class="<?=$cl13?>">&nbsp;<?=($r_creation_date=="00.00.0000 00:00")?"-":$r_creation_date?></td>
	    <td class="<?=$cl14?>" align="right" nowrap title="<?=$lng[15][71]?>">&nbsp;<?=$lng[15][18]?>&nbsp;:&nbsp;</td>
	    <td class="<?=$cl14?>">&nbsp;<?=($r_change_date=="00.00.0000 00:00")?"-":$r_change_date?></td>
	    <td class="<?=$cl15?>" align="right" nowrap title="<?=$lng[15][72]?>">&nbsp;<?=$lng[15][19]?>&nbsp;:&nbsp;</td>
	    <td class="<?=$cl15?>">&nbsp;<?=($r_accept_date=="00.00.0000 00:00")?"-":$r_accept_date?></td>
	    <td class="<?=$cl16?>" align="right" nowrap title="<?=$lng[15][73]?>">&nbsp;<?=$lng[15][20]?>&nbsp;:&nbsp;</td>
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
	  </tr>  	  
	</table>
    </td> 	 
  </tr>
<?}?>
</tbody>  
</table>


<table border="0" width="100%">
  <tbody id="tr2" style="display:"><a href="#" onclick="if (document.getElementById('tr2').style.display=='')  document.getElementById('tr2').style.display='none';else document.getElementById('tr2').style.display='';">&nbsp;-<?=$lng[16][16]?></a>
  <tr valign="top">
    <td>
      <table border="0" cellpadding="2" cellspacing="2" class="content" width="100%">
	  <tr class="gray">
	    <td align="center" width="100%"><b><?=$lng[16][17]?></b></td>
	  </tr>
	  <?
	    $r_parent_tmp=1;$cnt=0;
	    $parent=checkTree($r_id);
	   // die($parent);
	    ?>
	    <?
	      $query3="select r_id, r_name, r_parent_id from requirements where r_id=".$parent;
  	      $rs3 = mysql_query($query3) or die(mysql_error());
  	      if($row3=mysql_fetch_array($rs3))
  	       {
  	        ?>
	        <tr class="blue">
	           <?if ($row3['r_id']==$r_id) {?> 
	           <td>&nbsp;+<?=$row3['r_name']?></td>
                   <?}else {?>
	           <td>&nbsp;+<a href="index.php?inc=view_requirement&r_id=<?=$row3['r_id']?>"><?=$row3['r_name']?></a></td>
                   <?}?>
	        </tr>
	      <?
	       }
	      //displaying tree
  	      if ($parent!=-1) getTree($parent,$r_id);
  	      else echo "<span class='error'>".$lng[16][20]."</span>";
  	    ?> 
	</table>
    </td> 	 
  </tr>
  </tbody>
</table>

<table border="0" width="100%">
  <tbody id="tr3" style="display:"><a href="#" onclick="if (document.getElementById('tr3').style.display=='')  document.getElementById('tr3').style.display='none';else document.getElementById('tr3').style.display='';">&nbsp;-<?=$lng[16][13]?></a>
  <tr valign="top">
    <td>
      <table border="0" cellpadding="2" cellspacing="2" class="content" width="100%">
	  <form name="f11">
	  <tr class="gray">
	    <td align="center"><?if ($_SESSION['uid']!=""){?><input type="button" value="<?=$lng[16][8]?>" onclick="document.location.href='index.php?inc=add_comment&r_id=<?=$r_id?>&what=long'"><?}?>&nbsp;</td>
	    <td align="center" colspan=7 width="100%"><b><?=$lng[16][7]?></b></td>
	  </tr>
	  <?
	  $cnt=0;
	  while($row44=mysql_fetch_array($rs44)) 
	   {	  
	    ?>
	  <tr class="blue">
	    <td align="right" nowrap>&nbsp;<?=$lng[16][9]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;<?=htmlspecialchars($row44['u_name'])?></td>
	    <td align="right" nowrap>&nbsp;<?=$lng[16][10]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;<?=($row44['c_date']!="00.00.0000")?$row44['c_date']:""?>
	    <td>&nbsp;<?=$lng[18][3]?>&nbsp;<input type="checkbox" name="ch<?=$cnt?>" value="1" <?=($row44['c_question']=="1")?"checked":""?>>
	    <?
	      if ($_SESSION['rights']=="4" || $_SESSION['rights']=="5")
	       {
	        $flag_as=0;
	        if ($_SESSION['rights']=="5") $flag_as=1;
	        if ($_SESSION['rights']=="4") 
	         {
	          $query_project44="select * from projects p left outer join project_users pu on p.p_id=pu.pu_p_id where p.p_id='".$p_id."' and (pu.pu_u_id=".$_SESSION['uid']." or p_leader=".$_SESSION['uid'].")";
  	          $rs_project44 = mysql_query($query_project44) or die(mysql_error());
  	          if($row_project44=mysql_fetch_array($rs_project44)) $flag_as=1;  	          
	         }
	        if ($flag_as) {
	        ?>
	        &nbsp;<input type="button" value="<?=$lng[16][19]?>" onclick="sub('<?=$cnt?>','update');">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="<?=$lng[16][11]?>" onclick="if (confirm('<?=$lng[16][12]?>')) sub('<?=$cnt?>','delete');return false;">
	     <?}}?>
	   </td>
	  </tr>  
	  <tr class="light_blue">
	    <td colspan=5>&nbsp;<?=$row44['c_text']?></td>
	  </tr>  
	  <input type="hidden" name="c_id<?=$cnt?>" value="<?=$row44['c_id']?>">
	  <?
	   $cnt++;
	  }?>
	  <input type="hidden" name="inc" value="view_requirement_long">
	  <input type="hidden" name="c_id" value="">
	  <input type="hidden" name="ch" value="">
	  <input type="hidden" name="r_id" value="<?=$r_id?>">
	  <input type="hidden" name="what" value="long">
	  <input type="hidden" name="action" value="">
	  </form>
	</table>
    </td> 	 
  </tr>
  </tbody>
</table>


<script type="text/javascript">
function pdf2(r,p,m)
 {
  if (document.getElementById('tr1').style.display=='none') h=0;
  else h=1;
  if (document.getElementById('tr2').style.display=='none') t=0;
  else t=1;
  if (document.getElementById('tr3').style.display=='none') c=0;
  else c=1;
  //window.open('pdf.php?r_id='+r+'&history='+h+'&tree='+t+'&comments='+c+'&project_list='+p, 'pdf','menubar=yes,status=yes');
  document.location.href='index.php?inc=pdf_fields&r_id='+r+'&history='+h+'&tree='+t+'&comments='+c+'&project_list='+p+'&mode='+m;
 } 

function sub(who,what)
 {
  df=document.forms['f11'];
  if (df.elements['ch'+who].checked) df.elements['ch'].value=1;else df.elements['ch'].value=0;
  df.elements['c_id'].value=df.elements['c_id'+who].value;
  df.action.value=what;
  df.submit();	     
 }
 </script>
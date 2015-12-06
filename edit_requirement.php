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

//check if logged
if (!($_SESSION['rights']=="1" || $_SESSION['rights']=="2" || $_SESSION['rights']=="3" || $_SESSION['rights']=="4" || $_SESSION['rights']=="5")) header("Location:index.php");

if ($r_id!="")
 {
  //authorization check
  $query="select r.* from requirements r, projects p where r.r_id=".$r_id." and ((r.r_p_id=p.p_id and p.p_id in (".$project_list.")) OR r.r_p_id=0)";
  $rs = mysql_query($query) or die(mysql_error());
  if($row=mysql_fetch_array($rs)) ;
  else header("Location:index.php");
 }

if ($r_pos=="") $r_pos=0;
 
if ($action=="delete" && $r_id!="")
 {
  $query="select * from requirements where r_parent_id=".$r_id;
  $rs = mysql_query($query) or die(mysql_error());
  if($row=mysql_fetch_array($rs)) $tmp="<span class='error'>".$lng[15][117]."</span><br><br>";
  else
   {
    //history
    $query="select * from requirements where r_id=".$r_id;
    $rs = mysql_query($query) or die(mysql_error());
    if($row=mysql_fetch_array($rs)) 
     {
      $r_parent_id_tmp=$row['r_parent_id'];
      $r_pos_tmp=$row['r_pos'];
      $query="delete from requirements_history where r_parent_id=".$row['r_id'];
      mysql_query($query) or die(mysql_error());
     }
    
    //correcting positions of nodes of the old parent
    //da se proveri
    if ($r_parent_id_tmp!=0)
     {
      $query2="update requirements set r_pos=r_pos-1 where r_pos>".$r_pos_tmp." and r_parent_id=".$r_parent_id_tmp;
      $rs2 = mysql_query($query2) or die(mysql_error());
     }

    $query="delete from requirements where r_id=".$r_id;
    mysql_query($query) or die(mysql_error());
    header("Location:index.php?inc=edit_requirement");
   } 
 }


//if new keywors added
$kw_ids="";
if ($new_keywords!="")
 {
  $kw_arr=explode(",",$new_keywords);
  while (list ($key, $val) = each ($kw_arr))
   {
    $query="select * from keywords where k_name='".trim(escapeChars($val))."'";
    $rs = mysql_query($query) or die(mysql_error());
    if($row=mysql_fetch_array($rs)) ;
    else
     {
      $query="insert into keywords (k_name) values ('".trim(escapeChars($val))."')";
      mysql_query($query) or die(mysql_error());
      $kw_ids.=mysql_insert_id().","; 
      
      //$k_id2=mysql_insert_id();  
      //$query="insert into project_keywords (pk_p_id, pk_k_id) values ('".$p_id."','".$k_id2."')";
      //mysql_query($query) or die(mysql_error());

     }
   }
 }  


//cloning requirement to new project
if ($action=="clone" && $r_id!="" && $tmp_pr!="")
 {
  //insert new record 
  $query="select * from requirements where r_id=".$r_id;
  $rs = mysql_query($query) or die(mysql_error());
  if($row=mysql_fetch_array($rs)) 
   {
    $arr1[0]=$r_id;
    $r_id_old=$r_id;
    $r_p_id_tmp=$row['r_p_id'];
    $query="insert into requirements (r_p_id, r_release, r_c_id, r_s_id, r_stakeholder,r_glossary,r_keyword, r_u_id,r_assigned_u_id,r_name,r_desc,r_state,r_type_r,r_priority,r_link,r_satisfaction,r_dissatisfaction,r_conflicts,r_depends,r_component,r_source,r_risk,r_complexity,r_weight,r_points,r_creation_date,r_change_date,r_accept_date,r_accept_user,r_stub, r_keywords, r_userfield1, r_userfield2, r_userfield3, r_userfield4, r_userfield5, r_userfield6) values ('".$tmp_pr."','".escapeChars($row['r_release'])."','".escapeChars($row['r_c_id'])."','".escapeChars($row['r_s_id'])."','".escapeChars($row['r_stakeholder'])."','".escapeChars($row['r_glossary'])."','".escapeChars($row['r_keyword'])."','".escapeChars($row['r_u_id'])."','".$row['r_assigned_u_id']."','".escapeChars($row['r_name'])."','".escapeChars($row['r_desc'])."','".escapeChars($row['r_state'])."','".escapeChars($row['r_type_r'])."','".escapeChars($row['r_priority'])."','".escapeChars($row['r_link'])."','".escapeChars($row['r_satisfaction'])."','".escapeChars($row['r_dissatisfaction'])."','".escapeChars($row['r_conflicts'])."','".escapeChars($row['r_depends'])."','".escapeChars($row['r_component'])."','".escapeChars($row['r_source'])."','".escapeChars($row['r_risk'])."','".escapeChars($row['r_complexity'])."','".escapeChars($row['r_weight'])."','".escapeChars($row['r_points'])."','".escapeChars($row['r_creation_date'])."',now(),'".escapeChars($row['r_accept_date'])."','".escapeChars($row['r_accept_user'])."','".escapeChars($row['r_stub'])."','".escapeChars($row['r_keywords'])."','".escapeChars($row['r_userfield1'])."','".escapeChars($row['r_userfield2'])."','".escapeChars($row['r_userfield3'])."','".escapeChars($row['r_userfield4'])."','".escapeChars($row['r_userfield5'])."','".escapeChars($row['r_userfield6'])."')";
    mysql_query($query) or die(mysql_error());
    $r_id=mysql_insert_id();
    $arr2[0]=$r_id;    
   }   
  
  //moving all tree
  //getting tree array
  $query="select * from requirements where r_p_id=".$r_p_id_tmp." and r_parent_id=".$r_id_old." order by r_pos asc";
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
    //moving whole tree to new project
    $tmp_r_id=substr($val,strpos($val,"|")+1);
        
    //history
    $c=1;
    $query="select * from requirements where r_id=".$tmp_r_id." order by r_pos asc";
    $rs = mysql_query($query) or die(mysql_error());
    if($row=mysql_fetch_array($rs)) 
     {
      $arr1[$c]=$tmp_r_id;
      $query="insert into requirements (r_p_id,r_release, r_c_id, r_s_id, r_stakeholder,r_glossary,r_keyword, r_u_id, r_assigned_u_id, r_name, r_desc, r_state, r_type_r, r_priority, r_valid, r_link, r_satisfaction, r_dissatisfaction, r_conflicts, r_depends, r_component, r_source, r_risk, r_complexity, r_weight, r_points, r_creation_date, r_change_date, r_accept_date, r_accept_user, r_parent_id, r_pos, r_stub, r_keywords, r_userfield1, r_userfield2, r_userfield3, r_userfield4, r_userfield5, r_userfield6) values ('".$tmp_pr."','".escapeChars($row['r_release'])."','".escapeChars($row['r_c_id'])."','".escapeChars($row['r_s_id'])."','".escapeChars($row['r_stakeholder'])."','".escapeChars($row['r_glossary'])."','".escapeChars($row['r_keyword'])."','".escapeChars($row['r_u_id'])."','".$row['r_assigned_u_id']."','".escapeChars($row['r_name'])."','".escapeChars($row['r_desc'])."','".escapeChars($row['r_state'])."','".escapeChars($row['r_type_r'])."','".escapeChars($row['r_priority'])."','".escapeChars($row['r_valid'])."','".escapeChars($row['r_link'])."','".escapeChars($row['r_satisfaction'])."','".escapeChars($row['r_dissatisfaction'])."','".escapeChars($row['r_conflicts'])."','".escapeChars($row['r_depends'])."','".escapeChars($row['r_component'])."','".escapeChars($row['r_source'])."','".escapeChars($row['r_risk'])."','".escapeChars($row['r_complexity'])."','".escapeChars($row['r_weight'])."','".escapeChars($row['r_points'])."','".escapeChars($row['r_creation_date'])."','".escapeChars($row['r_change_date'])."','".escapeChars($row['r_accept_date'])."','".escapeChars($row['r_accept_user'])."','".escapeChars($row['r_parent_id'])."','".escapeChars($row['r_pos'])."','".escapeChars($row['r_stub'])."','".escapeChars($row['r_keywords'])."','".escapeChars($row['r_userfield1'])."','".escapeChars($row['r_userfield2'])."','".escapeChars($row['r_userfield3'])."','".escapeChars($row['r_userfield4'])."','".escapeChars($row['r_userfield5'])."','".escapeChars($row['r_userfield6'])."')";
      mysql_query($query) or die(mysql_error());
      $arr2[$c]=mysql_insert_id();
      $c++;
     }  
   }  
  //changing parent_ids with the new ones 
  $c=0;
  while (list ($key, $val) = each ($arr1))
   {
    $query="update requirements set r_parent_id='".$arr2[$c]."' where r_parent_id='".$val."' and r_p_id='".$tmp_pr."'";
    //echo $query."<br>";
    mysql_query($query) or die(mysql_error());
    $c++;
   } 
 }

if ($action=="add")
 {
  //work up the text
  $ta=str_replace('<link href="styles.css" rel="stylesheet" />','',stripslashes($ta));
  
  //getting nodes position count
  if ($r_parent_id!="0")
   {
    $query2="select count(*) from requirements where r_parent_id=".$r_parent_id;
    $rs2 = mysql_query($query2) or die(mysql_error());
    if($row2=mysql_fetch_array($rs2)) $pos_cnt=$row2[0]+1;
   }
  else 
   {
    $query2="select count(*) from requirements where r_parent_id=0";
    $rs2 = mysql_query($query2) or die(mysql_error());
    if($row2=mysql_fetch_array($rs2)) $pos_cnt=$row2[0]+1;
   }  
     
    //$r_link=str_replace('\\','|',$r_link);
    //$r_link=str_replace('||','/',$r_link);
    
         
  if ($stub==2) $query="insert into requirements (r_name, r_desc, r_p_id, r_release, r_c_id, r_s_id, r_stakeholder,r_glossary,r_keyword, r_u_id, r_assigned_u_id, r_state, r_type_r, r_priority, r_link, r_satisfaction, r_dissatisfaction, r_conflicts, r_depends, r_component, r_source, r_risk, r_complexity, r_weight, r_points, r_parent_id, r_pos, r_creation_date, r_change_date, r_stub, r_keywords, r_userfield1, r_userfield2, r_userfield3, r_userfield4, r_userfield5, r_userfield6) values ('".escapeChars($r_name)."','".stripbr(escapeChars($ta))."','".escapeChars($r_p_id)."','".escapeChars($r_release)."','".escapeChars($r_c_id)."','".escapeChars($r_s_id)."','".escapeChars($r_stakeholder)."','".escapeChars($r_glossary)."','".escapeChars($r_keyword)."','".$_SESSION['uid']."','".$r_assigned_u_id."','".escapeChars($r_state)."','".escapeChars($r_type_r)."','".escapeChars($r_priority)."','".addslashes($r_link)."','".escapeChars($r_satisfaction)."','".escapeChars($r_dissatisfaction)."','".escapeChars($r_conflicts)."','".escapeChars($r_depends)."','".escapeChars($r_component)."','".escapeChars($r_source)."','".escapeChars($r_risk)."','".escapeChars($r_complexity)."','".escapeChars($r_weight)."','".escapeChars($r_points)."','".escapeChars($r_parent_id)."','".escapeChars($pos_cnt)."',DATE_ADD( NOW( ) , INTERVAL - ".TIME_DIFF_HOURS." HOUR),DATE_ADD( NOW( ) , INTERVAL - ".TIME_DIFF_HOURS." HOUR),2,'".escapeChars($r_keywords).$kw_ids."','".escapeChars($r_userfield1)."','".escapeChars($r_userfield2)."','".escapeChars($r_userfield3)."','".escapeChars($r_userfield4)."','".escapeChars($r_userfield5)."','".escapeChars($r_userfield6)."')";
  elseif ($stub==1) $query="insert into requirements (r_name, r_desc, r_p_id, r_release, r_c_id, r_s_id, r_stakeholder,r_glossary,r_keyword, r_u_id, r_assigned_u_id, r_state, r_type_r, r_priority, r_link, r_satisfaction, r_dissatisfaction, r_conflicts, r_depends, r_component, r_source, r_risk, r_complexity, r_weight, r_points, r_parent_id, r_pos, r_creation_date, r_change_date, r_stub, r_keywords, r_userfield1, r_userfield2, r_userfield3, r_userfield4, r_userfield5, r_userfield6) values ('".escapeChars($r_name)."','".stripbr(escapeChars($ta))."','".escapeChars($r_p_id)."','".escapeChars($r_release)."','".escapeChars($r_c_id)."','".escapeChars($r_s_id)."','".escapeChars($r_stakeholder)."','".escapeChars($r_glossary)."','".escapeChars($r_keyword)."','".$_SESSION['uid']."','".$r_assigned_u_id."','".escapeChars($r_state)."','".escapeChars($r_type_r)."','".escapeChars($r_priority)."','".addslashes($r_link)."','".escapeChars($r_satisfaction)."','".escapeChars($r_dissatisfaction)."','".escapeChars($r_conflicts)."','".escapeChars($r_depends)."','".escapeChars($r_component)."','".escapeChars($r_source)."','".escapeChars($r_risk)."','".escapeChars($r_complexity)."','".escapeChars($r_weight)."','".escapeChars($r_points)."','".escapeChars($r_parent_id)."','".escapeChars($pos_cnt)."',DATE_ADD( NOW( ) , INTERVAL - ".TIME_DIFF_HOURS." HOUR),DATE_ADD( NOW( ) , INTERVAL - ".TIME_DIFF_HOURS." HOUR),1,'".escapeChars($r_keywords).$kw_ids."','".escapeChars($r_userfield1)."','".escapeChars($r_userfield2)."','".escapeChars($r_userfield3)."','".escapeChars($r_userfield4)."','".escapeChars($r_userfield5)."','".escapeChars($r_userfield6)."')";
  else $query="insert into requirements (r_name, r_desc, r_p_id, r_release, r_c_id, r_s_id, r_stakeholder,r_glossary,r_keyword, r_u_id, r_assigned_u_id, r_state, r_type_r, r_priority, r_link, r_satisfaction, r_dissatisfaction, r_conflicts, r_depends, r_component, r_source, r_risk, r_complexity, r_weight, r_points, r_parent_id, r_pos, r_creation_date, r_change_date, r_stub, r_keywords, r_userfield1, r_userfield2, r_userfield3, r_userfield4, r_userfield5, r_userfield6) values ('".escapeChars($r_name)."','".stripbr(escapeChars($ta))."','".escapeChars($r_p_id)."','".escapeChars($r_release)."','".escapeChars($r_c_id)."','".escapeChars($r_s_id)."','".escapeChars($r_stakeholder)."','".escapeChars($r_glossary)."','".escapeChars($r_keyword)."','".$_SESSION['uid']."','".$r_assigned_u_id."','".escapeChars($r_state)."','".escapeChars($r_type_r)."','".escapeChars($r_priority)."','".addslashes($r_link)."','".escapeChars($r_satisfaction)."','".escapeChars($r_dissatisfaction)."','".escapeChars($r_conflicts)."','".escapeChars($r_depends)."','".escapeChars($r_component)."','".escapeChars($r_source)."','".escapeChars($r_risk)."','".escapeChars($r_complexity)."','".escapeChars($r_weight)."','".escapeChars($r_points)."','".escapeChars($r_parent_id)."','".escapeChars($pos_cnt)."',DATE_ADD( NOW( ) , INTERVAL - ".TIME_DIFF_HOURS." HOUR),DATE_ADD( NOW( ) , INTERVAL - ".TIME_DIFF_HOURS." HOUR),0,'".escapeChars($r_keywords).$kw_ids."','".escapeChars($r_userfield1)."','".escapeChars($r_userfield2)."','".escapeChars($r_userfield3)."','".escapeChars($r_userfield4)."','".escapeChars($r_userfield5)."','".escapeChars($r_userfield6)."')";
  mysql_query($query) or die(mysql_error());
  $r_id=mysql_insert_id();
  header("Location:index.php?inc=view_requirement&r_id=".$r_id);
 } 
 
if ($action=="update" && $p_status==2) $tmp="<span class='error'>".$lng[15][74]."</span><br><br>";
else
 {
  if ($action=="update" && $r_id!="")
   {
    //history
    $query="select * from requirements where r_id=".$r_id;
    $rs = mysql_query($query) or die(mysql_error());
    if($row=mysql_fetch_array($rs)) 
     {
      $query="insert into requirements_history (r_parent_id, r_p_id, r_release, r_c_id, r_s_id, r_stakeholder,r_glossary,r_keyword, r_u_id, r_assigned_u_id, r_name, r_desc, r_state, r_type_r, r_priority, r_valid, r_link, r_satisfaction, r_dissatisfaction, r_conflicts, r_depends, r_component, r_source, r_risk, r_complexity, r_weight, r_points, r_creation_date, r_change_date, r_accept_date, r_accept_user, r_version, r_save_date, r_save_user, r_parent_id2, r_pos, r_stub, r_keywords, r_userfield1, r_userfield2, r_userfield3, r_userfield4, r_userfield5, r_userfield6) values ('".$r_id."','".escapeChars($row['r_p_id'])."','".escapeChars($row['r_release'])."','".escapeChars($row['r_c_id'])."','".escapeChars($row['r_s_id'])."','".escapeChars($row['r_stakeholder'])."','".escapeChars($row['r_glossary'])."','".escapeChars($row['r_keyword'])."','".escapeChars($row['r_u_id'])."','".$row['r_assigned_u_id']."','".escapeChars($row['r_name'])."','".escapeChars($row['r_desc'])."','".escapeChars($row['r_state'])."','".escapeChars($row['r_type_r'])."','".escapeChars($row['r_priority'])."','".escapeChars($row['r_valid'])."','".escapeChars($row['r_link'])."','".escapeChars($row['r_satisfaction'])."','".escapeChars($row['r_dissatisfaction'])."','".escapeChars($row['r_conflicts'])."','".escapeChars($row['r_depends'])."','".escapeChars($row['r_component'])."','".escapeChars($row['r_source'])."','".escapeChars($row['r_risk'])."','".escapeChars($row['r_complexity'])."','".escapeChars($row['r_weight'])."','".escapeChars($row['r_points'])."','".escapeChars($row['r_creation_date'])."','".escapeChars($row['r_change_date'])."','".escapeChars($row['r_accept_date'])."','".escapeChars($row['r_accept_user'])."','".escapeChars($row['r_version'])."',DATE_ADD( NOW( ) , INTERVAL - ".TIME_DIFF_HOURS." HOUR),'".$_SESSION['uid']."','".escapeChars($row['r_parent_id'])."','".escapeChars($row['r_pos'])."','".escapeChars($row['r_stub'])."','".escapeChars($row['r_keywords'])."','".escapeChars($row['r_userfield1'])."','".escapeChars($row['r_userfield2'])."','".escapeChars($row['r_userfield3'])."','".escapeChars($row['r_userfield4'])."','".escapeChars($row['r_userfield5'])."','".escapeChars($row['r_userfield6'])."')";
      mysql_query($query) or die(mysql_error());
     }   
    //work up the text
    $ta=str_replace('<link href="styles.css" rel="stylesheet" />','',stripslashes($ta));
  
    //if validated -adding accept date and user
    //$r_link=str_replace('\\','|',$r_link);
    //$r_link=str_replace('||','/',$r_link);
    //$query="update requirements set r_name='".escapeChars($r_name)."', r_desc='".stripbr(escapeChars($ta))."', r_p_id='".escapeChars($r_p_id)."', r_release='".escapeChars($r_release)."', r_c_id='".escapeChars($r_c_id)."', r_s_id='".escapeChars($r_s_id)."', r_stakeholder='".escapeChars($r_stakeholder)."', r_glossary='".escapeChars($r_glossary)."', r_keyword='".escapeChars($r_keyword)."', r_assigned_u_id='".escapeChars($r_assigned_u_id)."', r_state='".escapeChars($r_state)."', r_type_r='".escapeChars($r_type_r)."', r_priority='".escapeChars($r_priority)."', r_link='".addslashes($r_link)."', r_satisfaction='".escapeChars($r_satisfaction)."', r_dissatisfaction='".escapeChars($r_dissatisfaction)."', r_conflicts='".escapeChars($r_conflicts)."', r_depends='".escapeChars($r_depends)."', r_component='".escapeChars($r_component)."', r_source='".escapeChars($r_source)."', r_risk='".escapeChars($r_risk)."', r_complexity='".escapeChars($r_complexity)."', r_weight='".escapeChars($r_weight)."', r_points='".escapeChars($r_points)."', r_parent_id='".escapeChars($r_parent_id)."', r_change_date=DATE_ADD( NOW( ) , INTERVAL - ".TIME_DIFF_HOURS." HOUR), r_version=r_version+1, r_stub='".escapeChars($r_stub)."', r_keywords='".escapeChars($r_keywords)."', r_userfield1='".escapeChars($r_userfield1)."', r_userfield2='".escapeChars($r_userfield2)."', r_userfield3='".escapeChars($r_userfield3)."', r_userfield4='".escapeChars($r_userfield4)."', r_userfield5='".escapeChars($r_userfield5)."', r_userfield6='".escapeChars($r_userfield6)."'";
    $query="update requirements set r_name='".escapeChars($r_name)."', r_desc='".stripbr(escapeChars($ta))."', r_p_id='".escapeChars($r_p_id)."', r_release='".escapeChars($r_release)."', r_c_id='".escapeChars($r_c_id)."', r_s_id='".escapeChars($r_s_id)."', r_stakeholder='".escapeChars($r_stakeholder)."', r_glossary='".escapeChars($r_glossary)."', r_keyword='".escapeChars($r_keyword)."', r_assigned_u_id='".escapeChars($r_assigned_u_id)."', r_state='".escapeChars($r_state)."', r_type_r='".escapeChars($r_type_r)."', r_priority='".escapeChars($r_priority)."', r_link='".addslashes($r_link)."', r_satisfaction='".escapeChars($r_satisfaction)."', r_dissatisfaction='".escapeChars($r_dissatisfaction)."', r_conflicts='".escapeChars($r_conflicts)."', r_depends='".escapeChars($r_depends)."', r_component='".escapeChars($r_component)."', r_source='".escapeChars($r_source)."', r_risk='".escapeChars($r_risk)."', r_complexity='".escapeChars($r_complexity)."', r_weight='".escapeChars($r_weight)."', r_points='".escapeChars($r_points)."', r_parent_id='".escapeChars($r_parent_id)."', r_change_date=DATE_ADD( NOW( ) , INTERVAL - ".TIME_DIFF_HOURS." HOUR), r_version=r_version+1, r_stub='".escapeChars($r_stub)."', r_keywords='".escapeChars($r_keywords).$kw_ids."', r_userfield1='".escapeChars($r_userfield1)."', r_userfield2='".escapeChars($r_userfield2)."', r_userfield3='".escapeChars($r_userfield3)."', r_userfield4='".escapeChars($r_userfield4)."', r_userfield5='".escapeChars($r_userfield5)."', r_userfield6='".escapeChars($r_userfield6)."'";
    //adding nodes position
    if ($r_parent_id!=$r_parent_id_tmp) //if different parent selected - appending at last position
     {
      $query2="select count(*) from requirements where r_parent_id=".$r_parent_id." and r_p_id='".$r_p_id."'";
      $rs2 = mysql_query($query2) or die(mysql_error());
      if($row2=mysql_fetch_array($rs2)) $pos_cnt=$row2[0]+1;
   
      //correcting positions of nodes of the old parent
      if ($r_parent_id_tmp!=0)
       {
        $query2="update requirements set r_pos=r_pos-1 where r_pos>".$r_pos_tmp." and r_parent_id=".$r_parent_id_tmp." and r_p_id='".$r_p_id."'";
        $rs2 = mysql_query($query2) or die(mysql_error());
       }
         
      //adding new pos to the query
      $query.=", r_pos='".$pos_cnt."'";
     }
    elseif ($r_parent_id==$r_parent_id_tmp && $r_pos!=$r_pos_tmp)  //if position is changed on the same parent
     {
      //changing positions of other nodes - if moving to the top
      if ($r_pos<$r_pos_tmp) $query2="update requirements set r_pos=r_pos+1 where (r_pos>=".$r_pos." and r_pos<".$r_pos_tmp.") and r_parent_id=".$r_parent_id." and r_p_id='".$r_p_id."'";
      //changing positions of other nodes - if moving to the bottom
      else $query2="update requirements set r_pos=r_pos-1 where (r_pos<=".$r_pos." and r_pos>".$r_pos_tmp.") and r_parent_id=".$r_parent_id." and r_p_id='".$r_p_id."'";
      $rs2 = mysql_query($query2) or die(mysql_error());
    
      //adding new pos to the query
      $query.=", r_pos='".$r_pos."'";    
     }
   
    //adding state, accepted date, user
    if ($r_state==4 && $r_state_old!=4) $query.=", r_accept_date=DATE_ADD( NOW( ) , INTERVAL - ".TIME_DIFF_HOURS." HOUR), r_accept_user='".$_SESSION['uid']."' where r_id=".$r_id;
    else $query.=" where r_id=".$r_id;
    
    mysql_query($query) or die(mysql_error());
    
    //if changing project
    if ($r_p_id!=$r_p_id_tmp)
     {
      //getting tree array
      $query="select * from requirements where r_p_id='".$r_p_id_tmp."' and r_parent_id=".$r_id." order by r_pos asc";
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
        //moving whole tree to new project
        $tmp_r_id=substr($val,strpos($val,"|")+1);
        
        //history
        $query="select * from requirements where r_id=".$tmp_r_id;
        $rs = mysql_query($query) or die(mysql_error());
        if($row=mysql_fetch_array($rs)) 
         {
          $query="insert into requirements_history (r_parent_id, r_p_id, r_release, r_c_id, r_s_id, r_stakeholder, r_glossary, r_keyword, r_u_id, r_assigned_u_id, r_name, r_desc, r_state, r_type_r, r_priority, r_valid, r_link, r_satisfaction, r_dissatisfaction, r_conflicts, r_depends, r_component, r_source, r_risk, r_complexity, r_weight, r_points, r_creation_date, r_change_date, r_accept_date, r_accept_user, r_version, r_save_date, r_save_user, r_parent_id2, r_pos, r_stub, r_keywords, r_userfield1, r_userfield2, r_userfield3, r_userfield4, r_userfield5, r_userfield6) values ('".$tmp_r_id."','".escapeChars($row['r_p_id'])."','".escapeChars($row['r_release'])."','".escapeChars($row['r_c_id'])."','".escapeChars($row['r_s_id'])."','".escapeChars($row['r_stakeholder'])."','".escapeChars($row['r_glossary'])."','".escapeChars($row['r_keyword'])."','".escapeChars($row['r_u_id'])."','".$row['r_assigned_u_id']."','".escapeChars($row['r_name'])."','".escapeChars($row['r_desc'])."','".escapeChars($row['r_state'])."','".escapeChars($row['r_type_r'])."','".escapeChars($row['r_priority'])."','".escapeChars($row['r_valid'])."','".escapeChars($row['r_link'])."','".escapeChars($row['r_satisfaction'])."','".escapeChars($row['r_dissatisfaction'])."','".escapeChars($row['r_conflicts'])."','".escapeChars($row['r_depends'])."','".escapeChars($row['r_component'])."','".escapeChars($row['r_source'])."','".escapeChars($row['r_risk'])."','".escapeChars($row['r_complexity'])."','".escapeChars($row['r_weight'])."','".escapeChars($row['r_points'])."','".escapeChars($row['r_creation_date'])."','".escapeChars($row['r_change_date'])."','".escapeChars($row['r_accept_date'])."','".escapeChars($row['r_accept_user'])."','".escapeChars($row['r_version'])."',DATE_ADD( NOW( ) , INTERVAL - ".TIME_DIFF_HOURS." HOUR),'".$_SESSION['uid']."','".escapeChars($row['r_parent_id'])."','".escapeChars($row['r_pos'])."','".escapeChars($row['r_stub'])."','".escapeChars($row['r_keywords'])."','".escapeChars($row['r_userfield1'])."','".escapeChars($row['r_userfield2'])."','".escapeChars($row['r_userfield3'])."','".escapeChars($row['r_userfield4'])."','".escapeChars($row['r_userfield5'])."','".escapeChars($row['r_userfield6'])."')";
          mysql_query($query) or die(mysql_error());
         }   

        $query="update requirements set r_p_id='".$r_p_id."' where r_id=".$tmp_r_id;
        mysql_query($query) or die(mysql_error());
       } 
     } 

    if ($ref=="short") header("Location:index.php?inc=view_requirement&r_id=".$r_id);
    if ($ref=="long") header("Location:index.php?inc=view_requirement_long&r_id=".$r_id);
   }
 }
  
if ($r_id!="" && $tmp_p_id=="") 
 {
  $query="select r.*, p.p_status, p.p_req_del, date_format(r.r_creation_date, '%d.%m.%Y %H:%i') as d1, date_format(r.r_change_date, '%d.%m.%Y %H:%i') as d2, date_format(r.r_accept_date, '%d.%m.%Y %H:%i') as d3, u.u_name from requirements r left outer join users u on r.r_u_id=u.u_id left outer join projects p on r.r_p_id=p.p_id where r.r_id=".$r_id;
  $rs = mysql_query($query) or die(mysql_error());
  if($row=mysql_fetch_array($rs)) 
   {
    $r_name=htmlspecialchars($row['r_name']);
    $ta=$row['r_desc'];
    $r_state=$row['r_state'];$r_state_old=$r_state;
    $r_type_r=$row['r_type_r'];
    $r_p_id=$row['r_p_id'];$r_p_id_tmp=$r_p_id;
    $r_release=$row['r_release'];
    $r_c_id=$row['r_c_id'];
    $r_s_id=$row['r_s_id'];
    $r_stakeholder=$row['r_stakeholder'];
    $r_glossary=$row['r_glossary'];
    $r_keyword=$row['r_keyword'];
    $r_assigned_u_id=$row['r_assigned_u_id'];
    $author=htmlspecialchars($row['u_name']);
    $r_priority=$row['r_priority'];
    $r_link=(htmlspecialchars($row['r_link']));
    $r_satisfaction=htmlspecialchars($row['r_satisfaction']);
    $r_dissatisfaction=htmlspecialchars($row['r_dissatisfaction']);
    $r_conflicts=htmlspecialchars($row['r_conflicts']);
    $r_depends=htmlspecialchars($row['r_depends']);
    $r_component=$row['r_component'];    
    $r_source=htmlspecialchars($row['r_source']);    
    $r_risk=$row['r_risk'];    
    $r_complexity=$row['r_complexity'];    
    $r_weight=$row['r_weight'];    
    $r_points=$row['r_points'];    
    $r_stub=$row['r_stub'];    
    $r_keywords=$row['r_keywords'];    
    $r_parent_id=htmlspecialchars($row['r_parent_id']);
    $r_pos=htmlspecialchars($row['r_pos']);
    $r_creation_date=$row['d1'];
    $r_change_date=$row['d2'];
    $r_accept_date=$row['d3'];
    $r_accept_user=$row['r_accept_user'];    
    $r_version=$row['r_version'];    
    $p_status=$row['p_status'];    
    $r_userfield1=$row['r_userfield1'];    
    $r_userfield2=$row['r_userfield2'];    
    $r_userfield3=$row['r_userfield3'];    
    $r_userfield4=$row['r_userfield4'];    
    $r_userfield5=$row['r_userfield5'];    
    $r_userfield6=$row['r_userfield6'];    
    $p_req_del=$row['p_req_del'];    
   }
 } 
if ($r_pos=="") $r_pos=0;
if ($r_p_id=="") $r_p_id=0;
if ($r_p_id_tmp=="") $r_p_id_tmp=0;
if ($r_s_id_tmp!="") $r_s_id=$r_s_id_tmp;
if ($r_assigned_u_id_tmp!="") $r_assigned_u_id=$r_assigned_u_id_tmp;
?>
<?if ($tmp!="") echo $tmp;?>
<table border="0" width="100%">
  <tr valign="top">
    <td>
      <form method="post" name="edit" name="edit" action="" enctype='multipart/form-data'>
      <input type="hidden" name="r_id" value="<?=$r_id?>">
      <input type="hidden" name="ref" value="<?=$ref?>">
      <input type="hidden" name="r_s_id_tmp" value="">
      <input type="hidden" name="r_assigned_u_id_tmp" value="">
      <input type="hidden" name="tmp_p_id" value="">
	<table border="0" cellpadding="2" cellspacing="2" class="content" width="100%">
	  <tr class="gray">
	    <td align="left"  title="<?=$lng[15][96]?>">
	      <?if ($r_id=="") {?>
	        <?if ($stub==0 || $stub=="") {?>
	          <input type="button" value="<?=$lng[15][94]?>" onclick="document.location.href='index.php?inc=edit_requirement&stub=1'">&nbsp;
	          <input type="button" value="<?=$lng[15][105]?>" onclick="document.location.href='index.php?inc=edit_requirement&stub=2'">
	        <?}elseif ($stub==1) {?>
	          <input type="button" value="<?=$lng[15][95]?>" onclick="document.location.href='index.php?inc=edit_requirement&stub=0'">&nbsp;
	          <input type="button" value="<?=$lng[15][105]?>" onclick="document.location.href='index.php?inc=edit_requirement&stub=2'">
	        <?}elseif ($stub==2) {?>
	          <input type="button" value="<?=$lng[15][95]?>" onclick="document.location.href='index.php?inc=edit_requirement&stub=0'">&nbsp;
	          <input type="button" value="<?=$lng[15][94]?>" onclick="document.location.href='index.php?inc=edit_requirement&stub=1'">
	        <?}?>
	      <?}?>
	    </td>
	    <td align="center"><b><?=($r_id=="")?$lng[15][2]:$lng[15][1]?></b></td>
	  </tr>
	  <tr class="blue" valign="top" title="<?=$lng[15][51]?>">
	    <td align="right" nowrap>&nbsp;<?=$lng[15][3]?>&nbsp;:&nbsp;</td>
	    <td>
	      <select name="r_p_id" onchange="change_select();document.forms['edit'].tmp_p_id.value=this.value;document.forms['edit'].submit();">
	        <option value=''><?=$lng[15][28]?>
	        <?
	        //projects list
	        //if retired getting the project query from top.php
	        if ($p_status==2) $query_project2=$query_project;
	        else
	         {
	          if ($_SESSION['rights']=="") $query_project2="select p_name, p_id from projects where p_status=1 order by p_name asc";
		  elseif ($_SESSION['rights']=="0" || $_SESSION['rights']=="1" || $_SESSION['rights']=="2" || $_SESSION['rights']=="3") $query_project2="select distinct(p.p_id), p.p_name from projects p left outer join project_users pu on p.p_id=pu.pu_p_id where ((pu.pu_u_id=".$_SESSION['uid']." and p.p_status<>2) or p.p_status=1) order by p.p_name asc";
		  elseif ($_SESSION['rights']=="4") $query_project2="select distinct(p.p_id), p.p_name from projects p left outer join project_users pu on p.p_id=pu.pu_p_id where (((pu.pu_u_id=".$_SESSION['uid']." or p_leader=".$_SESSION['uid'].") and p.p_status<>2) or  p.p_status=1) order by p_name asc";
		  else $query_project2="select p_name, p_id from projects where p_status<>2 order by p_name asc";
                 }
                $rs2 = mysql_query($query_project2) or die(mysql_error());
	        if ($tmp_p_id=="")
	         {
	          if ($r_p_id!="") $tmp_p_id=$r_p_id;
	          elseif ($_SESSION['projects']!="") $tmp_p_id=$_SESSION['projects'];
		 }
		  
	        while($row2=mysql_fetch_array($rs2)) 
		 {
		  if ($tmp_p_id==$row2['p_id']) echo "<option value='".$row2['p_id']."' selected>".htmlspecialchars($row2['p_name']);
		  else echo "<option value='".$row2['p_id']."'>".htmlspecialchars($row2['p_name']);
		 }
	        ?>
      	      </select> 
      	      &nbsp;<?if ($p_status==2 && $r_p_id_tmp!=$tmp_p_id) {?><input type="button" value="<?=$lng[15][75]?>" onclick="document.location.href='index.php?inc=edit_requirement&r_id=<?=$r_id?>&action=clone&tmp_pr=<?=$tmp_p_id?>'"><?}?>
	    </td>
	  </tr>
	  <?if (!($stub==2 || $r_stub==2)) {?>
	  <tr class="light_blue" valign="top" title="<?=$lng[15][98]?>">
	    <td align="right" nowrap>&nbsp;<?=$lng[15][97]?>&nbsp;:&nbsp;</td>
	    <td>
	      <select name="r_s_id">
	      <option value='0'>--
	    <?
	    $query2="select * from subprojects where s_p_id='".$tmp_p_id."' order by s_name asc";
	    $rs2 = mysql_query($query2) or die(mysql_error());
	    while($row2=mysql_fetch_array($rs2))
	     {
	      if ($r_s_id==$row2['s_id']) echo "<option value='".$row2['s_id']."' selected>".htmlspecialchars($row2['s_name']);
	      else echo "<option value='".$row2['s_id']."'>".htmlspecialchars($row2['s_name']);
	     }
	    ?> 
	      </select>
	    <?if (!($r_p_id=="" || $r_p_id=="0") && $_SESSION['rights']=="5")  {?>
	      <input type="button" value="<?=$lng[99][30]?>" onclick="newwin=window.open('popup_subproject.php?p_id=<?=$r_p_id?>','name','height=470,width=700');">
	    <?}?>
	    </td>
	  </tr>
	  <tr class="blue" valign="top" title="<?=$lng[15][52]?>">
	    <td align="right" nowrap>&nbsp;<?=$lng[15][24]?>&nbsp;:&nbsp;</td>
	    <td>
	    <select name="r_release_tmp" multiple>
	    <?
	    $query2="select r.*, date_format(r.r_date, '%d.%m.%Y') as d1, date_format(r.r_released_date, '%d.%m.%Y') as d2 from releases r left outer join project_releases pr on pr.pr_r_id=r.r_id where (pr.pr_p_id='".$tmp_p_id."' or r.r_global=1) order by r.r_name asc";
	    $rs2 = mysql_query($query2) or die(mysql_error());
	    while($row2=mysql_fetch_array($rs2))
	     {
	      if (strstr(",".$r_release,",".$row2['r_id'].",")) echo "<option value='".$row2['r_id']."' selected>".htmlspecialchars($row2['r_name']);
	      else echo "<option value='".$row2['r_id']."'>".htmlspecialchars($row2['r_name']);
	     }
	    ?>
      	    </select> 
	    <?if (!($r_p_id=="" || $r_p_id=="0") && ($_SESSION['rights']=="3" || $_SESSION['rights']=="4" || $_SESSION['rights']=="5"))  {?>
	      <input type="button" value="<?=$lng[99][30]?>" onclick="newwin=window.open('popup_release.php?p_id=<?=$r_p_id?>','name','height=230,width=400');">
	    <?}?>
	    </td>
	  </tr>
	  <tr class="light_blue" title="<?=$lng[15][104]?>">
	    <td align="right" nowrap>&nbsp;<?=$lng[15][103]?>&nbsp;:&nbsp;</td>
	    <td>
	      <select name="r_c_id_tmp" multiple>
	        <?
	        //test cases list
	        $query2="select c.* from cases c left outer join project_cases pc on c.c_id=pc.pc_c_id where (pc.pc_p_id='".$tmp_p_id."' or c.c_global=1) order by c.c_name asc";
	        $rs2 = mysql_query($query2) or die(mysql_error());
	        while($row2=mysql_fetch_array($rs2)) 
		 {
		  if (strstr(",".$r_c_id,",".$row2['c_id'].",")) echo "<option value='".$row2['c_id']."' selected>".htmlspecialchars($row2['c_name']);
		  else echo "<option value='".$row2['c_id']."'>".htmlspecialchars($row2['c_name']);
		 }
	        ?>
      	      </select> 
	    <?if (!($r_p_id=="" || $r_p_id=="0") && ($_SESSION['rights']=="1" || $_SESSION['rights']=="2" || $_SESSION['rights']=="3" || $_SESSION['rights']=="4" || $_SESSION['rights']=="5"))  {?>
	      <input type="button" value="<?=$lng[99][30]?>" onclick="newwin=window.open('popup_testcase.php?p_id=<?=$r_p_id?>','name','scrollbars=yes,height=640,width=700');">
	    <?}?>
	    </td>
	  </tr>
	  <tr class="blue" valign="top" title="<?=$lng[15][77]?>">
	    <td align="right" nowrap>&nbsp;<?=$lng[15][76]?>&nbsp;:&nbsp;</td>
	    <td>
	    <select name="r_stakeholder_tmp" multiple>
	    <?
	    $query2="select s.* from stakeholders s left outer join project_stakeholders ps on s.s_id=ps.ps_s_id where (ps.ps_p_id='".$tmp_p_id."' or s.s_global=1) order by s.s_name asc";
	    //$query2="select s.* from project_stakeholders ps left outer join stakeholders s on ps.ps_s_id=s.s_id where ps.ps_p_id='".$tmp_p_id."' order by s.s_name asc";
	    $rs2 = mysql_query($query2) or die(mysql_error());
	    while($row2=mysql_fetch_array($rs2))
	     {
	      if (strstr(",".$r_stakeholder,",".$row2['s_id'].",")) echo "<option value='".$row2['s_id']."' selected>".htmlspecialchars($row2['s_name']);
	      else echo "<option value='".$row2['s_id']."'>".htmlspecialchars($row2['s_name']);
	     }
	    ?>
	    </select> 
	    <?if (!($r_p_id=="" || $r_p_id=="0") && $_SESSION['rights']=="5")  {?>
	      <input type="button" value="<?=$lng[99][30]?>" onclick="newwin=window.open('popup_stakeholder.php?p_id=<?=$r_p_id?>','name','height=340,width=700');">
	    <?}?>
	    </td>
	  </tr>
	  <tr class="light_blue" valign="top" title="<?=$lng[15][87]?>">
	    <td align="right" nowrap>&nbsp;<?=$lng[15][86]?>&nbsp;:&nbsp;</td>
	    <td>
	    <select name="r_glossary_tmp" multiple>
	    <?
	    $query26="select g.* from glossary g left outer join project_glossary pg on g.g_id=pg.pg_g_id where (pg.pg_p_id='".$tmp_p_id."' or g.g_global=1) order by g.g_id asc";
	    $rs26 = mysql_query($query26) or die(mysql_error());
	    while($row26=mysql_fetch_array($rs26))
	     {
	      $tmp_g="";
	      for ($i=0;$i<6-strlen($row26['g_id']);$i++) $tmp_g.="0";$tmp_g.=$row26['g_id'];
	      if (strstr(",".$r_glossary,",".$row26['g_id'].",")) echo "<option value='".$row26['g_id']."' selected>".htmlspecialchars($row26['g_abbreviation'])." ".htmlspecialchars($row26['g_term']);
	      else echo "<option value='".$row26['g_id']."'>".htmlspecialchars($row26['g_abbreviation'])." ".htmlspecialchars($row26['g_term']);
	     }
	    ?>
	    </select> 
	    <?if (!($r_p_id=="" || $r_p_id=="0") && $_SESSION['rights']=="5")  {?>
	      <input type="button" value="<?=$lng[99][30]?>" onclick="newwin=window.open('popup_glossary.php?p_id=<?=$r_p_id?>','name','height=600,width=900');">
	    <?}?>
	    </td>
	  </tr>
	  <?}?>
	  <tr class="blue" title="<?=$lng[15][53]?>">
	    <td align="right" nowrap>&nbsp;<?=$lng[15][4]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;<input type="text" name="r_name" value="<?=$r_name?>" maxlength="90" size=90></td>
	  </tr>  
	  <tr class="light_blue" title="<?=$lng[15][54]?>">
	    <td align="right" nowrap>&nbsp;<?=$lng[15][35]?>&nbsp;:&nbsp;</td>
	    <td>
	      <select name="r_parent_id">
	        <option value='0'><?=$lng[15][37]?>
	        <?
	        //getting list of childs in order to prevent endless cycle
	        if ($r_id!="")
	         {
	          //getting tree array
      		  $query="select * from requirements where r_parent_id=".$r_id." order by r_pos asc";
      		  $rs = mysql_query($query) or die(mysql_error());
      		  $cnt3=0;
      		  while($row=mysql_fetch_array($rs)) 
       		   {
        	    $cnt3++;
        	    $arr_[]=$cnt3."|".$row['r_id'];
        	    if (checkTree($r_id)!=-1) getTree2($row['r_id'],$cnt3,$arr_);
      		   }
                  $arrs="0";
      		  while ($cnt3>0 && list ($key, $val) = each ($arr_)) $arrs.=",".substr($val,strpos($val,"|")+1);
     		 }
	        
	        //requirements list
	        if ($r_id!="") $add_q.=" and r_id not in (".$arrs.") and r_id<>".$r_id;
	        if ($tmp_p_id!="") $add_q.=" and r_p_id=".$tmp_p_id;
	        $query2="select r_name, r_id from requirements where 1=1 ".$add_q." order by r_name asc";
		
		$rs2 = mysql_query($query2) or die(mysql_error());
	        while($row2=mysql_fetch_array($rs2)) 
		 {
		  if ($r_parent_id==$row2['r_id']) echo "<option value='".$row2['r_id']."' selected>".htmlspecialchars($row2['r_name']);
		  else echo "<option value='".$row2['r_id']."'>".htmlspecialchars($row2['r_name']);
		 }
	        ?>
      	      </select>
      	      <input type="hidden" name="r_parent_id_tmp" value="<?=$r_parent_id?>">
	    </td>
	  </tr>
	  <tr class="blue" title="<?=$lng[15][55]?>">
	    <td align="right" nowrap>&nbsp;<?=$lng[15][36]?>&nbsp;:&nbsp;</td>
	    <td>
	      <select name="r_pos">
	        <?
	        //position in requirements tree
	        if ($r_parent_id!="")
	         {
	          $query2="select count(*) from requirements where r_parent_id=".$r_parent_id." and r_p_id=".$tmp_p_id;
		  $rs2 = mysql_query($query2) or die(mysql_error());
	          if($row2=mysql_fetch_array($rs2)) $pos_cnt=$row2[0];
		  for ($i=1;$i<$pos_cnt+1;$i++)
		   {
		    if ($r_pos==$i) echo "<option value='".$i."' selected>".$i;
		    else echo "<option value='".$i."'>".$i;
		   }
		 }  
	        ?>
      	      </select> 
      	      <input type="hidden" name="r_pos_tmp" value="<?=$r_pos?>">
	    </td>
	  </tr> 
	  <?if (!($stub=="1" || $stub=="2" || $r_stub=="2")) {?>
	  <tr class="light_blue" title="<?=$lng[15][56]?>">
	    <td align="right" nowrap>&nbsp;<?=$lng[15][30]?>&nbsp;:&nbsp;</td>
	    <td>
	      <select name="r_assigned_u_id">
	        <option value=''>--
	        <?
	          //users list
	        
	          $query4="select u_name, u_id from users where u_rights in (0,1,2,3,4,5) order by u_name asc";
		  $rs4 = mysql_query($query4) or die(mysql_error());
	        
	          while($row4=mysql_fetch_array($rs4)) 
		   {
		    if ($row4['u_id']==$r_assigned_u_id) echo "<option value='".$row4['u_id']."' selected>".htmlspecialchars($row4['u_name']);
		    else echo "<option value='".$row4['u_id']."'>".htmlspecialchars($row4['u_name']);
		   }
	        ?>
      	      </select>
	    <?if (!($r_p_id=="" || $r_p_id=="0") && $_SESSION['rights']=="5")  {?>
	      <input type="button" value="<?=$lng[99][30]?>" onclick="newwin=window.open('popup_users.php?p_id=<?=$r_p_id?>','name','height=300,width=700');">
	    <?}?>
	    </td>
	  </tr>
	  <?}?>
	  <?if ($stub!="1") {?>
	  <tr class="blue" valign="top" title="<?=$lng[15][57]?>">
	    <td align="right" nowrap>&nbsp;<?=$lng[15][5]?>&nbsp;:&nbsp;</td>
	    <td align="left"><? 
		include("FCKeditor/fckeditor.php");
		$oFCKeditor = new FCKeditor('ta') ;
		$oFCKeditor->BasePath = 'FCKeditor/' ;
		$oFCKeditor->Value = $ta;
		$oFCKeditor->Width = '760';
		$oFCKeditor->Height = '400';
		$oFCKeditor->Create() ;
		?> 
	    </td>
	  </tr> 
	  <?}?>
	  <?if ($r_id!=""){?>
	  <tr class="blue" title="<?=$lng[15][91]?>">
	    <td align="right" nowrap>&nbsp;<?=$lng[15][90]?>&nbsp;:&nbsp;</td>
	    <td>
	       <select name="r_stub">
		<option value="0"><?=$lng[15][92]?>
		<option value="1"><?=$lng[15][93]?>		
		<option value="2"><?=$lng[15][106]?>		
	       </select>
	       <script>
		 for (i=0;i<document.forms.edit.r_stub.length;i++)
		   if (document.forms.edit.r_stub.options[i].value=='<?=$r_stub?>')
		      document.forms.edit.r_stub.options[i].selected=true;
	       </script>	       	    
	    </td>
	  </tr>  
	  <?}?>
	  <?if (!($stub=="1" || $stub=="2" || $r_stub=="2")) {?>
	  <?
	  $bg="";$cnt_u=0;
	  $query41="select * from user_fields order by uf_id asc";
	  $rs41=mysql_query($query41) or die(mysql_error());
	  while($row41=mysql_fetch_array($rs41))
	   {
	    $cnt_u++;
	    $tmp_var="r_userfield".$cnt_u;
	    $r_userfield=htmlspecialchars($$tmp_var);
	    if ($row41['uf_name_en']!="")
	     {
	      $uf_name=htmlspecialchars($row41['uf_name_'.$_SESSION['chlang']]);
	      $uf_text=htmlspecialchars($row41['uf_text_'.$_SESSION['chlang']]);
	      $uf_values=htmlspecialchars($row41['uf_values']);
	      if ($row41['uf_type']==0)
	       {
	        if ($bg=="light_") $bg="";
	        else $bg="light_";	  
	      ?>
		  <tr class="<?=$bg?>blue" title="<?=$uf_text?>">
		    <td align="right" nowrap>&nbsp;<?=$uf_name?>&nbsp;:&nbsp;</td>
		    <td>&nbsp;<input type="text" name="r_userfield<?=$cnt_u?>" value="<?=$r_userfield?>" maxlength="90" size=90></td>
		  </tr>  
	     <?}
	      else
	       {
	      ?>
		  <tr class="blue" title="<?=$uf_text?>">
		    <td align="right" nowrap>&nbsp;<?=$uf_name?>&nbsp;:&nbsp;</td>
		    <td>
		      <select name="r_userfield<?=$cnt_u?>">
		        <?
		        $val_arr = explode(",", $uf_values);
		        while (list ($key, $val) = each ($val_arr)) 
		         {
			  ?>
			  <option value="<?=$val?>" <?if ($val==$r_userfield) echo "selected";?>><?=$val?>
			  <?
			 }
		        ?>
		      </select>
		    </td>
		  </tr>  
	     <?}?>
	   <?}?>
	 <?}?>
	  <tr class="light_blue" title="<?=$lng[15][58]?>">
	    <td align="right" nowrap>&nbsp;<?=$lng[15][10]?>&nbsp;:&nbsp;</td>
	    <td>
	    <?include("ini/txts/".$_SESSION['chlang']."/state.php");?>
	    <?if ($r_id!="") {?>
	       <select name="r_state">
		 <?
		 if ($_SESSION['rights']=="2" || $_SESSION['rights']=="3") echo makeSelect($state_array,$r_state);
		 else echo makeSelect($state_array2,$r_state);
		 ?>
	       </select>
	       <script>
		 for (i=0;i<document.forms.edit.r_state.length;i++)
		   if (document.forms.edit.r_state.options[i].value=='<?=$r_state?>')
		      document.forms.edit.r_state.options[i].selected=true;
	       </script>	    
	       <input type="hidden" name="r_state_old" value="<?=$r_state_old?>">
	    </td>
	    <?}else 
	        {
	         if ($_SESSION['rights']=="2" || $_SESSION['rights']=="3") echo $state_array[0];
		 else echo $state_array2[0];
	        }
	        
	    
	    ?>
	  </tr>  
	  <tr class="blue" title="<?=$lng[15][59]?>">
	    <td align="right" nowrap>&nbsp;<?=$lng[15][11]?>&nbsp;:&nbsp;</td>
	    <td>
	       <select name="r_type_r">
		 <?include("ini/txts/".$_SESSION['chlang']."/type.php");
		   echo makeSelect($type_array,$r_type_r);?>
	       </select>
	       <script>
		 for (i=0;i<document.forms.edit.r_type_r.length;i++)
		   if (document.forms.edit.r_type_r.options[i].value=='<?=$r_type_r?>')
		      document.forms.edit.r_type_r.options[i].selected=true;
	       </script>
	       	    
	    </td>
	  </tr>  
	  <tr class="light_blue" title="<?=$lng[15][60]?>">
	    <td align="right" nowrap>&nbsp;<?=$lng[15][13]?>&nbsp;:&nbsp;</td>
	    <td>
	       <select name="r_priority">
		 <option value='1'>1 <?=$lng[15][102]?>
		 <option value='2'>2
		 <option value='3'>3
		 <option value='4'>4
		 <option value='5'>5
		 <option value='6'>6
		 <option value='7'>7
		 <option value='8'>8
		 <option value='9'>9
		 <option value='10'>10 <?=$lng[15][101]?>
	       </select>
	       <script>
		 for (i=0;i<document.forms.edit.r_priority.length;i++)
		   if (document.forms.edit.r_priority.options[i].value=='<?=$r_priority?>')
		      document.forms.edit.r_priority.options[i].selected=true;
	       </script>	    
	    </td>
	  </tr> 
	  <tr class="blue" title="<?=$lng[15][83]?>">
	    <td align="right" nowrap>&nbsp;<?=$lng[15][82]?>&nbsp;:&nbsp;</td>
	    <td>
	       <select name="r_satisfaction">
		 <option value='5'>5
		 <option value='4'>4
		 <option value='3'>3
		 <option value='2'>2
		 <option value='1'>1
		 <option value='0'>0
	       </select>
	       <script>
		 for (i=0;i<document.forms.edit.r_satisfaction.length;i++)
		   if (document.forms.edit.r_satisfaction.options[i].value=='<?=$r_satisfaction?>')
		      document.forms.edit.r_satisfaction.options[i].selected=true;
	       </script>	    
	    </td>
	  </tr> 
	  <tr class="light_blue" title="<?=$lng[15][85]?>">
	    <td align="right" nowrap>&nbsp;<?=$lng[15][84]?>&nbsp;:&nbsp;</td>
	    <td>
	       <select name="r_dissatisfaction">
		 <option value='5'>5
		 <option value='4'>4
		 <option value='3'>3
		 <option value='2'>2
		 <option value='1'>1
		 <option value='0'>0
	       </select>
	       <script>
		 for (i=0;i<document.forms.edit.r_dissatisfaction.length;i++)
		   if (document.forms.edit.r_dissatisfaction.options[i].value=='<?=$r_dissatisfaction?>')
		      document.forms.edit.r_dissatisfaction.options[i].selected=true;
	       </script>	    
	    </td>
	  </tr> 
	  <tr class="blue" title="<?=$lng[15][79]?>">
	    <td align="right" nowrap>&nbsp;<?=$lng[15][78]?>&nbsp;:&nbsp;</td>
	    <td>
	      <select name="r_depends_tmp" multiple>
	        <?
	        //requirements list from the same project
	        if ($tmp_p_id!="") 
	         {
	          if ($r_id!="") $query2="select r_name, r_id from requirements where r_id<>".$r_id." and r_p_id=".$tmp_p_id." order by r_name asc";
	          else $query2="select r_name, r_id from requirements where r_p_id=".$tmp_p_id." order by r_name asc";
		  $rs2 = mysql_query($query2) or die(mysql_error());
	          while($row2=mysql_fetch_array($rs2)) 
		   {
		    if (strstr(",".$r_depends,",".$row2['r_id'].",")) echo "<option value='".$row2['r_id']."' selected>".htmlspecialchars($row2['r_name']);
		    else echo "<option value='".$row2['r_id']."'>".htmlspecialchars($row2['r_name']);
		   }
		 } 
	        ?>
      	      </select> 
	    </td>
	  </tr>
	  <tr class="light_blue" title="<?=$lng[15][81]?>">
	    <td align="right" nowrap>&nbsp;<?=$lng[15][80]?>&nbsp;:&nbsp;</td>
	    <td>
	      <select name="r_conflicts_tmp" multiple>
	        <?
	        //requirements list from the same project
	        if ($tmp_p_id!="") 
	         {
	          if ($r_id!="") $query2="select r_name, r_id from requirements where r_id<>".$r_id." and r_p_id=".$tmp_p_id." order by r_name asc";
	          else $query2="select r_name, r_id from requirements where r_p_id=".$tmp_p_id." order by r_name asc";
		  $rs2 = mysql_query($query2) or die(mysql_error());
	          while($row2=mysql_fetch_array($rs2)) 
		   {
		    if (strstr(",".$r_conflicts,",".$row2['r_id'].",")) echo "<option value='".$row2['r_id']."' selected>".htmlspecialchars($row2['r_name']);
		    else echo "<option value='".$row2['r_id']."'>".htmlspecialchars($row2['r_name']);
		   }
		 } 
	        ?>
      	      </select> 
	    </td>
	  </tr>
	  <tr class="blue" title="<?=$lng[15][61]?>">
	    <td align="right" nowrap>&nbsp;<?=$lng[15][14]?>&nbsp;:&nbsp;</td>
	    <td><input type="text" name="r_link" value="<?=$r_link?>" size=90><br/><?=$lng[15][46]?></td>
	  </tr> 
	  <tr class="light_blue" title="<?=$lng[15][62]?>">
	    <td align="right" nowrap>&nbsp;<?=$lng[15][40]?>&nbsp;:&nbsp;</td>
	    <td>
	    <select name="r_component_tmp" multiple>
	    <?
	    $query2="select c.* from components c left outer join project_components pco on c.c_id=pco.pco_c_id where (pco.pco_p_id='".$tmp_p_id."' or c.c_global=1 ) order by c.c_name asc";
	    $rs2 = mysql_query($query2) or die(mysql_error());
	    while($row2=mysql_fetch_array($rs2))
	     {
	      if (strstr(",".$r_component,",".$row2['c_id'].",")) echo "<option value='".$row2['c_id']."' selected>".htmlspecialchars($row2['c_name']);
	      else echo "<option value='".$row2['c_id']."'>".htmlspecialchars($row2['c_name']);
	     }
	    ?>
	    </select> 
	    <?if (!($r_p_id=="" || $r_p_id=="0") && ($_SESSION['rights']=="1" || $_SESSION['rights']=="2" || $_SESSION['rights']=="3" || $_SESSION['rights']=="4" || $_SESSION['rights']=="5"))  {?>
	      <input type="button" value="<?=$lng[99][30]?>" onclick="newwin=window.open('popup_component.php?p_id=<?=$r_p_id?>','name','height=170,width=700');">
	    <?}?>
	    </td>
	  </tr>  
	  <tr class="blue" title="<?=$lng[15][63]?>">
	    <td align="right" nowrap>&nbsp;<?=$lng[15][41]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;<input type="text" name="r_source" value="<?=$r_source?>" maxlength="90" size=90></td>
	  </tr> 
	  <tr class="light_blue" title="<?=$lng[15][64]?>">
	    <td align="right" nowrap>&nbsp;<?=$lng[15][42]?>&nbsp;:&nbsp;</td>
	    <td>
	       <select name="r_risk">
		 <option value='0'>--
		 <?include("ini/txts/".$_SESSION['chlang']."/risk.php");
		   echo makeSelect($risk_array,$r_risk);?>
	       </select>
	       <script>
		 for (i=0;i<document.forms.edit.r_risk.length;i++)
		   if (document.forms.edit.r_risk.options[i].value=='<?=$r_risk?>')
		      document.forms.edit.r_risk.options[i].selected=true;
	       </script>	       	    
	    </td>
	  </tr>  
	  <tr class="blue" title="<?=$lng[15][65]?>">
	    <td align="right" nowrap>&nbsp;<?=$lng[15][43]?>&nbsp;:&nbsp;</td>
	    <td>
	       <select name="r_complexity">
		 <option value='0'>--
		 <?include("ini/txts/".$_SESSION['chlang']."/complexity.php");
		   echo makeSelect($complexity_array,$r_complexity);?>
	       </select>
	       <script>
		 for (i=0;i<document.forms.edit.r_complexity.length;i++)
		   if (document.forms.edit.r_complexity.options[i].value=='<?=$r_complexity?>')
		      document.forms.edit.r_complexity.options[i].selected=true;
	       </script>	       	    
	    </td>
	  </tr>  
	  <tr class="light_blue" title="<?=$lng[15][89]?>">
	    <td align="right" nowrap>&nbsp;<?=$lng[15][88]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;<input type="text" name="r_weight" value="<?=$r_weight?>" maxlength="10" size=2></td>
	  </tr>  
	  <tr class="blue" title="<?=$lng[15][66]?>">
	    <td align="right" nowrap>&nbsp;<?=$lng[15][44]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;<textarea name="r_points" rows=4 cols=92><?=$r_points?></textarea></td>
	  </tr> 
	  <tr class="light_blue" title="<?=$lng[15][108]?>">
	    <td align="right" nowrap>&nbsp;<?=$lng[15][107]?>&nbsp;:&nbsp;</td>
	    <td>
	      <select name="r_keywords_tmp" multiple>
	        <?
	        //keywords list
	        $query2="select k.* from keywords k left outer join project_keywords pk on k.k_id=pk.pk_k_id where (pk.pk_p_id='".$tmp_p_id."' or k.k_global=1 ) order by k.k_name asc";
		$rs2 = mysql_query($query2) or die(mysql_error());
	        while($row2=mysql_fetch_array($rs2)) 
		 {
		  if (strstr(",".$r_keywords,",".$row2['k_id'].",")) echo "<option value='".$row2['k_id']."' selected>".htmlspecialchars($row2['k_name']);
		  else echo "<option value='".$row2['k_id']."'>".htmlspecialchars($row2['k_name']);
		 } 
	        ?>
      	      </select> 
	    <?if (!($r_p_id=="" || $r_p_id=="0") && ($_SESSION['rights']=="1" || $_SESSION['rights']=="2" || $_SESSION['rights']=="3" || $_SESSION['rights']=="4" || $_SESSION['rights']=="5"))  {?>
	      <!--input type="button" value="<?=$lng[99][30]?>" onclick="newwin=window.open('popup_keyword.php?p_id=<?=$r_p_id?>','name','height=150,width=700');"-->
	    <?}?>
	    </td>
	  </tr>
	  <tr class="light_blue" title="<?=$lng[15][111]?>">
	    <td align="right" nowrap>&nbsp;<?=$lng[15][110]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;<input type="text" name="new_keywords" value="" maxlength="90" size=90><br><?=$lng[15][112]?></td>
	  </tr> 
	  <?if ($r_id!=""){?>
	  <tr class="blue" title="<?=$lng[15][67]?>">
	    <td align="right" nowrap>&nbsp;<?=$lng[15][45]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;<?=$r_id?></td>
	  </tr>  	  
	  <tr class="light_blue" title="<?=$lng[15][68]?>">
	    <td align="right" nowrap>&nbsp;<?=$lng[15][16]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;<?=$author?></td>
	  </tr>  	  
	  <tr class="blue" title="<?=$lng[15][69]?>">
	    <td align="right" nowrap>&nbsp;<?=$lng[15][34]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;<?=$r_version?></td>
	  </tr>  	  
	  <tr class="light_blue" title="<?=$lng[15][70]?>">
	    <td align="right" nowrap>&nbsp;<?=$lng[15][17]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;<?=($r_creation_date=="00.00.0000 00:00")?"-":$r_creation_date?></td>
	  </tr>  	  
	  <tr class="blue" title="<?=$lng[15][71]?>">
	    <td align="right" nowrap>&nbsp;<?=$lng[15][18]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;<?=($r_change_date=="00.00.0000 00:00")?"-":$r_change_date?></td>
	  </tr>  	  
	  <tr class="light_blue" title="<?=$lng[15][72]?>">
	    <td align="right" nowrap>&nbsp;<?=$lng[15][19]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;<?=($r_accept_date=="00.00.0000 00:00")?"-":$r_accept_date?></td>
	  </tr>  	  
	  <tr class="blue" title="<?=$lng[15][73]?>">
	    <td align="right" nowrap>&nbsp;<?=$lng[15][20]?>&nbsp;:&nbsp;</td>
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
	  <?}?>	 
	  
	  <?}?>	  	  
	  <tr class="gray">
	    <td colspan="2" align="center">
	       <?if (1==2) {?><input type="button" onclick="sub('update');" value="<?=$lng[15][21]?>">&nbsp;&nbsp;<input type="button" onclick="if (confirm('<?=$lng[15][23]?>')) sub('delete');" value="<?=$lng[15][22]?>"><?}?>
	       <?if ($r_id!="") {?><input type="button" onclick="sub('update');" value="<?=$lng[15][21]?>"><?}?>
	       <?if ($r_id!="" && $p_req_del=="1" && ($_SESSION['rights']=="2" || $_SESSION['rights']=="3" || $_SESSION['rights']=="4" || $_SESSION['rights']=="5")) {?><input type="button" onclick="if (confirm('<?=$lng[15][23]?>')) sub('delete');" value="<?=$lng[15][22]?>"><?}?>
	       <?if ($r_id=="") {?><input type="button" onclick="sub('add');" value="<?=$lng[15][6]?>"><?}?>
	    </td>
	  </tr>   
	</table>
      <input type="hidden" name="stub" value="<?=$stub?>">
      <input type="hidden" name="p_status" value="<?=$p_status?>">
      <input type="hidden" name="r_p_id_tmp" value="<?=$r_p_id_tmp?>">
      <input type="hidden" name="inc" value="edit_requirement">
      <input type="hidden" name="r_conflicts" value="">	
      <input type="hidden" name="r_depends" value="">	
      <input type="hidden" name="r_keywords" value="">	
      <input type="hidden" name="r_component" value="">	
      <input type="hidden" name="r_c_id" value="">	
      <input type="hidden" name="r_stakeholder" value="">	
      <input type="hidden" name="r_release" value="">	
      <input type="hidden" name="r_glossary" value="">	
      <input type="hidden" name="action" value="">	
      </form>	
    </td> 	 
  </tr>
</table>



<script>
function sub(what)
 {
  df=document.forms['edit'];
  if (what!="delete") 
   {
    /*if (df.r_p_id.value=="") 
     {
      alert("<?=$lng[15][9]?>");
      df.r_p_id.focus();	
      return false;
     } */
    if (df.r_name.value=="") 
     {
      alert("<?=$lng[15][7]?>");
      df.r_name.focus();	
      return false;
     } 
    <?if (!($stub=="1" || $stub=="2" || $r_stub=="2")) {?>  
    if (isNaN(df.r_weight.value) || df.r_weight.value<0) 
     {
      alert("<?=$lng[15][109]?>");
      df.r_weight.focus();	
      return false;
     } 
    <?}?> 
   }
   
  <?if (!($stub=="1" || $stub=="2" || $r_stub=="2")) {?> 
  df.elements.r_c_id.value="";
  for (i=0;i<df.elements.r_c_id_tmp.options.length;i++)
   {	
    if (df.elements.r_c_id_tmp.options[i].selected) df.elements.r_c_id.value+=df.elements.r_c_id_tmp.options[i].value+",";	
   }
  df.elements.r_stakeholder.value="";
  for (i=0;i<df.elements.r_stakeholder_tmp.options.length;i++)
   {	
    if (df.elements.r_stakeholder_tmp.options[i].selected) df.elements.r_stakeholder.value+=df.elements.r_stakeholder_tmp.options[i].value+",";	
   }
  df.elements.r_release.value="";
  for (i=0;i<df.elements.r_release_tmp.options.length;i++)
   {	
    if (df.elements.r_release_tmp.options[i].selected) df.elements.r_release.value+=df.elements.r_release_tmp.options[i].value+",";	
   }
  df.elements.r_glossary.value="";
  for (i=0;i<df.elements.r_glossary_tmp.options.length;i++)
   {	
    if (df.elements.r_glossary_tmp.options[i].selected) df.elements.r_glossary.value+=df.elements.r_glossary_tmp.options[i].value+",";	
   }
  df.elements.r_keywords.value="";
  for (i=0;i<df.elements.r_keywords_tmp.options.length;i++)
   {	
    if (df.elements.r_keywords_tmp.options[i].selected) df.elements.r_keywords.value+=df.elements.r_keywords_tmp.options[i].value+",";	
   }
  df.elements.r_component.value="";
  for (i=0;i<df.elements.r_component_tmp.options.length;i++)
   {	
    if (df.elements.r_component_tmp.options[i].selected) df.elements.r_component.value+=df.elements.r_component_tmp.options[i].value+",";	
   }
  df.elements.r_depends.value="";
  for (i=0;i<df.elements.r_depends_tmp.options.length;i++)
   {	
    if (df.elements.r_depends_tmp.options[i].selected) df.elements.r_depends.value+=df.elements.r_depends_tmp.options[i].value+",";	
   }
  df.elements.r_conflicts.value="";
  for (i=0;i<df.elements.r_conflicts_tmp.options.length;i++)
   {	
    if (df.elements.r_conflicts_tmp.options[i].selected) df.elements.r_conflicts.value+=df.elements.r_conflicts_tmp.options[i].value+",";	
   }
  df.elements.r_component.value="";
  for (i=0;i<df.elements.r_component_tmp.options.length;i++)
   {	
    if (df.elements.r_component_tmp.options[i].selected) df.elements.r_component.value+=df.elements.r_component_tmp.options[i].value+",";	
   }
  <?}?>     
  
  df.action.value=what;
  df.submit();	     
 }

function change_select()
 {
  df=document.forms['edit'];

  <?if (!($stub=="1" || $stub=="2" || $r_stub=="2")) {?> 
  df.elements.r_c_id.value="";
  for (i=0;i<df.elements.r_c_id_tmp.options.length;i++)
   {	
    if (df.elements.r_c_id_tmp.options[i].selected) df.elements.r_c_id.value+=df.elements.r_c_id_tmp.options[i].value+",";	
   }
  df.elements.r_release.value="";
  for (i=0;i<df.elements.r_release_tmp.options.length;i++)
   {	
    if (df.elements.r_release_tmp.options[i].selected) df.elements.r_release.value+=df.elements.r_release_tmp.options[i].value+",";	
   }
  df.elements.r_glossary.value="";
  for (i=0;i<df.elements.r_glossary_tmp.options.length;i++)
   {	
    if (df.elements.r_glossary_tmp.options[i].selected) df.elements.r_glossary.value+=df.elements.r_glossary_tmp.options[i].value+",";	
   }
  df.elements.r_stakeholder.value="";
  for (i=0;i<df.elements.r_stakeholder_tmp.options.length;i++)
   {	
    if (df.elements.r_stakeholder_tmp.options[i].selected) df.elements.r_stakeholder.value+=df.elements.r_stakeholder_tmp.options[i].value+",";	
   }
  df.elements.r_component.value="";
  for (i=0;i<df.elements.r_component_tmp.options.length;i++)
   {	
    if (df.elements.r_component_tmp.options[i].selected) df.elements.r_component.value+=df.elements.r_component_tmp.options[i].value+",";	
   }
  df.elements.r_depends.value="";
  for (i=0;i<df.elements.r_depends_tmp.options.length;i++)
   {	
    if (df.elements.r_depends_tmp.options[i].selected) df.elements.r_depends.value+=df.elements.r_depends_tmp.options[i].value+",";	
   }
  df.elements.r_conflicts.value="";
  for (i=0;i<df.elements.r_conflicts_tmp.options.length;i++)
   {	
    if (df.elements.r_conflicts_tmp.options[i].selected) df.elements.r_conflicts.value+=df.elements.r_conflicts_tmp.options[i].value+",";	
   }
  df.elements.r_keywords.value="";
  for (i=0;i<df.elements.r_keywords_tmp.options.length;i++)
   {	
    if (df.elements.r_keywords_tmp.options[i].selected) df.elements.r_keywords.value+=df.elements.r_keywords_tmp.options[i].value+",";	
   }
  df.elements.r_component.value="";
  for (i=0;i<df.elements.r_component_tmp.options.length;i++)
   {	
    if (df.elements.r_component_tmp.options[i].selected) df.elements.r_component.value+=df.elements.r_component_tmp.options[i].value+",";	
   }
  <?}?>   
 }

 </script>
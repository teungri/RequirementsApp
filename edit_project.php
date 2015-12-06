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
// Page: "edit project" - editing/adding/deleting projects

//check if logged
if ($_SESSION['rights']!="5") header("Location:index.php");

if ($action=="delete" && $p_id!="")
 {
  $query="delete from project_releases where pr_p_id=".$p_id;
  mysql_query($query) or die(mysql_error());
  
  $query="delete from project_users where pu_p_id=".$p_id;
  mysql_query($query) or die(mysql_error());
  
  $query="delete from project_stakeholders where ps_p_id=".$p_id;
  mysql_query($query) or die(mysql_error());

  $query="delete from project_keywords where pk_p_id=".$p_id;
  mysql_query($query) or die(mysql_error());

  $query="delete from project_components where pco_p_id=".$p_id;
  mysql_query($query) or die(mysql_error());

  $query="delete from project_glossary where pg_p_id=".$p_id;
  mysql_query($query) or die(mysql_error());
  
  $query="delete from projects where p_id=".$p_id;
  mysql_query($query) or die(mysql_error());
  header("Location:index.php?inc=manage_projects");
 }
if ($status_old=="2" && ($p_status=="2" || $p_status=="")) $tmp="<span class='error'>".$lng[10][25]."</span><br><br>";
else
 {
  //if template chosen - cloning project and requirements
  if ($action=="template" && $p_id!="")
   {
    //work up the text
    $ta=str_replace('<link href="styles.css" rel="stylesheet" />','',stripslashes($ta));
    //if newly created
    if ($p_id_old=="") 
     {
      $query="insert into projects (p_name, p_desc, p_phase, p_status, p_leader, p_date, p_template, p_req_del) values ('".escapeChars($p_name)."','".stripbr(escapeChars($ta))."','".escapeChars($p_phase)."','".escapeChars($p_status)."','".escapeChars($p_leader)."',now(),'".escapeChars($p_template)."','".escapeChars($p_req_del)."')";
      mysql_query($query) or die(mysql_error());
      $p_id_new=mysql_insert_id();
     }
    else //template added to existing project  
     {
      $query="update projects set p_name='".escapeChars($p_name)."', p_desc='".stripbr(escapeChars($ta))."', p_phase='".escapeChars($p_phase)."', p_status='".escapeChars($p_status)."', p_leader='".escapeChars($p_leader)."', p_template='".escapeChars($p_template)."', p_req_del='".escapeChars($p_req_del)."' where p_id=".$p_id_old;
      mysql_query($query) or die(mysql_error());
      $p_id_new=$p_id_old;
     } 
    
    //if newly created added users, stakeholders, releases, glossary, components from the template project
    if ($p_id_old=="")
     {
      //cloning releases
      $query="delete from project_releases where pr_p_id=".$p_id_new;
      mysql_query($query) or die(mysql_error());

      $query="select * from project_releases where pr_p_id=".$p_id;
      $rs = mysql_query($query) or die(mysql_error());
      while($row=mysql_fetch_array($rs)) 
       {
        $query="insert into project_releases (pr_p_id, pr_r_id) values ('".$p_id_new."','".$row['pr_r_id']."')";
        mysql_query($query) or die(mysql_error());
       }
    
      //cloning stakeholders
      $query="delete from project_stakeholders where ps_p_id=".$p_id_new;
      mysql_query($query) or die(mysql_error());

      $query="select * from project_stakeholders where ps_p_id=".$p_id;
      $rs = mysql_query($query) or die(mysql_error());
      while($row=mysql_fetch_array($rs)) 
       {
        $query="insert into project_stakeholders (ps_p_id, ps_s_id) values ('".$p_id_new."','".$row['ps_s_id']."')";
        mysql_query($query) or die(mysql_error());
       }
     
      //cloning keywords
      $query="delete from project_keywords where pk_p_id=".$p_id_new;
      mysql_query($query) or die(mysql_error());

      $query="select * from project_keywords where pk_p_id=".$p_id;
      $rs = mysql_query($query) or die(mysql_error());
      while($row=mysql_fetch_array($rs)) 
       {
        $query="insert into project_keywords (pk_p_id, pk_k_id) values ('".$p_id_new."','".$row['pk_k_id']."')";
        mysql_query($query) or die(mysql_error());
       }
     
      //cloning components
      $query="delete from project_components where pco_p_id=".$p_id_new;
      mysql_query($query) or die(mysql_error());

      $query="select * from project_components where pco_p_id=".$p_id;
      $rs = mysql_query($query) or die(mysql_error());
      while($row=mysql_fetch_array($rs)) 
       {
        $query="insert into project_components (pco_p_id, pco_c_id) values ('".$p_id_new."','".$row['pco_c_id']."')";
        mysql_query($query) or die(mysql_error());
       }
     
      //cloning glossary
      $query="delete from project_glossary where pg_p_id=".$p_id_new;
      mysql_query($query) or die(mysql_error());
  
      $query="select * from project_glossary where pg_p_id=".$p_id;
      $rs = mysql_query($query) or die(mysql_error());
      while($row=mysql_fetch_array($rs)) 
       {
        $query="insert into project_glossary (pg_p_id, pg_g_id) values ('".$p_id_new."','".$row['pg_g_id']."')";
        mysql_query($query) or die(mysql_error());
       }
    
      //cloning users
      $query="delete from project_users where pu_p_id=".$p_id_new;
      mysql_query($query) or die(mysql_error());

      $query="select * from project_users where pu_p_id=".$p_id;
      $rs = mysql_query($query) or die(mysql_error());
      while($row=mysql_fetch_array($rs)) 
       {
        $query="insert into project_users (pu_p_id, pu_u_id) values ('".$p_id_new."','".$row['pu_u_id']."')";
        mysql_query($query) or die(mysql_error());
       }
     }
     
    $c=0;
    $query="select * from requirements where r_p_id=".$p_id." order by r_pos asc";
    $rs = mysql_query($query) or die(mysql_error());
    while($row=mysql_fetch_array($rs)) 
     {
      $arr1[$c]=$row['r_id'];
      $query2="insert into requirements (r_p_id,r_release,r_c_id,r_s_id,r_stakeholder,r_glossary,r_keyword, r_u_id,r_assigned_u_id,r_name,r_desc,r_state,r_type_r,r_priority,r_link,r_satisfaction,r_dissatisfaction,r_conflicts,r_depends,r_component,r_source,r_risk,r_complexity,r_weight,r_points,r_creation_date,r_change_date,r_accept_date,r_accept_user, r_parent_id, r_pos, r_stub, r_keywords, r_userfield1, r_userfield2, r_userfield3, r_userfield4, r_userfield5, r_userfield6) values ('".$p_id_new."','".escapeChars($row['r_release'])."','".escapeChars($row['r_c_id'])."','".escapeChars($row['r_s_id'])."','".escapeChars($row['r_stakeholder'])."','".escapeChars($row['r_glossary'])."','".escapeChars($row['r_keyword'])."','".escapeChars($row['r_u_id'])."','".$row['r_assigned_u_id']."','".escapeChars($row['r_name'])."','".escapeChars($row['r_desc'])."','".escapeChars($row['r_state'])."','".escapeChars($row['r_type_r'])."','".escapeChars($row['r_priority'])."','".escapeChars($row['r_link'])."','".escapeChars($row['r_satisfaction'])."','".escapeChars($row['r_dissatisfaction'])."','".escapeChars($row['r_conflicts'])."','".escapeChars($row['r_depends'])."','".escapeChars($row['r_component'])."','".escapeChars($row['r_source'])."','".escapeChars($row['r_risk'])."','".escapeChars($row['r_complexity'])."','".escapeChars($row['r_weight'])."','".escapeChars($row['r_points'])."','".escapeChars($row['r_creation_date'])."',now(),'".escapeChars($row['r_accept_date'])."','".escapeChars($row['r_accept_user'])."','".escapeChars($row['r_parent_id'])."','".escapeChars($row['r_pos'])."','".escapeChars($row['r_stub'])."','".escapeChars($row['r_keywords'])."','".escapeChars($row['r_userfield1'])."','".escapeChars($row['r_userfield2'])."','".escapeChars($row['r_userfield3'])."','".escapeChars($row['r_userfield4'])."','".escapeChars($row['r_userfield5'])."','".escapeChars($row['r_userfield6'])."')";
      mysql_query($query2) or die(mysql_error());
      $arr2[$c]=mysql_insert_id();
      $c++;
     }  
    //changing parent_ids with the new ones 
    $c=0;
    if (is_array($arr1))
     {
      while (list ($key, $val) = each ($arr1))
       {
        $query="update requirements set r_parent_id='".$arr2[$c]."' where r_parent_id='".$val."' and r_p_id=".$p_id_new;
        mysql_query($query) or die(mysql_error());
        $c++;
       } 
     }
    //cloning subprojects
    $c=0;
    $query="select * from subprojects where s_p_id=".$p_id;
    $rs = mysql_query($query) or die(mysql_error());
    while($row=mysql_fetch_array($rs)) 
     {
      $arr3[$c]=$row['s_id'];
      $query="insert into subprojects (s_name, s_desc, s_p_id) values ('".$row['s_name']."','".$row['s_desc']."','".$p_id_new."')";
      mysql_query($query) or die(mysql_error());
      $arr4[$c]=mysql_insert_id();
      $c++;
     }

    //changing subprojects ids with the new ones 
    $c=0;
    if (is_array($arr3))
     {
      while (list ($key, $val) = each ($arr3))
       {
        $query="update requirements set r_s_id='".$arr4[$c]."' where r_s_id='".$val."' and r_p_id=".$p_id_new;
        mysql_query($query) or die(mysql_error());
        $c++;
       } 
     } 
      
    $p_load="";$p_id=$p_id_new;
   }

  if ($action=="add")
   {
    //work up the text
    $ta=str_replace('<link href="styles.css" rel="stylesheet" />','',stripslashes($ta));

    $query="insert into projects (p_name, p_phase, p_status, p_leader, p_date, p_desc, p_template, p_req_del) values ('".escapeChars($p_name)."','".escapeChars($p_phase)."','".escapeChars($p_status)."','".escapeChars($p_leader)."',DATE_ADD( NOW( ) , INTERVAL - ".TIME_DIFF_HOURS." HOUR),'".stripbr(escapeChars($ta))."','".escapeChars($p_name)."','".escapeChars($p_req_del)."')";
    mysql_query($query) or die(mysql_error());
    $p_id=mysql_insert_id();
    //header("Location:index.php?inc=manage_projects");
   }

  if ($action=="update" && $p_id!="")
   {
    //work up the text
    $ta=str_replace('<link href="styles.css" rel="stylesheet" />','',stripslashes($ta));

    $query="update projects set p_name='".escapeChars($p_name)."', p_phase='".escapeChars($p_phase)."', p_status='".escapeChars($p_status)."', p_leader='".escapeChars($p_leader)."', p_desc='".stripbr(escapeChars($ta))."', p_template='".escapeChars($p_template)."', p_req_del='".escapeChars($p_req_del)."' where p_id=".$p_id;
    mysql_query($query) or die(mysql_error());
    //header("Location:index.php?inc=manage_projects");
   }
 
  //if ($what=="users_list" && $p_id!="")
  if ($action!="")
   {
    $query="delete from project_users where pu_p_id=".$p_id;
    mysql_query($query) or die(mysql_error());  
    
    $list = explode(",", substr($users_list,1));
    if ($users_list!="")
     {
      while (list ($key, $val) = each ($list))
       {
        $query="insert into project_users (pu_p_id, pu_u_id) values ('".$p_id."','".$val."')";
        mysql_query($query) or die(mysql_error());
       }
     }   
    //header("Location:index.php?inc=manage_projects");
   }
 
//  if ($what=="releases_list" && $p_id!="")
  if ($action!="")
   {
    $query="delete from project_releases where pr_p_id=".$p_id;
    mysql_query($query) or die(mysql_error());    
    
    $list = explode(",", substr($releases_list,1));
    if ($releases_list!="")
     {
      while (list ($key, $val) = each ($list))
       {
        $query="insert into project_releases (pr_p_id, pr_r_id) values ('".$p_id."','".$val."')";
        mysql_query($query) or die(mysql_error());
       }
     }
    //header("Location:index.php?inc=manage_projects");
   }  
 
//  if ($what=="stakeholders_list" && $p_id!="")
  if ($action!="")
   {
    $query="delete from project_stakeholders where ps_p_id=".$p_id;
    mysql_query($query) or die(mysql_error());    
    
    $list = explode(",", substr($stakeholders_list,1));
    if ($stakeholders_list!="")
     {
      while (list ($key, $val) = each ($list))
       {
        $query="insert into project_stakeholders (ps_p_id, ps_s_id) values ('".$p_id."','".$val."')";
        mysql_query($query) or die(mysql_error());
       }
     }
    //header("Location:index.php?inc=manage_projects");
   }  

//  if ($what=="keywords_list" && $p_id!="")
  if ($action!="")
   {
    $query="delete from project_keywords where pk_p_id=".$p_id;
    mysql_query($query) or die(mysql_error());    
    
    $list = explode(",", substr($keywords_list,1));
    if ($keywords_list!="")
     {
      while (list ($key, $val) = each ($list))
       {
        $query="insert into project_keywords (pk_p_id, pk_k_id) values ('".$p_id."','".$val."')";
        mysql_query($query) or die(mysql_error());
       }
     }
    //header("Location:index.php?inc=manage_projects");
   }  

//  if ($what=="components_list" && $p_id!="")
  if ($action!="")
   {
    $query="delete from project_components where pco_p_id=".$p_id;
    mysql_query($query) or die(mysql_error());    
    
    $list = explode(",", substr($components_list,1));
    if ($components_list!="")
     {
      while (list ($key, $val) = each ($list))
       {
        $query="insert into project_components (pco_p_id, pco_c_id) values ('".$p_id."','".$val."')";
        mysql_query($query) or die(mysql_error());
       }
     }
    //header("Location:index.php?inc=manage_projects");
   }  

//  if ($what=="glossary_list" && $p_id!="")
  if ($action!="")
   {
    $query="delete from project_glossary where pg_p_id=".$p_id;
    mysql_query($query) or die(mysql_error());    
    
    $list = explode(",", substr($glossary_list,1));
    if ($glossary_list!="")
     {
      while (list ($key, $val) = each ($list))
       {
        $query="insert into project_glossary (pg_p_id, pg_g_id) values ('".$p_id."','".$val."')";
        mysql_query($query) or die(mysql_error());
       }
     }
    header("Location:index.php?inc=manage_projects");
   }  
 }
 
 
if ($p_id!="") 
 {
  $query="select p.*, date_format(p_date, '%d.%m.%Y') as d1, u_name from projects p left outer join users u on p.p_leader=u.u_id where p.p_id=".$p_id;
  $rs = mysql_query($query) or die(mysql_error());
  if($row=mysql_fetch_array($rs)) 
   {
    $p_name=htmlspecialchars($row['p_name']);
    $p_desc=$row['p_desc'];
    $p_phase=$row['p_phase'];
    $p_status=$row['p_status'];
    $p_leader=$row['p_leader'];
    $u_name=$row['u_name'];
    $p_date=$row['d1'];
    $p_template=$row['p_template'];
    $p_req_del=$row['p_req_del'];
   }
 }  
 
//project leaders 
$query="select * from users where (u_rights=4 or u_rights=5)";
$rs = mysql_query($query) or die(mysql_error());
while($row=mysql_fetch_array($rs)) 
 {
  if ($p_leader==$row['u_id']) $p_leader_list.="<option value='".$row['u_id']."' selected>".htmlspecialchars($row['u_name']);
  else $p_leader_list.="<option value='".$row['u_id']."'>".htmlspecialchars($row['u_name']);
 }
 
//load template options
if ($p_id!="" && $p_load=="") $query="select * from projects where p_template=1 and p_id<>".$p_id;
else $query="select * from projects where p_template=1";
$rs = mysql_query($query) or die(mysql_error());
while($row=mysql_fetch_array($rs)) 
 {
  if ($p_id==$row['p_id']) $template_options.="<option value='".$row['p_id']."' selected>".htmlspecialchars($row['p_name']);
  else $template_options.="<option value='".$row['p_id']."'>".htmlspecialchars($row['p_name']);
 }
 
?>
<?if ($tmp!="") echo $tmp;?>
<form method="post" name="f" action="">
<table border="0" width="70%">
  <tr valign="top">
    <td>
      <input type="hidden" name="p_id" value="<?=$p_id?>">
	<table border="0" cellpadding="2" cellspacing="2" class="content" width="100%">
	  <tr class="gray">
	    <td colspan="2" align="center"><b><?=($p_id=="")?$lng[10][2]:$lng[10][1]?></b></td>
	  </tr>
	  <?if ($p_template==0 || $p_load!="") {?>
	  <tr class="light_blue" title="<?=$lng[10][39]?>">
	    <td align="right">&nbsp;<?=$lng[10][38]?>&nbsp;:&nbsp;</td>
	    <td>
	      <select name="p_load" onchange="document.location.href='index.php?inc=edit_project&p_load=yes&p_id_old=<?=$p_id?>&p_id='+this.value">
	        <option value=''>--
	        <?=$template_options?>
	      </select>   
	    </td>
	  </tr>  
	  <?}?>
	  <tr class="blue" title="<?=$lng[9][16]?>">
	    <td align="right">&nbsp;<?=$lng[10][3]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;<input type="text" name="p_name" value="<?=$p_name?>" maxlength="90" size=60></td>
	  </tr>  
	  <tr class="light_blue" title="<?=$lng[9][17]?>">
	    <td align="right">&nbsp;<?=$lng[10][4]?>&nbsp;:&nbsp;</td>
	    <td>
	      <select name="p_phase">
	        <option value="0" <?if ($p_phase==0) echo "selected";?>><?=$lng[9][8];?>
	        <option value="1" <?if ($p_phase==1) echo "selected";?>><?=$lng[9][9];?>
	        <option value="2" <?if ($p_phase==2) echo "selected";?>><?=$lng[9][10];?>
	        <option value="3" <?if ($p_phase==3) echo "selected";?>><?=$lng[9][32];?>
	        <option value="4" <?if ($p_phase==4) echo "selected";?>><?=$lng[9][33];?>
	      </select>   
	    </td>
	  </tr>  
	  <tr class="blue" title="<?=$lng[9][18]?>">
	    <td align="right">&nbsp;<?=$lng[10][5]?>&nbsp;:&nbsp;</td>
	    <td>
	      <select name="p_status">
	        <option value="0" <?if ($p_status==0) echo "selected";?>><?=$lng[9][11];?>
	        <option value="1" <?if ($p_status==1) echo "selected";?>><?=$lng[9][12];?>
	        <option value="2" <?if ($p_status==2) echo "selected";?>><?=$lng[9][14];?>
	      </select>   
	    </td>
	  </tr>  
	  <tr class="light_blue" title="<?=$lng[9][19]?>">
	    <td align="right">&nbsp;<?=$lng[10][6]?>&nbsp;:&nbsp;</td>
	    <td>
	      <select name="p_leader">
	        <?=$p_leader_list?>
	      </select>   
	    </td>
	  </tr>  
	  <tr class="blue" valign=top title="<?=$lng[9][21]?>">
	    <td align="right">&nbsp;<?=$lng[10][7]?>&nbsp;:&nbsp;</td>
	    <td><? 
		include("FCKeditor/fckeditor.php");
		$oFCKeditor = new FCKeditor('ta') ;
		$oFCKeditor->BasePath = 'FCKeditor/' ;
		$oFCKeditor->Value = $p_desc;
		$oFCKeditor->Width = '560';
		$oFCKeditor->Height = '300';
		$oFCKeditor->Create() ;
		?> 
	    </td>
	  </tr>  
	  <tr class="light_blue" title="<?=$lng[10][37]?>">
	    <td align="right">&nbsp;<?=$lng[10][36]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;<input type="checkbox" name="p_template" <?if ($p_template==1 && $p_load=="") echo "checked";?> value="1"></td>
	  </tr>  
	  <?if ($p_id!="") {?>
	  <tr class="light_blue" title="<?=$lng[9][20]?>">
	    <td align="right">&nbsp;<?=$lng[10][8]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;<?=$p_date?></td>
	  </tr>  
	  <?}?>
	  <tr class="light_blue" title="<?=$lng[10][53]?>">
	    <td align="right">&nbsp;<?=$lng[10][53]?>&nbsp;:&nbsp;</td>
	    <td>
	      <select name="p_req_del">
	         <option value="1" <?if ($p_req_del=="" || $p_req_del=="1") echo "selected";?>><?=$lng[10][54]?>
	         <option value="0" <?if ($p_req_del=="0") echo "selected";?>><?=$lng[10][55]?>
	      </select>   
	    </td>
	  </tr>  
	</table>
      <input type="hidden" name="status_old" value="<?=$p_status?>">
      <input type="hidden" name="p_name_old" value="<?=$p_name?>">
      <input type="hidden" name="p_id_old" value="<?=$p_id_old?>">
      <input type="hidden" name="inc" value="edit_project">
      <input type="hidden" name="action" value="">	
      <!--/form-->	
    </td> 	 
  </tr>

<?
if ($p_load=="")
 {
if ($p_id!="")
 {
  $tmp_list="0";
  $query="select u.* from users u, project_users pu where u.u_rights in (0,1,2,3,4,5) and u.u_id=pu.pu_u_id and pu.pu_p_id=".$p_id." order by u_name asc";
  $rs = mysql_query($query) or die(mysql_error());
  while($row=mysql_fetch_array($rs)) 
   {
    $p_users_list2.="<option value='".$row['u_id']."'>".htmlspecialchars($row['u_name']);
    if ($row['u_rights']==0) $p_users_list2.=" (".$lng[2][25].")";
    elseif ($row['u_rights']==1) $p_users_list2.=" (".$lng[2][14].")";
    elseif ($row['u_rights']==2) $p_users_list2.=" (".$lng[2][15].")";
    elseif ($row['u_rights']==3) $p_users_list2.=" (".$lng[2][16].")";
    elseif ($row['u_rights']==4) $p_users_list2.=" (".$lng[2][17].")";
    elseif ($row['u_rights']==5) $p_users_list2.=" (".$lng[2][18].")";
    $tmp_list.=",".$row['u_id'];
   } 

  $query="select * from users where u_rights in (0,1,2,3,4,5) and u_id not in (".$tmp_list.")";
  $rs = mysql_query($query) or die(mysql_error());
  while($row=mysql_fetch_array($rs)) 
   {
    $p_users_list.="<option value='".$row['u_id']."'>".htmlspecialchars($row['u_name']);
    if ($row['u_rights']==0) $p_users_list.=" (".$lng[2][25].")";
    elseif ($row['u_rights']==1) $p_users_list.=" (".$lng[2][14].")";
    elseif ($row['u_rights']==2) $p_users_list.=" (".$lng[2][15].")";
    elseif ($row['u_rights']==3) $p_users_list.=" (".$lng[2][16].")";
    elseif ($row['u_rights']==4) $p_users_list.=" (".$lng[2][17].")";
    elseif ($row['u_rights']==5) $p_users_list.=" (".$lng[2][18].")";
   }
 }
 else
  {
  $tmp_list="0";
  $query="select u.* from users u, project_users pu where u.u_rights in (0,1,2,3,4,5) and u.u_id=pu.pu_u_id and pu.pu_p_id=-11 order by u_name asc";
  $rs = mysql_query($query) or die(mysql_error());
  while($row=mysql_fetch_array($rs)) 
   {
    $p_users_list2.="<option value='".$row['u_id']."'>".htmlspecialchars($row['u_name']);
    if ($row['u_rights']==0) $p_users_list2.=" (".$lng[2][25].")";
    elseif ($row['u_rights']==1) $p_users_list2.=" (".$lng[2][14].")";
    elseif ($row['u_rights']==2) $p_users_list2.=" (".$lng[2][15].")";
    elseif ($row['u_rights']==3) $p_users_list2.=" (".$lng[2][16].")";
    elseif ($row['u_rights']==4) $p_users_list2.=" (".$lng[2][17].")";
    elseif ($row['u_rights']==5) $p_users_list2.=" (".$lng[2][18].")";
    $tmp_list.=",".$row['u_id'];
   } 

  $query="select * from users where u_rights in (0,1,2,3,4,5) and u_id not in (".$tmp_list.")";
  $rs = mysql_query($query) or die(mysql_error());
  while($row=mysql_fetch_array($rs)) 
   {
    $p_users_list.="<option value='".$row['u_id']."'>".htmlspecialchars($row['u_name']);
    if ($row['u_rights']==0) $p_users_list.=" (".$lng[2][25].")";
    elseif ($row['u_rights']==1) $p_users_list.=" (".$lng[2][14].")";
    elseif ($row['u_rights']==2) $p_users_list.=" (".$lng[2][15].")";
    elseif ($row['u_rights']==3) $p_users_list.=" (".$lng[2][16].")";
    elseif ($row['u_rights']==4) $p_users_list.=" (".$lng[2][17].")";
    elseif ($row['u_rights']==5) $p_users_list.=" (".$lng[2][18].")";
   }
  
  
  } 
?>
  <tr valign="top">
    <td>
      <!--form method="post" name="f2" action=""-->
      <!--input type="hidden" name="p_id" value="<?=$p_id?>"-->
	<table border="0" cellpadding="2" cellspacing="2" class="content" width="100%">
	  <tr class="gray" title="<?=$lng[10][23]?>">
	    <td colspan="3" align="center"><b><?=$lng[10][14]?></b></td>
	  </tr>
	 <tr class="blue">
	    <td align="center">
	       <br/><?=$lng[10][16]?><br/>
	       <select name="users_tmp" multiple size=10 style="width:190px;background:#F3F3F3;">
	       <?=$p_users_list?>
	       </select><br/><br/>	    
	    </td>
	    <td align="center"><a href="#" onclick="copyToList('users_tmp','users_tmp2','f');return false;"><b>==></b></a><br><br><a href="#" onclick="copyToList('users_tmp2','users_tmp','f');return false;"><b><==</b></a><br/><br/><br/><br/><?if ($p_id!="") {?><input type="button" value="<?=$lng[99][30]?>" onclick="newwin=window.open('popup_users.php?p_id=<?=$p_id?>&where=1','name','height=300,width=700');"><?}?></td>
	    <td align="center">
	       <br/><?=$lng[10][17]?><br/>
	       <select name="users_tmp2" multiple size=10 style="width:190px;background:#F3F3F3;">
	       <?=$p_users_list2?>
	       </select><br/><br/>
	    </td>
	  </tr>
	   <!--tr class="gray">
	    <td colspan="3" align="center">
	       <input type="button" onclick="selectUsers();" value="<?=$lng[10][15]?>">
	    </td>
	  </tr-->  	    
	</table>
      <!--input type="hidden" name="status_old" value="<?=$p_status?>"-->
      <!--input type="hidden" name="inc" value="edit_project"-->
      <input type="hidden" name="users_list" value=""> 
      <!--input type="hidden" name="what" value=""--> 
      <!--/form-->	
    </td> 	 
  </tr>
<?}?>

<?
if ($p_load=="")
 {
if ($p_id!="" )
 {
  $tmp_list="0";
  $query="select r.* from releases r, project_releases pr where r.r_id=pr.pr_r_id and pr.pr_p_id=".$p_id." order by r_name asc";
  $rs = mysql_query($query) or die(mysql_error());
  while($row=mysql_fetch_array($rs)) 
   {
    $p_releases_list2.="<option value='".$row['r_id']."'>".htmlspecialchars($row['r_name']);
    $tmp_list.=",".$row['r_id'];
   } 

  $query="select * from releases where r_id not in (".$tmp_list.")";
  $rs = mysql_query($query) or die(mysql_error());
  while($row=mysql_fetch_array($rs)) 
   {
    $p_releases_list.="<option value='".$row['r_id']."'>".htmlspecialchars($row['r_name']);
   }
 }
 else{
  $tmp_list="0";
  $query="select r.* from releases r, project_releases pr where r.r_id=pr.pr_r_id and pr.pr_p_id=0 order by r_name asc";
  $rs = mysql_query($query) or die(mysql_error());
  while($row=mysql_fetch_array($rs)) 
   {
    $p_releases_list2.="<option value='".$row['r_id']."'>".htmlspecialchars($row['r_name']);
    $tmp_list.=",".$row['r_id'];
   } 

  $query="select * from releases where r_id not in (".$tmp_list.")";
  $rs = mysql_query($query) or die(mysql_error());
  while($row=mysql_fetch_array($rs)) 
   {
    $p_releases_list.="<option value='".$row['r_id']."'>".htmlspecialchars($row['r_name']);
   }
 
 } 
?>
  <tr valign="top">
    <td>
      <!--form method="post" name="f3" action=""-->
      <!--input type="hidden" name="p_id" value="<?=$p_id?>"-->
	<table border="0" cellpadding="2" cellspacing="2" class="content" width="100%">
	  <tr class="gray" title="<?=$lng[10][24]?>">
	    <td colspan="3" align="center"><b><?=$lng[10][18]?></b></td>
	  </tr>
	 <tr class="blue">
	    <td align="center">
	       <br/><?=$lng[10][20]?><br/>
	       <select name="releases_tmp" multiple size=10 style="width:190px;background:#F3F3F3;">
	       <?=$p_releases_list?>
	       </select><br/><br/>	    
	    </td>
	    <td align="center"><a href="#" onclick="copyToList('releases_tmp','releases_tmp2','f');return false;"><b>==></b></a><br><br><a href="#" onclick="copyToList('releases_tmp2','releases_tmp','f');return false;"><b><==</b></a><br/><br/><br/><br/><?if ($p_id!="") {?><input type="button" value="<?=$lng[99][30]?>" onclick="newwin=window.open('popup_release.php?p_id=<?=$p_id?>&where=1','name','height=230,width=400');"><?}?></td>
	    <td align="center">
	       <br/><?=$lng[10][21]?><br/>
	       <select name="releases_tmp2" multiple size=10 style="width:190px;background:#F3F3F3;">
	       <?=$p_releases_list2?>
	       </select><br/><br/>
	    </td>
	  </tr>
	   <!--tr class="gray">
	    <td colspan="3" align="center">
	       <input type="button" onclick="selectReleases();" value="<?=$lng[10][19]?>">
	    </td>
	  </tr-->  	    
	</table>
      <!--input type="hidden" name="status_old" value="<?=$p_status?>"-->
      <!--input type="hidden" name="inc" value="edit_project"-->
      <input type="hidden" name="releases_list" value=""> 
      <!--input type="hidden" name="what" value=""--> 
      <!--/form-->	
    </td> 	 
  </tr>
<?}?>

<?
if ($p_load=="")
 {
if ($p_id!="")
 {
  $tmp_list="0";
  $query="select s.* from stakeholders s, project_stakeholders ps where s.s_id=ps.ps_s_id and ps.ps_p_id=".$p_id." order by s_name asc";
  $rs = mysql_query($query) or die(mysql_error());
  while($row=mysql_fetch_array($rs)) 
   {
    $p_stakeholders_list2.="<option value='".$row['s_id']."'>".htmlspecialchars($row['s_name']);
    $tmp_list.=",".$row['s_id'];
   } 

  $query="select * from stakeholders where s_id not in (".$tmp_list.")";
  $rs = mysql_query($query) or die(mysql_error());
  while($row=mysql_fetch_array($rs)) 
   {
    $p_stakeholders_list.="<option value='".$row['s_id']."'>".htmlspecialchars($row['s_name']);
   }
 }else{
  $tmp_list="0";
  $query="select s.* from stakeholders s, project_stakeholders ps where s.s_id=ps.ps_s_id and ps.ps_p_id=0 order by s_name asc";
  $rs = mysql_query($query) or die(mysql_error());
  while($row=mysql_fetch_array($rs)) 
   {
    $p_stakeholders_list2.="<option value='".$row['s_id']."'>".htmlspecialchars($row['s_name']);
    $tmp_list.=",".$row['s_id'];
   } 

  $query="select * from stakeholders where s_id not in (".$tmp_list.")";
  $rs = mysql_query($query) or die(mysql_error());
  while($row=mysql_fetch_array($rs)) 
   {
    $p_stakeholders_list.="<option value='".$row['s_id']."'>".htmlspecialchars($row['s_name']);
   }
 
 }  
?>
  <tr valign="top">
    <td>
      <!--form method="post" name="f4" action=""-->
      <!--input type="hidden" name="p_id" value="<?=$p_id?>"-->
	<table border="0" cellpadding="2" cellspacing="2" class="content" width="100%">
	  <tr class="gray" title="<?=$lng[10][30]?>">
	    <td colspan="3" align="center"><b><?=$lng[10][26]?></b></td>
	  </tr>
	 <tr class="blue">
	    <td align="center">
	       <br/><?=$lng[10][28]?><br/>
	       <select name="stakeholders_tmp" multiple size=10 style="width:190px;background:#F3F3F3;">
	       <?=$p_stakeholders_list?>
	       </select><br/><br/>	    
	    </td>
	    <td align="center"><a href="#" onclick="copyToList('stakeholders_tmp','stakeholders_tmp2','f');return false;"><b>==></b></a><br><br><a href="#" onclick="copyToList('stakeholders_tmp2','stakeholders_tmp','f');return false;"><b><==</b></a><br/><br/><br/><br/><?if ($p_id!="") {?><input type="button" value="<?=$lng[99][30]?>" onclick="newwin=window.open('popup_stakeholder.php?p_id=<?=$p_id?>&where=1','name','height=340,width=700');"><?}?></td>
	    <td align="center">
	       <br/><?=$lng[10][29]?><br/>
	       <select name="stakeholders_tmp2" multiple size=10 style="width:190px;background:#F3F3F3;">
	       <?=$p_stakeholders_list2?>
	       </select><br/><br/>
	    </td>
	  </tr>
	   <!--tr class="gray">
	    <td colspan="3" align="center">
	       <input type="button" onclick="selectStakeholders();" value="<?=$lng[10][27]?>">
	    </td>
	  </tr-->  	    
	</table>
      <!--input type="hidden" name="status_old" value="<?=$p_status?>"-->
      <!--input type="hidden" name="inc" value="edit_project"-->
      <input type="hidden" name="stakeholders_list" value=""> 
      <!--input type="hidden" name="what" value=""--> 
      <!--/form-->	
    </td> 	 
  </tr>
<?}?>

<?
if ($p_load=="")
 {
if ($p_id!="")
 {
  $tmp_list="0";
  $query="select k.* from keywords k, project_keywords pk where k.k_id=pk.pk_k_id and pk.pk_p_id=".$p_id." order by k_name asc";
  $rs = mysql_query($query) or die(mysql_error());
  while($row=mysql_fetch_array($rs)) 
   {
    $p_keywords_list2.="<option value='".$row['k_id']."'>".htmlspecialchars($row['k_name']);
    $tmp_list.=",".$row['k_id'];
   } 

  $query="select * from keywords where k_id not in (".$tmp_list.")";
  $rs = mysql_query($query) or die(mysql_error());
  while($row=mysql_fetch_array($rs)) 
   {
    $p_keywords_list.="<option value='".$row['k_id']."'>".htmlspecialchars($row['k_name']);
   }
 }else{
  $tmp_list="0";
  $query="select k.* from keywords k, project_keywords pk where k.k_id=pk.pk_k_id and pk.pk_p_id=0 order by k_name asc";
  $rs = mysql_query($query) or die(mysql_error());
  while($row=mysql_fetch_array($rs)) 
   {
    $p_keywords_list2.="<option value='".$row['k_id']."'>".htmlspecialchars($row['k_name']);
    $tmp_list.=",".$row['k_id'];
   } 

  $query="select * from keywords where k_id not in (".$tmp_list.")";
  $rs = mysql_query($query) or die(mysql_error());
  while($row=mysql_fetch_array($rs)) 
   {
    $p_keywords_list.="<option value='".$row['k_id']."'>".htmlspecialchars($row['k_name']);
   }
 
 }  
?>
  <tr valign="top">
    <td>
      <!--form method="post" name="f4" action=""-->
      <!--input type="hidden" name="p_id" value="<?=$p_id?>"-->
	<table border="0" cellpadding="2" cellspacing="2" class="content" width="100%">
	  <tr class="gray" title="<?=$lng[10][48]?>">
	    <td colspan="3" align="center"><b><?=$lng[10][49]?></b></td>
	  </tr>
	 <tr class="blue">
	    <td align="center">
	       <br/><?=$lng[10][50]?><br/>
	       <select name="keywords_tmp" multiple size=10 style="width:190px;background:#F3F3F3;">
	       <?=$p_keywords_list?>
	       </select><br/><br/>	    
	    </td>
	    <td align="center"><a href="#" onclick="copyToList('keywords_tmp','keywords_tmp2','f');return false;"><b>==></b></a><br><br><a href="#" onclick="copyToList('keywords_tmp2','keywords_tmp','f');return false;"><b><==</b></a><br/><br/><br/><br/><?if ($p_id!="") {?><input type="button" value="<?=$lng[99][30]?>" onclick="newwin=window.open('popup_keyword.php?p_id=<?=$p_id?>&where=1','name','height=150,width=700');"><?}?></td>
	    <td align="center">
	       <br/><?=$lng[10][51]?><br/>
	       <select name="keywords_tmp2" multiple size=10 style="width:190px;background:#F3F3F3;">
	       <?=$p_keywords_list2?>
	       </select><br/><br/>
	    </td>
	  </tr>
	   <!--tr class="gray">
	    <td colspan="3" align="center">
	       <input type="button" onclick="selectKeywords();" value="<?=$lng[10][27]?>">
	    </td>
	  </tr-->  	    
	</table>
      <!--input type="hidden" name="status_old" value="<?=$p_status?>"-->
      <!--input type="hidden" name="inc" value="edit_project"-->
      <input type="hidden" name="keywords_list" value=""> 
      <!--input type="hidden" name="what" value=""--> 
      <!--/form-->	
    </td> 	 
  </tr>
<?}?>

<?
if ($p_load=="")
 {
if ($p_id!="")
 {
  $tmp_list="0";
  $query="select c.* from components c, project_components pco where c.c_id=pco.pco_c_id and pco.pco_p_id=".$p_id." order by c_name asc";
  $rs = mysql_query($query) or die(mysql_error());
  while($row=mysql_fetch_array($rs)) 
   {
    $p_components_list2.="<option value='".$row['c_id']."'>".htmlspecialchars($row['c_name']);
    $tmp_list.=",".$row['c_id'];
   } 

  $query="select * from components where c_id not in (".$tmp_list.")";
  $rs = mysql_query($query) or die(mysql_error());
  while($row=mysql_fetch_array($rs)) 
   {
    $p_components_list.="<option value='".$row['c_id']."'>".htmlspecialchars($row['c_name']);
   }
 }else{
  $tmp_list="0";
  $query="select c.* from components c, project_components pco where c.c_id=pco.pco_c_id and pco.pco_p_id=0 order by c_name asc";
  $rs = mysql_query($query) or die(mysql_error());
  while($row=mysql_fetch_array($rs)) 
   {
    $p_components_list2.="<option value='".$row['c_id']."'>".htmlspecialchars($row['c_name']);
    $tmp_list.=",".$row['c_id'];
   } 

  $query="select * from components where c_id not in (".$tmp_list.")";
  $rs = mysql_query($query) or die(mysql_error());
  while($row=mysql_fetch_array($rs)) 
   {
    $p_components_list.="<option value='".$row['c_id']."'>".htmlspecialchars($row['c_name']);
   }
 
 }  
?>
  <tr valign="top">
    <td>
      <!--form method="post" name="f4" action=""-->
      <!--input type="hidden" name="p_id" value="<?=$p_id?>"-->
	<table border="0" cellpadding="2" cellspacing="2" class="content" width="100%">
	  <tr class="gray" title="<?=$lng[10][44]?>">
	    <td colspan="3" align="center"><b><?=$lng[10][45]?></b></td>
	  </tr>
	 <tr class="blue">
	    <td align="center">
	       <br/><?=$lng[10][46]?><br/>
	       <select name="components_tmp" multiple size=10 style="width:190px;background:#F3F3F3;">
	       <?=$p_components_list?>
	       </select><br/><br/>	    
	    </td>
	    <td align="center"><a href="#" onclick="copyToList('components_tmp','components_tmp2','f');return false;"><b>==></b></a><br><br><a href="#" onclick="copyToList('components_tmp2','components_tmp','f');return false;"><b><==</b></a><br/><br/><br/><br/><?if ($p_id!="") {?><input type="button" value="<?=$lng[99][30]?>" onclick="newwin=window.open('popup_component.php?p_id=<?=$p_id?>&where=1','name','height=160,width=700');"><?}?></td>
	    <td align="center">
	       <br/><?=$lng[10][47]?><br/>
	       <select name="components_tmp2" multiple size=10 style="width:190px;background:#F3F3F3;">
	       <?=$p_components_list2?>
	       </select><br/><br/>
	    </td>
	  </tr>
	   <!--tr class="gray">
	    <td colspan="3" align="center">
	       <input type="button" onclick="selectStakeholders();" value="<?=$lng[10][27]?>">
	    </td>
	  </tr-->  	    
	</table>
      <!--input type="hidden" name="status_old" value="<?=$p_status?>"-->
      <!--input type="hidden" name="inc" value="edit_project"-->
      <input type="hidden" name="components_list" value=""> 
      <!--input type="hidden" name="what" value=""--> 
      <!--/form-->	
    </td> 	 
  </tr>
<?}?>

<?
if ($p_load=="")
 {
  if ($p_id!="")
   {
  $tmp_list="0";
  $query="select g.* from glossary g, project_glossary pg where g.g_id=pg.pg_g_id and pg.pg_p_id=".$p_id." order by g_id asc";
  $rs = mysql_query($query) or die(mysql_error());
  while($row=mysql_fetch_array($rs)) 
   {
    $tmp_g="";
    for ($i=0;$i<6-strlen($row['g_id']);$i++) $tmp_g.="0";$tmp_g.=$row['g_id'];
    $p_glossary_list2.="<option value='".$row['g_id']."'>".htmlspecialchars($row['g_abbreviation'])." ".htmlspecialchars($row['g_term']);
    $tmp_list.=",".$row['g_id'];
   } 
   
  $query="select * from glossary where g_id not in (".$tmp_list.")";
  $rs = mysql_query($query) or die(mysql_error());
  while($row=mysql_fetch_array($rs)) 
   {
    $tmp_g="";
    for ($i=0;$i<6-strlen($row['g_id']);$i++) $tmp_g.="0";$tmp_g.=$row['g_id'];
    $p_glossary_list.="<option value='".$row['g_id']."'>".htmlspecialchars($row['g_abbreviation'])." ".htmlspecialchars($row['g_term']);
   }
  }
  else
  {
  $tmp_list="0";
  $query="select g.* from glossary g, project_glossary pg where g.g_id=pg.pg_g_id and pg.pg_p_id=0 order by g_id asc";
  $rs = mysql_query($query) or die(mysql_error());
  while($row=mysql_fetch_array($rs)) 
   {
    $tmp_g="";
    for ($i=0;$i<6-strlen($row['g_id']);$i++) $tmp_g.="0";$tmp_g.=$row['g_id'];
    $p_glossary_list2.="<option value='".$row['g_id']."'>".htmlspecialchars($row['g_abbreviation'])." ".htmlspecialchars($row['g_term']);
    $tmp_list.=",".$row['g_id'];
   } 
   
  $query="select * from glossary where g_id not in (".$tmp_list.")";
  $rs = mysql_query($query) or die(mysql_error());
  while($row=mysql_fetch_array($rs)) 
   {
    $tmp_g="";
    for ($i=0;$i<6-strlen($row['g_id']);$i++) $tmp_g.="0";$tmp_g.=$row['g_id'];
    $p_glossary_list.="<option value='".$row['g_id']."'>".htmlspecialchars($row['g_abbreviation'])." ".htmlspecialchars($row['g_term']);
   }
  
  }
  
?>
  <tr valign="top">
    <td>
      <!--form method="post" name="f5" action=""-->
      <!--input type="hidden" name="p_id" value="<?=$p_id?>"-->
	<table border="0" cellpadding="2" cellspacing="2" class="content" width="100%">
	  <tr class="gray" title="<?=$lng[10][35]?>">
	    <td colspan="3" align="center"><b><?=$lng[10][31]?></b></td>
	  </tr>
	 <tr class="blue">
	    <td align="center">
	       <br/><?=$lng[10][33]?><br/>
	       <select name="glossary_tmp" multiple size=10 style="width:190px;background:#F3F3F3;">
	       <?=$p_glossary_list?>
	       </select><br/><br/>	    
	    </td>
	    <td align="center"><a href="#" onclick="copyToList('glossary_tmp','glossary_tmp2','f');return false;"><b>==></b></a><br><br><a href="#" onclick="copyToList('glossary_tmp2','glossary_tmp','f');return false;"><b><==</b></a><br/><br/><br/><br/><?if ($p_id!="") {?><input type="button" value="<?=$lng[99][30]?>" onclick="newwin=window.open('popup_glossary.php?p_id=<?=$p_id?>&where=1','name','height=600,width=900');"><?}?></td>
	    <td align="center">
	       <br/><?=$lng[10][34]?><br/>
	       <select name="glossary_tmp2" multiple size=10 style="width:190px;background:#F3F3F3;">
	       <?=$p_glossary_list2?>
	       </select><br/><br/>
	    </td>
	  </tr>
	   <!--tr class="gray">
	    <td colspan="3" align="center">
	       <input type="button" onclick="selectGlossary();" value="<?=$lng[10][32]?>">
	    </td>
	  </tr-->  	    
	</table>
      <!--input type="hidden" name="status_old" value="<?=$p_status?>"-->
      <!--input type="hidden" name="inc" value="edit_project"-->
      <input type="hidden" name="glossary_list" value=""> 
      <!--input type="hidden" name="what" value=""--> 
      <!--/form-->	
    </td> 	 
  </tr>
</table>


<?}?>
	  <tr class="gray">
	    <td colspan="3" align="center">
	       <input type="button" onclick="document.forms['f'].submit();" value="<?=$lng[10][41]?>">
	       <?if ($p_load=="yes") {?><input type="button" onclick="sub('template');" value="<?=$lng[10][41]?>"><?}?>
	       <?if ($p_id=="" && $p_load=="") {?><input type="button" onclick="sub('add');" value="<?=$lng[10][9]?>"><?}?>
	       <?if ($p_id!="" && $p_load=="") {?><input type="button" onclick="sub('update');" value="<?=$lng[10][10]?>">&nbsp;&nbsp;<input type="button" onclick="if (confirm('<?=$lng[10][13]?>')) sub('delete');" value="<?=$lng[10][11]?>"><?}?>
	    </td>
	  </tr>  	    
</table>
</form>
<script>
function sub(what)
 {
  df=document.forms['f'];
  if (what!="delete") 
   {
    if (df.p_name.value=="") 
     {
      alert("<?=$lng[10][12]?>");
      df.p_name.focus();	
      return false;
     }
    <?if ($p_load=="yes") {?>  
    if (df.p_name.value==df.p_name_old.value) 
     {
      alert("<?=$lng[10][40]?>");
      df.p_name.focus();	
      return false;
     }    
    df.action.value='template';
    <?}?>
   }
  if (df.action.value!='template') df.action.value=what;
  <?if ($p_load=="") {?>
  selectUsers();
  selectReleases();
  selectStakeholders();
  selectKeywords();
  selectComponents();
  selectGlossary();
  <?}?>
  df.submit();	     
 }
 
function copyToList(from,to,form_name)
 {
  fromList = eval('document.forms["'+form_name+'"].' + from);
  toList = eval('document.forms["'+form_name+'"].' + to);
  if (toList.options.length > 0 && toList.options[0].value == 'temp')
   {
    toList.options.length = 0;
   }
  var sel = false;
  for (i=0;i<fromList.options.length;i++)
   {
    var current = fromList.options[i];
    if (current.selected)
     {
      sel = true;
      txt = current.text;
      val = current.value;
      toList.options[toList.length] = new Option(txt,val);
      fromList.options[i] = null;
      i--;
     }
   }
 }

function selectUsers()
 {
  document.forms['f'].users_list.value="";
  for (i=0;i<document.forms['f'].users_tmp2.options.length;i++)
   {	
    document.forms['f'].users_list.value+=","+document.forms['f'].users_tmp2.options[i].value;	
   }   
  //document.forms['f'].what.value="users_list";
  //document.forms['f'].submit();
 }	
 
function selectReleases()
 {
  document.forms['f'].releases_list.value="";
  for (i=0;i<document.forms['f'].releases_tmp2.options.length;i++)
   {	
    document.forms['f'].releases_list.value+=","+document.forms['f'].releases_tmp2.options[i].value;	
   }   
  //document.forms['f'].what.value="releases_list";
  //document.forms['f'].submit();
 }	
 
function selectStakeholders()
 {
  document.forms['f'].stakeholders_list.value="";
  for (i=0;i<document.forms['f'].stakeholders_tmp2.options.length;i++)
   {	
    document.forms['f'].stakeholders_list.value+=","+document.forms['f'].stakeholders_tmp2.options[i].value;	
   }   
  //document.forms['f'].what.value="stakeholders_list";
  //document.forms['f'].submit();
 }	

function selectKeywords()
 {
  document.forms['f'].keywords_list.value="";
  for (i=0;i<document.forms['f'].keywords_tmp2.options.length;i++)
   {	
    document.forms['f'].keywords_list.value+=","+document.forms['f'].keywords_tmp2.options[i].value;	
   }   
  //document.forms['f'].what.value="keywords_list";
  //document.forms['f'].submit();
 }	

function selectComponents()
 {
  document.forms['f'].components_list.value="";
  for (i=0;i<document.forms['f'].components_tmp2.options.length;i++)
   {	
    document.forms['f'].components_list.value+=","+document.forms['f'].components_tmp2.options[i].value;	
   }   
  //document.forms['f'].what.value="components_list";
  //document.forms['f'].submit();
 }	

function selectGlossary()
 {
  document.forms['f'].glossary_list.value="";
  for (i=0;i<document.forms['f'].glossary_tmp2.options.length;i++)
   {	
    document.forms['f'].glossary_list.value+=","+document.forms['f'].glossary_tmp2.options[i].value;	
   }   
  //document.forms['f'].what.value="glossary_list";
  //document.forms['f'].submit();
 }	
 
</script>
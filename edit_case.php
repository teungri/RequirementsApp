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
// Page: "edit case" - editing/adding/deleting case

//check if logged
if (!($_SESSION['rights']=="2" || $_SESSION['rights']=="3" || $_SESSION['rights']=="4" || $_SESSION['rights']=="5")) header("Location:index.php");

if ($action=="delete" && $c_id!="")
 {
  $query="delete from project_cases where pc_c_id=".$c_id;
  mysql_query($query) or die(mysql_error());
  
  $query="delete from release_cases where rc_c_id=".$c_id;
  mysql_query($query) or die(mysql_error());
  
  $query="delete from cases where c_id=".$c_id;
  mysql_query($query) or die(mysql_error());
  header("Location:index.php?inc=manage_cases");
 }

  if ($action=="add")
   {
    //work up the text
    $ta=str_replace('<link href="styles.css" rel="stylesheet" />','',stripslashes($ta));
    $ta2=str_replace('<link href="styles.css" rel="stylesheet" />','',stripslashes($ta2));

    $query="insert into cases (c_name, c_desc, c_result, c_status, c_global) values ('".escapeChars($c_name)."','".stripbr(escapeChars($ta))."','".stripbr(escapeChars($ta2))."','".escapeChars($c_status)."','".escapeChars($c_global)."')";
    mysql_query($query) or die(mysql_error());
    $c_id=mysql_insert_id();
    //header("Location:index.php?inc=manage_cases");
   }

  if ($action=="update" && $c_id!="")
   {
    //work up the text
    $ta=str_replace('<link href="styles.css" rel="stylesheet" />','',stripslashes($ta));
    $ta2=str_replace('<link href="styles.css" rel="stylesheet" />','',stripslashes($ta2));

    $query="update cases set c_name='".escapeChars($c_name)."', c_desc='".stripbr(escapeChars($ta))."', c_result='".stripbr(escapeChars($ta2))."', c_status='".escapeChars($c_status)."', c_global='".escapeChars($c_global)."' where c_id=".$c_id;
    mysql_query($query) or die(mysql_error());
    //header("Location:index.php?inc=manage_cases");
   }
 
//  if ($what=="projects_list" && $c_id!="")
  if ($action!="")
   {
    $query="delete from project_cases where pc_c_id=".$c_id;
    mysql_query($query) or die(mysql_error());    
    $list = explode(",", substr($projects_list,1));
    if ($projects_list!="")
     {
      while (list ($key, $val) = each ($list))
       {
        $query="insert into project_cases (pc_c_id, pc_p_id) values ('".$c_id."','".$val."')";
        mysql_query($query) or die(mysql_error());
       }
     }   
    //header("Location:index.php?inc=manage_cases");
   }
 
//  if ($what=="releases_list" && $c_id!="")
  if ($action!="")
   {
    $query="delete from release_cases where rc_c_id=".$c_id;
    mysql_query($query) or die(mysql_error());    
    
    $list = explode(",", substr($releases_list,1));
    if ($releases_list!="")
     {
      while (list ($key, $val) = each ($list))
       {
        $query="insert into release_cases (rc_c_id, rc_r_id) values ('".$c_id."','".$val."')";
        mysql_query($query) or die(mysql_error());
       }
     }
    header("Location:index.php?inc=manage_cases");
   }  
 

 
if ($c_id!="") 
 {
  $query="select * from cases where c_id=".$c_id;
  $rs = mysql_query($query) or die(mysql_error());
  if($row=mysql_fetch_array($rs)) 
   {
    $c_name=htmlspecialchars($row['c_name']);
    $c_desc=$row['c_desc'];
    $c_result=$row['c_result'];
    $c_status=$row['c_status'];
    $c_global=$row['c_global'];
   }
 }  
?>
<?if ($tmp!="") echo $tmp;?>
<form method="post" name="f" action="">
<table border="0" width="70%">
  <tr valign="top">
    <td>
      <input type="hidden" name="c_id" value="<?=$c_id?>">
	<table border="0" cellpadding="2" cellspacing="2" class="content" width="100%">
	  <tr class="gray">
	    <td colspan="2" align="center"><b><?=($c_id=="")?$lng[30][2]:$lng[30][1]?></b></td>
	  </tr>
	  <tr class="blue" title="<?=$lng[30][12]?>">
	    <td align="right">&nbsp;<?=$lng[30][3]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;<input type="text" name="c_name" value="<?=$c_name?>" maxlength="90" size=60></td>
	  </tr>  
	  <tr class="blue" valign=top title="<?=$lng[30][13]?>">
	    <td align="right">&nbsp;<?=$lng[30][4]?>&nbsp;:&nbsp;</td>
	    <td><? 
		include("FCKeditor/fckeditor.php");
		$oFCKeditor = new FCKeditor('ta') ;
		$oFCKeditor->BasePath = 'FCKeditor/' ;
		$oFCKeditor->Value = $c_desc;
		$oFCKeditor->Width = '560';
		$oFCKeditor->Height = '300';
		$oFCKeditor->Create() ;
		?> 
	    </td>
	  </tr>  
	  <tr class="blue" title="<?=$lng[30][14]?>">
	    <td align="right">&nbsp;<?=$lng[30][5]?>&nbsp;:&nbsp;</td>
	    <td>
	        <? 
		$oFCKeditor = new FCKeditor('ta2') ;
		$oFCKeditor->BasePath = 'FCKeditor/' ;
		$oFCKeditor->Value = $c_result;
		$oFCKeditor->Width = '560';
		$oFCKeditor->Height = '300';
		$oFCKeditor->Create() ;
		?> 
	    </td>
	  </tr>  
	  <tr class="light_blue" title="<?=$lng[30][15]?>">
	    <td align="right">&nbsp;<?=$lng[30][6]?>&nbsp;:&nbsp;</td>
	    <td>
	      <select name="c_status">
	        <option value="1" <?if ($c_status==1) echo "selected";?>><?=$lng[30][16];?>
	        <option value="0" <?if ($c_status==0) echo "selected";?>><?=$lng[30][17];?>
	      </select>   
	    </td>
	  </tr>  
	  <tr class="blue" title="<?=$lng[13][13]?>">
	    <td align="right">&nbsp;<?=$lng[13][12]?>&nbsp;:&nbsp;</td>
	    <td>
	    <select name="c_global">
	      <option value="0"><?=$lng[13][14]?>
	      <option value="1" <?if ($c_global==1) echo "selected";?>><?=$lng[13][15]?>
	    </select>
	    </td>
	  </tr>  
	</table>
      <input type="hidden" name="inc" value="edit_case">
      <input type="hidden" name="action" value="">	
      <!--/form-->	
    </td> 	 
  </tr>
<!--/table-->

<?
if ($c_id!="")
 {
  $tmp_list="0";
  $query="select p.* from projects p, project_cases pc where p.p_id=pc.pc_p_id and pc.pc_c_id=".$c_id." and p.p_status<>2 order by p_name asc";
  $rs = mysql_query($query) or die(mysql_error());
  while($row=mysql_fetch_array($rs)) 
   {
    $p_projects_list2.="<option value='".$row['p_id']."'>".htmlspecialchars($row['p_name']);
    $tmp_list.=",".$row['p_id'];
   } 

  $query="select * from projects where p_status<>2 and p_id not in (".$tmp_list.")";
  $rs = mysql_query($query) or die(mysql_error());
  while($row=mysql_fetch_array($rs)) 
   {
    $p_projects_list.="<option value='".$row['p_id']."'>".htmlspecialchars($row['p_name']);
   }
 }
else
 {
  $tmp_list="0";
  $query="select p.* from projects p, project_cases pc where p.p_id=pc.pc_p_id and pc.pc_c_id=0 and p.p_status<>2 order by p_name asc";
  $rs = mysql_query($query) or die(mysql_error());
  while($row=mysql_fetch_array($rs)) 
   {
    $p_projects_list2.="<option value='".$row['p_id']."'>".htmlspecialchars($row['p_name']);
    $tmp_list.=",".$row['p_id'];
   } 

  $query="select * from projects where p_status<>2 and p_id not in (".$tmp_list.")";
  $rs = mysql_query($query) or die(mysql_error());
  while($row=mysql_fetch_array($rs)) 
   {
    $p_projects_list.="<option value='".$row['p_id']."'>".htmlspecialchars($row['p_name']);
   }
 }   
?>
<!--br/>
<table border="0" width="60%"-->
  <tr valign="top">
    <td>
      <!--form method="post" name="f2" action="">
      <input type="hidden" name="c_id" value="<?=$c_id?>"-->
	<table border="0" cellpadding="2" cellspacing="2" class="content" width="100%">
	  <tr class="gray" title="<?=$lng[30][20]?>">
	    <td colspan="3" align="center"><b><?=$lng[30][19]?></b></td>
	  </tr>
	 <tr class="blue">
	    <td align="center">
	       <br/><?=$lng[30][21]?><br/>
	       <select name="projects_tmp" multiple size=10 style="width:190px;background:#F3F3F3;">
	       <?=$p_projects_list?>
	       </select><br/><br/>	    
	    </td>
	    <td align="center"><a href="#" onclick="copyToList('projects_tmp','projects_tmp2','f');return false;"><b>==></b></a><br><br><a href="#" onclick="copyToList('projects_tmp2','projects_tmp','f');return false;"><b><==</b></a></td>
	    <td align="center">
	       <br/><?=$lng[30][22]?><br/>
	       <select name="projects_tmp2" multiple size=10 style="width:190px;background:#F3F3F3;">
	       <?=$p_projects_list2?>
	       </select><br/><br/>
	    </td>
	  </tr>
	   <!--tr class="gray">
	    <td colspan="3" align="center">
	       <input type="button" onclick="selectProjects();" value="<?=$lng[10][27]?>">
	    </td>
	  </tr-->  	    
	</table>
      <!--input type="hidden" name="inc" value="edit_case"-->
      <input type="hidden" name="projects_list" value=""> 
      <!--input type="hidden" name="what" value=""> 
      </form-->	
    </td> 	 
  </tr>
<!--/table-->

<?
if ($c_id!="")
 {
  $tmp_list="0";
  $query="select r.* from releases r, release_cases rc where r.r_id=rc.rc_r_id and rc.rc_c_id=".$c_id." order by r_name asc";
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
else
 {
  $tmp_list="0";
  $query="select r.* from releases r, release_cases rc where r.r_id=rc.rc_r_id and rc.rc_c_id=0 order by r_name asc";
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
<!--br/>
<table border="0" width="60%"-->
  <tr valign="top">
    <td>
      <!--form method="post" name="f3" action="">
      <input type="hidden" name="c_id" value="<?=$c_id?>"-->
	<table border="0" cellpadding="2" cellspacing="2" class="content" width="100%">
	  <tr class="gray" title="<?=$lng[30][24]?>">
	    <td colspan="3" align="center"><b><?=$lng[30][23]?></b></td>
	  </tr>
	 <tr class="blue">
	    <td align="center">
	       <br/><?=$lng[30][25]?><br/>
	       <select name="releases_tmp" multiple size=10 style="width:190px;background:#F3F3F3;">
	       <?=$p_releases_list?>
	       </select><br/><br/>	    
	    </td>
	    <td align="center"><a href="#" onclick="copyToList('releases_tmp','releases_tmp2','f');return false;"><b>==></b></a><br><br><a href="#" onclick="copyToList('releases_tmp2','releases_tmp','f');return false;"><b><==</b></a></td>
	    <td align="center">
	       <br/><?=$lng[30][26]?><br/>
	       <select name="releases_tmp2" multiple size=10 style="width:190px;background:#F3F3F3;">
	       <?=$p_releases_list2?>
	       </select><br/><br/>
	    </td>
	  </tr>
	   <!--tr class="gray">
	    <td colspan="3" align="center">
	       <input type="button" onclick="selectReleases();" value="<?=$lng[10][27]?>">
	    </td>
	  </tr-->  	    
	</table>
      <!--input type="hidden" name="inc" value="edit_case"-->
      <input type="hidden" name="releases_list" value=""> 
      <!--input type="hidden" name="what" value=""> 
      </form-->	
    </td> 	 
  </tr>

	  <tr class="gray">
	    <td colspan="2" align="center">
	       
	       <?if ($c_id=="") {?><input type="button" onclick="sub('add');" value="<?=$lng[30][11]?>"><?}?>
	       <?if ($c_id!="") {?><input type="button" onclick="sub('update');" value="<?=$lng[30][11]?>">&nbsp;&nbsp;<input type="button" onclick="if (confirm('<?=$lng[30][10]?>')) sub('delete');" value="<?=$lng[30][18]?>"><?}?>
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
    if (df.c_name.value=="") 
     {
      alert("<?=$lng[30][9]?>");
      df.c_name.focus();	
      return false;
     }
   }
  df.action.value=what;
  selectProjects();
  selectReleases();
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

function selectProjects()
 {
  document.forms['f'].projects_list.value="";
  for (i=0;i<document.forms['f'].projects_tmp2.options.length;i++)
   {	
    document.forms['f'].projects_list.value+=","+document.forms['f'].projects_tmp2.options[i].value;	
   }   
  //document.forms['f'].what.value="projects_list";
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
</script>
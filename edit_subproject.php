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
// Page: "edit subproject" - editing/adding/deleting subproject

//check if logged
if ($_SESSION['rights']!="5") header("Location:index.php");

if ($action=="delete" && $s_id!="")
 {
  $query="delete from subprojects where s_id=".$s_id;
  mysql_query($query) or die(mysql_error());
  header("Location:index.php?inc=manage_subprojects");
 }

  if ($action=="add")
   {
    //work up the text
    $ta=str_replace('<link href="styles.css" rel="stylesheet" />','',stripslashes($ta));

    $query="insert into subprojects (s_name, s_desc, s_p_id) values ('".escapeChars($s_name)."','".stripbr(escapeChars($ta))."','".escapeChars($s_p_id)."')";
    mysql_query($query) or die(mysql_error());
    $s_id=mysql_insert_id();
    header("Location:index.php?inc=manage_subprojects");
   }

  if ($action=="update" && $s_id!="")
   {
    //work up the text
    $ta=str_replace('<link href="styles.css" rel="stylesheet" />','',stripslashes($ta));

    $query="update subprojects set s_name='".escapeChars($s_name)."', s_desc='".stripbr(escapeChars($ta))."', s_p_id='".escapeChars($s_p_id)."' where s_id=".$s_id;
    mysql_query($query) or die(mysql_error());
    header("Location:index.php?inc=manage_subprojects");
   }
 
if ($s_id!="") 
 {
  $query="select * from subprojects where s_id=".$s_id;
  $rs = mysql_query($query) or die(mysql_error());
  if($row=mysql_fetch_array($rs)) 
   {
    $s_name=htmlspecialchars($row['s_name']);
    $s_desc=$row['s_desc'];
    $s_p_id=$row['s_p_id'];
   }
 }  
 
//projects
$query="select * from projects where p_status<>2";
$rs = mysql_query($query) or die(mysql_error());
while($row=mysql_fetch_array($rs)) 
 {
  if ($s_p_id==$row['p_id']) $projects_list.="<option value='".$row['p_id']."' selected>".htmlspecialchars($row['p_name']);
  else $projects_list.="<option value='".$row['p_id']."'>".htmlspecialchars($row['p_name']);
 }
 

?>
<?if ($tmp!="") echo $tmp;?>
<table border="0" width="70%">
  <tr valign="top">
    <td>
      <form method="post" name="f" action="">
      <input type="hidden" name="s_id" value="<?=$s_id?>">
	<table border="0" cellpadding="2" cellspacing="2" class="content" width="100%">
	  <tr class="gray">
	    <td colspan="2" align="center"><b><?=($s_id=="")?$lng[27][2]:$lng[27][1]?></b></td>
	  </tr>
	  <tr class="blue" title="<?=$lng[27][10]?>">
	    <td align="right" nowrap>&nbsp;<?=$lng[27][3]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;<input type="text" name="s_name" value="<?=$s_name?>" maxlength="90" size=60></td>
	  </tr>  
	  <tr class="blue" valign=top title="<?=$lng[27][11]?>">
	    <td align="right">&nbsp;<?=$lng[27][4]?>&nbsp;:&nbsp;</td>
	    <td><? 
		include("FCKeditor/fckeditor.php");
		$oFCKeditor = new FCKeditor('ta') ;
		$oFCKeditor->BasePath = 'FCKeditor/' ;
		$oFCKeditor->Value = $s_desc;
		$oFCKeditor->Width = '560';
		$oFCKeditor->Height = '300';
		$oFCKeditor->Create() ;
		?> 
	    </td>
	  </tr>  
	  <tr class="light_blue" title="<?=$lng[27][12]?>">
	    <td align="right">&nbsp;<?=$lng[27][5]?>&nbsp;:&nbsp;</td>
	    <td>
	      <select name="s_p_id">
	        <option value=''>--
	        <?=$projects_list?>
	      </select>   
	    </td>
	  </tr>  
	  <tr class="gray">
	    <td colspan="2" align="center">
	       <?if ($s_id=="") {?><input type="button" onclick="sub('add');" value="<?=$lng[27][2]?>"><?}?>
	       <?if ($s_id!="") {?><input type="button" onclick="sub('update');" value="<?=$lng[27][6]?>">&nbsp;&nbsp;<input type="button" onclick="if (confirm('<?=$lng[27][9]?>')) sub('delete');" value="<?=$lng[27][7]?>"><?}?>
	    </td>
	  </tr>  	    
	</table>
      <input type="hidden" name="inc" value="edit_subproject">
      <input type="hidden" name="action" value="">	
      </form>	
    </td> 	 
  </tr>
</table>


<script>
function sub(what)
 {
  df=document.forms['f'];
  if (what!="delete") 
   {
    if (df.s_name.value=="") 
     {
      alert("<?=$lng[27][8]?>");
      df.s_name.focus();	
      return false;
     }
   }
  df.action.value=what;
  df.submit();	     
 }
 
</script>
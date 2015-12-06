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
// Page: "edit component" - editing/adding/deleting component

//check if logged
if (!($_SESSION['rights']=="1" || $_SESSION['rights']=="2" || $_SESSION['rights']=="3" || $_SESSION['rights']=="4" || $_SESSION['rights']=="5")) header("Location:index.php");

if ($action=="delete" && $c_id!="")
 {
  $query="delete from project_components where pco_c_id=".$c_id;
  mysql_query($query) or die(mysql_error());
  
  $query="delete from components where c_id=".$c_id;
  mysql_query($query) or die(mysql_error());
  header("Location:index.php?inc=manage_components");
 }

if ($action=="add")
 {
  $query="insert into components (c_name,c_global) values ('".escapeChars($c_name)."','".escapeChars($c_global)."')";
  mysql_query($query) or die(mysql_error());
  $c_id=mysql_insert_id();;
  header("Location:index.php?inc=manage_components");
 }

if ($action=="update" && $c_id!="")
 {
  $query="update components set c_name='".escapeChars($c_name)."',c_global='".escapeChars($c_global)."' where c_id=".$c_id;
  mysql_query($query) or die(mysql_error());
  header("Location:index.php?inc=manage_components");
 }
 
if ($c_id!="") 
 {
  $query="select * from components where c_id=".$c_id;
  $rs = mysql_query($query) or die(mysql_error());
  if($row=mysql_fetch_array($rs)) 
   {
    $c_name=htmlspecialchars($row['c_name']);
    $c_global=htmlspecialchars($row['c_global']);
   }
 }  
 ?>
<table border="0" width="50%">
  <tr valign="top">
    <td>
      <form method="post" name="f" action="">
      <input type="hidden" name="c_id" value="<?=$c_id?>">
	<table border="0" cellpadding="2" cellspacing="2" class="content" width="100%">
	  <tr class="gray">
	    <td colspan="2" align="center"><b><?=($c_id=="")?$lng[37][2]:$lng[37][1]?></b></td>
	  </tr>
	  <tr class="blue" title="<?=$lng[37][13]?>">
	    <td align="right">&nbsp;<?=$lng[37][3]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;<input type="text" name="c_name" value="<?=$c_name?>" maxlength="90" size=35></td>
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
	  <tr class="gray">
	    <td colspan="2" align="center">
	       <?if ($c_id!="") {?><input type="button" onclick="sub('update');" value="<?=$lng[37][7]?>">&nbsp;&nbsp;<input type="button" onclick="if (confirm('<?=$lng[37][10]?>')) sub('delete');" value="<?=$lng[37][8]?>"><?}?>
	       <?if ($c_id=="") {?><input type="button" onclick="sub('add');" value="<?=$lng[37][9]?>"><?}?>
	       &nbsp;<input type="button" onclick="document.location.href='index.php?inc=manage_components'" value="<?=$lng[37][17]?>">
	    </td>
	  </tr>   
	</table>
      <input type="hidden" name="inc" value="edit_component">
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
    if (df.c_name.value=="") 
     {
      alert("<?=$lng[37][11]?>");
      df.c_name.focus();	
      return false;
     } 

   }
  df.action.value=what;
  df.submit();	     
 }
 </script>
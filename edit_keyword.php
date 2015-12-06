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
// Page: "edit keywords" - editing/adding/deleting keywords

//check if logged
if (!($_SESSION['rights']=="1" || $_SESSION['rights']=="2" || $_SESSION['rights']=="3" || $_SESSION['rights']=="4" || $_SESSION['rights']=="5")) header("Location:index.php");

if ($action=="delete" && $k_id!="")
 {
  $query="delete from keywords where k_id=".$k_id;
  mysql_query($query) or die(mysql_error());
  header("Location:index.php?inc=manage_keywords");
 }

if ($action=="add")
 {
  $query="insert into keywords (k_name,k_global) values ('".escapeChars($k_name)."','".escapeChars($k_global)."')";
  mysql_query($query) or die(mysql_error());
  $k_id=mysql_insert_id();
  header("Location:index.php?inc=manage_keywords");
 }

if ($action=="update" && $k_id!="")
 {
  $query="update keywords set k_name='".escapeChars($k_name)."', k_global='".escapeChars($k_global)."' where k_id=".$k_id;
  mysql_query($query) or die(mysql_error());
  header("Location:index.php?inc=manage_keywords");
 }
 
if ($k_id!="") 
 {
  $query="select * from keywords where k_id=".$k_id;
  $rs = mysql_query($query) or die(mysql_error());
  if($row=mysql_fetch_array($rs)) 
   {
    $k_name=htmlspecialchars($row['k_name']);
    $k_global=htmlspecialchars($row['k_global']);
   }
 }  
 ?>
<table border="0" width="50%">
  <tr valign="top">
    <td>
      <form method="post" name="f" action="">
      <input type="hidden" name="k_id" value="<?=$k_id?>">
	<table border="0" cellpadding="2" cellspacing="2" class="content" width="100%">
	  <tr class="gray">
	    <td colspan="2" align="center"><b><?=($k_id=="")?$lng[33][2]:$lng[33][1]?></b></td>
	  </tr>
	  <tr class="blue" title="<?=$lng[33][8]?>">
	    <td align="right">&nbsp;<?=$lng[33][3]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;<input type="text" name="k_name" value="<?=$k_name?>" maxlength="90" size=35></td>
	  </tr>  
	  <tr class="blue" title="<?=$lng[13][13]?>">
	    <td align="right">&nbsp;<?=$lng[13][12]?>&nbsp;:&nbsp;</td>
	    <td>
	    <select name="k_global">
	      <option value="0"><?=$lng[13][14]?>
	      <option value="1" <?if ($k_global==1) echo "selected";?>><?=$lng[13][15]?>
	    </select>
	    </td>
	  </tr>  
	  <tr class="gray">
	    <td colspan="2" align="center">
	       <?if ($k_id!="") {?><input type="button" onclick="sub('update');" value="<?=$lng[33][4]?>">&nbsp;&nbsp;<input type="button" onclick="if (confirm('<?=$lng[33][6]?>')) sub('delete');" value="<?=$lng[33][5]?>"><?}?>
	       <?if ($k_id=="") {?><input type="button" onclick="sub('add');" value="<?=$lng[33][2]?>"><?}?>
	       &nbsp;<input type="button" onclick="document.location.href='index.php?inc=manage_keywords'" value="<?=$lng[33][9]?>">
	    </td>
	  </tr>   
	</table>
      <input type="hidden" name="inc" value="edit_keyword">
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
    if (df.k_name.value=="") 
     {
      alert("<?=$lng[33][7]?>");
      df.k_name.focus();	
      return false;
     } 
   }
  df.action.value=what;
  df.submit();	     
 }
 </script>
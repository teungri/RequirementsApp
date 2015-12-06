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
// Page: "edit release" - editing/adding/deleting releases

//check if logged
if (!($_SESSION['rights']=="3" || $_SESSION['rights']=="4" || $_SESSION['rights']=="5")) header("Location:index.php");

if ($action=="delete" && $r_id!="")
 {
  $query="delete from project_releases where pr_r_id=".$r_id;
  mysql_query($query) or die(mysql_error());
  
  $query="delete from releases where r_id=".$r_id;
  mysql_query($query) or die(mysql_error());
  header("Location:index.php?inc=manage_releases");
 }

if ($action=="add")
 {
  if (!isValidDate($r_date)) {$tmp=$lng[14][13];}
  if ($r_released_date!="" && !isValidDate($r_released_date)) {$tmp=$lng[14][14];}
  if ($tmp=="")
   {
    $r_date=parseDate($r_date, "%d.%m.%Y"); //parsing date into mysql format
    $r_released_date=parseDate($r_released_date, "%d.%m.%Y"); //parsing date into mysql format
    $query="insert into releases (r_name, r_date, r_released_date,r_global) values ('".escapeChars($r_name)."','".escapeChars($r_date)."','".escapeChars($r_released_date)."','".escapeChars($r_global)."')";
    mysql_query($query) or die(mysql_error());
    $r_id=mysql_insert_id();;
    header("Location:index.php?inc=manage_releases");
   } 
 }

if ($action=="update" && $r_id!="")
 {
  if (!isValidDate($r_date)) {$tmp=$lng[14][13];}
  if ($r_released_date!="" && !isValidDate($r_released_date)) {$tmp=$lng[14][14];}
  if ($tmp=="")
   {
    $r_date=parseDate($r_date, "%d.%m.%Y"); //parsing date into mysql format
    $r_released_date=parseDate($r_released_date, "%d.%m.%Y"); //parsing date into mysql format
    $query="update releases set r_name='".escapeChars($r_name)."', r_date='".escapeChars($r_date)."', r_released_date='".escapeChars($r_released_date)."', r_global='".escapeChars($r_global)."' where r_id=".$r_id;
    mysql_query($query) or die(mysql_error());
    header("Location:index.php?inc=manage_releases");
   } 
 }
 
if ($r_id!="") 
 {
  $query="select *, date_format(r_date, '%d.%m.%Y') as d1, date_format(r_released_date, '%d.%m.%Y') as d2 from releases where r_id=".$r_id;
  $rs = mysql_query($query) or die(mysql_error());
  if($row=mysql_fetch_array($rs)) 
   {
    $r_name=htmlspecialchars($row['r_name']);
    $r_date=$row['d1'];
    $r_released_date=$row['d2'];
    $r_global=$row['r_global'];
   }
 }  
 ?>
<table border="0" width="50%">
  <tr valign="top">
    <td>
      <form method="post" name="f" action="">
      <input type="hidden" name="r_id" value="<?=$r_id?>">
	<table border="0" cellpadding="2" cellspacing="2" class="content" width="100%">
	<?if ($tmp!="") {?>
	  <tr>
	    <td class="error" align="center" colspan="2">&nbsp;<?=$tmp?></td>
	  </tr> 
	<?}?>   
	  <tr class="gray">
	    <td colspan="2" align="center"><b><?=($r_id=="")?$lng[14][2]:$lng[14][1]?></b></td>
	  </tr>
	  <tr class="blue" title="<?=$lng[13][6]?>">
	    <td align="right">&nbsp;<?=$lng[14][3]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;<input type="text" name="r_name" value="<?=$r_name?>" maxlength="90" size=35></td>
	  </tr>  
	  <tr class="blue" title="<?=$lng[13][8]?>">
	    <td align="right">&nbsp;<?=$lng[14][5]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;<input type="text" name="r_date" value="<?=$r_date?>" maxlength="90" size=10></td>
	  </tr>  
	  <tr class="blue" title="<?=$lng[13][9]?>">
	    <td align="right">&nbsp;<?=$lng[14][6]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;<input type="text" maxlength="90" name="r_released_date" value="<?=($r_released_date=="00.00.0000")?"":$r_released_date?>" size=10></td>
	  </tr>  
	  <tr class="blue" title="<?=$lng[13][13]?>">
	    <td align="right">&nbsp;<?=$lng[13][12]?>&nbsp;:&nbsp;</td>
	    <td>
	    <select name="r_global">
	      <option value="0"><?=$lng[13][14]?>
	      <option value="1" <?if ($r_global==1) echo "selected";?>><?=$lng[13][15]?>
	    </select>
	    </td>
	  </tr>  
	  <tr class="gray">
	    <td colspan="2" align="center">
	       <?if ($r_id!="") {?><input type="button" onclick="sub('update');" value="<?=$lng[14][7]?>">&nbsp;&nbsp;<input type="button" onclick="if (confirm('<?=$lng[14][10]?>')) sub('delete');" value="<?=$lng[14][8]?>"><?}?>
	       <?if ($r_id=="") {?><input type="button" onclick="sub('add');" value="<?=$lng[14][9]?>"><?}?>
	       &nbsp;<input type="button" onclick="document.location.href='index.php?inc=manage_releases'" value="<?=$lng[2][22]?>">
	    </td>
	  </tr>   
	</table>
      <input type="hidden" name="inc" value="edit_release">
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
    if (df.r_name.value=="") 
     {
      alert("<?=$lng[14][11]?>");
      df.r_name.focus();	
      return false;
     } 
    if (df.r_date.value=="") 
     {
      alert("<?=$lng[14][12]?>");
      df.r_date.focus();	
      return false;
     }  
   }
  df.action.value=what;
  df.submit();	     
 }
 </script>
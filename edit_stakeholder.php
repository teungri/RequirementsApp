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
// Page: "edit stakeholder" - editing/adding/deleting stakeholder

//check if logged
if (!($_SESSION['rights']=="5")) header("Location:index.php");

if ($action=="delete" && $s_id!="")
 {
  $query="delete from project_stakeholders where ps_s_id=".$s_id;
  mysql_query($query) or die(mysql_error());
  
  $query="delete from stakeholders where s_id=".$s_id;
  mysql_query($query) or die(mysql_error());
  header("Location:index.php?inc=manage_stakeholders");
 }

if ($action=="add")
 {
  $query="insert into stakeholders (s_name, s_function, s_email, s_interests, s_global) values ('".escapeChars($s_name)."','".escapeChars($s_function)."','".escapeChars($s_email)."','".escapeChars($s_interests)."','".escapeChars($s_global)."')";
  mysql_query($query) or die(mysql_error());
  $s_id=mysql_insert_id();;
  header("Location:index.php?inc=manage_stakeholders");
 }

if ($action=="update" && $s_id!="")
 {
  $query="update stakeholders set s_name='".escapeChars($s_name)."', s_function='".escapeChars($s_function)."', s_email='".escapeChars($s_email)."', s_interests='".escapeChars($s_interests)."', s_global='".escapeChars($s_global)."' where s_id=".$s_id;
  mysql_query($query) or die(mysql_error());
  header("Location:index.php?inc=manage_stakeholders");
 }
 
if ($s_id!="") 
 {
  $query="select * from stakeholders where s_id=".$s_id;
  $rs = mysql_query($query) or die(mysql_error());
  if($row=mysql_fetch_array($rs)) 
   {
    $s_name=htmlspecialchars($row['s_name']);
    $s_function=htmlspecialchars($row['s_function']);
    $s_email=htmlspecialchars($row['s_email']);
    $s_interests=htmlspecialchars($row['s_interests']);
    $s_global=htmlspecialchars($row['s_global']);
   }
 }  
 ?>
<table border="0" width="50%">
  <tr valign="top">
    <td>
      <form method="post" name="f" action="">
      <input type="hidden" name="s_id" value="<?=$s_id?>">
	<table border="0" cellpadding="2" cellspacing="2" class="content" width="100%">
	  <tr class="gray">
	    <td colspan="2" align="center"><b><?=($s_id=="")?$lng[23][2]:$lng[23][1]?></b></td>
	  </tr>
	  <tr class="blue" title="<?=$lng[23][13]?>">
	    <td align="right">&nbsp;<?=$lng[23][3]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;<input type="text" name="s_name" value="<?=$s_name?>" maxlength="90" size=35></td>
	  </tr>  
	  <tr class="blue" title="<?=$lng[23][14]?>">
	    <td align="right">&nbsp;<?=$lng[23][5]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;<input type="text" name="s_function" value="<?=$s_function?>" maxlength="90" size=35></td>
	  </tr>  
	  <tr class="blue" title="<?=$lng[23][15]?>">
	    <td align="right">&nbsp;<?=$lng[23][6]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;<input type="text" name="s_email" value="<?=$s_email?>" maxlength="90" size=35></td>
	  </tr>  
	  <tr class="blue" title="<?=$lng[23][16]?>">
	    <td align="right" valign="top">&nbsp;<?=$lng[23][12]?>&nbsp;:&nbsp;</td>
	    <td><textarea name="s_interests" rows="5" cols="37"><?=$s_interests?></textarea></td>
	  </tr>  
	  <tr class="blue" title="<?=$lng[13][13]?>">
	    <td align="right">&nbsp;<?=$lng[13][12]?>&nbsp;:&nbsp;</td>
	    <td>
	    <select name="s_global">
	      <option value="0"><?=$lng[13][14]?>
	      <option value="1" <?if ($s_global==1) echo "selected";?>><?=$lng[13][15]?>
	    </select>
	    </td>
	  </tr>  
	  <tr class="gray">
	    <td colspan="2" align="center">
	       <?if ($s_id!="") {?><input type="button" onclick="sub('update');" value="<?=$lng[23][7]?>">&nbsp;&nbsp;<input type="button" onclick="if (confirm('<?=$lng[23][10]?>')) sub('delete');" value="<?=$lng[23][8]?>"><?}?>
	       <?if ($s_id=="") {?><input type="button" onclick="sub('add');" value="<?=$lng[23][9]?>"><?}?>
	       &nbsp;<input type="button" onclick="document.location.href='index.php?inc=manage_stakeholders'" value="<?=$lng[23][17]?>">
	    </td>
	  </tr>   
	</table>
      <input type="hidden" name="inc" value="edit_stakeholder">
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
      alert("<?=$lng[23][11]?>");
      df.s_name.focus();	
      return false;
     } 
    var filter=/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i
    if (df.s_email.value!="" && !filter.test(df.s_email.value)) 
     {
      alert("<?=$lng[12][11]?>");
      df.s_email.focus();	
      return false;
     }      

   }
  df.action.value=what;
  df.submit();	     
 }
 </script>
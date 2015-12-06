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
// Page: "edit glossary" - editing/adding/deleting glossary

//check if logged
if (!($_SESSION['rights']=="5")) header("Location:index.php");

if ($action=="delete" && $g_id!="")
 {
  $query="delete from project_glossary where pg_g_id=".$g_id;
  mysql_query($query) or die(mysql_error());
  
  $query="delete from glossary where g_id=".$g_id;
  mysql_query($query) or die(mysql_error());
  header("Location:index.php?inc=manage_glossary");
 }

if ($action=="add")
 {
  //work up the text
  $ta=str_replace('<link href="styles.css" rel="stylesheet" />','',stripslashes($ta));
 
  $query="insert into glossary (g_name, g_term, g_abbreviation, g_desc,g_global) values ('".escapeChars($g_name)."','".escapeChars($g_term)."','".escapeChars($g_abbreviation)."','".stripbr(escapeChars($ta))."','".escapeChars($g_global)."')";
  mysql_query($query) or die(mysql_error());
  $g_id=mysql_insert_id();
  header("Location:index.php?inc=manage_glossary");
 }

if ($action=="update" && $g_id!="")
 {
  //work up the text
  $ta=str_replace('<link href="styles.css" rel="stylesheet" />','',stripslashes($ta));
 
  $query="update glossary set g_name='".escapeChars($g_name)."', g_term='".escapeChars($g_term)."', g_abbreviation='".escapeChars($g_abbreviation)."', g_desc='".stripbr(escapeChars($ta))."', g_global='".escapeChars($g_global)."' where g_id=".$g_id;
  mysql_query($query) or die(mysql_error());
  header("Location:index.php?inc=manage_glossary");
 }
 
if ($g_id!="") 
 {
  $query="select * from glossary where g_id=".$g_id;
  $rs = mysql_query($query) or die(mysql_error());
  if($row=mysql_fetch_array($rs)) 
   {
    $g_name=htmlspecialchars($row['g_name']);
    $g_term=htmlspecialchars($row['g_term']);
    $g_abbreviation=htmlspecialchars($row['g_abbreviation']);
    $g_global=htmlspecialchars($row['g_global']);
    $ta=($row['g_desc']);
   }
 }  
 ?>
<table border="0" width="50%">
  <tr valign="top">
    <td>
      <form method="post" name="f" action="">
      <input type="hidden" name="g_id" value="<?=$g_id?>">
	<table border="0" cellpadding="2" cellspacing="2" class="content" width="100%">
	  <tr class="gray">
	    <td colspan="2" align="center"><b><?=($g_id=="")?$lng[25][2]:$lng[25][1]?></b></td>
	  </tr>
	  <?if ($g_id!="") {?>
	  <tr class="blue" title="<?=$lng[25][13]?>">
	    <td align="right">&nbsp;<?=$lng[25][3]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;<?for ($i=0;$i<6-strlen($g_id);$i++) echo "0";echo $g_id;?></td>
	  </tr>  
	  <?}?>
	  <tr class="blue" title="<?=$lng[25][14]?>">
	    <td align="right">&nbsp;<?=$lng[25][5]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;<input type="text" name="g_term" value="<?=$g_term?>" maxlength="90" size=35></td>
	  </tr>  
	  <tr class="blue" title="<?=$lng[25][15]?>">
	    <td align="right">&nbsp;<?=$lng[25][6]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;<input type="text" name="g_abbreviation" value="<?=$g_abbreviation?>" maxlength="90" size=35></td>
	  </tr>  
	  <tr class="blue" title="<?=$lng[25][16]?>">
	    <td align="right" valign="top">&nbsp;<?=$lng[25][12]?>&nbsp;:&nbsp;</td>
	    <td>
		<?
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
	  <tr class="blue" title="<?=$lng[13][13]?>">
	    <td align="right">&nbsp;<?=$lng[13][12]?>&nbsp;:&nbsp;</td>
	    <td>
	    <select name="g_global">
	      <option value="0"><?=$lng[13][14]?>
	      <option value="1" <?if ($g_global==1) echo "selected";?>><?=$lng[13][15]?>
	    </select>
	    </td>
	  </tr>  
	  <tr class="gray">
	    <td colspan="2" align="center">
	       <?if ($g_id!="") {?><input type="button" onclick="sub('update');" value="<?=$lng[25][7]?>">&nbsp;&nbsp;<input type="button" onclick="if (confirm('<?=$lng[25][10]?>')) sub('delete');" value="<?=$lng[25][8]?>"><?}?>
	       <?if ($g_id=="") {?><input type="button" onclick="sub('add');" value="<?=$lng[25][9]?>"><?}?>
	       &nbsp;<input type="button" onclick="document.location.href='index.php?inc=manage_glossary'" value="<?=$lng[25][17]?>">
	    </td>
	  </tr>   
	</table>
      <input type="hidden" name="inc" value="edit_glossary">
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
    if (df.g_abbreviation.value=="") 
     {
      alert("<?=$lng[25][18]?>");
      df.g_abbreviation.focus();	
      return false;
     } 
   }
  df.action.value=what;
  df.submit();	     
 }
 </script>
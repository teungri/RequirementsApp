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
// -----------------------------------------------------------------
// Page: "add comment" - comment requirements
// -----------------------------------------------------------------
//


//check if logged
if (!($_SESSION['rights']=="0" || $_SESSION['rights']=="1" || $_SESSION['rights']=="2" || $_SESSION['rights']=="3" || $_SESSION['rights']=="4" || $_SESSION['rights']=="5")) header("Location:index.php");

if ($r_id!="")
 {
  //authorization check
  $query="select r.* from requirements r, projects p where r.r_id=".$r_id." and ((r.r_p_id=p.p_id and p.p_id in (".$project_list.")) OR r.r_p_id=0)";
  $rs = mysql_query($query) or die(mysql_error());
  if($row=mysql_fetch_array($rs)) ;
  else header("Location:index.php");
 }
 
if ($action=="add")
 {
  //work up the text
  $ta=str_replace('<link href="styles.css" rel="stylesheet" />','',stripslashes($ta));
  
  if ($_SESSION['rights']=="0") $c_question=1;
  $query="insert into comments (c_r_id, c_u_id, c_text, c_date, c_question) values ('".escapeChars($r_id)."','".$_SESSION['uid']."','".stripbr(escapeChars($ta))."',DATE_ADD( NOW( ) , INTERVAL - ".TIME_DIFF_HOURS." HOUR),'".escapeChars($c_question)."')";
  if ($ta!="") mysql_query($query) or die(mysql_error());
  if ($what=="long") header("Location:index.php?inc=view_requirement_long&r_id=".$r_id);
  else header("Location:index.php?inc=view_requirement&r_id=".$r_id);  
 }
?>
<table border="0" width="100%">
  <tr valign="top">
    <td>
      <form method="post" name="edit" name="edit" action="" enctype='multipart/form-data'>
      <input type="hidden" name="r_id" value="<?=$r_id?>">
      <input type="hidden" name="what" value="<?=$what?>">
	<table border="0" cellpadding="2" cellspacing="2" class="content" width="100%">
	  <tr class="gray">
	    <td align="center"><b><?=$lng[18][1]?></b></td>
	  </tr>
	  <tr class="light_blue" title="<?=$lng[18][4]?>">
	    <td align="left"><b><?=$lng[18][3]?></b>&nbsp;<input name="c_question" type="checkbox" value="1"></td>
	  </tr>
	  <tr class="blue" valign="top">
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
	  <tr class="gray">
	    <td colspan="2" align="center">
	       <input type="button" onclick="sub();" value="<?=$lng[18][2]?>">
	    </td>
	  </tr>   
	</table>
      <input type="hidden" name="inc" value="add_comment">
      <input type="hidden" name="action" value="add">	
      </form>	
    </td> 	 
  </tr>
</table>



<script>
function sub()
 {
  df=document.forms['edit'];
  df.submit();	     
 }
 </script>
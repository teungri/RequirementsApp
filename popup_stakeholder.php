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
// Page: "popup stakeholders" - editing/adding/deleting stakeholders in a popup

session_start();

include ("admin/inc/conn.php");//include settings file
include ("admin/inc/func.php");//include functions file
include ("ini/params.php");//include configuration file

//setting referer if not logged
if ($_SESSION['uid']=="" && $_SERVER['QUERY_STRING']!="" && !strstr($_SERVER['QUERY_STRING'],'login'))
{
 $_SESSION['http_ref']=$_SERVER['QUERY_STRING'];
} 


//default language
if ($_chlang!="") $_SESSION['chlang']=$_chlang;
if (!$_SESSION['chlang']) $_SESSION['chlang']="en";
include ("ini/lng/".$_SESSION['chlang'].".php");//include language file

//check if logged
if (!($_SESSION['rights']=="5")) header("Location:index.php");

//check if project applied
if ($p_id=="" || $p_id=="0") 
 {
  ?>
  <script>self.close();</script>
  <?
 }
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="description" content="<?=$lng[1][2]?>"/>
	<meta name="keywords" content="<?=$lng[1][3]?>"/>
	<title><?=$lng[1][1]?></title>
	<link rel='STYLESHEET' type='text/css' href='dhtmlxTree/samples/common/style.css'>
	<link rel="stylesheet" href="s.css" type="text/css"/>
</head>
<body bgcolor="#ffffff">

<?
if ($action=="add")
 {
  $query="insert into stakeholders (s_name, s_function, s_email, s_interests, s_global) values ('".escapeChars($s_name)."','".escapeChars($s_function)."','".escapeChars($s_email)."','".escapeChars($s_interests)."','".escapeChars($s_global)."')";
  mysql_query($query) or die(mysql_error());
  $s_id=mysql_insert_id();  
    
        $query="insert into project_stakeholders (ps_p_id, ps_s_id) values ('".$p_id."','".$s_id."')";
        mysql_query($query) or die(mysql_error());

    ?>
    <script>
      <?if ($where!="1") {?>
      opener.change_select();
      opener.document.forms['edit'].r_stakeholder.value+=<?=$s_id?>+",";
      opener.document.forms['edit'].tmp_p_id.value=opener.document.forms['edit'].r_p_id.value;
      opener.document.forms['edit'].submit();
      self.close();
      <?}else{?>
      opener.document.forms['f'].submit();
      self.close();
      <?}?>      
    </script>
    
    <?   
 }
 ?>
<table border="0" width="100%">
  <tr valign="top">
    <td>
      <form method="post" name="f" action="">
<table border="0" width="100%">
  <tr valign="top">
    <td>
      <input type="hidden" name="c_id" value="<?=$c_id?>">
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
	       <input type="button" onclick="sub('add');" value="<?=$lng[23][2]?>">
	    </td>
	  </tr>   
	</table>

    </td> 	 
  </tr>	    
</table>
      <input type="hidden" name="inc" value="edit_case">
      <input type="hidden" name="p_id" value="<?=$p_id?>">
      <input type="hidden" name="where" value="<?=$where?>">
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
 
 </body>
 </html>
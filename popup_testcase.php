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
// Page: "popup testcase" - editing/adding/deleting testcases in a popup

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
if (!($_SESSION['rights']=="1" || $_SESSION['rights']=="2" || $_SESSION['rights']=="3" || $_SESSION['rights']=="4" || $_SESSION['rights']=="5")) header("Location:index.php");

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
    //work up the text
    //work up the text
    $ta=str_replace('<link href="styles.css" rel="stylesheet" />','',stripslashes($ta));
    $ta2=str_replace('<link href="styles.css" rel="stylesheet" />','',stripslashes($ta2));

    $query="insert into cases (c_name, c_desc, c_result, c_status, c_global) values ('".escapeChars($c_name)."','".stripbr(escapeChars($ta))."','".stripbr(escapeChars($ta2))."','".escapeChars($c_status)."','".escapeChars($c_global)."')";
    mysql_query($query) or die(mysql_error());
    $c_id=mysql_insert_id();
    
    $query="insert into project_cases (pc_c_id, pc_p_id) values ('".$c_id."','".$p_id."')";
    mysql_query($query) or die(mysql_error());

    ?>
    <script>
      <?if ($where!="1") {?>
      opener.change_select();
      opener.document.forms['edit'].r_c_id.value+=<?=$c_id?>+",";
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
	  <tr class="gray">
	    <td colspan="2" align="center">
	       <input type="button" onclick="sub('add');" value="<?=$lng[30][2]?>">
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
    if (df.c_name.value=="") 
     {
      alert("<?=$lng[30][9]?>");
      df.c_name.focus();	
      return false;
     }
   }
  df.action.value=what;
  df.submit();	 
 }
 
</script>
 
 </body>
 </html>
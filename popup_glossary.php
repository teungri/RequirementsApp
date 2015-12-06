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
// Page: "popup glossary" - editing/adding/deleting glossary in a popup

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
  $query="insert into glossary (g_name, g_term, g_abbreviation, g_desc, g_global) values ('".escapeChars($g_name)."','".escapeChars($g_term)."','".escapeChars($g_abbreviation)."','".stripbr(escapeChars($ta))."','".escapeChars($g_global)."')";
  mysql_query($query) or die(mysql_error());
  $g_id=mysql_insert_id();
    
        $query="insert into project_glossary (pg_p_id, pg_g_id) values ('".$p_id."','".$g_id."')";
        mysql_query($query) or die(mysql_error());

    ?>
    <script>
      <?if ($where!="1") {?>
      opener.change_select();
      opener.document.forms['edit'].r_glossary.value+=<?=$g_id?>+",";
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
	       <input type="button" onclick="sub('add');" value="<?=$lng[25][2]?>">
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
 
 </body>
 </html>
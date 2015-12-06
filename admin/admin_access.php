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
// Page: "Modify/Insert single User " - modifiyng/inserting the data for a single user
?>
<?include("inc/conn.php");?>
<?include("inc/func.php");?>
<?include("inc/conn_admin.php");?>
<?
if ($_POST['action']=="insert" && $aa_id=="")
 {
  //inserting new user
  $query="insert into admin_access (aa_username,aa_password) values ('".escapeChars($_POST['aa_username'])."','".pw($_POST['aa_password'])."')";
  mysql_query($query) or die($query."<br/>".mysql_error());
  $aa_id=mysql_insert_id();
 }

if ($_POST['action']=="update" && $_POST['aa_id']!="")
 {
  //updating user
  if ($aa_password!="") $query="update admin_access set aa_username='".escapeChars($_POST['aa_username'])."',aa_password='".pw($_POST['aa_password'])."' where aa_id=".$_POST['aa_id'];
  else $query="update admin_access set aa_username='".escapeChars($_POST['aa_username'])."' where aa_id=".$_POST['aa_id'];
  mysql_query($query) or die($query."<br>".mysql_error());
  if ($_SESSION['ses_username']==$us_tmp)
   {
    $_SESSION['ses_username']=$_POST['aa_username'];
    $_SESSION['ses_password']=$_POST['aa_password'];  
   } 
 }
?>
<?
if ($aa_id!="")
 {
  //getting data for the selected user from previous page
  $query="select * from admin_access where aa_id=".$aa_id;
  $rs = mysql_query($query) or die(mysql_error());
  if($row=mysql_fetch_array($rs))
   {
    $aa_id=htmlspecialchars($row['aa_id']);
    $aa_username=htmlspecialchars($row['aa_username']);
    //$aa_password=htmlspecialchars($row['aa_password']);
   }
 }  
?>
<script>
function subm(what)
 {
  //validation checks
  if (document.forms[0].elements.aa_username.value.length==0) 
   {
    alert('<?=$lng[99][11]?>');
    document.forms[0].elements.aa_username.focus();
    return;
   }
  <?if ($aa_id==""){?> 
  if (document.forms[0].elements.aa_password.value.length==0) 
   {
    alert('<?=$lng[99][12]?>');
    document.forms[0].elements.aa_password.focus();
    return;
   }
  <?}?> 
  document.forms[0].action.value=what;
  document.forms[0].submit();
 }
</script>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<LINK HREF="css/styles_admin.css" REL=stylesheet>
</head>
<body bgcolor=#E6E6E6 topmargin=0 leftmargin=0>
<center><br><br>
<table border="1" cellpadding="0" cellspacing="0" width="85%">
<form method=post enctype='multipart/form-data'>
<input type=hidden name="aa_id" value="<?=$aa_id?>">
<input type=hidden name="us_tmp" value="<?=$aa_username?>">
  <tr bgcolor=#C4D4D7>
    <td colspan=2 height=45 align=center><b>:&nbsp;:&nbsp;&nbsp;<?=$lng[99][13]?></b></td>
  </tr>
  <tr bgcolor=#E6E6E6>
    <td align=right nowrap width=300>&nbsp;<?=$lng[99][2]?>&nbsp;&nbsp;</td>
    <td align=left>
      <input type="text" name="aa_username" value="<?=$aa_username?>" size=61 maxlength=255>
    </td>
  </tr>
  <tr bgcolor=#D7D7D7>
    <td align=right nowrap width=300>&nbsp;<?if ($aa_id!="") echo $lng[99][21]." ";?><?=$lng[99][3]?>&nbsp;&nbsp;</td>
    <td align=left>
      <input type="text" name="aa_password" value="" size=61 maxlength=255>
    </td>
  </tr>
  <tr>
    <td colspan=2 height=35 align=center>
      <input type=hidden name=action value="">
      <input type=hidden name=table value=admin_access>
   <?
	 if ($aa_id=="" || $aa_id=="0")
	  {
	   ?>
	      <img src="img/b.gif" width=60 height=1>
	      <input type=button value=<?=$lng[99][32]?> onclick="subm('insert')">
	   <?
	  }
	 else
	  {
	   ?>
	      <input type=button value="<?=$lng[99][14]?>" onclick="subm('update')">
	   <?
	  }
   ?>
     
      
      &nbsp;&nbsp;<input type=button value="<?=$lng[99][31]?>" onclick="document.location='admin_access_select.php'">
    </td>
  </tr>
</form>
</table>
</center>
</body>
</html>

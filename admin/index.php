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
// Page: "Homepage" - requiring login
?>
<?include("inc/conn.php");?>
<?include("inc/func.php");?>
<?include("inc/conn_admin.php");?>
<?
if ($_POST['username']!="" && $_POST['password']!="")
 {
  //if logged - creating session vars
  session_cache_limiter('');
  ini_set('session.gc_maxlifetime', '121600');   	
  $ses_username=$_POST['username'];
  $_SESSION['ses_username']=$ses_username; 
  //session_register("ses_username");
  $ses_password=$_POST['password'];
  $_SESSION['ses_password']=$ses_password; 
  //session_register("ses_password"); 
  
  $query="select * from admin_access where aa_username='".escapeChars($ses_username)."' and aa_password='".pw($ses_password)."'";
  $rs = mysql_query($query) or die(mysql_error());
  if($row=mysql_fetch_array($rs)) header("Location:frameset.html");  
 } 
?> 
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<LINK HREF="css/styles_admin.css" REL=stylesheet>
</head>
<body bgcolor=#E6E6E6 topmargin=0 leftmargin=0>
<br>
<center>
<form method=post>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td align=center height=30 class="td_no_border"><img src="img/b.gif" width=1 height=1></td>
  </tr>
  <tr>
    <td align=center height=30 class=title><?=$lng[99][1]?></td>
  </tr>
  <tr>
    <td align=center height=50 class="td_no_border"><img src="img/b.gif" width=1 height=1></td>
  </tr>
  <tr>
    <td align=center height=30 class=tables_title><?=$lng[99][2]?> <input type=text name=username></td>
  </tr>
  <tr>
    <td align=center height=30 class=tables_title><?=$lng[99][3]?><img src="img/b.gif" width=5 height=1><input type=password name=password></td>
  </tr>
  <tr>
    <td align=center height=30 class=tables_title><img src="img/b.gif" width=16 height=1><?=$lng[99][4]?><img src="img/b.gif" width=1 height=1>
      <select name="_lang">
        <option value="de" <?if ($_SESSION['lang']=="de") echo "selected";?>>DE
        <option value="fr" <?if ($_SESSION['lang']=="fr") echo "selected";?>>FR
        <option value="it" <?if ($_SESSION['lang']=="it") echo "selected";?>>IT
        <option value="en" <?if ($_SESSION['lang']=="en") echo "selected";?>>EN
      </select><img src="img/b.gif" width=84 height=1>
    </td>
  </tr>
  <tr>
    <td align=center height=30 class=tables_title><img src="img/b.gif" width=136 height=1><input type=submit value=<?=$lng[99][5]?>></td>
  </tr>
</table>
</form>
</center>
</body>
</html>

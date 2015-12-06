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
// Page: "print test" - checking if dompdf is installed and running

session_start();
include ("admin/inc/conn.php");//include settings file
include ("ini/params.php");//include configuration file

//default language
$_SESSION['chlang']=$_lng;

if (!$_SESSION['chlang']) $_SESSION['chlang']="en";
include ("ini/lng/".$_SESSION['chlang'].".php");//include language file
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="description" content="<?=$lng[1][2]?>"/>
	<meta name="keywords" content="<?=$lng[1][3]?>"/>
	<title><?=$lng[1][1]?></title>
	<link rel="stylesheet" href="s.css" type="text/css"/>
</head>
<body>
<a href="index.php"><img src="img/logo.jpg" border="0"></a>
<table border="0" width="100%">
  <tr valign="top">
    <td>
	<table border="0" cellpadding="2" cellspacing="2" class="content" width="50%">
	  <tr class="gray" align="left">
	    <td colspan="2" align="left"><?include("ini/txts/".$_SESSION['chlang']."/_about.php");?> </td>
	  </tr>   
	</table>
    </td> 	 
  </tr>
</table>


</body>
</html>


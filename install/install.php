<?php
// REQHEAP - a simple requirement management program.
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
// Page: "Install - step 1" - HTML form to collect all necessary information.
?>
<?php
ob_start();
session_start();
if ($_GET["lang"]) $_SESSION['lang']=$_GET["lang"];
if (!$_SESSION['lang']) $_SESSION['lang']="en"; //default language

if (file_exists("../ini/lng/".$_SESSION['lang'].".php")) include ("../ini/lng/".$_SESSION['lang'].".php");//include language file
else {
	echo "<h6>Language ".strtoupper($_SESSION['lang'])." is not supported.</h6>";
	include ("../ini/lng/en.php");
}

?>
<html>
<head>
<style>
.error
 {
  color:red;
 }
</style>
</head>
<body>
<form action="install2.php" method="post" name="install">
	<h1><?=$lng[98][1]?></h1>
	<h5><a href="install.php?lang=de">Deutsch</a> &nbsp; &nbsp; <a href="install.php?lang=en">English</a> &nbsp; &nbsp; <a href="install.php?lang=fr">Francais</a> &nbsp; &nbsp; <a href="install.php?lang=it">Italiano</a>
	<br /><br />
	<?=$lng[98][2]?>: <input name="db_host" type="text" value="localhost:3306" /> <?=$lng[98][3]?><br />
	<?=$lng[98][4]?>: <input name="db_user" type="text" value="root" /><br />
	<?=$lng[98][5]?>: <input name="db_pass" type="text" value="" /><br /><br />
	<input name="db_existing" type="checkbox" value="1" /><?=$lng[98][38]?><br />
	<?=$lng[98][6]?>: <input name="db_name" type="text" value="reqheap" /><br />
	<?=$lng[98][7]?>: <input name="db_app_user" type="text" value="rh_user" /><br />
	<?=$lng[98][8]?>: <input name="db_app_pass" type="text" value="" /><br /><br />
	<?=$lng[98][24]?>: <input name="site_url" type="text" size="40" value="http://www.yoursite.com/reqheap" /><br />
	<?=$lng[98][25]?>: <input name="site_folder" type="text" value="reqheap" /><br />
	<?=$lng[98][26]?>: <input name="admin_email" type="text" value="yourname@mail.com" /><br />
	</h5>
	<b><?=$lng[98][30]?></b>
	<br /><?=$lng[98][31]?>
	<br /><?=$lng[98][32]?>
	<br /><?=$lng[98][33]?>
	<br /><?=$lng[98][34]?>
	<br /><?=$lng[98][35]?>
	<br /><br />
	<input type="submit" name="smbB" value="<?=$lng[98][9]?>" />
</form>
</body>
</html>
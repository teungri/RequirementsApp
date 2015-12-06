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
// Page: "checking if user is logged"
?>
<?session_start();
$query="select * from admin_access where aa_username='".escapeChars($_SESSION['ses_username'])."' and aa_password='".pw($_SESSION['ses_password'])."'";
$rs = mysql_query($query) or die(mysql_error());
if($row=mysql_fetch_array($rs)) ;
else
 {
  ?>
  <script>parent.location.href="index.php";</script>
  <?
  die("Not logged!");
 } 
?>


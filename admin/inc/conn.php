<?php
ob_start();
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
// Page: "DB connections and settings"
?>
<?
header("Expires: Mon, 26 Dec 1997 05:00:00 GMT"); 
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
//header("Cache-Control: no-store, no-cache, must-revalidate");
//header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache"); 
header ("Cache-Control: public");

foreach($_POST as $key=>$value) {
   eval("$$key = \"$value\";"); 
} 
foreach($_GET as $key=>$value) {
   eval("$$key = \"$value\";"); 
} 

$pwkey="rqhp71"; //password key for md5 encryption / don't change it once reqheap runs live
$db="reqheap";
$link=mysql_connect("localhost","root","");
mysql_select_db($db,$link);
mysql_query("set names 'utf8'", $link);
?>
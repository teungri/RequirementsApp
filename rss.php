<?
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
// Page: "RSS" - creates a RSS feed containing requirements changes for all users attached to the projects

include("rss.class.php");

session_start();
include ("admin/inc/conn.php");//include settings file
include ("admin/inc/func.php");//include functions file
include ("ini/params.php");//include configuration file

$query="select * from users";
$rs = mysql_query($query) or die(mysql_error());
while($row=mysql_fetch_array($rs))
 {
  //Rss
  $myfeed = new RSSFeed();
  $myfeed->SetChannel(PROJECT_URL."/".$row['u_id'].".rss",
  htmlspecialchars($row['u_name'])."'s feed",
  "Feed contains last changes in requirements of my projects",
  "en-us",
  "",
  htmlspecialchars($row['u_name']),
  "Requirements changes");

  $query3="select r.*, p.p_name from requirements r, project_users pu, projects p where r.r_change_date>=DATE_SUB(CONCAT(CURDATE(), ' 00:00:00'), INTERVAL 1 DAY) and r.r_p_id=pu.pu_p_id and pu.pu_p_id=p.p_id and pu.pu_u_id=".$row['u_id'];
  $rs3 = mysql_query($query3) or die(mysql_error());
  while($row3=mysql_fetch_array($rs3))
   {
    $myfeed->SetItem(PROJECT_URL."/index.php?inc=view_requirement&amp;r_id=".$row3['r_id'],htmlspecialchars($row3['r_name'])." (".htmlspecialchars($row3['p_name']).")",(strip_tags($row3['r_desc'])));
   } 
  $fp = fopen("RSS/".$row['u_id'].".xml", "wb");
  fwrite($fp, $myfeed->output());
  fclose($fp); 
 }
?>
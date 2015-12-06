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
// Page: "Modify DB" -this tool allows updating DB(with keeping the data) from release 0.7.4 to recent release
ob_start();
include ("../admin/inc/conn.php");//include settings file

$result = mysql_query("ALTER TABLE cases CHANGE c_result c_result text",$link);
if (!$result) echo mysql_error()."<br>";
// or echo "Invalid query ALTER TABLE cases CHANGE c_result c_result text<br/>";
$result = mysql_query("ALTER TABLE requirements CHANGE r_s_id r_s_id varchar(255)",$link);
if (!$result) echo mysql_error()."<br>";
$result = mysql_query("ALTER TABLE requirements CHANGE r_component r_component text",$link);
if (!$result) echo mysql_error()."<br>";
$result = mysql_query("ALTER TABLE requirements CHANGE r_parent_id r_parent_id int(11)",$link);
if (!$result) echo mysql_error()."<br>";
$result = mysql_query("ALTER TABLE requirements CHANGE r_pos r_pos int(11)",$link);
if (!$result) echo mysql_error()."<br>";
$result = mysql_query("ALTER TABLE requirements_history CHANGE r_s_id r_s_id varchar(255)",$link);
if (!$result) echo mysql_error()."<br>";
$result = mysql_query("ALTER TABLE requirements_history CHANGE r_component r_component text",$link);
if (!$result) echo mysql_error()."<br>";
$result = mysql_query("ALTER TABLE requirements ADD r_stakeholder varchar(255)",$link);
if (!$result) echo mysql_error()."<br>";
$result = mysql_query("ALTER TABLE requirements ADD r_keywords text",$link);
if (!$result) echo mysql_error()."<br>";
$result = mysql_query("ALTER TABLE requirements_history ADD r_stakeholder varchar(255)",$link);
if (!$result) echo mysql_error()."<br>";
$result = mysql_query("ALTER TABLE requirements_history ADD r_keywords text",$link);
if (!$result) echo mysql_error()."<br>";

$sql = "CREATE TABLE IF NOT EXISTS `keywords` (
  `k_id` int(11) NOT NULL auto_increment,
  `k_name` varchar(255) NOT NULL,
  PRIMARY KEY  (`k_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

// Execute query
mysql_query($sql,$link);


$sql = "CREATE TABLE IF NOT EXISTS `tree_history` (
  `th_id` int(11) NOT NULL auto_increment,
  `th_r_id` int(11) NOT NULL default '0',
  `th_u_id` int(11) NOT NULL default '0',
  `th_p_id` int(11) NOT NULL default '0',
  `th_parent_old` int(11) NOT NULL default '0',
  `th_parent_old_pos` int(11) NOT NULL default '0',
  `th_parent_new` int(11) NOT NULL default '0',
  `th_parent_new_pos` int(11) NOT NULL default '0',
  `th_rh_id` int(11) NOT NULL default '0',
  `th_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `th_current` int(11) NOT NULL default '0',
  PRIMARY KEY  (`th_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";

// Execute query
mysql_query($sql,$link);


$sql = "CREATE TABLE IF NOT EXISTS `export_fields` (
  `ef_id` int(11) NOT NULL auto_increment,
  `ef_name` varchar(255) NOT NULL default '',
  `ef_uid` int(11) NOT NULL default '0',
  `ef_description` tinyint(4) NOT NULL default '0',
  `ef_project` tinyint(4) NOT NULL default '0',
  `ef_subproject` tinyint(4) NOT NULL default '0',
  `ef_release` tinyint(4) NOT NULL default '0',
  `ef_test_case` tinyint(4) NOT NULL default '0',
  `ef_stakeholder` tinyint(4) NOT NULL default '0',
  `ef_glossary` tinyint(4) NOT NULL default '0',
  `ef_state` tinyint(4) NOT NULL default '0',
  `ef_type` tinyint(4) NOT NULL default '0',
  `ef_priority` tinyint(4) NOT NULL default '0',
  `ef_assign_to` tinyint(4) NOT NULL default '0',
  `ef_rid` tinyint(4) NOT NULL default '0',
  `ef_version` tinyint(4) NOT NULL default '0',
  `ef_component` tinyint(4) NOT NULL default '0',
  `ef_source` tinyint(4) NOT NULL default '0',
  `ef_risk` tinyint(4) NOT NULL default '0',
  `ef_complexity` tinyint(4) NOT NULL default '0',
  `ef_weight` tinyint(4) NOT NULL default '0',
  `ef_open_points` tinyint(4) NOT NULL default '0',
  `ef_keywords` tinyint(4) NOT NULL default '0',
  `ef_satisfaction` tinyint(4) NOT NULL default '0',
  `ef_dissatisfaction` tinyint(4) NOT NULL default '0',
  `ef_depends` tinyint(4) NOT NULL default '0',
  `ef_conflicts` tinyint(4) NOT NULL default '0',
  `ef_author` tinyint(4) NOT NULL default '0',
  `ef_url` tinyint(4) NOT NULL default '0',
  `ef_parent` tinyint(4) NOT NULL default '0',
  `ef_position` tinyint(4) NOT NULL default '0',
  `ef_userfields` tinyint(4) NOT NULL default '0',
  `ef_creation_date` tinyint(4) NOT NULL default '0',
  `ef_last_change` tinyint(4) NOT NULL default '0',
  `ef_accepted_date` tinyint(4) NOT NULL default '0',
  `ef_accepted_user` tinyint(4) NOT NULL default '0',
  `ef_comments` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`ef_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

// Execute query
mysql_query($sql,$link);

$query="select count(uf_id) from user_fields";
$rs = mysql_query($query) or die(mysql_error());
if($row=mysql_fetch_array($rs)) 
 {
  if ($row[0]!=6)
   {
    $query="delete from user_fields";
    $rs = mysql_query($query) or die(mysql_error());
    $sql = "INSERT INTO `user_fields` (`uf_id`, `uf_name_en`, `uf_name_de`,`uf_name_fr`, `uf_name_it`, `uf_type`, `uf_text_en`, `uf_text_fr`,`uf_text_de`, `uf_text_it`, `uf_values`) VALUES(1, '', '', '', '', 0, '', '', '', '', ''),(2, '', '', '', '', 0, '', '', '', '', ''),(3, '', '', '', '', 0, '', '', '', '', ''),(4, '', '', '', '', 0, '', '', '', '', ''),(5, '', '', '', '', 0, '', '', '', '', ''),(6, '', '', '', '', 0, '', '', '', '', '')";   
    mysql_query($sql,$link);
   }
 }

echo "Done!";
?>



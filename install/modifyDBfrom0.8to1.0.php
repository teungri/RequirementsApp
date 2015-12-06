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
// Page: "Modify DB" -this tool allows updating DB(with keeping the data) from release 0.8 to 1.0
ob_start();
include ("../admin/inc/conn.php");//include settings file

$result = mysql_query("ALTER TABLE cases ADD c_global TINYINT(4) NOT NULL DEFAULT '0';",$link);
if (!$result) echo mysql_error()."<br>";
$result = mysql_query("ALTER TABLE glossary ADD g_global TINYINT(4) NOT NULL DEFAULT '0';",$link);
if (!$result) echo mysql_error()."<br>";
$result = mysql_query("ALTER TABLE keywords ADD k_global TINYINT(4) NOT NULL DEFAULT '0';",$link);
if (!$result) echo mysql_error()."<br>";
$result = mysql_query("ALTER TABLE projects ADD p_req_del TINYINT(4) NOT NULL DEFAULT '1';",$link);
if (!$result) echo mysql_error()."<br>";
$result = mysql_query("ALTER TABLE releases ADD r_global TINYINT(4) NOT NULL DEFAULT '0';",$link);
if (!$result) echo mysql_error()."<br>";
$result = mysql_query("ALTER TABLE stakeholders ADD s_global TINYINT(4) NOT NULL DEFAULT '0';",$link);
if (!$result) echo mysql_error()."<br>";
$result = mysql_query("ALTER TABLE requirements CHANGE r_p_id r_p_id int(11) NOT NULL DEFAULT '-1'",$link);
if (!$result) echo mysql_error()."<br>";
$result = mysql_query("ALTER TABLE requirements ADD r_release varchar(255)",$link);
if (!$result) echo mysql_error()."<br>";
$result = mysql_query("ALTER TABLE requirements ADD r_glossary varchar(255)",$link);
if (!$result) echo mysql_error()."<br>";
$result = mysql_query("ALTER TABLE requirements ADD r_keyword varchar(255)",$link);
if (!$result) echo mysql_error()."<br>";
$result = mysql_query("ALTER TABLE requirements CHANGE r_parent_id r_parent_id int(11) default '0'",$link);
if (!$result) echo mysql_error()."<br>";
$result = mysql_query("ALTER TABLE requirements CHANGE r_pos r_pos int(11) default '1'",$link);
if (!$result) echo mysql_error()."<br>";
$result = mysql_query("ALTER TABLE requirements CHANGE r_userfield1 r_userfield1 varchar(255) NOT NULL",$link);
if (!$result) echo mysql_error()."<br>";
$result = mysql_query("ALTER TABLE requirements CHANGE r_userfield2 r_userfield2 varchar(255) NOT NULL",$link);
if (!$result) echo mysql_error()."<br>";
$result = mysql_query("ALTER TABLE requirements CHANGE r_userfield3 r_userfield3 varchar(255) NOT NULL",$link);
if (!$result) echo mysql_error()."<br>";
$result = mysql_query("ALTER TABLE requirements CHANGE r_userfield4 r_userfield4 varchar(255) NOT NULL",$link);
if (!$result) echo mysql_error()."<br>";
$result = mysql_query("ALTER TABLE requirements CHANGE r_userfield5 r_userfield5 varchar(255) NOT NULL",$link);
if (!$result) echo mysql_error()."<br>";
$result = mysql_query("ALTER TABLE requirements CHANGE r_userfield6 r_userfield6 varchar(255) NOT NULL",$link);
if (!$result) echo mysql_error()."<br>";
$result = mysql_query("ALTER TABLE requirements_history CHANGE r_p_id r_p_id int(11) NOT NULL DEFAULT '-1'",$link);
if (!$result) echo mysql_error()."<br>";
$result = mysql_query("ALTER TABLE requirements_history ADD r_release varchar(255)",$link);
if (!$result) echo mysql_error()."<br>";
$result = mysql_query("ALTER TABLE requirements_history ADD r_glossary varchar(255)",$link);
if (!$result) echo mysql_error()."<br>";
$result = mysql_query("ALTER TABLE requirements_history ADD r_keyword varchar(255)",$link);
if (!$result) echo mysql_error()."<br>";
$result = mysql_query("ALTER TABLE requirements_history CHANGE r_userfield1 r_userfield1 varchar(255) NOT NULL",$link);
if (!$result) echo mysql_error()."<br>";
$result = mysql_query("ALTER TABLE requirements_history CHANGE r_userfield2 r_userfield2 varchar(255) NOT NULL",$link);
if (!$result) echo mysql_error()."<br>";
$result = mysql_query("ALTER TABLE requirements_history CHANGE r_userfield3 r_userfield3 varchar(255) NOT NULL",$link);
if (!$result) echo mysql_error()."<br>";
$result = mysql_query("ALTER TABLE requirements_history CHANGE r_userfield4 r_userfield4 varchar(255) NOT NULL",$link);
if (!$result) echo mysql_error()."<br>";
$result = mysql_query("ALTER TABLE requirements_history CHANGE r_userfield5 r_userfield5 varchar(255) NOT NULL",$link);
if (!$result) echo mysql_error()."<br>";
$result = mysql_query("ALTER TABLE requirements_history CHANGE r_userfield6 r_userfield6 varchar(255) NOT NULL",$link);
if (!$result) echo mysql_error()."<br>";


$sql = "CREATE TABLE IF NOT EXISTS `components` (
  `c_id` int(11) NOT NULL auto_increment,
  `c_name` varchar(255) NOT NULL,
  `c_global` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`c_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";

// Execute query
mysql_query($sql,$link);


$sql = "CREATE TABLE IF NOT EXISTS `project_components` (
  `pco_id` int(11) NOT NULL auto_increment,
  `pco_p_id` int(11) NOT NULL default '0',
  `pco_c_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`pco_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";

// Execute query
mysql_query($sql,$link);

$sql = "CREATE TABLE IF NOT EXISTS `project_keywords` (
  `pk_id` int(11) NOT NULL auto_increment,
  `pk_p_id` int(11) NOT NULL default '0',
  `pk_k_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`pk_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";

// Execute query
mysql_query($sql,$link);

$sql = "CREATE TABLE IF NOT EXISTS `reviews` (
  `r_id` int(11) NOT NULL auto_increment,
  `r_p_id` int(11) NOT NULL,
  `r_name` varchar(255) NOT NULL,
  `r_desc` text NOT NULL,
  `r_date` date NOT NULL,
  `r_status` int(11) NOT NULL,
  PRIMARY KEY  (`r_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";

// Execute query
mysql_query($sql,$link);

$sql = "CREATE TABLE IF NOT EXISTS `review_comments` (
  `rc_id` int(11) NOT NULL auto_increment,
  `rc_rev_id` int(11) NOT NULL,
  `rc_req_id` int(11) NOT NULL,
  `rc_text` text NOT NULL,
  `rc_comment` int(11) NOT NULL,
  `rc_date` datetime NOT NULL,
  `rc_u_id` int(11) NOT NULL,
  PRIMARY KEY  (`rc_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";

// Execute query
mysql_query($sql,$link);

$sql = "CREATE TABLE IF NOT EXISTS `review_requirements` (
  `rr_id` int(11) NOT NULL auto_increment,
  `rr_rev_id` int(11) NOT NULL,
  `rr_req_id` int(11) NOT NULL,
  PRIMARY KEY  (`rr_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";

// Execute query
mysql_query($sql,$link);

$sql = "CREATE TABLE IF NOT EXISTS `review_users` (
  `ru_id` int(11) NOT NULL auto_increment,
  `ru_r_id` int(11) NOT NULL,
  `ru_u_id` int(11) NOT NULL,
  PRIMARY KEY  (`ru_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";

// Execute query
mysql_query($sql,$link);

echo "Done!";
?>



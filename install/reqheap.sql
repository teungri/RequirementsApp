-- phpMyAdmin SQL Dump
-- version 2.11.9.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 28, 2008 at 07:11 PM
-- Server version: 5.0.51
-- PHP Version: 5.2.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `reqheap`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_access`
--

CREATE TABLE IF NOT EXISTS `admin_access` (
  `aa_id` int(11) NOT NULL auto_increment,
  `aa_username` varchar(255) NOT NULL,
  `aa_password` varchar(255) NOT NULL,
  PRIMARY KEY  (`aa_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `admin_access`
--

INSERT INTO `admin_access` VALUES(1, 'admin', '61c9fc7e8c467e24a094f825d7be087212992c75');
-- --------------------------------------------------------

--
-- Table structure for table `cases`
--

CREATE TABLE IF NOT EXISTS `cases` (
  `c_id` int(11) NOT NULL auto_increment,
  `c_name` varchar(255) NOT NULL,
  `c_desc` text NOT NULL,
  `c_result` text NOT NULL,
  `c_status` tinyint(4) NOT NULL default '0',
  `c_global` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`c_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `c_id` int(11) NOT NULL auto_increment,
  `c_r_id` int(11) NOT NULL default '0',
  `c_u_id` int(11) NOT NULL default '0',
  `c_text` text NOT NULL,
  `c_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `c_question` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`c_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

---------------------------------------------------------

--
-- Table structure for table `components`
--

CREATE TABLE IF NOT EXISTS `components` (
  `c_id` int(11) NOT NULL auto_increment,
  `c_name` varchar(255) NOT NULL,
  `c_global` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`c_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--------------------------------------------------------

--
-- Table structure for table `export_fields`
--

CREATE TABLE IF NOT EXISTS `export_fields` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
-- --------------------------------------------------------

--
-- Table structure for table `glossary`
--

CREATE TABLE IF NOT EXISTS `glossary` (
  `g_id` int(11) NOT NULL auto_increment,
  `g_name` varchar(255) NOT NULL,
  `g_term` varchar(255) NOT NULL,
  `g_abbreviation` varchar(255) NOT NULL,
  `g_desc` text NOT NULL,
  `g_global` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`g_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `keywords`
--

CREATE TABLE IF NOT EXISTS `keywords` (
  `k_id` int(11) NOT NULL auto_increment,
  `k_name` varchar(255) NOT NULL,
  `k_global` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`k_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE IF NOT EXISTS `projects` (
  `p_id` int(11) NOT NULL auto_increment,
  `p_name` varchar(255) NOT NULL,
  `p_desc` text NOT NULL,
  `p_phase` tinyint(1) NOT NULL default '0',
  `p_status` tinyint(1) NOT NULL default '0',
  `p_leader` int(11) NOT NULL default '0',
  `p_date` date NOT NULL default '0000-00-00',
  `p_template` tinyint(1) NOT NULL default '0',
  `p_req_del` tinyint(4) NOT NULL default '1',
  PRIMARY KEY  (`p_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `project_cases`
--

CREATE TABLE IF NOT EXISTS `project_cases` (
  `pc_id` int(11) NOT NULL auto_increment,
  `pc_p_id` int(11) NOT NULL default '0',
  `pc_c_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`pc_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `project_components`
--

CREATE TABLE IF NOT EXISTS `project_components` (
  `pco_id` int(11) NOT NULL auto_increment,
  `pco_p_id` int(11) NOT NULL default '0',
  `pco_c_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`pco_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
-- --------------------------------------------------------

--
-- Table structure for table `project_glossary`
--

CREATE TABLE IF NOT EXISTS `project_glossary` (
  `pg_id` int(11) NOT NULL auto_increment,
  `pg_p_id` int(11) NOT NULL default '0',
  `pg_g_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`pg_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `project_keywords`
--

CREATE TABLE IF NOT EXISTS `project_keywords` (
  `pk_id` int(11) NOT NULL auto_increment,
  `pk_p_id` int(11) NOT NULL default '0',
  `pk_k_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`pk_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `project_releases`
--

CREATE TABLE IF NOT EXISTS `project_releases` (
  `pr_id` int(11) NOT NULL auto_increment,
  `pr_p_id` int(11) NOT NULL default '0',
  `pr_r_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`pr_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Table structure for table `project_stakeholders`
--

CREATE TABLE IF NOT EXISTS `project_stakeholders` (
  `ps_id` int(11) NOT NULL auto_increment,
  `ps_p_id` int(11) NOT NULL default '0',
  `ps_s_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`ps_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `project_users`
--

CREATE TABLE IF NOT EXISTS `project_users` (
  `pu_id` int(11) NOT NULL auto_increment,
  `pu_p_id` int(11) NOT NULL default '0',
  `pu_u_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`pu_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `releases`
--

CREATE TABLE IF NOT EXISTS `releases` (
  `r_id` int(11) NOT NULL auto_increment,
  `r_name` varchar(255) NOT NULL,
  `r_date` date NOT NULL default '0000-00-00',
  `r_released_date` date NOT NULL default '0000-00-00',
  `r_global` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`r_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Table structure for table `release_cases`
--

CREATE TABLE IF NOT EXISTS `release_cases` (
  `rc_id` int(11) NOT NULL auto_increment,
  `rc_r_id` int(11) NOT NULL default '0',
  `rc_c_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`rc_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `requirements`
--

CREATE TABLE IF NOT EXISTS `requirements` (
  `r_id` int(11) NOT NULL auto_increment,
  `r_p_id` int(11) NOT NULL default '-1',
  `r_release` varchar(255) NOT NULL,
  `r_c_id` varchar(255) NOT NULL,
  `r_s_id` varchar(255) NOT NULL,
  `r_stakeholder` varchar(255) NOT NULL,
  `r_glossary` varchar(255) NOT NULL,
  `r_keyword` varchar(255) NOT NULL,
  `r_u_id` int(11) NOT NULL default '0',
  `r_assigned_u_id` int(11) NOT NULL default '0',
  `r_name` varchar(255) NOT NULL,
  `r_desc` text NOT NULL,
  `r_state` tinyint(4) NOT NULL default '0',
  `r_type_r` tinyint(4) NOT NULL default '0',
  `r_priority` smallint(6) NOT NULL default '0',
  `r_valid` tinyint(4) NOT NULL default '0',
  `r_link` varchar(255) NOT NULL,
  `r_satisfaction` tinyint(4) NOT NULL default '0',
  `r_dissatisfaction` tinyint(4) NOT NULL default '0',
  `r_conflicts` text NOT NULL,
  `r_depends` text NOT NULL,
  `r_component` varchar(255) NOT NULL,
  `r_source` varchar(255) NOT NULL,
  `r_risk` tinyint(4) NOT NULL default '0',
  `r_complexity` tinyint(4) NOT NULL default '0',
  `r_weight` mediumint(9) NOT NULL default '0',
  `r_points` text NOT NULL,
  `r_creation_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `r_change_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `r_accept_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `r_accept_user` int(11) NOT NULL default '0',
  `r_version` int(11) NOT NULL default '1',
  `r_parent_id` int(11) default '0',
  `r_pos` int(11) default '1',
  `r_stub` tinyint(1) NOT NULL default '0',
  `r_keywords` text NOT NULL,
  `r_userfield1` varchar(255) NOT NULL,
  `r_userfield2` varchar(255) NOT NULL,
  `r_userfield3` varchar(255) NOT NULL,
  `r_userfield4` varchar(255) NOT NULL,
  `r_userfield5` varchar(255) NOT NULL,
  `r_userfield6` varchar(255) NOT NULL,
  PRIMARY KEY  (`r_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Table structure for table `requirements_history`
--

CREATE TABLE IF NOT EXISTS `requirements_history` (
  `r_id` int(11) NOT NULL auto_increment,
  `r_parent_id` int(11) NOT NULL default '0',
  `r_p_id` int(11) NOT NULL default '-1',
  `r_release` varchar(255) NOT NULL,
  `r_c_id` varchar(255) NOT NULL,
  `r_s_id` varchar(255) NOT NULL,
  `r_stakeholder` varchar(255) NOT NULL,
  `r_glossary` varchar(255) NOT NULL,
  `r_keyword` varchar(255) NOT NULL,
  `r_u_id` int(11) NOT NULL default '0',
  `r_assigned_u_id` int(11) NOT NULL default '0',
  `r_name` varchar(255) NOT NULL,
  `r_desc` text NOT NULL,
  `r_state` tinyint(4) NOT NULL default '0',
  `r_type_r` tinyint(4) NOT NULL default '0',
  `r_priority` smallint(6) NOT NULL default '0',
  `r_valid` tinyint(4) NOT NULL default '0',
  `r_link` varchar(255) NOT NULL,
  `r_satisfaction` tinyint(4) NOT NULL default '0',
  `r_dissatisfaction` tinyint(4) NOT NULL default '0',
  `r_conflicts` text NOT NULL,
  `r_depends` text NOT NULL,
  `r_component` varchar(255) NOT NULL,
  `r_source` varchar(255) NOT NULL,
  `r_risk` tinyint(4) NOT NULL default '0',
  `r_complexity` tinyint(4) NOT NULL default '0',
  `r_weight` mediumint(9) NOT NULL default '0',
  `r_points` text NOT NULL,
  `r_creation_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `r_change_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `r_accept_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `r_accept_user` int(11) NOT NULL default '0',
  `r_released_date` date NOT NULL default '0000-00-00',
  `r_version` int(11) NOT NULL default '0',
  `r_save_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `r_save_user` int(11) NOT NULL default '0',
  `r_parent_id2` int(11) NOT NULL default '0',
  `r_pos` int(11) NOT NULL default '1',
  `r_stub` tinyint(1) NOT NULL default '0',
  `r_keywords` text NOT NULL,
  `r_userfield1` varchar(255) NOT NULL,
  `r_userfield2` varchar(255) NOT NULL,
  `r_userfield3` varchar(255) NOT NULL,
  `r_userfield4` varchar(255) NOT NULL,
  `r_userfield5` varchar(255) NOT NULL,
  `r_userfield6` varchar(255) NOT NULL,
  PRIMARY KEY  (`r_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE IF NOT EXISTS `reviews` (
  `r_id` int(11) NOT NULL auto_increment,
  `r_p_id` int(11) NOT NULL,
  `r_name` varchar(255) NOT NULL,
  `r_desc` text NOT NULL,
  `r_date` date NOT NULL,
  `r_status` int(11) NOT NULL,
  PRIMARY KEY  (`r_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `review_comments`
--

CREATE TABLE IF NOT EXISTS `review_comments` (
  `rc_id` int(11) NOT NULL auto_increment,
  `rc_rev_id` int(11) NOT NULL,
  `rc_req_id` int(11) NOT NULL,
  `rc_text` text NOT NULL,
  `rc_comment` int(11) NOT NULL,
  `rc_date` datetime NOT NULL,
  `rc_u_id` int(11) NOT NULL,
  PRIMARY KEY  (`rc_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `review_requirements`
--

CREATE TABLE IF NOT EXISTS `review_requirements` (
  `rr_id` int(11) NOT NULL auto_increment,
  `rr_rev_id` int(11) NOT NULL,
  `rr_req_id` int(11) NOT NULL,
  PRIMARY KEY  (`rr_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `review_users`
--

CREATE TABLE IF NOT EXISTS `review_users` (
  `ru_id` int(11) NOT NULL auto_increment,
  `ru_r_id` int(11) NOT NULL,
  `ru_u_id` int(11) NOT NULL,
  PRIMARY KEY  (`ru_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------


--
-- Table structure for table `stakeholders`
--

CREATE TABLE IF NOT EXISTS `stakeholders` (
  `s_id` int(11) NOT NULL auto_increment,
  `s_name` varchar(255) NOT NULL,
  `s_function` varchar(255) NOT NULL,
  `s_email` varchar(255) NOT NULL,
  `s_interests` text NOT NULL,
  `s_global` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`s_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `subprojects`
--

CREATE TABLE IF NOT EXISTS `subprojects` (
  `s_id` int(11) NOT NULL auto_increment,
  `s_name` varchar(255) NOT NULL,
  `s_desc` text NOT NULL,
  `s_p_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`s_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tree_history`
--

CREATE TABLE IF NOT EXISTS `tree_history` (
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

--
-- Dumping data for table `tree_history`
--


-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `u_id` int(11) NOT NULL auto_increment,
  `u_username` varchar(255) NOT NULL,
  `u_password` varchar(255) NOT NULL,
  `u_email` varchar(255) NOT NULL,
  `u_name` varchar(255) NOT NULL,
  `u_rights` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`u_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` VALUES(1, 'admin', '61c9fc7e8c467e24a094f825d7be087212992c75', 'admin@reqheap.com', 'Admin', 5);
INSERT INTO `users` VALUES(2, 'guest', '7d0dcf3f185967102c90353f141884426596e63c', 'guest@reqheap.com', 'guest', 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_fields`
--

CREATE TABLE IF NOT EXISTS `user_fields` (
  `uf_id` int(11) NOT NULL auto_increment,
  `uf_name_en` varchar(255) NOT NULL,
  `uf_name_de` varchar(255) NOT NULL,
  `uf_name_fr` varchar(255) NOT NULL,
  `uf_name_it` varchar(255) NOT NULL,
  `uf_type` tinyint(1) NOT NULL default '0',
  `uf_text_en` varchar(255) NOT NULL,
  `uf_text_fr` varchar(255) NOT NULL,
  `uf_text_de` varchar(255) NOT NULL,
  `uf_text_it` varchar(255) NOT NULL,
  `uf_values` text NOT NULL,
  PRIMARY KEY  (`uf_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `user_fields` (`uf_id`, `uf_name_en`, `uf_name_de`,
`uf_name_fr`, `uf_name_it`, `uf_type`, `uf_text_en`, `uf_text_fr`,
`uf_text_de`, `uf_text_it`, `uf_values`) VALUES
(1, '', '', '', '', 0, '', '', '', '', ''),
(2, '', '', '', '', 0, '', '', '', '', ''),
(3, '', '', '', '', 0, '', '', '', '', ''),
(4, '', '', '', '', 0, '', '', '', '', ''),
(5, '', '', '', '', 0, '', '', '', '', ''),
(6, '', '', '', '', 0, '', '', '', '', '');

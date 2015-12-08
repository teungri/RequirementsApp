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
// Page: "Homepage" - containing the site content. Includes the form pages depending on the variable 'inc'

session_start();

include ("admin/inc/conn.php");//include settings file
include ("admin/inc/func.php");//include functions file
include ("ini/params.php");//include configuration file

//setting referer if not logged
if ( ! isset($_SESSION['uid']) ) $_SESSION['uid'] = "";
if ($_SESSION['uid']=="" && $_SERVER['QUERY_STRING']!="" && !strstr($_SERVER['QUERY_STRING'],'login'))
{
 $_SESSION['http_ref']=$_SERVER['QUERY_STRING'];
} 


//default language
if ( !isset($_chlang) ) $_chlang = "";
if ($_chlang!="") $_SESSION['chlang']=$_chlang;
if (!$_SESSION['chlang']) $_SESSION['chlang']="en";

//if (!AUTO_TRANSLATIONS) // !oburnato e yes=0, no-1
 {
  //purvo se zarejda en file-a i sled tova ako e drug ezika se overwritevat value-tata, taka 4e ako nqkoe ne e vuvedeno da go ima ot en file-a!
  //na purvona4alnite otpred se dobavq [en]
  include ("ini/lng/en.php");//include language file
  while (list ($key, $val) = each ($lng)) while (list ($key2, $val2) = each ($lng[$key])) $lng[$key][$key2]="[EN]".$val2;
 }
include ("ini/lng/".$_SESSION['chlang'].".php");//include language file

?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="description" content="<?=$lng[1][2]?>"/>
	<meta name="keywords" content="<?=$lng[1][3]?>"/>
	<title><?=$lng[1][1]?></title>
	<link rel='STYLESHEET' type='text/css' href='dhtmlxTree/samples/common/style.css'>
	<link rel="stylesheet" href="s.css" type="text/css"/>
</head>
<body bgcolor="#ffffff">
<a href="index.php"><img src="img/logo.jpg" border="0"></a>
<center>
  <table border="0" cellpadding="10" cellspacing="0" width="100%" class="main">
    <tr>
       <td>
	 <!-- TOP COLUMN -->
	 <?php
	   include ("top.php");
	 ?>
       </td>
     </tr>
     <tr>
       <td valign="top" align="center">
	 <?php
	   switch($inc)
	    {
	     case "copy":include("copyright.php");break;
	     case "about":include("about.php");break;
	     case "login":include("login.php");break;
	     case "logout":include("logout.php");break;
	     case "register":include("register.php");break;
	     case "lost_password":include("lost_password.php");break;
	     case "my_profile":include("my_profile.php");break;
	     case "manage_projects":include("manage_projects.php");break;
	     case "manage_subprojects":include("manage_subprojects.php");break;
	     case "manage_reviews":include("manage_reviews.php");break;
	     case "edit_project":include("edit_project.php");break;
	     case "edit_subproject":include("edit_subproject.php");break;
	     case "edit_review":include("edit_review.php");break;
	     case "view_review":include("view_review.php");break;
	     case "view_project":include("view_project.php");break;
	     case "view_subproject":include("view_subproject.php");break;
	     case "view_release":include("view_release.php");break;
	     case "view_case":include("view_case.php");break;
	     case "view_stakeholder":include("view_stakeholder.php");break;
	     case "view_component":include("view_component.php");break;
	     case "view_glossary":include("view_glossary.php");break;
	     case "manage_users":include("manage_users.php");break;
	     case "manage_stakeholders":include("manage_stakeholders.php");break;
	     case "manage_components":include("manage_components.php");break;
	     case "manage_glossary":include("manage_glossary.php");break;
	     case "manage_keywords":include("manage_keywords.php");break;
	     case "edit_user":include("edit_user.php");break;
	     case "edit_stakeholder":include("edit_stakeholder.php");break;
	     case "edit_component":include("edit_component.php");break;
	     case "edit_glossary":include("edit_glossary.php");break;
	     case "edit_keyword":include("edit_keyword.php");break;
	     case "manage_releases":include("manage_releases.php");break;
	     case "manage_cases":include("manage_cases.php");break;
	     case "edit_release":include("edit_release.php");break;
	     case "edit_case":include("edit_case.php");break;
	     case "edit_requirement":include("edit_requirement.php");break;
	     case "import":include("import.php");break;
	     case "pdf_fields":include("pdf_fields.php");break;
	     case "pdf_project_fields":include("pdf_project_fields.php");break;
	     case "pdf_review_fields":include("pdf_review_fields.php");break;
	     case "xls_project_fields":include("xls_project_fields.php");break;
	     case "view_requirement":include("view_requirement.php");break;
	     case "view_requirement_long":include("view_requirement_long.php");break;
	     case "view_all":include("view_all.php");break;
	     case "add_comment":include("add_comment.php");break;
	     case "statistics":include("statistics.php");break;
	     default:include("main.php");
	    }
	 ?>
       </td>
     </tr>
     <tr>
       <td>
	 <?php
	   include("footer.php");
	 ?>
       </td>
     </tr>
   </table>	
</center>
</body>
</html>
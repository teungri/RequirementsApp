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
// Page: "Install - step 2" - the file creates the database connaction, new database and db user and creates all db tables. It copies all necessary scripts and runs diagnostics.

ob_start();
session_start();
error_reporting(1);
/*include language file*/

if ($_GET["lang"]) $_SESSION['lang']=$_GET["lang"];
if (!$_SESSION['lang']) $_SESSION['lang']="en"; //default language
if (file_exists("../ini/lng/".$_SESSION['lang'].".php")) include ("../ini/lng/".$_SESSION['lang'].".php");//include language file
else {
	echo "<h6>Language ".strtoupper($_SESSION['lang'])." is not supported.</h6>";
	include ("../ini/lng/en.php");
}

/*install parameters - get by form*/
if ($_POST["db_name"]) $db_name=$_POST["db_name"]; else $db_name="reqheap";
if ($_POST["db_user"]) $db_user=$_POST["db_user"]; else $db_user="root";
if ($_POST["db_pass"]) $db_pass=$_POST["db_pass"]; else $db_pass="123";
if ($_POST["db_app_user"]) $db_app_user=$_POST["db_app_user"]; else $db_app_user="rh_user";
if ($_POST["db_app_pass"]) $db_app_pass=$_POST["db_app_pass"]; else $db_app_pass="rh123";
if ($_POST["db_existing"]=="1") $db_existing=1; else $db_existing=0;
if ($_POST["db_host"]) $db_host=$_POST["db_host"]; else $db_host="localhost:3306";
if ($_POST["site_url"]) $site_url=$_POST["site_url"]; else $site_url="http://www.yoursite.com/reqheap";
$site_url="http://".str_replace("http://","",$site_url);
if ($_POST["site_folder"]) $site_folder=$_POST["site_folder"]; else $site_folder="reqheap";
if ($_POST["admin_email"]) $admin_email=$_POST["admin_email"]; else $admin_email="yourname@mail.com";

/*------------------- end install parameters ----------------------*/
/*additional parameters*/
$db_sql_file_url="reqheap.sql";
/*------------------- end additional parameters -------------------*/

/*------------------- MYSQL ---------------------------------------*/
$db_link=mysql_connect($db_host,$db_user,$db_pass); //create connection
if (!$db_link) die ($lng[98][11]); //unsuccessful
else echo $lng[98][12];
echo "<br />";



//Get PHP version
$php_version=phpversion();
echo $lng[98][28].": ".$php_version."<br>";
if ($php_version[0]<5) echo $lng[98][29];

//Get MySQL version
$db_query=mysql_query("select version() as db_version", $db_link);
$rs=mysql_fetch_object($db_query);
$db_version=$rs->db_version;
echo $lng[98][13].": ".$db_version."<br>";


if ($db_existing==1) 
 {
  if ($db_link2=mysql_connect($db_host,$db_app_user,$db_app_pass)) echo $lng[98][40];
  else die ($lng[98][39]." : ".$db_app_user); //unsuccessful
 
  if (!mysql_select_db($db_name,$db_link2)) {echo "<br>".$lng[98][43];die();} //select DB
  include("install_modifyDBfrom0.8to1.0.php");
 }
else
 {
  $db_query = "CREATE DATABASE $db_name";
  if (mysql_query($db_query, $db_link)) echo $lng[98][14].": '$db_name'!<br>";
  else die (mysql_error()." - DATABASE CAN'T BE CREATED!");

  $db_query = "GRANT USAGE ON *.*  TO '$db_app_user'@'".substr($db_host,0,strpos($db_host,":"))."'  IDENTIFIED BY '$db_app_pass'";
  if (mysql_query($db_query, $db_link)) echo $lng[98][15]." - '$db_app_user'!<br>";
  //else die($db_query);

  $db_query = "GRANT SELECT,INSERT,UPDATE,DELETE,CREATE,DROP ON $db_name.* TO '$db_app_user'@'".substr($db_host,0,strpos($db_host,":"))."' IDENTIFIED BY '$db_app_pass'";
  if (mysql_query($db_query, $db_link)) echo $lng[98][16].": '$db_app_user' - database: '$db_name'<br>";
  //else die($db_query);

  if ($db_link2=mysql_connect($db_host,$db_app_user,$db_app_pass)) echo $lng[98][40];
  else die ($lng[98][39]." : ".$db_app_user); //unsuccessful

  mysql_select_db($db_name,$db_link2); //select DB

  //check MySQL version and load the apropriate sql script
  $db_sql_file = fopen($db_sql_file_url, "r");

  $db_query=""; 
  if ($db_sql_file) 
   {
    while (!feof($db_sql_file)) {
       $buffer = fgets($db_sql_file, 4096);
       if ($buffer!="\n" && (substr($buffer,0,2)!="--" && substr($buffer,0,2)!="/*")) $db_query.=$buffer;
      }
    fclose($db_sql_file);
  }

  $db_query=explode(";", $db_query);
  for ($i=0; $i<sizeof($db_query); $i++)
   {
      if ($db_version[0]<5)
       {
	$db_query[$i]=str_replace('SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO"','',$db_query[$i]);
	$db_query[$i]=str_replace("ENGINE=MyISAM","",$db_query[$i]);
	$db_query[$i]=str_replace("DEFAULT CHARSET=utf8","",$db_query[$i]);
       }
       if ($db_query[$i] && $db_query[$i]!="\n" && trim($db_query[$i])!="") 
	 {
	  if (!mysql_query($db_query[$i])) die ("<br><br>".$db_query[$i]."<br>".mysql_error());
	 } 
   }
  echo " ".$lng[98][17]."!<br>";
  mysql_close($db_link);
 }


/*------------------- end MYSQL -----------------------------------*/


/* FILES & FOLDERS */


//copy install files
//$oldumask = umask(0);
//if (!is_dir("install") && !file_exists("install")) @mkdir("install", 0766); //create folder
//if (!is_dir("ini") && !file_exists("ini")) @mkdir("ini", 0766); //create folder FIX
//umask($oldumask);

//if (!chmod('../admin', 777)) echo " ".$lng[98][36]."<br/>".$lng[98][31]."<br />".$lng[98][32]."<br />".$lng[98][33]."<br />".$lng[98][34]."<br />".$lng[98][35]."<br />";

chmod('../admin', 777); 
chmod('../admin/css', 777); 
chmod('../admin/img', 777); 
chmod('../admin/inc', 777); 
chmod('../FCKeditor', 777); 
chmod('../img', 777); 
chmod('../install', 777); 
chmod('../ini', 777); 
chmod('../ini/bkps', 777); 
chmod('../ini/lng', 777); 
chmod('../ini/lng/bkps', 777); 
chmod('../ini/txts', 777); 
chmod('../ini/txts/bkps', 777); 


/*GENERATE conn.php*/
if (!$handle = fopen("../admin/inc/conn.php", "w")) {echo "<br/> ".$lng[98][37]."<br/>".$lng[98][31]."<br />".$lng[98][32]."<br />".$lng[98][33]."<br />".$lng[98][34]."<br />".$lng[98][35]."<br />";die();}
else
{
$buf='<?php
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

$pwkey="rqhp71"; //password key for md5 encryption / don\'t change it once reqheap runs live
$db="'.$db_name.'";
$link=mysql_connect("'.$db_host.'","'.$db_app_user.'","'.$db_app_pass.'");
mysql_select_db($db,$link);
mysql_query("set names \'utf8\'", $link);
?>';
if (!fwrite($handle, $buf))  echo " ".$lng[98][37]."!<br/>".$lng[98][31]."<br />".$lng[98][32]."<br />".$lng[98][33]."<br />".$lng[98][34]."<br />".$lng[98][35]."<br />";
fclose($handle);
echo "<br/>".$lng[98][21]." - admin/inc/conn.php";

}
/*END GENERATING conn.php*/



/*GENERATE params.php*/
$handle = fopen("../ini/params.php", "w");
$buf='<?php
//       -----    edit below this line

//VARIABLES
$PPAGE=20; //items per page (default)

//CONSTANTS
define("PROJECT_URL", "'.$site_url.'");  //should be changed to https
define("PROJECT_FOLDERNAME", "'.$site_folder.'");  //installation folder name
define("PROJECT_LOCAL_PATH", "/");
define("SITE_NAME", "ReqHeap");
define("DEFAULT_EMAIL", "'.$admin_email.'");
define("TIME_DIFF_HOURS", 0); //if server\'s time is ahead/backhead
define("PDF_SCRIPT_URL", "dompdf-0.5.1/");  //PATH to the PDF library and scripts
//define("PDF_SCRIPT_URL", "http://www.i-nature.com/reqheaptest/dompdf-0.5.1/");  //PATH to the PDF library and scripts
//define("PDF_SCRIPT_URL", "http://bel-bg.com/reqheap2/dompdf-0.5.1/");  //PATH to the PDF library and scripts
//define("XLS_SCRIPT_URL", "http://www.i-nature.com/reqheaptest/php_writeexcel-0.3.0/");  //PATH to the XLS library and scripts
define("XLS_SCRIPT_URL", "xls/");  //PATH to the XLS library and scripts
//define("XLS_SCRIPT_URL", "http://bel-bg.com/reqheap/php_writeexcel-0.3.0/");  //PATH to the XLS library and scripts
define("NUMBER_OF_NEWS_SHOWN", 3);  //the number of the news shown on login page
?>';

fwrite($handle, $buf);
fclose($handle);
echo "<br />".$lng[98][27]." - ini/params.php";

/*END GENERATING params.php*/

echo "<br /><br />".$lng[98][19]."!<br /><br />-----------------------------------------------------------<br />".$lng[98][20].":<br /><br />";
include ("diagnostics.php");
//REDIRECT:?>
<h1><?=$lng[98][22]?></h1>
<form action="../index.php"><input type="submit" value="<?=$lng[98][23]?>"></form>

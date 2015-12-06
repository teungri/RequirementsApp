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
// Page: "Diagnostics" - the file is testing the db connection, the integrity of the database,  the user rights in each folder that reqheap is using and statistics about the projects, users and requirements in the database.
ob_start();






//da se dobavi proverka za 3te tablici v koito ima danni!








?>
<html>
<head>
	<link rel="stylesheet" href="../s.css" type="text/css"/>
</head>
<body>
<?

//default language
if ($_chlang!="") $_SESSION['chlang']=$_chlang;
if (!$_SESSION['chlang']) $_SESSION['chlang']="en";
if (!file_exists("../ini/lng/".$_SESSION['chlang'].".php")) {echo "<span class='error'>Language file in 'ini/lng' is missing!</span></br></br>";die();}
include ("../ini/lng/".$_SESSION['chlang'].".php");//include language file

if (!is_dir('../admin') || (substr(sprintf('%o', fileperms('../admin')), -4)!='0755' && substr(sprintf('%o', fileperms('../admin')), -4)!='0777')) {echo "<span class='error'>".$lng[21][17]."</span></br></br>";die();}
if (!is_dir('../ini/lng') || (substr(sprintf('%o', fileperms('../ini/lng')), -4)!='0755' && substr(sprintf('%o', fileperms('../ini/lng')), -4)!='0777')) {echo "<span class='error'>".$lng[21][18]."</span></br></br>";die();}
if (!file_exists("../ini/params.php")) {echo "<span class='error'>".$lng[21][45]."</span></br></br>";die();}

include ("../admin/inc/conn.php");//include settings file

echo $lng[21][65].phpversion()."<br/><br/>"; 


echo $lng[21][1]."</br>";
//checking DB connection
if (!mysql_select_db($db,$link)) echo "<span class='error'>".$lng[21][2]."</span></br></br>";
else 
 {
	$db_sql_file_url="reqheap.sql";
	$db_sql_file = fopen($db_sql_file_url, "r");
	
	$db_query=""; 
	if ($db_sql_file) 
	 {
	  while (!feof($db_sql_file))
	   {
	    $buffer = fgets($db_sql_file, 4096);
	    if ($buffer!="\n" && (substr($buffer,0,2)!="--" && substr($buffer,0,2)!="/*")) $db_query.=$buffer;
	   }
	  fclose($db_sql_file);
	 }
	
	$db_query=explode("CREATE", $db_query);
	for ($i=0; $i<sizeof($db_query); $i++)
	 {
	  if ($db_query[$i] && $db_query[$i]!="\n" && trim($db_query[$i])!="") 
	   {
	    //echo "<br>".$db_query[$i]."<br>--";
	    $table_key=substr($db_query[$i],22,strpos($db_query[$i],"(")-24);
	    $table_val=substr($db_query[$i],strpos($db_query[$i],"(")+1,strpos($db_query[$i],"ENGINE")-strpos($db_query[$i],"(")-3);
	    //echo "<br>".$table_key."-".$table_val."<br>--";
	    if ($table_key!="") $tables_names[]=$table_key;	    
	   } 
	 }
	
  
  
  echo $lng[21][3]."<br/>";
  echo $lng[21][64] . find_SQL_Version()."<br/><br/>"; 
  
  $result = mysql_list_tables($db);
  $fl=1;$i=0;
  echo $lng[21][4]."</br>";
  
  //---------------  start checking DB tables -----------------
$result = mysql_list_tables($db);
while ($row = mysql_fetch_row($result)) $tables_names2[]=$row[0];

while (list ($key, $val) = each ($tables_names))
 {
  if (!in_array($val,$tables_names2)) {$fl=0;echo "<span class='error'>".$lng[21][73]." ".$val."</span><br/><br/>";}
 }
//---------------  end checking DB tables -----------------

  
  
  //checking DB tables
  /*while ($row = mysql_fetch_row($result))
   {
    if ($i==0 && $row[0]!="admin_access") {$fl=0;echo "<span class='error'>".$lng[21][6]."</span><br/><br/>";}
    if ($i==1 && $row[0]!="cases") {$fl=0;echo "<span class='error'>".$lng[21][58]."</span><br/><br/>";}
    if ($i==2 && $row[0]!="comments") {$fl=0;echo "<span class='error'>".$lng[21][7]."</span><br/><br/>";}
    if ($i==3 && $row[0]!="components") {$fl=0;echo "<span class='error'>".$lng[21][66]."</span><br/><br/>";}
    if ($i==4 && $row[0]!="export_fields") {$fl=0;echo "<span class='error'>".$lng[21][63]."</span><br/><br/>";}
    if ($i==5 && $row[0]!="glossary") {$fl=0;echo "<span class='error'>".$lng[21][52]."</span><br/><br/>";}
    if ($i==6 && $row[0]!="keywords") {$fl=0;echo "<span class='error'>".$lng[21][61]."</span><br/><br/>";}
    if ($i==7 && $row[0]!="project_cases") {$fl=0;echo "<span class='error'>".$lng[21][59]."</span><br/><br/>";}
    if ($i==8 && $row[0]!="project_components") {$fl=0;echo "<span class='error'>".$lng[21][67]."</span><br/><br/>";}
    if ($i==9 && $row[0]!="project_glossary") {$fl=0;echo "<span class='error'>".$lng[21][51]."</span><br/><br/>";}
    if ($i==10 && $row[0]!="project_keywords") {$fl=0;echo "<span class='error'>".$lng[21][68]."</span><br/><br/>";}
    if ($i==11 && $row[0]!="project_releases") {$fl=0;echo "<span class='error'>".$lng[21][8]."</span><br/><br/>";}
    if ($i==12 && $row[0]!="project_stakeholders") {$fl=0;echo "<span class='error'>".$lng[21][49]."</span><br/><br/>";}
    if ($i==13 && $row[0]!="project_users") {$fl=0;echo "<span class='error'>".$lng[21][9]."</span><br/><br/>";}
    if ($i==14 && $row[0]!="projects") {$fl=0;echo "<span class='error'>".$lng[21][10]."</span><br/><br/>";}
    if ($i==15 && $row[0]!="release_cases") {$fl=0;echo "<span class='error'>".$lng[21][60]."</span><br/><br/>";}
    if ($i==16 && $row[0]!="releases") {$fl=0;echo "<span class='error'>".$lng[21][11]."</span><br/><br/>";}
    if ($i==17 && $row[0]!="requirements") {$fl=0;echo "<span class='error'>".$lng[21][12]."</span><br/><br/>";}
    if ($i==18 && $row[0]!="requirements_history") {$fl=0;echo "<span class='error'>".$lng[21][13]."</span><br/><br/>";}
    if ($i==19 && $row[0]!="review_comments") {$fl=0;echo "<span class='error'>".$lng[21][70]."</span><br/><br/>";}
    if ($i==20 && $row[0]!="review_requirements") {$fl=0;echo "<span class='error'>".$lng[21][71]."</span><br/><br/>";}
    if ($i==21 && $row[0]!="review_users") {$fl=0;echo "<span class='error'>".$lng[21][72]."</span><br/><br/>";}
    if ($i==22 && $row[0]!="reviews") {$fl=0;echo "<span class='error'>".$lng[21][69]."</span><br/><br/>";}
    if ($i==23 && $row[0]!="stakeholders") {$fl=0;echo "<span class='error'>".$lng[21][50]."</span><br/><br/>";}
    if ($i==24 && $row[0]!="subprojects") {$fl=0;echo "<span class='error'>".$lng[21][57]."</span><br/><br/>";}
    if ($i==25 && $row[0]!="tree_history") {$fl=0;echo "<span class='error'>".$lng[21][62]."</span><br/><br/>";}
    if ($i==26 && $row[0]!="user_fields") {$fl=0;echo "<span class='error'>".$lng[21][56]."</span><br/><br/>";}
    if ($i==27 && $row[0]!="users") {$fl=0;echo "<span class='error'>".$lng[21][14]."</span><br/><br/>";}
    $i++;
   }*/
  if ($fl) echo $lng[21][5]."</br><br/>";

  //checking folders and permissions
  echo $lng[21][15]."</br>";
  $fl=1;
  if (!is_dir('../admin') || (substr(sprintf('%o', fileperms('../admin')), -4)!='0755' && substr(sprintf('%o', fileperms('../admin')), -4)!='0777'))
     {$fl=0;echo "<span class='error'>".$lng[21][17]."</span><br/><br/>";}
  if (!is_dir('../admin/css') || (substr(sprintf('%o', fileperms('../admin/css')), -4)!='0755' && substr(sprintf('%o', fileperms('../admin/css')), -4)!='0777'))
     {$fl=0;echo "<span class='error'>".$lng[21][20]."</span><br/><br/>";}
  if (!is_dir('../admin/img') || (substr(sprintf('%o', fileperms('../admin/img')), -4)!='0755' && substr(sprintf('%o', fileperms('../admin/img')), -4)!='0777'))
     {$fl=0;echo "<span class='error'>".$lng[21][21]."</span><br/><br/>";}
  if (!is_dir('../admin/inc') || (substr(sprintf('%o', fileperms('../admin/inc')), -4)!='0755' && substr(sprintf('%o', fileperms('../admin/inc')), -4)!='0777'))
     {$fl=0;echo "<span class='error'>".$lng[21][22]."</span><br/><br/>";}
  if (!is_dir('../FCKeditor') || (substr(sprintf('%o', fileperms('../FCKeditor')), -4)!='0755' && substr(sprintf('%o', fileperms('../FCKeditor')), -4)!='0777'))
     {$fl=0;echo "<span class='error'>".$lng[21][23]."</span><br/><br/>";}
  if (!is_dir('../img') || (substr(sprintf('%o', fileperms('../img')), -4)!='0755' && substr(sprintf('%o', fileperms('../img')), -4)!='0777'))
     {$fl=0;echo "<span class='error'>".$lng[21][24]."</span><br/><br/>";}
  if (!is_dir('../install') || (substr(sprintf('%o', fileperms('../install')), -4)!='0755' && substr(sprintf('%o', fileperms('../install')), -4)!='0777'))
     {$fl=0;echo "<span class='error'>".$lng[21][25]."</span><br/><br/>";}
  //if (!is_dir('../RSS') || (substr(sprintf('%o', fileperms('../RSS')), -4)!='0777'))
     //{$fl=0;echo "<span class='error'>".$lng[21][26]."</span><br/><br/>";}
  if (!is_dir('../ini') || (substr(sprintf('%o', fileperms('../ini')), -4)!='0777'))
     {$fl=0;echo "<span class='error'>".$lng[21][27]."</span><br/><br/>";}
  if (!is_dir('../ini/bkps') || (substr(sprintf('%o', fileperms('../ini')), -4)!='0777'))
     {$fl=0;echo "<span class='error'>".$lng[21][28]."</span><br/><br/>";}
  if (!is_dir('../ini/lng') || (substr(sprintf('%o', fileperms('../ini')), -4)!='0777'))
     {$fl=0;echo "<span class='error'>".$lng[21][29]."</span><br/><br/>";}
  if (!is_dir('../ini/lng/bkps') || (substr(sprintf('%o', fileperms('../ini')), -4)!='0777'))
     {$fl=0;echo "<span class='error'>".$lng[21][28]."</span><br/><br/>";}
  if (!is_dir('../ini/txts') || (substr(sprintf('%o', fileperms('../ini')), -4)!='0777'))
     {$fl=0;echo "<span class='error'>".$lng[21][30]."</span><br/><br/>";}
  if (!is_dir('../ini/txts/bkps') || (substr(sprintf('%o', fileperms('../ini')), -4)!='0777'))
     {$fl=0;echo "<span class='error'>".$lng[21][28]."</span><br/><br/>";}
 
  if ($fl) echo $lng[21][16]."</br><br/>";
 
  if ($fl) echo $lng[21][43]."</br>";
  if ($fl) echo $lng[21][44]."</br>";
  include("../FCKeditor/fckeditor.php");
  $oFCKeditor = new FCKeditor('ta') ;
  $oFCKeditor->BasePath = '../FCKeditor/' ;
  $oFCKeditor->Value = $ta;
  $oFCKeditor->Width = '460';
  $oFCKeditor->Height = '100';
  $oFCKeditor->Create();
  
  //checking PDF script
  echo "</br>".$lng[21][46]."<a href='../pdf_test.php' target='_blank'>".$lng[21][47]."</a>";
  echo "</br>".$lng[21][48];

  //checking XLS script
  echo "</br>".$lng[21][53]."<a href='../xls_test.php' target='_blank'>".$lng[21][54]."</a>";
  echo "</br>".$lng[21][55];
  
 
  //getting statitistics
  echo "</br><hr></br>".$lng[21][31]."</br>";
  $query="select count(*) from projects";
  $rs = mysql_query($query) or die(mysql_error());
  if($row=mysql_fetch_array($rs)) echo $lng[21][32].$row[0]."</br>";
  $query="select count(*) from projects where p_status=0";
  $rs = mysql_query($query) or die(mysql_error());
  if($row=mysql_fetch_array($rs)) echo $lng[21][33].$row[0]."</br>";
  $query="select count(*) from projects where p_status=1";
  $rs = mysql_query($query) or die(mysql_error());
  if($row=mysql_fetch_array($rs)) echo $lng[21][34].$row[0]."</br>";
  $query="select count(*) from projects where p_status=2";
  $rs = mysql_query($query) or die(mysql_error());
  if($row=mysql_fetch_array($rs)) echo $lng[21][35].$row[0]."</br></br>";

  $query="select count(*) from requirements";
  $rs = mysql_query($query) or die(mysql_error());
  if($row=mysql_fetch_array($rs)) echo $lng[21][36].$row[0]."</br>";

  //state list	        
  include("../ini/txts/".$_SESSION['chlang']."/state.php");
  while (list($k,$v) = each($state_array))
   {
    $query="select count(*) from requirements where r_state=".$k;
    $rs = mysql_query($query) or die(mysql_error());
    if($row=mysql_fetch_array($rs)) echo " - ".$v." requirements : ".$row[0]."</br>";
   }

  $query="select count(*) from users where u_rights<5";
  $rs = mysql_query($query) or die(mysql_error());
  if($row=mysql_fetch_array($rs)) echo "</br>".$lng[21][37].$row[0]."</br>";

  $query="select count(*) from users where u_rights=0";
  $rs = mysql_query($query) or die(mysql_error());
  if($row=mysql_fetch_array($rs)) echo $lng[21][38].$row[0]."</br>";

  $query="select count(*) from users where u_rights=1";
  $rs = mysql_query($query) or die(mysql_error());
  if($row=mysql_fetch_array($rs)) echo $lng[21][39].$row[0]."</br>";

  $query="select count(*) from users where u_rights=2";
  $rs = mysql_query($query) or die(mysql_error());
  if($row=mysql_fetch_array($rs)) echo $lng[21][40].$row[0]."</br>";

  $query="select count(*) from users where u_rights=3";
  $rs = mysql_query($query) or die(mysql_error());
  if($row=mysql_fetch_array($rs)) echo $lng[21][41].$row[0]."</br>";

  $query="select count(*) from users where u_rights=4";
  $rs = mysql_query($query) or die(mysql_error());
  if($row=mysql_fetch_array($rs)) echo $lng[21][42].$row[0]."</br>";

  

 } 


function find_SQL_Version() {
   $output = shell_exec('mysql -V');
   preg_match('@[0-9]+\.[0-9]+\.[0-9]+@', $output, $version);
   return $version[0];
}

?>
</body>
</html>
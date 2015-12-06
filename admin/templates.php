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
// Page: "templates" - loading already generated templates
?>
<?include("inc/conn.php");?>
<?include("inc/func.php");?>
<?include("inc/conn_admin.php");?>
<?include("inc/check_login.php");?>
<?include("../ini/params.php");?>

<?
if ($_POST['Save']!="" && $_POST['template']==1)
 {
  $query="insert into projects (p_name, p_desc) values ('IT-project 1','General IT projects based on: <a href=\"http://de.wikipedia.org/wiki/Anforderung_(Informatik)\" target=\"_blank\"><u>Anforderung_(Informatik)</u></a>')";
  mysql_query($query) or die(mysql_error());
  $p_id=mysql_insert_id();

  $query="insert into requirements (r_name, r_desc, r_p_id, r_parent_id, r_pos, r_creation_date, r_change_date, r_stub) 
  values ('Functional requirements','','".$p_id."','','1',DATE_ADD( NOW( ) , INTERVAL - ".TIME_DIFF_HOURS." HOUR),DATE_ADD( NOW( ) , INTERVAL - ".TIME_DIFF_HOURS." HOUR),'2')";
  mysql_query($query) or die(mysql_error());

  $query="insert into requirements (r_name, r_desc, r_p_id, r_parent_id, r_pos, r_creation_date, r_change_date, r_stub) 
  values ('Non-functional requirements','According to Volere oder DIN 66272','".$p_id."','','2',DATE_ADD( NOW( ) , INTERVAL - ".TIME_DIFF_HOURS." HOUR),DATE_ADD( NOW( ) , INTERVAL - ".TIME_DIFF_HOURS." HOUR),'2')";
  mysql_query($query) or die(mysql_error());
  $r_id=mysql_insert_id();
  
  $query="insert into requirements (r_name, r_desc, r_p_id, r_parent_id, r_pos, r_creation_date, r_change_date, r_stub) 
  values ('reliability','maturity, recoverabilty, tolerance to errors','".$p_id."','".$r_id."','1',DATE_ADD( NOW( ) , INTERVAL - ".TIME_DIFF_HOURS." HOUR),DATE_ADD( NOW( ) , INTERVAL - ".TIME_DIFF_HOURS." HOUR),'2')";
  mysql_query($query) or die(mysql_error());
  
  $query="insert into requirements (r_name, r_desc, r_p_id, r_parent_id, r_pos, r_creation_date, r_change_date, r_stub) 
  values ('look and feel','','".$p_id."','".$r_id."','2',DATE_ADD( NOW( ) , INTERVAL - ".TIME_DIFF_HOURS." HOUR),DATE_ADD( NOW( ) , INTERVAL - ".TIME_DIFF_HOURS." HOUR),'2')";
  mysql_query($query) or die(mysql_error());
  
  $query="insert into requirements (r_name, r_desc, r_p_id, r_parent_id, r_pos, r_creation_date, r_change_date, r_stub) 
  values ('Usabilty','understandability, easy to learn, easy to work with','".$p_id."','".$r_id."','3',DATE_ADD( NOW( ) , INTERVAL - ".TIME_DIFF_HOURS." HOUR),DATE_ADD( NOW( ) , INTERVAL - ".TIME_DIFF_HOURS." HOUR),'2')";
  mysql_query($query) or die(mysql_error());
  
  $query="insert into requirements (r_name, r_desc, r_p_id, r_parent_id, r_pos, r_creation_date, r_change_date, r_stub) 
  values ('performance and efficiency','response times, resource usage','".$p_id."','".$r_id."','4',DATE_ADD( NOW( ) , INTERVAL - ".TIME_DIFF_HOURS." HOUR),DATE_ADD( NOW( ) , INTERVAL - ".TIME_DIFF_HOURS." HOUR),'2')";
  mysql_query($query) or die(mysql_error());
  
  $query="insert into requirements (r_name, r_desc, r_p_id, r_parent_id, r_pos, r_creation_date, r_change_date, r_stub) 
  values ('operations and environment','','".$p_id."','".$r_id."','5',DATE_ADD( NOW( ) , INTERVAL - ".TIME_DIFF_HOURS." HOUR),DATE_ADD( NOW( ) , INTERVAL - ".TIME_DIFF_HOURS." HOUR),'2')";
  mysql_query($query) or die(mysql_error());
  
  $query="insert into requirements (r_name, r_desc, r_p_id, r_parent_id, r_pos, r_creation_date, r_change_date, r_stub) 
  values ('maintainability, ease of change','easy to analyse, stability, testability','".$p_id."','".$r_id."','6',DATE_ADD( NOW( ) , INTERVAL - ".TIME_DIFF_HOURS." HOUR),DATE_ADD( NOW( ) , INTERVAL - ".TIME_DIFF_HOURS." HOUR),'2')";
  mysql_query($query) or die(mysql_error());
  
  $query="insert into requirements (r_name, r_desc, r_p_id, r_parent_id, r_pos, r_creation_date, r_change_date, r_stub) 
  values ('portability and ease of transfer, ease of change','ease of change, ease of installation, conformance to standards, interoperability','".$p_id."','".$r_id."','7',DATE_ADD( NOW( ) , INTERVAL - ".TIME_DIFF_HOURS." HOUR),DATE_ADD( NOW( ) , INTERVAL - ".TIME_DIFF_HOURS." HOUR),'2')";
  mysql_query($query) or die(mysql_error());
  
  $query="insert into requirements (r_name, r_desc, r_p_id, r_parent_id, r_pos, r_creation_date, r_change_date, r_stub) 
  values ('security','confidentiality, data integrity, availability','".$p_id."','".$r_id."','8',DATE_ADD( NOW( ) , INTERVAL - ".TIME_DIFF_HOURS." HOUR),DATE_ADD( NOW( ) , INTERVAL - ".TIME_DIFF_HOURS." HOUR),'2')";
  mysql_query($query) or die(mysql_error());
  
  $query="insert into requirements (r_name, r_desc, r_p_id, r_parent_id, r_pos, r_creation_date, r_change_date, r_stub) 
  values ('cultural and political requirements','','".$p_id."','".$r_id."','9',DATE_ADD( NOW( ) , INTERVAL - ".TIME_DIFF_HOURS." HOUR),DATE_ADD( NOW( ) , INTERVAL - ".TIME_DIFF_HOURS." HOUR),'2')";
  mysql_query($query) or die(mysql_error());
  
  $query="insert into requirements (r_name, r_desc, r_p_id, r_parent_id, r_pos, r_creation_date, r_change_date, r_stub) 
  values ('legal requirements','','".$p_id."','".$r_id."','10',DATE_ADD( NOW( ) , INTERVAL - ".TIME_DIFF_HOURS." HOUR),DATE_ADD( NOW( ) , INTERVAL - ".TIME_DIFF_HOURS." HOUR),'2')";
  mysql_query($query) or die(mysql_error());
  
  $query="insert into requirements (r_name, r_desc, r_p_id, r_parent_id, r_pos, r_creation_date, r_change_date, r_stub) 
  values ('Constraints','','".$p_id."','','3',DATE_ADD( NOW( ) , INTERVAL - ".TIME_DIFF_HOURS." HOUR),DATE_ADD( NOW( ) , INTERVAL - ".TIME_DIFF_HOURS." HOUR),'2')";
  mysql_query($query) or die(mysql_error());
  
  $txt=$lng[99][59];
 }
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<LINK HREF="css/styles_admin.css" REL=stylesheet>
</head>
<body bgcolor=#E6E6E6 topmargin=0 leftmargin=0>
<center><br><br>
<?if ($txt!="") echo $txt."<br><br>";?>
<table border="1" cellpadding="4" cellspacing="0" width="90%">
<form method=post enctype='multipart/form-data' name="form_pos">
  <tr bgcolor=#C4D4D7>
    <td colspan=3 height=45 align=center><b>&nbsp;&nbsp;<?=$lng[99][55]?></b> &nbsp;&nbsp;&nbsp;
      <select name="template" onchange="document.forms.form_pos.submit();">
      <option value="">--
      <option value="1" <?if ($_POST['template']==1) echo "selected";?>><?=$lng[99][56]?>
      </select>
    </td>
  </tr> 
  
  <tr height=22>
    <td>
       <?if ($_POST['template']==1) {?>    
       <b>Name: </b>IT-project 1
       <br><b>Description: </b>General IT projects based on <a href="http://de.wikipedia.org/wiki/Anforderung_(Informatik)" target="_blank"><u>Anforderung_(Informatik)</u></a>
       <br><br>
       <b>Requirement tree (only headings)</b>
	<br>1. Functional requirements
	<br>2. Non-functional requirements (According to Volere oder DIN 66272)
	<br>2.1 reliability (maturity, recoverabilty, tolerance to errors)
	<br>2.2 look and feel
	<br>2.3 Usabilty (understandability, easy to learn, easy to work with)
	<br>2.4 performance and efficiency (response times, resource usage)
	<br>2.5 operations and environment
	<br>2.6 maintainability ,ease of change (easy to analyse, stability, testability)
	<br>2.7 portability and ease of transfer (ease of change, ease of installation, conformance to standards, interoperability)
	<br>2.8. security (confidentiality, data integrity, availability)
	<br>2.9 cultural and political requirements
	<br>2.10 legal requirements
	<br>3. Constraints
        <?
         $query="select * from projects where p_name='IT-project 1'";
         $rs=mysql_query($query) or die($query."<br/>".mysql_error());
         if($row=mysql_fetch_array($rs)) $fl=1;else $fl=0;
        ?>
       <br><br>
       <?if ($fl==1) {echo "<span style='color:red'>".$lng[99][58]."</span>";}
       else{?><input type="submit" value="<?=$lng[99][57]?>" name="Save"><?}?>       
       <?}?>
    </td>      
  </tr> 
 
</form>
</table>
<br><br>
</center>
</body>
</html>


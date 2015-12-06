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
// Page: "print" - converting the requirement tree into pdf file

session_start();
include ("admin/inc/conn.php");//include settings file
include ("admin/inc/func.php");//include functions file
include ("ini/params.php");//include configuration file

//default language
$_SESSION['chlang']=$_lng;

if (!$_SESSION['chlang']) $_SESSION['chlang']="en";
include ("ini/lng/".$_SESSION['chlang'].".php");//include language file
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="description" content="<?=$lng[1][2]?>"/>
	<meta name="keywords" content="<?=$lng[1][3]?>"/>
	<title><?=$lng[1][1]?></title>
	<link rel="stylesheet" href="s.css" type="text/css"/>
</head>
<body>
<a href="index.php"><img src="img/logo.jpg" border="0"></a>
<?
//parse url vaiables
$_vars = explode("|", $c_id);
$c_id=$_vars[0];
$_lng=$_vars[1];

//getting case info
$query="select * from cases where c_id=".$c_id;
$rs = mysql_query($query) or die(mysql_error());
if($row=mysql_fetch_array($rs)) 
 {
  $c_name=htmlspecialchars($row['c_name']);
  $c_desc=$row['c_desc'];
  $c_result=$row['c_result'];
  $c_status=htmlspecialchars($row['c_status']);
 }
?>

<table border="0" width="100%">
  <tr valign="top">
    <td>
        <table border="0" cellpadding="2" cellspacing="2" class="content" width="100%">
	  <tr class="gray">
	    <td class="gray" colspan="2" align="center"><b><?=$lng[31][1]?></b></td>
	  </tr>
	  <tr class="blue">
	    <td class="blue" align="right" width="50%">&nbsp;<?=$lng[31][2]?>&nbsp;:&nbsp;</td>
	    <td class="blue">&nbsp;<?=$c_name?></td>
	  </tr>  
	  <tr class="blue">
	    <td class="blue" align="right" width="50%">&nbsp;<?=$lng[31][3]?>&nbsp;:&nbsp;</td>
	    <td class="blue">&nbsp;<?=$c_desc?></td>
	  </tr>  
	  <tr class="blue">
	    <td class="blue" align="right" width="50%">&nbsp;<?=$lng[31][4]?>&nbsp;:&nbsp;</td>
	    <td class="blue">&nbsp;<?=$c_result?></td>
	  </tr>  
	  <tr class="blue">
	    <td class="blue" align="right">&nbsp;<?=$lng[31][5]?>&nbsp;:&nbsp;</td>
	    <td class="blue">
	    <?
	      if ($c_status==0) echo $lng[31][6];
	      elseif ($c_status==1) echo $lng[31][7];
	    ?>  
	    </td>
	  </tr>  
	  <tr class="blue" valign="top">
	    <td class="blue" align="right">&nbsp;<?=$lng[31][14]?>&nbsp;:&nbsp;</td>
	    <td class="blue">
	    <?
	    $query2="select p.* from projects p left outer join project_cases pc on p.p_id=pc.pc_p_id where pc.pc_c_id='".$c_id."' order by p.p_name asc";
	    $rs2 = mysql_query($query2) or die(mysql_error());
	    while($row2=mysql_fetch_array($rs2))
	     {
	      echo htmlspecialchars($row2['p_name']);
	      echo "<br>";
	     }
	    ?>
	    </td>
	  </tr>     
	  <tr class="blue" valign="top">
	    <td class="blue" align="right">&nbsp;<?=$lng[31][15]?>&nbsp;:&nbsp;</td>
	    <td class="blue">
	    <?
	    $query2="select r.* from releases r left outer join release_cases rc on r.r_id=rc.rc_r_id where rc.rc_c_id='".$c_id."' order by r.r_name asc";
	    $rs2 = mysql_query($query2) or die(mysql_error());
	    while($row2=mysql_fetch_array($rs2))
	     {
	      echo htmlspecialchars($row2['r_name']);
	      echo "<br>";
	     }
	    ?>
	    </td>
	  </tr>     
	  <tr class="blue" valign="top">
	    <td class="blue" align="right">&nbsp;<?=$lng[31][16]?>&nbsp;:&nbsp;</td>
	    <td class="blue">
	    <?
	    $query2="select * from requirements where CONCAT(',',r_c_id) like ('%,".$c_id.",%') order by r_name asc";
	    $rs2 = mysql_query($query2) or die(mysql_error());
	    while($row2=mysql_fetch_array($rs2))
	     {
	      echo htmlspecialchars($row2['r_name']);
	      echo "<br>";
	     }
	    ?>
	    </td>
	  </tr> 
	</table>
    </td> 	 
  </tr>
</table>


</body>
</html>


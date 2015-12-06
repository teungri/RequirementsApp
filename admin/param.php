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
// Page: "params " - displaying/modifying a list of site settings
?>
<?include("inc/conn.php");?>
<?include("inc/func.php");?>
<?include("inc/conn_admin.php");?>
<?
if ($Save=="Save")
 {
  if (!copy("../ini/params.php", "../ini/bkps/params_".date("m.d.Y H'i's").".php")) print ("Failed to create a bkp!");
  $fp = fopen ("../ini/params.php", "wb");
  if (fwrite($fp,stripslashes(trim($param_file))) === FALSE)  print ("Failed updating the file! Please, check file permissions.");
  fclose ($fp);
 }
?>


<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<LINK HREF="css/styles_admin.css" REL=stylesheet>
</head>
<body bgcolor=#E6E6E6 topmargin=0 leftmargin=0>
<center><br><br>
<table border="1" cellpadding="4" cellspacing="0">
<form method=post enctype='multipart/form-data' name="form_pos">
  <tr height=22>
    <td>
       <textarea name="param_file" rows="28" cols="100">
         <?
           $fp = fopen ("../ini/params.php", "r");
	   while (!feof ($fp)) {
             $buffer = fgets($fp, 4096);
             echo str_replace("","",$buffer);
	    }
	   fclose ($fp);
	 ?>  
       </textarea>    
    </td>      
  </tr> 
  
  <tr height=22>
    <td>
       <input type="submit" value="Save" name="Save">
    </td>      
  </tr>  
</form>
</table>
<br><br>
</center>
</body>
</html>


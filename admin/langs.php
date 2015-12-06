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
// Page: "Language files" - updating the 4 language files
?>
<?include("inc/conn.php");?>
<?include("inc/func.php");?>
<?include("inc/conn_admin.php");?>
<?include("inc/check_login.php");?>
<?
if ($lng2=="") $lng2="en";
if ($Save=="Save")
 {
  //saving the content of the selected lang file
  if (!copy("../ini/lng/".$lng2.".php", "../ini/lng/bkps/".$lng2."_".date("m.d.Y H'i's").".php")) print ("Failed to create a bkp!");
  $fp = fopen ("../ini/lng/".$lng2.".php", "wb");
  $lng_file=substr($lng_file,strpos($lng_file,"<?php"));
  if (fwrite($fp,stripslashes(trim($lng_file))) === FALSE)  print ("Failed updating the file! Please, check file permissions.");
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
<input type=hidden name="lng2" value="<?=$lng2?>">
  <tr bgcolor=#C4D4D7>
    <td colspan=3 height=45 align=center><b>:&nbsp;:&nbsp;&nbsp;<?=$lng[99][19]?></b> &nbsp;&nbsp;&nbsp;
      <select name="lng2" onchange="document.forms.form_pos.submit();">
        <option value="de" <?if ($lng2=="de") echo "selected";?>>DE
        <option value="fr" <?if ($lng2=="fr") echo "selected";?>>FR
        <option value="it" <?if ($lng2=="it") echo "selected";?>>IT
        <option value="en" <?if ($lng2=="en") echo "selected";?>>EN
      </select>&nbsp;&nbsp;&nbsp;<input type="button" value="<?=$lng[99][22]?>" onclick="window.open('check_lang.php?l=<?=$lng2?>','a','')">
    </td>
  </tr>  
  
  <tr height=22>
    <td>
       <textarea name="lng_file" rows="28" cols="100">
         <?
          //echo the content of the selected lang file
           $fp = fopen ("../ini/lng/".$lng2.".php", "r");
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
       <input type="submit" value="<?=$lng[99][18]?>" name="Save">
    </td>      
  </tr>  
</form>
</table>
<br><br>
</center>
</body>
</html>


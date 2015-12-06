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
// Page: "txt files" - updating the configurable lists in 4 languages
?>
<?include("inc/conn.php");?>
<?include("inc/func.php");?>
<?include("inc/conn_admin.php");?>
<?include("inc/check_login.php");?>
<?
if ($lng2=="") $lng2="en";
if ($Save=="Save" && $txt!="")
 {
  if (!copy("../ini/txts/".$lng2."/".$txt.".php", "../ini/txts/bkps/".$txt."_".date("m.d.Y H'i's").".php")) print ("Failed to create a bkp!");
  $fp = fopen ("../ini/txts/".$lng2."/".$txt.".php", "wb");
  $txt_file=substr($txt_file,strpos($txt_file,"<?"));
  if (fwrite($fp,stripslashes(trim($txt_file))) === FALSE)  print ("Failed updating the file! Please, check file permissions.");
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
<input type=hidden name="txt" value="<?=$txt?>">
  <tr bgcolor=#C4D4D7>
    <td colspan=3 height=45 align=center><b>:&nbsp;:&nbsp;&nbsp;<?=$lng[99][20]?></b> &nbsp;&nbsp;&nbsp;
      <select name="lng2" onchange="document.forms.form_pos.submit();">
        <option value="de" <?if ($lng2=="de") echo "selected";?>>DE
        <option value="fr" <?if ($lng2=="fr") echo "selected";?>>FR
        <option value="it" <?if ($lng2=="it") echo "selected";?>>IT
        <option value="en" <?if ($lng2=="en") echo "selected";?>>EN
      </select>
      
      <select name="txt" onchange="document.forms.form_pos.submit();">
      <option value=""><?=$lng[99][16]?>
      <?
        if ($dir = @opendir("../ini/txts/".$lng2)) 
         {
          while (($file = readdir($dir)) !== false)
           {
            if ($file!="." && $file!="..") 
             {
              ?>
              <option value="<?=str_replace(".php","",$file)?>" <?if ($txt==str_replace(".php","",$file)) echo "selected";?>><?=str_replace(".php","",$file)?>
              <?
             } 
          }  
  	 closedir($dir);
	}
        ?>
      </select>
    </td>
  </tr> 
  
  <tr height=22>
    <td>
       <textarea name="txt_file" rows="28" cols="100">
         <?
          if (file_exists("../ini/txts/".$lng2."/".$txt.".php"))
           {
            $fp = fopen ("../ini/txts/".$lng2."/".$txt.".php", "r");
	    while (!feof ($fp)) {
              $buffer = fgets($fp, 4096);
              echo str_replace("","",$buffer);
	     }
	    fclose ($fp);
	   } 
	 ?>  
       </textarea>    
    </td>      
  </tr> 
  
  <tr height=22>
    <td>
       <input type="submit" value="<?=$lng[99][18]?>" name="Save">
    </td>      
  </tr> 
    <?
  if ($txt=="news" || $txt=="_about") {
  ?>
  <tr height=22>
    <td style="color:red">
      HTML is enabled here!
    </td>
  </tr>  
  <?}?>
 
</form>
</table>
<br><br>
</center>
</body>
</html>


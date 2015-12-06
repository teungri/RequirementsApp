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
// Page: "fields" - creating and updating user defined texts
?>
<?include("inc/conn.php");?>
<?include("inc/func.php");?>
<?include("inc/conn_admin.php");?>
<?include("inc/check_login.php");?>
<?
if ($lng2=="") $lng2="en";
if ($Save!="")
 {
  if ($uf_name_en1!="") $query="update user_fields set uf_name_en='".escapeChars($uf_name_en1)."', uf_name_de='".escapeChars($uf_name_de1)."', uf_name_fr='".escapeChars($uf_name_fr1)."', uf_name_it='".escapeChars($uf_name_it1)."', uf_text_en='".escapeChars($uf_text_en1)."', uf_text_de='".escapeChars($uf_text_de1)."', uf_text_fr='".escapeChars($uf_text_fr1)."', uf_text_it='".escapeChars($uf_text_it1)."', uf_type='".escapeChars($uf_type1)."', uf_values='".escapeChars($uf_values1)."' where uf_id=1";
  else $query="update user_fields set uf_name_en='', uf_name_de='', uf_name_fr='', uf_name_it='', uf_text_en='', uf_text_de='', uf_text_fr='', uf_text_it='', uf_type='', uf_values='' where uf_id=1";
  $rs = mysql_query($query) or die(mysql_error());

  if ($uf_name_en2!="") $query="update user_fields set uf_name_en='".escapeChars($uf_name_en2)."', uf_name_de='".escapeChars($uf_name_de2)."', uf_name_fr='".escapeChars($uf_name_fr2)."', uf_name_it='".escapeChars($uf_name_it2)."', uf_text_en='".escapeChars($uf_text_en2)."', uf_text_de='".escapeChars($uf_text_de2)."', uf_text_fr='".escapeChars($uf_text_fr2)."', uf_text_it='".escapeChars($uf_text_it2)."', uf_type='".escapeChars($uf_type2)."', uf_values='".escapeChars($uf_values2)."' where uf_id=2";
  else $query="update user_fields set uf_name_en='', uf_name_de='', uf_name_fr='', uf_name_it='', uf_text_en='', uf_text_de='', uf_text_fr='', uf_text_it='', uf_type='', uf_values='' where uf_id=2";
  $rs = mysql_query($query) or die(mysql_error());

  if ($uf_name_en3!="") $query="update user_fields set uf_name_en='".escapeChars($uf_name_en3)."', uf_name_de='".escapeChars($uf_name_de3)."', uf_name_fr='".escapeChars($uf_name_fr3)."', uf_name_it='".escapeChars($uf_name_it3)."', uf_text_en='".escapeChars($uf_text_en3)."', uf_text_de='".escapeChars($uf_text_de3)."', uf_text_fr='".escapeChars($uf_text_fr3)."', uf_text_it='".escapeChars($uf_text_it3)."', uf_type='".escapeChars($uf_type3)."', uf_values='".escapeChars($uf_values3)."' where uf_id=3";
  else $query="update user_fields set uf_name_en='', uf_name_de='', uf_name_fr='', uf_name_it='', uf_text_en='', uf_text_de='', uf_text_fr='', uf_text_it='', uf_type='', uf_values='' where uf_id=3";
  $rs = mysql_query($query) or die(mysql_error());

  if ($uf_name_en4!="") $query="update user_fields set uf_name_en='".escapeChars($uf_name_en4)."', uf_name_de='".escapeChars($uf_name_de4)."', uf_name_fr='".escapeChars($uf_name_fr4)."', uf_name_it='".escapeChars($uf_name_it4)."', uf_text_en='".escapeChars($uf_text_en4)."', uf_text_de='".escapeChars($uf_text_de4)."', uf_text_fr='".escapeChars($uf_text_fr4)."', uf_text_it='".escapeChars($uf_text_it4)."', uf_type='".escapeChars($uf_type4)."', uf_values='".escapeChars($uf_values4)."' where uf_id=4";
  else $query="update user_fields set uf_name_en='', uf_name_de='', uf_name_fr='', uf_name_it='', uf_text_en='', uf_text_de='', uf_text_fr='', uf_text_it='', uf_type='', uf_values='' where uf_id=4";
  $rs = mysql_query($query) or die(mysql_error());

  if ($uf_name_en5!="") $query="update user_fields set uf_name_en='".escapeChars($uf_name_en5)."', uf_name_de='".escapeChars($uf_name_de5)."', uf_name_fr='".escapeChars($uf_name_fr5)."', uf_name_it='".escapeChars($uf_name_it5)."', uf_text_en='".escapeChars($uf_text_en5)."', uf_text_de='".escapeChars($uf_text_de5)."', uf_text_fr='".escapeChars($uf_text_fr5)."', uf_text_it='".escapeChars($uf_text_it5)."', uf_type='".escapeChars($uf_type5)."', uf_values='".escapeChars($uf_values5)."' where uf_id=5";
  else $query="update user_fields set uf_name_en='', uf_name_de='', uf_name_fr='', uf_name_it='', uf_text_en='', uf_text_de='', uf_text_fr='', uf_text_it='', uf_type='', uf_values='' where uf_id=5";
  $rs = mysql_query($query) or die(mysql_error());

  if ($uf_name_en6!="") $query="update user_fields set uf_name_en='".escapeChars($uf_name_en6)."', uf_name_de='".escapeChars($uf_name_de6)."', uf_name_fr='".escapeChars($uf_name_fr6)."', uf_name_it='".escapeChars($uf_name_it6)."', uf_text_en='".escapeChars($uf_text_en6)."', uf_text_de='".escapeChars($uf_text_de6)."', uf_text_fr='".escapeChars($uf_text_fr6)."', uf_text_it='".escapeChars($uf_text_it6)."', uf_type='".escapeChars($uf_type6)."', uf_values='".escapeChars($uf_values6)."' where uf_id=6";
  else $query="update user_fields set uf_name_en='', uf_name_de='', uf_name_fr='', uf_name_it='', uf_text_en='', uf_text_de='', uf_text_fr='', uf_text_it='', uf_type='', uf_values='' where uf_id=6";
  $rs = mysql_query($query) or die(mysql_error());
 }

$query="select * from user_fields order by uf_id asc";
$rs = mysql_query($query) or die(mysql_error());
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<LINK HREF="css/styles_admin.css" REL=stylesheet>
</head>
<body bgcolor=#E6E6E6 topmargin=0 leftmargin=0>
<center><br><br>
<table border="1" cellpadding="4" cellspacing="0" width=600>
<form method=post enctype='multipart/form-data' name="form_pos">
<input type=hidden name="lng2" value="<?=$lng2?>">
  <tr bgcolor=#C4D4D7>
    <td colspan=2 height=45 align=center><b>:&nbsp;:&nbsp;&nbsp;<?=$lng[99][37]?></b> &nbsp;&nbsp;&nbsp;</td>
  </tr> 
  <?
  $cnt=0;
  while($row=mysql_fetch_array($rs)) 
   { 
    $cnt++;
  ?>
  <tr height=22>
    <td colspan=2>&nbsp;<b><?=$lng[99][38]." ".$cnt?> :</b></td>      
  </tr> 
  <tr height=22>
    <td width="150" align="right">&nbsp;<?=$lng[99][39]?>:</td>      
    <td>&nbsp;<input type="text" name="uf_name_en<?=$cnt?>" value="<?=htmlspecialchars($row['uf_name_en'])?>" size=60> <b><?=$lng[99][53]?></b></td>      
  </tr> 
  <tr height=22>
    <td width="150" align="right">&nbsp;<?=$lng[99][40]?>:</td>      
    <td>&nbsp;<input type="text" name="uf_name_de<?=$cnt?>" value="<?=htmlspecialchars($row['uf_name_de'])?>" size=60></td>      
  </tr> 
  <tr height=22>
    <td width="150" align="right">&nbsp;<?=$lng[99][41]?>:</td>      
    <td>&nbsp;<input type="text" name="uf_name_fr<?=$cnt?>" value="<?=htmlspecialchars($row['uf_name_fr'])?>" size=60></td>      
  </tr> 
  <tr height=22>
    <td width="150" align="right">&nbsp;<?=$lng[99][42]?>:</td>      
    <td>&nbsp;<input type="text" name="uf_name_it<?=$cnt?>" value="<?=htmlspecialchars($row['uf_name_it'])?>" size=60></td>      
  </tr> 
  <tr height=22>
    <td width="150" align="right">&nbsp;<?=$lng[99][43]?>:</td>      
    <td>&nbsp;<input type="text" name="uf_text_en<?=$cnt?>" value="<?=htmlspecialchars($row['uf_text_en'])?>" size=60></td>      
  </tr> 
  <tr height=22>
    <td width="150" align="right">&nbsp;<?=$lng[99][44]?>:</td>      
    <td>&nbsp;<input type="text" name="uf_text_de<?=$cnt?>" value="<?=htmlspecialchars($row['uf_text_de'])?>" size=60></td>      
  </tr> 
  <tr height=22>
    <td width="150" align="right">&nbsp;<?=$lng[99][45]?>:</td>      
    <td>&nbsp;<input type="text" name="uf_text_fr<?=$cnt?>" value="<?=htmlspecialchars($row['uf_text_fr'])?>" size=60></td>      
  </tr> 
  <tr height=22>
    <td width="150" align="right">&nbsp;<?=$lng[99][46]?>:</td>      
    <td>&nbsp;<input type="text" name="uf_text_it<?=$cnt?>" value="<?=htmlspecialchars($row['uf_text_it'])?>" size=60></td>      
  </tr> 
  <tr height=22>
    <td width="150" align="right">&nbsp;<?=$lng[99][47]?>:</td>      
    <td>&nbsp;
      <select name="uf_type<?=$cnt?>">
        <option value="0" <?if($row['uf_type']==0) echo "selected";?>><?=$lng[99][48]?>
        <option value="1" <?if($row['uf_type']==1) echo "selected";?>><?=$lng[99][49]?>
      </select>
    </td>      
  </tr> 
  <tr height=22 valign="top">
    <td width="150" align="right">&nbsp;<?=$lng[99][50]?>:</td>      
    <td style="color:red">&nbsp;<textarea name="uf_values<?=$cnt?>" rows="7" cols="80"><?=htmlspecialchars($row['uf_values'])?></textarea>
        <br><?=$lng[99][51]?><br><?=$lng[99][52]?>
    </td>      
  </tr> 
  <tr height=22>
    <td colspan=2>&nbsp;</td>      
  </tr> 
  <?}?>
  <tr height=22>
    <td colspan=2 style="color:red">&nbsp;<?=$lng[99][54]?></td>      
  </tr> 
  <tr height=22>
    <td colspan=2>
       <input type="submit" value="<?=$lng[99][18]?>" name="Save">
    </td>      
  </tr> 
</form>
</table>
<br><br>
</center>
</body>
</html>


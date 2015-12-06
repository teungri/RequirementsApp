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
// Page: "Login" - user's login

if ($u_username!="")
 {
  //deleting all old records from tree history
  $query="delete from tree_history where th_date<DATE_SUB(now(), INTERVAL 1 HOUR);";
  mysql_query($query) or die(mysql_error());
 
  $query="select * from users where u_username='".escapeChars($u_username)."' and u_password='".pw($u_password)."'";
  $rs = mysql_query($query) or die(mysql_error());
  if($row=mysql_fetch_array($rs))
   {
    $_SESSION['uid']=$row['u_id'];
    $_SESSION['email']=$row['u_email'];
    $_SESSION['username']=stripslashes($row['u_username']);
    $_SESSION['name']=stripslashes($row['u_name']);
    $_SESSION['rights']=$row['u_rights'];
    if (strstr($_SESSION['http_ref'],"lost_password")) header("Location:index.php");
    elseif ($_SESSION['http_ref']!="") header("Location:index.php?".$_SESSION['http_ref']);
    else header("Location:index.php");
   } 
  else $tmp="<br><br><span class='error'>".$lng[5][6]."</span>";
 
 }
?>
<?if ($lp=="yes") echo "<br><span class='error'>".$lng[7][4]."</span><br><br>";?>
<table border="0">
  <tr valign="top">
    <td>
      <form method="post" name="f" action="">
	<table border="0" cellpadding="2" cellspacing="2" class="content" width="50%">
	  <tr class="gray">
	    <td colspan="2" align="center"><b><?=$lng[5][1]?></b></td>
	  </tr>
	  <tr class="blue">
	    <td align="right">&nbsp;<?=$lng[5][2]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;<input type="text" name="u_username" value="<?=stripslashes(htmlspecialchars($u_username))?>"></td>
	  </tr>  
	  <tr class="blue">
	    <td align="right">&nbsp;<?=$lng[5][3]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;<input type="password" name="u_password"></td>
	  </tr>
	  <tr class="gray">
	    <td colspan="2" align="center"><input type="submit" value="<?=$lng[5][1]?>" name="Login"></td>
	  </tr> 
	  <tr class="gray">
	    <td colspan="2" align="center"><?=$lng[5][7]?></td>
	  </tr> 	   	    
	</table>
      <input type="hidden" name="inc" value="login">	
      </form>	
    </td> 	 
  </tr>
</table>
<a href="index.php?inc=register"><?=$lng[5][4]?></a> | <a href="index.php?inc=lost_password"><?=$lng[5][5]?></a>
<?=$tmp?>

<?
//news section
include("ini/txts/".$_SESSION['chlang']."/news.php");
//rsort($news_array);
$paging=NUMBER_OF_NEWS_SHOWN;
$cnt=0;
$all_count=count($news_array);
if ($from=="") $from=0; 
if ($from+$paging>$all_count) $cnt2=$all_count;
else $cnt2=$from+$paging;
$cnt3=0;
$news_array2="";
for ($i=($all_count-1);$i>=0;$i--) {$news_array2[$cnt3]=$news_array[$i];$cnt3++;}

?>
<br><br>
<table border="0" cellspacing="2" cellpadding="2" width="98%">
  <tr>
    <td align="left"><b><u>News:</u></b></td> 	 
  </tr>
  <?
  for ($i=$from;$i<$cnt2;$i++)
   {
    $cnt++;
   ?>
  <tr valign="top">
    <td align="left"><?=$news_array2[$i]['title']?>&nbsp;(<?=$news_array2[$i]['date']?>)</td> 	 
  </tr>
  <tr valign="top">
    <td align="left"><?=$news_array2[$i]['text']?></td> 	 
  </tr>
  <tr valign="top">
    <td align="left">&nbsp;</td> 	 
  </tr>
  <? 
   }
  
//displaying paging list
if ($all_count>$paging && $cnt!=0) 
 {
  $j=ceil($all_count/$paging);
  for ($i=0,$tmp_c=0;$i<$j;$i++,$tmp_c++) 
   {
    if ($i*$paging==$from) $tmp_paging.=($i+1)." &nbsp;";	
    else $tmp_paging.="<a href=# onclick='subm_paging(".($i*$paging).");return false;'>".($i+1)."</a> &nbsp;";
   } 
  $tmp_paging=substr($tmp_paging,0,strlen($tmp_paging)-1);
 }  
?>  
 <? if ($cnt>0 && $tmp_paging!=""){?>
<tr align=left>
     <td align=left>&nbsp;<b>
     <?if ($from>0) echo "<a href=# onclick='subm_paging(".($from-$paging).");return false;'>&lt;</a>&nbsp;&nbsp;";?>
     <?=$tmp_paging?>
     <?if ($from<$all_count-$paging) echo "<a href=# onclick='subm_paging(".($from+$paging).");return false;'>&gt;</a>";?>
     </b></td>
</tr>
<?}?>  
</table>

<form method=post name=form_paging action="">
<input type=hidden name=from value="">
</form>

<script>
function subm_paging(who)
 {
  document.forms['form_paging'].from.value=who;
  document.forms['form_paging'].submit();
 }  
</script> 
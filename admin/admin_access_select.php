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
// Page: "Users " - displaying the list of admin users with paging, possibility for deleting records
?>
<?include("inc/conn.php");?>
<?include("inc/func.php");?>
<?include("inc/conn_admin.php");?>
<?
//delete record
if ($action=="delete" && $aa_id!="")
 {
  //deleting user
  $query="delete from admin_access where aa_id=".$aa_id;
  mysql_query($query) or die($query."<br/>".mysql_error());
  $aa_id="";  
 }
?>


<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<LINK HREF="css/styles_admin.css" REL=stylesheet>
</head>
<body bgcolor=#E6E6E6 topmargin=0 leftmargin=0>
<center><br/><br/>
<table border=1 cellpadding=4 cellspacing=0 width=324>
<form method=post>
<input type=hidden name=aa_id value="<?=$aa_id?>">
  <tr bgcolor=#C4D4D7>
    <td colspan=7 height=45 align=center><b>:&nbsp;:&nbsp;&nbsp;<?=$lng[99][13]?>&nbsp;&nbsp;:&nbsp;:</b></td>
  </tr>  
  <tr bgcolor=#C4D4D7 height=22>
      <td align=right width=20>No</td>
      <td align=right width=20><?=$lng[99][2]?></td>
      <td align=left><?=$lng[99][28]?></td>
  </tr>  
  <?
  //paging query, 10 results per page displayed
  $paging=10;
  $query_cnt="SELECT count(aa_id) as cnt from admin_access".$tmp_query;
  $rs_cnt = mysql_query($query_cnt,$link) or die('select failed: <b>'.mysql_error().'</b>'."<br/>\n");
  if($row_cnt=mysql_fetch_array($rs_cnt)) $all_count=$row_cnt['cnt']-1;	
  if ($from=="") $from=0; 

  //getting all records
  $query="select aa_id, aa_username from admin_access where aa_username<>'slav' order by aa_username Limit ".$from.",".$paging;
  $rs = mysql_query($query) or die(mysql_error());
  $cnt=0;
  while($row=mysql_fetch_array($rs))
   {
    $cnt++;
    if ($cnt % 2) $bgcolor="#E6E6E6";
    else $bgcolor="#D7D7D7";
    ?>
    <tr bgcolor="<?=$bgcolor?>" height=22>
      <td align=right width=20><?=($cnt+$from)?></td>
      <td align=left nowrap><a href=admin_access.php?aa_id=<?=$row[0]?> target=main><?=htmlspecialchars($row[1])?></a>&nbsp;&nbsp;</td>
      <td align=left width=50>
        <a href=admin_access.php?aa_id=<?=$row[0]?> target=main><img src=img/b_edit.gif border=0></a>&nbsp;&nbsp;&nbsp;
        <a href=admin_access_select.php?aa_id=<?=$row[0]?>&action=delete onclick="if (!confirm('<?=$lng[99][29]?>')) return false;" target=main><img src=img/b_drop.gif border=0></a>
      </td>
    </tr>   
    <?
   }
  ?>  
</form>


<?
//displaying paging list
if ($all_count>$paging && $cnt!=0) 
 {
  $j=ceil($all_count/$paging);
  for ($i=0,$tmp_c=0;$i<$j;$i++,$tmp_c++) 
   {
    if ($i*$paging==$from) $tmp_paging.="<span class=paging_hit>".($i+1)."</span> &nbsp;";	
    else $tmp_paging.="<a href=# class=paging onclick='subm_paging(".($i*$paging).");return false;'>".($i+1)."</a> &nbsp;";
   } 
  $tmp_paging=substr($tmp_paging,0,strlen($tmp_paging)-1);
 }  
?>
<tr>
    <td colspan=7 height=22 align=right bgcolor=<?if ($bgcolor=="#D7D7D7") echo "#E6E6E6";else echo "#D7D7D7";?>><a href=admin_access.php target=main><b><?=$lng[99][30]?></b></a></td>
</tr>
<? if ($cnt>0 && $tmp_paging!=""){?>
<tr bgcolor=#C4D4D7 align=right>
     <td colspan=7 align=right valign=bottom class=paging><b>
     <?if ($from>0) echo "<a href=# class=paging onclick='subm_paging(".($from-$paging).");return false;'>&lt;</a>&nbsp;&nbsp;";?>
     <?=$tmp_paging?>
     <?if ($from<$all_count-$paging) echo "<a href=# class=paging onclick='subm_paging(".($from+$paging).");return false;'>&gt;</a>";?>
     </b></td>
</tr>
<?}?>
</table>
<br/><br/>
</center>
</body>
</html>

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
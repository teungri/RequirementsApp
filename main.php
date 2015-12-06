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
// Page: "Main" - user's assigned projects/requirements

//check if logged
if ($_SESSION['uid']=="") header("Location:index.php?inc=login");

include("ini/txts/".$_SESSION['chlang']."/state.php");
?>
<table border="0" width="100%">
  <tr valign="top">
    <td width="50%">
        <?if ($err=="yes") echo "<span class='error'>".$lng[4][8]."<span><br><br>";?>
	<table border="0" cellpadding="2" cellspacing="2" class="content" width="50%">
	  <tr class="gray">
	    <td colspan="4">&nbsp;<b><?=$lng[4][1]?></b></td>
	  </tr>
	  <?
	  //getting requirements - assigned
	  $query="select r.*, date_format(r.r_creation_date, '%d.%m.%Y %H:%i') as d1, date_format(r.r_change_date, '%d.%m.%Y %H:%i') as d2, p.p_id,p.p_name from requirements r left outer join projects p on r.r_p_id=p.p_id where  r.r_assigned_u_id=".$_SESSION['uid']." and r.r_p_id in (".$project_list.") order by r.r_change_date desc";
	  $rs = mysql_query($query) or die(mysql_error());
	  while($row=mysql_fetch_array($rs)) 
	   {
	    if (mktime (0,0,0,date("m"),date("d")-2,date("Y")) < mktime (0,0,0,substr($row['d1'],3,2),substr($row['d1'],0,2),substr($row['d1'],6,4))) $new=1;
	    else $new=0;
	    $cl=$state_colors_array[$row['r_state']];
	  ?>
	  <tr class="red" style="background-color:<?=$cl?>;">
	    <td width="30" align=center>&nbsp;<a href="index.php?inc=view_requirement&r_id=<?=$row['r_id']?>"><?=$new?"<b>":""?><?=$row['r_id']?><?=$new?"</b>":""?></a></td>
	    <td>&nbsp;<?=$new?"<b>":""?><?=htmlspecialchars($row['r_name'])?><?=$new?"</b>":""?></td>
	    <td width="110">&nbsp;<?=$new?"<b>":""?><?=($row['d2']!="00.00.0000 00:00")?$row['d2']:"(".$row['d1'].")"?><?=$new?"</b>":""?></td>
	    <td width="100">&nbsp;<a href="index.php?inc=view_project&p_id=<?=$row['p_id']?>"><?=$new?"<b>":""?><?=htmlspecialchars($row['p_name'])?><?=$new?"</b>":""?></a></td>
	  </tr>	  
	  <?}?>
	  
	  
	  <?if ($_SESSION['rights']==1 || $_SESSION['rights']==2 || $_SESSION['rights']==3 || $_SESSION['rights']==4) {?>
	  <tr>
	    <td colspan="4">&nbsp;</td>
	  </tr>
	  <tr class="gray">
	    <td colspan="4">&nbsp;<b><?=$lng[4][5]?></b></td>
	  </tr>
	  <?//getting requirements - waiting for acceptance
	  $query="select r.*, date_format(r.r_creation_date, '%d.%m.%Y %H:%i') as d1, date_format(r.r_change_date, '%d.%m.%Y %H:%i') as d2, p.p_id,p.p_name from requirements r left outer join projects p on r.r_p_id=p.p_id where r.r_state=0 and r.r_p_id in (".$project_list.") order by r.r_change_date desc";
	  $rs = mysql_query($query) or die(mysql_error());
	  while($row=mysql_fetch_array($rs)) 
	   {
	    if (mktime (0,0,0,date("m"),date("d")-2,date("Y")) < mktime (0,0,0,substr($row['d1'],3,2),substr($row['d1'],0,2),substr($row['d1'],6,4))) $new=1;
	    else $new=0;	  
	    $cl=$state_colors_array[$row['r_state']];
	  ?>
	  <tr class="orange" style="background-color:<?=$cl?>;">
	    <td width="30" align=center>&nbsp;<a href="index.php?inc=view_requirement&r_id=<?=$row['r_id']?>"><?=$new?"<b>":""?><?=$row['r_id']?><?=$new?"</b>":""?></a></td>
	    <td>&nbsp;<?=$new?"<b>":""?><?=htmlspecialchars($row['r_name'])?><?=$new?"</b>":""?></td>
	    <td width="110">&nbsp;<?=$new?"<b>":""?><?=($row['d2']!="00.00.0000 00:00")?$row['d2']:"(".$row['d1'].")"?><?=$new?"</b>":""?></td>
	    <td width="100">&nbsp;<a href="index.php?inc=view_project&p_id=<?=$row['p_id']?>"><?=$new?"<b>":""?><?=htmlspecialchars($row['p_name'])?><?=$new?"</b>":""?></a></td>
	  </tr>	  
	   <?}?>
	  <?}?>	  
	</table>
    </td>
    <td>&nbsp;&nbsp;&nbsp;</td>	 
    <td width="50%">
	<form method=post name=form_paging action="">
	<table border="0" cellpadding="2" cellspacing="2" class="content" width="50%">
	  <?
	  //paging query, number of results (from the param.php file) per page displayed
	  $paging=$PPAGE;
	  if ($_ppaging!="") $paging=$_ppaging;
	  $_ppaging=$paging;
	  $query="select count(*) from requirements r left outer join projects p on r.r_p_id=p.p_id where r.r_p_id in (".$project_list.")";
	  $rs = mysql_query($query) or die(mysql_error());
	  if($row=mysql_fetch_array($rs)) $all_count=$row[0];
	  if ($from=="") $from=0; 
	  if ($from+$paging>$all_count) $cnt2=$all_count;
	  else $cnt2=$from+$paging;
	  ?>
	  <tr class="gray">
	    <td colspan="4">&nbsp;<b><?=$lng[4][3]?> (<?=($from+1)?> - <?=$cnt2?> / <?=$all_count?> )</b></td>
	  </tr>
	  <?
	  //getting requirements - recently modified
	  $query="select r.*, date_format(r.r_creation_date, '%d.%m.%Y %H:%i') as d1, date_format(r.r_change_date, '%d.%m.%Y %H:%i') as d2, p.p_id,p.p_name from requirements r left outer join projects p on r.r_p_id=p.p_id where r.r_p_id in (".$project_list.") order by r.r_change_date desc Limit ".$from.",".$paging;
	  $rs = mysql_query($query) or die(mysql_error());
	  $cnt=0;
	  while($row=mysql_fetch_array($rs)) 
	   {
	    $cnt++;
	    if (mktime (0,0,0,date("m"),date("d")-2,date("Y")) < mktime (0,0,0,substr($row['d1'],3,2),substr($row['d1'],0,2),substr($row['d1'],6,4))) $new=1;
	    else $new=0;	  
	    $cl=$state_colors_array[$row['r_state']];
	  ?>
	  <tr class="blue" style="background-color:<?=$cl?>;">
	    <td width="30" align=center>&nbsp;<a href="index.php?inc=view_requirement&r_id=<?=$row['r_id']?>"><?=$new?"<b>":""?><?=$row['r_id']?><?=$new?"</b>":""?></a></td>
	    <td>&nbsp;<?=$new?"<b>":""?><?=htmlspecialchars($row['r_name'])?><?=$new?"</b>":""?></td>
	    <td width="110">&nbsp;<?=$new?"<b>":""?><?=($row['d2']!="00.00.0000 00:00")?$row['d2']:"(".$row['d1'].")"?><?=$new?"</b>":""?></td>
	    <td>&nbsp;<a href="index.php?inc=view_project&p_id=<?=$row['p_id']?>"><?=$new?"<b>":""?><?=htmlspecialchars($row['p_name'])?><?=$new?"</b>":""?></a></td>
	  </tr>	  
	  <?}?>
	  
	  
	 <?
	//displaying paging list
	if ($all_count>$paging && $cnt!=0) 
	 {
	  $j=ceil($all_count/$paging);
	  for ($i=0,$tmp_c=0;$i<$j;$i++,$tmp_c++) 
	   {
	    if ($i*$paging==$from) $tmp_paging.=($i+1)." &nbsp;";	
	    elseif ( ($i*$paging>=$from-(4*$paging)) && ($i*$paging<=$from+(4*$paging))) $tmp_paging.="<a href=# onclick='subm_paging(".($i*$paging).");return false;'>".($i+1)."</a> &nbsp;";
	   } 
	  $tmp_paging=substr($tmp_paging,0,strlen($tmp_paging)-1);
	 }  
	?>  
	 <? if ($cnt>0 && $tmp_paging!="" && $_SESSION['_viewalltype']!=0){?>
	
	
	<tr class="gray" align=left>
	     <td colspan=3 align=left>&nbsp;<b>
	     
	     <?if ($from>0) echo "<a href=# onclick='subm_paging(0);return false;'>&lt;&lt;</a>&nbsp;&nbsp;<a href=# onclick='subm_paging(".($from-$paging).");return false;'>&lt;</a>&nbsp;&nbsp;";?>
	     <?=$tmp_paging?>
	     <?if ($from<$all_count-$paging) echo "<a href=# onclick='subm_paging(".($from+$paging).");return false;'>&gt;</a>&nbsp;&nbsp;<a href=# onclick='subm_paging(".(($i-1)*$paging).");return false;'>&gt;&gt;</a>";?>
	     </b>
	     </td>
	     
	     <td align=right><?=$lng[4][9]?> <select name="_ppaging" onchange="document.forms['form_paging'].submit();">
	     <option value="10" <?if ($_ppaging==10) echo "selected";?>>10
	     <option value="20" <?if ($_ppaging==20) echo "selected";?>>20
	     <option value="50" <?if ($_ppaging==50) echo "selected";?>>50
	     <option value="100" <?if ($_ppaging==100) echo "selected";?>>100
	     </select>
	     </td>
	</tr>
	<?}?>
	
	</table>
	<input type=hidden name=from value="">
	</form>
    </td>	 
  </tr>
  <tr valign="top" colspan="3">
    <td><br></td>
  </tr> 
  <tr valign="top">
    <td colspan="3">
      <table border="0" cellpadding="2" cellspacing="2" class="content" width="50%">
	 <tr>
	   <?for ($i=0;$i<count($state_array);$i++) {?>
	   <td align="center" class="blue" style="background-color: <?=$state_colors_array[$i]?>;" width="12.5%">&nbsp;<?=$state_array[$i]?></td>
	   <?}?>
	   <!--td align="center" class="red" width="17%">&nbsp;<?=$lng[17][12]?></td>
	   <td align="center" class="violet" width="17%">&nbsp;<?=$lng[17][13]?></td>
	   <td align="center" class="green" width="17%">&nbsp;<?=$lng[17][14]?></td>
	   <td align="center" class="yellow" width="17%">&nbsp;<?=$lng[17][15]?></td>
	   <td align="center" class="orange" width="17%">&nbsp;<?=$lng[17][10]?></td>
	   <td align="center" class="gray" width="17%">&nbsp;<?=$lng[17][11]?></td-->	 
	 </tr>
      </table>
    </td>
  </tr>  
</table>



<script>
function subm_paging(who)
 {
  document.forms['form_paging'].from.value=who;
  document.forms['form_paging'].submit();
 }  
 </script>
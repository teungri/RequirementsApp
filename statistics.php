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
// Page: "statistics" - display statistics for selected project

?>
<table border="0" width="100%">
  <tr valign="top">
    <td width="50%">
	<table border="0" cellpadding="2" cellspacing="2" class="content" width="50%">
	  <tr class="gray">
	    <td colspan="2" align="center">&nbsp;<b><?=$lng[26][1]?></b></td>
	  </tr>
	    <?
	    $query="select * from projects where p_id in (".$project_list.")";
 	    $rs = mysql_query($query) or die(mysql_error());
  	    while($row=mysql_fetch_array($rs)) 
  	     {
  	      $p_name=htmlspecialchars($row['p_name']);

	      $query2="select count(*) from project_releases where pr_p_id=".$row['p_id'];
 	      $rs2 = mysql_query($query2) or die(mysql_error());
  	      if($row2=mysql_fetch_array($rs2)) $pr_cnt=htmlspecialchars($row2[0]);
	
	      $query2="select count(*) from requirements where r_p_id=".$row['p_id'];
 	      $rs2 = mysql_query($query2) or die(mysql_error());
  	      if($row2=mysql_fetch_array($rs2)) $r_cnt=htmlspecialchars($row2[0]);
	     ?>
	    <tr class="light_blue">
	      <td nowrap>&nbsp;<?=$lng[26][2]?>&nbsp;</td>
	      <td width="100%">&nbsp;<?=$p_name?>&nbsp;</td>
	    </tr>
	    <tr class="light_blue">
	      <td nowrap>&nbsp;<?=$lng[26][3]?>&nbsp;</td>
	      <td>&nbsp;<?=$pr_cnt?>&nbsp;</td>
	    </tr>
	    <tr class="light_blue">
	      <td nowrap>&nbsp;<?=$lng[26][4]?>&nbsp;</td>
	      <td>&nbsp;<?=$r_cnt?>&nbsp;</td>
	    </tr>
	    <tr class="blue">
	      <td nowrap colspan="2">&nbsp;<?=$lng[26][5]?>&nbsp;</td>
	    </tr>
	    <?
	      $query2="select u.u_name, count(u.u_name) as c from requirements r left outer join users u on r_assigned_u_id=u.u_id where r_p_id=".$row['p_id']." and r_assigned_u_id<>0 group by u.u_name";
 	      $rs2 = mysql_query($query2) or die(mysql_error());
	      while ($row2=mysql_fetch_array($rs2))
	       {
	    ?>
	    <tr class="light_blue">
	      <td nowrap>&nbsp;<?=htmlspecialchars($row2['u_name'])?>&nbsp;</td>
	      <td>&nbsp;<?=$row2['c']?>&nbsp;</td>
	    </tr>
	    <?}?>
	    <tr class="blue">
	      <td nowrap colspan="2">&nbsp;<?=$lng[26][6]?>&nbsp;</td>
	    </tr>
	    <?
	      $query2="select u.u_name, count(u.u_name) as c from requirements r left outer join users u on r.r_u_id=u.u_id where r_p_id=".$row['p_id']." group by u.u_name";
 	      $rs2 = mysql_query($query2) or die(mysql_error());
	      while ($row2=mysql_fetch_array($rs2))
	       {
	    ?>
	    <tr class="light_blue">
	      <td nowrap>&nbsp;<?=htmlspecialchars($row2['u_name'])?>&nbsp;</td>
	      <td>&nbsp;<?=$row2['c']?>&nbsp;</td>
	    </tr>
	    <?}?>
	    <tr class="gray">
	      <td colspan="2">&nbsp;</td>
	    </tr>
	    <?}?>
	</table> 
    </td>
  </tr>
  
</table>

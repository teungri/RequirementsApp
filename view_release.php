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
// Page: "View release" - showing release info

//check
if ($r_id=="") header("Location:index.php");

$query="select *, date_format(r_date, '%d.%m.%Y') as d1, date_format(r_released_date, '%d.%m.%Y') as d2 from releases where r_id=".$r_id;
$rs = mysql_query($query) or die(mysql_error());
if($row=mysql_fetch_array($rs)) 
 {
  $r_name=htmlspecialchars($row['r_name']);
  $r_date=$row['d1'];
  $r_released_date=$row['d2'];
  $r_global=$row['r_global'];
 }
?>
<table border="0" width="50%">
  <tr valign="top">
    <td>
        <table border="0" cellpadding="2" cellspacing="2" class="content" width="100%">
	  <tr class="gray">
	    <td colspan="2" align="center"><b><?=$lng[13][1]?></b></td>
	  </tr>
	  <tr class="blue" title="<?=$lng[13][6]?>">
	    <td align="right" width="50%">&nbsp;<?=$lng[14][3]?>&nbsp;:&nbsp;</td>
	    <td><?=$r_name?></td>
	  </tr>  
	  <tr class="light_blue" title="<?=$lng[13][8]?>">
	    <td align="right">&nbsp;<?=$lng[13][3]?>&nbsp;:&nbsp;</td>
	    <td><?=$r_date?></td>
	  </tr>  
	  <tr class="blue" valign=top title="<?=$lng[13][9]?>">
	    <td align="right">&nbsp;<?=$lng[13][4]?>&nbsp;:&nbsp;</td>
	    <td><?=$r_released_date?></td>
	  </tr>  
	  <tr class="light_blue" valign=top title="<?=$lng[13][13]?>">
	    <td align="right">&nbsp;<?=$lng[13][12]?>&nbsp;:&nbsp;</td>
	    <td><?=$r_global?"global":"local"?></td>
	  </tr>  
	  <tr class="light_blue" valign="top" title="<?=$lng[13][10]?>">
	    <td align="right">&nbsp;<?=$lng[13][11]?>&nbsp;:&nbsp;</td>
	    <td>
	    <?
	    $query2="select c.* from release_cases rc left outer join cases c on rc.rc_c_id=c.c_id where rc.rc_r_id='".$r_id."' order by c.c_name asc";
	    $rs2 = mysql_query($query2) or die(mysql_error());
	    while($row2=mysql_fetch_array($rs2))
	     {
	      echo "<a href='index.php?inc=view_case&c_id=".$row2['c_id']."'>".htmlspecialchars($row2['c_name'])."</a>";
	      echo "<br>";
	     }
	    ?>
	    </td>
	  </tr>     

	</table>
    </td> 	 
  </tr>
</table>
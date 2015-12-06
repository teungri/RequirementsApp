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
// Page: "View subproject" - showing subproject info

//check
if ($s_id=="") header("Location:index.php");

$query="select * from subprojects where s_id=".$s_id;
$rs = mysql_query($query) or die(mysql_error());
if($row=mysql_fetch_array($rs)) 
 {
  $s_name=htmlspecialchars($row['s_name']);
  $s_desc=$row['s_desc'];
  $s_p_id=htmlspecialchars($row['s_p_id']);
 }
?>
<table border="0" width="50%">
  <tr valign="top">
    <td>
        <table border="0" cellpadding="2" cellspacing="2" class="content" width="100%">
	  <tr class="gray">
	    <td colspan="2" align="center"><b><?=$lng[28][8]?></b></td>
	  </tr>
	  <tr class="blue" title="<?=$lng[28][1]?>">
	    <td align="right" width="50%">&nbsp;<?=$lng[28][1]?>&nbsp;:&nbsp;</td>
	    <td><?=$s_name?></td>
	  </tr>  
	  <tr class="light_blue" title="<?=$lng[28][2]?>">
	    <td align="right">&nbsp;<?=$lng[28][2]?>&nbsp;:&nbsp;</td>
	    <td><?=$s_desc?></td>
	  </tr>  
	  <tr class="blue" valign="top" title="<?=$lng[28][3]?>">
	    <td align="right">&nbsp;<?=$lng[28][3]?>&nbsp;:&nbsp;</td>
	    <td>
	    <?
	    $query2="select p.* from projects p where p.p_id='".$s_p_id."' order by p.p_name asc";
	    $rs2 = mysql_query($query2) or die(mysql_error());
	    while($row2=mysql_fetch_array($rs2))
	     {
	      echo "<a href='index.php?inc=view_project&p_switch=yes&project_id=".$row2['p_id']."&p_id=".$row2['p_id']."'>".htmlspecialchars($row2['p_name'])."</a>";
	      echo "<br>";
	     }
	    ?>
	    </td>
	  </tr>     
	</table>
    </td> 	 
  </tr>
</table>
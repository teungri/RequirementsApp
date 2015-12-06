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
// Page: "Manage releases" - page for managing releases for selected projects

//check if logged
if (!($_SESSION['rights']=="3" || $_SESSION['rights']=="4" || $_SESSION['rights']=="5")) header("Location:index.php");

if ($order=="") $order="r_name asc"; 
$query2="select  r.*, pr.pr_p_id, date_format(r_date, '%d.%m.%Y') as d1, date_format(r_released_date, '%d.%m.%Y') as d2 from releases r left outer join project_releases pr on r.r_id=pr.pr_r_id group by r.r_id order by ".$order;
$rs2 = mysql_query($query2) or die(mysql_error());
	    
?>
<table border="0" width="50%">
  <tr valign="top">
    <td>
      <form method="post" name="f" action="">
	<table border="0" cellpadding="2" cellspacing="2" class="content" width="100%">
	    <tr class="gray">
	      <td colspan="6" align="left"><input type="button" onclick="document.location.href='index.php?inc=edit_release'" value="<?=$lng[13][5]?>"></td>
	    </tr> 
	  <tr class="gray">
	    <td align="center" title="<?=$lng[13][6]?>"><a href="#" onclick="document.forms['f'].order.value='r_name <?if ($order=='r_name asc') echo "desc";else echo "asc";?>';document.forms['f'].submit();"><b><?=$lng[13][1]?></b></a></td>
	    <td align="center" title="<?=$lng[13][8]?>"><a href="#" onclick="document.forms['f'].order.value='r_date <?if ($order=='r_date asc') echo "desc";else echo "asc";?>';document.forms['f'].submit();"><b><?=$lng[13][3]?></b></a></td>
	    <td align="center" title="<?=$lng[13][9]?>"><a href="#" onclick="document.forms['f'].order.value='r_released_date <?if ($order=='r_released_date asc') echo "desc";else echo "asc";?>';document.forms['f'].submit();"><b><?=$lng[13][4]?></b></a></td>
	    <td align="center" title="<?=$lng[13][13]?>"><a href="#" onclick="document.forms['f'].order.value='r_global <?if ($order=='r_global asc') echo "desc";else echo "asc";?>';document.forms['f'].submit();"><b><?=$lng[13][12]?></b></a></td>
	    <td align="center" title="<?=$lng[13][7]?>"><a href="#" onclick="document.forms['f'].order.value='pr_p_id <?if ($order=='pr_p_id asc') echo "desc";else echo "asc";?>';document.forms['f'].submit();"><b><?=$lng[13][2]?></b></a></td>
	  </tr>
	   <?
	    $cnt=0;
	    while($row2=mysql_fetch_array($rs2))
	     {	     
	     ?>
	     <tr class="light_blue">
	       <td align="left"><a href="index.php?inc=edit_release&r_id=<?=$row2['r_id']?>"><?=htmlspecialchars($row2['r_name'])?></a></td>
	       <td align="left"><?=htmlspecialchars($row2['d1'])?></td>
	       <td align="left"><?=($row2['d2']=="00.00.0000")?"":$row2['d2']?></td>	
	       <td align="left"><?=($row2['r_global'])?"global":"local"?></td>	
	       <td align="left">
	       <?
	       $query="select distinct(p_name), p.* from project_releases pr left outer join projects p on pr.pr_p_id=p.p_id where p.p_id in (".$project_list.") and pr_r_id=".$row2['r_id'];
	       $rs = mysql_query($query) or die(mysql_error());
	       while($row=mysql_fetch_array($rs)) echo "<a href='index.php?inc=view_project&p_id=".$row['p_id']."'>".htmlspecialchars($row['p_name'])."</a><br>";
	       ?> 
	       </td>	   
	     </tr>  
	    <?
	     }
	    ?>
	    <tr class="gray">
	      <td colspan="6" align="left"><input type="button" onclick="document.location.href='index.php?inc=edit_release'" value="<?=$lng[13][5]?>"></td>
	    </tr> 
	 </table>
      <input type="hidden" name="inc" value="manage_releases">
      <input type="hidden" name="order" value="">
      </form>	
    </td> 	 
  </tr>
</table>
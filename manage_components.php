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
// Page: "Manage components" - page for managing components for selected projects

//check if logged
if (!($_SESSION['rights']=="1" || $_SESSION['rights']=="2" || $_SESSION['rights']=="3" || $_SESSION['rights']=="4" || $_SESSION['rights']=="5")) header("Location:index.php");

if ($order=="") $order="c_name asc"; 
$query2="select  c.*, pco.pco_p_id from components c left outer join project_components pco on c.c_id=pco.pco_c_id group by c.c_id order by ".$order;
$rs2 = mysql_query($query2) or die(mysql_error());
	    
?>
<table border="0" width="50%">
  <tr valign="top">
    <td>
      <form method="post" name="f" action="">
	<table border="0" cellpadding="2" cellspacing="2" class="content" width="100%">
	    <tr class="gray">
	      <td colspan="6" align="left"><input type="button" onclick="document.location.href='index.php?inc=edit_component'" value="<?=$lng[36][3]?>"></td>
	    </tr> 
	  <tr class="gray">
	    <td align="center" title="<?=$lng[36][4]?>"><a href="#" onclick="document.forms['f'].order.value='c_name <?if ($order=='c_name asc') echo "desc";else echo "asc";?>';document.forms['f'].submit();"><b><?=$lng[36][1]?></b></a></td>
	    <td align="center" title="<?=$lng[13][13]?>"><a href="#" onclick="document.forms['f'].order.value='c_global <?if ($order=='c_global asc') echo "desc";else echo "asc";?>';document.forms['f'].submit();"><b><?=$lng[13][12]?></b></a></td>
	    <td align="center" title="<?=$lng[36][5]?>"><a href="#" onclick="document.forms['f'].order.value='pco_p_id <?if ($order=='pco_p_id asc') echo "desc";else echo "asc";?>';document.forms['f'].submit();"><b><?=$lng[36][2]?></b></a></td>
	  </tr>
	   <?
	    $cnt=0;
	    while($row2=mysql_fetch_array($rs2))
	     {	     
	     ?>
	     <tr class="light_blue">
	       <td align="left"><a href="index.php?inc=edit_component&c_id=<?=$row2['c_id']?>"><?=htmlspecialchars($row2['c_name'])?></a></td>
	       <td align="left"><?=($row2['c_global'])?"global":"local"?></td>	
	        <td align="left">
	       <?
	       //$query="select p.* from project_components pco left outer join projects p on pco.pco_p_id=p.p_id where p.p_id in (".$project_list.") and pco_c_id=".$row2['c_id'];
	       $query="select p.* from project_components pco left outer join projects p on pco.pco_p_id=p.p_id where pco_c_id=".$row2['c_id'];
	       $rs = mysql_query($query) or die(mysql_error());
	       while($row=mysql_fetch_array($rs)) echo "<a href='index.php?inc=view_project&p_switch=yes&project_id=".$row['p_id']."&p_id=".$row['p_id']."'>".htmlspecialchars($row['p_name'])."</a><br>";
	       ?> 
	       </td>	   
	     </tr>  
	    <?
	     }
	    ?>
	    <tr class="gray">
	      <td colspan="6" align="left"><input type="button" onclick="document.location.href='index.php?inc=edit_component'" value="<?=$lng[36][3]?>"></td>
	    </tr> 
	 </table>
      <input type="hidden" name="inc" value="manage_components">
      <input type="hidden" name="order" value="">
      </form>	
    </td> 	 
  </tr>
</table>
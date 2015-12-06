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
// Page: "Manage subprojects" - page for managing subprojects for admin users

//check if logged
if ($_SESSION['rights']!="5") header("Location:index.php");

if ($order=="") $order="s.s_name asc"; 
//$query="select s.*, p.p_id, p.p_name from subprojects s left outer join projects p on s.s_p_id=p.p_id and p.p_id in (".$project_list.") order by ".$order;
$query="select s.*, p.p_id, p.p_name from subprojects s left outer join projects p on s.s_p_id=p.p_id order by ".$order;
$rs = mysql_query($query) or die(mysql_error());
?>
<table border="0" width="100%">
  <tr valign="top">
    <td>
      <form method="post" name="f" action="">
	<table border="0" cellpadding="2" cellspacing="2" class="content" width="100%">
	  <tr class="gray">
	    <td colspan="9" align="left"><input type="button" onclick="document.location.href='index.php?inc=edit_subproject'" value="<?=$lng[28][4]?>"></td>
	  </tr>  	    
	  <tr class="gray">
	    <td align="center" title="<?=$lng[28][5]?>"><a href="#" onclick="document.forms['f'].order.value='s.s_name <?if ($order=='s.s_name asc') echo "desc";else echo "asc";?>';document.forms['f'].submit();"><b><?=$lng[28][1]?></b></a></td>
	    <td align="center" title="<?=$lng[28][6]?>"><a href="#" onclick="document.forms['f'].order.value='s.s_desc <?if ($order=='s.s_desc asc') echo "desc";else echo "asc";?>';document.forms['f'].submit();"><b><?=$lng[28][2]?></b></a></td>
	    <td align="center" title="<?=$lng[28][7]?>"><a href="#" onclick="document.forms['f'].order.value='p.p_name <?if ($order=='p.p_name asc') echo "desc";else echo "asc";?>';document.forms['f'].submit();"><b><?=$lng[28][3]?></b></a></td>
	  </tr>
	  <?
	  $cnt=0;
	  while($row=mysql_fetch_array($rs))
	   {
	  ?>
	  <tr class="<?if ($cnt) {echo "light_";$cnt=0;}else $cnt=1;?>blue">
	    <td align="left"><a href="index.php?inc=edit_subproject&s_id=<?=$row['s_id']?>"><?=htmlspecialchars($row['s_name'])?></a></td>
	    <td align="left"><?=$row['s_desc']?></td>	    
	    <td align="left"><a href="index.php?inc=view_project&p_switch=yes&project_id=<?=$row['p_id']?>&p_id=<?=$row['p_id']?>"><?=htmlspecialchars($row['p_name'])?></a></td>
	  </tr>  
	  <?
	   }
	  ?>
	  <tr class="gray">
	    <td colspan="9" align="left"><input type="button" onclick="document.location.href='index.php?inc=edit_subproject'" value="<?=$lng[28][4]?>"></td>
	  </tr>  	    
	</table>
      <input type="hidden" name="inc" value="manage_subprojects">
      <input type="hidden" name="order" value="">
      </form>	
    </td> 	 
  </tr>
</table>
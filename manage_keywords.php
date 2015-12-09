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
// Page: "Manage keywords" - page for managing keywords

//check if logged
if (!($_SESSION['rights']=="1" || $_SESSION['rights']=="2" || $_SESSION['rights']=="3" || $_SESSION['rights']=="4" || $_SESSION['rights']=="5")) header("Location:index.php");

if ( ! isset($order) || $order=="") $order="k_name asc"; 
$query2="select * from keywords order by ".$order;
$rs2 = mysql_query($query2) or die(mysql_error());
	    
?>
<table border="0" width="50%">
  <tr valign="top">
    <td>
      <form method="post" name="f" action="">
	<table border="0" cellpadding="2" cellspacing="2" class="content" width="100%">
	    <tr class="gray">
	      <td colspan="6" align="left"><input type="button" onclick="document.location.href='index.php?inc=edit_keyword'" value="<?=$lng[32][4]?>"></td>
	    </tr> 
	  <tr class="gray">
	    <td align="center" title="<?=$lng[32][3]?>"><a href="#" onclick="document.forms['f'].order.value='k_name <?if ($order=='k_name asc') echo "desc";else echo "asc";?>';document.forms['f'].submit();"><b><?=$lng[32][2]?></b></a></td>
	    <td align="center" title="<?=$lng[13][13]?>"><a href="#" onclick="document.forms['f'].order.value='k_global <?if ($order=='k_global asc') echo "desc";else echo "asc";?>';document.forms['f'].submit();"><b><?=$lng[13][12]?></b></a></td>
	  </tr>
	   <?
	    $cnt=0;
	    while($row2=mysql_fetch_array($rs2))
	     {	     
	     ?>
	     <tr class="light_blue">
	       <td align="left"><a href="index.php?inc=edit_keyword&k_id=<?=$row2['k_id']?>"><?=htmlspecialchars($row2['k_name'])?></a></td>
	       <td align="left"><?=($row2['k_global'])?"global":"local"?></td>	
	     </tr>  
	    <?
	     }
	    ?>
	    <tr class="gray">
	      <td colspan="6" align="left"><input type="button" onclick="document.location.href='index.php?inc=edit_keyword'" value="<?=$lng[32][4]?>"></td>
	    </tr> 
	 </table>
      <input type="hidden" name="inc" value="manage_keywords">
      <input type="hidden" name="order" value="">
      </form>	
    </td> 	 
  </tr>
</table>
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
// Page: "Manage cases" - page for managing test cases 

//check if logged
if (!($_SESSION['rights']=="1" || $_SESSION['rights']=="2" || $_SESSION['rights']=="3" || $_SESSION['rights']=="4" || $_SESSION['rights']=="5")) header("Location:index.php");

if ( !isset($order) || $order=="") $order="c_name asc"; 
$query2="select  * from cases order by ".$order;
$rs2 = mysql_query($query2) or die(mysql_error());
	    
?>
<table border="0" width="50%">
  <tr valign="top">
    <td>
      <form method="post" name="f" action="">
	<table border="0" cellpadding="2" cellspacing="2" class="content" width="100%">
	    <tr class="gray">
	      <td colspan="<?=($c_view)?7:5?>" align="left"><input type="button" onclick="document.location.href='index.php?inc=edit_case'" value="<?=$lng[29][10]?>"></td>
	    </tr> 
	  <tr class="gray">
	    <td align="center"><a href="#" onclick="document.forms['f'].order.value='c_id <?if ($order=='c_id asc') echo "desc";else echo "asc";?>';document.forms['f'].submit();"><b><?=$lng[29][1]?></b></a></td>
	    <td align="center" title="<?=$lng[29][6]?>"><a href="#" onclick="document.forms['f'].order.value='c_name <?if ($order=='c_name asc') echo "desc";else echo "asc";?>';document.forms['f'].submit();"><b><?=$lng[29][2]?></b></a></td>
	    <?if ( ! isset($c_view) ) $c_view = 0; if ($c_view) {?>
	    <td align="center" title="<?=$lng[29][7]?>"><a href="#" onclick="document.forms['f'].order.value='c_desc <?if ($order=='c_desc asc') echo "desc";else echo "asc";?>';document.forms['f'].submit();"><b><?=$lng[29][3]?></b></a></td>
	    <td align="center" title="<?=$lng[29][8]?>"><a href="#" onclick="document.forms['f'].order.value='c_result <?if ($order=='c_result asc') echo "desc";else echo "asc";?>';document.forms['f'].submit();"><b><?=$lng[29][4]?></b></a></td>
	    <?}?>
	    <td align="center" title="<?=$lng[29][9]?>"><a href="#" onclick="document.forms['f'].order.value='c_status <?if ($order=='c_status asc') echo "desc";else echo "asc";?>';document.forms['f'].submit();"><b><?=$lng[29][5]?></b></a></td>
	    <td align="center" title="<?=$lng[13][13]?>"><a href="#" onclick="document.forms['f'].order.value='c_global <?if ($order=='c_global asc') echo "desc";else echo "asc";?>';document.forms['f'].submit();"><b><?=$lng[13][12]?></b></a></td>
	    <td align="center"><input type="button" value="&nbsp;<?=($c_view)?$lng[29][13]:$lng[29][14]?>&nbsp;" onclick="document.location.href='index.php?inc=manage_cases&c_view=<?=($c_view)?0:1?>'"></td>
	  </tr>
	   <?
	    $cnt=0;
	    while($row2=mysql_fetch_array($rs2))
	     {	     
	     ?>
	     <tr class="light_blue">
	       <td align="left"><a href="index.php?inc=edit_case&c_id=<?=$row2['c_id']?>"><?=htmlspecialchars($row2['c_id'])?></a></td>
	       <td align="left"><a href="index.php?inc=edit_case&c_id=<?=$row2['c_id']?>"><?=htmlspecialchars($row2['c_name'])?></a></td>
	       <?if ($c_view) {?>
               <td align="left"><?=$row2['c_desc']?></td>
	       <td align="left"><?=$row2['c_result']?></td>	
	       <?}?>
	       <td align="left"><?=($row2['c_status']==1)?"active":"not active"?></td>	   
	       <td align="left"><?=($row2['c_global'])?"global":"local"?></td>	
	       <td align="center"><input type="button" value="&nbsp;<?=$lng[29][11]?>&nbsp;" onclick="document.location.href='index.php?inc=edit_case&c_id=<?=$row2['c_id']?>'">&nbsp;&nbsp;<input type="button" value="<?=$lng[29][12]?>" onclick="document.location.href='index.php?inc=view_case&c_id=<?=$row2['c_id']?>'">&nbsp;&nbsp;<input type="button" onclick="window.open('pdf_case.php?c_id=<?=$row2['c_id']?>', 'pdf','menubar=yes,status=yes');" value="<?=$lng[31][20]?>"></td>	   
	     </tr>  
	    <?
	     }
	    ?>
	    <tr class="gray">
	      <td colspan="<?=($c_view)?6:4?>" align="left"><input type="button" onclick="document.location.href='index.php?inc=edit_case'" value="<?=$lng[29][10]?>"></td>
	    </tr> 
	 </table>
      <input type="hidden" name="inc" value="manage_cases">
      <input type="hidden" name="order" value="">
      </form>	
    </td> 	 
  </tr>
</table>
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
// Page: "Manage glossary" - page for managing glossary for selected projects

//check if logged
if (!($_SESSION['rights']=="5")) header("Location:index.php");

if ( ! isset($order) || $order=="") $order="g_id asc"; 
$query2="select g.*, pg.pg_p_id from glossary g left outer join project_glossary pg on g.g_id=pg.pg_g_id group by g.g_id order by ".$order;
$rs2 = mysql_query($query2) or die(mysql_error());
	    
?>
<table border="0" width="50%">
  <tr valign="top">
    <td>
      <form method="post" name="f" action="">
	<table border="0" cellpadding="2" cellspacing="2" class="content" width="100%">
	    <tr class="gray">
	      <td colspan="6" align="left"><input type="button" onclick="document.location.href='index.php?inc=edit_glossary'" value="<?=$lng[24][5]?>"></td>
	    </tr> 
	  <tr class="gray">
	    <td align="center" title="<?=$lng[24][6]?>"><a href="#" onclick="document.forms['f'].order.value='g_id <?if ($order=='g_id asc') echo "desc";else echo "asc";?>';document.forms['f'].submit();"><b><?=$lng[24][1]?></b></a></td>
	    <td align="center" title="<?=$lng[24][8]?>"><a href="#" onclick="document.forms['f'].order.value='g_term <?if ($order=='g_term asc') echo "desc";else echo "asc";?>';document.forms['f'].submit();"><b><?=$lng[24][3]?></b></a></td>
	    <td align="center" title="<?=$lng[24][9]?>"><a href="#" onclick="document.forms['f'].order.value='g_abbreviation <?if ($order=='g_abbreviation asc') echo "desc";else echo "asc";?>';document.forms['f'].submit();"><b><?=$lng[24][4]?></b></a></td>
	    <td align="center" title="<?=$lng[13][13]?>"><a href="#" onclick="document.forms['f'].order.value='g_global <?if ($order=='g_global asc') echo "desc";else echo "asc";?>';document.forms['f'].submit();"><b><?=$lng[13][12]?></b></a></td>
	    <td align="center" title="<?=$lng[24][7]?>"><a href="#" onclick="document.forms['f'].order.value='pg_p_id <?if ($order=='pg_p_id asc') echo "desc";else echo "asc";?>';document.forms['f'].submit();"><b><?=$lng[24][2]?></b></a></td>
	  </tr>
	   <?
	    $cnt=0;
	    while($row2=mysql_fetch_array($rs2))
	     {	     
	     ?>
	     <tr class="light_blue">
	       <td align="left"><a href="index.php?inc=edit_glossary&g_id=<?=$row2['g_id']?>"><?for ($i=0;$i<6-strlen($row2['g_id']);$i++) echo "0";echo $row2['g_id'];?></a></td>
	       <td align="left"><?=htmlspecialchars($row2['g_term'])?></td>
	       <td align="left"><?=htmlspecialchars($row2['g_abbreviation'])?></td>	
	       <td align="left"><?=($row2['g_global'])?"global":"local"?></td>	
	       <td align="left">
	       <?
	       //$query="select p.* from project_glossary pg left outer join projects p on pg.pg_p_id=p.p_id where p.p_id in (".$project_list.") and pg_g_id=".$row2['g_id'];
	       $query="select p.* from project_glossary pg left outer join projects p on pg.pg_p_id=p.p_id where pg_g_id=".$row2['g_id'];
	       $rs = mysql_query($query) or die(mysql_error());
	       while($row=mysql_fetch_array($rs)) echo "<a href='index.php?inc=view_project&p_switch=yes&project_id=".$row['p_id']."&p_id=".$row['p_id']."'>".htmlspecialchars($row['p_name'])."</a><br>";
	       ?> 
	       </td>	   
	     </tr>  
	    <?
	     }
	    ?>
	    <tr class="gray">
	      <td colspan="6" align="left"><input type="button" onclick="document.location.href='index.php?inc=edit_glossary'" value="<?=$lng[24][5]?>"></td>
	    </tr> 
	 </table>
      <input type="hidden" name="inc" value="manage_glossary">
      <input type="hidden" name="order" value="">
      </form>	
    </td> 	 
  </tr>
</table>
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
// Page: "View case" - showing case info

//check
if ($c_id=="") header("Location:index.php");

$query="select * from cases where c_id=".$c_id;
$rs = mysql_query($query) or die(mysql_error());
if($row=mysql_fetch_array($rs)) 
 {
  $c_name=htmlspecialchars($row['c_name']);
  $c_desc=$row['c_desc'];
  $c_result=$row['c_result'];
  $c_status=htmlspecialchars($row['c_status']);
  $c_global=htmlspecialchars($row['c_global']);
 }
?>
<table border="0" width="50%">
  <tr valign="top">
    <td>
        <table border="0" cellpadding="2" cellspacing="2" class="content" width="100%">
	  <tr class="gray">
	    <td colspan="2" align="center"><b><?=$lng[31][1]?></b></td>
	  </tr>
	  <tr class="blue" title="<?=$lng[31][9]?>">
	    <td align="right" width="50%">&nbsp;<?=$lng[31][2]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;<?=$c_name?></td>
	  </tr>  
	  <tr class="light_blue" title="<?=$lng[31][10]?>">
	    <td align="right" width="50%">&nbsp;<?=$lng[31][3]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;<?=$c_desc?></td>
	  </tr>  
	  <tr class="blue" title="<?=$lng[31][11]?>">
	    <td align="right" width="50%">&nbsp;<?=$lng[31][4]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;<?=$c_result?></td>
	  </tr>  
	  <tr class="light_blue" title="<?=$lng[31][12]?>">
	    <td align="right">&nbsp;<?=$lng[31][5]?>&nbsp;:&nbsp;</td>
	    <td>
	    <?
	      if ($c_status==0) echo $lng[31][6];
	      elseif ($c_status==1) echo $lng[31][7];
	    ?>  
	    </td>
	  </tr>  
	  <tr class="light_blue" valign=top title="<?=$lng[13][13]?>">
	    <td align="right">&nbsp;<?=$lng[13][12]?>&nbsp;:&nbsp;</td>
	    <td><?=$c_global?"global":"local"?></td>
	  </tr>  
	  <tr class="blue" valign="top" title="<?=$lng[31][17]?>">
	    <td align="right">&nbsp;<?=$lng[31][14]?>&nbsp;:&nbsp;</td>
	    <td>
	    <?
	    $query2="select p.* from projects p left outer join project_cases pc on p.p_id=pc.pc_p_id where pc.pc_c_id='".$c_id."' order by p.p_name asc";
	    $rs2 = mysql_query($query2) or die(mysql_error());
	    while($row2=mysql_fetch_array($rs2))
	     {
	      echo "<a href='index.php?inc=view_project&p_switch=yes&project_id=".$row2['p_id']."&p_id=".$row2['p_id']."'>".htmlspecialchars($row2['p_name'])."</a>";
	      echo "<br>";
	     }
	    ?>
	    </td>
	  </tr>     
	  <tr class="light_blue" valign="top" title="<?=$lng[31][18]?>">
	    <td align="right">&nbsp;<?=$lng[31][15]?>&nbsp;:&nbsp;</td>
	    <td>
	    <?
	    $query2="select r.* from releases r left outer join release_cases rc on r.r_id=rc.rc_r_id where rc.rc_c_id='".$c_id."' order by r.r_name asc";
	    $rs2 = mysql_query($query2) or die(mysql_error());
	    while($row2=mysql_fetch_array($rs2))
	     {
	      echo "<a href='index.php?inc=view_release&r_id=".$row2['r_id']."'>".htmlspecialchars($row2['r_name'])."</a>";
	      echo "<br>";
	     }
	    ?>
	    </td>
	  </tr>     
	  <tr class="blue" valign="top" title="<?=$lng[31][19]?>">
	    <td align="right">&nbsp;<?=$lng[31][16]?>&nbsp;:&nbsp;</td>
	    <td>
	    <?
	    $query2="select * from requirements where CONCAT(',',r_c_id) like ('%,".$c_id.",%') order by r_name asc";
	    $rs2 = mysql_query($query2) or die(mysql_error());
	    while($row2=mysql_fetch_array($rs2))
	     {
	      echo "<a href='index.php?inc=view_requirement&p_switch=yes&project_id=".$row2['r_p_id']."&p_id=".$row2['r_p_id']."&r_id=".$row2['r_id']."'>".htmlspecialchars($row2['r_name'])."</a>";
	      echo "<br>";
	     }
	    ?>
	    </td>
	  </tr>     
	  <tr class="light_blue">
	    <td align="center" colspan="2"><input type="button" onclick="window.open('pdf_case.php?c_id=<?=$c_id?>', 'pdf','menubar=yes,status=yes');" value="<?=$lng[31][20]?>"></td>
	  </tr>  
	</table>
    </td> 	 
  </tr>
</table>
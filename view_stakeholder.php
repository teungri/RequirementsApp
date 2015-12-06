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
// Page: "View stakeholder" - showing stakeholder info

//check
if ($s_id=="") header("Location:index.php");

$query="select * from stakeholders where s_id=".$s_id;
$rs = mysql_query($query) or die(mysql_error());
if($row=mysql_fetch_array($rs)) 
 {
  $s_name=htmlspecialchars($row['s_name']);
  $s_function=htmlspecialchars($row['s_function']);
  $s_email=htmlspecialchars($row['s_email']);
  $s_interests=nl2br(htmlspecialchars($row['s_interests']));
  $s_global=htmlspecialchars($row['s_global']);
 }
?>
<table border="0" width="50%">
  <tr valign="top">
    <td>
        <table border="0" cellpadding="2" cellspacing="2" class="content" width="100%">
	  <tr class="gray">
	    <td colspan="2" align="center"><b><?=$lng[22][1]?></b></td>
	  </tr>
	  <tr class="blue" title="<?=$lng[23][13]?>">
	    <td align="right" width="50%">&nbsp;<?=$lng[23][3]?>&nbsp;:&nbsp;</td>
	    <td><?=$s_name?></td>
	  </tr>  
	  <tr class="light_blue" title="<?=$lng[23][14]?>">
	    <td align="right">&nbsp;<?=$lng[23][5]?>&nbsp;:&nbsp;</td>
	    <td><?=$s_function?></td>
	  </tr>  
	  <tr class="blue" valign=top title="<?=$lng[23][15]?>">
	    <td align="right">&nbsp;<?=$lng[23][6]?>&nbsp;:&nbsp;</td>
	    <td><?=$s_email?></td>
	  </tr>  
	  <tr class="light_blue" valign=top title="<?=$lng[23][16]?>">
	    <td align="right">&nbsp;<?=$lng[23][12]?>&nbsp;:&nbsp;</td>
	    <td><?=$s_interests?></td>
	  </tr>  
	  <tr class="light_blue" valign=top title="<?=$lng[13][13]?>">
	    <td align="right">&nbsp;<?=$lng[13][12]?>&nbsp;:&nbsp;</td>
	    <td><?=$s_global?"global":"local"?></td>
	  </tr>  
	</table>
    </td> 	 
  </tr>
</table>
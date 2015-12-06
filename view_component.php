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
// Page: "View component" - showing component info

//check
if ($c_id=="") header("Location:index.php");

$query="select * from components where c_id=".$c_id;
$rs = mysql_query($query) or die(mysql_error());
if($row=mysql_fetch_array($rs)) 
 {
  $c_name=htmlspecialchars($row['c_name']);
  $c_global=htmlspecialchars($row['c_global']);
 }
?>
<table border="0" width="50%">
  <tr valign="top">
    <td>
        <table border="0" cellpadding="2" cellspacing="2" class="content" width="100%">
	  <tr class="gray">
	    <td colspan="2" align="center"><b><?=$lng[36][1]?></b></td>
	  </tr>
	  <tr class="blue" title="<?=$lng[37][13]?>">
	    <td align="right" width="50%">&nbsp;<?=$lng[37][3]?>&nbsp;:&nbsp;</td>
	    <td><?=$c_name?></td>
	  </tr> 
	  <tr class="light_blue" valign=top title="<?=$lng[13][13]?>">
	    <td align="right">&nbsp;<?=$lng[13][12]?>&nbsp;:&nbsp;</td>
	    <td><?=$c_global?"global":"local"?></td>
	  </tr>  
	</table>
    </td> 	 
  </tr>
</table>
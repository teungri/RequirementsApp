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
// Page: "Manage users" - page for managing users (for admin users)

//check if logged

if ($_SESSION['rights']!="5") header("Location:index.php");

if ($order=="") $order="u_name asc"; 
$query="select * from users order by ".$order;
$rs = mysql_query($query) or die(mysql_error());
?>
<table border="0" width="100%">
  <tr valign="top">
    <td>
      <form method="post" name="f" action="">
	<table border="0" cellpadding="2" cellspacing="2" class="content" width="100%">
	  <tr class="gray">
	    <td colspan="4" align="left"><input type="button" onclick="document.location.href='index.php?inc=edit_user'" value="<?=$lng[11][5]?>"></td>
	  </tr>  	    
	  <tr class="gray">
	    <td align="center"><a href="#" onclick="document.forms['f'].order.value='u_name <?if ($order=='u_name asc') echo "desc";else echo "asc";?>';document.forms['f'].submit();"><b><?=$lng[11][1]?></b></a></td>
	    <td align="center"><a href="#" onclick="document.forms['f'].order.value='u_username <?if ($order=='u_username asc') echo "desc";else echo "asc";?>';document.forms['f'].submit();"><b><?=$lng[11][2]?></b></a></td>
	    <td align="center"><a href="#" onclick="document.forms['f'].order.value='u_email <?if ($order=='u_email asc') echo "desc";else echo "asc";?>';document.forms['f'].submit();"><b><?=$lng[11][3]?></b></a></td>
	    <td align="center"><a href="#" onclick="document.forms['f'].order.value='u_rights <?if ($order=='u_rights asc') echo "desc";else echo "asc";?>';document.forms['f'].submit();"><b><?=$lng[11][4]?></b></a></td>
	  </tr>
	  <?
	  $cnt=0;
	  while($row=mysql_fetch_array($rs))
	   {
	    //phase
	    switch($row['u_rights'])
	     {
	      case 0:$u_rights=$lng[2][25];break;
              case 1:$u_rights=$lng[2][14];break;
              case 2:$u_rights=$lng[2][15];break;
              case 3:$u_rights=$lng[2][16];break;
              case 4:$u_rights=$lng[2][17];break;
              case 5:$u_rights=$lng[2][18];break;
              default:$u_rights=$lng[2][14];
	     }
	  ?>
	  <tr class="<?if ($cnt) {echo "light_";$cnt=0;}else $cnt=1;?>blue">
	    <td align="left"><a href="index.php?inc=edit_user&u_id=<?=$row['u_id']?>"><?=htmlspecialchars($row['u_name'])?></a></td>
	    <td align="left"><?=$row['u_username']?></td>
	    <td align="left"><?=$row['u_email']?></td>
	    <td align="left"><?=$u_rights?></td>
	  </tr>  
	  <?
	   }
	  ?>
	  <tr class="gray">
	    <td colspan="4" align="left"><input type="button" onclick="document.location.href='index.php?inc=edit_user'" value="<?=$lng[11][5]?>"></td>
	  </tr>  	    

	</table>
      <input type="hidden" name="inc" value="manage_users">
      <input type="hidden" name="order" value="">
      </form>	
    </td> 	 
  </tr>
</table>
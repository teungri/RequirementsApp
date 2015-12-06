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
// Page: "Manage reviews" - page for managing reviews for selected projects

//check if logged
//if (!($_SESSION['rights']=="2" || $_SESSION['rights']=="3" || $_SESSION['rights']=="4" || $_SESSION['rights']=="5")) header("Location:index.php");

if ($order=="") $order="r_name asc"; 
$query2="select *, date_format(r.r_date, '%d.%m.%Y') as d1, p.p_name from reviews r left outer join projects p on r.r_p_id=p.p_id order by ".$order;
$rs2 = mysql_query($query2) or die(mysql_error());
	    
?>
<table border="0" width="50%">
  <tr valign="top">
    <td>
      <form method="post" name="f" action="">
	<table border="0" cellpadding="2" cellspacing="2" class="content" width="100%">
	  <tr class="gray">
	    <td align="center" title="<?=$lng[38][4]?>"><a href="#" onclick="document.forms['f'].order.value='r_name <?if ($order=='r_name asc') echo "desc";else echo "asc";?>';document.forms['f'].submit();"><b><?=$lng[38][1]?></b></a></td>
	    <td align="center" title="<?=$lng[38][7]?>"><a href="#" onclick="document.forms['f'].order.value='r_date <?if ($order=='r_date asc') echo "desc";else echo "asc";?>';document.forms['f'].submit();"><b><?=$lng[38][6]?></b></a></td>
	    <td align="center" title="<?=$lng[38][9]?>"><a href="#" onclick="document.forms['f'].order.value='r_status <?if ($order=='r_status asc') echo "desc";else echo "asc";?>';document.forms['f'].submit();"><b><?=$lng[38][8]?></b></a></td>
	    <td align="center" title="<?=$lng[38][5]?>"><a href="#" onclick="document.forms['f'].order.value='p_name <?if ($order=='p_name asc') echo "desc";else echo "asc";?>';document.forms['f'].submit();"><b><?=$lng[38][2]?></b></a></td></td>
	    <td align="center">&nbsp;	    
	      <?if ($_SESSION['rights']=="2" || $_SESSION['rights']=="3" || $_SESSION['rights']=="4" || $_SESSION['rights']=="5") {?>
              <input type="button" onclick="document.location.href='index.php?inc=edit_review'" value="<?=$lng[38][3]?>">	    
              <?}?> 
	    </td>
	  </tr>
	   <?
	    $cnt=0;$cnt2=0;
	    while($row2=mysql_fetch_array($rs2))
	     {	
	      $query="select * from review_users where ru_r_id='".$row2['r_id']."' and ru_u_id='".$_SESSION['uid']."'";
	      $rs = mysql_query($query) or die(mysql_error());
	      if($row=mysql_fetch_array($rs) || $_SESSION['rights']=="5") 
	       {    
	        $cnt2++;
	     ?>
	     <tr class="light_blue">
	       <td align="left"><a href="index.php?inc=view_review&r_id=<?=$row2['r_id']?>"><?=htmlspecialchars($row2['r_name'])?></a></td>
	       <td align="left"><?=htmlspecialchars($row2['d1'])?></td>
	       <td align="left">
	         <?
	           include("ini/txts/".$_SESSION['chlang']."/review_status.php");
	           echo $review_status_array[$row2['r_status']];
	         ?>   
	       </td>	
	       <td align="left"><a href='index.php?inc=view_project&p_switch=yes&project_id=<?=$row2['r_p_id']?>&p_id=<?=$row2['r_p_id']?>'><?=htmlspecialchars($row2['p_name'])?></a></td>
	       <td align="center"><?if ($_SESSION['rights']=="2" || $_SESSION['rights']=="3" || $_SESSION['rights']=="4" || $_SESSION['rights']=="5") {?><input type="button" onclick="document.location.href='index.php?inc=edit_review&r_id=<?=$row2['r_id']?>'" value="<?=$lng[38][11]?>">&nbsp;<?}?><input type="button" onclick="document.location.href='index.php?inc=view_review&r_id=<?=$row2['r_id']?>'" value="<?=$lng[38][12]?>"></td>
	     </tr>  
	    <? 
	       }
	     }
	    if ($cnt2==0) 
	     {
	      ?>
	  <tr class="gray">
	    <td align="center" colspan="5"><br/><?=$lng[38][10]?><br/></td>
	  </tr>
	      
	      <?
	     }
	    ?>
  	 </table>
      <input type="hidden" name="inc" value="manage_reviews">
      <input type="hidden" name="order" value="">
      </form>	
    </td> 	 
  </tr>
</table>
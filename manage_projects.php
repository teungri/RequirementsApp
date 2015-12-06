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
// Page: "Manage projects" - page for managing projects for admin users

//check if logged
if ($_SESSION['rights']!="5") header("Location:index.php");

if ($order=="") $order="p_name asc"; 
if ($show_r=="1") $query="select p.*, date_format(p_date, '%d.%m.%Y') as d1, u_name, u_id from projects p left outer join users u on p.p_leader=u.u_id order by ".$order;
else $query="select p.*, date_format(p_date, '%d.%m.%Y') as d1, u_name, u_id from projects p left outer join users u on p.p_leader=u.u_id where p_status<>2 order by ".$order;
$rs = mysql_query($query) or die(mysql_error());
?>
<table border="0" width="100%">
  <tr valign="top">
    <td>
      <form method="post" name="f" action="">
	<table border="0" cellpadding="2" cellspacing="2" class="content" width="100%">
	  <tr class="gray">
	    <td align="left"><input type="checkbox" onclick="document.forms['f'].submit();" name="show_r" value="1" <?if ($show_r=="1") echo "checked";?>> <?=$lng[9][34]?></td>
	    <td colspan="9" align="left"><input type="button" onclick="document.location.href='index.php?inc=edit_project'" value="<?=$lng[9][7]?>"></td>
	  </tr>  	    
	  <tr class="gray">
	    <td align="center" title="<?=$lng[9][16]?>"><a href="#" onclick="document.forms['f'].order.value='p_name <?if ($order=='p_name asc') echo "desc";else echo "asc";?>';document.forms['f'].submit();"><b><?=$lng[9][1]?></b></a></td>
	    <td align="center" title="<?=$lng[9][17]?>"><a href="#" onclick="document.forms['f'].order.value='p_phase <?if ($order=='p_phase asc') echo "desc";else echo "asc";?>';document.forms['f'].submit();"><b><?=$lng[9][2]?></b></a></td>
	    <td align="center" title="<?=$lng[9][18]?>"><a href="#" onclick="document.forms['f'].order.value='p_status <?if ($order=='p_status asc') echo "desc";else echo "asc";?>';document.forms['f'].submit();"><b><?=$lng[9][3]?></b></a></td>
	    <td align="center" title="<?=$lng[9][19]?>"><a href="#" onclick="document.forms['f'].order.value='u_name <?if ($order=='u_name asc') echo "desc";else echo "asc";?>';document.forms['f'].submit();"><b><?=$lng[9][4]?></b></a></td>
	    <td align="center" title="<?=$lng[9][20]?>"><a href="#" onclick="document.forms['f'].order.value='p_date <?if ($order=='p_date asc') echo "desc";else echo "asc";?>';document.forms['f'].submit();"><b><?=$lng[9][5]?></b></a></td>
	    <td align="center" title="<?=$lng[9][21]?>"><a href="#" onclick="document.forms['f'].order.value='p_desc <?if ($order=='p_desc asc') echo "desc";else echo "asc";?>';document.forms['f'].submit();"><b><?=$lng[9][6]?></b></a></td>
	    <td align="center" title="<?=$lng[9][29]?>"><a href="#" onclick="document.forms['f'].order.value='p_template <?if ($order=='p_template asc') echo "desc";else echo "asc";?>';document.forms['f'].submit();"><b><?=$lng[9][28]?></b></a></td>
	    <td align="center"><b><?=$lng[9][13]?></b></td>
	    <td align="center"><b><?=$lng[9][37]?></b></td>
	    <td align="center">&nbsp;</td>
	  </tr>
	  <?
	  $cnt=0;
	  while($row=mysql_fetch_array($rs))
	   {
	    //phase
	    switch($row['p_phase'])
	     {
	      case "0":$p_phase=$lng[9][8];break;
	      case "1":$p_phase=$lng[9][9];break;
	      case "2":$p_phase=$lng[9][10];break;
	      case "3":$p_phase=$lng[9][32];break;
	      case "4":$p_phase=$lng[9][33];break;
	      default:$p_phase=$lng[9][8];     
	     }
	    
	    //status
	    switch($row['p_status'])
	     {
	      case "0":$p_status=$lng[9][11];break;
	      case "1":$p_status=$lng[9][12];break;
	      case "2":$p_status=$lng[9][14];break;
	      default:$p_status=$lng[9][11];     
	     } 
	     
	  ?>
	  <tr class="<?if ($cnt) {echo "light_";$cnt=0;}else $cnt=1;?>blue">
	    <td align="left"><a href="index.php?inc=edit_project&p_id=<?=$row['p_id']?>"><?=htmlspecialchars($row['p_name'])?></a></td>
	    <td align="left"><?=$p_phase?></td>
	    <td align="left"><?=$p_status?></td>
	    <td align="left"><a href="index.php?inc=edit_user&u_id=<?=$row['u_id']?>"><?=htmlspecialchars($row['u_name'])?></a></td>
	    <td align="left"><?=htmlspecialchars($row['d1'])?></td>
	    <td align="left"><?=$row['p_desc']?></td>	    
	    <td align="left">&nbsp;<?=($row['p_template']==1)?"yes":"no"?></td>	    
	    <td align="left">
	    <?
	    $query2="select r.*, date_format(r.r_date, '%d.%m.%Y') as d1, date_format(r.r_released_date, '%d.%m.%Y') as d2 from project_releases pr left outer join releases r on pr.pr_r_id=r.r_id where pr.pr_p_id='".$row['p_id']."' order by r.r_name asc";
	    $rs2 = mysql_query($query2) or die(mysql_error());
	    while($row2=mysql_fetch_array($rs2))
	     {
	      echo "<a href='index.php?inc=view_release&r_id=".$row2['r_id']."'>".htmlspecialchars($row2['r_name'])."</a> (".$row2['d1'].")";
	      if ($row2['d2']!="00.00.0000") echo " - ".$row2['d2'];	      
	      echo "<br>";
	     }
	    ?>
	    </td>	    
	    <td align="left">
	    <?
	    $query2="select * from reviews where r_p_id='".$row['p_id']."' order by r_name asc";
	    $rs2 = mysql_query($query2) or die(mysql_error());
	    while($row2=mysql_fetch_array($rs2))
	     {
	      echo "<a href='index.php?inc=view_review&r_id=".$row2['r_id']."'>".htmlspecialchars($row2['r_name'])."</a>";
	      echo "<br>";
	     }
	    ?>
	    </td>	    
	    <td align="center"><input type="button" value="<?=$lng[9][15]?>" style="width:150px;" onclick="document.location.href='xml_project.php?p_id=<?=$row['p_id']?>&_lng=<?=$_SESSION['chlang']?>'" target="_blank">	    
              <br/><input type="button" value="<?=$lng[2][26]?>" style="width:150px;" onclick="document.location.href='index.php?inc=pdf_project_fields&r_p_id=<?=$row['p_id']?>&mode=landscape';" />
              <br/><input type="button" value="<?=$lng[2][38]?>" style="width:150px;"  onclick="document.location.href='index.php?inc=pdf_project_fields&r_p_id=<?=$row['p_id']?>&mode=portrait';" />
              <br/><input type="button" value="<?=$lng[2][30]?>" style="width:150px;"  onclick="document.location.href='index.php?inc=xls_project_fields&p_id=<?=$row['p_id']?>';" />
	      <br/><input type="button" value="<?=$lng[2][31]?>" style="width:150px;"  onclick="window.open('csv.php?p_id=<?=$row['p_id']?>', 'csv','menubar=yes,status=yes');" />
	    </td>
	  </tr>  
	  <?
	   }
	  ?>
	  <tr class="gray">
	    <td colspan="9" align="left"><input type="button" onclick="document.location.href='index.php?inc=edit_project'" value="<?=$lng[9][7]?>"></td>
	  </tr>  	    
	</table>
      <input type="hidden" name="inc" value="manage_projects">
      <input type="hidden" name="order" value="">
      </form>	
    </td> 	 
  </tr>
</table>
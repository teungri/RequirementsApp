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
// Page: "View project" - showing project info

//check if logged
if ($_SESSION['uid']=="")
 {
  //authorization check
  $query="select * from projects where p_id=".$p_id." and p_status=1";
  $rs = mysql_query($query) or die(mysql_error());
  if($row=mysql_fetch_array($rs)) ;
  else header("Location:index.php");
 }
else
 {
  //authorization check
  $query="select * from projects where p_id=".$p_id." and p_id in (".$project_list.")";
 // echo $query;die();
  $rs = mysql_query($query) or die(mysql_error());
  if($row=mysql_fetch_array($rs)) ;
  else header("Location:index.php");
 }

$query="select p.*, date_format(p_date, '%d.%m.%Y') as d1, u_name from projects p left outer join users u on p.p_leader=u.u_id where p.p_id=".$p_id;
$rs = mysql_query($query) or die(mysql_error());
if($row=mysql_fetch_array($rs)) 
 {
  $p_name=htmlspecialchars($row['p_name']);
  $p_desc=$row['p_desc'];
  $p_phase=htmlspecialchars($row['p_phase']);
  $p_status=htmlspecialchars($row['p_status']);
  $p_leader=htmlspecialchars($row['u_name']);
  $u_name=htmlspecialchars($row['u_name']);
  $p_date=htmlspecialchars($row['d1']);
  $p_template=htmlspecialchars($row['p_template']);
 }
?>
<table border="0" width="50%">
  <tr valign="top">
    <td>
        <table border="0" cellpadding="2" cellspacing="2" class="content" width="100%">
	  <tr class="gray">
	    <td colspan="2" align="center"><b><?=$lng[2][1]?></b></td>
	  </tr>
	  <tr class="blue" title="<?=$lng[9][16]?>">
	    <td align="right" width="50%">&nbsp;<?=$lng[10][3]?>&nbsp;:&nbsp;</td>
	    <td><?=$p_name?></td>
	  </tr>  
	  <tr class="light_blue" title="<?=$lng[9][17]?>">
	    <td align="right">&nbsp;<?=$lng[10][4]?>&nbsp;:&nbsp;</td>
	    <td>
	    <?
	      if ($p_phase==0) echo $lng[9][8];
	      elseif ($p_phase==1) echo $lng[9][9];
	      elseif ($p_phase==2) echo $lng[9][10];
	      elseif ($p_phase==3) echo $lng[9][32];
	      elseif ($p_phase==4) echo $lng[9][33];
	    ?>  
	    </td>
	  </tr>  
	  <tr class="blue" title="<?=$lng[9][18]?>">
	    <td align="right">&nbsp;<?=$lng[10][5]?>&nbsp;:&nbsp;</td>
	    <td>
	    <?
	      if ($p_status==0) echo $lng[9][11];
	      elseif ($p_status==1) echo $lng[9][12];
	      elseif ($p_status==2) echo "<span class='error'>".$lng[9][14]."</span>";
	    ?> 
	    </td>
	  </tr>  
	  <tr class="light_blue" title="<?=$lng[9][19]?>">
	    <td align="right">&nbsp;<?=$lng[10][6]?>&nbsp;:&nbsp;</td>
	    <td><?=$p_leader?></td>
	  </tr>  
	  <tr class="blue" valign=top title="<?=$lng[9][21]?>">
	    <td align="right">&nbsp;<?=$lng[10][7]?>&nbsp;:&nbsp;</td>
	    <td><?=$p_desc?></td>
	  </tr>  
	  <tr class="light_blue" title="<?=$lng[9][29]?>">
	    <td align="right">&nbsp;<?=$lng[9][28]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;<?=($p_template=="1")?"yes":"no"?></td>
	  </tr>     
	  <tr class="blue" title="<?=$lng[9][20]?>">
	    <td align="right">&nbsp;<?=$lng[10][8]?>&nbsp;:&nbsp;</td>
	    <td><?=$p_date?></td>
	  </tr>     
	  <tr class="light_blue" valign="top" title="<?=$lng[10][43]?>">
	    <td align="right">&nbsp;<?=$lng[10][42]?>&nbsp;:&nbsp;</td>
	    <td>
	    <?
	    $query2="select s.* from subprojects s left outer join projects p on s.s_p_id=p.p_id where p.p_id='".$p_id."' order by s.s_name asc";
	    $rs2 = mysql_query($query2) or die(mysql_error());
	    while($row2=mysql_fetch_array($rs2))
	     {
	      echo "<a href='index.php?inc=view_subproject&s_id=".$row2['s_id']."'>".htmlspecialchars($row2['s_name'])."</a>";
	      echo "<br>";
	     }
	    ?>
	    </td>
	  </tr>     
	  <tr class="blue" valign="top" title="<?=$lng[9][22]?>">
	    <td align="right">&nbsp;<?=$lng[10][22]?>&nbsp;:&nbsp;</td>
	    <td>
	    <?
	    $query2="select r.*, date_format(r.r_date, '%d.%m.%Y') as d1, date_format(r.r_released_date, '%d.%m.%Y') as d2 from project_releases pr left outer join releases r on pr.pr_r_id=r.r_id where pr.pr_p_id='".$p_id."' order by r.r_name asc";
	    $rs2 = mysql_query($query2) or die(mysql_error());
	    while($row2=mysql_fetch_array($rs2))
	     {
	      echo "<a href='index.php?inc=view_release&r_id=".$row2['r_id']."'>".htmlspecialchars($row2['r_name'])."</a> (".$row2['d1'].")";
	      if ($row2['d2']!="00.00.0000") echo " - ".$row2['d2'];	      
	      echo "<br>";
	     }
	    ?>
	    </td>
	  </tr>     
	  <tr class="light_blue" valign="top" title="<?=$lng[9][30]?>">
	    <td align="right">&nbsp;<?=$lng[9][31]?>&nbsp;:&nbsp;</td>
	    <td>
	    <?
	    $query2="select c.* from project_cases pc left outer join cases c on pc.pc_c_id=c.c_id where pc.pc_p_id='".$p_id."' order by c.c_name asc";
	    $rs2 = mysql_query($query2) or die(mysql_error());
	    while($row2=mysql_fetch_array($rs2))
	     {
	      echo "<a href='index.php?inc=view_case&c_id=".$row2['c_id']."'>".htmlspecialchars($row2['c_name'])."</a>";
	      echo "<br>";
	     }
	    ?>
	    </td>
	  </tr>     
	  <tr class="blue" valign="top" title="<?=$lng[9][24]?>">
	    <td align="right">&nbsp;<?=$lng[9][25]?>&nbsp;:&nbsp;</td>
	    <td>
	    <?
	    $query2="select s.* from project_stakeholders ps left outer join stakeholders s on ps.ps_s_id=s.s_id where ps.ps_p_id='".$p_id."' order by s.s_name asc";
	    $rs2 = mysql_query($query2) or die(mysql_error());
	    while($row2=mysql_fetch_array($rs2))
	     {
	      echo "<a href='index.php?inc=view_stakeholder&s_id=".$row2['s_id']."'>".htmlspecialchars($row2['s_name'])."</a>";
	      echo "<br>";
	     }
	    ?>
	    </td>
	  </tr>     
	  <tr class="light_blue" valign="top" title="<?=$lng[9][26]?>">
	    <td align="right">&nbsp;<?=$lng[9][27]?>&nbsp;:&nbsp;</td>
	    <td>
	    <?
	    $query2="select g.* from project_glossary pg left outer join glossary g on pg.pg_g_id=g.g_id where pg.pg_p_id='".$p_id."' order by g.g_id asc";
	    $rs2 = mysql_query($query2) or die(mysql_error());
	    while($row2=mysql_fetch_array($rs2))
	     {
	      $tmp_g="";
	      for ($i=0;$i<6-strlen($row2['g_id']);$i++) $tmp_g.="0";$tmp_g.=$row2['g_id'];
	      echo "<a href='index.php?inc=view_glossary&g_id=".$row2['g_id']."'>".$tmp_g."</a>";
	      echo "<br>";
	     }
	    ?>
	    </td>
	  </tr>     
	  <tr class="blue" valign="top" title="<?=$lng[9][36]?>">
	    <td align="right">&nbsp;<?=$lng[9][35]?>&nbsp;:&nbsp;</td>
	    <td>
	    <?
	    $query2="select c.* from project_components pco left outer join components c on pco.pco_c_id=c.c_id where pco.pco_p_id='".$p_id."' order by c.c_name asc";
	    $rs2 = mysql_query($query2) or die(mysql_error());
	    while($row2=mysql_fetch_array($rs2))
	     {
	      echo "<a href='index.php?inc=view_component&c_id=".$row2['c_id']."'>".htmlspecialchars($row2['c_name'])."</a>";
	      echo "<br>";
	     }
	    ?>
	    </td>
	  </tr>     
	  <tr class="blue" valign="top" title="<?=$lng[9][37]?>">
	    <td align="right">&nbsp;<?=$lng[9][37]?>&nbsp;:&nbsp;</td>
	    <td>
	    <?
	    $query2="select * from reviews where r_p_id='".$p_id."' order by r_name asc";
	    $rs2 = mysql_query($query2) or die(mysql_error());
	    while($row2=mysql_fetch_array($rs2))
	     {
	      echo "<a href='index.php?inc=view_review&r_id=".$row2['r_id']."'>".htmlspecialchars($row2['r_name'])."</a>";
	      echo "<br>";
	     }
	    ?>
	    </td>
	  </tr>     
	  <tr class="light_blue" valign="top">
	    <td colspan="2" align="center">
	      <input type="button" value="<?=$lng[9][15]?>" onclick="document.location.href='xml_project.php?p_id=<?=$p_id?>&_lng=<?=$_SESSION['chlang']?>'" target="_blank">
              &nbsp;&nbsp;<input type="button" value="<?=$lng[2][26]?>"  onclick="document.location.href='index.php?inc=pdf_project_fields&r_p_id=<?=$p_id?>&mode=landscape';" />
              &nbsp;&nbsp;<input type="button" value="<?=$lng[2][38]?>"  onclick="document.location.href='index.php?inc=pdf_project_fields&r_p_id=<?=$p_id?>&mode=portrait';" />
              &nbsp;&nbsp;<input type="button" value="<?=$lng[2][30]?>"  onclick="document.location.href='index.php?inc=xls_project_fields&p_id=<?=$p_id?>';" />
	      &nbsp;&nbsp;<input type="button" value="<?=$lng[2][31]?>"  onclick="window.open('csv.php?p_id=<?=$p_id?>', 'csv','menubar=yes,status=yes');" />
	    </td>
	  </tr>     
	</table>
    </td> 	 
  </tr>
</table>
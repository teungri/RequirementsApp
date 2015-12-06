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
// Page: "add/edit requirement" - editing/adding/deleting requirements
if ($viewtypefl=="y") $_SESSION['viewtype']=0;
if ($_SESSION['viewtype']==1) header("Location:index.php?inc=view_requirement_long&r_id=".$r_id);

//if review comment
if ($rc_text!="") 
 {
  $query="insert into review_comments (rc_rev_id, rc_req_id, rc_text, rc_comment, rc_date, rc_u_id) values ('".$review_id."','".$r_id."','".escapeChars($rc_text)."','".escapeChars($rc_comment)."', now(),'".$_SESSION['uid']."')";      
  mysql_query($query) or die(mysql_error());
 }

if ($r_id!="")
 {
  //check if logged
  if ($_SESSION['uid']=="")
   {
    //authorization check
    $query="select r.* from requirements r, projects p where r.r_id=".$r_id." and ((r.r_p_id=p.p_id and p.p_status=1) OR r.r_p_id=0)";
    $rs = mysql_query($query) or die(mysql_error());
    if($row=mysql_fetch_array($rs)) ;
    else header("Location:index.php");
   }
  else
   {
    //authorization check
    $query="select r.* from requirements r, projects p where r.r_id=".$r_id." and ((r.r_p_id=p.p_id and p.p_id in (".$project_list.")) OR r.r_p_id=0)";
    $rs = mysql_query($query) or die(mysql_error());
    if($row=mysql_fetch_array($rs)) ;
    else header("Location:index.php?err=yes");
   } 
 }
 
if ($action=="delete" && $c_id!="" && ($_SESSION['rights']=="5" || $_SESSION['rights']=="4")) 
 {
  $flag_as=0;
  if ($_SESSION['rights']=="5") $flag_as=1;
  if ($_SESSION['rights']=="4") 
   {
    $query_project44="select * from projects p left outer join project_users pu on p.p_id=pu.pu_p_id where (pu.pu_u_id=".$_SESSION['uid']." or p_leader=".$_SESSION['uid'].")";
    $rs_project44 = mysql_query($query_project44) or die(mysql_error());
    if($row_project44=mysql_fetch_array($rs_project44)) $flag_as=1;  	          
   }
  if ($flag_as)
   {
    $query="delete from comments where c_id=".$c_id." and c_r_id=".$r_id;
    mysql_query($query,$link) or die('select failed: <b>'.mysql_error().'</b>'."<br>\n");
   } 
 }  

if ($action=="update" && $c_id!="" && ($_SESSION['rights']=="5" || $_SESSION['rights']=="4")) 
 {
  $flag_as=0;
  if ($_SESSION['rights']=="5") $flag_as=1;
  if ($_SESSION['rights']=="4") 
   {
    $query_project44="select * from projects p left outer join project_users pu on p.p_id=pu.pu_p_id where (pu.pu_u_id=".$_SESSION['uid']." or p_leader=".$_SESSION['uid'].")";
    $rs_project44 = mysql_query($query_project44) or die(mysql_error());
    if($row_project44=mysql_fetch_array($rs_project44)) $flag_as=1;  	          
   }
  if ($flag_as)
   {
    $query="update comments set c_question='".$ch."' where c_id=".$c_id;
    mysql_query($query,$link) or die('select failed: <b>'.mysql_error().'</b>'."<br>\n");
   } 
 }  

 
if ($r_id!="") 
 {
  $query="select r.*, date_format(r.r_creation_date, '%d.%m.%Y %H:%i') as d1, date_format(r.r_change_date, '%d.%m.%Y %H:%i') as d2, date_format(r.r_accept_date, '%d.%m.%Y %H:%i') as d3, u.u_name, p.p_id, p.p_name, sp.s_id, sp.s_name from requirements r left outer join users u on r.r_u_id=u.u_id left outer join projects p on r.r_p_id=p.p_id left outer join subprojects sp on r.r_s_id=sp.s_id where r.r_id=".$r_id;
  $rs = mysql_query($query) or die(mysql_error());
  if($row=mysql_fetch_array($rs)) 
   {
    $r_name=htmlspecialchars($row['r_name']);
    $r_release=htmlspecialchars($row['r_release']);
    $r_c_id=htmlspecialchars($row['r_c_id']);
    $s_id=htmlspecialchars($row['s_id']);
    $r_stakeholder=htmlspecialchars($row['r_stakeholder']);
    $r_glossary=htmlspecialchars($row['r_glossary']);
    $s_name=htmlspecialchars($row['s_name']);
    $ta=$row['r_desc'];
    $r_state=$row['r_state'];
    $r_type_r=$row['r_type_r'];
    $p_id=htmlspecialchars($row['p_id']);
    $p_name=htmlspecialchars($row['p_name']);
    $r_assigned_u_id=$row['r_assigned_u_id'];
    $author=htmlspecialchars($row['u_name']);
    $r_priority=$row['r_priority'];
    $r_link=htmlspecialchars($row['r_link']);
    $r_satisfaction=htmlspecialchars($row['r_satisfaction']);
    $r_dissatisfaction=htmlspecialchars($row['r_dissatisfaction']);
    $r_conflicts=htmlspecialchars($row['r_conflicts']);
    $r_depends=htmlspecialchars($row['r_depends']);
    $r_component=$row['r_component'];    
    $r_source=htmlspecialchars($row['r_source']);    
    $r_risk=$row['r_risk'];    
    $r_complexity=$row['r_complexity'];    
    $r_weight=$row['r_weight'];    
    $r_points=$row['r_points'];    
    $r_creation_date=$row['d1'];
    $r_change_date=$row['d2'];
    $r_accept_date=$row['d3'];
    $r_accept_user=$row['r_accept_user'];  
    $r_version=$row['r_version'];  
    $r_parent_id=$row['r_parent_id'];  
    $r_pos=$row['r_pos'];  
    $r_keywords=$row['r_keywords'];  
    $r_userfield1=htmlspecialchars($row['r_userfield1']);    
    $r_userfield2=htmlspecialchars($row['r_userfield2']);    
    $r_userfield3=htmlspecialchars($row['r_userfield3']);    
    $r_userfield4=htmlspecialchars($row['r_userfield4']);    
    $r_userfield5=htmlspecialchars($row['r_userfield5']);    
    $r_userfield6=htmlspecialchars($row['r_userfield6']);   
    fixPos($r_id,$p_id); //fixing requirement position in the tree after moving elements (if needed)
   }
   
 $query44="select c.*, u.u_name, date_format(c.c_date, '%d.%m.%Y %H:%i') as d1 from comments c left outer join users u on c.c_u_id=u.u_id where c.c_r_id=".$r_id." order by c_date desc";
 $rs44 = mysql_query($query44) or die(mysql_error());
 
 } 
  
 ?>
<table border="0" width="100%">
  <tr valign="top">
    <td>
      <table border="0" cellpadding="2" cellspacing="2" class="content" width="100%">
	  <tr class="gray">
	    <td align="left" colspan="8"><?if ($viewreview!="y") {?><input type="button" value="<?=$lng[16][4]?>" onclick="document.location.href='index.php?inc=view_requirement_long&viewtypefl=y&r_id=<?=$r_id?>'">&nbsp;<?}?><?if ($_SESSION['uid']!="" && $_SESSION['rights']!=0){?><input type="button" value=" <?=$lng[16][2]?> " onclick="document.location.href='index.php?inc=edit_requirement&ref=short&r_id=<?=$r_id?>'"><?}?>&nbsp;<input type="button" value="<?=$lng[16][15]?>" onclick="document.location.href='index.php?inc=pdf_fields&r_id=<?=$r_id?>&project_list=<?=$project_list?>&mode=landscape';">&nbsp;<input type="button" value="<?=$lng[16][21]?>" onclick="document.location.href='index.php?inc=pdf_fields&r_id=<?=$r_id?>&project_list=<?=$project_list?>&mode=portrait';"><img src="img/x.gif" width="200" height="1"><b><?=$lng[16][1]?></b></td>
	  </tr>
	  <tr class="light_blue" valign="top">
	    <td align="right" nowrap title="<?=$lng[15][53]?>">&nbsp;<?=$lng[15][4]?>&nbsp;:&nbsp;</td>
	    <td colspan="7"><b>&nbsp;<?=$r_name?></b></td>
	  </tr>
	  <tr class="blue" valign="top">
	    <td align="right" nowrap title="<?=$lng[15][57]?>">&nbsp;<?=$lng[15][5]?>&nbsp;:&nbsp;</td>
	    <td align="left" colspan=7><?=$ta?></td>
	  </tr> 
	  <tr class="light_blue" valign="top">
	    <td align="right" nowrap width="15%" title="<?=$lng[15][51]?>">&nbsp;<?=$lng[15][3]?>&nbsp;:&nbsp;</td>
	    <td><a href="index.php?inc=view_project&p_id=<?=$p_id?>"><?=$p_name?></a></td>
	    <td align="right" nowrap title="<?=$lng[15][98]?>">&nbsp;<?=$lng[15][97]?>&nbsp;:&nbsp;</td>
	    <td><a href="index.php?inc=view_subproject&s_id=<?=$s_id?>"><?=$s_name?></a></td>
	    <td align="right" nowrap title="<?=$lng[15][52]?>">&nbsp;<?=$lng[15][24]?>&nbsp;:&nbsp;</td>
	    <td colspan="3">
	    <?
	    $query2="select r.*, date_format(r.r_date, '%d.%m.%Y') as d1, date_format(r.r_released_date, '%d.%m.%Y') as d2 from releases r where r.r_id in (".$r_release."0) order by r.r_name asc";
	    //$query2="select r.*, date_format(r.r_date, '%d.%m.%Y') as d1, date_format(r.r_released_date, '%d.%m.%Y') as d2 from project_releases pr left outer join releases r on pr.pr_r_id=r.r_id where pr.pr_p_id='".$p_id."' order by r.r_name asc";
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
	  <tr class="blue" valign="top">
	    <td align="right" nowrap title="<?=$lng[15][104]?>">&nbsp;<?=$lng[15][103]?>&nbsp;:&nbsp;</td>
	    <td>
	    <?
	    $query26="select * from cases where c_id in (".$r_c_id."0) order by c_name asc";
	    $rs26 = mysql_query($query26) or die(mysql_error());
	    while($row26=mysql_fetch_array($rs26))
	     {
	      echo "<a href='index.php?inc=view_case&c_id=".$row26['c_id']."'>".htmlspecialchars($row26['c_name'])."</a>";
	      echo "<br>";
	     }
	    ?>	      
	    </td> 
	    <td align="right" nowrap title="<?=$lng[15][77]?>">&nbsp;<?=$lng[15][76]?>&nbsp;:&nbsp;</td>
	    <td>
	    <?
	    $query26="select * from stakeholders where s_id in (".$r_stakeholder."0) order by s_name asc";
	    $rs26 = mysql_query($query26) or die(mysql_error());
	    while($row26=mysql_fetch_array($rs26))
	     {
	      echo "<a href='index.php?inc=view_stakeholder&s_id=".$row26['s_id']."'>".htmlspecialchars($row26['s_name'])."</a>";
	      echo "<br>";
	     }
	    ?>	      
	    </td> 
	    <td align="right" nowrap title="<?=$lng[15][87]?>">&nbsp;<?=$lng[15][86]?>&nbsp;:&nbsp;</td>
	    <td colspan="3">
	    <?
	    $query26="select g.* from glossary g where g.g_id in (".$r_glossary."0) order by g.g_id asc";
	    //$query26="select g.* from project_glossary pg left outer join glossary g on pg.pg_g_id=g.g_id where pg.pg_p_id='".$p_id."' order by g.g_id asc";
	    $rs26 = mysql_query($query26) or die(mysql_error());
	    while($row26=mysql_fetch_array($rs26))
	     {
	      $tmp_g="";
	      for ($i=0;$i<6-strlen($row26['g_id']);$i++) $tmp_g.="0";$tmp_g.=$row26['g_id'];
	      echo "<a href='index.php?inc=view_glossary&g_id=".$row26['g_id']."'>".$tmp_g."</a>";
	      echo "<br>";
	     }
	    ?>	      
	    </td> 
	  </tr>
	  <tr class="light_blue" valign="top">
	    <td align="right" nowrap title="<?=$lng[15][58]?>">&nbsp;<?=$lng[15][10]?>&nbsp;:&nbsp;</td>
	    <td>
	      <?
	      include("ini/txts/".$_SESSION['chlang']."/state.php");
	      echo $state_array[$r_state];
	      ?>
	    </td> 
	    <td align="right" nowrap title="<?=$lng[15][59]?>">&nbsp;<?=$lng[15][11]?>&nbsp;:&nbsp;</td>
	    <td colspan="5">
	      <?
	      include("ini/txts/".$_SESSION['chlang']."/type.php");
	      echo $type_array[$r_type_r];
	      ?>
	    </td>	     
	  </tr>
	  <tr class="blue" valign="top">
	    <td align="right" nowrap title="<?=$lng[15][60]?>">&nbsp;<?=$lng[15][13]?>&nbsp;:&nbsp;</td>
	    <td><?=$r_priority?></td>
	    <!--td align="right" nowrap>&nbsp;<?=$lng[15][15]?>&nbsp;:&nbsp;</td>
	    <td>
	      <?
	        if ($r_valid==0) echo $lng[15][31];
	        elseif ($r_valid==1) echo $lng[15][32];
	        elseif ($r_valid==2) echo $lng[15][33];
	      ?>	       	    
	    </td-->
	    <td align="right" nowrap title="<?=$lng[15][56]?>">&nbsp;<?=$lng[15][30]?>&nbsp;:&nbsp;</td>
	    <td>
	    <?
	       if ($r_assigned_u_id!=0)
	        {
	         $query4="select * from users where u_id=".$r_assigned_u_id;
 		 $rs4 = mysql_query($query4) or die(mysql_error());
  		 if($row4=mysql_fetch_array($rs4)) echo "&nbsp;".htmlspecialchars($row4['u_name']);
  		} 
  	       else echo "&nbsp;-";	
	      ?>
	    </td>
	    <td align="right" nowrap title="<?=$lng[15][67]?>">&nbsp;<?=$lng[15][45]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;<?=$r_id?></td>
	    <td align="right" nowrap title="<?=$lng[15][69]?>">&nbsp;<?=$lng[15][34]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;<?=$r_version?></td>	    
	  </tr>  	  
	  <tr class="light_blue" valign="top">
	    <td align="right" nowrap title="<?=$lng[15][62]?>">&nbsp;<?=$lng[15][40]?>&nbsp;:&nbsp;</td>
	    <td>
	    <?
	    $query26="select * from components where c_id in (".$r_component."0) order by c_name asc";
	    $rs26 = mysql_query($query26) or die(mysql_error());
	    while($row26=mysql_fetch_array($rs26))
	     {
	      echo "<a href='index.php?inc=view_component&c_id=".$row26['c_id']."'>".htmlspecialchars($row26['c_name'])."</a>; ";
	     }
	    ?>
	    </td>
	    <td align="right" nowrap title="<?=$lng[15][63]?>">&nbsp;<?=$lng[15][41]?>&nbsp;:&nbsp;</td>
	    <td><?=$r_source?></td>
	    <td align="right" nowrap title="<?=$lng[15][64]?>">&nbsp;<?=$lng[15][42]?>&nbsp;:&nbsp;</td>
	    <td>
	      <?
	      include("ini/txts/".$_SESSION['chlang']."/risk.php");
	      echo $risk_array[$r_risk];
	      ?>
	    </td>
	    <td align="right" nowrap title="<?=$lng[15][65]?>">&nbsp;<?=$lng[15][43]?>&nbsp;:&nbsp;</td>
	    <td>
	      <?
	      include("ini/txts/".$_SESSION['chlang']."/complexity.php");
	      echo $complexity_array[$r_complexity];
	      ?>
	    </td>	    
	  </tr>  	  
	  <tr class="blue" valign="top">
	    <td align="right" nowrap title="<?=$lng[15][89]?>">&nbsp;<?=$lng[15][88]?>&nbsp;:&nbsp;</td>
	    <td align="left">&nbsp;<?=$r_weight?></td>
	    <td align="right" nowrap title="<?=$lng[15][66]?>">&nbsp;<?=$lng[15][44]?>&nbsp;:&nbsp;</td>
	    <td align="left">&nbsp;<?=$r_points?></td>
	    <td align="right" nowrap title="<?=$lng[15][108]?>">&nbsp;<?=$lng[15][107]?>&nbsp;:&nbsp;</td>
	    <td align="left" colspan="3">
	    <?
	      $query456="select k.* from keywords k where k.k_id in (".$r_keywords."0) order by k.k_name asc";
	      //$query456="select k_id, k_name from keywords where k_id in (".$r_keywords."0)";
              $rs456 = mysql_query($query456) or die(mysql_error());	        
	      while($row456=mysql_fetch_array($rs456)) 
	       {
	        ?>
	        <?=htmlspecialchars($row456['k_name'])?>;
	      <?}?>
	    </td>
	  </tr> 
	  <tr class="light_blue" valign="top">
	    <td align="right" nowrap title="<?=$lng[15][83]?>">&nbsp;<?=$lng[15][82]?>&nbsp;:&nbsp;</td>
	    <td align="left">&nbsp;<?=$r_satisfaction?></td>
	    <td align="right" nowrap title="<?=$lng[15][85]?>">&nbsp;<?=$lng[15][84]?>&nbsp;:&nbsp;</td>
	    <td align="left">&nbsp;<?=$r_dissatisfaction?></td>
	    <td align="right" nowrap title="<?=$lng[15][79]?>">&nbsp;<?=$lng[15][78]?>&nbsp;:&nbsp;</td>
	    <td align="left">
	      <?
	      $query456="select r_id, r_name from requirements where r_id in (".$r_depends."0)";
              $rs456 = mysql_query($query456) or die(mysql_error());	        
	      while($row456=mysql_fetch_array($rs456)) 
	       {
	        ?>
	        &nbsp;<a href="index.php?inc=view_requirement&r_id=<?=$row456['r_id']?>"><?=htmlspecialchars($row456['r_name'])?></a><br>
	      <?}?>
	    </td>
	    <td align="right" nowrap title="<?=$lng[15][81]?>">&nbsp;<?=$lng[15][80]?>&nbsp;:&nbsp;</td>
	    <td align="left">
	      <?
	      $query456="select r_id, r_name from requirements where r_id in (".$r_conflicts."0)";
              $rs456 = mysql_query($query456) or die(mysql_error());	        
	      while($row456=mysql_fetch_array($rs456)) 
	       {
	        ?>
	        &nbsp;<a href="index.php?inc=view_requirement&r_id=<?=$row456['r_id']?>"><?=htmlspecialchars($row456['r_name'])?></a><br>
	      <?}?>
	    </td>
	  </tr> 
	  <tr class="blue" valign="top">
	    <td align="right" nowrap title="<?=$lng[15][68]?>">&nbsp;<?=$lng[15][16]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;<?=$author?></td>
	    <td align="right" nowrap title="<?=$lng[15][61]?>">&nbsp;<?=$lng[15][14]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;<a href="<?=$r_link?>" target="_blank">
	    <?
	     for ($i=0;$i<strlen($r_link);$i+=40) 
	      echo substr($r_link,$i,40)." ";
	    ?>
	    </a></td>	    
	    <td align="right" nowrap title="<?=$lng[15][54]?>">&nbsp;<?=$lng[15][38]?>&nbsp;:&nbsp;</td>
	    <td>
	      <?
	      $query456="select r_name from requirements where r_id=".$r_parent_id;
              $rs456 = mysql_query($query456) or die(mysql_error());	        
	      if($row456=mysql_fetch_array($rs456)) 
	       {
	        ?>
	        &nbsp;<a href="index.php?inc=view_requirement&r_id=<?=$r_parent_id?>"><?=htmlspecialchars($row456['r_name'])?></a>
	      <?}?>
	    </td>	    
	    <td align="right" nowrap title="<?=$lng[15][55]?>">&nbsp;<?=$lng[15][39]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;<?=$r_pos?></a></td>	    
	  </tr>  
	  <?
	  $cnt_u=0;
	  $query41="select count(*) from user_fields where uf_name_en<>''";
	  $rs41=mysql_query($query41) or die(mysql_error());
	  if($row41=mysql_fetch_array($rs41)) $cnt_num=$row41[0];	  
	   
	  if ($cnt_num>0) 
	   {
	    $query41="select * from user_fields order by uf_id asc";
	    $rs41=mysql_query($query41) or die(mysql_error());
	    while($row41=mysql_fetch_array($rs41))
	     {
	      $uf_name[]=htmlspecialchars($row41['uf_name_'.$_SESSION['chlang']]);
	      $uf_text[]=htmlspecialchars($row41['uf_text_'.$_SESSION['chlang']]);
             }
	    ?>
	  <tr class="light_blue" valign="top">
	    <?if ($cnt_num>0) {?>
	      <td align="right" nowrap title="<?=$uf_text[0]?>">&nbsp;<?=$uf_name[0]?>&nbsp;:&nbsp;</td>
	      <td align="left" <?if ($cnt_num==1) echo "colspan=7";?>><?=$r_userfield1?></td>
	    <?}?>
	    <?if ($cnt_num>1) {?>
	      <td align="right" nowrap title="<?=$uf_text[1]?>">&nbsp;<?=$uf_name[1]?>&nbsp;:&nbsp;</td>
	      <td align="left" <?if ($cnt_num==2) echo "colspan=5";?>><?=$r_userfield2?></td>
	    <?}?>
	    <?if ($cnt_num>2) {?>
	      <td align="right" nowrap title="<?=$uf_text[2]?>">&nbsp;<?=$uf_name[2]?>&nbsp;:&nbsp;</td>
	      <td align="left" colspan=3><?=$r_userfield3?></td>
	    <?}?>
	  </tr> 
	  <?
	   }
	  if ($cnt_num>3) 
	   {
	    ?>
	  <tr class="blue" valign="top">
	    <?if ($cnt_num>3) {?>
	      <td align="right" nowrap title="<?=$uf_text[3]?>">&nbsp;<?=$uf_name[3]?>&nbsp;:&nbsp;</td>
	      <td align="left" <?if ($cnt_num==4) echo "colspan=7";?>><?=$r_userfield4?></td>
	    <?}?>
	    <?if ($cnt_num>4) {?>
	      <td align="right" nowrap title="<?=$uf_text[4]?>">&nbsp;<?=$uf_name[4]?>&nbsp;:&nbsp;</td>
	      <td align="left" <?if ($cnt_num==5) echo "colspan=5";?>><?=$r_userfield5?></td>
	    <?}?>
	    <?if ($cnt_num>5) {?>
	      <td align="right" nowrap title="<?=$uf_text[5]?>">&nbsp;<?=$uf_name[5]?>&nbsp;:&nbsp;</td>
	      <td align="left" colspan=3><?=$r_userfield6?></td>
	    <?}?>
	  </tr> 
	  <?}?>
	   
	  <tr class="<?if ($cnt_num<1 || $cnt_num>3) echo "light_";?>blue" valign="top">
	    <td align="right" nowrap title="<?=$lng[15][70]?>">&nbsp;<?=$lng[15][17]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;<?=($r_creation_date=="00.00.0000 00:00")?"-":$r_creation_date?></td>
	    <td align="right" nowrap title="<?=$lng[15][71]?>">&nbsp;<?=$lng[15][18]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;<?=($r_change_date=="00.00.0000 00:00")?"-":$r_change_date?></td>
	    <td align="right" nowrap title="<?=$lng[15][72]?>">&nbsp;<?=$lng[15][19]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;<?=($r_accept_date=="00.00.0000 00:00")?"-":$r_accept_date?></td>
	    <td align="right" nowrap title="<?=$lng[15][73]?>">&nbsp;<?=$lng[15][20]?>&nbsp;:&nbsp;</td>
	    <td>
	      <?
	       if ($r_accept_user!=0)
	        {
	         $query4="select * from users where u_id=".$r_accept_user;
 		 $rs4 = mysql_query($query4) or die(mysql_error());
  		 if($row4=mysql_fetch_array($rs4)) echo "&nbsp;".htmlspecialchars($row4['u_name']);
  		} 
  	       else echo "&nbsp;-";	
	      ?>
	    </td>
	  </tr>     
	</table>
    </td> 	 
  </tr>
</table>

<?if ($viewreview=="y") {?>
<table border="0" width="100%">
  <tr valign="top">
    <td>
      <?include("ini/txts/".$_SESSION['chlang']."/review_comments.php");?>
      <table border="0" cellpadding="2" cellspacing="2" class="content" width="100%">
	 <form name="f12">
 	  <tr class="gray">
	    <td colspan=2 align="center" width="100%"><b><?=$lng[16][22]?></b></td>
	  </tr>
	  <?
	  $cnt=0;
	  $query44="select rc.*, u.u_name, date_format(rc.rc_date, '%d.%m.%Y %H:%i') as d1 from review_comments rc left outer join users u on rc.rc_u_id=u.u_id where rc.rc_rev_id=".$review_id." and rc.rc_req_id=".$r_id." order by  rc.rc_date desc";
	  $rs44 = mysql_query($query44) or die(mysql_error());
	  while($row44=mysql_fetch_array($rs44)) 
	   {	
	    ?>
	  <tr class="light_blue">
	    <td colspan=2><?=$lng[16][9]?>: <?=htmlspecialchars($row44['u_name'])?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$lng[16][10]?>: <?=htmlspecialchars($row44['d1'])?><br/>&nbsp;<?if($review_comments_array[$row44['rc_comment']]!="") echo $review_comments_array[$row44['rc_comment']].": ";?><?=htmlspecialchars($row44['rc_text'])?></td>
	  </tr>  
	  <?}?>
 	  <tr class="gray" valign="top">
	    <td colspan=2 valign="top" align="left" width="100%">
	    	<select name="rc_comment">
	    	 <option value="">--- 
		 <?echo makeSelect($review_comments_array,$rc_comment);?>
	       </select>	    
          </td>
	  </tr>
 	  <tr class="gray" valign="top">
	    <td colspan=2 valign="top" align="left" width="100%">
	         <textarea name="rc_text" rows="4" cols="40"></textarea>
	    </td>
	  </tr>
 	  <tr class="gray" valign="top">
	    <td colspan=2 valign="top" align="left" width="100%">
	         <input type="button" value="<?=$lng[16][23]?>" onclick="if (document.forms['f12'].rc_text.value!='') document.forms['f12'].submit();">
	    </td>
	  </tr>
	  <input type="hidden" name="inc" value="view_requirement">
	  <input type="hidden" name="review_id" value="<?=$review_id?>">
	  <input type="hidden" name="r_id" value="<?=$r_id?>">
	  <input type="hidden" name="viewreview" value="y">
	  </form>
	</table>
    </td> 	 
  </tr>
</table>

<?}else{?>
<table border="0" width="100%">
  <tr valign="top">
    <td>
      <table border="0" cellpadding="2" cellspacing="2" class="content" width="100%">
	  <form name="f11">
	  <tr class="gray">
	    <td align="center"><?if ($_SESSION['uid']!=""){?><input type="button" value="<?=$lng[16][8]?>" onclick="document.location.href='index.php?inc=add_comment&r_id=<?=$r_id?>&what='"><?}?>&nbsp;</td>
	    <td align="center" colspan=7 width="100%"><b><?=$lng[16][7]?></b></td>
	  </tr>
	  <?
	  $cnt=0;
	  while($row44=mysql_fetch_array($rs44)) 
	   {	
	    ?>
	  <tr class="blue">
	    <td align="right" nowrap>&nbsp;<?=$lng[16][9]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;<?=htmlspecialchars($row44['u_name'])?></td>
	    <td align="right" nowrap>&nbsp;<?=$lng[16][10]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;<?=($row44['c_date']!="00.00.0000")?$row44['c_date']:""?></td>
	    <td>&nbsp;<?=$lng[18][3]?>&nbsp;<input type="checkbox" name="ch<?=$cnt?>" value="1" <?=($row44['c_question']=="1")?"checked":""?>>
	    <? $flag_as=0;
	      if ($_SESSION['rights']=="4" || $_SESSION['rights']=="5")
	       {
	        $flag_as=0;
	        if ($_SESSION['rights']=="5") $flag_as=1;
	        if ($_SESSION['rights']=="4") 
	         {
	          $query_project44="select * from projects p left outer join project_users pu on p.p_id=pu.pu_p_id where p.p_id='".$p_id."' and (pu.pu_u_id=".$_SESSION['uid']." or p.p_leader=".$_SESSION['uid'].")";
  	          $rs_project44 = mysql_query($query_project44) or die(mysql_error());
  	          if($row_project44=mysql_fetch_array($rs_project44)) $flag_as=1;  	          
	         }
	        if ($flag_as) {
	        ?>
	        &nbsp;<input type="button" value="<?=$lng[16][19]?>" onclick="sub('<?=$cnt?>','update');">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="<?=$lng[16][11]?>" onclick="if (confirm('<?=$lng[16][12]?>')) sub('<?=$cnt?>','delete');return false;">
	     <?}}?>
	    </td>
	  </tr>  
	  <tr class="light_blue">
	    <td colspan=8>&nbsp;<?=$row44['c_text']?></td>
	  </tr>  
	  <input type="hidden" name="c_id<?=$cnt?>" value="<?=$row44['c_id']?>">
	  <?
	   $cnt++;
	  }?>
	  <input type="hidden" name="inc" value="view_requirement">
	  <input type="hidden" name="c_id" value="">
	  <input type="hidden" name="ch" value="">
	  <input type="hidden" name="r_id" value="<?=$r_id?>">
	  <input type="hidden" name="what" value="">
	  <input type="hidden" name="action" value="">
	  </form>
	</table>
    </td> 	 
  </tr>
</table>
<?}?>


<script>
function sub(who,what)
 {
  df=document.forms['f11'];
  if (df.elements['ch'+who].checked) df.elements['ch'].value=1;else df.elements['ch'].value=0;
  df.elements['c_id'].value=df.elements['c_id'+who].value;
  df.action.value=what;
  df.submit();	     
 }
 </script>
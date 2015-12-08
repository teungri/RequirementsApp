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
// Page: "Top part of the site"

if ($_SESSION['projects']=="")
{
    $_SESSION['projects']=0;
}
if ( ! isset($p_switch) )
{
    $p_switch = "";
}
if ($p_switch!="" && $project_id!="")
{
    $_SESSION['projects']=$project_id;
}
if ( ! isset($_SESSION['rights']) )
{
    $_SESSION['rights'] = "";
}
if ($_SESSION['rights']=="")
{
    $query_project="select p_name, p_id from projects where p_status=1 order by p_name asc";
}
elseif ($_SESSION['rights']=="0" || $_SESSION['rights']=="1" || $_SESSION['rights']=="2" || $_SESSION['rights']=="3") 
{
    $query_project="select distinct(p.p_id), p.p_name from projects p left outer join project_users pu on p.p_id=pu.pu_p_id where (pu.pu_u_id=".$_SESSION['uid']." or p.p_status=1) order by p.p_name asc";
}
elseif ($_SESSION['rights']=="4") 
{
    $query_project="select distinct(p.p_id), p.p_name from projects p left outer join project_users pu on p.p_id=pu.pu_p_id where (pu.pu_u_id=".$_SESSION['uid']." or p_leader=".$_SESSION['uid']." or  p.p_status=1) order by p_name asc";
}
else 
{
    $query_project="select p_name, p_id from projects where p_status<>2 order by p_name asc";
}
$rs = mysql_query($query_project) or die(mysql_error());

if ( ! isset($viewalltype) )
{
    $viewalltype = "normal";
}
if ($viewalltype=="tree" && $_SESSION['projects']!=0) 
{
    $_SESSION['_viewalltype']=0;
}
elseif ($viewalltype=="normal" || $_SESSION['projects']==0)
{
    $_SESSION['_viewalltype']=1;
}
?>
<table border="0" cellpadding="2" cellspacing="0" class="topMenu" width="100%">
  <tr>
    <td>
      <form method="post" name="f_project" action="">
      &nbsp;<?=$lng[2][1]?>: 
      <select name="project_id" class="small" onchange="document.forms['f_project'].submit()">
        <option value="0"><?=$lng[2][2]?></option>
        <?
        //setting all assigned projects to the user
        $project_list="0";
        
        while($row=mysql_fetch_array($rs)) 
	 {
	  if ($_SESSION['projects']==$row['p_id']) echo "<option value='".$row['p_id']."' selected>".htmlspecialchars($row['p_name']);
	  else echo "<option value='".$row['p_id']."'>".htmlspecialchars($row['p_name']);
	  //if all projects selected
	  if ($_SESSION['projects']==0) $project_list.=",".$row['p_id'];
	  //if just one project selected
	  elseif ($_SESSION['projects']>0 && $_SESSION['projects']==$row['p_id']) $project_list=$row['p_id'];
	 }

        ?>
      </select> 
      <!--input type="submit" value="<?=$lng[2][3]?>" /-->
      <?if ($inc=="view_all" && $_SESSION['projects']!=0 && $_SESSION['projects']!="" && $_SESSION['_viewalltype']==1) {?><input type="button" value=" <?=$lng[2][32]?>"  onclick="document.forms['req'].viewalltype.value='tree';document.forms['req'].submit();" /><?}?>
      <?if ($inc=="view_all" && $_SESSION['projects']!=0 && $_SESSION['projects']!="" && $_SESSION['_viewalltype']==0) {?><input type="button" value=" <?=$lng[2][33]?>"  onclick="document.forms['req'].viewalltype.value='normal';document.forms['req'].submit();" /><?}?>
      <?if ($inc=="view_all" && $_SESSION['projects']!=0 && $_SESSION['projects']!="") {?><input type="button" value="<?=$lng[2][26]?>"  onclick="document.location.href='index.php?inc=pdf_project_fields&r_p_id=<?=$_SESSION['projects']?>&filter1=<?=$filter1?>&filter2=<?=$filter2?>&filter4=<?=$filter4?>&filter5=<?=htmlspecialchars($filter5)?>&filter6=<?=$filter6?>&filter7=<?=$filter7?>&filter8=<?=$filter8?>&filter18=<?=$filter18?>&order=<?=str_replace(" ","_",$order)?>&ids='+gen_ids('req')+'&ids2='+getCh()+'&srch='+gen_srch()+'&mode=landscape';" /><?}?>
      <?if ($inc=="view_all" && $_SESSION['projects']!=0 && $_SESSION['projects']!="") {?><input type="button" value="<?=$lng[2][38]?>"  onclick="document.location.href='index.php?inc=pdf_project_fields&r_p_id=<?=$_SESSION['projects']?>&filter1=<?=$filter1?>&filter2=<?=$filter2?>&filter4=<?=$filter4?>&filter5=<?=htmlspecialchars($filter5)?>&filter6=<?=$filter6?>&filter7=<?=$filter7?>&filter8=<?=$filter8?>&filter18=<?=$filter18?>&order=<?=str_replace(" ","_",$order)?>&ids='+gen_ids('req')+'&ids2='+getCh()+'&srch='+gen_srch()+'&mode=portrait';" /><?}?>
      <?if ($inc=="view_all" && $_SESSION['projects']!=0 && $_SESSION['projects']!="") {?><input type="button" value="<?=$lng[2][30]?>"  onclick="document.location.href='index.php?inc=xls_project_fields&p_id=<?=$_SESSION['projects']?>&srch='+gen_srch()+'&ids2='+getCh()+'&ids='+gen_ids('req');" /><?}?>
      <?if ($inc=="view_all" && $_SESSION['projects']!=0 && $_SESSION['projects']!="") {?><input type="button" value="<?=$lng[2][31]?>"  onclick="window.open('tsv.php?p_id=<?=$_SESSION['projects']?>&srch='+gen_srch()+'&ids2='+getCh()+'&ids='+gen_ids('req'), 'tsv','menubar=yes,status=yes');" /><?}?>
      <?if ($inc=="view_all" && $_SESSION['projects']!=0 && $_SESSION['projects']!="") {?><input type="button" value="<?=$lng[2][41]?>"  onclick="window.open('csv.php?p_id=<?=$_SESSION['projects']?>&srch='+gen_srch()+'&ids2='+getCh()+'&ids='+gen_ids('req'), 'csv','menubar=yes,status=yes');" /><?}?>
      <input type="hidden" name="inc" value="<?=($inc=="edit_project" || $inc=="view_project" || $inc=="edit_requirement" || $inc=="view_requirement" || $inc=="view_requirement_long" || $inc=="view_all" || $inc=="add_comment")?$inc:""?>"/>
      <input type="hidden" name="p_switch" value="yes"/>
      </form>
    </td>
    <?if ($inc=="view_all") {?>
    </tr>
    <tr>
    <?}?>
    <td align="right">      
      &nbsp;<?=$lng[2][6]?>: 
      <select name="_chlang" onchange="document.location.href='index.php?inc=<?=$inc?>&_chlang='+this.value">
        <option value="en" <?if ($_SESSION['chlang']=="en") echo "selected";?>><?=$lng[2][7]?></option>
        <option value="de" <?if ($_SESSION['chlang']=="de") echo "selected";?>><?=$lng[2][8]?></option>
        <option value="fr" <?if ($_SESSION['chlang']=="fr") echo "selected";?>><?=$lng[2][9]?></option>
        <option value="it" <?if ($_SESSION['chlang']=="it") echo "selected";?>><?=$lng[2][10]?></option>
      </select> 
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <?if ($_SESSION['uid']!="") {?>
        <?=$lng[2][4]?> <?=htmlspecialchars($_SESSION['username'])?> <span class="small">(<?=htmlspecialchars($_SESSION['name'])?> - 
        <?
         switch($_SESSION['rights'])
          {
           case 0:echo $lng[2][25];break;
           case 1:echo $lng[2][14];break;
           case 2:echo $lng[2][15];break;
           case 3:echo $lng[2][16];break;
           case 4:echo $lng[2][17];break;
           case 5:echo $lng[2][18];break;
           default:echo $lng[2][14];
          }
        ?>
        
        )</span>   <a href="index.php?inc=logout"><?=$lng[2][12]?></a>
      <?}else{?>
        <a href="index.php?inc=login"><?=$lng[2][11]?></a> | <a href="index.php?inc=register"><?=$lng[2][13]?></a>
      <?}?>
      &nbsp;
    </td>
  </tr>  
</table>
<br/>
<table border="0" cellpadding="2" cellspacing="0" class="topMenu" width="100%">
  <tr>
    <td>
      &nbsp;<a href="index.php"><?=$lng[2][5]?></a>&nbsp;|
      &nbsp;<a href="index.php?inc=view_all"><?=$lng[2][24]?></a>&nbsp;|
      <?if (!($_SESSION['uid']=="" || $_SESSION['username']=="guest")) {?>&nbsp;<a href="index.php?inc=my_profile"><?=$lng[2][19]?></a>&nbsp;|<?}?>
      <?if ($_SESSION['rights']=="5") {?>&nbsp;<a href="index.php?inc=manage_projects"><?=$lng[2][20]?></a>&nbsp;|<?}?>
      <?if ($_SESSION['rights']=="5") {?>&nbsp;<a href="index.php?inc=manage_subprojects"><?=$lng[2][34]?></a>&nbsp;|<?}?>
      <?if ($_SESSION['rights']=="1" || $_SESSION['rights']=="2" || $_SESSION['rights']=="3" || $_SESSION['rights']=="4" || $_SESSION['rights']=="5") {?>&nbsp;<a href="index.php?inc=manage_releases"><?=$lng[2][22]?></a>&nbsp;|<?}?>
      <?if ($_SESSION['rights']=="1" || $_SESSION['rights']=="2" || $_SESSION['rights']=="3" || $_SESSION['rights']=="4" || $_SESSION['rights']=="5") {?>&nbsp;<a href="index.php?inc=manage_cases"><?=$lng[2][35]?></a>&nbsp;|<?}?>
      <?if ($_SESSION['rights']=="5") {?>&nbsp;<a href="index.php?inc=manage_stakeholders"><?=$lng[2][27]?></a>&nbsp;|<?}?>
      <?if ($_SESSION['rights']=="5") {?>&nbsp;<a href="index.php?inc=manage_glossary"><?=$lng[2][28]?></a>&nbsp;|<?}?>
      <?if ($_SESSION['rights']=="1" || $_SESSION['rights']=="2" || $_SESSION['rights']=="3" || $_SESSION['rights']=="4" || $_SESSION['rights']=="5") {?>&nbsp;<a href="index.php?inc=manage_components"><?=$lng[2][39]?></a>&nbsp;|<?}?>
      <?if ($_SESSION['rights']=="5") {?>&nbsp;<a href="index.php?inc=manage_users"><?=$lng[2][21]?></a>&nbsp;|<?}?>
      <?if ($_SESSION['rights']=="1" || $_SESSION['rights']=="2" || $_SESSION['rights']=="3" || $_SESSION['rights']=="4" || $_SESSION['rights']=="5") {?>&nbsp;<a href="index.php?inc=edit_requirement"><?=$lng[2][23]?></a>&nbsp;|<?}?>
      <?if ($_SESSION['rights']=="4" || $_SESSION['rights']=="5") {?>&nbsp;<a href="index.php?inc=import"><?=$lng[2][37]?></a>&nbsp;|<?}?>
      <?if ($_SESSION['rights']=="1" || $_SESSION['rights']=="2" || $_SESSION['rights']=="3" || $_SESSION['rights']=="4" || $_SESSION['rights']=="5") {?>&nbsp;<a href="index.php?inc=manage_keywords"><?=$lng[2][36]?></a>&nbsp;|<?}?>
      &nbsp;<a href="index.php?inc=manage_reviews"><?=$lng[2][40]?></a>&nbsp;|
      &nbsp;<a href="index.php?inc=statistics"><?=$lng[2][29]?></a>
    </td>
  </tr>  
</table>
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
// Page: "pdf fields" - list of fields

if($act=="delete" && $templates!="")
 {
  $query2="delete from export_fields where ef_id=".$templates." and ef_uid=".$_SESSION['uid'];
  $rs = mysql_query($query2) or die(mysql_error());
 }

if($act=="save" && $templates!="")
 {
  $query2="update export_fields set "; 
  $query2.="ef_description='".escapeChars($description)."', "; 
  $query2.="ef_project='".escapeChars($project)."', "; 
  $query2.="ef_subproject='".escapeChars($subproject)."', "; 
  $query2.="ef_release='".escapeChars($release)."', "; 
  $query2.="ef_test_case='".escapeChars($test_case)."', "; 
  $query2.="ef_stakeholder='".escapeChars($stakeholder)."', "; 
  $query2.="ef_glossary='".escapeChars($glossary)."', "; 
  $query2.="ef_state='".escapeChars($state)."', "; 
  $query2.="ef_type='".escapeChars($type)."', "; 
  $query2.="ef_priority='".escapeChars($priority)."', "; 
  $query2.="ef_assign_to='".escapeChars($assign_to)."', "; 
  $query2.="ef_rid='".escapeChars($rid)."', "; 
  $query2.="ef_version='".escapeChars($version)."', "; 
  $query2.="ef_component='".escapeChars($component)."', "; 
  $query2.="ef_source='".escapeChars($source)."', "; 
  $query2.="ef_risk='".escapeChars($risk)."', "; 
  $query2.="ef_complexity='".escapeChars($complexity)."', "; 
  $query2.="ef_weight='".escapeChars($weight)."', "; 
  $query2.="ef_open_points='".escapeChars($open_points)."', "; 
  $query2.="ef_keywords='".escapeChars($keywords)."', "; 
  $query2.="ef_satisfaction='".escapeChars($satisfaction)."', "; 
  $query2.="ef_dissatisfaction='".escapeChars($dissatisfaction)."', "; 
  $query2.="ef_depends='".escapeChars($depends)."', "; 
  $query2.="ef_conflicts='".escapeChars($conflicts)."', "; 
  $query2.="ef_author='".escapeChars($author)."', "; 
  $query2.="ef_url='".escapeChars($url)."', "; 
  $query2.="ef_parent='".escapeChars($parent)."', "; 
  $query2.="ef_position='".escapeChars($position)."', "; 
  $query2.="ef_userfields='".escapeChars($userfields)."', "; 
  $query2.="ef_creation_date='".escapeChars($creation_date)."', "; 
  $query2.="ef_last_change='".escapeChars($last_change)."', "; 
  $query2.="ef_accepted_date='".escapeChars($accepted_date)."', "; 
  $query2.="ef_accepted_user='".escapeChars($accepted_user)."', "; 
  $query2.="ef_comments='".escapeChars($comments)."' "; 
  $query2.="where ef_id='".$templates."' and ef_uid=".$_SESSION['uid'];
  $rs = mysql_query($query2) or die(mysql_error());
 }
 
if($act=="save" && $templates=="")
 {
  $max=1;
  $query3="select max(ef_id) from export_fields where ef_uid=".$_SESSION['uid'];
  $rs3 = mysql_query($query3) or die(mysql_error());   
  if($row3=mysql_fetch_array($rs3)) $max=$row3[0]+1;

  $query2="insert into export_fields (ef_name, ef_uid, "; 
  $query2.="ef_description, "; 
  $query2.="ef_project, "; 
  $query2.="ef_subproject, "; 
  $query2.="ef_release, "; 
  $query2.="ef_test_case, "; 
  $query2.="ef_stakeholder, "; 
  $query2.="ef_glossary, "; 
  $query2.="ef_state, "; 
  $query2.="ef_type, "; 
  $query2.="ef_priority, "; 
  $query2.="ef_assign_to, "; 
  $query2.="ef_rid, "; 
  $query2.="ef_version, "; 
  $query2.="ef_component, "; 
  $query2.="ef_source, "; 
  $query2.="ef_risk, "; 
  $query2.="ef_complexity, "; 
  $query2.="ef_weight, "; 
  $query2.="ef_open_points, "; 
  $query2.="ef_keywords, "; 
  $query2.="ef_satisfaction, "; 
  $query2.="ef_dissatisfaction, "; 
  $query2.="ef_depends, "; 
  $query2.="ef_conflicts, "; 
  $query2.="ef_author, "; 
  $query2.="ef_url, "; 
  $query2.="ef_parent, "; 
  $query2.="ef_position, "; 
  $query2.="ef_userfields, "; 
  $query2.="ef_creation_date, "; 
  $query2.="ef_last_change, "; 
  $query2.="ef_accepted_date, "; 
  $query2.="ef_accepted_user, "; 
  $query2.="ef_comments) "; 
  $query2.="values (";
  $query2.="'".$lng[35][4]." $max', "; 
  $query2.="'".$_SESSION['uid']."', "; 
  $query2.="'".escapeChars($description)."', "; 
  $query2.="'".escapeChars($project)."', "; 
  $query2.="'".escapeChars($subproject)."', "; 
  $query2.="'".escapeChars($release)."', "; 
  $query2.="'".escapeChars($test_case)."', "; 
  $query2.="'".escapeChars($stakeholder)."', "; 
  $query2.="'".escapeChars($glossary)."', "; 
  $query2.="'".escapeChars($state)."', "; 
  $query2.="'".escapeChars($type)."', "; 
  $query2.="'".escapeChars($priority)."', "; 
  $query2.="'".escapeChars($assign_to)."', "; 
  $query2.="'".escapeChars($rid)."', "; 
  $query2.="'".escapeChars($version)."', "; 
  $query2.="'".escapeChars($component)."', "; 
  $query2.="'".escapeChars($source)."', "; 
  $query2.="'".escapeChars($risk)."', "; 
  $query2.="'".escapeChars($complexity)."', "; 
  $query2.="'".escapeChars($weight)."', "; 
  $query2.="'".escapeChars($open_points)."', "; 
  $query2.="'".escapeChars($keywords)."', "; 
  $query2.="'".escapeChars($satisfaction)."', "; 
  $query2.="'".escapeChars($dissatisfaction)."', "; 
  $query2.="'".escapeChars($depends)."', "; 
  $query2.="'".escapeChars($conflicts)."', "; 
  $query2.="'".escapeChars($author)."', "; 
  $query2.="'".escapeChars($url)."', "; 
  $query2.="'".escapeChars($parent)."', "; 
  $query2.="'".escapeChars($position)."', "; 
  $query2.="'".escapeChars($userfields)."', "; 
  $query2.="'".escapeChars($creation_date)."', "; 
  $query2.="'".escapeChars($last_change)."', "; 
  $query2.="'".escapeChars($accepted_date)."', "; 
  $query2.="'".escapeChars($accepted_user)."', "; 
  $query2.="'".escapeChars($comments)."')"; 
  mysql_query($query2) or die(mysql_error());
  $templates=mysql_insert_id();
 }

if ($templates!="")
 {
  $query2="select * from export_fields where ef_id=".$templates." and ef_uid=".$_SESSION['uid'];
  $rs = mysql_query($query2) or die(mysql_error());
  if($row=mysql_fetch_array($rs)) 
   {
    $description=$row['ef_description'];
    $project=$row['ef_project'];
    $subproject=$row['ef_subproject'];
    $release=$row['ef_release'];
    $test_case=$row['ef_test_case'];
    $stakeholder=$row['ef_stakeholder'];
    $glossary=$row['ef_glossary'];
    $state=$row['ef_state'];
    $type=$row['ef_type'];
    $priority=$row['ef_priority'];
    $assign_to=$row['ef_assign_to'];
    $rid=$row['ef_rid'];
    $version=$row['ef_version'];
    $component=$row['ef_component'];
    $source=$row['ef_source'];
    $risk=$row['ef_risk'];
    $complexity=$row['ef_complexity'];
    $weight=$row['ef_weight'];
    $open_points=$row['ef_open_points'];
    $keywords=$row['ef_keywords'];
    $satisfaction=$row['ef_satisfaction'];
    $dissatisfaction=$row['ef_dissatisfaction'];
    $depends=$row['ef_depends'];
    $conflicts=$row['ef_conflicts'];
    $author=$row['ef_author'];
    $url=$row['ef_url'];
    $parent=$row['ef_parent'];
    $position=$row['ef_position'];
    $userfields=$row['ef_userfields'];
    $creation_date=$row['ef_creation_date'];
    $last_change=$row['ef_last_change'];
    $accepted_date=$row['ef_accepted_date'];
    $accepted_user=$row['ef_accepted_user'];
    $comments=$row['ef_comments'];
   }
 }  
 
 ?>
<table border="0" width="100%">
  <tr valign="top">
    <td>
      <table border="0" cellpadding="2" cellspacing="2" class="content" width="100%">
	  <form method="post" name="f1" action="">
          <tr class="gray">
	    <td colspan="5" align="center" title="<?=$lng[35][1]?>"><b><?=$lng[35][1]?></b></td>
	  </tr>
	  <tr class="gray">
	    <td align="left">&nbsp;&nbsp;<input <?if ($description==1 || $description=="") echo "checked";?> type="checkbox" name="description" value="1">&nbsp;<?=$lng[15][5]?></td>
	    <td align="left">&nbsp;&nbsp;<input <?if ($project==1 || $project=="") echo "checked";?> type="checkbox" name="project" value="1">&nbsp;<?=$lng[15][3]?></td>
	    <td align="left">&nbsp;&nbsp;<input <?if ($subproject==1 || $subproject=="") echo "checked";?> type="checkbox" name="subproject" value="1">&nbsp;<?=$lng[15][97]?></td>
	    <td align="left">&nbsp;&nbsp;<input <?if ($release==1 || $release=="") echo "checked";?> type="checkbox" name="release" value="1">&nbsp;<?=$lng[15][24]?></td>
	    <td align="left">&nbsp;&nbsp;<input <?if ($test_case==1 || $test_case=="") echo "checked";?> type="checkbox" name="test_case" value="1">&nbsp;<?=$lng[15][103]?></td>
	  </tr>
	  <tr class="gray">
	    <td align="left">&nbsp;&nbsp;<input <?if ($stakeholder==1 || $stakeholder=="") echo "checked";?> type="checkbox" name="stakeholder" value="1">&nbsp;<?=$lng[15][76]?></td>
	    <td align="left">&nbsp;&nbsp;<input <?if ($glossary==1 || $glossary=="") echo "checked";?> type="checkbox" name="glossary" value="1">&nbsp;<?=$lng[15][86]?></td>
	    <td align="left">&nbsp;&nbsp;<input <?if ($state==1 || $state=="") echo "checked";?> type="checkbox" name="state" value="1">&nbsp;<?=$lng[15][58]?></td>
	    <td align="left">&nbsp;&nbsp;<input <?if ($type==1 || $type=="") echo "checked";?> type="checkbox" name="type" value="1">&nbsp;<?=$lng[15][11]?></td>
	    <td align="left">&nbsp;&nbsp;<input <?if ($priority==1 || $priority=="") echo "checked";?> type="checkbox" name="priority" value="1">&nbsp;<?=$lng[15][13]?></td>
	  </tr>
	  <tr class="gray">
	    <td align="left">&nbsp;&nbsp;<input <?if ($assign_to==1 || $assign_to=="") echo "checked";?> type="checkbox" name="assign_to" value="1">&nbsp;<?=$lng[15][30]?></td>
	    <td align="left">&nbsp;&nbsp;<input <?if ($rid==1 || $rid=="") echo "checked";?> type="checkbox" name="rid" value="1">&nbsp;<?=$lng[15][45]?></td>
	    <td align="left">&nbsp;&nbsp;<input <?if ($version==1 || $version=="") echo "checked";?> type="checkbox" name="version" value="1">&nbsp;<?=$lng[15][34]?></td>
	    <td align="left">&nbsp;&nbsp;<input <?if ($component==1 || $component=="") echo "checked";?> type="checkbox" name="component" value="1">&nbsp;<?=$lng[15][40]?></td>
	    <td align="left">&nbsp;&nbsp;<input <?if ($source==1 || $source=="") echo "checked";?> type="checkbox" name="source" value="1">&nbsp;<?=$lng[15][41]?></td>
	  </tr>
	  <tr class="gray">
	    <td align="left">&nbsp;&nbsp;<input <?if ($risk==1 || $risk=="") echo "checked";?> type="checkbox" name="risk" value="1">&nbsp;<?=$lng[15][42]?></td>
	    <td align="left">&nbsp;&nbsp;<input <?if ($complexity==1 || $complexity=="") echo "checked";?> type="checkbox" name="complexity" value="1">&nbsp;<?=$lng[15][43]?></td>
	    <td align="left">&nbsp;&nbsp;<input <?if ($weight==1 || $weight=="") echo "checked";?> type="checkbox" name="weight" value="1">&nbsp;<?=$lng[15][88]?></td>
	    <td align="left">&nbsp;&nbsp;<input <?if ($open_points==1 || $open_points=="") echo "checked";?> type="checkbox" name="open_points" value="1">&nbsp;<?=$lng[15][44]?></td>
	    <td align="left">&nbsp;&nbsp;<input <?if ($keywords==1 || $keywords=="") echo "checked";?> type="checkbox" name="keywords" value="1">&nbsp;<?=$lng[15][107]?></td>
	  </tr>
	  <tr class="gray">
	    <td align="left">&nbsp;&nbsp;<input <?if ($satisfaction==1 || $satisfaction=="") echo "checked";?> type="checkbox" name="satisfaction" value="1">&nbsp;<?=$lng[15][82]?></td>
	    <td align="left">&nbsp;&nbsp;<input <?if ($dissatisfaction==1 || $dissatisfaction=="") echo "checked";?> type="checkbox" name="dissatisfaction" value="1">&nbsp;<?=$lng[15][84]?></td>
	    <td align="left">&nbsp;&nbsp;<input <?if ($depends==1 || $depends=="") echo "checked";?> type="checkbox" name="depends" value="1">&nbsp;<?=$lng[15][78]?></td>
	    <td align="left">&nbsp;&nbsp;<input <?if ($conflicts==1 || $conflicts=="") echo "checked";?> type="checkbox" name="conflicts" value="1">&nbsp;<?=$lng[15][80]?></td>
	    <td align="left">&nbsp;&nbsp;<input <?if ($author==1 || $author=="") echo "checked";?> type="checkbox" name="author" value="1">&nbsp;<?=$lng[15][16]?></td>
	  </tr>
	  <tr class="gray">
	    <td align="left">&nbsp;&nbsp;<input <?if ($url==1 || $url=="") echo "checked";?> type="checkbox" name="url" value="1">&nbsp;<?=$lng[15][14]?></td>
	    <td align="left">&nbsp;&nbsp;<input <?if ($parent==1 || $parent=="") echo "checked";?> type="checkbox" name="parent" value="1">&nbsp;<?=$lng[15][35]?></td>
	    <td align="left">&nbsp;&nbsp;<input <?if ($position==1 || $position=="") echo "checked";?> type="checkbox" name="position" value="1">&nbsp;<?=$lng[15][36]?></td>
	    <td align="left">&nbsp;&nbsp;<input <?if ($userfields==1 || $userfields=="") echo "checked";?> type="checkbox" name="userfields" value="1">&nbsp;<?=$lng[15][113]?></td>
	    <td align="left">&nbsp;&nbsp;<input <?if ($creation_date==1 || $creation_date=="") echo "checked";?> type="checkbox" name="creation_date" value="1">&nbsp;<?=$lng[15][17]?></td>
	  </tr>
	  <tr class="gray">
	    <td align="left">&nbsp;&nbsp;<input <?if ($last_change==1 || $last_change=="") echo "checked";?> type="checkbox" name="last_change" value="1">&nbsp;<?=$lng[15][18]?></td>
	    <td align="left">&nbsp;&nbsp;<input <?if ($accepted_date==1 || $accepted_date=="") echo "checked";?> type="checkbox" name="accepted_date" value="1">&nbsp;<?=$lng[15][19]?></td>
	    <td align="left">&nbsp;&nbsp;<input <?if ($accepted_user==1 || $accepted_user=="") echo "checked";?> type="checkbox" name="accepted_user" value="1">&nbsp;<?=$lng[15][20]?></td>
	    <td align="left" colspan="2">&nbsp;&nbsp;<input <?if ($comments==1 || $comments=="") echo "checked";?> type="checkbox" name="comments" value="1">&nbsp;<?=$lng[15][114]?></td>
	  </tr>
	  <tr class="gray">
	    <td align="center" colspan="5">
	      <input type="button" value="<?=$lng[35][2]?>" onclick="document.forms['f1'].action='pdf_review.php';document.forms['f1'].submit();">&nbsp;&nbsp;
	      <?
	       if ($_SESSION['rights']=="1" || $_SESSION['rights']=="2" || $_SESSION['rights']=="3" || $_SESSION['rights']=="4" || $_SESSION['rights']=="5") 
	        {
	         ?>
		   <select name="templates" onchange="document.forms['f1'].submit();">
		   <option value=''><?=$lng[35][5]?>
		     <?
		        $cnt=0;
		       $query="select * from export_fields where ef_uid=".$_SESSION['uid'];
		       $rs = mysql_query($query) or die(mysql_error());
		       while($row=mysql_fetch_array($rs)) 
		        {
		         ?>
		         <option value='<?=$row['ef_id']?>' <?if ($row['ef_id']==$templates) echo "selected";?>><?=$row['ef_name']?> 
		         <?
		        }
		     ?>
		   </select>&nbsp;&nbsp; 
		   <input type="button" value="<?=$lng[35][6]?>" onclick="document.forms['f1'].act.value='save';document.forms['f1'].submit();">&nbsp;&nbsp;
		   <?if ($templates!="") {?><input type="button" value="<?=$lng[35][7]?>" onclick="document.forms['f1'].act.value='delete';document.forms['f1'].submit();">&nbsp;&nbsp;<?}?>
	      <?}?>     
	    </td>
	  </tr>
	  <input type="hidden" name="r_id" value="<?=$r_id?>">
	  <input type="hidden" name="filter1" value="<?=$filter1?>">
	  <input type="hidden" name="filter2" value="<?=$filter2?>">
	  <input type="hidden" name="filter4" value="<?=$filter4?>">
	  <input type="hidden" name="filter5" value="<?=$filter5?>">
	  <input type="hidden" name="filter6" value="<?=$filter6?>">
	  <input type="hidden" name="filter7" value="<?=$filter7?>">
	  <input type="hidden" name="filter8" value="<?=$filter8?>">
	  <input type="hidden" name="srch" value="<?=$srch?>">
	  <input type="hidden" name="act" value="">
	  <input type="hidden" name="order" value="<?=$order?>">
	  <input type="hidden" name="ids" value="<?=($ids2!="")?$ids2:$ids?>">
	  <input type="hidden" name="mode" value="<?=$mode?>">
	  </form>
	</table>
    </td> 	 
  </tr>
</table>


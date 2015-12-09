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
// Page: "View all" - viewimg all requirements

include("ajax.php");
include("ini/txts/".$_SESSION['chlang']."/state.php");
if ( ! isset($action) ) $action = "";
if ($action=="undo")
 {
  $query22="select count(*), th_current from tree_history where th_u_id=".$_SESSION['uid']." and th_p_id=".$_SESSION['projects']." and th_date>=DATE_SUB(now(), INTERVAL 1 HOUR) group by th_current";
  $rs22 = mysql_query($query22) or die(mysql_error());
  if($row22=mysql_fetch_array($rs22)) 
   {
    if ($row22[0]>$row22[1]) 
     {
      $query2="select * from tree_history where th_u_id=".$_SESSION['uid']." and th_p_id=".$_SESSION['projects']." and th_date>=DATE_SUB(now(), INTERVAL 1 HOUR) order by th_id desc limit ".$row22[1].",1";
      $rs2 = mysql_query($query2) or die(mysql_error());
      if($row2=mysql_fetch_array($rs2)) 
       {
        $th_parent_new=$row2['th_parent_new'];
        $th_parent_new_pos=$row2['th_parent_new_pos'];
        $th_parent_old=$row2['th_parent_old'];
        $th_parent_old_pos=$row2['th_parent_old_pos'];
        $th_r_id=$row2['th_r_id'];
        $th_rh_id=$row2['th_rh_id'];

        //delete from requirement history
        //$query2="delete from requirements_history where r_id=".$th_rh_id;
        //mysql_query($query2) or die(mysql_error());
    
        if ($th_parent_new!=$th_parent_old)
         {
          //changing positions of nodes of the new parent
          $query2="update requirements set r_pos=r_pos+1 where r_pos>=".$th_parent_old_pos." and r_parent_id=".$th_parent_old;
          $rs2 = mysql_query($query2) or die(mysql_error());

          //changing positions of nodes of the old parent
          $query2="update requirements set r_pos=r_pos-1 where r_pos>".$th_parent_new_pos." and r_parent_id=".$th_parent_new;
          $rs2 = mysql_query($query2) or die(mysql_error());

          $query2="update requirements set r_parent_id=".$th_parent_old.", r_pos=".$th_parent_old_pos." where r_id=".$th_r_id;
          mysql_query($query2) or die(mysql_error());
         }
        else //if parent's positions changed only
         {
          if ($th_parent_old_pos<$th_parent_new_pos) $query2="update requirements set r_pos=r_pos+1 where (r_pos>=".$th_parent_old_pos." and r_pos<".$th_parent_new_pos.") and r_parent_id=".$th_parent_new." and r_p_id=".$_SESSION['projects'];
          else $query2="update requirements set r_pos=r_pos-1 where (r_pos<=".$th_parent_old_pos." and r_pos>".$th_parent_new_pos.") and r_parent_id=".$th_parent_new." and r_p_id=".$_SESSION['projects'];
          mysql_query($query2) or die(mysql_error());
          
          $query2="update requirements set r_pos=".$th_parent_old_pos." where r_id=".$th_r_id;
          mysql_query($query2) or die(mysql_error());
         } 
         
        //increment current
        $query2="update tree_history set th_current=th_current+1 where th_u_id=".$_SESSION['uid']." and th_p_id=".$_SESSION['projects']." and th_date>=DATE_SUB(now(), INTERVAL 1 HOUR)";
        mysql_query($query2) or die(mysql_error());
       } 
     }
    else $err=$lng[17][66];
   }  
  else $err=$lng[17][66]; 
 }
if ($action=="redo")
 {
  $query22="select th_current from tree_history where th_u_id=".$_SESSION['uid']." and th_p_id=".$_SESSION['projects']." and th_date>=DATE_SUB(now(), INTERVAL 1 HOUR)";
  $rs22 = mysql_query($query22) or die(mysql_error());
  if($row22=mysql_fetch_array($rs22)) 
   {
    if ($row22[0]>0) 
     {
      $query2="select * from tree_history where th_u_id=".$_SESSION['uid']." and th_p_id=".$_SESSION['projects']." and th_date>=DATE_SUB(now(), INTERVAL 1 HOUR) order by th_id desc limit ".($row22[0]-1).",1";
      $rs2 = mysql_query($query2) or die(mysql_error());
      if($row2=mysql_fetch_array($rs2)) 
       {
        $th_parent_new=$row2['th_parent_new'];
        $th_parent_new_pos=$row2['th_parent_new_pos'];
        $th_parent_old=$row2['th_parent_old'];
        $th_parent_old_pos=$row2['th_parent_old_pos'];
        $th_r_id=$row2['th_r_id'];
        $th_rh_id=$row2['th_rh_id'];

        //insert into requirement history
        //$query2="delete from requirements_history where r_id=".$th_rh_id;
        //mysql_query($query2) or die(mysql_error());
    
        if ($th_parent_new!=$th_parent_old)
         {
          //changing positions of nodes of the new parent
          $query2="update requirements set r_pos=r_pos+1 where r_pos>=".$th_parent_new_pos." and r_parent_id=".$th_parent_new;
          $rs2 = mysql_query($query2) or die(mysql_error());

          //changing positions of nodes of the old parent
          $query2="update requirements set r_pos=r_pos-1 where r_pos>".$th_parent_old_pos." and r_parent_id=".$th_parent_old;
          $rs2 = mysql_query($query2) or die(mysql_error());

          $query2="select max(r_pos) from requirements where r_parent_id=".$th_parent_new." and r_p_id=".$_SESSION['projects'];
          $rs2 = mysql_query($query2) or die(mysql_error());
          if($row2=mysql_fetch_array($rs2)) $max=$row2[0];
          if ($max=="") $max=0;

          $query2="update requirements set r_parent_id=".$th_parent_new.", r_pos=".($max+1)." where r_id=".$th_r_id;
          mysql_query($query2) or die(mysql_error());
         }
        else //if parent's positions changed only
         {
          if ($th_parent_old_pos>$th_parent_new_pos) $query2="update requirements set r_pos=r_pos+1 where (r_pos<".$th_parent_old_pos." and r_pos>=".$th_parent_new_pos.") and r_parent_id=".$th_parent_new." and r_p_id=".$_SESSION['projects'];
          else $query2="update requirements set r_pos=r_pos-1 where (r_pos>".$th_parent_old_pos." and r_pos<=".$th_parent_new_pos.") and r_parent_id=".$th_parent_new." and r_p_id=".$_SESSION['projects'];
          mysql_query($query2) or die(mysql_error());
          
          $query2="update requirements set r_pos=".$th_parent_new_pos." where r_id=".$th_r_id;
          mysql_query($query2) or die(mysql_error());
         } 

        //increment current
        $query2="update tree_history set th_current=th_current-1 where th_u_id=".$_SESSION['uid']." and th_p_id=".$_SESSION['projects']." and th_date>=DATE_SUB(now(), INTERVAL 1 HOUR)";
        mysql_query($query2) or die(mysql_error());
       } 
     }
    else $err=$lng[17][67];
   }  
   else $err=$lng[17][67]; 
 }

if ($action=="update" && $ids!="" && ($r_assigned_u_id!="" || $r_p_id!="" || $r_s_id!="" || $r_release!="" || $r_state!="" || $r_priority!="" || $r_source!=""))
 {
  //checking if the project is not retired
  //history
  $query="select r.*,p.p_status from requirements r left outer join projects p on r.r_p_id=p.p_id where r.r_id in (".$ids."0)";
  $rs = mysql_query($query) or die(mysql_error());
  while($row=mysql_fetch_array($rs)) 
   {
    if ($row['p_status']==2) $tmp="<span class='error'>".$lng[17][43]."</span><br><br>";
    else
     {
      $query="insert into requirements_history (r_parent_id, r_p_id,r_release, r_c_id, r_s_id, r_stakeholder, r_glossary, r_keyword, r_u_id, r_assigned_u_id, r_name, r_desc, r_state, r_type_r, r_priority, r_valid, r_link, r_satisfaction, r_dissatisfaction, r_conflicts, r_depends, r_component, r_source, r_risk, r_complexity, r_weight, r_points, r_creation_date, r_change_date, r_accept_date, r_accept_user, r_version, r_save_date, r_save_user, r_parent_id2, r_pos, r_stub, r_keywords, r_userfield1, r_userfield2, r_userfield3, r_userfield4, r_userfield5, r_userfield6) values ('".$row['r_id']."','".escapeChars($row['r_p_id'])."','".escapeChars($row['r_release'])."','".escapeChars($row['r_c_id'])."','".escapeChars($row['r_s_id'])."','".escapeChars($row['r_stakeholder'])."','".escapeChars($row['r_glossary'])."','".escapeChars($row['r_keyword'])."','".escapeChars($row['r_u_id'])."','".$row['r_assigned_u_id']."','".escapeChars($row['r_name'])."','".escapeChars($row['r_desc'])."','".escapeChars($row['r_state'])."','".escapeChars($row['r_type_r'])."','".escapeChars($row['r_priority'])."','".escapeChars($row['r_valid'])."','".escapeChars($row['r_link'])."','".escapeChars($row['r_satisfaction'])."','".escapeChars($row['r_dissatisfaction'])."','".escapeChars($row['r_conflicts'])."','".escapeChars($row['r_depends'])."','".escapeChars($row['r_component'])."','".escapeChars($row['r_source'])."','".escapeChars($row['r_risk'])."','".escapeChars($row['r_complexity'])."','".escapeChars($row['r_weight'])."','".escapeChars($row['r_points'])."','".escapeChars($row['r_creation_date'])."','".escapeChars($row['r_change_date'])."','".escapeChars($row['r_accept_date'])."','".escapeChars($row['r_accept_user'])."','".escapeChars($row['r_version'])."',DATE_ADD( NOW( ) , INTERVAL - ".TIME_DIFF_HOURS." HOUR),'".$_SESSION['uid']."','".escapeChars($row['r_parent_id'])."','".escapeChars($row['r_pos'])."','".escapeChars($row['r_stub'])."','".escapeChars($row['r_keywords'])."','".escapeChars($row['r_userfield1'])."','".escapeChars($row['r_userfield2'])."','".escapeChars($row['r_userfield3'])."','".escapeChars($row['r_userfield4'])."','".escapeChars($row['r_userfield5'])."','".escapeChars($row['r_userfield6'])."')";
      mysql_query($query) or die(mysql_error());
   
      //subproject
      if ($r_s_id!="") $q.=", r_s_id='".escapeChars($r_s_id)."' ";
      
      //subproject
      if ($r_release=="0") $q.=", r_release=''";
      elseif ($r_release!="") $q.=", r_release=concat(r_release,'".escapeChars($r_release).",')";
      
      //assigned user
      if ($r_assigned_u_id!="") $q.=", r_assigned_u_id='".escapeChars($r_assigned_u_id)."' ";
      
      //project
      if ($r_p_id!="") $q.=", r_p_id='".escapeChars($r_p_id)."' ";
    
      //state
      //if ($_SESSION['rights']==1 || $_SESSION['rights']==2 || $_SESSION['rights']==3) 
      if ($r_state!="") 
       {
        //if accepted date and user should be added
        if ($r_state=="1" && $row['r_state']!=1) $q.=", r_state='".escapeChars($r_state)."', r_accept_date=DATE_ADD( NOW( ) , INTERVAL - ".TIME_DIFF_HOURS." HOUR), r_accept_user='".$_SESSION['uid']."'";
        else $q.=", r_state='".escapeChars($r_state)."'";     
       }
   
      //priority
      if ($r_priority!="") $q.=", r_priority='".escapeChars($r_priority)."' ";

      //source
      if ($r_source!="") $q.=", r_source='".escapeChars($r_source)."' ";

      $query="update requirements set r_version=r_version+1, r_change_date=DATE_ADD( NOW( ) , INTERVAL - ".TIME_DIFF_HOURS." HOUR) ".$q." where r_id=".$row['r_id'];
      mysql_query($query) or die(mysql_error());
     } 
   }   
  }
?>
<?if ( ! isset($tmp) ) $tmp = ""; if ($tmp!="") echo $tmp;?>
<?
//if tree view
if ($_SESSION['_viewalltype']==0) 
 {
  //check if logged
  if (!($_SESSION['rights']=="1" || $_SESSION['rights']=="2" || $_SESSION['rights']=="3" || $_SESSION['rights']=="4" || $_SESSION['rights']=="5")) header("Location:index.php");

  $current=0;$all_c=0;
  
  //getting info from undo/redoes
  $query2="select count(*), th_current from tree_history where th_u_id=".$_SESSION['uid']." and th_p_id=".$_SESSION['projects']." and th_date>=DATE_SUB(now(), INTERVAL 1 HOUR) group by th_current";
  $rs2 = mysql_query($query2) or die(mysql_error());
  if($row2=mysql_fetch_array($rs2)) 
   {
    $all_c=$row2[0];
    $current=$row2[1];
   }
 
  if ($current>$all_c)      
   {
    $query2="update tree_history set th_current=".$all_c." where th_u_id=".$_SESSION['uid']." and th_p_id=".$_SESSION['projects']." and th_date>=DATE_SUB(now(), INTERVAL 1 HOUR)";
    mysql_query($query2) or die(mysql_error());
   }
  ?>
  
  <form method="post" action="" name="req">
<input type=hidden name=from value="<?=$from?>">
<input type=hidden name=order value="">
<input type=hidden name=ids value="<?=$ids?>">
<input type=hidden name=action value="">
<input type=hidden name=filter11 value="<?=$filter11?>">
<table border="0" width="100%">
  <tr valign="top">
    <td valign="top">
      <table border="0" cellpadding="2" cellspacing="2" class="content" width="50%">
	 <tr>
	   <td valign="top" align="left" class="gray" nowrap>
	     &nbsp;&nbsp;<?=$lng[17][3]?>: <select name=filter1>
		<option value="">--
		<?
	          //users list	        
	          $query4="select u_name, u_id from users where u_rights in (0,1,2,3,4,5) order by u_name asc";
		  $rs4 = mysql_query($query4) or die(mysql_error());	        
	          while($row4=mysql_fetch_array($rs4)) 
		   {
		    echo "<option value='".$row4['u_id']."'>".htmlspecialchars($row4['u_name']);
		   }
	        ?>
	     </select>
	       <script>
		 for (i=0;i<document.forms.req.filter1.length;i++)
		   if (document.forms.req.filter1.options[i].value=='<?=$filter1?>')
		      document.forms.req.filter1.options[i].selected=true;
	       </script>	    
	     	  
	     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$lng[17][16]?>: <select name=filter2>
		<option value="">--
		<?//state list	        
	          include("ini/txts/".$_SESSION['chlang']."/state.php");
		  echo makeSelect($state_array,$r_state);
	        ?>
	     </select>
	       <script>
		 for (i=0;i<document.forms.req.filter2.length;i++)
		   if (document.forms.req.filter2.options[i].value=='<?=$filter2?>')
		      document.forms.req.filter2.options[i].selected=true;
	       </script>	    
	    
	     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$lng[17][5]?>: <select name=filter4>
		 <option value="">--
		 <option value='1'>1 <?=$lng[15][101]?>
		 <option value='2'>2
		 <option value='3'>3
		 <option value='4'>4
		 <option value='5'>5
		 <option value='6'>6
		 <option value='7'>7
		 <option value='8'>8
		 <option value='9'>9
		 <option value='10'>10 <?=$lng[15][102]?>
	       </select>
	       <script>
		 for (i=0;i<document.forms.req.filter4.length;i++)
		   if (document.forms.req.filter4.options[i].value=='<?=$filter4?>')
		      document.forms.req.filter4.options[i].selected=true;
	       </script>
	       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$lng[17][4]?>: 
	       <select name="filter6">
	       <option value="">--
	       <?
		//users list
		$query4="select u_name, u_id from users where u_rights in (0,1,2,3,4,5) order by u_name asc";
		$rs4 = mysql_query($query4) or die(mysql_error());
		        
		while($row4=mysql_fetch_array($rs4)) 
		 {
		  if ($row4['u_id']==$filter6) echo "<option value='".$row4['u_id']."' selected>".htmlspecialchars($row4['u_name']);
		  else echo "<option value='".$row4['u_id']."'>".htmlspecialchars($row4['u_name']);
		 }
		?>
	      </select> 
	       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$lng[17][36]?>: 
	       <select name="filter7">
	       <option value="">--
	       <?
		//releases list
		//$query4="select * from releases order by r_name asc";
	        $query4="select r.* from releases r left outer join project_releases pr on r.r_id=pr.pr_r_id where (pr.pr_p_id in ('".$project_list."') or r.r_global=1 ) order by r.r_name asc";
		$rs4 = mysql_query($query4) or die(mysql_error());
		        
		while($row4=mysql_fetch_array($rs4)) 
		 {
		  if ($row4['r_id']==$filter7) echo "<option value='".$row4['r_id']."' selected>".htmlspecialchars($row4['r_name']);
		  else echo "<option value='".$row4['r_id']."'>".htmlspecialchars($row4['r_name']);
		 }
		?>
	      </select> 
	   </td>	   	 
	 </tr>
	 <tr>
	   <td align="left" class="gray" nowrap valign="top">
	     &nbsp;&nbsp;<?=$lng[17][42]?>: <select name=filter8>
	       <option value="">--
	       <?
		//components list
		//$query4="select * from components order by c_name asc";
	        $query4="select c.* from components c left outer join project_components pco on c.c_id=pco.pco_c_id where (pco.pco_p_id in ('".$project_list."') or c.c_global=1 ) order by c.c_name asc";
		$rs4 = mysql_query($query4) or die(mysql_error());
		        
		while($row4=mysql_fetch_array($rs4)) 
		 {
		  if ($row4['c_id']==$filter8) echo "<option value='".$row4['c_id']."' selected>".htmlspecialchars($row4['c_name']);
		  else echo "<option value='".$row4['c_id']."'>".htmlspecialchars($row4['c_name']);
		 }
		?>
	     </select>
	       <script>
		 for (i=0;i<document.forms.req.filter8.length;i++)
		   if (document.forms.req.filter8.options[i].value=='<?=$filter8?>')
		      document.forms.req.filter8.options[i].selected=true;
	       </script>	    
	     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$lng[17][45]?>: <select name=filter9>
		<option value="0"><?=$lng[17][46]?>
		<option value="1"><?=$lng[17][47]?>		
		<option value="2"><?=$lng[17][51]?>		
	     </select>
	       <script>
		 for (i=0;i<document.forms.req.filter9.length;i++)
		   if (document.forms.req.filter9.options[i].value=='<?=$filter9?>')
		      document.forms.req.filter9.options[i].selected=true;
	       </script>
	       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$lng[17][48]?>: <select name=filter10>
		<option value="0"><?=$lng[17][49]?>
		<option value="1"><?=$lng[17][50]?>		
	     </select>
	       <script>
		 for (i=0;i<document.forms.req.filter10.length;i++)
		   if (document.forms.req.filter10.options[i].value=='<?=$filter10?>')
		      document.forms.req.filter10.options[i].selected=true;
	       </script>	    
	       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$lng[2][35]?>: <select name=filter18>
	       <option value="">--
	       <?
		//test cases list
		//$query4="select * from cases order by c_name asc";
	        $query4="select c.* from cases c left outer join project_cases pc on c.c_id=pc.pc_c_id where (pc.pc_p_id in ('".$project_list."') or c.c_global=1 ) order by c.c_name asc";
		$rs4 = mysql_query($query4) or die(mysql_error());
		        
		while($row4=mysql_fetch_array($rs4)) 
		 {
		  if ($row4['c_id']==$filter18) echo "<option value='".$row4['c_id']."' selected>".htmlspecialchars($row4['c_name']);
		  else echo "<option value='".$row4['c_id']."'>".htmlspecialchars($row4['c_name']);
		 }
		?>
	     </select>
	       <script>
		 for (i=0;i<document.forms.req.filter18.length;i++)
		   if (document.forms.req.filter18.options[i].value=='<?=$filter18?>')
		      document.forms.req.filter18.options[i].selected=true;
	       </script>	    
	   </td>	   	 
	 </tr>
	 <tr valign="top">
	   <td align="left" class="gray" nowrap valign="top">
	      <table border=0 cellspacing=0 cellpadding=0>
	        <tr valign="top">
	          <td valign="top" rowspan="2">
		       &nbsp;&nbsp;<?=$lng[17][52]?>:&nbsp;&nbsp; 
		  </td>
		  <td rowspan="2">   
		      <select name=filter11_tmp multiple size=3>  
		       <?
			//keywords list
			//$query4="select * from keywords order by k_name asc";
	       		$query4="select k.* from keywords k left outer join project_keywords pk on k.k_id=pk.pk_k_id where (pk.pk_p_id in ('".$project_list."') or k.k_global=1 ) order by k.k_name asc";
			$rs4 = mysql_query($query4) or die(mysql_error());
			        
			while($row4=mysql_fetch_array($rs4)) 
			 {
			  if (strstr(",".$filter11,",".$row4['k_id'].",")) echo "<option value='".$row4['k_id']."' selected>".htmlspecialchars($row4['k_name']);
			  else echo "<option value='".$row4['k_id']."'>".htmlspecialchars($row4['k_name']);
			 }
			?>
		     </select>
		       <script>
			 for (i=0;i<document.forms.req.filter11.length;i++)
			   if (document.forms.req.filter11.options[i].value=='<?=$filter11?>')
			      document.forms.req.filter11.options[i].selected=true;
		       </script>
		  </td>   
		  <td>  	    
	             &nbsp;&nbsp;<img src="x.gif" width=8 height=1><?=$lng[17][53]?>&nbsp;<input type="radio" name="filter12" value="<?=$lng[17][53]?>" <?if ($filter12=="" || $filter12==$lng[17][53]) echo "checked";?>>
	          </td>
	          <td rowspan="2">  	    
			     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$lng[17][55]?>: 
		             <select name=filter13>
				<option value="">--
				<option value="1"><?=$lng[17][56]?>
				<option value="2"><?=$lng[17][57]?>
				<option value="3"><?=$lng[17][58]?>
				<option value="5"><?=$lng[17][60]?>
				<option value="6"><?=$lng[17][61]?>
			     </select>
		       <script>
			 for (i=0;i<document.forms.req.filter13.length;i++)
			   if (document.forms.req.filter13.options[i].value=='<?=$filter13?>')
			      document.forms.req.filter13.options[i].selected=true;
		       </script>
		  </td> 
	          <td rowspan="2">  
		       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$lng[17][72]?>: <select name=filter14>
		       <option value="">--
		       <?
			//users list
			$query4="select u_name, u_id from users order by u_name asc";
			$rs4 = mysql_query($query4) or die(mysql_error());
			        
			while($row4=mysql_fetch_array($rs4)) 
			 {
			  if ($row4['u_id']==$filter14) echo "<option value='".$row4['u_id']."' selected>".htmlspecialchars($row4['u_name']);
			  else echo "<option value='".$row4['u_id']."'>".htmlspecialchars($row4['u_name']);
			 }
			?>
		     </select>
	          	    
	          </td> 
	        </tr>  
	        <tr>
	          <td>
	            &nbsp;&nbsp;<?=$lng[17][54]?>&nbsp;<input type="radio" name="filter12" value="<?=$lng[17][54]?>"<?if ($filter12==$lng[17][54]) echo "checked";?>>
	          </td>
	       </tr> 
	     </table> 
	   </td>	   	 
	 </tr>
	 <tr>
	   <td align="left" class="gray" nowrap valign="top">
	            &nbsp;&nbsp;<?=$lng[17][73]?>: <input type="text" size=2 name="filter15_3" value="<?=stripslashes(htmlspecialchars($filter15_3))?>"> <?=$lng[17][83]?> <input type="text" size=2 name="filter15_1" value="<?=stripslashes(htmlspecialchars($filter15_1))?>">(<?=$lng[17][74]?>) <input type="text" size=2 name="filter15_2" value="<?=stripslashes(htmlspecialchars($filter15_2))?>">(<?=$lng[17][75]?>)

	            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$lng[17][18]?>: <input type="text" size=30 name="filter5" value="<?=stripslashes(htmlspecialchars($filter5))?>">&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" onclick="if (!sub_search()) return false;" value="<?=$lng[17][17]?>">&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" onclick="document.location.href='index.php?inc=view_all'" value="<?=$lng[17][79]?>">
	   </td>	   	 
	 </tr>
      </table>
    </td>
  </tr>  
  <tr valign="top">
    <td>
      <br>
    </td>
  </tr> 
</table>
 	 <?
	  //sortable columns
          if ($order=="") $order="r_change_date desc";

	  //search
	  if ($filter1!="") $search.=" and r.r_u_id=".$filter1;
	  if ($filter2!="") $search.=" and r.r_state=".$filter2;
	  if ($filter4!="") $search.=" and r.r_priority=".$filter4;
	  if ($filter5!="") 
	   {
	    $query4="select * from comments where c_text like('%".escapeChars($filter5)."%')";
	    $rs4 = mysql_query($query4) or die(mysql_error());
	    $new_pr29="(0";
	    while($row4=mysql_fetch_array($rs4)) 
	     {
	      $new_pr29.=",".$row4['c_r_id'];
	     } 
	    $new_pr29.=")";	 
	    //$search.=" and r.r_id in ".$new_pr29;
            $search.=" and (r.r_id in ".$new_pr29." or (r.r_desc like ('%".escapeChars($filter5)."%') or r.r_source like ('%".escapeChars($filter5)."%') or r.r_name like ('%".escapeChars($filter5)."%') or r.r_userfield1 like ('%".escapeChars($filter5)."%') or r.r_userfield2 like ('%".escapeChars($filter5)."%') or r.r_userfield3 like ('%".escapeChars($filter5)."%') or r.r_userfield4 like ('%".escapeChars($filter5)."%') or r.r_userfield5 like ('%".escapeChars($filter5)."%') or r.r_userfield6 like ('%".escapeChars($filter5)."%')))";
           }
	  
	  if ($filter6!="") $search.=" and r.r_assigned_u_id=".$filter6;
	  if ($filter7!="") 
	   {
	    $search.=" and CONCAT(',',r.r_release) like ('%,".$filter7.",%')"; 
	    /*$query4="select * from project_releases where pr_r_id=".$filter7;
	    $rs4 = mysql_query($query4) or die(mysql_error());
	    $new_pr="(0";
	    while($row4=mysql_fetch_array($rs4)) 
	     {
	      $new_pr.=",".$row4['pr_p_id'];
	     } 
	    $new_pr.=")";	 
	    $search.=" and r.r_p_id in ".$new_pr;*/
	   } 
	  if ($filter8!="") $search.=" and CONCAT(',',r.r_component) like ('%,".$filter8.",%')"; 
	  if ($filter9=="1") $search.=" and r.r_stub=".$filter9;
	  elseif ($filter9=="2") $search.=" and r.r_stub=".$filter9;
	  if ($filter10=="1") 
	   {
	    $query4="select * from comments where c_question=1";
	    $rs4 = mysql_query($query4) or die(mysql_error());
	    $new_pr2="(0";
	    while($row4=mysql_fetch_array($rs4)) 
	     {
	      $new_pr2.=",".$row4['c_r_id'];
	     } 
	    $new_pr2.=")";	 
	    $search.=" and r.r_id in ".$new_pr2;
	   } 
	  if ($filter11!="") 
	   {
	    $cnt_k=0;
	    $search.=" and (";
	    $kwrds = explode(",", $filter11);
	    while (list ($key, $val) = each ($kwrds)) 
	     {
   	      if ($val!="")
   	       {
   	        if ($cnt_k==0) $search.=" CONCAT(',',r.r_keywords) like ('%,".$val.",%')";
   	        else $search.=" ".$filter12." CONCAT(',',r.r_keywords) like ('%,".$val.",%')";
   	        $cnt_k++;
   	       } 
             }
	    $search.=")";	    
	   }
	  if ($filter13!="") 
	   {
	    if ($filter13==1) $search.=" and r.r_u_id='0'";
	    elseif ($filter13==2) $search.=" and r.r_assigned_u_id='0'";
	    elseif ($filter13==3) $search.=" and r.r_component=''";
	    //elseif ($filter13==4) $search.=" and r.r_assigned_u_id='0'";
	    elseif ($filter13==5) $search.=" and r.r_keywords=''";
	    elseif ($filter13==6) $search.=" and r.r_c_id=''";
	   } 
	  if ($filter14!="") 
	   {
	    $query4="select * from comments where c_u_id=".$filter14;
	    $rs4 = mysql_query($query4) or die(mysql_error());
	    $new_pr29="(0";
	    while($row4=mysql_fetch_array($rs4)) 
	     {
	      $new_pr29.=",".$row4['c_r_id'];
	     } 
	    $new_pr29.=")";	 
	    $search.=" and r.r_id in ".$new_pr29;
	   } 
	  if ($filter15_3!="") $search.=" and (r.r_id=".$filter15_3.")";
	  else
	   {
	    if ($filter15_1!="" && $filter15_2!="") $search.=" and (r.r_id>=".$filter15_1." and r.r_id<=".$filter15_2.")";
	    if ($filter15_1!="" && $filter15_2=="") $search.=" and r.r_id>=".$filter15_1;
	    if ($filter15_1=="" && $filter15_2!="") $search.=" and r.r_id<=".$filter15_2;
           }
	  if ($filter18!="") $search.=" and CONCAT(',',r.r_c_id) like ('%,".$filter18.",%')"; 

	  //paging query, number of results (from the param.php file) per page displayed
	  $paging=$PPAGE;
	  $query="select count(*) from requirements r left outer join projects p on r.r_p_id=p.p_id where r.r_p_id in (".$project_list.")".$search;
	  $rs = mysql_query($query) or die(mysql_error());
	  if($row=mysql_fetch_array($rs)) $all_count=$row[0];
	  if ($from=="") $from=0; 
	  if ($from+$paging>$all_count) $cnt2=$all_count;
	  else $cnt2=$from+$paging;
	  
	  //getting requirements - recently modified
	  $cnt=0;
	  
	  $r_ids=",";
	  $query="select r.* from requirements r left outer join projects p on r.r_p_id=p.p_id left outer join users u on r.r_u_id=u.u_id left outer join users u2 on r.r_assigned_u_id=u2.u_id where r.r_p_id in (".$project_list.") ".$search;
          $rs = mysql_query($query) or die(mysql_error());
	  while($row=mysql_fetch_array($rs)) $r_ids.=$row['r_id'].",";
	  
	  $pieces = explode(",", $r_ids);
	  while (list ($key, $val) = each ($pieces)) if ($val!="") $parent_ids.=",".checkTree($val);

	
    ?>
  
<input type=hidden name=viewalltype value="tree">
<input type=hidden name=srch value="<?=$search?>">  
  </form>
  
  <table border=0 width="100%" cellspacing="0" cellpadding="0">
  <tr>
  <td align="left" valign="top" width="40%">
<?if ($err!="") echo "<span class='error'>".$err."</span><br><br>";?>
 <?
 /* $query2="select * from tree_history where th_u_id=".$_SESSION['uid']." and th_p_id=".$_SESSION['projects']." and th_date>=DATE_SUB(now(), INTERVAL 1 HOUR)";
  $rs2 = mysql_query($query2) or die(mysql_error());
  while($row2=mysql_fetch_array($rs2)) 
   {
    echo $row2['th_r_id']." - ".$row2['th_parent_old']." - ".$row2['th_parent_old_pos']." - ".$row2['th_parent_new']." - ".$row2['th_parent_new_pos']." - ".$row2['th_current']."<br>";
   }
  echo "<br>";
  $query2="select * from requirements where r_p_id=20 order by r_pos asc";
  $rs2 = mysql_query($query2) or die(mysql_error());
  while($row2=mysql_fetch_array($rs2)) 
   {
    echo $row2['r_id']."(".$row2['r_name'].") - ".$row2['r_pos']."<br>";
   }
  echo "<br>";
  */
 ?>
 
 
  <form action="" name="undo_form" method="post">
  <input type="hidden" name="par_id" value="<?=$par_id?>">
  <input type="hidden" name="inc" value="view_all">
  <input type="hidden" name="action" value="">
<input type=hidden name=filter1 value="<?=$filter1?>">
<input type=hidden name=filter2 value="<?=$filter2?>">
<input type=hidden name=filter4 value="<?=$filter4?>">
<input type=hidden name=filter5 value="<?=$filter5?>">
<input type=hidden name=filter6 value="<?=$filter6?>">
<input type=hidden name=filter7 value="<?=$filter7?>">
<input type=hidden name=filter8 value="<?=$filter8?>">
<input type=hidden name=filter9 value="<?=$filter9?>">
<input type=hidden name=filter10 value="<?=$filter10?>">
<input type=hidden name=filter11 value="<?=$filter11?>">
<input type=hidden name=filter12 value="<?=$filter12?>">
<input type=hidden name=filter13 value="<?=$filter13?>">
<input type=hidden name=filter14 value="<?=$filter14?>">
<input type=hidden name=filter15_1 value="<?=$filter15_1?>">
<input type=hidden name=filter15_2 value="<?=$filter15_2?>">
<input type=hidden name=filter15_3 value="<?=$filter15_3?>">
  </form>

  <?if ($parent_ids!="") {?>  
  <img src="img/x.gif" width="5" height="1"><input type="button" value="<?=$lng[17][64]?>" onclick="document.forms['undo_form'].action.value='undo';document.forms['undo_form'].submit();">
  <input type="button" value="<?=$lng[17][65]?>" onclick="document.forms['undo_form'].action.value='redo';document.forms['undo_form'].submit();">
    <br><br>	    
  <?}?>  
    
	    <script  src="dhtmlxTree/codebase/dhtmlxcommon.js"></script>
	    <script  src="dhtmlxTree/codebase/dhtmlxtree.js"></script>
            <div id="treeboxbox_tree" style="width:200;height:200">
	    
	    <?if ($parent_ids!="") {?>
	    <table border=0 width="100%"><tr><td nowrap><?=$lng[17][62]?><br/></td></tr></table>
	    </div>
	    <?}?>
	    
	    <script> 
            function myDragHandler(idFrom,idTo){
                //if we return false then drag&drop be aborted
                //alert(idTo);
                x_submitOrder(idTo, idFrom, showData);
		//document.getElementById(row.id).style.background='#FCBABA';
                return true;
            }       
     
                
            <?php
  	      //include ajax generated javascript functions
              sajax_show_javascript();
             ?>		

		function showData(q) {
			//var now = new Date();
			//document.getElementById('debug').innerHTML="<br>Categories order was updated successfully.<br>"+now.getHours()+":"+now.getMinutes()+":"+now.getSeconds()+"";
        }

	       
            tree=new dhtmlXTreeObject("treeboxbox_tree","100%","100%",0);
            tree.enableThreeStateCheckboxes(true);
            tree.setImagePath("dhtmlxTree/codebase/imgs/");
            //enable Drag&Drop
            tree.enableDragAndDrop(1);
            //tree.enableSmartRendering(true);
            tree.enableCheckBoxes(1);


            //set my Drag&Drop handler
            tree.setDragHandler(myDragHandler);
            
            //tree.loadXML("dhtmlxTree/samples/events/tree.xml");
	    </script> 
	    
	    <?
            
	    //if ($parent_ids=="") $query="select * from requirements where r_p_id in (".$project_list.") and r_parent_id=0 order by r_pos asc";
	    //else 
	    $query="select * from requirements where r_p_id in (".$project_list.") and r_id in (0".$parent_ids.") and r_parent_id=0 order by r_pos asc";
	    
	    $rs = mysql_query($query) or die(mysql_error());
	    $cnt3=0;
	    while($row=mysql_fetch_array($rs)) 
	     {
	      $cnt3++;
	      $arr[]=$cnt3."|".$row['r_id'];
	      getTree2($row['r_id'],$cnt3,$arr);
	     }
            
            $tmpOrder="";
	    while ($cnt3>0 && list ($key, $val) = each ($arr)) 
	     {
	      $query="select r.*, date_format(r.r_creation_date, '%d.%m.%Y %H:%i') as d1, date_format(r.r_change_date, '%d.%m.%Y %H:%i') as d2, p.p_id,p.p_name,u.u_name,u2.u_name as u_name2 from requirements r left outer join projects p on r.r_p_id=p.p_id left outer join users u on r.r_u_id=u.u_id left outer join users u2 on r.r_assigned_u_id=u2.u_id where r_id=".substr($val,strpos($val,"|")+1)." and r.r_p_id in (".$project_list.") order by r_change_date desc";
	      $rs = mysql_query($query) or die(mysql_error());
	      if($row=mysql_fetch_array($rs)) 
	       {
	        $cnt++;
	        if (mktime (0,0,0,date("m"),date("d")-2,date("Y")) < mktime (0,0,0,substr($row['d2'],3,2),substr($row['d2'],0,2),substr($row['d2'],6,4))) $new=1;
	        else $new=0;	  
	    
	        //setting row colours
	        $cl=$state_colors_array[$row['r_state']];
	        
	        $req_id=$row['r_id'];
	        
	        $tmpOrder.=$req_id."|";
	        
	        $match="";
	        if (strstr($r_ids,",".$req_id.",") && $search!="") $match=" style='color:red'";
	    ?>
	    
	  <script> 
            tree.insertNewChild(<?=$row['r_parent_id']?>,<?=$row['r_id']?>,"<a href='index.php?inc=view_requirement&r_id=<?=$row['r_id']?>' <?=$match?>><?=htmlspecialchars($row['r_name'])?></a> (<a onclick=document.location.href='index.php?inc=view_all&par_id=<?=$row['r_parent_id']?>'><?=$lng[17][70]?></a>)",0,0,0,0,"");
     	  </script>
    	   
    	 	  
	  <?   }
	     }
	     ?>
	     <script> 
	     tree.closeAllItems(0);
	     </script><?
	    if ($par_id=="") $par_id=0;	    
	    $query="select * from requirements where r_id=".$par_id;
	    $rs = mysql_query($query) or die(mysql_error());
	    if($row=mysql_fetch_array($rs)) $parent_name=$row['r_name'];
	    if($parent_name=="") $parent_name="root";
	     
	     
	     ?>
	     </td>
	     <td width="60%" valign="top" align="left">
	        <?if ($parent_ids=="") {echo "<br/><span class='error'>".$lng[17][82]."</span><br/><br/>";}else{?>
                <br><img src="img/x.gif" width="1" height="20"><br/><?=$lng[17][69]?><br/><br/>
	       <table  border="0" cellpadding="2" cellspacing="2" class="content" id="table-1">
		  <tr class="">
		    <td nowrap colspan="7"><?=$lng[17][71]?>: <?=htmlspecialchars($parent_name)?></td>
		  </tr>
		  <tr class="light_blue">
		    <td nowrap>&nbsp;</td>
		    <td nowrap>&nbsp;<?=$lng[17][2]?></td>
		    <td nowrap>&nbsp;<?=$lng[17][7]?></td>
		    <td nowrap>&nbsp;<?=$lng[17][36]?></td>
		    <td nowrap>&nbsp;<?=$lng[17][3]?></td>
		    <td nowrap>&nbsp;<?=$lng[17][6]?></td>
		    <td nowrap>&nbsp;<?=$lng[17][8]?></td>
		  </tr>
	  <?
	    $query="select * from requirements where r_p_id in (".$project_list.") and r_parent_id=".$par_id." order by r_pos asc";
	    $rs = mysql_query($query) or die(mysql_error());
	    $cnt3=0;
	    while($row=mysql_fetch_array($rs)) 
	     {
	      $cnt3++;
	      $arr[]=$cnt3."|".$row['r_id'];
	      getTree2($row['r_id'],$cnt3,$arr);
	     }
            
            $tmpOrder="";
	    while ($cnt3>0 && list ($key, $val) = each ($arr)) 
	     {
	      $query="select r.*, date_format(r.r_creation_date, '%d.%m.%Y %H:%i') as d1, date_format(r.r_change_date, '%d.%m.%Y %H:%i') as d2, p.p_id,p.p_name,u.u_name,u2.u_name as u_name2 from requirements r left outer join projects p on r.r_p_id=p.p_id left outer join users u on r.r_u_id=u.u_id left outer join users u2 on r.r_assigned_u_id=u2.u_id where r_id=".substr($val,strpos($val,"|")+1)." and r.r_parent_id=".$par_id." and r.r_p_id in (".$project_list.") ".$search." order by r_pos asc";
	      $rs = mysql_query($query) or die(mysql_error());
	      if($row=mysql_fetch_array($rs)) 
	       {
	        $cnt++;
	        if (mktime (0,0,0,date("m"),date("d")-2,date("Y")) < mktime (0,0,0,substr($row['d2'],3,2),substr($row['d2'],0,2),substr($row['d2'],6,4))) $new=1;
	        else $new=0;	
	        $tmpOrder.=$row['r_id']."|";
	    ?>
	  <tr id="<?=$row['r_id']?>" name="<?=substr($val,0,strpos($val,"|"))?>" class="blue" style="background-color:white;">
	    <td valign="middle" align=right><img src="dhtmlxTree/codebase/imgs/leaf.gif" align="middle"></td>
	    <td valign="middle">&nbsp;<?=$new?"<b>":""?><?=htmlspecialchars($row['r_name'])?><?=$new?"</b>":""?></td>
	    <td valign="middle">&nbsp;<a href="index.php?inc=view_project&p_id=<?=$row['p_id']?>"><?=$new?"<b>":""?><?=htmlspecialchars($row['p_name'])?><?=$new?"</b>":""?></a></td>
	    <td valign="middle">
	    <?
	    $query2="select r.*, date_format(r.r_date, '%d.%m.%Y') as d1, date_format(r.r_released_date, '%d.%m.%Y') as d2 from project_releases pr left outer join releases r on pr.pr_r_id=r.r_id where pr.pr_p_id='".$row['p_id']."' order by r.r_name asc";
	    $rs2 = mysql_query($query2) or die(mysql_error());
	    while($row2=mysql_fetch_array($rs2))
	     {
	      echo "<a href='index.php?inc=view_release&r_id=".$row2['r_id']."'>".htmlspecialchars($row2['r_name'])."</a>";
	      echo "; ";
	     }
	    ?>
	    </td>

	    <td valign="middle">&nbsp;<?=$new?"<b>":""?><?=htmlspecialchars($row['u_name'])?><?=$new?"</b>":""?></td>
	    <td valign="middle" width="110">&nbsp;<?=$new?"<b>":""?><?=htmlspecialchars($row['d1'])?><?=$new?"</b>":""?></td>
	    <td valign="middle" width="110">&nbsp;<?=$new?"<b>":""?><?=($row['d2']!="00.00.0000 00:00")?$row['d2']:"(".$row['d1'].")"?><?=$new?"</b>":""?></td>
	  </tr>	  
	      <? 
	       }
	     }
	     ?>
	       </table>	  
	     <?}?>
	     </td>
	     </tr>
	     </table>
	     <?
	   } 
else //if normal view selected
 {
  $query="select * from requirements where r_p_id in (".$project_list.") and r_parent_id=0 order by r_pos asc";
  $rs = mysql_query($query) or die(mysql_error());
  $cnt45=0;
  while($row=mysql_fetch_array($rs)) 
   {
    $cnt45++;
    $arr45[]=$cnt45."|".$row['r_id'];
    $arr45_1[]=$row['r_id'];
    getTree2_1($row['r_id'],$cnt45,$arr45,$arr45_1);
   }
   
  ?>

<form method="post" action="" name="req">
<input type=hidden name=from value="<?=$from?>">
<input type=hidden name=order value="">
<input type=hidden name=ids value="<?=$ids?>">
<input type=hidden name=action value="">
<input type=hidden name=filter11 value="<?=$filter11?>">
<table border="0" width="100%">
  <tr valign="top">
    <td valign="top">
      <table border="0" cellpadding="2" cellspacing="2" class="content" width="50%">
	 <tr>
	   <td valign="top" align="left" class="gray" nowrap>
	     &nbsp;&nbsp;<?=$lng[17][3]?>: <select name=filter1>
		<option value="">--
		<?
	          //users list	        
	          $query4="select u_name, u_id from users where u_rights in (0,1,2,3,4,5) order by u_name asc";
		  $rs4 = mysql_query($query4) or die(mysql_error());	        
	          while($row4=mysql_fetch_array($rs4)) 
		   {
		    echo "<option value='".$row4['u_id']."'>".htmlspecialchars($row4['u_name']);
		   }
	        ?>
	     </select>
	       <script>
		 for (i=0;i<document.forms.req.filter1.length;i++)
		   if (document.forms.req.filter1.options[i].value=='<?=$filter1?>')
		      document.forms.req.filter1.options[i].selected=true;
	       </script>	    
	     	  
	     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$lng[17][16]?>: <select name=filter2>
		<option value="">--
		<?//state list	        
	          include("ini/txts/".$_SESSION['chlang']."/state.php");
              if ( ! isset($r_state) ) $r_state="";
		  echo makeSelect($state_array,$r_state);
	        ?>
	     </select>
	       <script>
		 for (i=0;i<document.forms.req.filter2.length;i++)
		   if (document.forms.req.filter2.options[i].value=='<?=$filter2?>')
		      document.forms.req.filter2.options[i].selected=true;
	       </script>	    
	    
	     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$lng[17][5]?>: <select name=filter4>
		 <option value="">--
		 <option value='1'>1 <?=$lng[15][101]?>
		 <option value='2'>2
		 <option value='3'>3
		 <option value='4'>4
		 <option value='5'>5
		 <option value='6'>6
		 <option value='7'>7
		 <option value='8'>8
		 <option value='9'>9
		 <option value='10'>10 <?=$lng[15][102]?>
	       </select>
	       <script>
		 for (i=0;i<document.forms.req.filter4.length;i++)
		   if (document.forms.req.filter4.options[i].value=='<?=$filter4?>')
		      document.forms.req.filter4.options[i].selected=true;
	       </script>
	       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$lng[17][4]?>: 
	       <select name="filter6">
	       <option value="">--
	       <?
		//users list
		$query4="select u_name, u_id from users where u_rights in (0,1,2,3,4,5) order by u_name asc";
		$rs4 = mysql_query($query4) or die(mysql_error());
		if ( ! isset($filter6) ) $filter6="";
		while($row4=mysql_fetch_array($rs4)) 
		 {
		  if ($row4['u_id']==$filter6) echo "<option value='".$row4['u_id']."' selected>".htmlspecialchars($row4['u_name']);
		  else echo "<option value='".$row4['u_id']."'>".htmlspecialchars($row4['u_name']);
		 }
		?>
	      </select> 
	       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$lng[17][36]?>: 
	       <select name="filter7">
	       <option value="">--
	       <?
		//releases list
		//$query4="select * from releases order by r_name asc";
	        $query4="select r.* from releases r left outer join project_releases pr on r.r_id=pr.pr_r_id where (pr.pr_p_id in ('".$project_list."') or r.r_global=1 ) order by r.r_name asc";
		$rs4 = mysql_query($query4) or die(mysql_error());
		        
		while($row4=mysql_fetch_array($rs4)) 
		 {
		  if ($row4['r_id']==$filter7) echo "<option value='".$row4['r_id']."' selected>".htmlspecialchars($row4['r_name']);
		  else echo "<option value='".$row4['r_id']."'>".htmlspecialchars($row4['r_name']);
		 }
		?>
	      </select> 
	   </td>	   	 
	 </tr>
	 <tr>
	   <td align="left" class="gray" nowrap valign="top">
	     &nbsp;&nbsp;<?=$lng[17][42]?>: <select name=filter8>
		<option value="">--
	       <?
		//components list
		//$query4="select * from components order by c_name asc";
	        $query4="select c.* from components c left outer join project_components pco on c.c_id=pco.pco_c_id where (pco.pco_p_id in ('".$project_list."') or c.c_global=1 ) order by c.c_name asc";
		$rs4 = mysql_query($query4) or die(mysql_error());
		        
		while($row4=mysql_fetch_array($rs4)) 
		 {
		  if ($row4['c_id']==$filter8) echo "<option value='".$row4['c_id']."' selected>".htmlspecialchars($row4['c_name']);
		  else echo "<option value='".$row4['c_id']."'>".htmlspecialchars($row4['c_name']);
		 }
		?>
	     </select>
	       <script>
		 for (i=0;i<document.forms.req.filter8.length;i++)
		   if (document.forms.req.filter8.options[i].value=='<?=$filter8?>')
		      document.forms.req.filter8.options[i].selected=true;
	       </script>	    
	     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$lng[17][45]?>: <select name=filter9>
		<option value="0"><?=$lng[17][46]?>
		<option value="1"><?=$lng[17][47]?>		
		<option value="2"><?=$lng[17][51]?>		
	     </select>
	       <script>
		 for (i=0;i<document.forms.req.filter9.length;i++)
		   if (document.forms.req.filter9.options[i].value=='<?=$filter9?>')
		      document.forms.req.filter9.options[i].selected=true;
	       </script>
	       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$lng[17][48]?>: <select name=filter10>
		<option value="0"><?=$lng[17][49]?>
		<option value="1"><?=$lng[17][50]?>		
	     </select>
	       <script>
		 for (i=0;i<document.forms.req.filter10.length;i++)
		   if (document.forms.req.filter10.options[i].value=='<?=$filter10?>')
		      document.forms.req.filter10.options[i].selected=true;
	       </script>	    
	       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$lng[2][35]?>: <select name=filter18>
	       <option value="">--
	       <?
		//test cases list
		//$query4="select * from cases order by c_name asc";
	        $query4="select c.* from cases c left outer join project_cases pc on c.c_id=pc.pc_c_id where (pc.pc_p_id in ('".$project_list."') or c.c_global=1 ) order by c.c_name asc";
		$rs4 = mysql_query($query4) or die(mysql_error());
		        
		while($row4=mysql_fetch_array($rs4)) 
		 {
		  if ($row4['c_id']==$filter18) echo "<option value='".$row4['c_id']."' selected>".htmlspecialchars($row4['c_name']);
		  else echo "<option value='".$row4['c_id']."'>".htmlspecialchars($row4['c_name']);
		 }
		?>
	     </select>
	       <script>
		 for (i=0;i<document.forms.req.filter18.length;i++)
		   if (document.forms.req.filter18.options[i].value=='<?=$filter18?>')
		      document.forms.req.filter18.options[i].selected=true;
	       </script>	    
	   </td>	   	 
	 </tr>
	 <tr valign="top">
	   <td align="left" class="gray" nowrap valign="top">
	      <table border=0 cellspacing=0 cellpadding=0>
	        <tr valign="top">
	          <td valign="top" rowspan="2">
		       &nbsp;&nbsp;<?=$lng[17][52]?>:&nbsp;&nbsp; 
		  </td>
		  <td rowspan="2">   
		      <select name=filter11_tmp multiple size=3>  
		       <?
			//keywords list
			//$query4="select * from keywords order by k_name asc";
	       		$query4="select k.* from keywords k left outer join project_keywords pk on k.k_id=pk.pk_k_id where (pk.pk_p_id in ('".$project_list."') or k.k_global=1 ) order by k.k_name asc";
			$rs4 = mysql_query($query4) or die(mysql_error());
			        
			while($row4=mysql_fetch_array($rs4)) 
			 {
			  if (strstr(",".$filter11,",".$row4['k_id'].",")) echo "<option value='".$row4['k_id']."' selected>".htmlspecialchars($row4['k_name']);
			  else echo "<option value='".$row4['k_id']."'>".htmlspecialchars($row4['k_name']);
			 }
			?>
		     </select>
		       <script>
			 for (i=0;i<document.forms.req.filter11.length;i++)
			   if (document.forms.req.filter11.options[i].value=='<?=$filter11?>')
			      document.forms.req.filter11.options[i].selected=true;
		       </script>
		  </td>   
		  <td>  	    
	             &nbsp;&nbsp;<img src="x.gif" width=8 height=1><?=$lng[17][53]?>&nbsp;<input type="radio" name="filter12" value="<?=$lng[17][53]?>" <?if ( ! isset($filter12) ) $filter12=""; if ($filter12=="" || $filter12==$lng[17][53]) echo "checked";?>>
	          </td>
	          <td rowspan="2">  	    
			     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$lng[17][55]?>: 
		             <select name=filter13>
				<option value="">--
				<option value="1"><?=$lng[17][56]?>
				<option value="2"><?=$lng[17][57]?>
				<option value="3"><?=$lng[17][58]?>
				<option value="5"><?=$lng[17][60]?>
				<option value="6"><?=$lng[17][61]?>
			     </select>
		       <script>
			 for (i=0;i<document.forms.req.filter13.length;i++)
			   if (document.forms.req.filter13.options[i].value=='<?=$filter13?>')
			      document.forms.req.filter13.options[i].selected=true;
		       </script>
		  </td> 
	          <td rowspan="2">  
		       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$lng[17][72]?>: <select name=filter14>
		       <option value="">--
		       <?
			//users list
			$query4="select u_name, u_id from users order by u_name asc";
			$rs4 = mysql_query($query4) or die(mysql_error());
			        
		    if ( ! isset($filter14) ) $filter14="";
			while($row4=mysql_fetch_array($rs4)) 
			 {
			  if ($row4['u_id']==$filter14) echo "<option value='".$row4['u_id']."' selected>".htmlspecialchars($row4['u_name']);
			  else echo "<option value='".$row4['u_id']."'>".htmlspecialchars($row4['u_name']);
			 }
			?>
		     </select>
	          	    
	          </td> 
	        </tr>  
	        <tr>
	          <td>
	            &nbsp;&nbsp;<?=$lng[17][54]?>&nbsp;<input type="radio" name="filter12" value="<?=$lng[17][54]?>"<?if ($filter12==$lng[17][54]) echo "checked";?>>
	          </td>
	       </tr> 
	     </table> 
	   </td>	   	 
	 </tr>
	 <tr>
	   <td align="left" class="gray" nowrap valign="top">
           <? if ( ! isset($filter15_3) ) $filter15_3 = ""; if ( ! isset($filter15_1) ) $filter15_1 = ""; if ( ! isset($filter15_2) ) $filter15_2 = ""; if ( ! isset($filter5) ) $filter5 = "";?>
	            &nbsp;&nbsp;<?=$lng[17][73]?>: <input type="text" size=2 name="filter15_3" value="<?=stripslashes(htmlspecialchars($filter15_3))?>"> <?=$lng[17][83]?> <input type="text" size=2 name="filter15_1" value="<?=stripslashes(htmlspecialchars($filter15_1))?>">(<?=$lng[17][74]?>) <input type="text" size=2 name="filter15_2" value="<?=stripslashes(htmlspecialchars($filter15_2))?>">(<?=$lng[17][75]?>)

	            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$lng[17][18]?>: <input type="text" size=30 name="filter5" value="<?=stripslashes(htmlspecialchars($filter5))?>">&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" onclick="if (!sub_search()) return false;" value="<?=$lng[17][17]?>">&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" onclick="document.location.href='index.php?inc=view_all'" value="<?=$lng[17][79]?>">
	   </td>	   	 
	 </tr>
      </table>
    </td>
  </tr>  
  <tr valign="top">
    <td><?if ($_SESSION['_viewalltype']==0) {echo "<br/>".$lng[17][62]."<br/>";}?>
<br></td>
  </tr> 
  
  
  <tr valign="top">
    <td width="50%">
	<table <?if ($_SESSION['_viewalltype']==0) echo "id='table-1'";?> border="0" cellpadding="2" cellspacing="2" class="content" width="50%">
	  <?
	    $query="select count(*) from requirements where r_p_id in (".$project_list.")";
	    $rs = mysql_query($query) or die(mysql_error());
        if ( ! isset($all_reqs) ) $all_reqs="";
	    if($row=mysql_fetch_array($rs)) $all_reqs.=$row[0];



	    //sortable columns
        if ( ! isset($order) ) $order="";
        if ($order=="") $order="r_change_date desc";

	    //search
        if ( ! isset($order) ) $order="";
        if ( ! isset($filter1) ) $filter1="";
        if ( ! isset($filter2) ) $filter2="";
        if ( ! isset($filter3) ) $filter3="";
        if ( ! isset($filter4) ) $filter4="";
        if ( ! isset($filter5) ) $filter5="";
        if ( ! isset($filter6) ) $filter6="";
        if ( ! isset($filter7) ) $filter7="";
        if ( ! isset($filter8) ) $filter8="";
        if ( ! isset($filter9) ) $filter9="";
        if ( ! isset($filter10) ) $filter10="";
        if ( ! isset($filter11) ) $filter11="";
        if ( ! isset($filter12) ) $filter12="";
        if ( ! isset($filter13) ) $filter13="";
        if ( ! isset($filter14) ) $filter14="";
        if ( ! isset($filter15_1) ) $filter15_1="";
        if ( ! isset($filter15_2) ) $filter15_2="";
        if ( ! isset($filter15_3) ) $filter15_3="";
        if ( ! isset($filter18) ) $filter18="";
        if ($filter1!="") $search.=" and r.r_u_id=".$filter1;
	    if ($filter2!="") $search.=" and r.r_state=".$filter2;
	    if ($filter4!="") $search.=" and r.r_priority=".$filter4;
	    if ($filter5!="") 
	   {
	    $query4="select * from comments where c_text like('%".escapeChars($filter5)."%')";
	    $rs4 = mysql_query($query4) or die(mysql_error());
	    $new_pr29="(0";
	    while($row4=mysql_fetch_array($rs4)) 
	     {
	      $new_pr29.=",".$row4['c_r_id'];
	     } 
	    $new_pr29.=")";	 
	    //$search.=" and r.r_id in ".$new_pr29;
            $search.=" and (r.r_id in ".$new_pr29." or (r.r_desc like ('%".escapeChars($filter5)."%') or r.r_source like ('%".escapeChars($filter5)."%') or r.r_name like ('%".escapeChars($filter5)."%') or r.r_userfield1 like ('%".escapeChars($filter5)."%') or r.r_userfield2 like ('%".escapeChars($filter5)."%') or r.r_userfield3 like ('%".escapeChars($filter5)."%') or r.r_userfield4 like ('%".escapeChars($filter5)."%') or r.r_userfield5 like ('%".escapeChars($filter5)."%') or r.r_userfield6 like ('%".escapeChars($filter5)."%')))";
           }
	  
	  if ($filter6!="") $search.=" and r.r_assigned_u_id=".$filter6;
	  if ($filter7!="") 
	   {
	    $search.=" and CONCAT(',',r.r_release) like ('%,".$filter7.",%')"; 
	    /*$query4="select * from project_releases where pr_r_id=".$filter7;
	    $rs4 = mysql_query($query4) or die(mysql_error());
	    $new_pr="(0";
	    while($row4=mysql_fetch_array($rs4)) 
	     {
	      $new_pr.=",".$row4['pr_p_id'];
	     } 
	    $new_pr.=")";	 
	    $search.=" and r.r_p_id in ".$new_pr;*/
	   } 
	  if ($filter8!="") $search.=" and CONCAT(',',r.r_component) like ('%,".$filter8.",%')"; 
	  if ($filter9=="1") $search.=" and r.r_stub=".$filter9;
	  elseif ($filter9=="2") $search.=" and r.r_stub=".$filter9;
	  if ($filter10=="1") 
	   {
	    $query4="select * from comments where c_question=1";
	    $rs4 = mysql_query($query4) or die(mysql_error());
	    $new_pr2="(0";
	    while($row4=mysql_fetch_array($rs4)) 
	     {
	      $new_pr2.=",".$row4['c_r_id'];
	     } 
	    $new_pr2.=")";	 
	    $search.=" and r.r_id in ".$new_pr2;
	   } 
	  if ($filter11!="") 
	   {
	    $cnt_k=0;
	    $search.=" and (";
	    $kwrds = explode(",", $filter11);
	    while (list ($key, $val) = each ($kwrds)) 
	     {
   	      if ($val!="")
   	       {
   	        if ($cnt_k==0) $search.=" CONCAT(',',r.r_keywords) like ('%,".$val.",%')";
   	        else $search.=" ".$filter12." CONCAT(',',r.r_keywords) like ('%,".$val.",%')";
   	        $cnt_k++;
   	       } 
             }
	    $search.=")";	    
	   }
	  if ($filter13!="") 
	   {
	    if ($filter13==1) $search.=" and r.r_u_id='0'";
	    elseif ($filter13==2) $search.=" and r.r_assigned_u_id='0'";
	    elseif ($filter13==3) $search.=" and r.r_component=''";
	    //elseif ($filter13==4) $search.=" and r.r_assigned_u_id='0'";
	    elseif ($filter13==5) $search.=" and r.r_keywords=''";
	    elseif ($filter13==6) $search.=" and r.r_c_id=''";
	   } 
	  if ($filter14!="") 
	   {
	    $query4="select * from comments where c_u_id=".$filter14;
	    $rs4 = mysql_query($query4) or die(mysql_error());
	    $new_pr29="(0";
	    while($row4=mysql_fetch_array($rs4)) 
	     {
	      $new_pr29.=",".$row4['c_r_id'];
	     } 
	    $new_pr29.=")";	 
	    $search.=" and r.r_id in ".$new_pr29;
	   } 
	  if ($filter15_3!="") $search.=" and (r.r_id=".$filter15_3.")";
	  else
	   {
	    if ($filter15_1!="" && $filter15_2!="") $search.=" and (r.r_id>=".$filter15_1." and r.r_id<=".$filter15_2.")";
	    if ($filter15_1!="" && $filter15_2=="") $search.=" and r.r_id>=".$filter15_1;
	    if ($filter15_1=="" && $filter15_2!="") $search.=" and r.r_id<=".$filter15_2;
           }
	  if ($filter18!="") $search.=" and CONCAT(',',r.r_c_id) like ('%,".$filter18.",%')"; 

	  //paging query, number of results (from the param.php file) per page displayed
      if ( ! isset($search) ) $search="";
	  $paging=$PPAGE;
	  $query="select count(*) from requirements r left outer join projects p on r.r_p_id=p.p_id where r.r_p_id in (".$project_list.")".$search;
	  // echo $query;
	  $rs = mysql_query($query) or die(mysql_error());
	  if($row=mysql_fetch_array($rs)) $all_count=$row[0];
      if ( ! isset($from) ) $from="";
      if ( ! isset($check_all) ) $check_all=1;
	  if ($from=="") $from=0; 
	  if ($from+$paging>$all_count) $cnt2=$all_count;
	  else $cnt2=$from+$paging;

	  //check all ids if check all pressed
	  if ($check_all)
	   {
	    $ids="";
	    $query="select r_id from requirements r left outer join projects p on r.r_p_id=p.p_id where r.r_p_id in (".$project_list.")".$search;
	    $rs = mysql_query($query) or die(mysql_error());
	    while($row=mysql_fetch_array($rs)) $ids.=$row[0].",";
	   }
	  
	  //getting requirements - recently modified
	  $cnt=0;
	  ?>
	  <tr class="gray">
	    <td colspan="14">&nbsp;<b><?=$lng[4][3]?> (<?=($all_count==0)?"0":($from+1)?> - <?=$cnt2?> / <?=$all_count?> )</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><?=$lng[4][7]?> (<?=$all_reqs?>)</b></td>
	  </tr>
	  <tr class="light_blue">
	    <td>&nbsp;</td>
	    <td align=center>&nbsp;<a href="#" onclick="document.forms['req'].order.value='r.r_id <?if ($order=='r.r_id asc') echo "desc";else echo "asc";?>';subm_order();"><?=$lng[17][1]?></a></td>
	    <?if ($_SESSION['_viewalltype']==0) {?><td align=center>&nbsp;<?=$lng[17][44]?></td><?}?>
	    <td width=200>&nbsp;<a href="#" onclick="document.forms['req'].order.value='r.r_name <?if ($order=='r.r_name asc') echo "desc";else echo "asc";?>';subm_order();"><?=$lng[17][2]?></a></td>
	    <td nowrap>&nbsp;<a href="#" onclick="document.forms['req'].order.value='p.p_name <?if ($order=='p.p_name asc') echo "desc";else echo "asc";?>';subm_order();"><?=$lng[17][7]?></a></td>
	    <td nowrap>&nbsp;<a href="#" onclick="document.forms['req'].order.value='rl.r_name <?if ($order=='rl.r_name asc') echo "desc";else echo "asc";?>';subm_order();"><?=$lng[17][36]?></a></td>
	    <td nowrap>&nbsp;<a href="#" onclick="document.forms['req'].order.value='u.u_name <?if ($order=='u.u_name asc') echo "desc";else echo "asc";?>';subm_order();"><?=$lng[17][3]?></a></td>
	    <td nowrap>&nbsp;<a href="#" onclick="document.forms['req'].order.value='r.r_state <?if ($order=='r.r_state asc') echo "desc";else echo "asc";?>';subm_order();"><?=$lng[17][16]?></a></td>
	    <td nowrap>&nbsp;<a href="#" onclick="document.forms['req'].order.value='u_name2 <?if ($order=='u_name2 asc') echo "desc";else echo "asc";?>';subm_order();"><?=$lng[17][4]?></a></td>
	    <td nowrap>&nbsp;<a href="#" onclick="document.forms['req'].order.value='r.r_priority <?if ($order=='r.r_priority asc') echo "desc";else echo "asc";?>';subm_order();"><?=$lng[17][5]?></a></td>
	    <td nowrap>&nbsp;<a href="#" onclick="document.forms['req'].order.value='r.r_pos <?if ($order=='r.r_pos asc') echo "desc";else echo "asc";?>';subm_order();"><?=$lng[17][76]?></a></td>
	    <td nowrap>&nbsp;<a href="#" onclick="document.forms['req'].order.value='c.c_id <?if ($order=='c.c_id asc') echo "desc";else echo "asc";?>';subm_order();"><?=$lng[17][42]?></a></td>
	    <td nowrap>&nbsp;<a href="#" onclick="document.forms['req'].order.value='r.r_creation_date <?if ($order=='r.r_creation_date asc') echo "desc";else echo "asc";?>';subm_order();"><?=$lng[17][6]?></a></td>
	    <td nowrap>&nbsp;<a href="#" onclick="document.forms['req'].order.value='r.r_change_date <?if ($order=='r.r_change_date asc') echo "desc";else echo "asc";?>';subm_order();"><?=$lng[17][8]?></a></td>
	  </tr>
	    
	    
	    <?
	    if ($_SESSION['projects']!=0 && ($order=="r.r_pos asc" || $order=="r.r_pos desc"))
	     {
	      while ($cnt45>0 && list ($key, $val) = each ($arr45)) $new_order.=substr($val,strpos($val,"|")+1).",";
	      $order=" FIELD(r_id,".$new_order."0) asc";	     
	     }
	    $query="select r.*, date_format(r.r_creation_date, '%d.%m.%Y %H:%i') as d1, date_format(r.r_change_date, '%d.%m.%Y %H:%i') as d2, p.p_id,p.p_name,u.u_name,u2.u_name as u_name2 from requirements r left outer join projects p on r.r_p_id=p.p_id left outer join releases rl on r.r_release=rl.r_id left outer join users u on r.r_u_id=u.u_id left outer join users u2 on r.r_assigned_u_id=u2.u_id left outer join components c on r.r_component=c.c_id where r.r_p_id in (".$project_list.") ".$search." order by ".$order.", r_change_date desc Limit ".$from.",".$paging;
            //echo $query;
            $rs = mysql_query($query) or die(mysql_error());
	    while($row=mysql_fetch_array($rs)) 
	     {
	      $cnt++;
	      if (mktime (0,0,0,date("m"),date("d")-2,date("Y")) < mktime (0,0,0,substr($row['d2'],3,2),substr($row['d2'],0,2),substr($row['d2'],6,4))) $new=1;
	      else $new=0;	  
	    
	    //setting row colours
	    $cl=$state_colors_array[$row['r_state']];
	    ?>
	  <tr class="blue" style="background-color:<?=$cl?>;">
	    <td width="20" valign="middle" align=center><input type="checkbox" onclick="if (this.checked) document.forms['req'].uncheck_all.checked=false;if (!this.checked) document.forms['req'].check_all.checked=false;" name="ch<?=$cnt?>" value="<?=$row['r_id']?>" <?if (!$uncheck_all && (strstr(",".$ids,",".$row['r_id'].",") || $check_all)) echo "checked";?>></td>
	    <td width="30" valign="middle" align=center>&nbsp;<a href="index.php?inc=view_requirement&r_id=<?=$row['r_id']?>"><?=$new?"<b>":""?><?=$row['r_id']?><?=$new?"</b>":""?></a></td>
	    <td valign="middle">&nbsp;<?=$new?"<b>":""?><?=htmlspecialchars($row['r_name'])?><?=$new?"</b>":""?></td>
	    <td valign="middle">&nbsp;<a href="index.php?inc=view_project&p_id=<?=$row['p_id']?>"><?=$new?"<b>":""?><?=htmlspecialchars($row['p_name'])?><?=$new?"</b>":""?></a></td>
	    <td valign="middle">
	    <?
	    $query2="select r.*, date_format(r.r_date, '%d.%m.%Y') as d1, date_format(r.r_released_date, '%d.%m.%Y') as d2 from releases r where r.r_id in (".$row['r_release']."0) order by r.r_name asc";
	    //$query2="select r.*, date_format(r.r_date, '%d.%m.%Y') as d1, date_format(r.r_released_date, '%d.%m.%Y') as d2 from project_releases pr left outer join releases r on pr.pr_r_id=r.r_id where pr.pr_p_id='".$row['p_id']."' order by r.r_name asc";
	    $rs2 = mysql_query($query2) or die(mysql_error());
	    while($row2=mysql_fetch_array($rs2))
	     {
	      echo "<a href='index.php?inc=view_release&r_id=".$row2['r_id']."'>".htmlspecialchars($row2['r_name'])."</a>";
	      echo "; ";
	     }
	    ?>
	    </td>

	    <td valign="middle">&nbsp;<?=$new?"<b>":""?><?=htmlspecialchars($row['u_name'])?><?=$new?"</b>":""?></td>
	    <td valign="middle">
	      <?=$new?"<b>":""?>
	      <?
	      include("ini/txts/".$_SESSION['chlang']."/state.php");
	      echo $state_array[$row['r_state']];
	      ?>
	      <?=$new?"</b>":""?>
	    </td>
	    <td valign="middle">&nbsp;<?=$new?"<b>":""?><?=htmlspecialchars($row['u_name2'])?><?=$new?"</b>":""?></td>
	    <td valign="middle">&nbsp;<?=$new?"<b>":""?><?=htmlspecialchars($row['r_priority'])?><?=$new?"</b>":""?></td>
	    <td valign="middle">&nbsp;<?=$new?"<b>":""?><?if (sizeof($arr45)>0) echo substr($arr45[array_search($row['r_id'],$arr45_1)],0,strpos($arr45[array_search($row['r_id'],$arr45_1)],"|"));else echo $row['r_pos'];?><?=$new?"</b>":""?></td>
	    <td valign="middle">
	    <?
	    $query2="select * from components where c_id in (".$row['r_component']."0) order by c_name asc";
	    $rs2 = mysql_query($query2) or die(mysql_error());
	    while($row2=mysql_fetch_array($rs2))
	     {
	      echo "<a href='index.php?inc=view_component&c_id=".$row2['c_id']."'>".htmlspecialchars($row2['c_name'])."</a>; ";
	     }
	    ?>

	    </td>
	    <td valign="middle" width="110">&nbsp;<?=$new?"<b>":""?><?=htmlspecialchars($row['d1'])?><?=$new?"</b>":""?></td>
	    <td valign="middle" width="110">&nbsp;<?=$new?"<b>":""?><?=($row['d2']!="00.00.0000 00:00")?$row['d2']:"(".$row['d1'].")"?><?=$new?"</b>":""?></td>
	  </tr>	  
	  <?}?>
	  <?if ($cnt>0) {?>
	  <tr class="gray">
	    <td colspan="14">&nbsp;<input type="checkbox" onclick="checkall(1);" <?if ($check_all) echo "checked";?> name="check_all" value="1" onclick="">&nbsp;<?=$lng[17][77]?>&nbsp;<input type="checkbox" onclick="checkall(0);" <?if ($uncheck_all) echo "checked";?> name="uncheck_all" value="1" onclick="">&nbsp;<?=$lng[17][78]?></td>
	  </tr>
	  <?}?>
	  
	  <?
//displaying paging list
if ($all_count>$paging && $cnt!=0) 
 {
  $j=ceil($all_count/$paging);
  for ($i=0,$tmp_c=0;$i<$j;$i++,$tmp_c++) 
   {
    if ($i*$paging==$from) $tmp_paging.=($i+1)." &nbsp;";	
    elseif ( ($i*$paging>=$from-(4*$paging)) && ($i*$paging<=$from+(4*$paging))) $tmp_paging.="<a href=# onclick='subm_paging(".($i*$paging).");return false;'>".($i+1)."</a> &nbsp;";
   } 
  $tmp_paging=substr($tmp_paging,0,strlen($tmp_paging)-1);
 }  
?>  
</table>
<table border="0" cellpadding="2" cellspacing="2" class="content" width="50%">
 <? if ($cnt>0 && $tmp_paging!="" && $_SESSION['_viewalltype']!=0){?>


<tr class="gray" align=left>
     <td colspan=13 align=left>&nbsp;<b>
     
     <?if ($from>0) echo "<a href=# onclick='subm_paging(0);return false;'>&lt;&lt;</a>&nbsp;&nbsp;<a href=# onclick='subm_paging(".($from-$paging).");return false;'>&lt;</a>&nbsp;&nbsp;";?>
     <?=$tmp_paging?>
     <?if ($from<$all_count-$paging) echo "<a href=# onclick='subm_paging(".($from+$paging).");return false;'>&gt;</a>&nbsp;&nbsp;<a href=# onclick='subm_paging(".(($i-1)*$paging).");return false;'>&gt;&gt;</a>";?>
     </b></td>
</tr>
<?}?>

<?if ($_SESSION['uid']!="" && $_SESSION['rights']!=0) { ?>
<tr class="light_blue">
   <td colspan="13">&nbsp;<b><?=$lng[17][28]?></b></td>
</tr>
<tr class="light_blue" align=left>
   <td colspan=13 align=left>
   <table cellpadding=0 cellspacing=0 border=0>
   <tr class="light_blue" align=left valign=top>
   <td align=left valign=top>     
   <?=$lng[17][35]?>:&nbsp;<br>
   <select name="r_p_id" onchange="document.forms['req'].submit()">
	        <option value=''><?=$lng[17][38]?>
	        <?
	        //projects list
	        if ($_SESSION['rights']=="") $query_project2="select p_name, p_id from projects where p_status=1 order by p_name asc";
		elseif ($_SESSION['rights']=="0" || $_SESSION['rights']=="1" || $_SESSION['rights']=="2" || $_SESSION['rights']=="3") $query_project2="select distinct(p.p_id), p.p_name from projects p left outer join project_users pu on p.p_id=pu.pu_p_id where ((pu.pu_u_id=".$_SESSION['uid']." and p.p_status<>2) or p.p_status=1) order by p.p_name asc";
		elseif ($_SESSION['rights']=="4") $query_project2="select distinct(p.p_id), p.p_name from projects p left outer join project_users pu on p.p_id=pu.pu_p_id where (((pu.pu_u_id=".$_SESSION['uid']." or p_leader=".$_SESSION['uid'].") and p.p_status<>2) or p.p_status=1) order by p_name asc";
		else $query_project2="select p_name, p_id from projects where p_status<>2 order by p_name asc";
	        $rs2 = mysql_query($query_project2) or die(mysql_error());
	          
	        while($row2=mysql_fetch_array($rs2)) 
		 {
		  if ($r_p_id==$row2['p_id']) echo "<option value='".$row2['p_id']."' selected>".htmlspecialchars($row2['p_name']);
		  else echo "<option value='".$row2['p_id']."'>".htmlspecialchars($row2['p_name']);
		 }
	        ?>
    </select>&nbsp;&nbsp;  
   </td>
   <td align=left valign=top>     
   <?=$lng[17][80]?>:&nbsp;<br>
   <select name="r_s_id">
	        <option value=''><?=$lng[17][81]?>
	        <option value='0'><?=$lng[17][27]?>
	        <?
	        //projects list
	        if ($r_p_id!="") {
	          //$query_subproject="select * from subprojects order by s_name asc";
	          $query_subproject="select * from subprojects where s_p_id='".$r_p_id."' order by s_name asc";
		  $rs5 = mysql_query($query_subproject) or die(mysql_error());
	          
	          while($row5=mysql_fetch_array($rs5)) 
		   {
		    if ($r_s_id==$row5['s_id']) echo "<option value='".$row5['s_id']."' selected>".htmlspecialchars($row5['s_name']);
		    else echo "<option value='".$row5['s_id']."'>".htmlspecialchars($row5['s_name']);
		   } 
		 }
	        ?>
    </select>&nbsp;&nbsp;  
   </td>
   <td align=left valign=top>     
   <?=$lng[17][36]?>:&nbsp;<br>
   <select name="r_release">
	        <option value=''><?=$lng[17][26]?>
	        <option value='0'><?=$lng[17][27]?>
	        <?
	        //releases list
	        //if ($r_p_id!="") {
	          $query_subproject="select r.*, date_format(r.r_date, '%d.%m.%Y') as d1, date_format(r.r_released_date, '%d.%m.%Y') as d2 from releases r left outer join project_releases pr on pr.pr_r_id=r.r_id where (pr.pr_p_id='".$r_p_id."' or r.r_global=1) order by r.r_name asc";
	          $rs5 = mysql_query($query_subproject) or die(mysql_error());
	          while($row5=mysql_fetch_array($rs5)) 
		   {
		    if ($r_release==$row5['r_id']) echo "<option value='".$row5['r_id']."' selected>".htmlspecialchars($row5['r_name']);
		    else echo "<option value='".$row5['r_id']."'>".htmlspecialchars($row5['r_name']);
		   } 
		// }
	        ?>
    </select>&nbsp;&nbsp;  	      
   </td>
   <td align=left valign=top>
   <?=$lng[17][37]?>:&nbsp;<br>
   <select name="r_assigned_u_id">
      <option value='' <?if ( ! isset($r_assigned_u_id) ) $r_assigned_u_id=""; if ($r_assigned_u_id=="") echo "selected";?>><?=$lng[17][26]?>
      <option value='0' <?if ($r_assigned_u_id=="0") echo "selected";?>><?=$lng[17][27]?>
	<?
	//users list
	$query4="select u_name, u_id from users where u_rights in (0,1,2,3,4,5) order by u_name asc";
	$rs4 = mysql_query($query4) or die(mysql_error());
	        
	while($row4=mysql_fetch_array($rs4)) 
	 {
	  if ($row4['u_id']==$r_assigned_u_id) echo "<option value='".$row4['u_id']."' selected>".htmlspecialchars($row4['u_name']);
	  else echo "<option value='".$row4['u_id']."'>".htmlspecialchars($row4['u_name']);
	 }
	?>
    </select> 
    &nbsp;&nbsp;
    </td>
    <td align=left valign=top>
    <?=$lng[17][30]?>:&nbsp;<br>
    <select name="r_state">
        <option value=''><?=$lng[17][31]?>
	<?if ($_SESSION['rights']=="2" || $_SESSION['rights']=="3") echo makeSelect($state_array,$r_state);
	  else echo makeSelect($state_array2,$r_state);?>
    </select>
    <script>
	for (i=0;i<document.forms.req.r_state.length;i++)
	   if (document.forms.req.r_state.options[i].value=='<?=$r_state?>')
	       document.forms.req.r_state.options[i].selected=true;
    </script>
    &nbsp;&nbsp;
    </td>
    <td align=left valign=top>
    <?=$lng[17][39]?>:&nbsp;<br>
    <select name="r_priority">
        <option value=''><?=$lng[17][40]?>
	<option value='1'>1 <?=$lng[15][102]?>
	<option value='2'>2
	<option value='3'>3
	<option value='4'>4
	<option value='5'>5
	<option value='6'>6
	<option value='7'>7
	<option value='8'>8
	<option value='9'>9
	<option value='10'>10 <?=$lng[15][101]?>
    </select>
    <script>
	for (i=0;i<document.forms.req.r_priority.length;i++)
	   if (document.forms.req.r_priority.options[i].value=='<?=$r_priority?>')
	       document.forms.req.r_priority.options[i].selected=true;
    </script>
    &nbsp;&nbsp; 
    </td>
    <td align=left valign=top>
    <?=$lng[17][41]?>:&nbsp;<br>
    <? if ( ! isset($r_source) ) $r_source=""; ?>
    <input type="text" name="r_source" value="<?=$r_source?>">
    </td>
    </tr>
    <tr>
    <td colspan="6">    
    <input type="button" onclick="mov();" value="<?=$lng[17][25]?>">
    </td>
    </tr>
    </table>
   </td>
</tr>
<?}?>

	</table>
    </td>	 
  </tr>

  
  
  <tr valign="top">
    <td><br></td>
  </tr> 
  <tr valign="top">
    <td>
      <table border="0" cellpadding="2" cellspacing="2" class="content" width="50%">
	 <tr>
	   <?for ($i=0;$i<count($state_array);$i++) {?>
	   <td align="center" class="blue" style="background-color: <?=$state_colors_array[$i]?>;" width="12.5%">&nbsp;<?=$state_array[$i]?></td>
	   <?}?>
	   <!--td align="center" class="red" width="17%">&nbsp;<?=$lng[17][12]?></td>
	   <td align="center" class="violet" width="17%">&nbsp;<?=$lng[17][13]?></td>
	   <td align="center" class="green" width="17%">&nbsp;<?=$lng[17][14]?></td>
	   <td align="center" class="yellow" width="17%">&nbsp;<?=$lng[17][15]?></td>
	   <td align="center" class="orange" width="17%">&nbsp;<?=$lng[17][10]?></td>
	   <td align="center" class="gray" width="17%">&nbsp;<?=$lng[17][11]?></td-->	 
	 </tr>
      </table>
    </td>
  </tr>  
</table>

<input type=hidden name=viewalltype value="normal">
<input type=hidden name=srch value="<?=$search?>">
</form>

<form method=post name=form_paging action="">
<input type=hidden name=from value="">
<input type=hidden name=filter1 value="<?=$filter1?>">
<input type=hidden name=filter2 value="<?=$filter2?>">
<input type=hidden name=filter4 value="<?=$filter4?>">
<input type=hidden name=filter5 value="<?=$filter5?>">
<input type=hidden name=filter6 value="<?=$filter6?>">
<input type=hidden name=filter7 value="<?=$filter7?>">
<input type=hidden name=filter8 value="<?=$filter8?>">
<input type=hidden name=filter9 value="<?=$filter9?>">
<input type=hidden name=filter10 value="<?=$filter10?>">
<input type=hidden name=filter11 value="<?=$filter11?>">
<input type=hidden name=filter12 value="<?=$filter12?>">
<input type=hidden name=filter13 value="<?=$filter13?>">
<input type=hidden name=filter14 value="<?=$filter14?>">
<input type=hidden name=filter15_1 value="<?=$filter15_1?>">
<input type=hidden name=filter15_2 value="<?=$filter15_2?>">
<input type=hidden name=filter15_3 value="<?=$filter15_3?>">
<input type=hidden name=order value="<?=$order?>">
<input type=hidden name=ids value="<?=$ids?>">
<input type=hidden name=check_all value="<?=$check_all?>">
<input type=hidden name=uncheck_all value="<?=$uncheck_all?>">

</form>

<?}?>
<script>
function checkall(wh)
 {
  if (wh==1) 
   {
    //for (i=<?=($cnt-$PPAGE+1)?>;i<<?=$cnt+1?>;i++) document.forms['req'].elements['ch'+i].checked=true;
    for (i=1;i<<?=$cnt?>+1;i++) document.forms['req'].elements['ch'+i].checked=true;   
    
    document.forms['req'].elements['check_all'].checked=true;
    document.forms['req'].elements['uncheck_all'].checked=false;
   } 
  else 
   {
    //for (i=<?=($cnt-$PPAGE+1)?>;i<<?=$cnt+1?>;i++) document.forms['req'].elements['ch'+i].checked=false; 
    for (i=1;i<<?=$cnt?>+1;i++) document.forms['req'].elements['ch'+i].checked=false;       
    
    document.forms['req'].elements['uncheck_all'].checked=true;
    document.forms['req'].elements['check_all'].checked=false;
   } 
 }


function gen_srch()
 {
  //if (document.forms['req'].elements['srch'])
  return document.forms['req'].elements['srch'].value;
 }

function gen_ids(f)
 {
  var ids_list;
  var t_str;
  <?if ($_SESSION['_viewalltype']!=0) {?>
  ids_list=","+document.forms[f].ids.value;
  for (i=1;i<<?=$cnt?>+1;i++)
   {
    if (document.forms['req'].elements['ch'+i].checked) 
     {
      if (ids_list.indexOf(","+document.forms['req'].elements['ch'+i].value+",")==-1)
        ids_list+=document.forms['req'].elements['ch'+i].value+",";
     }    
    else
     {
      t_str=","+document.forms['req'].elements['ch'+i].value+","
      if (ids_list.indexOf(t_str)!=-1)
        ids_list=ids_list.substr(0,ids_list.indexOf(t_str))+""+ids_list.substr(ids_list.indexOf(t_str)+t_str.length-1);
     }
   }
  return ids_list.substr(1);
  <?}else{?>
  return "";
  <?}?>
 }

function subm_paging(who)
 {
  document.forms['form_paging'].ids.value=gen_ids('form_paging');
  /*tmpstr=","+document.forms['req'].ids.value;
  for (i=1;i<<?=$cnt?>+1;i++)
   if (document.forms['req'].elements['ch'+i].checked) 
     if (tmpstr.indexOf(","+document.forms['req'].elements['ch'+i].value+",")==-1)
       document.forms['form_paging'].ids.value+=document.forms['req'].elements['ch'+i].value+",";*/
  document.forms['form_paging'].from.value=who;
  if (document.forms['req'].check_all.checked) document.forms['form_paging'].check_all.value=1;else document.forms['form_paging'].check_all.value=0;
  if (document.forms['req'].uncheck_all.checked) document.forms['form_paging'].uncheck_all.value=1;else document.forms['form_paging'].uncheck_all.value=0;
  document.forms['form_paging'].submit();
 }  

function subm_order()
 {
  document.forms['req'].ids.value=gen_ids('req');
  /*tmpstr=","+document.forms['req'].ids.value;
  for (i=1;i<<?=$cnt?>+1;i++)
   if (document.forms['req'].elements['ch'+i].checked) 
     if (tmpstr.indexOf(","+document.forms['req'].elements['ch'+i].value+",")==-1)
       document.forms['req'].ids.value+=document.forms['req'].elements['ch'+i].value+",";*/
  document.forms['req'].submit();
 }  

function mov()
 {
  document.forms['req'].ids.value=gen_ids('req');
  /*  tmpstr=","+document.forms['req'].ids.value;
  for (i=1;i<<?=$cnt?>+1;i++)
   if (document.forms['req'].elements['ch'+i].checked) 
     if (tmpstr.indexOf(","+document.forms['req'].elements['ch'+i].value+",")==-1)
       document.forms['req'].ids.value+=document.forms['req'].elements['ch'+i].value+",";*/
  document.forms['req'].action.value="update";
  
  if (document.forms['req'].ids.value!="" && (document.forms['req'].r_p_id.value!="" || document.forms['req'].r_s_id.value!="" || document.forms['req'].r_release.value!="" || document.forms['req'].r_assigned_u_id.value!="" || document.forms['req'].r_state.value!="" || document.forms['req'].r_priority.value!="" || document.forms['req'].r_source.value!="")) document.forms['req'].submit();
 }  
 
function remove(s, t) {
  i = s.indexOf(t);
  r = "";
  alert(i);
  if (i == -1) return s;
  r += s.substring(0,i) + remove(s.substring(i + t.length), t);
  return r;
  }

function sub_search()
 {
  df=document.forms['req'];
  df.elements.filter11.value="";
  for (i=0;i<df.elements.filter11_tmp.options.length;i++)
   {	
    if (df.elements.filter11_tmp.options[i].selected) df.elements.filter11.value+=df.elements.filter11_tmp.options[i].value+",";	
   }
  df.elements.ids.value=""; 
  df.submit();	     
 }
 
 
 </script>

<?if ($_SESSION['_viewalltype']==0) {?> 
<script language="JavaScript1.2" type="text/javascript" src="tablednd.js"></script>
<script language="JavaScript1.2" type="text/javascript">
var table = document.getElementById('table-1');
var tableDnD = new TableDnD();
var tmpOrder = "<?=$tmpOrder?>";

tableDnD.onDrop = function(table, row) {
    var rows = this.table.tBodies[0].rows;
	var debugStr = "";
    for (var i=0; i<rows.length; i++) {
		debugStr += rows[i].id+"|";
	}
	if (debugStr.indexOf(tmpOrder)==-1){	
		x_submitOrder2(debugStr, row.id, showData2);
		//document.getElementById(row.id).style.background='#FCBABA';
	}
	tmpOrder = debugStr;
}
tableDnD.init(table);
        <?php
		//include ajax generated javascript functions
        sajax_show_javascript();
        ?>		
		function showData2(q) {
			//var now = new Date();
			//document.getElementById('debug').innerHTML="<br>Categories order was updated successfully.<br>"+now.getHours()+":"+now.getMinutes()+":"+now.getSeconds()+"";
        }
        
        function getCh()
         {
          var list=tree.getAllChecked();
          return list;
         } 
        
</script>
<?}else{?>
<script language="JavaScript1.2" type="text/javascript">
function getCh()
         {
          return "";
         } 
         </script>
<?}?>

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
include("ini/txts/".$_SESSION['chlang']."/component.php");


if ($action=="update" && $ids!="" && ($r_assigned_u_id!="" || $r_p_id!="" || $r_state!="" || $r_priority!="" || $r_source!=""))
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
      $query="insert into requirements_history (r_parent_id, r_p_id, r_c_id, r_s_id, r_u_id, r_assigned_u_id, r_name, r_desc, r_state, r_type_r, r_priority, r_valid, r_link, r_satisfaction, r_dissatisfaction, r_conflicts, r_depends, r_component, r_source, r_risk, r_complexity, r_weight, r_points, r_creation_date, r_change_date, r_accept_date, r_accept_user, r_version, r_save_date, r_save_user, r_parent_id2, r_pos, r_stub, r_keywords, r_userfield1, r_userfield2, r_userfield3, r_userfield4, r_userfield5, r_userfield6) values ('".$row['r_id']."','".escapeChars($row['r_p_id'])."','".escapeChars($row['r_c_id'])."','".escapeChars($row['r_s_id'])."','".escapeChars($row['r_u_id'])."','".$row['r_assigned_u_id']."','".escapeChars($row['r_name'])."','".escapeChars($row['r_desc'])."','".escapeChars($row['r_state'])."','".escapeChars($row['r_type_r'])."','".escapeChars($row['r_priority'])."','".escapeChars($row['r_valid'])."','".escapeChars($row['r_link'])."','".escapeChars($row['r_satisfaction'])."','".escapeChars($row['r_dissatisfaction'])."','".escapeChars($row['r_conflicts'])."','".escapeChars($row['r_depends'])."','".escapeChars($row['r_component'])."','".escapeChars($row['r_source'])."','".escapeChars($row['r_risk'])."','".escapeChars($row['r_complexity'])."','".escapeChars($row['r_weight'])."','".escapeChars($row['r_points'])."','".escapeChars($row['r_creation_date'])."','".escapeChars($row['r_change_date'])."','".escapeChars($row['r_accept_date'])."','".escapeChars($row['r_accept_user'])."','".escapeChars($row['r_version'])."',DATE_ADD( NOW( ) , INTERVAL - ".TIME_DIFF_HOURS." HOUR),'".$_SESSION['uid']."','".escapeChars($row['r_parent_id'])."','".escapeChars($row['r_pos'])."','".escapeChars($row['r_stub'])."','".escapeChars($row['r_keywords'])."','".escapeChars($row['r_userfield1'])."','".escapeChars($row['r_userfield2'])."','".escapeChars($row['r_userfield3'])."','".escapeChars($row['r_userfield4'])."','".escapeChars($row['r_userfield5'])."','".escapeChars($row['r_userfield6'])."')";
      mysql_query($query) or die(mysql_error());
   
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
<?if ($tmp!="") echo $tmp;?>
<div id="debug" name="debug">aa</div>
<form method="post" action="" name="req">
<input type=hidden name=from value="<?=$from?>">
<input type=hidden name=order value="">
<input type=hidden name=ids value="">
<input type=hidden name=action value="">
<input type=hidden name=filter11 value="">
<table border="0" width="100%">
  <tr valign="top">
    <td>
      <table border="0" cellpadding="2" cellspacing="2" class="content" width="50%">
	 <tr>
	   <td align="left" class="gray" nowrap>
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
		$query4="select * from releases order by r_name asc";
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
		<?//state list	        
	          include("ini/txts/".$_SESSION['chlang']."/component.php");
		  echo makeSelect($component_array,$r_component);
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
			$query4="select * from keywords order by k_name asc";
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
	            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$lng[17][18]?>: <input type="text" size=30 name="filter5" value="<?=stripslashes(htmlspecialchars($filter5))?>">&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" onclick="sub_search();" value="<?=$lng[17][17]?>">
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
      </table>
    </td>
  </tr>  
  <tr valign="top">
    <td><?if ($_SESSION['_viewalltype']==0) {echo "<br/>".$lng[17][62];echo "<br/>".$lng[17][63];}?>
<br></td>
  </tr> 
  
  
  <tr valign="top">
    <td width="50%">
	<table <?if ($_SESSION['_viewalltype']==0) echo "id='table-1'";?> border="0" cellpadding="2" cellspacing="2" class="content" width="50%">
	  <?
	  //sortable columns
          if ($order=="") $order="r_change_date desc";

	  //search
	  if ($filter1!="") $search.=" and r.r_u_id=".$filter1;
	  if ($filter2!="") $search.=" and r.r_state=".$filter2;
	  if ($filter4!="") $search.=" and r.r_priority=".$filter4;
	  if ($filter5!="") $search.=" and (r.r_name like ('%".escapeChars($filter5)."%') or r.r_desc like ('%".escapeChars($filter5)."%') or r.r_source like ('%".escapeChars($filter5)."%') or p.p_name like ('%".escapeChars($filter5)."%'))";
	  if ($filter6!="") $search.=" and r.r_assigned_u_id=".$filter6;
	  if ($filter7!="") 
	   {
	    $query4="select * from project_releases where pr_r_id=".$filter7;
	    $rs4 = mysql_query($query4) or die(mysql_error());
	    $new_pr="(0";
	    while($row4=mysql_fetch_array($rs4)) 
	     {
	      $new_pr.=",".$row4['pr_p_id'];
	     } 
	    $new_pr.=")";	 
	    $search.=" and r.r_p_id in ".$new_pr;
	   } 
	  if ($filter8!="") $search.=" and r.r_component=".$filter8;
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

	  //paging query, number of results (from the param.php file) per page displayed
	  $paging=$PPAGE;
	  $query="select count(*) from requirements r left outer join projects p on r.r_p_id=p.p_id where r.r_p_id in (".$project_list.")".$search;
	  $rs = mysql_query($query) or die(mysql_error());
	  if($row=mysql_fetch_array($rs)) $all_count=$row[0];
	  if ($from=="") $from=0; 
	  if ($from+$paging>$all_count) $cnt2=$all_count;
	  else $cnt2=$from+$paging;
	  ?>
	  <tr class="gray">
	    <td colspan="13">&nbsp;<b><?=$lng[4][3]?> (<?=($all_count==0)?"0":($from+1)?> - <?=$cnt2?> / <?=$all_count?> )</b></td>
	  </tr>
	  <tr class="light_blue">
	    <td>&nbsp;</td>
	    <td align=center>&nbsp;<a href="#" onclick="document.forms['req'].order.value='r.r_id <?if ($order=='r.r_id asc') echo "desc";else echo "asc";?>';document.forms['req'].submit();"><?=$lng[17][1]?></a></td>
	    <?if ($_SESSION['_viewalltype']==0) {?><td align=center>&nbsp;<?=$lng[17][44]?></td><?}?>
	    <td width=200>&nbsp;<a href="#" onclick="document.forms['req'].order.value='r.r_name <?if ($order=='r.r_name asc') echo "desc";else echo "asc";?>';document.forms['req'].submit();"><?=$lng[17][2]?></a></td>
	    <td nowrap>&nbsp;<a href="#" onclick="document.forms['req'].order.value='p.p_name <?if ($order=='p.p_name asc') echo "desc";else echo "asc";?>';document.forms['req'].submit();"><?=$lng[17][7]?></a></td>
	    <td nowrap>&nbsp;<a href="#" onclick="document.forms['req'].submit();"><?=$lng[17][36]?></a></td>
	    <td nowrap>&nbsp;<a href="#" onclick="document.forms['req'].order.value='u.u_name <?if ($order=='u.u_name asc') echo "desc";else echo "asc";?>';document.forms['req'].submit();"><?=$lng[17][3]?></a></td>
	    <td nowrap>&nbsp;<a href="#" onclick="document.forms['req'].order.value='r.r_state <?if ($order=='r.r_state asc') echo "desc";else echo "asc";?>';document.forms['req'].submit();"><?=$lng[17][16]?></a></td>
	    <td nowrap>&nbsp;<a href="#" onclick="document.forms['req'].order.value='u_name2 <?if ($order=='u_name2 asc') echo "desc";else echo "asc";?>';document.forms['req'].submit();"><?=$lng[17][4]?></a></td>
	    <td nowrap>&nbsp;<a href="#" onclick="document.forms['req'].order.value='r.r_priority <?if ($order=='r.r_priority asc') echo "desc";else echo "asc";?>';document.forms['req'].submit();"><?=$lng[17][5]?></a></td>
	    <td nowrap>&nbsp;<a href="#" onclick="document.forms['req'].order.value='r.r_component <?if ($order=='r.r_component asc') echo "desc";else echo "asc";?>';document.forms['req'].submit();"><?=$lng[17][42]?></a></td>
	    <td nowrap>&nbsp;<a href="#" onclick="document.forms['req'].order.value='r.r_creation_date <?if ($order=='r.r_creation_date asc') echo "desc";else echo "asc";?>';document.forms['req'].submit();"><?=$lng[17][6]?></a></td>
	    <td nowrap>&nbsp;<a href="#" onclick="document.forms['req'].order.value='r.r_change_date <?if ($order=='r.r_change_date asc') echo "desc";else echo "asc";?>';document.forms['req'].submit();"><?=$lng[17][8]?></a></td>
	  </tr>
	  <?
	  //getting requirements - recently modified
	  $cnt=0;
	  //if tree view
	  if ($_SESSION['_viewalltype']==0) 
	   {
	    $query="select * from requirements where r_p_id in (".$project_list.") and r_parent_id=0 order by r_pos asc";
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
	      $query="select r.*, date_format(r.r_creation_date, '%d.%m.%Y %H:%i') as d1, date_format(r.r_change_date, '%d.%m.%Y %H:%i') as d2, p.p_id,p.p_name,u.u_name,u2.u_name as u_name2 from requirements r left outer join projects p on r.r_p_id=p.p_id left outer join users u on r.r_u_id=u.u_id left outer join users u2 on r.r_assigned_u_id=u2.u_id where r_id=".substr($val,strpos($val,"|")+1)." and r.r_p_id in (".$project_list.") ".$search." order by ".$order.", r_change_date desc Limit ".$from.",".$paging;
	      $rs = mysql_query($query) or die(mysql_error());
	      if($row=mysql_fetch_array($rs)) 
	       {
	        $cnt++;
	        if (mktime (0,0,0,date("m"),date("d")-2,date("Y")) < mktime (0,0,0,substr($row['d2'],3,2),substr($row['d2'],0,2),substr($row['d2'],6,4))) $new=1;
	        else $new=0;	  
	    
	        //setting row colours
	        $cl=$state_colors_array[$row['r_state']];
	        
	        $tmpOrder.=$row['r_id']."|";
	    ?>
	  <tr id="<?=$row['r_id']?>" name="<?=substr($val,0,strpos($val,"|"))?>" class="blue" style="background-color:<?=$cl?>;">
	    <td width="20" valign="middle" align=center><input type="checkbox" name="ch<?=$cnt?>" value="<?=$row['r_id']?>" <?if (strstr(",".$ids,",".$row['r_id'].",")) echo "checked";?>></td>
	    <td width="30" valign="middle" align=center>&nbsp;<a href="index.php?inc=view_requirement&r_id=<?=$row['r_id']?>"><?=$new?"<b>":""?><?=$row['r_id']?><?=$new?"</b>":""?></a></td>
	    <td valign="middle" align=left><?for ($i=0;$i<substr_count($val, ".");$i++) echo "&nbsp;&nbsp;";?>&nbsp;<?=substr($val,0,strpos($val,"|"))?></td>
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
	    <td valign="middle">&nbsp;<?=$new?"<b>":""?><?=$component_array[$row['r_component']]?><?=$new?"</b>":""?></td>
	    <td valign="middle" width="110">&nbsp;<?=$new?"<b>":""?><?=htmlspecialchars($row['d1'])?><?=$new?"</b>":""?></td>
	    <td valign="middle" width="110">&nbsp;<?=$new?"<b>":""?><?=($row['d2']!="00.00.0000 00:00")?$row['d2']:"(".$row['d1'].")"?><?=$new?"</b>":""?></td>
	  </tr>	  
	  <?   }
	     }
	   } 
	  else //if normal view selected
	   {
	    $query="select r.*, date_format(r.r_creation_date, '%d.%m.%Y %H:%i') as d1, date_format(r.r_change_date, '%d.%m.%Y %H:%i') as d2, p.p_id,p.p_name,u.u_name,u2.u_name as u_name2 from requirements r left outer join projects p on r.r_p_id=p.p_id left outer join users u on r.r_u_id=u.u_id left outer join users u2 on r.r_assigned_u_id=u2.u_id where r.r_p_id in (".$project_list.") ".$search." order by ".$order.", r_change_date desc Limit ".$from.",".$paging;
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
	    <td width="20" valign="middle" align=center><input type="checkbox" name="ch<?=$cnt?>" value="<?=$row['r_id']?>" <?if (strstr(",".$ids,",".$row['r_id'].",")) echo "checked";?>></td>
	    <td width="30" valign="middle" align=center>&nbsp;<a href="index.php?inc=view_requirement&r_id=<?=$row['r_id']?>"><?=$new?"<b>":""?><?=$row['r_id']?><?=$new?"</b>":""?></a></td>
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
	    <td valign="middle">&nbsp;<?=$new?"<b>":""?><?=$component_array[$row['r_component']]?><?=$new?"</b>":""?></td>
	    <td valign="middle" width="110">&nbsp;<?=$new?"<b>":""?><?=htmlspecialchars($row['d1'])?><?=$new?"</b>":""?></td>
	    <td valign="middle" width="110">&nbsp;<?=$new?"<b>":""?><?=($row['d2']!="00.00.0000 00:00")?$row['d2']:"(".$row['d1'].")"?><?=$new?"</b>":""?></td>
	  </tr>	  
	  <?}?>
	<?}?>  
	  
	  <?
//displaying paging list
if ($all_count>$paging && $cnt!=0) 
 {
  $j=ceil($all_count/$paging);
  for ($i=0,$tmp_c=0;$i<$j;$i++,$tmp_c++) 
   {
    if ($i*$paging==$from) $tmp_paging.=($i+1)." &nbsp;";	
    else $tmp_paging.="<a href=# onclick='subm_paging(".($i*$paging).");return false;'>".($i+1)."</a> &nbsp;";
   } 
  $tmp_paging=substr($tmp_paging,0,strlen($tmp_paging)-1);
 }  
?>  
</table>
<table border="0" cellpadding="2" cellspacing="2" class="content" width="50%">
 <? if ($cnt>0 && $tmp_paging!="" && $_SESSION['_viewalltype']!=0){?>


<tr class="gray" align=left>
     <td colspan=13 align=left>&nbsp;<b>
     <?if ($from>0) echo "<a href=# onclick='subm_paging(".($from-$paging).");return false;'>&lt;</a>&nbsp;&nbsp;";?>
     <?=$tmp_paging?>
     <?if ($from<$all_count-$paging) echo "<a href=# onclick='subm_paging(".($from+$paging).");return false;'>&gt;</a>";?>
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
   <select name="r_p_id">
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
   <?=$lng[17][37]?>:&nbsp;<br>
   <select name="r_assigned_u_id">
      <option value='' <?if ($r_assigned_u_id=="") echo "selected";?>><?=$lng[17][26]?>
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
	for (i=0;i<document.forms.req.r_priority.length;i++)
	   if (document.forms.req.r_priority.options[i].value=='<?=$r_priority?>')
	       document.forms.req.r_priority.options[i].selected=true;
    </script>
    &nbsp;&nbsp; 
    </td>
    <td align=left valign=top>
    <?=$lng[17][41]?>:&nbsp;<br>
    <input type="text" name="r_source" value="<?=$r_source?>">
    &nbsp;&nbsp; 
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
<input type=hidden name=order value="<?=$order?>">
</form>
<script>
function subm_paging(who)
 {
  document.forms['form_paging'].from.value=who;
  document.forms['form_paging'].submit();
 }  

function mov()
 {
  for (i=1;i<<?=$cnt?>+1;i++)
   if (document.forms['req'].elements['ch'+i].checked) 
     document.forms['req'].ids.value+=document.forms['req'].elements['ch'+i].value+",";
  document.forms['req'].action.value="update";
  
  if (document.forms['req'].ids.value!="" && (document.forms['req'].r_p_id.value!="" || document.forms['req'].r_assigned_u_id.value!="" || document.forms['req'].r_state.value!="" || document.forms['req'].r_priority.value!="" || document.forms['req'].r_source.value!="")) document.forms['req'].submit();
 }  

function sub_search()
 {
  df=document.forms['req'];
  df.elements.filter11.value="";
  for (i=0;i<df.elements.filter11_tmp.options.length;i++)
   {	
    if (df.elements.filter11_tmp.options[i].selected) df.elements.filter11.value+=df.elements.filter11_tmp.options[i].value+",";	
   }
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
		x_submitOrder(debugStr, row.id, showData);
		document.getElementById(row.id).style.background='#FCBABA';
	}
	tmpOrder = debugStr;
}
tableDnD.init(table);
        <?php
		//include ajax generated javascript functions
        sajax_show_javascript();
        ?>		
		function showData(q) {
			var now = new Date();
			document.getElementById('debug').innerHTML="<br>Categories order was updated successfully.<br>"+now.getHours()+":"+now.getMinutes()+":"+now.getSeconds()+"";
        }
</script>
<?}?>
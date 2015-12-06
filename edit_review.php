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
// Page: "edit review" - editing/adding/deleting reviews

//check if logged
if (!($_SESSION['rights']=="2" || $_SESSION['rights']=="3" || $_SESSION['rights']=="4" || $_SESSION['rights']=="5")) header("Location:index.php");
//check if asssgined to review
if ($r_id!="" && $_SESSION['rights']!="5") 
 {
  $query="select * from review_users where ru_r_id='".$r_id."' and ru_u_id='".$_SESSION['uid']."'";
  $rs = mysql_query($query) or die(mysql_error());
  if($row=mysql_fetch_array($rs)) ;
  else header("Location:index.php");  
 }

if ($action=="delete" && $r_id!="")
 {
  $query="delete from review_comments where rc_rev_id=".$r_id;
  mysql_query($query) or die(mysql_error());
  
  $query="delete from review_users where ru_r_id=".$r_id;
  mysql_query($query) or die(mysql_error());
  
  $query="delete from review_requirements where rr_rev_id=".$r_id;
  mysql_query($query) or die(mysql_error());

  $query="delete from reviews where r_id=".$r_id;
  mysql_query($query) or die(mysql_error());
  header("Location:index.php?inc=manage_reviews");
 }

  if ($action=="add")
   {
    //work up the text
    $ta=str_replace('<link href="styles.css" rel="stylesheet" />','',stripslashes($ta));

    $query="insert into reviews (r_name, r_desc, r_date, r_status, r_p_id) values ('".escapeChars($r_name)."','".stripbr(escapeChars($ta))."',DATE_ADD( NOW( ) , INTERVAL - ".TIME_DIFF_HOURS." HOUR),'".escapeChars($r_status)."','".escapeChars($r_p_id)."')";
    mysql_query($query) or die(mysql_error());
    $r_id=mysql_insert_id();
    //header("Location:index.php?inc=manage_projects");
   }

  if ($action=="update" && $r_id!="")
   {
    //work up the text
    $ta=str_replace('<link href="styles.css" rel="stylesheet" />','',stripslashes($ta));

    $query="update reviews set r_name='".escapeChars($r_name)."', r_desc='".stripbr(escapeChars($ta))."', r_status='".escapeChars($r_status)."', r_p_id='".escapeChars($r_p_id)."' where r_id=".$r_id;
    mysql_query($query) or die(mysql_error());
    //header("Location:index.php?inc=manage_projects");
   }
 
  if ($action!="")
   {
    $query="delete from review_users where ru_r_id=".$r_id;
    mysql_query($query) or die(mysql_error());  
    
    $list = explode(",", substr($users_list,1));
    if ($users_list!="")
     {
      while (list ($key, $val) = each ($list))
       {
        $query="insert into review_users (ru_r_id, ru_u_id) values ('".$r_id."','".$val."')";
        mysql_query($query) or die(mysql_error());
       }
     }   
    //header("Location:index.php?inc=manage_projects");
   }
 
  if ($action!="")
   {   
    $query="delete from review_requirements where rr_rev_id=".$r_id;
    mysql_query($query) or die(mysql_error());    
    
    $req_ids2=",".$req_ids.","; 
    
    $list2 = explode(",",$req_ids);
    if ($req_ids!="")
     {
      while (list ($key2, $val2) = each ($list2))
       {    
        $r_parent_tmp=1;$tmp_id="";
	while ($r_parent_tmp!=0)
	 {
	  if ($tmp_id=="") $tmp_id=$val2;
	  else $tmp_id=$r_parent_tmp;
	  $query3="select r_id, r_name, r_parent_id from requirements where r_id=".$tmp_id;
  	  $rs3 = mysql_query($query3) or die(mysql_error());
  	  if($row3=mysql_fetch_array($rs3))
  	   {
  	    $r_parent_tmp=$row3['r_parent_id'];
  	    $parent=$row3['r_id'];
  	    //echo $parent."-";
  	    if (!strstr($req_ids2,",".$parent.",")) $req_ids2.=$parent.",";
  	   }
	 } 
       }
     $req_ids2=substr($req_ids2,1,strlen($req_ids2)-2);  
     //echo $req_ids2;
     $list = explode(",",$req_ids2);
     while (list ($key, $val) = each ($list))
       {    
        $query="insert into review_requirements (rr_rev_id, rr_req_id) values ('".$r_id."','".$val."')";      
        mysql_query($query) or die(mysql_error());
       }
     }
    //header("Location:index.php?inc=manage_projects");
   }  
 
 
 
if ($r_id!="") 
 {
  $query="select *, date_format(r_date, '%d.%m.%Y') as d1 from reviews where r_id=".$r_id;
  $rs = mysql_query($query) or die(mysql_error());
  if($row=mysql_fetch_array($rs)) 
   {
    $r_name=htmlspecialchars($row['r_name']);
    $r_desc=$row['r_desc'];
    $r_date=$row['d1'];
    $r_status=$row['r_status'];
    $r_p_id=$row['r_p_id'];
   }
 }  
 
//projects 
if ($_SESSION['rights']=="") $query_project2="select p_name, p_id from projects where p_status=1 order by p_name asc";
elseif ($_SESSION['rights']=="0" || $_SESSION['rights']=="1" || $_SESSION['rights']=="2" || $_SESSION['rights']=="3") $query_project2="select distinct(p.p_id), p.p_name from projects p left outer join project_users pu on p.p_id=pu.pu_p_id where ((pu.pu_u_id=".$_SESSION['uid']." and p.p_status<>2) or p.p_status=1) order by p.p_name asc";
elseif ($_SESSION['rights']=="4") $query_project2="select distinct(p.p_id), p.p_name from projects p left outer join project_users pu on p.p_id=pu.pu_p_id where (((pu.pu_u_id=".$_SESSION['uid']." or p_leader=".$_SESSION['uid'].") and p.p_status<>2) or  p.p_status=1) order by p_name asc";
else $query_project2="select p_name, p_id from projects where p_status<>2 order by p_name asc";

$rs = mysql_query($query_project2) or die(mysql_error());
while($row=mysql_fetch_array($rs)) 
 {
  if ($row['p_id']==$r_p_id) $project_list.="<option value='".$row['p_id']."' selected>".htmlspecialchars($row['p_name']);
  else $project_list.="<option value='".$row['p_id']."'>".htmlspecialchars($row['p_name']);
 }
 
 
?>
<?if ($tmp!="") echo $tmp;?>
<form method="post" name="f" action="">
<table border="0" width="70%">
  <tr valign="top">
    <td>
      <input type="hidden" name="r_id" value="<?=$r_id?>">
	<table border="0" cellpadding="2" cellspacing="2" class="content" width="100%">
	  <tr class="gray">
	    <td colspan="2" align="center"><b><?=($r_id=="")?$lng[39][2]:$lng[39][1]?></b></td>
	  </tr>
	  <tr class="blue" title="<?=$lng[39][7]?>">
	    <td align="right">&nbsp;<?=$lng[39][6]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;<input type="text" name="r_name" value="<?=$r_name?>" maxlength="90" size=95></td>
	  </tr>  
	  <tr class="light_blue" title="<?=$lng[39][11]?>">
	    <td align="right">&nbsp;<?=$lng[39][10]?>&nbsp;:&nbsp;</td>
	    <td><? 
		include("FCKeditor/fckeditor.php");
		$oFCKeditor = new FCKeditor('ta') ;
		$oFCKeditor->BasePath = 'FCKeditor/' ;
		$oFCKeditor->Value = $r_desc;
		$oFCKeditor->Width = '560';
		$oFCKeditor->Height = '300';
		$oFCKeditor->Create() ;
		?> 
	    </td>
	  </tr>  
	  <tr class="blue" title="<?=$lng[39][13]?>">
	    <td align="right">&nbsp;<?=$lng[39][12]?>&nbsp;:&nbsp;</td>
	    <td>
	       <select name="r_status">
	         <?include("ini/txts/".$_SESSION['chlang']."/review_status.php");?>
		 <?echo makeSelect($review_status_array,$r_status);?>
	       </select>
	       <script>
		 for (i=0;i<document.forms.f.r_status.length;i++)
		   if (document.forms.f.r_status.options[i].value=='<?=$r_status?>')
		      document.forms.f.r_status.options[i].selected=true;
	       </script>	    
	    </td>
	  </tr>  
	  <tr class="light_blue" title="<?=$lng[39][15]?>">
	    <td align="right">&nbsp;<?=$lng[39][14]?>&nbsp;:&nbsp;</td>
	    <td>
	      <select name="r_p_id">
	        <?=$project_list?>
	      </select>   
	    </td>
	  </tr>  
	  <tr class="blue" title="<?=$lng[39][17]?>">
	    <td align="right">&nbsp;<?=$lng[39][16]?>&nbsp;:&nbsp;</td>
	    <td><?=$r_date?$r_date:date("d.m.Y")?></td>
	  </tr>  
	</table>
      <input type="hidden" name="inc" value="edit_review">
      <input type="hidden" name="action" value="">	
      <!--/form-->	
    </td> 	 
  </tr>

<?
if ($r_id!="")
 {
  $tmp_list="0";
  $query="select u.* from users u, review_users ru where u.u_rights in (0,1,2,3,4,5) and u.u_id=ru.ru_u_id and ru.ru_r_id=".$r_id." order by u_name asc";
  $rs = mysql_query($query) or die(mysql_error());
  while($row=mysql_fetch_array($rs)) 
   {
    $p_users_list2.="<option value='".$row['u_id']."'>".htmlspecialchars($row['u_name']);
    if ($row['u_rights']==0) $p_users_list2.=" (".$lng[2][25].")";
    elseif ($row['u_rights']==1) $p_users_list2.=" (".$lng[2][14].")";
    elseif ($row['u_rights']==2) $p_users_list2.=" (".$lng[2][15].")";
    elseif ($row['u_rights']==3) $p_users_list2.=" (".$lng[2][16].")";
    elseif ($row['u_rights']==4) $p_users_list2.=" (".$lng[2][17].")";
    elseif ($row['u_rights']==5) $p_users_list2.=" (".$lng[2][18].")";
    $tmp_list.=",".$row['u_id'];
   } 

  $query="select * from users where u_rights in (0,1,2,3,4,5) and u_id not in (".$tmp_list.")";
  $rs = mysql_query($query) or die(mysql_error());
  while($row=mysql_fetch_array($rs)) 
   {
    $p_users_list.="<option value='".$row['u_id']."'>".htmlspecialchars($row['u_name']);
    if ($row['u_rights']==0) $p_users_list.=" (".$lng[2][25].")";
    elseif ($row['u_rights']==1) $p_users_list.=" (".$lng[2][14].")";
    elseif ($row['u_rights']==2) $p_users_list.=" (".$lng[2][15].")";
    elseif ($row['u_rights']==3) $p_users_list.=" (".$lng[2][16].")";
    elseif ($row['u_rights']==4) $p_users_list.=" (".$lng[2][17].")";
    elseif ($row['u_rights']==5) $p_users_list.=" (".$lng[2][18].")";
   }
 }
 else
  {
  $tmp_list="0";
  $query="select u.* from users u, review_users ru where u.u_rights in (0,1,2,3,4,5) and u.u_id=ru.ru_u_id and ru.ru_r_id=-11 order by u_name asc";
  $rs = mysql_query($query) or die(mysql_error());
  while($row=mysql_fetch_array($rs)) 
   {
    $p_users_list2.="<option value='".$row['u_id']."'>".htmlspecialchars($row['u_name']);
    if ($row['u_rights']==0) $p_users_list2.=" (".$lng[2][25].")";
    elseif ($row['u_rights']==1) $p_users_list2.=" (".$lng[2][14].")";
    elseif ($row['u_rights']==2) $p_users_list2.=" (".$lng[2][15].")";
    elseif ($row['u_rights']==3) $p_users_list2.=" (".$lng[2][16].")";
    elseif ($row['u_rights']==4) $p_users_list2.=" (".$lng[2][17].")";
    elseif ($row['u_rights']==5) $p_users_list2.=" (".$lng[2][18].")";
    $tmp_list.=",".$row['u_id'];
   } 

  $query="select * from users where u_rights in (0,1,2,3,4,5) and u_id not in (".$tmp_list.")";
  $rs = mysql_query($query) or die(mysql_error());
  while($row=mysql_fetch_array($rs)) 
   {
    $p_users_list.="<option value='".$row['u_id']."'>".htmlspecialchars($row['u_name']);
    if ($row['u_rights']==0) $p_users_list.=" (".$lng[2][25].")";
    elseif ($row['u_rights']==1) $p_users_list.=" (".$lng[2][14].")";
    elseif ($row['u_rights']==2) $p_users_list.=" (".$lng[2][15].")";
    elseif ($row['u_rights']==3) $p_users_list.=" (".$lng[2][16].")";
    elseif ($row['u_rights']==4) $p_users_list.=" (".$lng[2][17].")";
    elseif ($row['u_rights']==5) $p_users_list.=" (".$lng[2][18].")";
   }
  
  
  } 
?>
  <tr valign="top">
    <td>
      <!--form method="post" name="f2" action=""-->
      <!--input type="hidden" name="p_id" value="<?=$p_id?>"-->
	<table border="0" cellpadding="2" cellspacing="2" class="content" width="100%">
	  <tr class="gray" title="<?=$lng[10][23]?>">
	    <td colspan="3" align="center"><b><?=$lng[10][14]?></b></td>
	  </tr>
	 <tr class="blue">
	    <td align="center">
	       <br/><?=$lng[10][16]?><br/>
	       <select name="users_tmp" multiple size=10 style="width:190px;background:#F3F3F3;">
	       <?=$p_users_list?>
	       </select><br/><br/>	    
	    </td>
	    <td align="center"><a href="#" onclick="copyToList('users_tmp','users_tmp2','f');return false;"><b>==></b></a><br><br><a href="#" onclick="copyToList('users_tmp2','users_tmp','f');return false;"><b><==</b></a></td>
	    <td align="center">
	       <br/><?=$lng[10][17]?><br/>
	       <select name="users_tmp2" multiple size=10 style="width:190px;background:#F3F3F3;">
	       <?=$p_users_list2?>
	       </select><br/><br/>
	    </td>
	  </tr>
	   <!--tr class="gray">
	    <td colspan="3" align="center">
	       <input type="button" onclick="selectUsers();" value="<?=$lng[10][15]?>">
	    </td>
	  </tr-->  	    
	</table>
      <!--input type="hidden" name="status_old" value="<?=$p_status?>"-->
      <!--input type="hidden" name="inc" value="edit_project"-->
      <input type="hidden" name="users_list" value=""> 
      <!--input type="hidden" name="what" value=""--> 
      <!--/form-->	
    </td> 	 
  </tr>


<?
//requirements
if ($r_p_id!="") {
?>
  <tr valign="top">
    <td>
      <!--form method="post" name="f2" action=""-->
      <!--input type="hidden" name="p_id" value="<?=$p_id?>"-->
	<table border="0" cellpadding="2" cellspacing="2" class="content" width="100%">
	  <tr class="gray" title="<?=$lng[10][57]?>">
	    <td colspan="3" align="center"><b><?=$lng[10][56]?></b></td>
	  </tr>
	 <tr class="blue">
	    <td align="center" colspan="3">
	      	  <?
	//requirements
	include("ajax.php");

	    $query="select * from requirements where r_p_id=".$r_p_id." and r_parent_id=0 order by r_pos asc";	    
	    $rs = mysql_query($query) or die(mysql_error());
	    $cnt3=0;
	    while($row=mysql_fetch_array($rs)) 
	     {
	      $cnt3++;
	      $arr[]=$cnt3."|".$row['r_id'];
	      getTree2($row['r_id'],$cnt3,$arr);
	     }
	?>
	    
        
	        <script  src="dhtmlxTree/codebase/dhtmlxcommon.js"></script>
	    <script  src="dhtmlxTree/codebase/dhtmlxtree.js"></script>
            <div id="treeboxbox_tree" style="width:620;height:<?=($cnt3*18.5+180)?>">

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
            //tree.enableThreeStateCheckboxes(true);
            tree.setImagePath("dhtmlxTree/codebase/imgs/");
            //enable Drag&Drop
            //tree.enableDragAndDrop(1);
            //tree.enableSmartRendering(true);
            tree.enableCheckBoxes(1);


            //set my Drag&Drop handler
            tree.setDragHandler(myDragHandler);
            
            //tree.loadXML("dhtmlxTree/samples/events/tree.xml");
	    </script> 
	    
	    
	     <?
            
            
            $tmpOrder="";
	    while ($cnt3>0 && list ($key, $val) = each ($arr)) 
	     {
	      $query="select r.*, date_format(r.r_creation_date, '%d.%m.%Y %H:%i') as d1, date_format(r.r_change_date, '%d.%m.%Y %H:%i') as d2 from requirements r where r_id=".substr($val,strpos($val,"|")+1)." and r.r_p_id=".$r_p_id." order by r_change_date desc";
	      $rs = mysql_query($query) or die(mysql_error());
	      if($row=mysql_fetch_array($rs)) 
	       {
	        $cnt++;
	        $req_id=$row['r_id'];
	        $tmpOrder.=$req_id."|";
	        
	        $req_checked=0;
	        $query3="select * from review_requirements where rr_rev_id='".$r_id."' and rr_req_id='".$row['r_id']."'";
	        $rs3 = mysql_query($query3) or die(mysql_error());
	        if($row3=mysql_fetch_array($rs3)) $req_checked=1;
	        
	    ?>
	    
	  <script> 
            tree.insertNewChild(<?=$row['r_parent_id']?>,<?=$row['r_id']?>,"<a href='index.php?inc=view_requirement&r_id=<?=$row['r_id']?>'><?=htmlspecialchars($row['r_name'])?></a>",0,0,0,0,"<?=($req_checked)?"CHECKED":""?>");
     	  </script>
    	   
    	 	  
	  <?   }
	     }
	     ?>
	     <script> 
	     //tree.closeAllItems(0);
	     </script>
	    </td>
	  </tr>
	</table>
    </td> 	 
  </tr>
<?}?>


	  <tr class="gray">
	    <td colspan="3" align="center">
	       <?if ($r_id=="") {?><input type="button" onclick="sub('add');" value="<?=$lng[39][2]?>"><?}?>
	       <?if ($r_id!="") {?><input type="button" onclick="getCh();sub('update');" value="<?=$lng[39][3]?>">&nbsp;&nbsp;<input type="button" onclick="if (confirm('<?=$lng[39][9]?>')) sub('delete');" value="<?=$lng[39][4]?>"><?}?>
	    </td>
	  </tr>  	    

</table>




<input type="hidden" name="req_ids" value="">

</form>
<script>
function sub(what)
 {
  df=document.forms['f'];
  if (what!="delete") 
   {
    if (df.r_name.value=="") 
     {
      alert("<?=$lng[39][8]?>");
      df.r_name.focus();	
      return false;
     }
   }
  df.action.value=what;
  selectUsers();
  df.submit();	     
 }
 
function copyToList(from,to,form_name)
 {
  fromList = eval('document.forms["'+form_name+'"].' + from);
  toList = eval('document.forms["'+form_name+'"].' + to);
  if (toList.options.length > 0 && toList.options[0].value == 'temp')
   {
    toList.options.length = 0;
   }
  var sel = false;
  for (i=0;i<fromList.options.length;i++)
   {
    var current = fromList.options[i];
    if (current.selected)
     {
      sel = true;
      txt = current.text;
      val = current.value;
      toList.options[toList.length] = new Option(txt,val);
      fromList.options[i] = null;
      i--;
     }
   }
 }

function selectUsers()
 {
  document.forms['f'].users_list.value="";
  for (i=0;i<document.forms['f'].users_tmp2.options.length;i++)
   {	
    document.forms['f'].users_list.value+=","+document.forms['f'].users_tmp2.options[i].value;	
   }   
  //document.forms['f'].what.value="users_list";
  //document.forms['f'].submit();
 }	
 
function getCh()
 {
  var list=tree.getAllChecked();
  document.forms['f'].req_ids.value=list;
  return list;
 } 
</script>
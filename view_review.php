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
// Page: "View review" - showing review info

//check
if ($r_id=="") header("Location:index.php");
else
 {
  $query="select * from review_users where ru_r_id='".$r_id."' and ru_u_id='".$_SESSION['uid']."'";
  $rs = mysql_query($query) or die(mysql_error());
  if($row=mysql_fetch_array($rs)) ;
  else header("Location:index.php");  
 }

if ($rc_text!="") 
 {
  $query="insert into review_comments (rc_rev_id, rc_req_id, rc_text, rc_comment, rc_date, rc_u_id) values ('".$r_id."',0,'".escapeChars($rc_text)."','".escapeChars($rc_comment)."', now(),'".$_SESSION['uid']."')";      
  mysql_query($query) or die(mysql_error());
 }


$query="select r.*, date_format(r.r_date, '%d.%m.%Y') as d1, p.p_name from reviews r left outer join projects p on r.r_p_id=p.p_id where r.r_id=".$r_id;
$rs = mysql_query($query) or die(mysql_error());
if($row=mysql_fetch_array($rs)) 
 {
  $r_name=htmlspecialchars($row['r_name']);
  $r_p_id=$row['r_p_id'];
  $r_desc=$row['r_desc'];
  $r_date=$row['d1'];
  $r_status=$row['r_status'];
  $p_name=htmlspecialchars($row['p_name']);
 }
?>
<table border="0" width="100%">
  <tr valign="top">
    <td>
        <table border="0" cellpadding="2" cellspacing="2" class="content" width="100%">
	  <tr class="gray">	  
	    <td align="center">
	      <input type="button" value="<?=$lng[2][26]?>"  onclick="document.location.href='index.php?inc=pdf_review_fields&r_id=<?=$r_id?>&mode=landscape';" />
	      &nbsp;<input type="button" value="<?=$lng[2][38]?>"  onclick="document.location.href='index.php?inc=pdf_review_fields&r_id=<?=$r_id?>&mode=portrait';" />
	    </td>
	    <td align="center"><b><?=$lng[38][1]?></b></td>
	  </tr>
	  <tr class="blue" title="<?=$lng[39][7]?>">
	    <td align="right" width="20%">&nbsp;<?=$lng[39][6]?>&nbsp;:&nbsp;</td>
	    <td><?=$r_name?></td>
	  </tr>  
	  <tr class="light_blue" title="<?=$lng[39][17]?>">
	    <td align="right">&nbsp;<?=$lng[39][16]?>&nbsp;:&nbsp;</td>
	    <td><?=$r_date?></td>
	  </tr>  
	  <tr class="light_blue" title="<?=$lng[39][13]?>">
	    <td align="right">&nbsp;<?=$lng[39][12]?>&nbsp;:&nbsp;</td>
	    <td>
	      <?
	      include("ini/txts/".$_SESSION['chlang']."/review_status.php");
	      echo $review_status_array[$r_status];
	      ?>	    
	    </td>
	  </tr>  
	  <tr class="blue" title="<?=$lng[39][11]?>">
	    <td align="right">&nbsp;<?=$lng[39][10]?>&nbsp;:&nbsp;</td>
	    <td><?=$r_desc?></td>
	  </tr>  
	  <tr class="light_blue" title="<?=$lng[39][15]?>">
	    <td align="right">&nbsp;<?=$lng[39][14]?>&nbsp;:&nbsp;</td>
	    <td><a href='index.php?inc=view_project&p_switch=yes&project_id=<?=$r_p_id?>&p_id=<?=$r_p_id?>'><?=$p_name?></a></td>
	  </tr>  
	  <tr class="light_blue" valign="top" title="<?=$lng[39][19]?>">
	    <td align="right">&nbsp;<?=$lng[39][18]?>&nbsp;:&nbsp;</td>
	    <td>
	    <?
	    $query2="select u.* from review_users ru left outer join users u on ru.ru_u_id=u.u_id where ru.ru_r_id='".$r_id."' order by u.u_name asc";
	    $rs2 = mysql_query($query2) or die(mysql_error());
	    while($row2=mysql_fetch_array($rs2))
	     {
	      $usrs.=htmlspecialchars($row2['u_name']).",&nbsp;";
	     }
	    echo substr($usrs,0,strlen($usrs)-7);
	    ?>
	    </td>
	  </tr>     
	</table>
    </td> 	 
  </tr>
</table>
<br/>
<table border="0" width="100%">
  <tr valign="top">
    <td>
        <table border="0" cellpadding="2" cellspacing="2" class="content" width="100%">
	  <tr class="gray">
	    <td align="center"><b><?=$lng[40][1]?></b></td>
	  </tr>
	  <tr class="blue">
	    <td align="left">
	      	  <?
	    //requirements
	    include("ajax.php");
	    $query="select * from review_requirements where rr_rev_id='".$r_id."' order by rr_id asc";	    
	    $rs = mysql_query($query) or die(mysql_error());
	    $req_ids=",";
	    while($row=mysql_fetch_array($rs))
	     {
	      $parent_ids.=checkTree($row['rr_req_id']).",";
	      $req_ids.=$row['rr_req_id'].",";
	     } 
	    
	    $query="select * from requirements where r_id in (".$parent_ids."0) and r_parent_id=0 order by r_pos asc";	    
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
            //tree.enableCheckBoxes(1);


            //set my Drag&Drop handler
            tree.setDragHandler(myDragHandler);
            
            //tree.loadXML("dhtmlxTree/samples/events/tree.xml");
	    </script> 
	    
	    
	    <?
            $tmpOrder="";
	    while ($cnt3>0 && list ($key, $val) = each ($arr)) 
	     {
	      if (strstr($req_ids,substr($val,strpos($val,"|")+1)))
	       {
	        $query="select r.*, date_format(r.r_creation_date, '%d.%m.%Y %H:%i') as d1, date_format(r.r_change_date, '%d.%m.%Y %H:%i') as d2 from requirements r where r_id=".substr($val,strpos($val,"|")+1)." order by r_change_date desc";
	        $rs = mysql_query($query) or die(mysql_error());
	        if($row=mysql_fetch_array($rs)) 
	         {
	          $cnt++;
	          $req_id=$row['r_id'];
	          $tmpOrder.=$req_id."|";
	         
	          $comm_cnt=0;
	          $query3="select count(*) from review_comments where rc_req_id='".$req_id."' and rc_rev_id='".$r_id."'";
	          $rs3 = mysql_query($query3) or die(mysql_error());
	          if($row3=mysql_fetch_array($rs3)) $comm_cnt=$row3[0];
	        
	      ?>
	    
	    <script> 
              tree.insertNewChild(<?=$row['r_parent_id']?>,<?=$row['r_id']?>,"<a href='index.php?inc=view_requirement&viewtypefl=y&viewreview=y&r_id=<?=$row['r_id']?>&review_id=<?=$r_id?>' target='_blank'><?=htmlspecialchars($row['r_name'])?> (<?=$comm_cnt?> <?=$lng[40][3]?>)</a> ",0,0,0,0,"");
     	    </script>
    	   
    	 	  
	  <?     
	          }
	       } 
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
</table>

<br/>
<table border="0" width="100%">
  <tr valign="top">
    <td>  
      <?include("ini/txts/".$_SESSION['chlang']."/review_comments.php");?>
      <table border="0" cellpadding="2" cellspacing="2" class="content" width="100%">
	 <form name="f11">
 	  <tr class="gray">
	    <td colspan=2 align="center" width="100%"><b><?=$lng[16][22]?></b></td>
	  </tr>
	  <?
	  $cnt=0;
	  $query44="select rc.*, u.u_name, date_format(rc.rc_date, '%d.%m.%Y %H:%i') as d1 from review_comments rc left outer join users u on rc.rc_u_id=u.u_id where rc.rc_rev_id=".$r_id." and rc.rc_req_id=0 order by  rc.rc_date desc";
	  $rs44 = mysql_query($query44) or die(mysql_error());
	  while($row44=mysql_fetch_array($rs44)) 
	   {	
            //$cl=$review_colors_array[$row44['rc_comment']];
            if ($cnt) $cnt=0; else $cnt=1;
	  ?>
	  <tr class="<?if ($cnt) echo "light_";?>blue">
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
	         <input type="button" value="<?=$lng[16][23]?>" onclick="if (document.forms['f11'].rc_text.value!='') document.forms['f11'].submit();">
	    </td>
	  </tr>
	  <input type="hidden" name="inc" value="view_review">
	  <input type="hidden" name="r_id" value="<?=$r_id?>">
	  <input type="hidden" name="viewreview" value="y">
	  </form>
	</table>
	    
    </td> 	 
  </tr>
</table>
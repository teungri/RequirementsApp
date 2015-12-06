<?php
//SITE FUNCTIONS:

function submitOrder($parent, $r_id) {
         //$tmpOrder=substr($tmpOrder,1,strpos($tmpOrder,"|".$r_id)-1);
         //$parent=substr($tmpOrder,strrpos($tmpOrder,"|")+1);
         //if ($parent=="") $parent=0;
         //$parent=$tmpOrder;
         
         //die();
         
         //check tree endless cycle 
        /* $r_parent_tmp=1;$cnt=0;
         while ($r_parent_tmp!=0)
	  {
	   $cnt++;
	   if ($r_parent_tmp==$r_id) {$parent=-1;break 0;}
	   if ($tmp_id=="") $tmp_id=$parent;
	   else $tmp_id=$r_parent_tmp;
	   $query3="select r_id, r_name, r_parent_id from requirements where r_id=".$tmp_id;
	   //echo $r_id."-".$query3."<br>";
	   $rs3 = mysql_query($query3) or die(mysql_error());
	   if($row3=mysql_fetch_array($rs3))
	    {
	     $r_parent_tmp=$row3['r_parent_id'];
	     $parent=$row3['r_id'];
	    }  	       
	  } */
       
        //if ($parent!=-1) 
         {          
          //history
          $query="select * from requirements where r_id=".$r_id;
          $tmp.=$query."\n\r";
          $rs = mysql_query($query) or die(mysql_error());
        
        
          if($row=mysql_fetch_array($rs)) 
           {
            $query="insert into requirements_history (r_parent_id, r_p_id, r_release, r_c_id, r_s_id, r_stakeholder,r_glossary,r_keyword, r_u_id, r_assigned_u_id, r_name, r_desc, r_state, r_type_r, r_priority, r_valid, r_link, r_satisfaction, r_dissatisfaction, r_conflicts, r_depends, r_component, r_source, r_risk, r_complexity, r_weight, r_points, r_creation_date, r_change_date, r_accept_date, r_accept_user, r_version, r_save_date, r_save_user, r_parent_id2, r_pos, r_stub, r_keywords, r_userfield1, r_userfield2, r_userfield3, r_userfield4, r_userfield5, r_userfield6) values (\"".$r_id."\",\"".escapeChars($row['r_p_id'])."\",\"".escapeChars($row['r_release'])."\",\"".escapeChars($row['r_c_id'])."\",\"".escapeChars($row['r_s_id'])."\",\"".escapeChars($row['r_stakeholder'])."\",\"".escapeChars($row['r_glossary'])."\",\"".escapeChars($row['r_keyword'])."\",\"".escapeChars($row['r_u_id'])."\",\"".$row['r_assigned_u_id']."\",\"".escapeChars($row['r_name'])."\",\"".escapeChars($row['r_desc'])."\",\"".escapeChars($row['r_state'])."\",\"".escapeChars($row['r_type_r'])."\",\"".escapeChars($row['r_priority'])."\",\"".escapeChars($row['r_valid'])."\",\"".escapeChars($row['r_link'])."\",\"".escapeChars($row['r_satisfaction'])."\",\"".escapeChars($row['r_dissatisfaction'])."\",\"".escapeChars($row['r_conflicts'])."\",\"".escapeChars($row['r_depends'])."\",\"".escapeChars($row['r_component'])."\",\"".escapeChars($row['r_source'])."\",\"".escapeChars($row['r_risk'])."\",\"".escapeChars($row['r_complexity'])."\",\"".escapeChars($row['r_weight'])."\",\"".escapeChars($row['r_points'])."\",\"".escapeChars($row['r_creation_date'])."\",\"".escapeChars($row['r_change_date'])."\",\"".escapeChars($row['r_accept_date'])."\",\"".escapeChars($row['r_accept_user'])."\",\"".escapeChars($row['r_version'])."\",DATE_ADD( NOW( ) , INTERVAL - ".TIME_DIFF_HOURS." HOUR),\"".$_SESSION['uid']."\",\"".escapeChars($row['r_parent_id'])."\",\"".escapeChars($row['r_pos'])."\",\"".escapeChars($row['r_stub'])."\",\"".escapeChars($row['r_keywords'])."\",\"".escapeChars($row['r_userfield1'])."\",\"".escapeChars($row['r_userfield2'])."\",\"".escapeChars($row['r_userfield3'])."\",\"".escapeChars($row['r_userfield4'])."\",\"".escapeChars($row['r_userfield5'])."\",\"".escapeChars($row['r_userfield6'])."\")";
            $tmp.=$query."\n\r";
            mysql_query($query) or die(mysql_error());
            $rh_id=mysql_insert_id();
            $p_id=$row['r_p_id'];
            $r_parent_id_tmp=$row['r_parent_id'];
            $r_pos=$row['r_pos'];
           }  
        
	  $query2="select count(*) from requirements where r_parent_id=".$parent." and r_p_id=".$p_id;
          $tmp.=$query2."\n\r";
          $rs2 = mysql_query($query2) or die(mysql_error());
          if($row2=mysql_fetch_array($rs2)) $pos_cnt=$row2[0]+1;
   
          //correcting positions of nodes of the old parent
          //if ($r_parent_id_tmp!=0)
           {
            $query2="update requirements set r_pos=r_pos-1 where r_pos>".$r_pos." and r_parent_id=".$r_parent_id_tmp." and r_p_id=".$p_id;
            $tmp.=$query2."\n\r";
            $rs2 = mysql_query($query2) or die(mysql_error());
           }
        
          
          //taking last position of nodes of the new parent
          //if ($r_parent_id_tmp!=0)
           {
            //$query2="update requirements set r_pos=r_pos+1 where r_parent_id=".$parent." and r_p_id=".$p_id;
            $query23="select max(r_pos) from requirements where r_parent_id=".$parent." and r_p_id=".$p_id;
            $rs23 = mysql_query($query23) or die(mysql_error());
            if($row23=mysql_fetch_array($rs23)) 
             {
              $tmp.=$query23."\n\r";
              $max=$row23[0];
             } 
           }
          if ($max=="") $max=0;
          
          //saving requirement
          $query2="update requirements set r_parent_id=".$parent.", r_pos=".($max+1)." where r_id=".$r_id." and r_p_id=".$p_id;
          $tmp.=$query2."\n\r";
          mysql_query($query2) or die(mysql_error());
        
          //saving undo/redoes
          $current=0;$all_c=0;
	  $query2="select count(*), th_current from tree_history where th_u_id=".$_SESSION['uid']." and th_p_id=".$_SESSION['projects']." and th_date>=DATE_SUB(now(), INTERVAL 1 HOUR) group by th_current";
          $tmp.=$query2."\n\r";
	  $rs2 = mysql_query($query2) or die(mysql_error());
	  if($row2=mysql_fetch_array($rs2)) 
	   {
	    $all_c=$row2[0];
	    $current=$row2[1];
	   }
	  
	  if ($current>$all_c)      
	   {
	    $query2="update tree_history set th_current=".$all_c." where th_u_id=".$_SESSION['uid']." and th_p_id=".$_SESSION['projects']." and th_date>=DATE_SUB(now(), INTERVAL 1 HOUR)";
            $tmp.=$query2."\n\r";
	    mysql_query($query2) or die(mysql_error());
	   }
	   
          $query2="delete from tree_history where th_u_id=".$_SESSION['uid']." and th_p_id=".$p_id." and th_date>=DATE_SUB(now(), INTERVAL 1 HOUR) order by th_id desc limit ".$current;
          $tmp.=$query2."\n\r";
          mysql_query($query2) or die(mysql_error());
          
          $query2="update tree_history set th_current=0 where th_u_id=".$_SESSION['uid']." and th_p_id=".$p_id." and th_date>=DATE_SUB(now(), INTERVAL 1 HOUR)";
          $tmp.=$query2."\n\r";
          mysql_query($query2) or die(mysql_error());
	  
	  $query2="insert into tree_history (th_r_id,th_u_id,th_p_id,th_parent_old,th_parent_old_pos,th_parent_new,th_parent_new_pos,th_rh_id,th_date,th_current) values (".$r_id.",".$_SESSION['uid'].",".$_SESSION['projects'].",".$r_parent_id_tmp.",".$r_pos.",".$parent.",".($max+1).",".$rh_id.",now(),0)";
          $tmp.=$query2."\n\r";
	  mysql_query($query2) or die(mysql_error());
          
          $query2="update test set r_id='".$tmp."' where t_id=5";
          $rs2 = mysql_query($query2) or die(mysql_error());
         }
        return $tmp;
}

function submitOrder2($tmpOrder, $r_id) {
         $tmpPos=substr_count(substr($tmpOrder,0,strpos($tmpOrder,"|".$r_id."|")), "|"); 
       
        //if ($parent!=-1) 
         {          
          //history
          $query="select * from requirements where r_id=".$r_id;
          $tmp.=$query."\n\r";
          $rs = mysql_query($query) or die(mysql_error());
          
          if($row=mysql_fetch_array($rs)) 
           {
            $query="insert into requirements_history (r_parent_id, r_p_id,r_release, r_c_id, r_s_id, r_stakeholder,r_glossary,r_keyword, r_u_id, r_assigned_u_id, r_name, r_desc, r_state, r_type_r, r_priority, r_valid, r_link, r_satisfaction, r_dissatisfaction, r_conflicts, r_depends, r_component, r_source, r_risk, r_complexity, r_weight, r_points, r_creation_date, r_change_date, r_accept_date, r_accept_user, r_version, r_save_date, r_save_user, r_parent_id2, r_pos, r_stub, r_keywords, r_userfield1, r_userfield2, r_userfield3, r_userfield4, r_userfield5, r_userfield6) values (\"".$r_id."\",\"".escapeChars($row['r_p_id'])."\",\"".escapeChars($row['r_release'])."\",\"".escapeChars($row['r_c_id'])."\",\"".escapeChars($row['r_s_id'])."\",\"".escapeChars($row['r_stakeholder'])."\",\"".escapeChars($row['r_glossary'])."\",\"".escapeChars($row['r_keyword'])."\",\"".escapeChars($row['r_u_id'])."\",\"".$row['r_assigned_u_id']."\",\"".escapeChars($row['r_name'])."\",\"".escapeChars($row['r_desc'])."\",\"".escapeChars($row['r_state'])."\",\"".escapeChars($row['r_type_r'])."\",\"".escapeChars($row['r_priority'])."\",\"".escapeChars($row['r_valid'])."\",\"".escapeChars($row['r_link'])."\",\"".escapeChars($row['r_satisfaction'])."\",\"".escapeChars($row['r_dissatisfaction'])."\",\"".escapeChars($row['r_conflicts'])."\",\"".escapeChars($row['r_depends'])."\",\"".escapeChars($row['r_component'])."\",\"".escapeChars($row['r_source'])."\",\"".escapeChars($row['r_risk'])."\",\"".escapeChars($row['r_complexity'])."\",\"".escapeChars($row['r_weight'])."\",\"".escapeChars($row['r_points'])."\",\"".escapeChars($row['r_creation_date'])."\",\"".escapeChars($row['r_change_date'])."\",\"".escapeChars($row['r_accept_date'])."\",\"".escapeChars($row['r_accept_user'])."\",\"".escapeChars($row['r_version'])."\",DATE_ADD( NOW( ) , INTERVAL - ".TIME_DIFF_HOURS." HOUR),\"".$_SESSION['uid']."\",\"".escapeChars($row['r_parent_id'])."\",\"".escapeChars($row['r_pos'])."\",\"".escapeChars($row['r_stub'])."\",\"".escapeChars($row['r_keywords'])."\",\"".escapeChars($row['r_userfield1'])."\",\"".escapeChars($row['r_userfield2'])."\",\"".escapeChars($row['r_userfield3'])."\",\"".escapeChars($row['r_userfield4'])."\",\"".escapeChars($row['r_userfield5'])."\",\"".escapeChars($row['r_userfield6'])."\")";
            $tmp.=$query."\n\r";
            mysql_query($query) or die(mysql_error());
            $rh_id=mysql_insert_id();
            $p_id=$row['r_p_id'];
            $r_parent_id_tmp=$row['r_parent_id'];
            $r_pos=$row['r_pos'];
           }  

          if ($r_pos>$tmpPos) $query2="update requirements set r_pos=r_pos+1 where (r_pos<".$r_pos." and r_pos>=".$tmpPos.") and r_parent_id=".$r_parent_id_tmp." and r_p_id=".$p_id;
          else $query2="update requirements set r_pos=r_pos-1 where (r_pos>".$r_pos." and r_pos<=".$tmpPos.") and r_parent_id=".$r_parent_id_tmp." and r_p_id=".$p_id;
          $tmp.=$query2."\n\r";
          mysql_query($query2) or die(mysql_error());

          //saving requirement
          $query2="update requirements set r_pos=".$tmpPos." where r_id=".$r_id." and r_p_id=".$p_id;
          $tmp.=$query2."\n\r";
          mysql_query($query2) or die(mysql_error());
           
          //saving undo/redoes
          $current=0;$all_c=0;
	  $query2="select count(*), th_current from tree_history where th_u_id=".$_SESSION['uid']." and th_p_id=".$_SESSION['projects']." and th_date>=DATE_SUB(now(), INTERVAL 1 HOUR) group by th_current";
          $tmp.=$query2."\n\r";
	  $rs2 = mysql_query($query2) or die(mysql_error());
	  if($row2=mysql_fetch_array($rs2)) 
	   {
	    $all_c=$row2[0];
	    $current=$row2[1];
	   }
	  
	  if ($current>$all_c)      
	   {
	    $query2="update tree_history set th_current=".$all_c." where th_u_id=".$_SESSION['uid']." and th_p_id=".$_SESSION['projects']." and th_date>=DATE_SUB(now(), INTERVAL 1 HOUR)";
            $tmp.=$query2."\n\r";
	    mysql_query($query2) or die(mysql_error());
	   }
	   
          $query2="delete from tree_history where th_u_id=".$_SESSION['uid']." and th_p_id=".$p_id." and th_date>=DATE_SUB(now(), INTERVAL 1 HOUR) order by th_id desc limit ".$current;
          $tmp.=$query2."\n\r";
          mysql_query($query2) or die(mysql_error());
          
          $query2="update tree_history set th_current=0 where th_u_id=".$_SESSION['uid']." and th_p_id=".$p_id." and th_date>=DATE_SUB(now(), INTERVAL 1 HOUR)";
          $tmp.=$query2."\n\r";
          mysql_query($query2) or die(mysql_error());
	  
	  $query2="insert into tree_history (th_r_id,th_u_id,th_p_id,th_parent_old,th_parent_old_pos,th_parent_new,th_parent_new_pos,th_rh_id,th_date,th_current) values (".$r_id.",".$_SESSION['uid'].",".$_SESSION['projects'].",".$r_parent_id_tmp.",".$r_pos.",".$r_parent_id_tmp.",".$tmpPos.",".$rh_id.",now(),0)";
          $tmp.=$query2."\n\r";
	  mysql_query($query2) or die(mysql_error());
          
          $query2="update test set r_id='".$tmp."' where t_id=5";
          $rs2 = mysql_query($query2) or die(mysql_error());
         }
        return $tmp;     
}

//include Sajax
require("sajax.php");


//Sajax init
$sajax_request_type = "POST";
$sajax_debug_mode = "0";
sajax_init();
sajax_export("submitOrder"); //register all SITE FUNCTIONS
sajax_export("submitOrder2"); //register all SITE FUNCTIONS
sajax_handle_client_request();
?>
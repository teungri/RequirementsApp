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
// Page: "edit user" - editing/adding/deleting users

//check if logged
if ($_SESSION['rights']!="5") header("Location:index.php");

$tmp="";
if ($action=="delete" && $u_id!="")
 {
  $query="delete from users where u_id=".$u_id;
  mysql_query($query) or die(mysql_error());
  header("Location:index.php?inc=manage_users");
 }

if ($action=="reset" && $u_id!="")
 {
  $query="select * from users where u_id='".escapeChars($u_id)."'";
  $rs = mysql_query($query) or die(mysql_error());
  if($row=mysql_fetch_array($rs)) 
   {
    $tmp_pass=substr(md5(uniqid(8)),8);
   
    $query="update users set u_password='".pw($tmp_pass)."' where u_id=".$u_id;
    mysql_query($query) or die(mysql_error());

    //mailing user
    $to=$row['u_email'];
    $from=DEFAULT_EMAIL; 
    $subject = $lng[6][22];
  
    $headers="Content-type: text/plain; charset=utf-8\r\n";
    $headers .= "From: ".$from."\n\r";    
 
    $message = "\n\r".$lng[6][23]." ".escapeChars($tmp_pass)."\n\r";
    $message .= $lng[6][15].": ".escapeChars($u_username)."\n\r";
    $message .= "\n\r".$lng[6][17].": ".PROJECT_URL."\n\r";
    mail($to, $subject, $message, $headers); 
  
    header("Location:index.php?inc=manage_users");
   } 
 }

if ($action=="insert")
 {
  $query="select * from users where u_username='".escapeChars($u_username)."' and u_password='".pw($u_password)."'";
  $rs = mysql_query($query) or die(mysql_error());
  if($row=mysql_fetch_array($rs)) $tmp="<br><span class='error'>".$lng[6][10]."</span>";
  
  $query="select * from users where u_email='".escapeChars($u_email)."'";
  $rs = mysql_query($query) or die(mysql_error());
  if($row=mysql_fetch_array($rs)) $tmp="<br><span class='error'>".$lng[6][18]."</span>";
  
  $query="select * from users where u_name='".escapeChars($u_name)."'";
  $rs = mysql_query($query) or die(mysql_error());
  if($row=mysql_fetch_array($rs)) $tmp="<br><span class='error'>".$lng[6][19]."</span>";

  if ($tmp=="")
   {  

    //inserting new user
    $query="insert into users (u_username,u_password,u_name,u_email,u_rights) values ('".escapeChars($u_username)."','".pw($u_password)."','".escapeChars($u_name)."','".escapeChars($u_email)."','".escapeChars($u_rights)."')";
    mysql_query($query) or die(mysql_error());
    $u_id=mysql_insert_id();
          
    //mailing user
    $to=$u_email;
    $from=DEFAULT_EMAIL; 
    $subject = $lng[6][11];
  
    $headers="Content-type: text/plain; charset=utf-8\r\n";
    $headers .= "From: ".$from."\n\r";    
 
    $message = "\n\r".$lng[6][12]." ".escapeChars($u_username).$lng[6][13]."\n\r";
    $message .= "\n\r".$lng[6][14].":\n\r";
    $message .= $lng[6][15].": ".escapeChars($u_username)."\n\r";
    $message .= $lng[6][16].": ".escapeChars($u_password)."\n\r";
    $message .= "\n\r".$lng[6][17].": ".PROJECT_URL."\n\r";
    mail($to, $subject, $message, $headers); 
    
    //mailing administrator
    $to=DEFAULT_EMAIL;
    $from=DEFAULT_EMAIL; 
    $subject = $lng[6][20];
  
    $headers="Content-type: text/plain; charset=utf-8\r\n";
    $headers .= "From: ".$from."\n\r";    
 
    $message = "\n\r".$lng[6][5].": ".escapeChars($u_name)."\n\r";
    $message .= $lng[6][6].": ".escapeChars($u_email)."\n\r";
    mail($to, $subject, $message, $headers);    
    
    $query222="delete from project_users where pu_u_id=".$u_id;
    mysql_query($query222) or die(mysql_error());    
    
    $list = explode(",", substr($users_list,1));
    while (list ($key, $val) = each ($list))
     {
      $query="insert into project_users (pu_p_id, pu_u_id) values ('".$val."','".$u_id."')";
      mysql_query($query) or die(mysql_error());
     }
    
    header("Location:index.php?inc=manage_users");      
   }
 }
 
elseif ($action=="update" && $u_id!="")
 {
  $query="select * from users where u_username='".escapeChars($u_username)."' and u_id<>".$u_id;
  $rs = mysql_query($query) or die(mysql_error());
  if($row=mysql_fetch_array($rs)) $tmp="<br><span class='error'>".$lng[6][10]."</span>";
  
  $query="select * from users where u_email='".escapeChars($u_email)."' and u_id<>".$u_id;
  $rs = mysql_query($query) or die(mysql_error());
  if($row=mysql_fetch_array($rs)) $tmp="<br><span class='error'>".$lng[6][18]."</span>";
  
  $query="select * from users where u_name='".escapeChars($u_name)."' and u_id<>".$u_id;
  $rs = mysql_query($query) or die(mysql_error());
  if($row=mysql_fetch_array($rs)) $tmp="<br><span class='error'>".$lng[6][19]."</span>";

  if ($tmp=="")
   {
    $query="update users set u_name='".escapeChars($u_name)."', u_username='".escapeChars($u_username)."', u_email='".escapeChars($u_email)."', u_rights='".escapeChars($u_rights)."' where u_id=".$u_id;
    mysql_query($query) or die(mysql_error());
    
    $query="delete from project_users where pu_u_id=".$u_id;
    mysql_query($query) or die(mysql_error());    
    
    $list = explode(",", substr($users_list,1));
    while (list ($key, $val) = each ($list))
     {
      $query="insert into project_users (pu_p_id, pu_u_id) values ('".$val."','".$u_id."')";
      mysql_query($query) or die(mysql_error());
     }
    header("Location:index.php?inc=manage_users");
   } 
 }



if ($u_id!="") 
 {
  $query="select * from users where u_id=".$u_id;
  $rs = mysql_query($query) or die(mysql_error());
  if($row=mysql_fetch_array($rs)) 
   {
    $u_name=htmlspecialchars($row['u_name']);
    $u_username=htmlspecialchars($row['u_username']);
    $u_email=$row['u_email'];
    $u_rights=$row['u_rights'];
   }
 }  
?>
<?=$tmp?>
<form method="post" name="f" action="">
<table border="0" width="70%">
  <tr valign="top">
    <td>
      <input type="hidden" name="u_id" value="<?=$u_id?>">
	<table border="0" cellpadding="2" cellspacing="2" class="content" width="100%">
	  <tr class="gray">
	    <td colspan="2" align="center"><b><?=$lng[12][1]?></b></td>
	  </tr>
	  <tr class="blue">
	    <td align="right">&nbsp;<?=$lng[12][2]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;<input type="text" name="u_name" value="<?=$u_name?>" maxlength="90" size=60></td>
	  </tr>  
	  <tr class="blue">
	    <td align="right">&nbsp;<?=$lng[12][3]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;<input type="text" name="u_username" value="<?=$u_username?>" maxlength="90" size=60></td>
	  </tr>  
	  <?if ($u_id=="") {?>
	  <tr class="blue">
	    <td align="right">&nbsp;<?=$lng[12][20]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;<input type="password" name="u_password"></td>
	  </tr>
	  <tr class="blue">
	    <td align="right">&nbsp;<?=$lng[12][21]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;<input type="password" name="u_password2"></td>
	  </tr>
	  <?}?>
	  <tr class="blue">
	    <td align="right">&nbsp;<?=$lng[12][4]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;<input type="text" name="u_email" value="<?=$u_email?>" maxlength="90" size=60></td>
	  </tr>  
	  <tr class="light_blue">
	    <td align="right">&nbsp;<?=$lng[12][5]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;
	      <select name="u_rights">
	        <option value="0" <?if ($u_rights==0) echo "selected";?>><?=$lng[2][25];?>
	        <option value="1" <?if ($u_rights==1) echo "selected";?>><?=$lng[2][14];?>
	        <option value="2" <?if ($u_rights==2) echo "selected";?>><?=$lng[2][15];?>
	        <option value="3" <?if ($u_rights==3) echo "selected";?>><?=$lng[2][16];?>
	        <option value="4" <?if ($u_rights==4) echo "selected";?>><?=$lng[2][17];?>
	        <option value="5" <?if ($u_rights==5) echo "selected";?>><?=$lng[2][18];?>
	      </select>   
	    </td>
	  </tr>  
	</table>
      <input type="hidden" name="inc" value="edit_user">
      <input type="hidden" name="action" value="">	
      <!--/form-->	
    </td> 	 
  </tr>
<!--/table-->

<?
  if ($u_id!="")
   {
	  $tmp_list="0";
	  $query="select p.* from projects p, project_users pu where p.p_id=pu.pu_p_id and pu.pu_u_id=".$u_id." order by p_name asc";
	  $rs = mysql_query($query) or die(mysql_error());
	  while($row=mysql_fetch_array($rs)) 
	   {
	    $p_users_list2.="<option value='".$row['p_id']."'>".htmlspecialchars($row['p_name']);
	    $tmp_list.=",".$row['p_id'];
	   } 
	
	  $query="select * from projects where p_id not in (".$tmp_list.")";
	  $rs = mysql_query($query) or die(mysql_error());
	  while($row=mysql_fetch_array($rs)) 
	   {
	    $p_users_list.="<option value='".$row['p_id']."'>".htmlspecialchars($row['p_name']);
	   }
   }
  else 
  {
	  $tmp_list="0";
	  $query="select p.* from projects p, project_users pu where p.p_id=pu.pu_p_id and pu.pu_u_id=0 order by p_name asc";
	  $rs = mysql_query($query) or die(mysql_error());
	  while($row=mysql_fetch_array($rs)) 
	   {
	    $p_users_list2.="<option value='".$row['p_id']."'>".htmlspecialchars($row['p_name']);
	    $tmp_list.=",".$row['p_id'];
	   } 
	
	  $query="select * from projects where p_id not in (".$tmp_list.")";
	  $rs = mysql_query($query) or die(mysql_error());
	  while($row=mysql_fetch_array($rs)) 
	   {
	    $p_users_list.="<option value='".$row['p_id']."'>".htmlspecialchars($row['p_name']);
	   }
  
  } 
?>
<!--br/>
<table border="0" width="60%"-->
  <tr valign="top">
    <td>
      <!--form method="post" name="f2" action="">
      <input type="hidden" name="u_id" value="<?=$u_id?>"-->
	<table border="0" cellpadding="2" cellspacing="2" class="content" width="100%">
	  <tr class="gray">
	    <td colspan="3" align="center"><b><?=$lng[12][13]?></b></td>
	  </tr>
	 <tr class="blue">
	    <td align="center">
	       <br/><?=$lng[12][15]?><br/>
	       <select name="users_tmp" multiple size=10 style="width:190px;background:#F3F3F3;">
	       <?=$p_users_list?>
	       </select><br/><br/>	    
	    </td>
	    <td align="center"><a href="#" onclick="copyToList('users_tmp','users_tmp2','f');return false;"><b>==></b></a><br><br><a href="#" onclick="copyToList('users_tmp2','users_tmp','f');return false;"><b><==</b></a></td>
	    <td align="center">
	       <br/><?=$lng[12][16]?><br/>
	       <select name="users_tmp2" multiple size=10 style="width:190px;background:#F3F3F3;">
	       <?=$p_users_list2?>
	       </select><br/><br/>
	    </td>
	  </tr>
	   <!--tr class="gray">
	    <td colspan="3" align="center">
	       <input type="button" onclick="selectUsers();" value="<?=$lng[12][14]?>">
	    </td>
	  </tr-->  	    
	</table>
      <!--input type="hidden" name="inc" value="edit_user"-->
      <input type="hidden" name="users_list" value=""> 
      <!--/form-->	
    </td> 	 
  </tr>
	  <tr class="gray">
	    <td colspan="2" align="center">
	       <?if ($u_id!="") {?><input type="button" onclick="sub('update');" value="<?=$lng[12][6]?>">&nbsp;&nbsp;<input type="button" onclick="if (confirm('<?=$lng[12][12]?>')) sub('delete');" value="<?=$lng[12][7]?>"><?}?>
	       <?if ($u_id=="") {?><input type="button" onclick="sub('insert');" value="<?=$lng[12][17]?>"><?}?>
	       <?if ($_SESSION['rights']=="5") {?><input type="button" onclick="sub('reset');" value="<?=$lng[12][22]?>"><?}?>
	    </td>
	  </tr>  	    
</table>
</form>
<script>
function sub(what)
 {
  df=document.forms['f'];
  if (what!="delete") 
   {
    if (df.u_name.value=="") 
     {
      alert("<?=$lng[12][8]?>");
      df.u_name.focus();	
      return false;
     } 
    if (df.u_username.value=="") 
     {
      alert("<?=$lng[12][9]?>");
      df.u_username.focus();	
      return false;
     } 

    <?if ($u_id=="") {?>
    if (df.u_password.value=="") 
     {
      alert("<?=$lng[12][18]?>");
      df.u_password.focus();	
      return false;
     } 

    if (df.u_password.value!=df.u_password2.value) 
     {
      alert("<?=$lng[12][19]?>");
      df.u_password2.focus();	
      return false;
     }  
    <?}?>

    if (df.u_email.value=="") 
     {
      alert("<?=$lng[12][10]?>");
      df.u_email.focus();	
      return false;
     } 
    var filter=/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i
    if (!filter.test(df.u_email.value)) 
     {
      alert("<?=$lng[12][11]?>");
      df.u_email.focus();	
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
  //document.forms['f'].submit();
 }	
 
</script>
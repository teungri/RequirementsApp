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
// Page: "My profile" - user account

//check if logged
if ($_SESSION['uid']=="" || $_SESSION['username']=="guest") header("Location:index.php");

if ($save=="yes")
 {
  if ($u_password=="")
   {
    $query="select * from users where u_username='".escapeChars($u_username)."' and u_id<>".$_SESSION['uid'];
    $rs = mysql_query($query) or die(mysql_error());
    if($row=mysql_fetch_array($rs)) $tmp="<br><span class='error'>".$lng[8][6]."</span>";
   }
  else
   {
    $query="select * from users where u_username='".escapeChars($u_username)."' and u_password='".pw($u_password)."' and u_id<>".$_SESSION['uid'];
    $rs = mysql_query($query) or die(mysql_error());
    if($row=mysql_fetch_array($rs)) $tmp="<br><span class='error'>".$lng[8][7]."</span>";   
   }  
   
  $query="select * from users where u_email='".escapeChars($u_email)."' and u_id<>".$_SESSION['uid'];
  $rs = mysql_query($query) or die(mysql_error());
  if($row=mysql_fetch_array($rs)) $tmp="<br><span class='error'>".$lng[6][18]."</span>";
  
  $query="select * from users where u_name='".escapeChars($u_name)."' and u_id<>".$_SESSION['uid'];
  $rs = mysql_query($query) or die(mysql_error());
  if($row=mysql_fetch_array($rs)) $tmp="<br><span class='error'>".$lng[6][19]."</span>";
  
  //updating user
  if ($tmp=="")
   {
    if ($u_password=="") $query="update users set u_username='".escapeChars($u_username)."',u_name='".escapeChars($u_name)."',u_email='".escapeChars($u_email)."' where u_id=".$_SESSION['uid'];
    else $query="update users set u_username='".escapeChars($u_username)."',u_password='".pw($u_password)."',u_name='".escapeChars($u_name)."',u_email='".escapeChars($u_email)."' where u_id=".$_SESSION['uid'];
    mysql_query($query) or die(mysql_error());
    
    //changing session vars
    $_SESSION['email']=$u_email;
    $_SESSION['username']=$u_username;
    $_SESSION['name']=$u_name;
    
    $tmp="<br><span class='error'>".$lng[8][2]."</span>";
   }
 }
 
$query="select * from users where u_id='".$_SESSION['uid']."'";
$rs = mysql_query($query) or die(mysql_error());
if($row=mysql_fetch_array($rs)) 
 {
  $u_username=htmlspecialchars($row['u_username']);
  $u_password=htmlspecialchars($row['u_password']);
  $u_name=htmlspecialchars($row['u_name']);
  $u_email=htmlspecialchars($row['u_email']);
 }
?>
<table border="0">
  <tr valign="top">
    <td>
      <form method="post" name="f" action="">
	<table border="0" cellpadding="2" cellspacing="2" class="content" width="50%">
	  <tr class="gray">
	    <td colspan="2" align="center"><b><?=$lng[8][1]?></b></td>
	  </tr>
	  <tr class="blue">
	    <td align="right">&nbsp;<?=$lng[6][2]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;<input type="text" name="u_username" value="<?=$u_username?>"></td>
	  </tr>  
	  <tr class="light_blue">
	    <td align="right">&nbsp;<?=$lng[6][3]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;<input type="password" name="u_password"></td>
	  </tr>
	  <tr class="blue">
	    <td align="right">&nbsp;<?=$lng[6][4]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;<input type="password" name="u_password2"></td>
	  </tr>
	  <tr class="light_blue">
	    <td align="right">&nbsp;<?=$lng[6][5]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;<input type="text" name="u_name" value="<?=$u_name?>"></td>
	  </tr>  
	  <tr class="blue">
	    <td align="right">&nbsp;<?=$lng[6][6]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;<input type="text" name="u_email" value="<?=$u_email?>"></td>
	  </tr>  
	  <tr class="light_blue">
	    <td align="right">&nbsp;<?=$lng[8][9]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;
	       <?
	         switch($_SESSION['rights'])
	          {
	           case 0:echo $lng[2][25];break;
	           case 1:echo $lng[2][14];break;
	           case 2:echo $lng[2][15];break;
	           case 3:echo $lng[2][16];break;
	           case 4:echo $lng[2][17];break;
	           case 5:echo $lng[2][18];break;
	           default:echo $lng[2][6];
	          }
	        ?>  
	    </td>
	  </tr>  
	  <tr class="gray">
	    <td colspan="2" align="center"><input type="button" onclick="sub('');" value="<?=$lng[8][8]?>" name="Register"></td>
	  </tr>  	    
	</table>
      <input type="hidden" name="inc" value="my_profile">
      <input type="hidden" name="save" value="yes">	
      </form>	
    </td> 	 
  </tr>
</table>
<?=$tmp?>
<script>
function sub()
 {
  df=document.forms['f'];
 
  if (df.u_username.value=="") 
   {
    alert("<?=$lng[8][3]?>");
    df.u_username.focus();	
    return false;
   } 
  
  if (df.u_name.value=="") 
   {
    alert("<?=$lng[8][4]?>");	
    df.u_name.focus();	
    return false;
   } 
  
  if (df.u_email.value=="") 
   {
    alert("<?=$lng[8][5]?>");	
    df.u_email.focus();	
    return false;
   } 
  
  if (df.u_password.value!="" && df.u_password.value!=df.u_password2.value) 
   {
    alert("<?=$lng[6][9]?>");
    df.u_password2.focus();	
    return false;
   }  
   
  var filter=/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i
  if (!filter.test(df.u_email.value)) 
   {
    alert("<?=$lng[6][8]?>");
    df.u_email.focus();	
    return false;
   }  
   
  df.submit();	     
 }
</script>
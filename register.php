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
// Page: "Register" - user registartion


if ($u_username!="" && $u_password!="" && $u_name!="" && $u_email!="")
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
    $query="insert into users (u_username,u_password,u_name,u_email,u_rights) values ('".escapeChars($u_username)."','".pw($u_password)."','".escapeChars($u_name)."','".escapeChars($u_email)."','0')";
    mysql_query($query) or die(mysql_error());
    
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

    //mailing admin
    $to=DEFAULT_EMAIL;
    $from=DEFAULT_EMAIL; 
    $subject = $lng[6][20];
  
    $headers="Content-type: text/plain; charset=utf-8\r\n";
    $headers .= "From: ".$from."\n\r";    
 
    $message = "\n\r".$lng[6][5].": ".escapeChars($u_name)."\n\r";
    $message .= $lng[6][6].": ".escapeChars($u_email)."\n\r";
    mail($to, $subject, $message, $headers);    
    
    //logging user
    $_SESSION['uid']=mysql_insert_id();
    $_SESSION['email']=$u_email;
    $_SESSION['username']=$u_username;
    $_SESSION['name']=$u_name;
    $_SESSION['rights']=0;
    header("Location:index.php?inc=login");
   }
 }
?>
<table border="0">
  <tr valign="top">
    <td>
      <form method="post" name="f" action="">
	<table border="0" cellpadding="2" cellspacing="2" class="content" width="50%">
	  <tr class="gray">
	    <td colspan="2" align="center"><b><?=$lng[6][1]?></b></td>
	  </tr>
	  <tr class="blue">
	    <td align="right">&nbsp;<?=$lng[6][2]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;<input type="text" name="u_username" value="<?=$u_username?>"></td>
	  </tr>  
	  <tr class="blue">
	    <td align="right">&nbsp;<?=$lng[6][3]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;<input type="password" name="u_password"></td>
	  </tr>
	  <tr class="blue">
	    <td align="right">&nbsp;<?=$lng[6][4]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;<input type="password" name="u_password2"></td>
	  </tr>
	  <tr class="blue">
	    <td align="right">&nbsp;<?=$lng[6][5]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;<input type="text" name="u_name" value="<?=$u_name?>"></td>
	  </tr>  
	  <tr class="blue">
	    <td align="right">&nbsp;<?=$lng[6][6]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;<input type="text" name="u_email" value="<?=$u_email?>"></td>
	  </tr>  
	  <tr class="gray">
	    <td colspan="2" align="center"><input type="button" onclick="sub();" value="<?=$lng[6][1]?>" name="Register"></td>
	  </tr>  	    
	</table>
      <input type="hidden" name="inc" value="register">	
      </form>	
    </td> 	 
  </tr>
</table>
<?=$tmp?>
<script>
function sub()
 {
  df=document.forms['f'];
 
  if (df.u_username.value=="" || df.u_password.value=="" || df.u_name.value=="" || df.u_email.value=="") 
   {
    alert("<?=$lng[6][7]?>");	
    return false;
   } 
  
  if (df.u_password.value!=df.u_password2.value) 
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
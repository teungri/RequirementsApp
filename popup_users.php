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
// Page: "popup users" - editing/adding/deleting users in a popup

session_start();

include ("admin/inc/conn.php");//include settings file
include ("admin/inc/func.php");//include functions file
include ("ini/params.php");//include configuration file

//setting referer if not logged
if ($_SESSION['uid']=="" && $_SERVER['QUERY_STRING']!="" && !strstr($_SERVER['QUERY_STRING'],'login'))
{
 $_SESSION['http_ref']=$_SERVER['QUERY_STRING'];
} 


//default language
if ($_chlang!="") $_SESSION['chlang']=$_chlang;
if (!$_SESSION['chlang']) $_SESSION['chlang']="en";
include ("ini/lng/".$_SESSION['chlang'].".php");//include language file

//check if logged
if (!($_SESSION['rights']=="5")) header("Location:index.php");

//check if project applied
if ($p_id=="" || $p_id=="0") 
 {
  ?>
  <script>self.close();</script>
  <?
 }
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="description" content="<?=$lng[1][2]?>"/>
	<meta name="keywords" content="<?=$lng[1][3]?>"/>
	<title><?=$lng[1][1]?></title>
	<link rel='STYLESHEET' type='text/css' href='dhtmlxTree/samples/common/style.css'>
	<link rel="stylesheet" href="s.css" type="text/css"/>
</head>
<body bgcolor="#ffffff">

<?
if ($action=="add")
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
    
    $query="insert into project_users (pu_p_id, pu_u_id) values ('".$p_id."','".$u_id."')";
    mysql_query($query) or die(mysql_error());
    
    ?>
    <script>
      <?if ($where!="1") {?>
      opener.change_select();
      opener.document.forms['edit'].r_assigned_u_id_tmp.value='<?=$u_id?>';
      opener.document.forms['edit'].tmp_p_id.value=opener.document.forms['edit'].r_p_id.value;
      opener.document.forms['edit'].submit();
      self.close();
      <?}else{?>
      opener.document.forms['f'].submit();
      self.close();
      <?}?>      
    </script>
    
    <?   
   }
 }
 ?>
<table border="0" width="100%">
  <tr valign="top">
    <td>
    <?=$tmp?>
      <form method="post" name="f" action="">
<table border="0" width="100%">
  <tr valign="top">
    <td>
      <input type="hidden" name="c_id" value="<?=$c_id?>">
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
	  <tr class="gray">
	    <td colspan="2" align="center">
	       <input type="button" onclick="sub('add');" value="<?=$lng[12][17]?>">
	    </td>
	  </tr>   
	</table>

    </td> 	 
  </tr>	    
</table>
      <input type="hidden" name="inc" value="edit_case">
      <input type="hidden" name="where" value="<?=$where?>">
      <input type="hidden" name="p_id" value="<?=$p_id?>">
      <input type="hidden" name="action" value="">	
      </form>	
    </td> 	 
  </tr>
</table>


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
  df.submit();	     
 }    
</script>
 
 </body>
 </html>
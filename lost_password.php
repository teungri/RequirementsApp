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
// Page: "Lost password" - sending back username and password


if ($u_email!="")
 {
  $query="select * from users where u_email='".escapeChars($u_email)."'";
  $rs = mysql_query($query) or die(mysql_error());
  if($row=mysql_fetch_array($rs)) 
   {
    //creating new password
    $tmp_pass=uniqid(10);
    $query="update users set u_password='".pw($tmp_pass)."' where u_email='".escapeChars($u_email)."'";
    mysql_query($query) or die(mysql_error());
   
    //mailing user
    $to=$u_email;
    $from=DEFAULT_EMAIL; 
    $subject = $lng[7][6];
  
    $headers="Content-type: text/plain; charset=utf-8\r\n";
    $headers .= "From: ".$from."\n\r";    
 
    $message .= "\n\r".$lng[7][7].":\n\r";
    $message .= $lng[7][8].": ".escapeChars($row['u_username'])."\n\r";
    $message .= $lng[7][9].": ".$tmp_pass."\n\r";
    $message .= "\n\r".$lng[7][10].": ".PROJECT_URL."\n\r";
    mail($to, $subject, $message, $headers);  
    
    $tmp="<br><span class='error'>".$lng[7][4]."</span>"; 
    header("Location:index.php?inc=login&lp=yes"); 
   }
  else $tmp="<br><span class='error'>".$lng[7][5]."</span>";
 }
?>
<table border="0">
  <tr valign="top">
    <td>
      <form method="post" name="f" action="">
	<table border="0" cellpadding="2" cellspacing="2" class="content" width="50%">
	  <tr class="gray">
	    <td colspan="2" align="center"><b><?=$lng[7][1]?></b></td>
	  </tr>
	  <tr class="blue">
	    <td align="right">&nbsp;<?=$lng[7][2]?>&nbsp;:&nbsp;</td>
	    <td>&nbsp;<input type="text" name="u_email" value="<?=$u_email?>"></td>
	  </tr>  
	  <tr class="gray">
	    <td colspan="2" align="center"><input type="submit" value="<?=$lng[7][3]?>" name="Lost password"></td>
	  </tr>  	    
	</table>
      <input type="hidden" name="inc" value="lost_password">	
      </form>	
    </td> 	 
  </tr>
</table>
<?=$tmp?>

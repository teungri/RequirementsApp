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
// Page: "import - page for importing requirements from cvs/xls
//header('Content-Type: text/plain; charset=UTF-8');

function is_utf8($str) {
    $c=0; $b=0;
    $bits=0;
    $len=strlen($str);
    for($i=0; $i<$len; $i++){
        $c=ord($str[$i]);
        if($c > 128){
            if(($c >= 254)) return false;
            elseif($c >= 252) $bits=6;
            elseif($c >= 248) $bits=5;
            elseif($c >= 240) $bits=4;
            elseif($c >= 224) $bits=3;
            elseif($c >= 192) $bits=2;
            else return false;
            if(($i+$bits) > $len) return false;
            while($bits > 1){
                $i++;
                $b=ord($str[$i]);
                if($b < 128 || $b > 191) return false;
                $bits--;
            }
        }
    }
    return true;
}

function CSV2Array($content, $delim = ';', $encl = '"', $optional = 1)
{
    $reg = '/(('.$encl.')'.($optional?'?(?(2)':'(').
'[^'.$encl.']*'.$encl.'|[^'.
$delim.'\r\n]*))('.$delim.'|\r\n)/smi';

    preg_match_all($reg, $content, $treffer);
    $linecount = 0;

    for ($i = 0; $i<=count($treffer[3]);$i++)
    {
        $liste[$linecount][] = $treffer[1][$i];
        if ($treffer[3][$i] != $delim)
            $linecount++;
    }
    return $liste;
}

function isUTF8($str) {
        if ($str === mb_convert_encoding(mb_convert_encoding($str, "UTF-32", "UTF-8"), "UTF-8", "UTF-32")) {
            return true;
        } else {
            return false;
        }
    } 

function is_utf82($string) {
    
    // From http://w3.org/International/questions/qa-forms-utf-8.html
    return preg_match('%^(?:
          [\x09\x0A\x0D\x20-\x7E]            # ASCII
        | [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
        |  \xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
        | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
        |  \xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
        |  \xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
        | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
        |  \xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
    )*$%xs', $string);
    
} // function is_utf8


//check if logged
if (!($_SESSION['rights']=="4" || $_SESSION['rights']=="5")) header("Location:index.php");

if ( ! isset($_FILES['file1']['tmp_name']) ) $_FILES['file1']['tmp_name'] = "";
if (is_uploaded_file($_FILES['file1']['tmp_name']))
 {
  $tmp=""; 
  $extension=strtolower(substr($_FILES['file1']['name'],strrpos($_FILES['file1']['name'],".")+1));
  if ($extension!="csv") $tmp.="<span class='error'>".$lng[34][4]."</span><br>";
  
  /*$fd = fopen ($_FILES['file1']['tmp_name'], "r");
  while (!feof ($fd)) $buffer .= fgets($fd, 4096);
  fclose ($fd);
   
  $contents=explode("\n", $buffer);
  $cnt=0;
  while (list ($key, $val) = each ($contents))
   {
    //creating an array for each row
    $arr[$cnt]=explode(";", $val);
    $cnt++;
   }*/
   
  $cont = join('',file($_FILES['file1']['tmp_name']));
  
  /*echo mb_detect_encoding($cont);
  if (is_utf8($cont)) echo "y";
  else echo "n";
  if (isUTF8($cont)) echo "y";
  else echo "n";*/

  if (!is_utf8($cont)) $tmp.="<span class='error'>".$lng[34][15]."</span><br>";
  //else echo "n";
  
  $content = join('',file($_FILES['file1']['tmp_name']));
  $liste = CSV2Array($content);
  //echo $liste[1][6];
  //while (list ($key, $val) = each ($liste)) echo $val[1][1]."<br>";
  
  $cnt=sizeof($liste);
  if ($cnt==0) $tmp.="<span class='error'>".$lng[34][4]."</span><br>";
  if (!is_array($liste[$cnt])) $cnt--;
  
  //while (list ($key, $val) = each ($liste[0])) echo $val."<br>";
 // die();
  //echo $cnt;
 
 
 // $arr=$liste;
  //checks at first
  for ($i=1;$i<$cnt;$i++)  
   {
    $cnt2=0;
    while (list ($key, $val) = each ($liste[$i]))
     {
      //check for existing project
      if ($i==1 && $cnt2==0) 
       {
        $query="select * from projects where p_name='".escapeChars($val)."'";
        $rs = mysql_query($query) or die(mysql_error());
        if($row=mysql_fetch_array($rs)) {$p_id=$row['p_id'];$p_name=$val;}
        else $tmp.="<span class='error'>".$lng[34][6].$val.$lng[34][7]."</span><br>";
       } 
      
      //check for project missing 
      if ($cnt2==0 && $tmp=="" && $val!=$p_name) $tmp.="<span class='error'>".$lng[34][9].($i+1)."</span><br>";

      //check for existing requirement
      if ($cnt2==4 && $empty!="empty") 
       {
        $query="select * from requirements where r_p_id='".$p_id."' and r_name='".escapeChars($val)."'";
        $rs = mysql_query($query) or die(mysql_error());
        if($row=mysql_fetch_array($rs)) $tmp.="<span class='error'>".$lng[34][8].$val."</span><br>";
       } 
       
      //check for title missing 
      if ($cnt2==4 && $val=="") $tmp.="<span class='error'>".$lng[34][10].($i+1)."</span><br>";
       
      $cnt2++;
     }
   }

  //if no errors -> importing data
  
  if ($tmp=="")
   {
    //emptying project
    if ($empty=="empty")
     {
      $query="delete from requirements where r_p_id=".$p_id;
      mysql_query($query) or die(mysql_error());
     }

    for ($i=1;$i<$cnt;$i++)  
     {
      //check for project
      $p_id=0;
      $query="select * from projects where p_name='".escapeChars($liste[$i][0])."'";
      $rs = mysql_query($query) or die(mysql_error());
      if($row=mysql_fetch_array($rs)) $p_id=$row['p_id'];

      //check for author
      $u_id="";
      $query="select * from users where u_name='".escapeChars($liste[$i][1])."'";
      $rs = mysql_query($query) or die(mysql_error());
      if($row=mysql_fetch_array($rs)) $u_id=$row['u_id'];
      else
       {
        $query="insert into users (u_username,u_password,u_email,u_name,u_rights) values ('".escapeChars($liste[$i][1])."','".pw('tmp')."','please_change@ipi.ch','".escapeChars($liste[$i][1])."',0)";
        mysql_query($query) or die(mysql_error());
        $u_id=mysql_insert_id();
        
            //mailing administrator
	    $to=DEFAULT_EMAIL;
	    $from=DEFAULT_EMAIL; 
	    $subject = $lng[6][21];
	  
	    $headers="Content-type: text/plain; charset=utf-8\r\n";
	    $headers .= "From: ".$from."\n\r";    
	 
	    $message = "\n\r".$lng[6][5].": ".escapeChars($u_name)."\n\r";
	    //$message .= $lng[6][6].": ".escapeChars($u_email)."\n\r";
	    mail($to, $subject, $message, $headers);    

       }

      //check for keywords
      $keywords="";
      if ($liste[$i][6]!="")
       {
        $query="select * from keywords where k_name='".escapeChars($liste[$i][6])."'";
        $rs = mysql_query($query) or die(mysql_error());
        if($row=mysql_fetch_array($rs)) $k_id1=$row['k_id'];
        else
         {
          $query="insert into keywords (k_name) values ('".escapeChars($liste[$i][6])."')";
          mysql_query($query) or die(mysql_error());
          $k_id1=mysql_insert_id();
         } 
        $keywords.=$k_id1.",";
       }
      if ($liste[$i][7]!="")
       {
        $query="select * from keywords where k_name='".escapeChars($liste[$i][7])."'";
        $rs = mysql_query($query) or die(mysql_error());
        if($row=mysql_fetch_array($rs)) $k_id2=$row['k_id'];
        else
         {
          $query="insert into keywords (k_name) values ('".escapeChars($liste[$i][7])."')";
          mysql_query($query) or die(mysql_error());
          $k_id2=mysql_insert_id();
         } 
        $keywords.=$k_id2.",";
       }
      if ($liste[$i][8]!="")
       {
        $query="select * from keywords where k_name='".escapeChars($liste[$i][8])."'";
        $rs = mysql_query($query) or die(mysql_error());
        if($row=mysql_fetch_array($rs)) $k_id3=$row['k_id'];
        else
         {
          $query="insert into keywords (k_name) values ('".escapeChars($liste[$i][8])."')";
          mysql_query($query) or die(mysql_error());
          $k_id3=mysql_insert_id();
         } 
        $keywords.=$k_id3.",";
       }
  
      //check for date
      if ($liste[$i][3]!="") $r_date="'".parseDate($liste[$i][3], "%d.%m.%Y")."'";
      else $r_date="now()";
      
       
      $query="insert into requirements (r_p_id, r_u_id, r_name, r_desc, r_state, r_type_r, r_priority,r_satisfaction,r_dissatisfaction,r_source,r_points,r_creation_date,r_change_date, r_keywords, r_parent_id) values ('".$p_id."','".$u_id."','".escapeChars($liste[$i][4])."','".escapeChars($liste[$i][5])."','0','0','1','5','5','".escapeChars($liste[$i][2])."','".escapeChars($liste[$i][9])."',".$r_date.",now(),'".$keywords."','0')";
      mysql_query($query) or die(mysql_error());
     }
    if ($cnt==0) $tmp.="<span class='error'>".$cnt." ".$lng[34][12]."</span><br>"; 
    else $tmp.="<span class='error'>".($cnt-1)." ".$lng[34][12]."</span><br>"; 
   }
 }
?>
<?if ( ! isset($tmp) ) $tmp = "";if ($tmp!="") echo $tmp."<br>";?>
<table border="0" width="73%">
  <tr valign="top">
    <td>
      <form method="post" name="f" action="" enctype="multipart/form-data">
	<table border="0" cellpadding="2" cellspacing="2" class="content" width="100%">
	  <tr class="gray">
	    <td align="center">
	      <b><?=$lng[34][1]?></b>
	    </td>
	  </tr>
	  <tr class="gray">
	      <td align="center">&nbsp;&nbsp;<?=$lng[34][2]?>&nbsp;<input type="file" name="file1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$lng[34][14]?>&nbsp;<input type="checkbox" value="empty" name="empty"></td>
	  </tr> 
	  <tr class="gray">
	      <td align="center">&nbsp;&nbsp;<?=$lng[34][11]?><br><?=$lng[34][13]?></td>
	   </tr> 
	  <tr class="gray">
	      <td align="center">&nbsp;&nbsp;<input type="submit" value="<?=$lng[34][3]?>"></td>
	   </tr> 
	  <tr class="gray">
	      <td align="center">&nbsp;&nbsp;<?=$lng[34][16]?>: <a href="template.csv" target="_blank"><?=$lng[34][17]?></a></td>
	   </tr> 
	 </table>
      </form>	
    </td> 	 
  </tr>
</table>
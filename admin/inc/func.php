<?
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
// Page: "functions"

//register globals
foreach($_REQUEST as $k => $v) ${$k} = $v;

function isValidDate($date)
{
    if (preg_match("/^(\d{2}).(\d{2}).(\d{4})$/", $date, $matches)) {
        if (checkdate($matches[2], $matches[1], $matches[3])) {
            return true;
        }
    }

    return false;
}




//GET FORMATED DATE
	function getDateTime($pattern="", $mkt=""){
		if ($pattern==""){
			if (defined('DEFAULT_DATE_FORMAT') && DEFAULT_DATE_FORMAT!="") $pattern=DEFAULT_DATE_FORMAT;
			else $pattern="Y-m-d";
		}
		if (defined('TIME_DIFF_HOURS') && TIME_DIFF_HOURS>0){
			if ($mkt!="") $mkt=mktime((date("H",$mkt)+TIME_DIFF_HOURS), date("i",$mkt), date("s",$mkt), date("m",$mkt), date("d",$mkt), date("Y",$mkt));
			else $mkt=mktime((date("H")+TIME_DIFF_HOURS), date("i"), date("s"), date("m"), date("d"), date("Y"));
		}
		else if ($mkt=="") $mkt=mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));
		return date($pattern, $mkt);
	}


/**
 * Returns a formatted date from a string based on a given format
 *
 * Supported formats
 *
 * %Y - year as a decimal number including the century
 * %m - month as a decimal number (range 1 to 12)
 * %d - day of the month as a decimal number (range 1 to 31)
 *
 * %H - hour as decimal number using a 24-hour clock (range 0 to 23)
 * %M - minute as decimal number
 * %s - second as decimal number
 * %u - microsec as decimal number
 * @param string date  string to convert to date
 * @param string format expected format of the original date
 * @return string rfc3339 w/o timezone YYYY-MM-DD YYYY-MM-DDThh:mm:ss YYYY-MM-DDThh:mm:ss.s
 */
function parseDate( $date, $format ) {
  // Builds up date pattern from the given $format, keeping delimiters in place.
  if( !preg_match_all( "/%([YmdHMsu])([^%])*/", $format, $formatTokens, PREG_SET_ORDER ) ) {
   return false;
  }
  foreach( $formatTokens as $formatToken ) {
   $delimiter = preg_quote( $formatToken[2], "/" );
   if($formatToken[1] == 'Y') {
     $datePattern .= '(.{1,4})'.$delimiter;
   } elseif($formatToken[1] == 'u') {
     $datePattern .= '(.{1,5})'.$delimiter;
   } else {
     $datePattern .= '(.{1,2})'.$delimiter;
   }  
  }

  // Splits up the given $date
  if( !preg_match( "/".$datePattern."/", $date, $dateTokens) ) {
   return false;
  }
  $dateSegments = array();
  for($i = 0; $i < count($formatTokens); $i++) {
   $dateSegments[$formatTokens[$i][1]] = $dateTokens[$i+1];
  }
  
  // Reformats the given $date into rfc3339
  
  if( $dateSegments["Y"] && $dateSegments["m"] && $dateSegments["d"] ) {
   if( ! checkdate ( $dateSegments["m"], $dateSegments["d"], $dateSegments["Y"] )) { return false; }
   $dateReformated = 
     str_pad($dateSegments["Y"], 4, '0', STR_PAD_LEFT)
     ."-".str_pad($dateSegments["m"], 2, '0', STR_PAD_LEFT)
     ."-".str_pad($dateSegments["d"], 2, '0', STR_PAD_LEFT);
  } else {
   return false;
  }
  if( $dateSegments["H"] && $dateSegments["M"] ) {
   $dateReformated .=
     " ".str_pad($dateSegments["H"], 2, '0', STR_PAD_LEFT)
     .':'.str_pad($dateSegments["M"], 2, '0', STR_PAD_LEFT);
     
   if( $dateSegments["s"] ) {
     $dateReformated .=
       ":".str_pad($dateSegments["s"], 2, '0', STR_PAD_LEFT);
     if( $dateSegments["u"] ) {
       $dateReformated .= 
       '.'.str_pad($dateSegments["u"], 5, '0', STR_PAD_RIGHT);
     }
   }
  }

  return $dateReformated;
} 
 
function dateDiff($dformat, $endDate, $beginDate)
 {
  $date_parts1=explode($dformat, $beginDate);
  $date_parts2=explode($dformat, $endDate);
  $start_date=gregoriantojd($date_parts1[0], $date_parts1[1], $date_parts1[2]);
  $end_date=gregoriantojd($date_parts2[0], $date_parts2[1], $date_parts2[2]);
  return $end_date - $start_date;
}

//creating dropdowns from array

//$arr - array, $def - if there is a value to be display as selected, $blank - if present a blank option will be displayed
function makeSelect($arr,$def,$blank="")
 {
  $dropdown="";
  if ($blank!="") $dropdown="<option value='' selected>".$blank;
  while (list($k, $v) = each ($arr))
   {
    if ($def!="" && $def==$k) $dropdown.="<option value='".$k."' selected>".$v;     
    else $dropdown.="<option value='".$k."'>".$v;
   }

  return $dropdown;
 } 

//$arr - array, $def_arr - if there are values to be display as selected, $blank - if present a blank option will be displayed
function makeSelectMultiple($arr,$def_arr,$blank)
 {
  $dropdown="";
  if ($blank!="") $dropdown="<option value='' selected>- ".$blank;
  while (list($k, $v) = each ($arr)) 
   {
    if (in_array($k, $def_arr))  $dropdown.="<option value='".$k."' selected>".$v;     
    else $dropdown.="<option value='".$k."'>".$v;
   }
  return $dropdown;
 } 
 
//check tree endless cycle
function checkTree($r_id,$r_parent_tmp=-1,$cnt=0)
 {
  while ($r_parent_tmp!=0)
   {
    $cnt++;
    if ($r_parent_tmp==$r_id && $cnt>0) {$parent=-1;break 0;}
    if ($tmp_id=="") $tmp_id=$r_id;
    else $tmp_id=$r_parent_tmp;
    $query3="select r_id, r_name, r_parent_id from requirements where r_id=".$tmp_id;
    //echo $r_id."-".$query3."<br>";
    $rs3 = mysql_query($query3) or die(mysql_error());
    if($row3=mysql_fetch_array($rs3))
     {
      $r_parent_tmp=$row3['r_parent_id'];
      $parent=$row3['r_id'];
     }  	       
   } 
  return $parent;
 }

//generating tree
function getTree($parentID,$old,$depth_tmp=1,$depth=0)
 {
	//echo $parentID."<br>";
	$q="select r_id, r_name from requirements where r_parent_id='".$parentID."' order by r_pos asc";
	$rs=mysql_query($q) or die("...");
	while ($r=mysql_fetch_array($rs))
	 {
	  while ($depth_tmp!=0)
	   {
	    if ($tmp_id=="") $tmp_id=$r[0];
	    else $tmp_id=$depth_tmp;
	    $query3="select r_id, r_name, r_parent_id from requirements where r_id=".$tmp_id;
  	    $rs3 = mysql_query($query3) or die(mysql_error());
  	    if($row3=mysql_fetch_array($rs3)) {$depth_tmp=$row3['r_parent_id'];$depth++;}
  	    else break;
  	   } 
	     
	  //creating blank spaces depending on the current depth	  
	  $nb="";
	  for ($i=0;$i<$depth-1;$i++) $nb.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	  echo "<tr class='blue'>";
	  if ($r[0]==$old) echo "<td>&nbsp;".$nb."-".$r[1]."</td>";
	  else echo "<td>&nbsp;".$nb."-<a href='index.php?inc=view_requirement&r_id=".$r[0]."'>".$r[1]."</a></td>";
	  echo "</tr>";
	  getTree($r[0],$old);
	 }
 }
 
//generating tree for showing 1.1, 1.1.1, 1.1.2 etc. in front
function getTree2($parentID,$cifri_otpred="",&$arrXXX)
 {
  //echo $parentID."<br>";
  $q="select r_id, r_name from requirements where r_parent_id='".$parentID."' order by r_pos asc";
  $rs=mysql_query($q) or die("...");
 
  $broia4=1;
 
  while ($r=mysql_fetch_array($rs))
   {
    $predai_natatak=$cifri_otpred.".".$broia4;
    //echo "";
    //echo "<br>&nbsp;".$predai_natatak."&nbsp;".$r[1]."<br>";
    $arrXXX[]=$predai_natatak."|".$r[0];
    $broia4++;
    getTree2($r[0],$predai_natatak,$arrXXX);
   }
 } 

//generating tree for showing 1.1, 1.1.1, 1.1.2 etc. in front with 2 arrays - second containing only ids
function getTree2_1($parentID,$cifri_otpred="",&$arrXXX,&$arrYYY)
 {
  //echo $parentID."<br>";
  $q="select r_id, r_name from requirements where r_parent_id='".$parentID."' order by r_pos asc";
  $rs=mysql_query($q) or die("...");
 
  $broia4=1;
 
  while ($r=mysql_fetch_array($rs))
   {
    $predai_natatak=$cifri_otpred.".".$broia4;
    //echo "";
    //echo "<br>&nbsp;".$predai_natatak."&nbsp;".$r[1]."<br>";
    $arrXXX[]=$predai_natatak."|".$r[0];
    $arrYYY[]=$r[0];
    $broia4++;
    getTree2_1($r[0],$predai_natatak,$arrXXX,$arrYYY);
   }
 } 

//fixing requirement position in the tree after moving elements 
function fixPos($rid,$pr)
 {
  $q="select * from requirements where r_id='".$rid."'";
  $rs=mysql_query($q) or die("...");
  if ($row=mysql_fetch_array($rs)) $r_par=$row['r_parent_id'];
  $q="select * from requirements where r_parent_id='".$r_par."' and r_p_id='".$pr."' order by r_pos asc";
  $rs=mysql_query($q) or die("...");
  $cnt=0;
  while ($row=mysql_fetch_array($rs))
   {
    $cnt++;
    $q="update requirements set r_pos='".$cnt."' where r_id='".$row['r_id']."' and r_p_id='".$pr."'";
    mysql_query($q) or die("...");
   }
 } 


//escape special characters for saving into DB
function escapechars($str)
 {
  $str=addslashes(stripslashes($str));
  return $str;
 }

function escapeInputs($str)
 {
  $str=htmlspecialchars(stripslashes($str));
  return $str;
 }

//converting newlines into <br>
function breaks($str)
 {
  $str=str_replace("\n","<br>",$str);
  return $str;
 }

//converting newlines into <br>
function stripbr($str)
 {
  $str_arr=explode("<br />", $str);
  $cnt=0;$fl=0;$str="";
  while (list ($key, $val) = each ($str_arr))
   {
    if ($val!="") $fl=$cnt;
    $cnt++;
   }
  $cnt=0;     

  reset($str_arr);
  while (list ($key, $val) = each ($str_arr))
   {
    if ($cnt<=$fl) $str.=$val."<br />";
    $cnt++;
   }    
  return $str;
 }
 
//check for valid mail entered
function valid_email($email) {
  $result = TRUE;
  if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $email)) {
    $result = FALSE;
  }
  return $result;
}
 
function pw($pwd) 
 {
  global $pwkey;
  $result=sha1(md5($pwd.$pwkey));
  return $result;
 }
 
function unhtmlentities ($string) {
   $trans_tbl =get_html_translation_table (HTML_ENTITIES);
   $trans_tbl =array_flip ($trans_tbl );
   return strtr ($string ,$trans_tbl );
}


function exc($str)
 {
  $str=chr(255).chr(254).iconv( 'UTF-8', 'UTF-16LE//IGNORE',$str);
  //$str = chr(255).chr(254).mb_convert_encoding( $str, 'UTF-16LE', 'UTF-8');

  return $str;
 } 
 
?>
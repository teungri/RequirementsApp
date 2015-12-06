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
// Page: "Check for missing language elements" - this page is checking the language arrays for missing elements in all languages
?>
<?
  if ($l=="") $l="en";
  if ($l=="en")
   {
    $arr_ord = array('EN','DE','FR','IT');
    require_once ("../ini/lng/en.php");//include language file
    $arr1=$lng;//copy en array
    if ($lng=="") $arr1[0]="";
    unset($lng);
    require_once ("../ini/lng/de.php");//include language file
    $arr2=$lng;//copy de array
    if ($lng=="") $arr2[0]="";
    unset($lng);
    require_once ("../ini/lng/fr.php");//include language file
    $arr3=$lng;//copy fr array
    if ($lng=="") $arr3[0]="";
    unset($lng);
    require_once ("../ini/lng/it.php");//include language file
    $arr4=$lng;//copy it array
    if ($lng=="") $arr4[0]="";
    unset($lng);
   }
  elseif ($l=="de")
   {
    $arr_ord = array('DE','EN','FR','IT');
    require_once ("../ini/lng/de.php");//include language file
    $arr1=$lng;//copy de array
    if ($lng=="") $arr1[0]="";
    unset($lng);
    require_once ("../ini/lng/en.php");//include language file
    $arr2=$lng;//copy en array
    if ($lng=="") $arr2[0]="";
    unset($lng);
    require_once ("../ini/lng/fr.php");//include language file
    $arr3=$lng;//copy fr array
    if ($lng=="") $arr3[0]="";
    unset($lng);
    require_once ("../ini/lng/it.php");//include language file
    $arr4=$lng;//copy it array
    if ($lng=="") $arr4[0]="";
    unset($lng);
   }
  elseif ($l=="fr")
   {
    $arr_ord = array('FR','EN','DE','IT');
    require_once ("../ini/lng/fr.php");//include language file
    $arr1=$lng;//copy fr array
    if ($lng=="") $arr1[0]="";
    unset($lng);
    require_once ("../ini/lng/en.php");//include language file
    $arr2=$lng;//copy en array
    if ($lng=="") $arr2[0]="";
    unset($lng);
    require_once ("../ini/lng/de.php");//include language file
    $arr3=$lng;//copy de array
    if ($lng=="") $arr3[0]="";
    unset($lng);
    require_once ("../ini/lng/it.php");//include language file
    $arr4=$lng;//copy it array
    if ($lng=="") $arr4[0]="";
    unset($lng);
   }
  elseif ($l=="it")
   {
    $arr_ord = array('IT','EN','DE','FR');
    require_once ("../ini/lng/it.php");//include language file
    $arr1=$lng;//copy it array
    if ($lng=="") $arr1[0]="";
    unset($lng);
    require_once ("../ini/lng/en.php");//include language file
    $arr2=$lng;//copy en array
    if ($lng=="") $arr2[0]="";
    unset($lng);
    require_once ("../ini/lng/de.php");//include language file
    $arr3=$lng;//copy de array
    if ($lng=="") $arr3[0]="";
    unset($lng);
    require_once ("../ini/lng/fr.php");//include language file
    $arr4=$lng;//copy fr array
    if ($lng=="") $arr4[0]="";
    unset($lng);
   }

include("inc/conn_admin.php");
?> 
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<LINK HREF="css/styles_admin.css" REL=stylesheet>
</head>
<body bgcolor=#E6E6E6 topmargin=0 leftmargin=0>
<br>
<center>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td align=center height=10 class="td_no_border"><img src="img/b.gif" width=1 height=1></td>
  </tr>
  <tr>
    <td align=center class="title"><?=$lng[99][35]?><br><br></td>
  </tr>
  <tr>
    <td align=left class="title"><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?="'".$arr_ord[1]."' ".$lng[99][36]?><br><br></td>
  </tr>
  <tr>
       <td align=left><br>
  <?    
  foreach($arr1 as $key => $val)
   { 
    if (!array_key_exists($key, $arr2)) echo "&nbsp;&nbsp;&nbsp;".$lng[99][23]." lng[".$key."][*]<br>";
    else
     {
      foreach($arr1[$key] as $key2 => $val2)
       { 
        if(!array_key_exists($key2, $arr2[$key])) echo "&nbsp;&nbsp;&nbsp;".$lng[99][24]." lng[".$key."][".$key2."] - '".$val2."'<br>";
       }
     }
   }
    ?> 
       <br></td>
   </tr>
  <tr>
    <td align=left class="title"><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?="'".$arr_ord[2]."' ".$lng[99][36]?><br><br></td>
  </tr>
  <tr>
       <td align=left><br>
  <?    
  foreach($arr1 as $key => $val)
   { 
    if (!array_key_exists($key, $arr3)) echo "&nbsp;&nbsp;&nbsp;".$lng[99][23]." lng[".$key."][*]<br>";
    else
     {
      foreach($arr1[$key] as $key2 => $val2)
       { 
        if(!array_key_exists($key2, $arr3[$key])) echo "&nbsp;&nbsp;&nbsp;".$lng[99][24]." lng[".$key."][".$key2."] - '".$val2."'<br>";
       }
     }
   } 
?>        <br></td>
       </tr>
  <tr>
    <td align=left class="title"><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?="'".$arr_ord[3]."' ".$lng[99][36]?><br><br></td>
  </tr>
  <tr>
       <td align=left><br>
  <?    
  foreach($arr1 as $key => $val)
   { 
    if (!array_key_exists($key, $arr4)) echo "&nbsp;&nbsp;&nbsp;".$lng[99][23]." lng[".$key."][*]<br>";
    else
     {
      foreach($arr1[$key] as $key2 => $val2)
       { 
        if(!array_key_exists($key2, $arr4[$key])) echo "&nbsp;&nbsp;&nbsp;".$lng[99][24]." lng[".$key."][".$key2."] - '".$val2."'<br>";
       }
     }
   } 
?>        <br></td>
       </tr>

</table>
</center>
</body>
</html>

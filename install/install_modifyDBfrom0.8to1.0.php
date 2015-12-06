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
// Page: "Install DB" -this tool allows updating DB(with keeping the data) from release 0.8 to 1.0 during installation routine
$database=$db_name;
//$db_link2??


//check MySQL version and load the apropriate sql script
$db_sql_file = fopen($db_sql_file_url, "r");

$db_query=""; 
if ($db_sql_file) 
 {
  while (!feof($db_sql_file))
   {
    $buffer = fgets($db_sql_file, 4096);
    if ($buffer!="\n" && (substr($buffer,0,2)!="--" && substr($buffer,0,2)!="/*")) $db_query.=$buffer;
   }
  fclose($db_sql_file);
 }

$db_query=explode("CREATE", $db_query);
for ($i=0; $i<sizeof($db_query); $i++)
 {
  if ($db_query[$i] && $db_query[$i]!="\n" && trim($db_query[$i])!="") 
   {
    //echo "<br>".$db_query[$i]."<br>--";
    $table_key=substr($db_query[$i],22,strpos($db_query[$i],"(")-24);
    $table_val=substr($db_query[$i],strpos($db_query[$i],"(")+1,strpos($db_query[$i],"ENGINE")-strpos($db_query[$i],"(")-3);
    //echo "<br>".$table_key."-".$table_val."<br>--";
    if ($table_key!="") 
     {
      $tables_names[]=$table_key;
      $tables_structure[]=$table_val;
      $tables_all[]=$db_query[$i];
     } 
   } 
 }
//echo "==============".$tables_all[0];




//---------------  start checking DB tables - if missing attempts to create them  -----------------
$result = mysql_list_tables($database);
while ($row = mysql_fetch_row($result)) $tables_names2[]=$row[0];

while (list ($key, $val) = each ($tables_names))
 {
  if (!in_array($val,$tables_names2)) 
   { //missing tables
    if ($db_version[0]<5)
     {
      $tables_all[$key]=str_replace('SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO"','',$tables_all[$key]);
      $tables_all[$key]=str_replace("ENGINE=MyISAM","",$tables_all[$key]);
      $tables_all[$key]=str_replace("DEFAULT CHARSET=utf8","",$tables_all[$key]);
     }
    if ($tables_all[$key] && $tables_all[$key]!="\n" && trim($tables_all[$key])!="") 
     {
      $db_query2=explode("INSERT INTO ", "CREATE ".($tables_all[$key]));
      for ($j=0; $j<sizeof($db_query2); $j++)
       {
        if ($j==0) $tmp_query=$db_query2[$j];else $tmp_query="INSERT INTO ".$db_query2[$j];
        if (!mysql_query($tmp_query)) die ("<br><br>".$tmp_query."<br>".mysql_error());
       }
     } 
   } 
  else 
   { 
    //present tables
    unset($fields_orig);unset($fields_orig_tmp);unset($fields_orig_arr);unset($fields_name_orig);unset($fields_type_orig);unset($fields_null_orig);unset($fields_key_orig);unset($fields_default_orig);unset($fields_extra_orig);
    $fields_orig=explode(",", substr($tables_structure[array_search($val, $tables_names)],0,strrpos($tables_structure[array_search($val, $tables_names)],",")));
    $c=0;
    while (list ($key6, $val6) = each ($fields_orig)) 
     {
      $fields_orig_tmp[$c]=$val6;
      $val6=str_replace("NOT NULL","NOT_NULL",$val6); 
      $val6=str_replace("default ","default_",$val6); 
      $val6=str_replace("0000-00-00 00:00:00","0000-00-00_00:00:00",$val6); 
      $fields_orig_arr=explode(" ",trim($val6));
      $fields_name_orig[$c]=substr($fields_orig_arr[0],1,strlen($fields_orig_arr[0])-2);
      $fields_type_orig[$c]=$fields_orig_arr[1];
      if (strstr($fields_orig_arr[2],"NOT_NULL"))
       {
        $fields_null_orig[$c]=str_replace("NOT_NULL","NOT NULL",$fields_orig_arr[2]); 
        //$fields_key_orig[]="";
        if ($fields_orig_arr[3]!="auto_increment") 
         {
          $tmp_val6=str_replace("default_","",$fields_orig_arr[3]);
          $tmp_val6=str_replace("'","",$tmp_val6);
          $tmp_val6=str_replace("0000-00-00_00:00:00","0000-00-00 00:00:00",$tmp_val6);
          $fields_default_orig[$c]=$tmp_val6;
         } 
        else $fields_extra_orig[$c]=$fields_orig_arr[3];
       }
      else
       {
        $fields_null_orig[$c]=""; 
        //$fields_key_orig[]="";
        if ($fields_orig_arr[2]!="auto_increment") 
         {
          $tmp_val6=str_replace("default_","",$fields_orig_arr[2]);
          $tmp_val6=str_replace("'","",$tmp_val6);
          $tmp_val6=str_replace("0000-00-00_00:00:00","0000-00-00 00:00:00",$tmp_val6);
          $fields_default_orig[$c]=$tmp_val6;
         } 
        else $fields_extra_orig[$c]=$fields_orig_arr[2];
       }  
      $c++;
     } 
    
    
    $query2="Describe ".$val."";
    $rs2 = mysql_query($query2) or die(mysql_error());
    //getting fields data
    while($row2=mysql_fetch_array($rs2)) 
     {
      $fields_name[]=$row2['Field'];
      $fields_type[]=$row2['Type'];
      $fields_null[]=$row2['Null'];
      $fields_key[]=$row2['Key'];
      $fields_default[]=$row2['Default'];
      $fields_extra[]=$row2['Extra'];
      //echo "<br>Name: ".$row2['Field'].", Type: ".$row2['Type'].", Null: ".$row2['Null'].", Key: ".$row2['Key'].", Default: ".$row2['Default'].", Extra: ".$row2['Extra'];  
     }
    
    $cnt2=0;
    while (list ($key2, $val2) = each ($fields_name_orig))
     {
      if (!in_array($val2,$fields_name))
       {
        $result = mysql_query("ALTER TABLE ".$val." ADD ".$fields_orig_tmp[$cnt2],$db_link2);
	if (!$result) echo "<br>".$lng[98][42];
        //echo "Missing column: ".$val2;
       }
      else
       {
        $k=array_search($val2,$fields_name);
        //echo "<br>".$val.":".$val2.":".$fields_null_orig[$cnt2]."-".$fields_null[$k];        
        if ($fields_type_orig[$cnt2]!=$fields_type[$k]) 
         {
          //echo "<br>(".$val.") Changed Type: ".$val2."--->".$fields_type_orig[$cnt2]."-".$fields_type[$k];
          $result = mysql_query("ALTER TABLE ".$val." CHANGE ".$fields_name_orig[$cnt2]." ".$fields_name_orig[$cnt2]." ".$fields_type_orig[$cnt2]." ".$fields_null_orig[$cnt2]." default '".$fields_default_orig[$cnt2]."' ".$fields_extra_orig[$cnt2],$db_link2);
	  if (!$result) echo "<br>".$lng[98][42];
         }
          
        if ( ($fields_null_orig[$cnt2]=="NOT NULL" && $fields_null[$k]=="YES") || ($fields_null_orig[$cnt2]=="" && $fields_null[$k]=="")) 
         {
          //echo "<br>(".$val.") Changed Null: ".$val2."--->".$fields_null_orig[$cnt2]."-".$fields_null[$k];
          $result = mysql_query("ALTER TABLE ".$val." CHANGE ".$fields_name_orig[$cnt2]." ".$fields_name_orig[$cnt2]." ".$fields_type_orig[$cnt2]." ".$fields_null_orig[$cnt2]." default '".$fields_default_orig[$cnt2]."' ".$fields_extra_orig[$cnt2],$db_link2);
	  if (!$result) echo "<br>".$lng[98][42];
         }
          
        if ($fields_default_orig[$cnt2]!=$fields_default[$k]) 
         {
          //echo "<br>(".$val.")Changed Default: ".$val2."--->".$fields_default_orig[$cnt2]."-".$fields_default[$k];
          $result = mysql_query("ALTER TABLE ".$val." CHANGE ".$fields_name_orig[$cnt2]." ".$fields_name_orig[$cnt2]." ".$fields_type_orig[$cnt2]." ".$fields_null_orig[$cnt2]." default '".$fields_default_orig[$cnt2]."' ".$fields_extra_orig[$cnt2],$db_link2);
	  if (!$result) echo "<br>".$lng[98][42];
         }
          
        if ($fields_extra_orig[$cnt2]!=$fields_extra[$k])
         {
          //echo "<br>(".$val.") Changed Extra: ".$val2."--->".$fields_extra_orig[$cnt2]."-".$fields_extra[$k];
          $result = mysql_query("ALTER TABLE ".$val." CHANGE ".$fields_name_orig[$cnt2]." ".$fields_name_orig[$cnt2]." ".$fields_type_orig[$cnt2]." ".$fields_null_orig[$cnt2]." default '".$fields_default_orig[$cnt2]."' ".$fields_extra_orig[$cnt2],$db_link2);
	  if (!$result) echo "<br>".$lng[98][42];
         } 
       } 
      $cnt2++; 
     }   
    unset($fields_name);unset($fields_type);unset($fields_null);unset($fields_key);unset($fields_default);unset($fields_extra);
   }
 }
//---------------  end checking DB tables - if missing attempts to create them  -----------------


 
echo "<br>".$lng[98][41];



function escapechars($str)
 {
  $str=addslashes(stripslashes($str));
  return $str;
 }
?>



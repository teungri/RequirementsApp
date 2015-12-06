<?
include ("admin/inc/conn.php");//include settings file
include ("admin/inc/func.php");//include functions file
include ("ini/params.php");//include configuration file

if ($p_name!="")
 {
  $query="select p_id from projects where p_name='".$p_name."'";
  $rs = mysql_query($query) or die(mysql_error());
  if($row=mysql_fetch_array($rs)) $p_id=$row['p_id'];
  
  if (!($p_id==0 || $p_id==""))
   {
    $query="delete from requirements where r_p_id='".$p_id."'";
    mysql_query($query) or die(mysql_error());
    echo "Records deleted!";
   }
  else echo "Project not found!";  
 }


?>
<form method=post>
Project name: <input type="text" name="p_name">
<input type="submit">
</form>
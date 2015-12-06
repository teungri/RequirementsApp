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
// Page: "admin connection" - page for Admin panel settings
?>
<?
session_start();
if ($_lang!="") $_SESSION['lang']=$_lang;
if (!$_SESSION['lang']) $_SESSION['lang']="en";

//if (!AUTO_TRANSLATIONS) // !oburnato e yes=0, no-1
 {
  //purvo se zarejda en file-a i sled tova ako e drug ezika se overwritevat value-tata, taka 4e ako nqkoe ne e vuvedeno da go ima ot en file-a!
  //na purvona4alnite otpred se dobavq [en]
  include ("../ini/lng/en.php");//include language file
  while (list ($key, $val) = each ($lng)) while (list ($key2, $val2) = each ($lng[$key])) $lng[$key][$key2]="[EN]".$val2;
 }

include ("../ini/lng/".$_SESSION['lang'].".php");//include language file
?>
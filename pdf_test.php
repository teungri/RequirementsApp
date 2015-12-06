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
// Page: "pdf test" - checking if dompdf is installed and running

include ("ini/params.php");//include configuration file
$filename = PROJECT_URL."/print_test.php";
$url = PDF_SCRIPT_URL."dompdf.php?input_file=".$filename."&paper=letter&orientation=portrait&output_file=test.pdf";
//die($url);
?>
<script>document.location.href='<?=$url?>'</script>


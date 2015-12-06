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
// Page: "Left menu" - navigation menu containing submenus
?>
<?include("inc/conn.php");?>
<?include("inc/func.php");?>
<?include("inc/conn_admin.php");?>
<?include("inc/check_login.php");?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<LINK HREF="css/styles_admin.css" REL=stylesheet>
</head>
<body bgcolor=#E6E6E6 topmargin=0 leftmargin=10>
<br>
<center>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td colspan=3 height=15 class="td_no_border"><img src="img/b.gif" width=1 height=1></td>
  </tr>
  <tr>
    <td colspan=3 height=50 align=center valign=top class=title><b>::&nbsp;<?=$lng[99][6]?>&nbsp;::</b></td>
  </tr>
  <tr>
    <td colspan=3 height=20 class="td_no_border"><img src="img/arrow.gif">&nbsp;&nbsp;<a href="#" onclick="parent.frames.main.location='langs.php'" class="tables_title" onmouseover="this.className='tables_title2'"  onmouseout="this.className='tables_title'"><b><?=$lng[99][7]?></b></a></td>
  </tr>
  <!--tr>
    <td colspan=3 height=20 class="td_no_border"><img src="img/arrow.gif">&nbsp;&nbsp;<a href="#" onclick="parent.frames.main.location='txts.php'" class="tables_title" onmouseover="this.className='tables_title2'"  onmouseout="this.className='tables_title'"><b><?=$lng[99][8]?></b></a></td>
  </t>
  <tr>
    <td colspan=3 height=20 class="td_no_border"><img src="img/arrow.gif">&nbsp;&nbsp;<a href="#" onclick="parent.frames.main.location='countries.php'" class="tables_title" onmouseover="this.className='tables_title2'"  onmouseout="this.className='tables_title'"><b><?=$lng[99][20]?></b></a></td>
  </tr>
  <tr>
    <td colspan=3 height=20 class="td_no_border"><img src="img/arrow.gif">&nbsp;&nbsp;<a href="#" onclick="parent.frames.main.location='xmls.php'" class="tables_title" onmouseover="this.className='tables_title2'"  onmouseout="this.className='tables_title'"><b><?=$lng[99][33]?></b></a></td>
  </trr-->
  <tr>
    <td colspan=3 height=20 class="td_no_border"><img src="img/arrow.gif">&nbsp;&nbsp;<a href="#" onclick="parent.frames.main.location='param.php'" class="tables_title" onmouseover="this.className='tables_title2'"  onmouseout="this.className='tables_title'"><b><?=$lng[99][34]?></b></a></td>
  </tr>
  <tr>
    <td colspan=3 height=20 class="td_no_border"><img src="img/arrow.gif">&nbsp;&nbsp;<a href="#" onclick="parent.frames.main.location='txts.php'" class="tables_title" onmouseover="this.className='tables_title2'"  onmouseout="this.className='tables_title'"><b><?=$lng[99][8]?></b></a></td>
  </tr>
  <tr>
    <td colspan=3 height=20 class="td_no_border"><img src="img/arrow.gif">&nbsp;&nbsp;<a href="#" onclick="parent.frames.main.location='templates.php'" class="tables_title" onmouseover="this.className='tables_title2'"  onmouseout="this.className='tables_title'"><b><?=$lng[99][27]?></b></a></td>
  </tr>
  <tr>
    <td colspan=3 height=20 class="td_no_border"><img src="img/arrow.gif">&nbsp;&nbsp;<a href="#" onclick="parent.frames.main.location='fields.php'" class="tables_title" onmouseover="this.className='tables_title2'"  onmouseout="this.className='tables_title'"><b><?=$lng[99][37]?></b></a></td>
  </tr>
  <tr>
    <td colspan=3 height=20 class="td_no_border"><img src="img/b.gif" width=1 height=1></td>
  </tr>
  
  <tr>
    <td colspan=3 height=20 class="tables_title" style="color:#5B858D;"><b>::&nbsp;</b><a href="left.php" onclick="parent.frames.main.location='admin_access_select.php'" class="tables_title" style="color:#5B858D;"><b><?=$lng[99][9]?></b></a></td>
  </tr>
  <tr>
    <td colspan=3 height=20 class="tables_title" style="color:#5B858D;"><b>::&nbsp;</b><a href="left.php" onclick="parent.frames.main.location='logout.php'" class="tables_title" style="color:#5B858D;"><b><?=$lng[99][10]?></b></a></td>
  </tr>
</table>
</center>
</body>
</html>

<?php

$insideitem = false;
$tag = "";
$title = "";
$description = "";
$link = "";

function startElement($parser, $name, $attrs) {
global $insideitem, $tag, $title, $description, $link;
if ($insideitem) {
$tag = $name;
} elseif ($name == "ITEM") {
$insideitem = true;
}
}

function endElement($parser, $name) {
global $insideitem, $tag, $title, $description, $link;
if ($name == "ITEM") {
printf("<dt><b><a href='%s'>%s</a></b></dt>",
trim($link),htmlspecialchars(trim($title)));
printf("<dd>%s</dd>",htmlspecialchars(trim($description)));
$title = "";
$description = "";
$link = "";
$insideitem = false;
}
}

function characterdata($parser, $data) {
global $insideitem, $tag, $title, $description, $link;
if ($insideitem) {
switch ($tag) {
case "TITLE":
$title .= $data;
break;
case "DESCRIPTION":
$description .= $data;

break;
case "LINK":
$link .= $data;
break;
}
}
}

$xml_parser = xml_parser_create();
xml_set_element_handler($xml_parser, "startElement", "endElement");
xml_set_character_data_handler($xml_parser, "characterdata");
$fp = fopen("RSS/1.xml","r")
or die("Error reading RSS data.");
while ($data = fread($fp, 4096))
xml_parse($xml_parser, $data, feof($fp))
or die(sprintf("xml error: %s at line %d",
xml_error_string(xml_get_error_code($xml_parser)),
xml_get_current_line_number($xml_parser)));
fclose($fp);
xml_parser_free($xml_parser);

?>
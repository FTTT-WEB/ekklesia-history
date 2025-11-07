<?php
### IMAGE FORMAT
$format = ".gif";

#######################################
## (C) 2000 Total Eclipse Scripts
#
### This script is free for personal
### or commercial use.
#
#   problems? scripts@tescripts.net
#######################################

if(file_exists("count.txt")) {
	$content = file("count.txt");
}
else {
	$content[0] = 0 ;
}

#written by Hang
if ($counter=="no"){
	$num = $content[0] ;
}
else {
	$num = intval($content[0]) + 1 ;
	$fp = fopen("count.txt", "w") ;
	fwrite($fp, $num) ;
	fclose($fp) ;
}
#above Hang

switch($type) {
 case "text":
  echo $num;
  break;
 case "gfx":
  $i = 0;
  $cntn = strlen($num);
  while($i < $cntn) {
   $tmpnum = substr($num, $i, 1);
   echo("<img width=20 height=20 src=\"$dir/$tmpnum$format\">");
   $i++;
  }
  break;
 case "q":
 break;
 default:
 echo("count.php <b>error</b>: type not specified.");
 break;
}
?>
<?php
#$new = htmlspecialchars("<a href='test'>Test</a>", ENT_QUOTES, "ISO-8859-1");
#echo $new ;


$a = get_html_translation_table(HTML_ENTITIES) ;
reset ($a);
while (list ($key, $val) = each ($a)) {
    echo "$key =>$val<br>\n";
}
?>
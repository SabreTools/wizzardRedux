<?php

// Original code: The Wizard of DATz

print "<pre>";

$page = "http://www.c64heaven.com/";

print "load ".$page."\n";

$content = get_data($page);

preg_match_all("/<a href=\"(.*)\">/", $content, $links);
$links = $links[1];
var_dump($links);

print str_replace('style', null, $content);

?>
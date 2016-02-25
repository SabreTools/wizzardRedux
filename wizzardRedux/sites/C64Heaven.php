<?php

// Original code: The Wizard of DATz

print "<pre>";

$page="http://www.c64heaven.com/";

print "load ".$page."\n";

$content = implode('', file($page));

print str_replace(array('style'), null, $content);

?>
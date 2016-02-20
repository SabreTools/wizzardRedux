<?php
print "<pre>";


	$page="http://www.c64heaven.com/";

	print "load ".$page."\n";

	$content=implode ('', file ($page));

	print	str_replace(Array('style'),null,$content);

?>
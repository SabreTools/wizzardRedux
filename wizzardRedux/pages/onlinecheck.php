<?php

/*
Check for new downloadable ROMs from all available sites

Note: This is the most tedious one of all. All of the checks should be named as "sites/<sitename>.php".

If the page is sent with no param, generate a list of all possible checks. Each check has a
<sitename>-id.txt file attached to it that designates what files have already been found. Use
the existing <sitename>/onlinecheck.php files for either reference or wholesale repurposing.

TODO: Figure out where the id files should be stored (temp/ids/?, sites/ids/?)
TODO: Retool existing onlinecheck.php files to follow the new format
TODO: Add a way to figure out if a site is dead based on the original list that WoD created
TODO: Figure out more todos
*/

?>
<?php

/*
Any specialized inline styles or code that isn't really a helper goes here.

Note: The original programmer (mnadareski/darksabre76) speaking: I don't have an artistic
bone in my body, so if anyone knows their way around making a site pretty, please contact
me. That is unless vibrant primary colors and bad <div> usage is your groove.
*/

$tab = "&nbsp;&nbsp;&nbsp;&nbsp;";

// Original code: The Wizard of DATz

// Replace accented characters
$normalize_chars = array(
		'�'=>'A',	'�'=>'a',
		'�'=>'A',	'�'=>'a',
		'�'=>'A',	'�'=>'a',
		'�'=>'Ae',	'�'=>'ae',
		'�'=>'A',	'�'=>'a',
		'�'=>'A',	'�'=>'a',
		'�'=>'Ae',	'�'=>'ae',
		'�'=>'C',	'�'=>'c',
		'�'=>'D',	'�'=>'d',
		'�'=>'E',	'�'=>'e',
		'�'=>'E',	'�'=>'e',
		'�'=>'E',	'�'=>'e',
		'�'=>'E',	'�'=>'e',
		'�'=>'f',
		'�'=>'I',	'�'=>'i',
		'�'=>'I',	'�'=>'i',
		'�'=>'I',	'�'=>'i',
		'�'=>'I',	'�'=>'i',
		'�'=>'N',	'�'=>'n',
		'�'=>'O',	'�'=>'o',
		'�'=>'O',	'�'=>'o',
		'�'=>'O',	'�'=>'o',
		'�'=>'Oe',	'�'=>'oe',
		'�'=>'O',	'�'=>'o',
		'�'=>'O',	'�'=>'o',
		'�'=>'S',	'�'=>'s',
		'�'=>'ss',
		'�'=>'B',	'�'=>'b',
		'�'=>'U',	'�'=>'u',
		'�'=>'U',	'�'=>'u',
		'�'=>'U',	'�'=>'u',
		'�'=>'Ue',	'�'=>'ue',
		'�'=>'y',
		'�'=>'Y',	'�'=>'y',
		'�'=>'Z',	'�'=>'z',
);

// Replace special characters and patterns
$search_pattern = array(
		'EXT' => array (
				1	=>	'/~/',
				2	=>	'/_/',
				3	=>	'/:/',
				4	=>	'/>/',
				5	=>	'/</',
				6	=>	'/\|/',
				7	=>	'/"/',
				8	=>	'/\*/',
				9	=>	'/\\\/',
				10	=>	'/\//',
				11	=>	'/\?/',
				13	=>	'/\(([^)(]*)\(([^)]*)\)([^)(]*)\)/',
				14	=>	'/\(([^)]+)\)/',
				15	=>	'/\[([^]]+)\]/',
				16	=>	'/\{([^}]+)\}/',
				17	=>	'/(ZZZJUNK|ZZZ-UNK-|ZZZ-UNK |zzz unknow |zzz unk |Copy of |[.][a-z]{3}[.][a-z]{3}[.]|[.][a-z]{3}[.])/i',
				18	=>	'/ (r|rev|v|ver)\s*[\d\.]+[^\s]*/i',
				19	=>	'/(( )|(\A))(\d{6}|\d{8})(( )|(\Z))/',
				20	=>	'/(( )|(\A))(\d{1,2})-(\d{1,2})-(\d{4}|\d{2})/',
				21	=>	'/(( )|(\A))(\d{4}|\d{2})-(\d{1,2})-(\d{1,2})/',
				23	=>	'/[-]+/',
				24	=>	'/\A\s*\)/',
				25	=>	'/\A\s*(,|-)/',
				26	=>	'/\s+/',
				27	=>	'/\s+,/',
				28	=>	'/\s*(,|-)\s*\Z/',
		),
		'REP' => array (
				1	=>	' - ',
				2	=>	' ',
				3	=>	' ',
				4	=>	')',
				5	=>	'(',
				6	=>	'-',
				7	=>	"'",
				8	=>	'.',
				9	=>	'-',
				10	=>	'-',
				11	=>	' ',
				13	=>	' ',
				14	=>	' ',
				15	=>	' ',
				16	=>	' ',
				17	=>	' ',
				18	=>	' ',
				19	=>	' ',
				20	=>	' ',
				21	=>	' ',
				23	=>	'-',
				24	=>	' ',
				25	=>	' ',
				26	=>	' ',
				27	=>	',',
				28	=>	' ',
		),
);

?>

?>
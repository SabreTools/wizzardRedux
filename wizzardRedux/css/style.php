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
		'Á'=>'A',	'á'=>'a',
		'À'=>'A',	'à'=>'a',
		'Â'=>'A',	'â'=>'a',
		'Ä'=>'Ae',	'ä'=>'ae',
		'Ã'=>'A',	'ã'=>'a',
		'Å'=>'A',	'å'=>'a',
		'Æ'=>'Ae',	'æ'=>'ae',
		'Ç'=>'C',	'ç'=>'c',
		'Ð'=>'D',	'ð'=>'d',
		'É'=>'E',	'é'=>'e',
		'È'=>'E',	'è'=>'e',
		'Ê'=>'E',	'ê'=>'e',
		'Ë'=>'E',	'ë'=>'e',
		'ƒ'=>'f',
		'Í'=>'I',	'í'=>'i',
		'Ì'=>'I',	'ì'=>'i',
		'Î'=>'I',	'î'=>'i',
		'Ï'=>'I',	'ï'=>'i',
		'Ñ'=>'N',	'ñ'=>'n',
		'Ó'=>'O',	'ó'=>'o',
		'Ò'=>'O',	'ò'=>'o',
		'Ô'=>'O',	'ô'=>'o',
		'Ö'=>'Oe',	'ö'=>'oe',
		'Õ'=>'O',	'õ'=>'o',
		'Ø'=>'O',	'ø'=>'o',
		'Š'=>'S',	'š'=>'s',
		'ß'=>'ss',
		'Þ'=>'B',	'þ'=>'b',
		'Ú'=>'U',	'ú'=>'u',
		'Ù'=>'U',	'ù'=>'u',
		'Û'=>'U',	'û'=>'u',
		'Ü'=>'Ue',	'ü'=>'ue',
		'ÿ'=>'y',
		'Ý'=>'Y',	'ý'=>'y',
		'Ž'=>'Z',	'ž'=>'z',
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
<?php

/* ------------------------------------------------------------------------------------
 Check VideopacNL for new items
 Original code: The Wizard of DATz

 Requires:
 cookie		Cookie called SMFCookie11 from VideopacNL; only available to logged in users
 type		Determine which part of the site must be parsed
 ------------------------------------------------------------------------------------ */

// All possible $_GET variables that we can use
$getvars = array(
		"cookie",			// Cookie named SMFCookie11; available only to logged in users
		"type",				// Determine which part of the site must be parsed
);

// All possible values for $type
$typeopts = array(
		"main",				// Main site
		"forum",			// User forums
		"main2",			// Main site (via proxy)
);

//Get the values for all parameters
foreach ($getvars as $var)
{
	$$var = (isset($_GET[$var]) ? trim($_GET[$var]) : "");
}

if ($type == 'forum')
{
	if ($cookie)
	{
		print "<pre>";
		
		$queryb = get_html("http://videopac.nl/forum/index.php", $cookie);
		preg_match("/<span style=\"font-size: 130%;\"> Hello <b>.*<\/b><\/span>/", $queryb, $queryb);

		if ($queryb[0] !== "")
		{
			print "cookie is OK\n";
		}
		else
		{
			die('cookie is wrong');
        }

		$new_IDs = array();
		$new_DLs = array();

		$boards = array(
				//	Videopac / Odyssey2
			2,	//		Anything Videopac / Odyssey2
			28,	//			VP/O2 Video Gameography
			31,	//			VP/O2 programmers list
			6,	//		Games
			25,	//			Game of the Month
			38,	//		New releases
			26,	//			Released Homebrews and Prototypes list
			43,	//			Homebrew and prototype release schedule
			7,	//		Hardware
			12,	//		Stories...
			21,	//		Other Videopac / Odyssey2 sites
				//	Collecting
			34,	//		Rarity Lists
			36,	//			Console Rarity List
			33,	//			Jopac Rarity List
			23,	//			Radiola Rarity List
			29,	//			Siera Rarity List
			35,	//			Japanes Rarity list
			41,	//		Wanted lists
			5,	//		Swap & Sell
			4,	//		My Collection
				//	Programming the Videopac / Odyssey2
			3,	//		Programming
			22,	//		Homebrews
			32,	//		Unreleased HomeBrew and test code
				//	Emulation
			8,	//		Emulating the Videopac / Odyssey2
			9,	//		The Videopac Base
			27,	//		Forum Arcade
				//	Anything not specifically Videopac / Odyssey2
			39,	//		Events
			17,	//		Other Systems
			18,	//		Other games
			42,	//		eBay Oddballz
			19,	//		Humor
			20,	//		Anything else...
				//	About this forum.
			40,	//		How to register...
			11,	//		Read this first!
			13,	//		Videopac / Odyssey2 forum
		);

		foreach ($boards as $board)
		{
			$maxboardpage = 1000;
			$firstDate2 = '';
	
			for ($boardpage = 0; $boardpage < $maxboardpage; $boardpage++)
			{
				print "load board: ".$board.".".($boardpage * 20)."\n";
				$thisboardpage = $boardpage * 20;
	
				$new_top = 0;
				$old_top = 0;
	
				$firstDateSet2 = true;
	
				$queryb = get_html("/forum/index.php?board=".$board.".".($boardpage * 20), $cookie);
				$queryb = explode('<td class="windowbg2" valign="middle" align="center" width="5%">', $queryb);
				array_splice($queryb, 0, 1);
				if (!$queryb)
				{
					print "empty!\n";
					break;
				}
				
				foreach ($queryb as $rowb)
				{	
					$stickyicon = explode('stickyicon', $rowb);
					$stickyicon = $stickyicon[1];
	
					$topic = explode('<a href="http://videopac.nl/forum/index.php?topic=', $rowb);
					$topic = explode('.', $topic[1]);
					$topic = $topic[0];
					
					$count = explode('" valign="middle" width="4%" align="center">', $rowb);
					$count = explode('<', $count[1]);
					$count = trim($count[0]);
		
					$titel = explode('.0">', $rowb);
					$titel = explode('<', $titel[1]);
					$titel = trim($titel[0]);
					
					if ($firstDate2 == $topic."#".$count)
					{
						$boardpage = $maxboardpage;
						break;
					}
	
					if ($firstDateSet2)
					{
						$firstDate2 = $topic."#".$count;
						$firstDateSet2 = false;
					}
					
					if (!$r_query[$topic."#".$count] || $stickyicon)
					{
						$maxpages = 1000;
						$firstDate = '';
				
						for ($page = 0; $page < $maxpages; $page++)
						{
							$firstDateSet = true;
							$new = 0;
							$old = 0;
				
							print "load page: ".$topic.".".($page*15);
				
							$query = get_html("/forum/index.php?topic=".$topic.".".($page * 15), $cookie);
							$query = explode('<tr><td style="padding: 1px 1px 0 1px;">', $query);
							array_splice($query, 0, 1);
					
							foreach ($query as $row)
							{
					        	$time = explode(" on:</b> ", $row);
					        	$time = explode(" &#187;</div>", $time[1]);
								$time = $time[0];
								$time = strtotime($time);
								$time = date('Y.m.d H.i', $time);
				
								if ($firstDate == $time)
								{
									$page = $maxpages;
									break;
								}
				
								if ($firstDateSet)
								{
									$firstDate = $time;
									$firstDateSet = false;
								}
					
								$dls = explode('<a href="http://videopac.nl/forum/index.php?page=dlattach;topic=', $row);
								array_splice($dls, 0, 1);
								foreach ($dls as $dl)
								{
									$dl = explode("</a>", $dl);
									$dl = explode('"><img src="http://videopac.nl/forum/Themes/default/images/icons/clip.gif" align="middle" alt="*" border="0" />&nbsp;', $dl[0]);
					
									$dl_id = $dl[0];
				
									if ($dl[1])
									{
										$dl_title = $dl[1];
						
										$dl_ext = explode('.', $dl_title);
										$dl_ext = strtolower($dl_ext[count($dl_ext) - 1]);
										$dl_title = $titel.' ('.substr($dl_title, 0, -(strlen($dl_ext) + 1)).') ('.$time.').'.$dl_ext;
				
										if (!$r_query[$dl_id])
										{
											$new_IDs[] = $dl_id;
											$new_DLs[] = array($dl_id, $dl_title);
											$new++;
										}
										else
										{
											$old++;
											$page = $maxpages;
											break;
										}
									}
								}
							}
				
							print ", new: ".$new.", old: ".$old."\n";
						}

						$new_top++;
						if (!$stickyicon)
						{
							$new_IDs[] = $topic."#".$count;
						}
					}
					else
					{
						$boardpage = $maxboardpage;
						$old_top++;
						break;
					}
				}
	
				print "close board: ".$board.".".$thisboardpage.", new: ".$new_top.", old: ".$old_top."\n";
			}
		}

		print "<table><tr><td><pre>";
		
		foreach ($new_IDs as $url)
		{
			print $url."\n";
		}
		
		print "</td></td></tr></table>
		<table><tr><td><pre>";
		
		foreach ($new_DLs as $url)
		{
			print "<a href=\"http://videopac.nl/forum/index.php?page=dlattach;topic=".$url[0]."\">".$url[1]."</a>\n";
		}
		
		print "</td></tr></table>";
	
	}
	else
	{
		print"<form method=\"get\" action=\"?\">
		<input name='page' value='onlinecheck' type=hidden />
		<input name='source' value='VideopacNL' type=hidden />
		<input name='type' value='forum' type=hidden />
		<input name='cookie' type='text' value='SMFCookie11' />
		<input type='submit' /></form>";
    }
}
elseif ($type == 'main')
{
	$dirs = array(
		'http://videopac.nl/games/games_videopac.php',
		'http://videopac.nl/games/games_imagic.php',
		'http://videopac.nl/games/games_jopac.php',
		'http://videopac.nl/games/games_parker.php',
		'http://videopac.nl/games/games_new.php',
	);
	
	print "<pre>check folders:\n\n";
	
	foreach($dirs as $dir)
	{
		list_dir($dir);
	}
	
	print "\nnew urls:\n\n";

	print "<table><tr><td><pre>";
	
	foreach ($found as $url)
	{
		print "<a href=\"http://videopac.nl/games/".$url[1]."\">".$url[0]."</a>\n";
	}
	
	print "</td><td><pre>";
	
	foreach ($found as $url)
	{
		print $url[1]."\n";
	}

	print "</td></tr></table>";
}
else
{
	print "<pre>";
	print "load <a href=?page=onlinecheck&source=".$_GET["source"]."&type=main>main</a>\n";
	print "load <a href=?page=onlinecheck&source=".$_GET["source"]."&type=forum>forum</a>\n";
}

function get_html ($target, $cookie)
{
	$timeout = 100;  // Max time for stablish the conection

	$server  = 'videopac.nl';           			 // Ziel domain
	$host    = 'videopac.nl';           			 // Domain name
	//$target  = '/new.html';        		 // Ziel document
	$referer = 'http://videopac.nl';   // ausgangs document
	$port    = 80;

	$method = "GET";
	if (is_array($gets))
	{
		$getValues = '?';
		foreach ($gets AS $name => $value)
		{
			$getValues .= urlencode($name)."=".urlencode($value).'&';
		}
		$getValues = substr($getValues, 0, -1);
	}
	else
	{
		$getValues = '';
	}

	if (is_array($posts))
	{
		foreach ($posts AS $name => $value)
		{
			$postValues .= urlencode($name)."=".urlencode($value).'&';
		}
		$postValues = substr($postValues, 0, -1);
		$method = "POST";
	}
	else
	{
		$postValues = '';
	}

	$request = "$method $target$getValues HTTP/1.1\r\n";
	$request .= "Accept: ".$_SERVER["HTTP_ACCEPT"]."\r\n";
	$request .= "Referer: ".$referer."\r\n";
	$request .= "Cookie: SMFCookie11=$cookie \r\n";
	$request .= "Accept-Language: ".$_SERVER["HTTP_ACCEPT_LANGUAGE"]."\r\n";
	if ($method == "POST")
	{
		$request .= "Content-Type: application/x-www-form-urlencoded\r\n";
	}
	$request .= "UA-CPU: ".$_SERVER["HTTP_UA_CPU"]."\r\n";
	$request .= "Accept-Encoding: none\r\n";
	$request .= "User-Agent:".$_SERVER["HTTP_USER_AGENT"]."\r\n";
	$request .= "Host: $host\r\n";
	if ($method == "POST")
	{
		$request .= "Content-Length: ".strlen($postValues)."\r\n";
	}
	$request .= "Connection: ".$_SERVER["HTTP_CONNECTION"]."\r\n";
	if ($method == "POST")
	{
		$request .= "Cache-Control: no-cache\r\n\r\n".$postValues;
	}

	$socket  = fsockopen($server, $port, $errno, $errstr, $timeout);
	fputs( $socket, $request."\r\n" );

	$ret = '';
	$lastlen = 1;
	socket_set_timeout($socket, 2);
	while ($lastlen > 0)
	{
		$temp = fread($socket, 1024 * 4);
		$lastlen = strlen($temp);
		$ret .= $temp;
	}
	fclose($socket);

	return $ret;
}

function list_dir ($dir)
{
	GLOBAL $found, $r_query;

	print "load: ".$dir."\n";

	$query = implode('', file ($dir));
	$query = explode('<table width="100%" border="0" cellspacing="0" cellpadding="0" height="100%">',$query);

	$new = 0;
	$old = 0;

	foreach($query as $row){
		$title = explode('<td class="gamestext" >', $row);
		$title = explode('</td>', $title[1]);
		$url = $title[0];
		$url = explode('<a href="', $url);
		$url = explode('"', $url[1]);
		$url = $url[0];
		$title = trim(strip_tags($title[0]));

		if ($url)
		{
			$title2 = explode('<td class="gamestext">', $row);
			$title2 = explode('</td>', $title2[1]);
			$title2 = trim(strip_tags($title2[0]));

			$dl = get_data("http://videopac.nl/games/".$url);
			$dl = explode('"><img src="../../../images/games/rom.jpg"', $dl);
			if ($dl[1])
			{
				$dl = explode('"', $dl[0]);
				$dl = $dl[count($dl) - 1];
				$url = explode('/', $url);
				$url[count($url) - 1] = null;
				$url = implode('/', $url).$dl;
				$ext = explode('.', $dl);
				$ext = $ext[count($ext) - 1];

				$title = $title." (".$title2.").".$ext;

				if (!$r_query[$url])
				{
					$found[] = array($title, $url);
					$new++;
				}
				else
				{
					$old++;
				}
			}
		}
	}

	print "close: ".$dir."\n";
	print "new: ".$new.", old: ".$old."\n";
}

?>
<?php
		$systems=Array(
			Array('all',	'ALL'),

			Array('appleii',	'Apple - II'),
			Array('a2600',		'Atari - 2600'),
			Array('a5200',		'Atari - 5200'),
			Array('a7800',		'Atari - 7800'),
			Array('aj',			'Atari - Jaguar'),
			Array('al',			'Atari - Lynx'),
			Array('WS',			'Bandai - Wonderswan'),
			Array('wsc',		'Bandai - Wonderswan Color'),
			Array('cv',			'Coleco - ColecoVision'),
			Array('c64',		'Commodore - 64'),
			Array('intv',		'Matel - Intellivision'),
			Array('msx',		'Microsoft - MSX'),
			Array('msx2',		'Microsoft - MSX 2'),
			Array('pcfx',		'NEC - PC-FX'),
			Array('sgfx',		'NEC - SuperGrafx'),
			Array('tg',			'NEC - Turbo Grafx'),
			Array('tgcd',		'NEC - Turbo Grafx CD'),
			Array('fds',		'Nintendo - Famicom Disk System'),
			Array('gb',			'Nintendo - Game Boy'),
			Array('gba',		'Nintendo - Game Boy Advance'),
			Array('gbc',		'Nintendo - Game Boy Color'),
			Array('nes',		'Nintendo - NES'),
			Array('n64',		'Nintendo - Nintendo 64'),
			Array('pm',			'Nintendo - Pokemon Mini'),
			Array('snes',		'Nintendo - Super Nintendo'),
			Array('vb',			'Nintendo - Virtual Boy'),
			Array('cdi',		'Philips - CD-i'),
			Array('mo2',		'Philips - Odyssey 2'),
			Array('ngcd',		'SNK - Neo Geo CD'),
			Array('ngp',		'SNK - Neo Geo Pocket'),
			Array('ngpc',		'SNK - Neo Geo Pocket Color'),
			Array('32x',		'Sega - 32X'),
			Array('scd32x',		'Sega - 32X CD'),
			Array('dc',			'Sega - DreamCast'),
			Array('gg',			'Sega - Game Gear'),
			Array('gen',		'Sega - Genesis'),
			Array('sms',		'Sega - Master System'),
			Array('scd',		'Sega - Mega CD'),
			Array('sp',			'Sega - Pico'),
			Array('ss',			'Sega - Saturn'),
			Array('sc3',		'Sega - SC-3000'),
			Array('sg1',		'Sega - SG-1000'),
			Array('psx',		'Sony - Playstation'),
			Array('sps',		'Sony - PocketStation'),
		);



$r_query=implode ('', file ($_GET["source"]."/ids2.txt"));
$r_query=explode ("\r\n","\r\n".$r_query);
$r_query=array_flip($r_query);
	print "<pre>";

if(!$_GET["type"]){
	print "load <a href=?action=onlinecheck&source=".$_GET["source"]."&type=full>full</a>\n";
	print "load <a href=?action=onlinecheck&source=".$_GET["source"]."&type=system>system</a>\n";
	print "load <a href=?action=onlinecheck&source=".$_GET["source"]."&type=full2>full proxy</a>\n";
	print "load <a href=?action=onlinecheck&source=".$_GET["source"]."&type=system2>system proxy</a>\n";
}elseif($_GET["type"]=="full2"){
	if($_GET["start"])
	{
		$start=$_GET["start"];
		$fp = fopen($_GET["source"]."/ids.txt", "w");
		fwrite($fp,	$start);
		fclose($fp);
	}
	else
	{
		$start=implode ('', file ($_GET["source"]."/ids.txt"));
	}

	print "Search for new uploads\n\n<table>";

	for ($x=$start;$x<$start+25;$x++)
	{
		if($r_query[$x.""]){
			print "<tr><td></td><td>reject ".$x.": allready loaded</td></tr>\n";
			$last=$x;
        }else{


$query=file_get_contents("http://two.webproxy.at/surf/browse.php?u=".urlencode("http://www.vizzed.com/playonlinegames/play.php?id=".$x)."&b=4", false,stream_context_create(array(
	'http'=>array(
		'method'	=>	"GET",
		'header'	=>	"Referer: http://two.webproxy.at/surf/browse.php?u=http%3A%2F%2Fwww.vizzed.com\r\n"
	))));



			if(strrpos($query,"Game doesn't exist.")){
				print "<tr><td></td><td>reject ".$x.": Game doesn't exist</td></tr>\n";
			}elseif(strrpos($query,"This game is not playable")){
				print "<tr><td></td><td>reject ".$x.": This game is not playable</td></tr>\n";
			}elseif(strrpos($query,"You cannot play Playstation games")){
				print "<tr><td>".$x."</td><td><a href=http://two.webproxy.at/surf/browse.php?u=".urlencode("http://www.vizzed.com/playonlinegames/play.php?id=".$x)."&b=4>Playstation game ".$x."</a></td></tr>\n";
				$last=$x;
			}else{
				$gametitle=explode ('<title>', $query);
				$gametitle=explode ('</title>', $gametitle[1]);
				$gametitle=trim($gametitle[0]);
			
				$dom="http://www.vizzed.net/";

				$url=explode ('"http://www.vizzed.net/', $query);
				$url=explode ('"', $url[1]);

				if(!$url[0]){
					$dom="http://www.vizzed.co/";
					$url=explode ('"http://www.vizzed.co/', $query);
					$url=explode ('"', $url[1]);
				}

				if(!$url[0]){
					$dom="http://www.get-your-rom.com/";
					$url=explode ('"http://www.get-your-rom.com/', $query);
					$url=explode ('"', $url[1]);
				}

				$url=str_replace('#','%23',$url[0]);

				$ext=explode ('.',$url);
				$ext=$ext[count($ext)-1];

				print "<tr><td>".$x."</td><td><a href=\"".$dom.$url."\">".$gametitle.".".$ext."</a></td></tr>\n";
				if(!$url){
					print "error".$x ;
					break;
				}


				$last=$x;
			}
		}
	}

	if($last) $start=$last+1;

	print "</table>\nnext startnr\t<a href=?action=onlinecheck&source=".$_GET["source"]."&type=full2&start=".($start).">".$start."</a>";
	if($last!=$x){
		print "\nignore last startnr\t<a href=?action=onlinecheck&source=".$_GET["source"]."&type=full2&start=".($x).">".($x)."</a>";
	}
}elseif($_GET["type"]=="full"){
	if($_GET["start"])
	{
		$start=$_GET["start"];
		$fp = fopen($_GET["source"]."/ids.txt", "w");
		fwrite($fp,	$start);
		fclose($fp);
	}
	else
	{
		$start=implode ('', file ($_GET["source"]."/ids.txt"));
	}

	print "Search for new uploads\n\n<table>";

	for ($x=$start;$x<$start+25;$x++)
	{
		if($r_query[$x.""]){
			print "<tr><td></td><td>reject ".$x.": allready loaded</td></tr>\n";
			$last=$x;
        }else{
			$query=trim(implode ('', file ("http://www.vizzed.com/playonlinegames/play.php?id=".$x)));

			if($query=="Game doesn't exist."){
				print "<tr><td></td><td>reject ".$x.": Game doesn't exist</td></tr>\n";
			}elseif($query=="This game is not playable"){
				print "<tr><td></td><td>reject ".$x.": This game is not playable</td></tr>\n";
			}elseif($query=="You cannot play Playstation games.  Either become a staff member or buy the Playstation item from the Item Shop."){
				print "<tr><td>".$x."</td><td><a href=http://www.vizzed.com/playonlinegames/play.php?id=".$x.">Playstation game ".$x."</a></td></tr>\n";
				$last=$x;
			}else{
				$gametitle=explode ('<title>', $query);
				$gametitle=explode ('</title>', $gametitle[1]);
				$gametitle=trim($gametitle[0]);

			
				$dom="http://www.vizzed.net/";

				$url=explode ('"http://www.vizzed.net/', $query);
				$url=explode ('"', $url[1]);

				if(!$url[0]){
					$dom="http://www.vizzed.co/";
					$url=explode ('"http://www.vizzed.co/', $query);
					$url=explode ('"', $url[1]);
				}

				if(!$url[0]){
					$dom="http://www.get-your-rom.com/";
					$url=explode ('"http://www.get-your-rom.com/', $query);
					$url=explode ('"', $url[1]);
				}

				$url=str_replace('#','%23',$url[0]);

				$ext=explode ('.',$url);
				$ext=$ext[count($ext)-1];
				
				print "<tr><td>".$x."</td><td><a href=\"".$dom.$url."\">".$gametitle.".".$ext."</a></td></tr>\n";
				if(!$url){
					print "error".$x ;
					break;
				}

				$last=$x;
			}
		}
	}

	if($last) $start=$last+1;

	print "</table>\nnext startnr\t<a href=?action=onlinecheck&source=".$_GET["source"]."&type=full&start=".($start).">".$start."</a>";
	if($last!=$x){
		print "\nignore last startnr\t<a href=?action=onlinecheck&source=".$_GET["source"]."&type=full&start=".($x).">".($x)."</a>";
	}
}elseif($_GET["type"]=="system"){

	if($_GET["system"]){
		print "load: ".$_GET["system"]."\n\n<table>";
		
		if($_GET["page"]){
			$page=$_GET["page"];
		}else{
			$page=1;
		}
		
		$counterArray=Array();

			// http://www.vizzed.com/p/?page=1&sort=1&system=dc
			$query=implode ('', file ("http://www.vizzed.com/p/?page=".$page."&sort=id&system=".$_GET["system"]."&search=".$_GET["search"]));

			$counter=explode ('<b>Page ', $query);
			$counter=explode ('</b>', $counter[1]);
			$counterArray[]=$counter[0];

			$query=explode ('</form>', $query);
			$query=explode ('<script', $query[3]);

			$query=explode ("href='../play/", $query[0]);
			array_splice ($query,0,1);

			if(!$query) break;

			$found=false;
			$foundArray=Array();

			foreach($query as $row){
				$row=explode ("'", $row);
				$row=explode ("-", $row[0]);
				$row=$row[count($row)-2];

				if($foundArray[$row]!=1)
				{
					$foundArray[$row]=1;
	
					if(($r_query[$row])&&($_GET["ig"]=="")){
						print "<tr><td></td><td>reject ".$row.": allready loaded</td></tr>\n";
			        }else{
						$query_s=trim(implode ('', file ("http://www.vizzed.com/playonlinegames/play.php?id=".$row)));

						if($query_s=="Game doesn't exist."){
							print "<tr><td></td><td>reject ".$row.": Game doesn't exist</td></tr>\n";
						}elseif($query_s=="This game is not playable"){
							print "<tr><td></td><td>reject ".$row.": This game is not playable</td></tr>\n";
						}elseif($query_s=="You cannot play Playstation games.  Either become a staff member or buy the Playstation item from the Item Shop."){
							print "<tr><td>".$x."</td><td><a href=http://www.vizzed.com/playonlinegames/play.php?id=".$x.">Playstation game ".$x."</a></td></tr>\n";
							$last=$x;
						}else{
							$gametitle=explode ('<title>', $query_s);
							$gametitle=explode ('</title>', $gametitle[1]);
							$gametitle=trim($gametitle[0]);
			
						
							$dom="http://www.vizzed.net/";
			
							$url=explode ('"http://www.vizzed.net/', $query_s);
							$url=explode ('"', $url[1]);
			
							if(!$url[0]){
								$dom="http://www.vizzed.co/";
								$url=explode ('"http://www.vizzed.co/', $query_s);
								$url=explode ('"', $url[1]);
							}
			
							if(!$url[0]){
								$dom="http://www.get-your-rom.com/";
								$url=explode ('"http://www.get-your-rom.com/', $query_s);
								$url=explode ('"', $url[1]);
							}
			
							$url=str_replace('#','%23',$url[0]);
			
							$ext=explode ('.',$url);
							$ext=$ext[count($ext)-1];
							
							print "<tr><td>".$row."</td><td><a href=\"".$dom.$url."\">".$gametitle.".".$ext."</a></td></tr>\n";
							$found=true;
						}
					}
				}
			}


		print "</table><br>";
		
		print_r($counterArray);

		if($query) {
			print "\nnext page <a href=?action=onlinecheck&source=".$_GET["source"]."&type=system&ig=".$_GET["ig"]."&system=".$_GET["system"]."&page=".($page+1).">".($page+1)."</a>";

			if(false){
				print "<script>
				window.open('?action=onlinecheck&source=".$_GET["source"]."&type=system&ig=".$_GET["ig"]."&system=".$_GET["system"]."&page=".($page+1)."','_blank');
				</script>";
			}
		}

	}else{

		foreach($systems as $system){
	   		print "<a href=?action=onlinecheck&source=".$_GET["source"]."&type=system&system=".$system[0].">".$system[1]."</a>\n";
		}
		

		print"<form method=\"get\" action=\"?\">
		<input name=action value=onlinecheck type=hidden>
		<input name=source value=".$_GET["source"]." type=hidden>
		<input name=type value=system type=hidden>
<input name=search><select name=system>";

		foreach($systems as $system){
	   		print "<option value=".$system[0].">".$system[1]."</option>";
		}

		print"</select><input type=submit></form>";

	}
}elseif($_GET["type"]=="system2"){

	if($_GET["system"]){
		print "load: ".$_GET["system"]."\n\n<table>";
		
		if($_GET["page"]){
			$page=$_GET["page"];
		}else{
			$page=1;
		}
		
		$counterArray=Array();

			$query=implode ('', file ("http://two.webproxy.at/surf/browse.php?u=".urlencode("http://www.vizzed.com/playonlinegames/index.php?page=".$page."&sort=id&system=".$_GET["system"]."&search=".$_GET["search"])."&b=4"));

			$counter=explode ('<b>Page ', $query);
			$counter=explode ('</b>', $counter[1]);
			$counterArray[]=$counter[0];

			$query=explode ('<b>Search Results </b>', $query);
			$query=explode ('::', $query[1]);
			$query=explode ("href='/surf/browse.php?u=http%3A%2F%2Fwww.vizzed.com%2Fplay%2F", $query[0]);
			array_splice ($query,0,1);
			
			if(!$query) break;

			$found=false;

			foreach($query as $row){
				$row=explode ("'", $row);
				$row=explode ("-", $row[0]);
				$row=$row[count($row)-2];

				if(($r_query[$row])&&($_GET["ig"]=="")){
					print "<tr><td></td><td>reject ".$row.": allready loaded</td></tr>\n";
		        }else{

					if($_GET["system"]=="psx"){
                    	$query_s="You cannot play Playstation games.  Either become a staff member or buy the Playstation item from the Item Shop.";
					} else {
						$query_s=implode ('', file ("http://two.webproxy.at/surf/browse.php?u=".urlencode("http://www.vizzed.com/playonlinegames/play.php?id=".$row)."&b=4"));
					}

					if(strrpos($query_s,"Game doesn't exist.")){
						print "<tr><td></td><td>reject ".$row.": Game doesn't exist</td></tr>\n";
					}elseif(strrpos($query_s,"This game is not playable")){
						print "<tr><td></td><td>reject ".$row.": This game is not playable</td></tr>\n";
					}elseif(strrpos($query_s,"You cannot play Playstation games")){
						print "<tr><td>".$row."</td><td><a href=http://two.webproxy.at/surf/browse.php?u=".urlencode("http://www.vizzed.com/playonlinegames/play.php?id=".$row)."&b=4>Playstation game ".$row."</a></td></tr>\n";
						$found=true;
					}else{
						$gametitle=explode ('<title>', $query_s);
						$gametitle=explode ('</title>', $gametitle[1]);
						$gametitle=trim($gametitle[0]);
					
						$dom="http://www.vizzed.net/";
		
						$url=explode ('"http://www.vizzed.net/', $query_s);
						$url=explode ('"', $url[1]);
		
						if(!$url[0]){
							$dom="http://www.vizzed.co/";
							$url=explode ('"http://www.vizzed.co/', $query_s);
							$url=explode ('"', $url[1]);
						}
		
						$url=str_replace('#','%23',$url[0]);
		
						$ext=explode ('.',$url);
						$ext=$ext[count($ext)-1];

						print "<tr><td>".$row."</td><td><a href=\"".$dom.$url."\">".$gametitle.".".$ext."</a></td></tr>\n";
						$found=true;
						
						if(!$url){
							print "error".$x ;
							break;
						}
					}
				}
			}


		print "</table><br>";
		
		print_r($counterArray);

		if($query) {
			print "\nnext page <a href=?action=onlinecheck&source=".$_GET["source"]."&type=system2&ig=".$_GET["ig"]."&system=".$_GET["system"]."&page=".($page+1).">".($page+1)."</a>";
		}

	}else{

		foreach($systems as $system){
	   		print "<a href=?action=onlinecheck&source=".$_GET["source"]."&type=system2&system=".$system[0].">".$system[1]."</a>\n";
		}
		

		print"<form method=\"get\" action=\"?\">
		<input name=action value=onlinecheck type=hidden>
		<input name=source value=".$_GET["source"]." type=hidden>
		<input name=type value=system2 type=hidden>
<input name=search><select name=system>";

		foreach($systems as $system){
	   		print "<option value=".$system[0].">".$system[1]."</option>";
		}

		print"</select><input type=submit></form>";

	}
}

	print "\n\n <a href=?action=onlinecheck&source=".$_GET["source"].">back to top</a>\n";

?>
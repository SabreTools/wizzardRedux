<?php
if($_GET["type"]=='forum')
{
	$r_query=implode ('', file ($_GET["source"]."/ids.txt"));
	$r_query=explode ("\r\n","\r\n".$r_query);
	$r_query=array_flip($r_query);

	print "<pre>";

	$topics=Array(
	//Atari Systems
		'16-atari-2600',								//	Atari 2600
			'50-atari-2600-programming', 				// 		Atari 2600 Programming 				//	139-atari-2600-programming
				'31-2600-programming-for-newbies', 		// 			2600 Programming For Newbies
				'65-batari-basic',						// 			batari Basic
			'59-2600-high-score-club',					//		2600 High Score Club
			'124-harmony-cartridge',					//		Harmony Cartridge
			'64-2600-game-descriptions',				//		2600 Game Descriptions
		'3-atari-5200',									//	Atari 5200
			'51-atari-5200-8-bit-programming',			// 		Atari 5200 / 8-bit Programming
			'88-5200-high-score-club',					//		5200 High Score Club
		'4-atari-7800',									//	Atari 7800
			'52-atari-7800-programming',				// 		Atari 7800 Programming
			'89-7800-high-score-club',					//		7800 High Score Club
		'13-atari-lynx',								//	Atari Lynx
			'53-atari-lynx-programming', 				//		Atari Lynx Programming
			'103-lynx-high-score-club',					//		Lynx High Score Club
		'14-atari-jaguar',								//	Atari Jaguar
			'54-atari-jaguar-programming', 				//		Atari Jaguar Programming
				'161-raptor-basic-and-raptor-basic', 	//			RAPTOR Basic and RAPTOR Basic+
				'160-raptor-api', 						//			RAPTOR API
			'98-jaguar-high-score-club',				//		Jaguar High Score Club
		'19-dedicated-systems',							//	Dedicated Systems
			'69-atari-flashback-consoles',				//		Atari Flashback Consoles
		'12-atari-8-bit-computers',						//	Atari 8-Bit Computers
			'51-atari-5200-8-bit-programming',			// 		Atari 5200 / 8-bit Programming
			'60-8-bit-high-score-club',					//		8-bit High Score Club
		'20-atari-sttt-computers',						//	Atari ST/TT Computers
	//Gaming General
		'17-classic-gaming-general',					//	Classic Gaming General
			'115-colecovision-adam',					//		ColecoVision / Adam
				'55-colecovision-programming',			//			ColecoVision Programming
				'96-colecovision-high-score-club',		//			ColecoVision High Score Club
				'117-opcode-games',						//			Opcode Games
			'125-intellivision-aquarius',				//		Intellivision / Aquarius
				'144-intellivision-programming',		//			Intellivision Programming
				'128-intellivision-high-score-club',	//			Intellivision High Score Club
			'109-x-port-emulation-development',			//		X-Port Emulation Development
			'92-nes-high-score-club',					//		NES High Score Club
			'93-sms-high-score-club',					//		SMS High Score Club
			'148-sega-genesis',							//		Sega Genesis
		'116-classic-computing',						//	Classic Computing
		'21-modern-gaming',								//	Modern Gaming
			'77-microsoft-xbox-360',					//		Microsoft Xbox 360
			'78-nintendo-wii',							//		Nintendo Wii
			'79-sony-playstation-3',					//		Sony Playstation 3
		'36-prototypes',								//	Prototypes
		'18-arcade-coin-ops',							//	Arcade Coin-ops
			'107-arcademame-high-score-club',			//	Arcade/MAME High Score Club
		'5-emulation',									//	Emulation
		'8-hardware',									//	Hardware
		'23-gaming-publications-and-websites',			//	Gaming Publications and Websites
			'101-2600-connection-newsletter',			//	2600 Connection Newsletter
		'9-international',								//	International
	//Community
		'85-high-score-clubs',							//	High Score Clubs
			'59-2600-high-score-club',					//		2600 High Score Club
			'88-5200-high-score-club',					//		5200 High Score Club
			'89-7800-high-score-club',					//		7800 High Score Club
			'98-jaguar-high-score-club',				//		Jaguar High Score Club
			'60-8-bit-high-score-club',					//		8-bit High Score Club
			'92-nes-high-score-club',					//		NES High Score Club
			'93-sms-high-score-club',					//		SMS High Score Club
			'96-colecovision-high-score-club',			//		ColecoVision High Score Club
			'128-intellivision-high-score-club',		//		Intellivision High Score Club
			'107-arcademame-high-score-club',			//	Arcade/MAME High Score Club
	//Game Programming
		'29-homebrew-discussion',						//	Homebrew Discussion
			'100-boulder-dash-development-blog',		//		Boulder Dash® Development Blog
		'11-programming',								// 	Programming
			'50-atari-2600-programming', 				// 		Atari 2600 Programming 				//	139-atari-2600-programming
				'31-2600-programming-for-newbies', 		// 			2600 Programming For Newbies
				'65-batari-basic',						// 			batari Basic
			'51-atari-5200-8-bit-programming',			// 		Atari 5200 / 8-bit Programming
			'52-atari-7800-programming',				// 		Atari 7800 Programming
			'53-atari-lynx-programming', 				//		Atari Lynx Programming
			'54-atari-jaguar-programming', 				//		Atari Jaguar Programming
				'161-raptor-basic-and-raptor-basic', 	//			RAPTOR Basic and RAPTOR Basic+
				'160-raptor-api', 						//			RAPTOR API
			'55-colecovision-programming',				//		ColecoVision Programming
			'144-intellivision-programming',			//		Intellivision Programming
			'119-ti-994a-programming',					//		TI-99/4A Programming
		'30-hacks',										// 	Hacks
			'66-atari-2600-hacks',						// 		Atari 2600 Hacks
			'67-atari-5200-hacks',						// 		Atari 5200 Hacks
			'68-atari-7800-hacks',						// 		Atari 7800 Hacks
	);

	$bad_ext=Array(
		'asm',
		'bas',
		'bmp',
		'doc',
		'gif',
		'jpg',
		'pdf',
		'png',
		'rtf',
		'txt',
		'xls',
	);

	$max=1000000;

	$newAttachIDs=Array();
	$newDLIDs=Array();
	$checked=Array();

	error_reporting(E_ERROR | E_PARSE);

	foreach($topics as $topic){
		if(!$checked[$topic])
		{
			$checked[$topic]=true;
			for ($x=1;$x<$max;$x++)
			{
				print "load: ".$topic." * ".$x."\n";
	
				$query=implode ('', file ("http://atariage.com/forums/forum/".$topic."/page-".$x."?prune_day=100&sort_by=Z-A&sort_key=last_post&topicfilter=all"));
	
				$next=explode ("rel='next'>Next</a></li>",$query);
	
				$pinned=explode (">Pinned<",$query);
				$pinned[0]=null;
	
				$pinnedAttachments=Array();

				foreach($pinned as $pin){
					if($pin){
						$attachset=explode ('id="tid-link-',$pin);
						$attachset=explode ('"',$attachset[1]);
						$attachset=$attachset[0];
						$pinnedAttachments[$attachset]=true;
					}
				}
	
				$section=explode ("<h1 class='ipsType_pagetitle'>",$query);
				$section=explode ("</h1>",$section[1]);
				$section=$section[0];
	
				$query=explode ('data-tid="',$query);
				$query[0]=null;

				foreach($query as $row){
					if($row){
						$attachset=explode ('"',$row);
						$attachset=$attachset[0];

						$attachcount=explode ('UserComments:',$row);
						$attachcount=explode ('"',$attachcount[1]);
						$attachcount=$attachcount[0];

						if($r_query[$attachset.'*'.$attachcount]){
							if($pinnedAttachments[$attachset]){
								print "skipp pinned ".$attachset.'*'.$attachcount."\n";
								continue;
							} else{
								$x=$max;
								print "break by ".$attachset.'*'.$attachcount."\n";
								break;
							}
						}

						$newAttachIDs[]=$attachset.'*'.$attachcount;

						$attachtitle=explode ('<span itemprop="name">',$row);
						$attachtitle=explode ('</span>',$attachtitle[1]);
						$attachtitle=$attachtitle[0];

						for ($y=0;$y<1000;$y++)
						{
							print "load: ".$attachset." * ".($y+1)."\n";
							$b_query=file ("http://atariage.com/forums/index.php?app=forums&module=forums&section=attach&tid=".$attachset."&st=".($y*50));

							if($b_query){
								$new=0;
								$old=0;
								$reject=0;

								$b_query=implode ('', $b_query);
								$b_next=explode ("rel='next'>Next</a></li>",$b_query);

								$b_query=explode ("post&amp;attach_id=",$b_query);
								$b_query[0]=null;

								foreach($b_query as $dl){
									if($dl){
										$dl_id=explode ('"',$dl);
										$dl_id=$dl_id[0];

										if(!$r_query[$dl_id.'#0']){
											$dl_date=explode ('<br />( Posted on ',$dl);
											$dl_date=explode (' )',$dl_date[1]);
											$dl_date=strtotime($dl_date[0]);
											$dl_date=date('Y.m.d H.i',$dl_date);

											$dl_title=explode ('" title="',$dl);
											$dl_title=explode ('"',$dl_title[1]);
											$dl_title=$dl_title[0];

											$dl_ext=explode ('.',$dl_title);
											$dl_ext=strtolower($dl_ext[count($dl_ext)-1]);

											$dl_title=substr($dl_title, 0, -(strlen($dl_ext)+1));
											if(!in_array($dl_ext,$bad_ext)){
												$newDLIDs[]=Array($dl_id,"{".$section."}".$attachtitle." (".$dl_title.") (".$dl_date.").".$dl_ext);
												$newAttachIDs[]=$dl_id.'#0';
												$new++;
											}else{
												$reject++;
											}
										}else{
											$old++;
										}
									}
								}
								
								print "found: old:".$old.", new:".$new.", reject:".$reject."\n";

								if(!$b_next[1]){
									break;
								}
							}else{
								break;
                            }
						}
					}
				}
	
				if(!$next[1]){
					$x=$max;
					break;
				}
			}
		}
	}

	print "\nnew IDs:\n<table><tr><td><pre>";

	foreach($newAttachIDs as $ID){
		print $ID."\n";
	}

	print "</td></tr></table>\nnew DLs:\n<table><tr><td><pre>";

	foreach($newDLIDs as $ID){
		print "<a href=\"http://atariage.com/forums/index.php?app=core&amp;module=attach&amp;section=attach&amp;attach_rel_module=post&amp;attach_id=".$ID[0]."\" >".$ID[1]."</a>\n";
	}

	print "</td></tr></table>";

}elseif($_GET["type"]=='main')
{
	$r_query=implode ('', file ($_GET["source"]."/ids.txt"));
	$r_query=explode ("\r\n","\r\n".$r_query);
	$r_query=array_flip($r_query);

	$systems=Array(
		Array('2600',	'Atari - 2600'),
		Array('5200',	'Atari - 5200'),
		Array('7800',	'Atari - 7800'),
		Array('LYNX',	'Atari - Lynx'),
	);

	print "<pre>";

	foreach($systems as $system)
	{
		$query2=Array();
		$count=0;

		print "load page for ".$system[1].", ";

		$query=implode ('', file ("http://www.atariage.com/software_list.html?SystemID=".$system[0]."&searchROM=checkbox&recordsPerPage=100000"));
		$query=explode ('atariage.com/software_page.html?SoftwareLabelID=', $query);

		for($x=1;$x<count($query);$x++)
		{
			$id=explode('"', $query[$x]);
			$id=$id[0];
			
			if(!$r_query[$id])
			{
				$query2[]=$id;
			}
			$count++;
		}

		print "found ".$count." entrys, ".count($query2)." are new\n";

		print "<table><tr><td><pre>";

		foreach($query2 as $row)
		{
			print "<a href=http://www.atariage.com/software_page.html?SoftwareLabelID=".$row." target=_blank>".$row."</a>\n";
		}

		print "</td><td><pre>";

		foreach($query2 as $row)
		{
			$query=implode ('', file ("http://www.atariage.com/software_page.html?SoftwareLabelID=".$row));

			$gametitle=explode ('<span class="gametitle">', $query);
			$gametitle=explode ('</span>', $gametitle[1]);
			$gametitle=$gametitle[0];

			$rarity=explode ('<a href="http://www.atariage.com/common/rarity_key.html" title="', $query);
			$rarity=explode (' - ', $rarity[1]);
			$rarity=$rarity[0];

			$region=explode ('<a href="http://www.atariage.com/common/region_key.html" title="', $query);
			$region=explode (' - ', $region[1]);
			$region=$region[0];

			$video=explode ('<a href="http://www.atariage.com/common/video_key.html" title="', $query);
			$video=explode (' - ', $video[1]);
			$video=$video[0];

			$url=explode ('<img src="http://www.atariage.com/images/buttons/ShotButton.gif"  width="18" height="18" /></a>&nbsp;<a href="', $query);
			$url=explode ('"', $url[1]);
			$url=$url[0];

			$company=explode ('<b>Company:</b>', $query);
			$company=explode ('</td>', $company[1]);
			$company=trim(strip_tags($company[0]));

			$developer=explode ('<b>Developer:</b>', $query);
			$developer=explode ('</td>', $developer[1]);
			$developer=trim(strip_tags($developer[0]));

			$model=explode ('<b>Model #:</b>', $query);
			$model=explode ('</td>', $model[1]);
			$model=trim(strip_tags($model[0]));

			$year=explode ('<b>Year of Release: </b>', $query);
			$year=explode ('</td>', $year[1]);
			$year=trim(strip_tags($year[0]));

			$name=$gametitle;

			$manufactor=Array();
			if($company) $manufactor[]=$company;
			if($developer) $manufactor[]=$developer;
			if($manufactor) $name=$name." (".implode(', ', $manufactor).")";

			if($year!='n/a') $name=$name." (".$year.")";

			$location=Array();
			if($region) $location[]=$region;
			if($video) $location[]=$video;
			if($location) $name=$name." (".implode(', ', $location).")";

			$propertys=Array();
			if(in_array($rarity, array('Homebrew','Reproduction','Prototype'))) $propertys[]=$rarity;
			if($model!='n/a') $propertys[]=$model;
			if($propertys) $name=$name." (".implode(', ', $propertys).")";

			print "<a href=".$url." target=_blank>".$name.".zip</a>\n";

		}

		print "</td></tr></table>";

		print "\n";
	}
}else{
	print "<pre>";
	print "load <a href=?action=onlinecheck&source=".$_GET["source"]."&type=main>main</a>\n";
	print "load <a href=?action=onlinecheck&source=".$_GET["source"]."&type=forum>forum</a>\n";

}
?>
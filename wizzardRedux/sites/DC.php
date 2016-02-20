<?php
	$r_query=implode ('', file ($_GET["source"]."/ids.txt"));
	$r_query=explode ("\r\n","\r\n".$r_query);
	$r_query=array_flip($r_query);

	$sides=Array(
		'Exeltel'	=>	Array('http://dcexel.free.fr/programmes/_html/index.html',null,1),
		'Thomson'	=>	Array('http://dcmoto.free.fr/programmes/_html/index.html',null,3),
		'Thomson2'	=>	Array('http://mo5.free.fr/title1.html',null,4),
		'Alice'		=>	Array('http://alice32.free.fr/soft/_html/index.html',null,1),
		'vg5000'	=>	Array('http://dcvg5k.free.fr/programmes/_html/index.html',null,1),
		'hector'	=>	Array('http://dchector.free.fr/index.html',null,2,'k7/_html'),
	);
	
	$searchs=Array(
		'1' => Array('Editeur :','Auteur :'	,'Editeurs :'	,'Auteurs :','Ann&eacute;e :'),
		'3' => Array('Editeur :','Auteur :'	,'Editeurs :'	,'Auteurs :','Ann&eacute;e :'),
		'2' => Array('Editeur:'	,'Auteur:'	,'Editeurs:'	,'Auteurs:'	,'Version:'),
	);

	$runs=implode ('', file ($_GET["source"]."/run.txt"));
	$runs=explode ("\n",$runs);

	foreach($runs as $run)
	{
		$run=explode ("\t",$run);
		$sides[$run[0]][1]=$run[1];
	}

	print "<pre>";

	foreach($sides as $key => $side){
		print "<a href=?action=onlinecheck&source=DC&system=".$key.">".$key."</a> ".$side[1]."\n";
	}

	$URLs=array();

	if($_GET["system"]){
		$fp = fopen($_GET["source"]."/run.txt", "a");
		fwrite($fp,	$_GET["system"]."\t".date('d.m.Y')."\n");
		fclose($fp);

		if($sides[$_GET["system"]][2]==4){
			$t_dir=explode ("/",$sides[$_GET["system"]][0]);
			$t_dir[count($t_dir)-1]=null;
			$t_dir=implode ("/",$t_dir);

			$t_query=implode ('', file ($sides[$_GET["system"]][0]));
			$t_query=explode ('<tr><td bgcolor="#dfffff">',$t_query);
			$t_query[0]=null;

			$new=0;
			$old=0;

			foreach($t_query as $row){
				if($row){
					$titel=explode ('<',$row);
					$titel=trim($titel[0]);

					$pub=explode ('<td>',$row);
					$dls=$pub[7];
					$autor=explode ('<',$pub[2]);
					$autor=trim($autor[0]);
					$year=explode ('<',$pub[3]);
					$year=trim($year[0]);
					$pub=explode ('<',$pub[1]);
					$pub=trim($pub[0]);

					$info=Array();
					if(($pub)&&		($pub!='*'))	$info[]=$pub;
					if(($autor)&&	($autor!='*'))	$info[]=$autor;
					if(($year)&&	($year!='*'))	$info[]=$year;

					$titel=$titel." (".implode(') (',$info).")";

					$dls=explode ('<a href="',$dls);
					$dls[0]=null;
					foreach($dls as $dl){
						if($dl){
							$url=explode ('"',$dl);
							$url=$t_dir.$url[0];
							$utlt=explode ('<',$dl);
							$utlt=explode ('>',$utlt[0]);
							$utlt=$utlt[1];

							$ext=explode ('.',$url);
							$ext=$ext[count($ext)-1];

							if(!$r_query[$url])
							{
								$URLs[]=Array($url,$titel." (".$utlt.").".$ext);
								$new++;
							}
							else
							{
								$old++;
							}
						}
					}
				}
			}
			print "new: ".$new.", old: ".$old."\n";
		}else{
			if(($sides[$_GET["system"]][2]==1)||($sides[$_GET["system"]][2]==3)){
				print "\nload ".$_GET["system"]." ".$sides[$_GET["system"]][0]."\n";
				$t_query=implode ('', file ($sides[$_GET["system"]][0]));
				$t_query=explode ('<li class="niveau0">Titre',$t_query);
				$t_query=explode ('</ul>',$t_query[1]);
				$t_query=explode ('<a href="',$t_query[0]);
				$t_query[0]=null;
				$t_dir=explode ("/",$sides[$_GET["system"]][0]);
				$t_dir[count($t_dir)-1]=null;
				$t_dir=implode ("/",$t_dir);
			} else {
				$t_query=Array($sides[$_GET["system"]][0]);
			}
	
			foreach($t_query as $url){
				if($url){
					$url=explode ('"',$url);
					$url=$t_dir.$url[0];
		
					print "url ".$url."\n";
		
					$dir=explode ("/",$url);
					$dir[count($dir)-1]=null;
					$dir=implode ("/",$dir);
	
					$query=implode ('', file ($url));
	
					if($sides[$_GET["system"]][2]==1)
					{
						$query=explode ('<div id="mainsection">',$query);
	     				$query=explode ('<a href="',$query[1]);
					} elseif($sides[$_GET["system"]][2]==3)
					{
						$query=explode ('<hr noshade>',$query);
						$query=explode ('<a href="',$query[1]);
					} else {
						$query=explode ('<a href="'.$sides[$_GET["system"]][3],$query);
					}
	
					//print_r($query);
	
					$query[0]=null;
	
					$new=0;
					$old=0;
					$other=0;
	
					foreach($query as $row){
						if($row){
							$row=explode ('"',$row);
							$row=$sides[$_GET["system"]][3].$row[0];
							print $dir.$row."\n";

							$b_query=implode ('', file ($dir.$row));
	
							if($sides[$_GET["system"]][2]==1)
							{
								$title=explode ('alt="logo">',$b_query);
								$title=explode ('</p>',$title[1]);
								$title=strip_tags(str_replace("\r\n","",$title[0]));
							} elseif($sides[$_GET["system"]][2]==3)
							{
								$title=explode ('<h2>',$b_query);
								$title=explode ('</h2>',$title[1]);
								$title=$title[0];
							} else {
								$title=explode ('<h2><big>',$b_query);
								$title=explode ('</big></h2>',$title[1]);
								$title=$title[0];
							}
	
							$info=Array();
	
							foreach($searchs[$sides[$_GET["system"]][2]] as $search)
							{
								$value=explode ($search.' <b>',$b_query);
								$value=explode ('</b>',$value[1]);
								if(($value[0])&&($value[0]!='Inconnu')&&($value[0]!='Inconnue')) $info[]=$value[0];
							}
	
							if($info) $title=$title." (".implode(") (",$info).")";
	
							$title=strtr(html_entity_decode($title), $GLOBALS['normalizeChars']);

							/*if($sides[$_GET["system"]][2]==1){
								$c_query=explode ('<div id="mainsection">',$b_query);
							} elseif($sides[$_GET["system"]][2]==3){
								$c_query=explode ('<hr noshade>',$b_query);
							} else {
								$c_query=explode ('<div id="mainSection">',$b_query);
							}*/
	
							$c_query=explode ('<a href="',$b_query);
							$c_query[0]=null;
	
							$dir2=explode ("/",$dir.$row);
							$dir2[count($dir2)-1]=null;
							$dir2=implode ("/",$dir2);
	
							foreach($c_query as $dl){
								if($dl){
									$dl_url=explode ('"',$dl);
									$dl_url=$dir2.$dl_url[0];
									
									if(substr($dl_url,-4)=='.zip')
									{
										if(!$r_query[$dl_url])
										{
											if($sides[$_GET["system"]][2]==1)
											{
												$dl_title=explode ('</a>',$dl);
												$dl_title=strip_tags('<a href="'.$dl_title[0]);
											} else {
												$dl_title=explode (' alt="',$dl);
												$dl_title=explode ('"',$dl_title[1]);
												$dl_title=$dl_title[0];
											}
	
											$URLs[]=Array($dl_url,$title." (".trim($dl_title).").zip");
											$new++;
										}
										else
										{
											$old++;
										}
									}
									else
									{
										$other++;
									}
								}
							}
						}
		            }

					print "new: ".$new.", old: ".$old.", other: ".$other."\n";
				}
			}
		}

		print "<table><tr><td><pre>";
		
		foreach($URLs as $row)
		{
			print $row[0]."\n";
		}
		
		print "</td><td><pre>";
		
		foreach($URLs as $row)
		{
			print "<a href=\"".$row[0]."\" target=_blank>".$row[1]."</a>\n";
		}
		print "</td></tr></table>";
	}

?>
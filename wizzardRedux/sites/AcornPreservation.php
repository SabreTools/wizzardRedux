<!--
THIS FILE IS NO LONGER USED BECAUSE THE SITE IS DEAD
-->

<?php
print "<pre>";

	$r_query=implode ('', file ($_GET["source"]."/ids.txt"));
	$r_query=explode ("\r\n",$r_query);
	$r_query=array_flip($r_query);

$pages=Array(
	"http://www.acornpreservation.org/main_tapes_games.html",
	"http://www.acornpreservation.org/main_discs_games.html",
);

$URLs=Array();

foreach ($pages as $page)
{
	print "load ".$page."\n";

	$old=0;
	$new=0;
	$other=0;

	$content=implode ('', file ($page));
	$content=explode ('HREF="',$content);
	$content[0]=null;

	foreach($content as $row){
		if($row){
			$url=explode ('"',$row);
			$url=$url[0];
			$ext=explode('.',$url);

			if(strtolower($ext[count($ext)-1])=='zip')
			{
				if(!$r_query[$url])	{
					$URLs[]=$url;
					$new++;
				} else {
					$old++;
				}
			} else {
				$other++;
			}
		}
	}

	print "new ".$new.", old ".$old.", other ".$other."\n";
}

foreach($URLs as $row)
{
	print "<a href=\"http://www.acornpreservation.org/".$row."\" target=_blank>".$row."</a>\n";
}
?>
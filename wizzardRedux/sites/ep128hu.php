<?php

print "<pre>";

$r_query=implode ('', file ($_GET["source"]."/ids.txt"));
$r_query=explode ("\r\n","\r\n".$r_query);
$r_query=array_flip($r_query);

$newURLs=Array();
$dirs=Array();

$pages=Array(
	Array ('Ep_Games/Games_09_eng.htm','EP'),
	Array ('Ep_Games/Games_AB_eng.htm','EP'),
	Array ('Ep_Games/Games_CD_eng.htm','EP'),
	Array ('Ep_Games/Games_EF_eng.htm','EP'),
	Array ('Ep_Games/Games_GH_eng.htm','EP'),
	Array ('Ep_Games/Games_IJ_eng.htm','EP'),
	Array ('Ep_Games/Games_KL_eng.htm','EP'),
	Array ('Ep_Games/Games_MN_eng.htm','EP'),
	Array ('Ep_Games/Games_OP_eng.htm','EP'),
	Array ('Ep_Games/Games_QR_eng.htm','EP'),
	Array ('Ep_Games/Games_ST_eng.htm','EP'),
	Array ('Ep_Games/Games_UV_eng.htm','EP'),
	Array ('Ep_Games/Games_WX_eng.htm','EP'),
	Array ('Ep_Games/Games_YZ_eng.htm','EP'),
	Array ('Ep_Games/Games_09.htm','EP'),
	Array ('Ep_Games/Games_AB.htm','EP'),
	Array ('Ep_Games/Games_CD.htm','EP'),
	Array ('Ep_Games/Games_EF.htm','EP'),
	Array ('Ep_Games/Games_GH.htm','EP'),
	Array ('Ep_Games/Games_IJ.htm','EP'),
	Array ('Ep_Games/Games_KL.htm','EP'),
	Array ('Ep_Games/Games_MN.htm','EP'),
	Array ('Ep_Games/Games_OP.htm','EP'),
	Array ('Ep_Games/Games_QR.htm','EP'),
	Array ('Ep_Games/Games_ST.htm','EP'),
	Array ('Ep_Games/Games_UV.htm','EP'),
	Array ('Ep_Games/Games_WX.htm','EP'),
	Array ('Ep_Games/Games_YZ.htm','EP'),
	Array ('Ep_Games/Orksoft.htm','EP'),
	Array ('Ep_Games/Games_Ep64_eng.htm','EP'),
	Array ('Ep_Games/Games_Ep64.htm','EP'),
	Array ('Ep_Demo/Demo_eng.htm','EP'),
	Array ('Ep_Demo/Demo.htm','EP'),
	Array ('Ep_Util/Ep_Util_eng.htm','EP'),
	Array ('Ep_Util/Ep_Util.htm','EP'),
	Array ('Game_SP_09.htm','ZX'),
	Array ('Game_SP_A.htm','ZX'),
	Array ('Game_SP_B.htm','ZX'),
	Array ('Game_SP_C.htm','ZX'),
	Array ('Game_SP_D.htm','ZX'),
	Array ('Game_SP_E.htm','ZX'),
	Array ('Game_SP_F.htm','ZX'),
	Array ('Game_SP_G.htm','ZX'),
	Array ('Game_SP_H.htm','ZX'),
	Array ('Game_SP_I.htm','ZX'),
	Array ('Game_SP_J.htm','ZX'),
	Array ('Game_SP_K.htm','ZX'),
	Array ('Game_SP_L.htm','ZX'),
	Array ('Game_SP_M.htm','ZX'),
	Array ('Game_SP_N.htm','ZX'),
	Array ('Game_SP_O.htm','ZX'),
	Array ('Game_SP_P.htm','ZX'),
	Array ('Game_SP_Q.htm','ZX'),
	Array ('Game_SP_R.htm','ZX'),
	Array ('Game_SP_S.htm','ZX'),
	Array ('Game_SP_T.htm','ZX'),
	Array ('Game_SP_U.htm','ZX'),
	Array ('Game_SP_V.htm','ZX'),
	Array ('Game_SP_W.htm','ZX'),
	Array ('Game_SP_X.htm','ZX'),
	Array ('Game_SP_Y.htm','ZX'),
	Array ('Game_SP_Z.htm','ZX'),
	Array ('Sp_Demo/Demo.htm','ZX'),
);

foreach ($pages as $page){
	print "load: ".$page[0]."\n";
	$query=str_replace('&nbsp;','',implode ('', file ('http://www.ep128.hu/'.$page[0])));
	$query=explode('<tr class=',$query);
	array_splice ($query,0,1);

	$new=0;
	$old=0;

	$dir=explode('/',$page[0]);
	if($dir[1]){
		$dir=$dir[0]."/";
	}else{
		$dir="";
	}

	foreach($query as $row){

		$row=explode('<td',$row);
		$title=trim(strip_tags('<td'.$row[1]));

		$info=Array();

		for($x=2;$x<7;$x++)
		{
			$temp=trim(strip_tags('<td'.$row[$x]));
			if($temp)$info[]=$temp;
		}

		if($info)$title=$title." (".implode(") (",$info).")";

		$title=preg_replace('/\s+/'," ",$title);

		$DLs=explode('href="',$row[1]);
		array_splice ($DLs,0,1);

		foreach($DLs as $DL){
			$DL=explode('"',$DL);
			$DL=$dir.$DL[0];

			$ext=explode('.',$DL);
			$ext=$ext[count($ext)-1];

			if(!$r_query[$DL])
			{
				$newURLs[]=Array("{".$page[1]."}".$title.".".$ext,$DL);
				$r_query[$DL]=true;
				$new++;
			}
			else
			{
				$old++;
			}
        }
	}

	print "new: ".$new.", old: ".$old."\n";
}

print "\nnew urls:\n\n";
print "<table><tr><td><pre>";

foreach($newURLs as $url)
{
	print $url[1]."\n";
}

print "</td><td><pre>";

foreach($newURLs as $url)
{
	print "<a href=\"http://www.ep128.hu/".$url[1]."\">".$url[0]."</a>\n";
}

print "</td></tr></table>";

?>
<?php

// Original code: The Wizard of DATz

print "<pre>";

$dirs = array();

$pages = array(
	array('Ep_Games/Games_09_eng.htm', 'EP'),
	array('Ep_Games/Games_AB_eng.htm', 'EP'),
	array('Ep_Games/Games_CD_eng.htm', 'EP'),
	array('Ep_Games/Games_EF_eng.htm', 'EP'),
	array('Ep_Games/Games_GH_eng.htm', 'EP'),
	array('Ep_Games/Games_IJ_eng.htm', 'EP'),
	array('Ep_Games/Games_KL_eng.htm', 'EP'),
	array('Ep_Games/Games_MN_eng.htm', 'EP'),
	array('Ep_Games/Games_OP_eng.htm', 'EP'),
	array('Ep_Games/Games_QR_eng.htm', 'EP'),
	array('Ep_Games/Games_ST_eng.htm', 'EP'),
	array('Ep_Games/Games_UV_eng.htm', 'EP'),
	array('Ep_Games/Games_WX_eng.htm', 'EP'),
	array('Ep_Games/Games_YZ_eng.htm', 'EP'),
	array('Ep_Games/Games_09.htm', 'EP'),
	array('Ep_Games/Games_AB.htm', 'EP'),
	array('Ep_Games/Games_CD.htm', 'EP'),
	array('Ep_Games/Games_EF.htm', 'EP'),
	array('Ep_Games/Games_GH.htm', 'EP'),
	array('Ep_Games/Games_IJ.htm', 'EP'),
	array('Ep_Games/Games_KL.htm', 'EP'),
	array('Ep_Games/Games_MN.htm', 'EP'),
	array('Ep_Games/Games_OP.htm', 'EP'),
	array('Ep_Games/Games_QR.htm', 'EP'),
	array('Ep_Games/Games_ST.htm', 'EP'),
	array('Ep_Games/Games_UV.htm', 'EP'),
	array('Ep_Games/Games_WX.htm', 'EP'),
	array('Ep_Games/Games_YZ.htm', 'EP'),
	array('Ep_Games/Orksoft.htm', 'EP'),
	array('Ep_Games/Games_Ep64_eng.htm', 'EP'),
	array('Ep_Games/Games_Ep64.htm', 'EP'),
	array('Ep_Demo/Demo_eng.htm', 'EP'),
	array('Ep_Demo/Demo.htm', 'EP'),
	array('Ep_Util/Ep_Util_eng.htm', 'EP'),
	array('Ep_Util/Ep_Util.htm', 'EP'),
	array('Game_SP_09.htm', 'ZX'),
	array('Game_SP_A.htm', 'ZX'),
	array('Game_SP_B.htm', 'ZX'),
	array('Game_SP_C.htm', 'ZX'),
	array('Game_SP_D.htm', 'ZX'),
	array('Game_SP_E.htm', 'ZX'),
	array('Game_SP_F.htm', 'ZX'),
	array('Game_SP_G.htm', 'ZX'),
	array('Game_SP_H.htm', 'ZX'),
	array('Game_SP_I.htm', 'ZX'),
	array('Game_SP_J.htm', 'ZX'),
	array('Game_SP_K.htm', 'ZX'),
	array('Game_SP_L.htm', 'ZX'),
	array('Game_SP_M.htm', 'ZX'),
	array('Game_SP_N.htm', 'ZX'),
	array('Game_SP_O.htm', 'ZX'),
	array('Game_SP_P.htm', 'ZX'),
	array('Game_SP_Q.htm', 'ZX'),
	array('Game_SP_R.htm', 'ZX'),
	array('Game_SP_S.htm', 'ZX'),
	array('Game_SP_T.htm', 'ZX'),
	array('Game_SP_U.htm', 'ZX'),
	array('Game_SP_V.htm', 'ZX'),
	array('Game_SP_W.htm', 'ZX'),
	array('Game_SP_X.htm', 'ZX'),
	array('Game_SP_Y.htm', 'ZX'),
	array('Game_SP_Z.htm', 'ZX'),
	array('Sp_Demo/Demo.htm', 'ZX'),
);

foreach ($pages as $page)
{
	print "load: ".$page[0]."\n";
	$query = str_replace('&nbsp;', '', get_data('http://www.ep128.hu/'.$page[0]));
	$query = explode('<tr class=', $query);
	array_splice($query, 0, 1);

	$new = 0;
	$old = 0;

	$dir = explode('/', $page[0]);
	if ($dir[1])
	{
		$dir = $dir[0]."/";
	}
	else
	{
		$dir = "";
	}

	foreach ($query as $row)
	{
		$row = explode('<td', $row);
		$title = trim(strip_tags('<td'.$row[1]));

		$info = array();

		for ($x = 2; $x < 7; $x++)
		{
			$temp = trim(strip_tags('<td'.$row[$x]));
			if ($temp)
			{
				$info[] = $temp;
			}
		}

		if ($info)
		{
			$title = $title." (".implode(") (", $info).")";
		}

		$title = preg_replace('/\s+/', " ", $title);

		$DLs = explode('href="', $row[1]);
		array_splice($DLs, 0, 1);

		foreach ($DLs as $DL)
		{
			$DL = explode ('"', $DL);
			$DL = $dir.$DL[0];

			$ext = explode('.', $DL);
			$ext = $ext[count($ext) - 1];

			if (!$r_query[$DL])
			{
				$found[] = array("{".$page[1]."}".$title.".".$ext, $DL);
				$r_query[$DL] = true;
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

foreach ($found as $url)
{
	print $url[1]."\n";
}

print "</td><td><pre>";

foreach ($found as $url)
{
	print "<a href=\"http://www.ep128.hu/".$url[1]."\">".$url[0]."</a>\n";
}

print "</td></tr></table>";

?>
<?php

// Original code: The Wizard of DATz

print "<pre>";

$new = 0;
$old = 0;

for ($x = 1; $x < 6; $x++)
{
	$query = implode('', file("http://blog.naver.com/PostList.nhn?from=postList&blogId=kevinhwsohn&currentPage=".$x));
	$query = explode("encodedAttachFileUrl': '", $query);
	$query[0] = null;
	foreach ($query as $row)
	{
		if ($row)
		{
			$url = explode("'", $row);
			$url = $url[0];
			$title = explode("/", $url);
			$title = $title[count($title) - 1];
			if (!$r_query[$title])
			{
				$found[] = array($url, $title);
				$r_query[$title] = true;
				$new++;
			}
			else
			{
				$old++;
			}
		}
	}
}

print "found new ".$new.", old ".$old."\n"; 

foreach ($found as $row)
{
	print "<a href=".$row[0]." target=_blank>".$row[1]."</a>\n";
}

?>
<?php
print "<pre>";

	$r_query=implode ('', file ($_GET["source"]."/ids.txt"));
	$r_query=explode ("\r\n","\r\n".$r_query);
	$r_query=array_flip($r_query);

	$newfiles=Array();

	$query=implode ('', file ('http://russianroms.ru/'));
 	$query=explode ('?page_id=',$query);
	$query[0]=null;

	foreach($query as $row){
		if($row){
			$row=explode ('"', $row);
			$row=$row[0];
			$newfiles[]=$row;
        }
	}

	$found = Array();

foreach($newfiles as $newfile){
	print "load ".$newfile."\n";
	$query=implode ('', file ("http://russianroms.ru/?page_id=".$newfile));
 	$query=explode ('"><img src="http://russianroms.narod.ru/linkware.gif"', $query);
	$query[count($query)-1]=null;

	$old=0;
	$new=0;

	foreach($query as $row){
		if($row){
			$row=explode ('"', $row);
			$row=$row[count($row)-1];

		//	if(substr($row, 0, 4)!='http') $row=$dir.$row;

	    	if($r_query[$row])
			{
				$old++;
			}else{
				$found[]=$row;
				$new++;
			}
  		}
	}


	print "found new:".$new.", old:".$old."\n";
}

	foreach($found as $row){
		print "<a href=\"".$row."\">".$row."</a>\n";
	}

?>
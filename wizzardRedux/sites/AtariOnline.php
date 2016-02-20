<?php

if($_GET["type"]=='search')
{

print "<pre>";

	$r_query=implode ('', file ($_GET["source"]."/ids.txt"));
	$r_query=explode ("\r\n","\r\n".$r_query);
	$r_query=array_flip($r_query);

$newfiles=Array(
	'http://atarionline.pl/',
);

	$found = Array();

foreach($newfiles as $newfile){
	print "load ".$newfile."\n";
	$query=implode ('', file ($newfile));
 	$query=explode ('href="/archiwa/',$query);
	$query[0]=null;

	$old=0;
	$new=0;

	foreach($query as $row){
		if($row){
			$row=explode ('"', $row);
			$row=explode ('?', $row[0]);
			$row=trim($row[0]);


	    	if($r_query[$row])
			{
				$old++;
			}else{
				$found[]=$row;
				$new++;
			}
		}
	}


	print "found new:".$new.", old:".$old."\n\n";
}

	foreach($found as $row){
		print "<a href=\"http://atarionline.pl/archiwa/".$row."\">".$row."</a>\n";
	}
	
}elseif($_GET["type"]=='compare'){

	if($GLOBALS[_FILES]){

		$file_old=implode ('', file ($GLOBALS[_FILES][file_old][tmp_name]));
		$file_old=explode ("\r\n", str_replace('\\','+',str_replace('|',' ',$file_old)));
		array_splice ($file_old,0,3);
		$pathArray=Array();
		$file_old_b=Array();
		foreach($file_old as $file){
			$split=explode ("+---", $file);
			if($split[1]){
				$pathLevel=strlen($split[0])/4;
				$pathArray[$pathLevel]=$split[1];
				$path="";
				for($y=0;$y<=$pathLevel;$y++){
					$path=$path.$pathArray[$y]."\\";
				}
			}else{
				$file=trim($file);
				if($file){
					$file_old_b[$path.$file]=1;
				}
			}
		}

		$file_new=implode ('', file ($GLOBALS[_FILES][file_new][tmp_name]));
		$file_new=explode ("\r\n", str_replace('\\','+',str_replace('|',' ',$file_new)));
		$dir=str_replace('+','\\',$file_new[2]);
		array_splice ($file_new,0,3);
		$pathArray=Array();

		foreach($file_new as $file){
			$split=explode ("+---", $file);
			if($split[1]){
				$pathLevel=strlen($split[0])/4;
				$pathArray[$pathLevel]=$split[1];
				$path="";
				for($y=0;$y<=$pathLevel;$y++){
					$path=$path.$pathArray[$y]."\\";
				}

			}else{
				$file=trim($file);
				if($file){
					if(!$file_old_b[$path.$file]){
						print "copy \"".$dir."\\".$path.$file."\" \"".str_replace('\\','^',$path.$file)."\"<br>";
                    }
				}
			}
		}

	}else{
		print'<pre><form method="post" enctype="multipart/form-data" action="?action=onlinecheck&source=AtariOnline&type=compare">
		old file	<input type="file" name="file_old" size="40">
		new file	<input type="file" name="file_new" size="40">
				<input type="submit" value="Send">
		</form>';
	}

}else{
	print "<pre>";
	print "load <a href=?action=onlinecheck&source=".$_GET["source"]."&type=search>search</a>\n";
	print "load <a href=?action=onlinecheck&source=".$_GET["source"]."&type=compare>compare</a>\n";

}

?>
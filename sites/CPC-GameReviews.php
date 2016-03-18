<?php

// Original code: The Wizard of DATz

$mainURL = "ftp://www.cantrell.org.uk/ftp.nvg.ntnu.no/pub/cpc/";

$header = array();
$new = 0;
$old = 0;

echo "<table>\n<tr><td>".$mainURL."00_table.csv</td>";

// Attempt to open the main listing for the site
if (($handle = fopen($mainURL."00_table.csv", "r")) !== FALSE)
{
	// While there's data still left to parse
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
    {
    	// If the header array has stuff in it
		if (sizeof($header) > 0)
		{
			// If the listing isn't found in the array
			if (!$r_query[$data[$header["File Path"]]])
			{
				$ext = pathinfo($data[$header["File Path"]], PATHINFO_EXTENSION);
		
				$info = array();
	
				foreach(array('ORIGINAL TITLE',
						'ALSO KNOWN AS',
						'COMPANY',
						'PUBLISHER',
						'RE-RELEASED BY',
						'YEAR',
						'LANGUAGE',
						'MEMORY REQUIRED',
						'PUBLICATION',
						'PUBLISHER CODE',
						'BARCODE',
						'DL CODE',
						'CRACKER',
						'DEVELOPER',
						'AUTHOR',
						'DESIGNER',
						'ARTIST',
						'MUSICIAN'
				) as $key)
				{
					if ($data[$header[$key]])
					{
						$info[] = $data[$header[$key]];
					}
				}
	
				if ($info)
				{
					$title = $data[$header[TITLE]].' ('.implode(') (', $info).').'.$ext;
				}
				else
				{
					$title = $data[$header[TITLE]].'.'.$ext;
		        }
		
		 		if (!$data[$header[TITLE]])
		 		{
		 			$title = $data[$header["File Path"]];
		 		}
	
				$found[] = array($title, $mainURL.$data[$header["File Path"]]);
				$new++;
			}
			else
			{
				$old++;
			}
		}
		else
		{
			$header = $data;
			$header = array_flip($header);
        }

    }
    fclose($handle);
}
echo "<td>Found new: ".$new.", old: ".$old."</tr>\n</table>\n";

if (sizeof($found) > 0)
{
	echo "<h2>New files:</h2>";
}

foreach ($found as $row)
{
	echo "<a href='".$row[1]."'>".$row[0]."</a><br/>\n";
}

echo "<br/>\n";

?>
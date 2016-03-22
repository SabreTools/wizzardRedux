<?php

/*------------------------------------------------------------------------------------
 House all miscellaneous helper functions
 Original code by Matt Nadareski (darksabre76)
 -----------------------------------------------------------------------------------*/

/**
 * Get a web file (HTML, XHTML, XML, image, etc.) from a URL.  Return an
 * array containing the HTTP server response header fields and content.
 * 
 * @link http://nadeausoftware.com/articles/2007/06/php_tip_how_get_web_page_using_curl
 * @link http://stackoverflow.com/questions/4372710/php-curl-https
 * 
 * @param $url
 */
function get_data($url)
{
	$options = array(
			CURLOPT_RETURNTRANSFER => true,     // return web page
			CURLOPT_HEADER         => false,    // don't return headers
			CURLOPT_FOLLOWLOCATION => true,     // follow redirects
			CURLOPT_ENCODING       => "",       // handle all encodings
			CURLOPT_USERAGENT      => "spider", // who am i
			CURLOPT_AUTOREFERER    => true,     // set referer on redirect
			CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
			CURLOPT_TIMEOUT        => 120,      // timeout on response
			CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
			CURLOPT_SSL_VERIFYPEER => false     // Disabled SSL Cert checks
	);

	$ch      = curl_init( $url );
	curl_setopt_array( $ch, $options );
	$content = curl_exec( $ch );
	$err     = curl_errno( $ch );
	$errmsg  = curl_error( $ch );
	$header  = curl_getinfo( $ch );
	curl_close( $ch );

	return $content;
}

// Convert an old-style DAT to XML
function rv2xml ($file)
{
	ob_end_clean();
	ini_set('max_execution_time', 0); // Set the execution time to infinite. This is a bad idea in production.
	
	// Set the complex regex patterns
	$headerPattern = "/(^.*?) \($/";
	$romPattern = "/^\s+((?:rom)|(?:disk)) \( (name) \"(.*?)\" (?:(size) (.*?) )?(?:(crc) (.*?))?(?:(md5) (.*?) )?(?:(sha1) (.*?) )?\)/";
	$itemPattern = "/^\s+(.*?) \"(.*?)\"/";
	$endPattern = "/^\s*\)\s*$/";
	
	// Create the XMLWriter
	$xmlw = new XMLWriter();
	$xmlw->openMemory();
	$xmlw->startElement("datafile");

	$block = false;
	$parent = false;
	for ($k = 0; k < sizeof($file); $k++)
	{
		$line = $file[$k];

		// If the line is the header or a game
		if (preg_match($headerPattern, $line, $matches) != 0)
		{
			if ($matches[1] == "clrmamepro" || matches[1] == "romvault")
			{
				$xmlw->startElement("header");
				$parent = true;
			}
			else
			{
				$xmlw->startElement($matches[1]);
			}
				
			$block = true;
		}

		// If the line is a rom or disk and we're in a block
		elseif (preg_match($romPattern, $line, $matches) != 0 && $block)
		{
			$xmlw->startElement($matches[1]);

			// Loop over all attributes and add them if possible
			for ($i = 1; $i < sizeof($matches); $i++)
			{
				if ($i + 2 < sizeof($matches))
				{
					$xmlw->writeAttribute($matches[$i + 1], $matches[$i + 2]);
					$i++;
				}
			}

			$xmlw->endElement();
		}
		// If the line is anything but a rom or disk  and we're in a block
		elseif (preg_match($itemPattern, $line, $matches) != 0 && $block)
		{
			if ($matches[1] == "name" && $header)
			{
				$xmlw->writeAttribute($matches[1], $matches[2]);
				$xmlw->writeElement("description", $matches[2]);
			}
			else
			{
				$xmlw->writeElement($matches[1], $matches[2]);
			}
		}

		// If we find an end bracket that's not associated with anything else, the block is done
		elseif (preg_match($endPattern, $line) != 0 && $block)
		{
			$block = false;
			$xmlw->endElement();
		}
	}
	$xmlw->endElement();
	
	echo $xmlw->outputMemory();
	//return $xmlw->outputMemory();
}

?>
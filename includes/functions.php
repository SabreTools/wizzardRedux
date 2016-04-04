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
	//ob_end_clean();
	ini_set('max_execution_time', 0); // Set the execution time to infinite. This is a bad idea in production.
	
	// Set the complex regex patterns
	$headerPattern = @"/(.*?) \($/m";
	$itemPattern = @"/^(.*?) (.*)/";
	$endPattern = @"/^\)\s*$/";
	
	// Create the XMLWriter
	$xmlw = new XMLWriter;
	$xmlw->openMemory();
	$xmlw->setIndent(true);
	$xmlw->setIndentString("\t");
	$xmlw->startDocument();
	$xmlw->startElement("datafile");

	$block = false; $header = false;
	for ($k = 0; $k < sizeof($file); $k++)
	{
		$line = trim($file[$k]);
		
		// If the line is the header or a game
		if (preg_match($headerPattern, $line, $matches) !== 0)
		{
			if ($matches[1] == "clrmamepro" || $matches[1] == "romvault")
			{
				$xmlw->startElement("header");
				$header = true;
			}
			else
			{
				$xmlw->startElement($matches[1]);
			}
				
			$block = true;
		}

		// If the line is a rom or disk and we're in a block
		elseif ((strpos(trim($line), "rom (") === 0 || strpos(trim($line), "disk (") === 0) && $block)
		{
			$line = explode(" ", $line);
			
			$xmlw->startElement($line[0]);

			// Loop over all attributes and add them if possible
			$quote = false; $attrib = ""; $val = "";
			for ($i = 2; $i < sizeof($line); $i++)
			{
				// Get the number of quotes
				preg_match_all(@"/\"/", $line[$i], $quotes);
				$quotes = sizeof($quotes[0]);
				
				// Even number of quotes, not in a quote, not in attribute
				if ($quotes % 2 == 0 && !$quote && $attrib == "")
				{
					$attrib = str_replace("\"", "", $line[$i]);
				}
				// Even number of quotes, not in a quote, in attribute
				elseif ($quotes % 2 == 0 && !$quote && $attrib != "")
				{
					$xmlw->writeAttribute($attrib, str_replace("\"", "", $line[$i]));
				
					$attrib = "";
				}
				// Even number of quotes, in a quote, not in attribute
				elseif ($quotes % 2 == 0 && $quote && $attrib == "")
				{
					// Attributes can't have quoted names
				}
				// Even number of quotes, in a quote, in attribute
				elseif ($quotes % 2 == 0 && $quote && $attrib != "")
				{
					$val .= " ".$line[$i];
				}
				// Odd number of quotes, not in a quote, not in attribute
				elseif ($quotes % 2 == 1 && !$quote && $attrib == "")
				{
					// Attributes can't have quoted names
				}
				// Odd number of quotes, not in a quote, in attribute
				elseif ($quotes % 2 == 1 && !$quote && $attrib != "")
				{
					$val = str_replace("\"", "", $line[$i]);
					$quote = true;
				}
				// Odd number of quotes, in a quote, not in attribute
				elseif ($quotes % 2 == 1 && $quote && $attrib == "")
				{
					$quote = false;
				}
				// Odd number of quotes, in a quote, in attribute
				elseif ($quotes % 2 == 1 && $quote && $attrib != "")
				{
					$val .= " ".str_replace("\"", "", $line[$i]);
					$xmlw->writeAttribute($attrib, $val);
				
					$quote = false;
					$attrib = "";
					$val = "";
				}
			}

			$xmlw->endElement();
		}
		// If the line is anything but a rom or disk  and we're in a block
		elseif (preg_match($itemPattern, $line, $matches) !== 0 && $block)
		{
			$matches[2] = str_replace("\"", "", $matches[2]);
			
			if ($matches[1] == "name" && !$header)
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
		elseif (preg_match($endPattern, $line) !== 0 && $block)
		{
			$block = false;
			$xmlw->endElement();
			$header = false;
		}
		
		// Somehow didn't match anything
		else 
		{
			//echo "<pre>".$line."</pre><br/>\n";
		}
	}
	$xmlw->endElement();
	$xmlw->endDocument();
	
	return $xmlw->outputMemory();
}

?>
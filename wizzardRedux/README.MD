<h2>Wizard of DATz Redux</h2>

<h3>Introduction</h3>

This project is the continuation of the work done by RomShepherd board member Wizard of DATz to create a series of custom DAT files for various websites and systems, and to create merged DATs that remove redundant ROMs from similar DATs.

This is in the severely early stages since it is a complete rewrite of the internal tool that WoD originally used.

Any suggestions are accepted.

<b>Note:</b> Because of the complexity of the code so far, this is a one-man project. No pull requests will be accepted for the time being.

<h3>DAT File Formats</h3>

<b>Old Format</b>

<pre>
clrmamepro (
	name "DATNAME"
	description "DESCRIPTION"
	version "VERSION"
	comment "COMMENT"
	author "The Wizard of DATz"
)

game (
	name "NAME"
	rom ( name "ROMNAME" size SIZE crc CRC md5 MD5 sha1 SHA1 )
	disk ( name "DISKNAME" sha1 SHA1 )
)
</pre>

<b>Modern Format</b>

<pre>
&lt;?xml version="1.0" encoding="UTF-8"?&gt;
&lt;!DOCTYPE datafile PUBLIC "-//Logiqx//DTD ROM Management Datafile//EN" "http://www.logiqx.com/Dats/datafile.dtd"&gt;

&lt;datafile&gt;
	&lt;header&gt;
		&lt;name&gt;DATNAME&lt;/name&gt;
		&lt;description&gt;DESCRIPTION&lt;/description&gt;
		&lt;category&gt;CATEGORY&lt;/category&gt;
		&lt;version&gt;VERSION&lt;/version&gt;
		&lt;date&gt;DATE&lt;/date&gt;
		&lt;author&gt;The Wizard of DATz&lt;/author&gt;
		&lt;email&gt;EMAIL&lt;/email&gt;
		&lt;homepage&gt;HOMEPAGE&lt;/homepage&gt;
		&lt;url&gt;URL&lt;/url&gt;
		&lt;comment&gt;COMMENT&lt;/comment&gt;
		&lt;clrmamepro/&gt;
	&lt;/header&gt;
	&lt;machine name="NAME" romof="PARENT"&gt;
		&lt;description&gt;ROMDESC&lt;/description&gt;
		&lt;rom name="ROMNAME" size="SIZE" crc="CRC" md5="MD5" sha1="SHA1"/&gt;
		&lt;disk name="DISKNAME" md5="MD5" sha1="SHA1" /&gt;
	&lt;/machine&gt;
&lt;/datafile&gt;
</pre>
 
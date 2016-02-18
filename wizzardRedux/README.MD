<h2>Wizard of DATz Redux</h2>

<h3>Introduction</h3>

This project is the continuation of the work done by RomShepherd board member Wizard of DATz to create a series of custom DAT files for various websites and systems, and to create merged DATs that remove redundant ROMs from similar DATs.

This is in the severely early stages since it is a complete rewrite of the internal tool that WoD originally used.

Any suggestions are accepted.

<b>Note:</b> Because of the complexity of the code so far, this is a one-man project. No pull requests will be accepted for the time being.

<h3>DAT File Formats</h3>

<b>Old Format</b>

<pre>
clrmamepro (<br/>
	name "DATNAME"<br/>
	description "DESCRIPTION"<br/>
	version "VERSION"<br/>
	comment "COMMENT"<br/>
	author "The Wizard of DATz"<br/>
)

game (<br/>
	name "NAME"<br/>
	rom ( name "ROMNAME" size SIZE crc CRC md5 MD5 sha1 SHA1 )<br/>
	disk ( name "DISKNAME" sha1 SHA1 )<br/>
)
</pre>

<b>Modern Format</b>

<pre>
&lt;?xml version="1.0" encoding="UTF-8"?&gt;<br/>
&lt;!DOCTYPE datafile PUBLIC "-//Logiqx//DTD ROM Management Datafile//EN" "http://www.logiqx.com/Dats/datafile.dtd"&gt;<br/>
<br/>
&lt;datafile&gt;<br/>
	&lt;header&gt;<br/>
		&lt;name&gt;DATNAME&lt;/name&gt;<br/>
		&lt;description&gt;DESCRIPTION&lt;/description&gt;<br/>
		&lt;category&gt;CATEGORY&lt;/category&gt;<br/>
		&lt;version&gt;VERSION&lt;/version&gt;<br/>
		&lt;date&gt;DATE&lt;/date&gt;<br/>
		&lt;author&gt;The Wizard of DATz&lt;/author&gt;<br/>
		&lt;email&gt;EMAIL&lt;/email&gt;<br/>
		&lt;homepage&gt;HOMEPAGE&lt;/homepage&gt;<br/>
		&lt;url&gt;URL&lt;/url&gt;<br/>
		&lt;comment&gt;COMMENT&lt;/comment&gt;<br/>
		&lt;clrmamepro/&gt;<br/>
	&lt;/header&gt;
	&lt;machine name="NAME" romof="PARENT"&gt;<br/>
		&lt;description&gt;ROMDESC&lt;/description&gt;<br/>
		&lt;rom name="ROMNAME" size="SIZE" crc="CRC" md5="MD5" sha1="SHA1"/&gt;<br/>
		&lt;disk name="DISKNAME" md5="MD5" sha1="SHA1" /&gt;<br/>
	&lt;/machine&gt;<br/>
&lt;/datafile&gt;<br/>
</pre>
 
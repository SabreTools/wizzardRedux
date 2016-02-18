<h2>Wizard of DATz Redux</h2>

<h3>Introduction</h3>

This project is the continuation of the work done by RomShepherd board member Wizard of DATz to create a series of custom DAT files for various websites and systems, and to create merged DATs that remove redundant ROMs from similar DATs.

This is in the severely early stages since it is a complete rewrite of the internal tool that WoD originally used.

Any suggestions are accepted.

<b>Note:</b> Because of the complexity of the code so far, this is a one-man project. No pull requests will be accepted for the time being.

<h3>DAT File Formats</h3>

<b>Old Format</b>

clrmamepro (<br/>
&nbsp;&nbsp;&nbsp;&nbsp;name "DATNAME"<br/>
&nbsp;&nbsp;&nbsp;&nbsp;description "DESCRIPTION"<br/>
&nbsp;&nbsp;&nbsp;&nbsp;version "VERSION"<br/>
&nbsp;&nbsp;&nbsp;&nbsp;comment "COMMENT"<br/>
&nbsp;&nbsp;&nbsp;&nbsp;author "The Wizard of DATz"<br/>
)

game (<br/>
&nbsp;&nbsp;&nbsp;&nbsp;name "NAME"<br/>
&nbsp;&nbsp;&nbsp;&nbsp;rom ( name "ROMNAME" size SIZE crc CRC md5 MD5 sha1 SHA1 )<br/>
&nbsp;&nbsp;&nbsp;&nbsp;disk ( name "DISKNAME" sha1 SHA1 )<br/>
)

<b>Modern Format</b>

&lt;?xml version="1.0" encoding="UTF-8"?&gt;<br/>
&lt;!DOCTYPE datafile PUBLIC "-//Logiqx//DTD ROM Management Datafile//EN" "http://www.logiqx.com/Dats/datafile.dtd"&gt;<br/>
<br/>
&lt;datafile&gt;<br/>
&nbsp;&nbsp;&nbsp;&nbsp;&lt;header&gt;<br/>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;name&gt;DATNAME&lt;/name&gt;<br/>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;description&gt;DESCRIPTION&lt;/description&gt;<br/>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;category&gt;CATEGORY&lt;/category&gt;<br/>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;version&gt;VERSION&lt;/version&gt;<br/>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;date&gt;DATE&lt;/date&gt;<br/>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;author&gt;The Wizard of DATz&lt;/author&gt;<br/>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;email&gt;EMAIL&lt;/email&gt;<br/>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;homepage&gt;HOMEPAGE&lt;/homepage&gt;<br/>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;url&gt;URL&lt;/url&gt;<br/>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;comment&gt;COMMENT&lt;/comment&gt;<br/>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;clrmamepro/&gt;<br/>
&nbsp;&nbsp;&nbsp;&nbsp;&lt;/header&gt;
&nbsp;&nbsp;&nbsp;&nbsp;&lt;machine name="NAME" romof="PARENT"&gt;<br/>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;description&gt;ROMDESC&lt;/description&gt;<br/>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;rom name="ROMNAME" size="SIZE" crc="CRC" md5="MD5" sha1="SHA1"/&gt;<br/>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;disk name="DISKNAME" md5="MD5" sha1="SHA1" /&gt;<br/>
&nbsp;&nbsp;&nbsp;&nbsp;&lt;/machine&gt;<br/>
&lt;/datafile&gt;<br/>
 
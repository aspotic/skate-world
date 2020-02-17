<?



/*
	XMBSource Chat System 1.0 Final
	© 2003 - 2004 The XMBSource Group
	Based On: XMB Livechat by KOD
	New Code Design By: John Briggs & Eagle1
	http://www.xmbsource.co.uk
	http://www.nc-soccer.net

	Posting or distributing this hack is not permitted
	Do not remove this copyright information
*/

include "header.php";
$username = $xmbuser;
smcwcache();
// BEGIN FIXED COLORS ////////////////////////////////////////////////////////
$chathelp = "#f4d6d2";			// Chat help color (/help)
$chatenterleave = "#0b9806";	// Enter/leave notification color
$chattime = "#075b8d";			// Chat time color
$chatposter = "#000000";		// Chat poster color
$chatemote = "#eec400";		// Chat emote color
$chatwhisper = "#09734e";	// Chat whisper color
// END FIXED COLORS //////////////////////////////////////////////////////////

if($page == "" && $username != ""){
?>
<html>
<head>
<title><?=$bbname?> - Chat</title>
</head>
<?
}else{
	if($page == "smilies"){
		$margin = 0;
	}else	{
		$margin = 3;
	}

?>
<html>
<head>

<script language="JavaScript" type="text/javascript">

</script>
<script language="javascript" type="text/javascript" src="./bbcode.js"></script>

<title><?=$bbname?> - Chat</title>
</head>
<body bgcolor="#9BAAB8" text="green" marginwidth="0" marginheight="0" leftmargin="<?=$margin?>" rightmargin="<?=$margin?>" topmargin="<?=$margin?>" bottommargin="<?=$margin?>" >
<?
}

$now = time();
$refreshtime2 = $now - 10;

if($username == ""){

?>
<table width=100% bgcolor="#9BAAB8" border=0><td align="center">
<br><br><br><br>
<span class="mediumtxt ">Anonymous chatting is not enabled for this forum, you will have to <a href="member.php?action=reg">register</a> to post in this chatroom.</span>
</td></table>
<?

/**************************************************************/
}elseif($page == "" && $username != "") {
/**************************************************************/

	$db->query("CREATE TABLE IF NOT EXISTS xmb_chat(type varchar(25) NOT NULL default '', datetime bigint(15) NOT NULL default '0', jointime bigint(15) NOT NULL default '0', user varchar(50) NOT NULL default '', body text NOT NULL) TYPE=MyISAM"); // create table if it does not exist

?>
<frameset rows="*,70,90,1" frameborder="0" border="0" framespacing="0">
<frameset cols="*,150">
	<frame name="messages" src="chat.php?page=messages" NORESIZE scrolling="yes">
	<frame name="users" src="chat.php?page=users" NORESIZE scrolling="yes">
</frameset>

<frame name="smilies"  height="150"src="chat.php?page=smilies" scrolling="auto">

<frameset cols="*,10">
	<frame name="inputtext" src="chat.php?page=inputtext" NORESIZE scrolling="no">
	<frame name="inputadd" src="chat.php?page=inputadd" NORESIZE scrolling="no">
</frameset>

<frame name="refresher" src="chat.php?page=refresh&refreshtime=$refreshtime2" NORESIZE scrolling="no">

</frameset>
<?

/**************************************************************/
}elseif($page == "smilies") {
/**************************************************************/

?>
<script language="javascript">
function moresmilies(theicon){
	top.inputtext.input.message.value += theicon;
}
</script>
<table bgcolor="#9BAAB8" border="0" cellspacing="0" cellpadding="2" width="40%" align="center"><tr>
<?

$querysmilie = $db->query("SELECT * FROM $table_smilies WHERE type='smiley'");
$i = 0;
$j = 0;
while($smilie = $db->fetch_array($querysmilie)){
	$smilies .= "<td class=\"tablerow\" bgcolor=\"#9BAAB8\" align=\"center\" valign=\"center\"><a href=\"#\" onClick=\"javascript:moresmilies('$smilie[code]');\"><img src=\"$smdir/$smilie[url]\" border=\"0\"></a></td>";
	$i++;
	$j++;
	if($i == $smcols){
		$smilies .= "</tr><tr>";
		$i = 0;
	}
	if($j == $smtotal){
		break;
	}
}

echo "$smilies</tr></table>";

/**************************************************************/
}elseif($page == "inputtext") {
/**************************************************************/

?>
<?=$css?>
<script language="javascript" type="text/javascript" src="./bbcode.js">
</script>
<script language="javascript">
a = 0;

function validate(f){
	if(f.elements["message"].value == ""){
		f.elements["message"].focus();
		alert("Please enter a message.")
		return false;
	}

	if(f.elements["message"].value == "/help" || f.elements["message"].value == "/HELP" || f.elements["message"].value == "/Help"){
		top.messages.document.write('<br><font color="<?=$chathelp?>">Here are the current commands:<br>/m <b>user</b> <b>message</b> - Sends a private <b>message</b> to <b>user</b><br>/me <b>action</b> - Emote, for instance "/me waves" would show up as *name waves*.</font><br><br>');
		parent.messages.scrollTo(0,500000);
		document.input.message.value="";
		document.input.message.focus();
		return false;
	}

	self.setTimeout("clrinp()",10);
	return true;
}

</script>
<form name="input" action="chat.php" onsubmit="return validate(this)" target="inputadd">
<table bgcolor="#9BAAB8" width="100%" border="0" cellspacing="0" cellpadding="3">
<tr>
<td width="1%" valign="top" nowrap>
&nbsp;<select name="font" onChange="chfont(this.options[this.selectedIndex].value)" size="1" onMouseOver="tipline('font')">
   <option value="Arial Narrow" selected>Arial Narrow</option>
  <option value="Andale Mono">Andale Mono</option>
  <option value="Arial">Arial</option>
  <option value="Arial Black">Arial Black</option>
  <option value="Book Antiqua">Book Antiqua</option>
  <option value="Century Gothic">Century Gothic</option>
  <option value="Comic Sans MS">Comic Sans MS</option>
  <option value="Courier New">Courier New</option>
  <option value="Georgia">Georgia</option>
  <option value="Impact">Impact</option>
  <option value="Tahoma">Tahoma</option>
  <option value="Times New Roman">Times New Roman</option>
  <option value="Trebuchet MS">Trebuchet MS</option>
  <option value="Script MT Bold">Script MT Bold</option>
  <option value="Stencil">Stencil</option>
  <option value="Verdana">Verdana</option>
  <option value="Lucida Console">Lucida Console</option>
</select>
</td>
<td width="1%" valign="top" nowrap>
<select name="color" onChange="chcolor(this.options[this.selectedIndex].value)" size="1" onMouseOver="tipline('color')">
  <option value="Black" style="color:black;">Black</option>
  <option value="White" style="color:white;">White</option>
  <option value="Red" style="color:red;">Red</option>
  <option value="Yellow" style="color:yellow;">Yellow</option>
  <option value="Pink" style="color:pink;">Pink</option>
  <option value="Green" style="color:green;">Green</option>
  <option value="Orange" style="color:orange;">Orange</option>
  <option value="Purple" style="color:purple;">Purple</option>
  <option value="Blue" style="color:blue;">Blue</option>
  <option value="Beige" style="color:beige;">Beige</option>
  <option value="Brown" style="color:brown;">Brown</option>
  <option value="Teal" style="color:teal;">Teal</option>
  <option value="Navy" style="color:navy;">Navy</option>
  <option value="Maroon" style="color:maroon;">Maroon</option>
  <option value="LimeGreen" style="color:limegreen;">LimeGreen</option>
  </select>
</td>
<td valign="top">
<a href="javascript:bold()" onMouseOver="tipline('bold')">
<img src="<?=$imgdir?>/bb_bold.gif" border="0" width="23" height="22" alt="$lang_cb_insert_bold"></a>
<a href="javascript:italicize()" onMouseOver="tipline('italic')">
<img src="<?=$imgdir?>/bb_italicize.gif" border="0" width="23" height="22" alt="<?=$lang_cb_insert_italics?>"></a>
<a href="javascript:underline()" onMouseOver="tipline('underline')">
<img src="<?=$imgdir?>/bb_underline.gif" border="0" width="23" height="22" alt="<?=$lang_cb_insert_underlined?>"></a>
<a href="javascript:center()" onMouseOver="tipline('center')">
<img src="<?=$imgdir?>/bb_center.gif" border="0" width="23" height="22" alt="<?=$lang_cb_insert_centered?>"></a>
<a href="javascript:hyperlink()" onMouseOver="tipline('url')">
<img src="<?=$imgdir?>/bb_url.gif" border="0" width="23" height="22" alt="<?=$lang_cb_insert_hyperlink?>"></a>
<a href="javascript:email()" onMouseOver="tipline('email')">
<img src="<?=$imgdir?>/bb_email.gif" border="0" width="23" height="22" alt="<?=$lang_cb_insert_email?>"></a>
</td>
</tr>

<tr>
<td colspan="4">
<table bgcolor="#9BAAB8" border="0" cellspacing="0" cellpadding="3">
<td>

<input type="hidden" name="page" value="inputadd">
<input type="hidden" name="username" value="$username">
<input type="text" autocomplete="OFF" size="75" maxlength="250" name="message" onselect="storeCaret(this);" onclick="storeCaret(this);" onkeyup="storeCaret(this);">
&nbsp;
<input type="submit" value="Say!">
</td>
</table>
</td>
</tr>
</table>
</form>
<script language="javascript">
document.input.message.focus();
top.messages.document.write('<html><head>');
<?

$printcss = explode("\n",$css);

foreach($printcss as $thecss){
	$thecss = str_replace("'","\\'",$thecss);
	$thecss = str_replace("\"","\\\"",$thecss);
	$thecss = str_replace("\r","",$thecss);
	$thecss = str_replace("\n","",$thecss);
	echo "top.messages.document.write('$thecss');";
}

?>
top.messages.document.write('</head><body bgcolor="#697886" text="#FFFFFF" marginwidth=0 marginheight=0 leftmargin=3 rightmargin=3 topmargin=3 bottommargin=3>');
top.messages.document.write('<font class="post" color="<?=$chathelp?>" >Welcome to the chatroom! Type /help for commands.<br><br><?=$bbname?> Chat Loaded...</font><br><br>');
function clrinp(){
	document.input.message.value="";
	document.input.message.focus();
}
</script>
<?

/**************************************************************/
}elseif($page == "inputadd"){
/**************************************************************/

	if($message != ""){
		$now = time() + ($timeoffset * 3600);
		$standardtime = gmdate("g:i:s A", $now);
		$poster = $username;
		$now2 = time();
		$c1 = "";
		$message=addslashes($message);
		$db->query("INSERT INTO xmb_chat VALUES('message',$now2, 0, '$poster', '$message')");
		
		$message = fixmsg($message);

		$isemote = substr($message,0,4);
		$isprivmess = substr($message,0,3);

		if($isemote == "/me "){  // emote something
			$message = substr($message,4,strlen($message)-4);
			$c1 = "*";
			$message = "$message$ch";
?>
<script language="javascript">
top.messages.document.write("<font class='post'> <font color='<?=$chatemote?>'>*<?=$poster?> ");
<?
		}elseif($isprivmess == "/m ") {  // message someone
			$message = substr($message,3,strlen($message)-3);
			$isinquote = substr($message,0,2);

			if($isinquote == "\'"){   // /m 'space name' message
				$message = substr($message,2,strlen($message)-2);
				list ($usertomessage, $ignoreme) = split("\'",$message);
				$message = substr($message,strlen($usertomessage)+2,strlen($message)-strlen($usertomessage)-2);
			}else{
				list ($usertomessage, $ignoreme) = split(" ",$message);

				$message = substr($message,strlen($usertomessage)+1,strlen($message)-strlen($usertomessage)-1);
			}
?>
<script language="javascript">
top.messages.document.write("<font class='post'> <font color='<?=$chatwhisper?>'>You whispered to <?=$usertomessage?>: ");
<?
		}else{
?>
<script language="javascript">
top.messages.document.write("<font class='post'> <font color='<?=$chatposter?>'><?=$poster?></font>: ");
<?
		}
		
?>		
top.messages.document.write("<?=$message?>");
top.messages.document.write("<?=$c1?></font></b></b></i></i></font></font></u></u></table></table><br>");
parent.messages.scrollTo(0,500000);
</script>
<?
	}

/**************************************************************/
}elseif($page == "refresh"){
/**************************************************************/

	$now2 = time();
	$now3 = time() - 60;

	$db->query("DELETE FROM xmb_chat WHERE datetime<='$now3'");
	echo "<script language=\"Javascript\">\n";

	$youruser = $db->query("SELECT * FROM xmb_chat WHERE type='user' AND user='$username' ORDER BY datetime DESC");
	$userinfo = $db->fetch_array($youruser);
	if($userinfo['jointime'] != "") {
		$thejointime = $userinfo['jointime'];
		$db->query("INSERT INTO xmb_chat VALUES('user',$now2, $thejointime, '$username', '')");
		$db->query("DELETE FROM xmb_chat WHERE type='user' AND user='$username' and datetime!=$now2");
	}else{
		$db->query("INSERT INTO xmb_chat VALUES('message',$now2, 1, '$username', '[color=$chatenterleave]$username has joined the channel[/color]')");
		$db->query("INSERT INTO xmb_chat VALUES('user',$now2, $now2, '$username', '')");
	}

	$oldtime = time() - 15;
	$oldusers = $db->query("SELECT * FROM xmb_chat WHERE type='user' AND datetime<$oldtime AND jointime>0 ORDER BY datetime DESC");
	while($olduserlist = $db->fetch_array($oldusers))	{
		$oldusername = $olduserlist['user'];
		$db->query("DELETE FROM xmb_chat WHERE type='user' AND user='$oldusername' AND datetime!=$now2");
		$db->query("INSERT INTO xmb_chat VALUES('message',$now2, 1, '$oldusername', '[color=$chatenterleave]$oldusername has left the channel[/color]')");
	}

	$refreshtime3 = time();
	$newmessages = $db->query("SELECT * FROM xmb_chat WHERE type='message' AND datetime>='$refreshtime' AND datetime<'$refreshtime3' AND user!='$username' ORDER BY datetime ASC");
	while($messages = $db->fetch_array($newmessages)) {
		$timepostedsec = $messages['datetime'];
		$timepostedstandard = gmdate("g:i:s A",$timepostedsec + ($timeoffset * 3600));
		$poster = $messages['user'];

		$noscroll = 0;
		$messagetext = fixmsg($messages['body']);

		$isemote = substr($messagetext,0,4);
		$isprivmess = substr($messagetext,0,3);

		if($messages['jointime'] == 1) {
			echo "top.messages.document.write(\"(<font class='post'><font color='$chattime'>$timepostedstandard</font>) $messagetext</b></b></i></i></font></font></u></u></table></table><br>\");\n";
			echo "top.users.location.reload();\n";
		}elseif($isemote == "/me ") {
			$messagetext = substr($messagetext,4,strlen($messagetext) -4);

			echo "top.messages.document.write(\"<font class='post'> <font color='$chatemote'>*$poster $messagetext*</font></b></b></i></i></font></font></u></u></table></table><br>\");\n";
		}elseif($isprivmess == "/m ") {
			$messagetext = substr($messagetext,3,strlen($messagetext) -3);
			$isinquote = substr($messagetext,0,2);

			if($isinquote == "\'") {
				$messagetext = substr($messagetext,2,strlen($messagetext)-2);
				list ($usertomessage, $ignoreme) = split("\'",$messagetext);

				$messagetext = substr($messagetext,strlen($usertomessage) +2, strlen($messagetext) - strlen($usertomessage) -2 );
			}else{
				list ($usertomessage, $ignoreme) = split(" ",$messagetext);
				$messagetext = substr($messagetext,strlen($usertomessage) +1, strlen($messagetext) - strlen($usertomessage) -1 );
			}

			if($usertomessage == $username){
				echo "top.messages.document.write(\"<font class='post'> <font color='$chatwhisper'>$poster whispered to you: $messagetext</b></b></i></i></font></font></u></u></table></table><br>\");\n";
			}else{
				$noscroll = 1;
			}
		}else{
			echo "top.messages.document.write(\"<font class='post'> <font color='$chatposter'>$poster</font>: $messagetext</b></b></i></i></font></font></u></u></table></table><br>\");\n";
		}

		if($noscroll == 0){
			echo "parent.messages.scrollTo(0,500000);";
		}
	}

	$refreshtime2 = time();

?>
self.setTimeout("refr()",5000);
function refr(){
		self.location.href = "chat.php?page=refresh&refreshtime=<?=$refreshtime2?>";
}

</script>
<?

/**************************************************************/
}elseif($page == "users"){
/**************************************************************/

	echo "<font color=\"#731109\" class=\"post\">\n";
	$shown = 0;
	$mintime = time() - 5;
	$userlist = $db->query("SELECT * FROM xmb_chat WHERE type='user' ORDER BY jointime ASC");
	while($users = $db->fetch_array($userlist)){
		$usertime = $users['jointime'];
		$onlinename = $users['user'];

		$p1 = "";
		$p2 = "";
		if($onlinename == $username){
			$shown = 1;
			$p1 = "<b>";
			$p2 = "</b>";
		}

		echo "<a href=\"member.php?action=viewpro&member=$onlinename\" target=\"viewuser\"><font color=\"#731109\">$p1$onlinename$p2</font></a><br>\n";
	}

	if($shown == 0){
		echo "<a href=\"member.php?action=viewpro&member=$username\" target=\"viewuser\"><font color=\"#731109\"><b>$username</b></font></a><br>\n";
	}

	echo "</font>";
?>
<script language="javascript">
self.setTimeout("user()",5000);
function user(){
		self.location.href = "chat.php?page=users";
}

</script>


<?

/**************************************************************/
}

function fixmsg($message){
	global $table_smilies, $imgdir, $smdir, $smiliecache, $censorcache, $smiliesnum, $wordsnum;

	
	reset($smiliecache);
	while(list($code, $url) = each($smiliecache)) {
		$message = str_replace($code, "<img src=\"$smdir/$url\" align=\"absmiddle\" border=0>",$message);
	}
	
	$message = stripslashes($message);
	$message = str_replace("[b]", "<b>", $message);
	$message = str_replace("[/b]", "</b>", $message);
	$message = str_replace("[i]", "<i>", $message);
	$message = str_replace("[/i]", "</i>", $message);
	$message = str_replace("[u]", "<u>", $message);
	$message = str_replace("[/u]", "</u>", $message);
	$message = str_replace("[strike]", "<strike>", $message);
	$message = str_replace("[/strike]", "</strike>", $message);
	$message = str_replace("<br />"," <br />",$message); 
	$message = eregi_replace("(^|[>[:space:]\n])([[:alnum:]]+)://([^[:space:]]*)([[:alnum:]#?/&=])([<[:space:]\n]|$)","\\1<a href=\"\\2://\\3\\4\" target=\"_blank\">\\2://\\3\\4</a>\\5", $message);
	$message = eregi_replace("\\[color=([^\\[]*)\\]([^\\[]*)\\[/color\\]","<font color=\"\\1\">\\2</font>",$message);
	$message = eregi_replace("\\[size=([^\\[]*)\\]([^\\[]*)\\[/size\\]","<font size=\"\\1\">\\2</font>",$message);
	$message = eregi_replace("\\[font=([^\\[]*)\\]([^\\[]*)\\[/font\\]","<font face=\"\\1\">\\2</font>",$message);
	$message = eregi_replace("\\[align=([^\\[]*)\\]([^\\[]*)\\[/align\\]","<p align=\"\\1\">\\2</p>",$message);
	
	$patterns = array();
	$replacements = array();

	$patterns[0] = "#\[url\]([a-z]+?://){1}(.*?)\[/url\]#si";
	$replacements[0] = '<a href= "\1\2" target="_blank">\1\2</a>';
	
	$patterns[1] = "#\[url\](.*?)\[/url\]#si";
	$replacements[1] = '<a href= "http://\1" target="_blank">\1</a>';
	
	$patterns[2] = "#\[url=([a-z]+?://){1}(.*?)\](.*?)\[/url\]#si";
	$replacements[2] = '<a href= "\1\2" target="_blank">\3</a>';
	
	$patterns[3] = "#\[url=(.*?)\](.*?)\[/url\]#si";
	$replacements[3] = '<a href= "http://\1" target="_blank">\2</a>';
	
	$patterns[4] = "#\[email\](.*?)\[/email\]#si";
	$replacements[4] = '<a href="mailto:\1"> \1</a>';
	
	$patterns[5] = "#\[email=(.*?){1}(.*?)\](.*?)\[/email\]#si";
	$replacements[5] = '<a href="mailto:\1\2"> \3</a>';
	
	$message = preg_replace($patterns, $replacements, $message);
	$message = eregi_replace("javascript", "java_script[Not active]", $message);

	$message = str_replace("&","&amp;",$message);
	$message = str_replace("\"", "'", $message);
	$alen = strlen($message);
	$lastspace = 0;

	for($a=0;$a<$alen;$a++){
		$achar = substr($message,$a,1);
		if($achar == " " || $achar == "-"){
			$lastspace = $a;
		}
		$spacebetween = $a - $lastspace;
		if($spacebetween >= 40){
			$lastspace = $a;
			$m1 = substr($message,0,$a);
			$m2 = substr($message,$a,strlen($message)- $a);
			$message = "$m1 $m2";
		}
	}

	return $message;
}

echo "</html>";
?>
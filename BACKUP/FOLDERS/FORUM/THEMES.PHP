<?

/*
	-----------------------------------------------------
	Themes
	©2002 FunForum
	Developed by FunForum
	Proud member of :: http://www.computerelated.com
	Formalized by ArXySe
	Proud owner of :: http://www.shadelinl.net

	Keep all copyright links on the script visible
  	-----------------------------------------------------
						version 1.0.4
*/

// Get global stuff
	require "./header.php";

// Pre-load templates
	loadtemplates('header,footer');

// Create header and footer
	eval("\$header = \"".template("header")."\";");
	eval("\$footer = \"".template("footer")."\";");

// Check for login status
	if(!$xmbuser || $xmbuser == "") {
		echo $header;
		echo "<center><font class=\"mediumtxt\">$lang_welcomeunregnotify</font></center>";
		echo $footer;
		exit;
	}

// Switch actions...
	switch($action){
		case 'help':
		echo $header;
		if($status != "Administrator" && $status !="Super Administrator") {
			echo("<br><br><center><font class=\"mediumtxt\">Only Admins allowed here...</font></center>");
			end_time();
			echo $footer;
			exit();
		}
		?>
		<table border="0" cellspacing="0" cellpadding="0" width="<?=$tablewidth?>" bgcolor="<?=$bordercolor?>" align="center"><tr><td>
		<table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">
		    <tr>
		        <td class="category" <?=$catbgcode?>> <font color="<?=$cattext?>"> <b>Help</b></font></td>
		    </tr>

		    <tr class="tablerow" bgcolor="<?=$altbg1?>">
		        <td align="center"> General Information </td>
		    </tr>
		    <tr class="mediumtxt" bgcolor="<?=$altbg2?>">
		        <td> This php file will go to your SQL database and look what themes are installed.<br>
		        Then it will try to find a few pictures in this folder :: /images/themes/<br>
		        There will be displayed a thumbnail (a small image), a link called "Preview" and a link called "Set This Theme".<BR>
		        The "Preview" link will point to the bigger image, so people can see how the theme will look like.<br>
		        The link "Set This Theme" will set this theme as your default theme. </td>
		    </tr>

		    <tr class="tablerow" bgcolor="<?=$altbg1?>">
		        <td align="center"> Version History </td>
		    </tr>
		    <tr class="mediumtxt" bgcolor="<?=$altbg2?>">
		        <td> This is version: 1.0.4.<br>
		        Changes in 1.0.4:<br>
		        - Small updates in coding to remove useless chunks and reduce filesize<br>
		        - Updated instructions for upgrading<br>
		        Changes in 1.0.3b:<br>
		        - Switched to JPG compatibility instead of PNG<br>
		        Changes in 1.0.3:<br>
		        - The theme selection redesigned<br>
		        - Designated area for themes link<br>
		        Changes in 1.0.2:<br>
		        - The Default Theme will now be shown at the top. (( As Requested by midmadn, on computerelated.com ))<br>
		        </td>
		    </tr>
		    <tr class="tablerow" bgcolor="<?=$altbg1?>">
		        <td align="center"> Installation; Clean Installation </td>
		    </tr>
		    <tr class="mediumtxt" bgcolor="<?=$altbg2?>">
		        <td> Open your language file; <br>
			Find: <br>
			?&gt; <br>
			Add Above: <br>
			$lang_themes_set = "Set"; <br>
			$lang_themes_ttheme = "This Theme"; <br>
			$lang_themes_dtheme = "The Default Theme On This Board Is"; <br>
			<br>
			<br>
			Open memcp.php <br>
			Find: ( If you can find it two times, use the last reference )<br>
			if($action == &quot;profile&quot;) { <br>
			Add Above: <br>
			if ($action == &quot;theme&quot;) {<br>
			$db-&gt;query(&quot;UPDATE $table_members SET theme = '$newtheme' WHERE username = '$xmbuser'&quot;);<br>
			echo &quot;&lt;meta http-equiv=\&quot;refresh\&quot; content=\&quot;0; url=themes.php\&quot;&gt;&quot;;<br>
			exit;<br>
			}<br>
			<br>
			Find:<br>
			$lang_textbuddylist, $lang_helpbar;<br>
			Replace With:<br>
			$lang_textbuddylist, $lang_helpbar, $lang_themes;<br>
			<br>
			Find:<br>
			if($current == &quot;subscriptions&quot;) {<br>
			Add Above:<br>
			echo &quot;&lt;td bgcolor=\&quot;$altbg2\&quot;&gt;&lt;a href=\&quot;#\&quot; onclick=\&quot;Popup('themes.php', 'Window', 550, 450);\&quot;&gt;$lang_themes&lt;/a&gt;&lt;/td&gt;&quot;;<br>
			<br>
			<br>
			Open template memcp_profile<br>
			Remove:<br>
			&lt;tr&gt;<br>
			&lt;td bgcolor=&quot;$altbg1&quot; class=&quot;tablerow&quot;&gt;$lang_texttheme&lt;/td&gt;<br>
			&lt;td bgcolor=&quot;$altbg2&quot; class=&quot;tablerow&quot;&gt;$themelist &lt;/td&gt;<br>
			&lt;/tr&gt;<br>
			<br>
			<br>
			Create a folder called "themes" in the images folder (/images/themes/).<br>
			<br>
			<br>
		        And now the hard part: You'll need to upload images (screenshots) to the themes directory.<BR>
			I took screenshots of all my themes and uploaded them. <br>
			You can make a screenshot using the "Print" button on the keyboard, next to the F10-F11-F12. Then open Paint (standard in every Windows version) and "Paste" (CTRL+V, or Edit Paste). Then save the picture.<BR>
			Then I resized the images to 50% of their original width with the Microsoft Photo Editor (Image, Resize. then enter 50%), which is a part of Microsoft Office, and saved them.<br>
			<BR>
			The names of the pictures need to have the extension .jpg   (that will be no problem using Paint). <BR>
			The names you give to the images can not be chosen randomly;<br>
			The name of the theme (for instant: AventureMedia) will also be the name of the big image (in this case AventureMedia.jpg).<br>
			The name of the smaller image will be the themename with "_thumb" after it (in this case AventureMedia_thumb.jpg).
			<br>
			<br>
			I have 20 themes installed and have made screenshots of them all. I've them uploaded into a zipfile, if you wish you can download the zip here :: <br>
			<a target="_blank" href="http://www.funforum.xmbgarage.com/xmb/Theme_Preview_screenshots.zip">http://www.funforum.xmbgarage.com/xmb/Theme_Preview_screenshots.zip</a><br>
		        </td>
		    </tr>

		    <tr class="tablerow" bgcolor="<?=$altbg1?>">
		        <td align="center">Installation; If Upgrading from 1.0.3 or 1.0.3b</td>
		    </tr>
		    <tr class="mediumtxt" bgcolor="<?=$altbg2?>">
		        <td> Replace your Themes.php with the new one (If you're reading this i assume you already have done this)</td>
		    </tr>
		    <tr class="tablerow" bgcolor="<?=$altbg1?>">
		        <td align="center">Installation; If Upgrading from 1.0.2 or below</td>
		    </tr>
		    <tr class="mediumtxt" bgcolor="<?=$altbg2?>">
		        <td>
		        Open your language file; <br>
			Remove (if you find any): <br>
			$lang_themes_set = "Set"; <br>
			$lang_themes_ttheme = "This Theme"; <br>
			$lang_themes_amtheme = "As My Theme"; <br>
			$lang_themes_dtheme = "The Default Theme On This Board Is"; <br>
			$lang_settheme = "Set This Theme"; <br>
			<br>
		        Open memcp.php;<br>
		        Remove:
		        if ($action == &quot;theme&quot;) {<br>
			$db-&gt;query(&quot;UPDATE $table_members SET theme = '$newtheme' WHERE username = '$xmbuser'&quot;);<br>
			echo &quot;&lt;meta http-equiv=\&quot;refresh\&quot; content=\&quot;0; url=memcp.php\&quot;&gt;&quot;;<br>
			exit;<br>
			}<br>
			<br>
			You will have to find where you put the following and remove it (there was no designated spot for it in any older version)
			Remove: &lt;a href=&quot;themes.php&quot;&gt;$lang_themes&lt;/a&gt;<br>
			<br>
			Then follow the rest of the instructions from the top.
		        </td>
		    </tr>

		</table>
		</td></tr>
		</table>
		<?
		echo $footer;
		break;

		default:
		echo "<html><head>$css<title>$bbname - $lang_textpowered</title></head><body $bgcode text=\"$text\">";
		?>
		<form method="get" action="memcp.php?action=theme">
		<table cellspacing="0" cellpadding="0" border="0" width="100%" bgcolor="<?=$bordercolor?>">
		<tr>
		<td>
			<table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">
			<tr><td align="center" class="category" <?=$catbgcode?>><big><?=$lang_themes_dtheme?>:</big></td></tr>
			<tr><td align="center" class="header"><big><?=$SETTINGS['theme']?></big>
			<tr><td align="center" bgcolor="<?=$altbg2?>" class="mediumtxt"><img border="0" src="images/themes/<?=$SETTINGS['theme']?>_thumb.jpg"></td></tr>
			<tr><td align="center" bgcolor="<?=$altbg1?>" class="mediumtxt"><a target="_blank" href="images/themes/<?=$SETTINGS['theme']?>.jpg">» <?=$lang_smiliepreview?> «</a><br><a href="memcp.php?action=theme&newtheme=<?=$SETTINGS['theme']?>">» <?=$lang_themes_set?> <?=$lang_themes_ttheme?> «</a></td></tr>
		<?
		$query = $db->query("SELECT name FROM $table_themes ORDER BY name ASC");
		$i=1;
		while($themeinfo = $db->fetch_array($query)) {
		?>


			<tr><td align="center" class="category" <?=$catbgcode?>><big><?=$lang_texttheme?> <?=$i?></big></td></tr>
			<tr><td align="center" class="header"><big><?=$themeinfo[name]?></big></td></tr>
			<tr><td align="center" bgcolor="<?=$altbg2?>" class="mediumtxt"><img border="0" src="images/themes/<?=$themeinfo[name]?>_thumb.jpg"></td></tr>
			<tr><td align="center" bgcolor="<?=$altbg1?>" class="mediumtxt"><a target="_blank" href="images/themes/<?=$themeinfo[name]?>.jpg">» <?=$lang_smiliepreview?> «</a><br><a href="memcp.php?action=theme&newtheme=<?=$themeinfo[name]?>">» <?=$lang_themes_set?> <?=$lang_themes_ttheme?> «</a></td></tr>
		<?
		$i++;
		}
		?>
		</table></table>
		</form>
		<?
		echo "</body></html>";
		}
?>
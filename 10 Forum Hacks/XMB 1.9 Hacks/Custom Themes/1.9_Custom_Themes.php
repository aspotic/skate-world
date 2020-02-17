<?php
/* 
 
 
 
 
     ATTENTION     !!!     ATTENTION     !!!     ATTENTION     !!!     ATTENTION     !!!     ATTENTION     !!!
 
 
 
     ATTENTION     !!!     ATTENTION     !!!     ATTENTION     !!!     ATTENTION     !!!     ATTENTION     !!!
 
 
 
 
     Upload this file to your forumdirectory and point your browser to it;
 
     Example:   http://www.domain.ext/forum/1.9_Custom_Themes.php
 
 
 
 
 
     ATTENTION     !!!     ATTENTION     !!!     ATTENTION     !!!     ATTENTION     !!!     ATTENTION     !!!
 
 
 
     ATTENTION     !!!     ATTENTION     !!!     ATTENTION     !!!     ATTENTION     !!!     ATTENTION     !!!
 
 
 
 
*/ 

if($action == "ct"){ ?><html><head><title> [1.9 Release] Custom Themes </title></head><body>
// Start Custom Themes<br />
elseif($action == "ct") { <br />
&nbsp;&nbsp;&nbsp;&nbsp;$page .= '&lt;table cellspacing="0" cellpadding="0" border="0" width="'.$tablewidth.'" align="center"&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&lt;tr&gt;&lt;td bgcolor="'.$bordercolor.'"&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&lt;table border="0" cellspacing="'.$borderwidth.'" cellpadding="'.$tablespace.'" width="100%"&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;switch($s) {<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;case 'admin':<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;if(!X_ADMIN) {<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;header("Location: memcp.php?action=ct");<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}else{<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;if(FunForum_su($SETTINGS[CustomTheme], 1) == "1"){ $fcto1y = 'checked'; $fcto1n = ''; }else{ $fcto1y = ''; $fcto1n = 'checked'; }<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;if(FunForum_su($SETTINGS[CustomTheme], 2) == "1"){ $fcto2y = 'checked'; $fcto2n = ''; }else{ $fcto2y = ''; $fcto2n = 'checked'; }<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;if(FunForum_su($SETTINGS[CustomTheme], 3) == "1"){ $fcto3y = 'checked'; $fcto3n = ''; }else{ $fcto3y = ''; $fcto3n = 'checked'; }<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;if(FunForum_su($SETTINGS[CustomTheme], 4) == "1"){ $fcto4y = 'checked'; $fcto4n = ''; }else{ $fcto4y = ''; $fcto4n = 'checked'; }<br />
<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$page .= '&lt;form method="post" action="memcp.php?action=ct&s=admin"&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$page .= '&lt;tr&gt;&lt;td colspan="3" '.$catbgcode.' class="category"&gt;&lt;strong&gt;&lt;font color="'.$cattext.'"&gt;'.$lang[ct_name].' &raquo; Admin Area&lt;/font&gt;&lt;/strong&gt;&lt;/td&gt;&lt;/tr&gt;';<br />
<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$page .= '&lt;/table&gt;&lt;/td&gt;&lt;/tr&gt;&lt;/table&gt; &lt;br /&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$page .= '&lt;table cellspacing="0" cellpadding="0" border="0" width="'.$tablewidth.'" align="center"&gt;&lt;tr&gt;&lt;td bgcolor="'.$bordercolor.'"&gt;&lt;table border="0" cellspacing="'.$borderwidth.'" cellpadding="'.$tablespace.'" width="100%"&gt;';<br />
<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;if(!$ctsubmit && !$ctcancel){<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$tquery = $db-&gt;query("SELECT CustomTheme FROM ".$tablepre."members_themes WHERE uid='0'"); $dtheme = $db-&gt;result($tquery, 0); <br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$themelist = '&lt;select name="ntname"&gt;\n';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$query = $db-&gt;query("SELECT themeid,name FROM $table_themes");<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;while($themeinfo = $db-&gt;fetch_array($query)) {<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;if($themeinfo['themeid'] == $dtheme){<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$themelist .= '&lt;option value="'.$themeinfo[themeid].'" selected&gt;'.$themeinfo[name].'&lt;/option&gt;\n';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}else{<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$themelist .= '&lt;option value="'.$themeinfo[themeid].'"&gt;'.$themeinfo[name].'&lt;/option&gt;\n';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$themelist .= '&lt;/select&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$page .= '&lt;tr bgcolor="'.$altbg1.'" class="tablerow"&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;'.$lang[ct_o16].'&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td colspan="2"&gt;'.$lang[ct_o28].''.$themelist.'&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/tr&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$page .= '&lt;tr bgcolor="'.$altbg1.'" class="tablerow"&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;'.$lang[ct_o29].'&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td colspan="2"&gt; &lt;input type="radio" value="1" '.$fcto1y.' name="fcto1new" /&gt; '.$lang[textyes].' &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &lt;input type="radio" value="0" '.$fcto1n.' name="fcto1new" /&gt; '.$lang[textno].' &lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/tr&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$page .= '&lt;tr bgcolor="'.$altbg1.'" class="tablerow"&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;'.$lang[ct_o30].'&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td colspan="2"&gt; &lt;input type="radio" value="1" '.$fcto2y.' name="fcto2new" /&gt; '.$lang[textyes].' &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &lt;input type="radio" value="0" '.$fcto2n.' name="fcto2new" /&gt; '.$lang[textno].' &lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/tr&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$page .= '&lt;tr bgcolor="'.$altbg1.'" class="tablerow"&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;'.$lang[ct_o32].'&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td colspan="2"&gt; &lt;input type="radio" value="1" '.$fcto3y.' name="fcto3new" /&gt; '.$lang[textyes].' &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &lt;input type="radio" value="0" '.$fcto3n.' name="fcto3new" /&gt; '.$lang[textno].' &lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/tr&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$page .= '&lt;tr bgcolor="'.$altbg1.'" class="tablerow"&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;'.$lang[ct_o33].'&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td colspan="2"&gt; &lt;input type="radio" value="1" '.$fcto4y.' name="fcto4new" /&gt; '.$lang[textyes].' &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &lt;input type="radio" value="0" '.$fcto4n.' name="fcto4new" /&gt; '.$lang[textno].' &lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/tr&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$page .= '&lt;tr bgcolor="'.$altbg1.'" class="tablerow"&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;'.$lang[ct_o04].'&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td colspan="2" align="center"&gt;&lt;input type="submit" value="'.$lang[ct_b01].'" name="ctsubmit"&gt; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &lt;input type="submit" value="'.$lang[ct_b02].'" name="ctcancel"&gt;&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/tr&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}elseif($ctsubmit){<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$querg = $db-&gt;query("SELECT * FROM $table_themes WHERE themeid='$ntname'");<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$t = $db-&gt;fetch_array($querg);<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$db-&gt;query("UPDATE ".$tablepre."members_themes SET CustomTheme='$t[themeid]', name='$t[name]', bgcolor='$t[bgcolor]', altbg1='$t[altbg1]', altbg2='$t[altbg2]', link='$t[link]', bordercolor='$t[bordercolor]', header='$t[header]', headertext='$t[headertext]', top='$t[top]', catcolor='$t[catcolor]', tabletext='$t[tabletext]', text='$t[text]', borderwidth='$t[borderwidth]', tablewidth='$t[tablewidth]', tablespace='$t[tablespace]', fontsize='$t[fontsize]', font='$t[font]', boardimg='$t[boardimg]', imgdir='$t[imgdir]', smdir='$t[smdir]', cattext='$t[cattext]' WHERE uid='0'");<br />
<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$db-&gt;query("UPDATE $table_settings SET CustomTheme='$fcto1new|$fcto2new|$fcto3new|$fcto4new'");<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$page .= '&lt;tr bgcolor="'.$altbg2.'" class="tablerow"&gt;&lt;td colspan="3" align="center"&gt;'.$lang[ct_o17].'&lt;/td&gt;&lt;/tr&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;redirect("memcp.php?action=ct", 2, X_REDIRECT_JS);<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}elseif($ctcancel){<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$page .= '&lt;tr bgcolor="'.$altbg2.'" class="tablerow"&gt;&lt;td colspan="3" align="center"&gt;'.$lang[ct_o15].'&lt;/td&gt;&lt;/tr&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;redirect("memcp.php?action=ct", 2, X_REDIRECT_JS);<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$page .= '&lt;/form&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;break;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;case 'activate':<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$page .= '&lt;tr&gt;&lt;td '.$catbgcode.' class="category"&gt;&lt;strong&gt;&lt;font color="'.$cattext.'"&gt;'.$lang[ct_name].' &raquo; Activation&lt;/font&gt;&lt;/strong&gt;&lt;/td&gt;&lt;/tr&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$query = $db-&gt;query("SELECT themeid FROM ".$tablepre."members_themes WHERE uid = '$self[uid]'"); <br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;if($e = $db-&gt;fetch_array($query)) { <br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$page .= '&lt;tr&gt;&lt;td bgcolor="'.$altbg2.'" class="tablerow" align="center"&gt;'.$lang[ct_o09].'&lt;br /&gt;&lt;br /&gt;&lt;input type="button" value="'.$lang[ct_o13].'" onClick="location.href=\'memcp.php?action=ct\'" /&gt;&lt;/td&gt;&lt;/tr&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}else{ <br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;if(!$do){<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$page .= '&lt;tr&gt;&lt;td bgcolor="'.$altbg2.'" class="tablerow" align="center"&gt;'.$lang[ct_o10].'&lt;br /&gt;&lt;br /&gt;&lt;input type="button" value="'.$lang[ct_o11].'" onClick="location.href=\'memcp.php?action=ct&s=activate&do=it\'" /&gt;&lt;/td&gt;&lt;/tr&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}else{<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$querg = $db-&gt;query("SELECT * FROM ".$tablepre."members_themes WHERE uid='0'");<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$t = $db-&gt;fetch_array($querg);<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;if(FunForum_su($SETTINGS[CustomTheme], 4) == "1"){ <br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;if(FunForum_su($SETTINGS[CustomTheme], 3) == "1"){ $t_name = $t[name]; }<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;else{ $t_name = "$self[username]$lang[ct_o31]"; $t_name = addslashes($t_name); }<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$query = "INSERT INTO ".$tablepre."members_themes (themeid, uid, CustomTheme, name, bgcolor, altbg1, altbg2, link, bordercolor, header, headertext, top, catcolor, tabletext, text, borderwidth, tablewidth, tablespace, font, fontsize, boardimg, imgdir, smdir, cattext) VALUES ('', '$self[uid]', '0|0', '$t_name', '$t[bgcolor]', '$t[altbg1]', '$t[altbg2]', '$t[link]', '$t[bordercolor]', '$t[header]', '$t[headertext]', '$t[top]', '$t[catcolor]', '$t[tabletext]', '$t[text]', '$t[borderwidth]', '$t[tablewidth]', '$t[tablespace]', '$t[font]', '$t[fontsize]', '$t[boardimg]', '$t[imgdir]', '$t[smdir]', '$t[cattext]')";<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}else{<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;if(FunForum_su($SETTINGS[CustomTheme], 3) == "0"){ $t_name = str_replace("*Username*", $self[username], $lang[ct_o31]); $t_name = addslashes($t_name); }<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$query = "INSERT INTO ".$tablepre."members_themes (themeid, uid, CustomTheme, name, bgcolor, altbg1, altbg2, link, bordercolor, header, headertext, top, catcolor, tabletext, text, borderwidth, tablewidth, tablespace, font, fontsize, boardimg, imgdir, smdir, cattext) VALUES ('', '$self[uid]', '0|0', '$t_name', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '')";<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$result = mysql_query($query) or die("Query failed : " . mysql_error());<br />
<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$page .= '&lt;tr&gt;&lt;td bgcolor="'.$altbg2.'" class="tablerow" align="center"&gt;'.$lang[ct_o12].'&lt;br /&gt;&lt;br /&gt;&lt;input type="button" value="'.$lang[ct_o13].'" onClick="location.href=\'memcp.php?action=ct\'" /&gt;&lt;/td&gt;&lt;/tr&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;break;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;default:<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$page .= '&lt;form method="post" action="memcp.php?action=ct"&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$page .= '&lt;tr&gt;&lt;td colspan="3" '.$catbgcode.' class="category"&gt;&lt;strong&gt;&lt;font color="'.$cattext.'"&gt;'.$lang[ct_name].'&lt;/font&gt;&lt;/strong&gt;&lt;/td&gt;&lt;/tr&gt;';<br />
<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;if(FunForum_su($self[CustomTheme], 1) == "1"){ $usectyes = "checked"; $usectno=''; }else{ $usectno = "checked"; $usectyes=''; } <br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;if(FunForum_su($self[CustomTheme], 2) == "1"){ $sharectyes = "checked"; $sharectno=''; }else{ $sharectno = "checked"; $sharectyes=''; } <br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$query = $db-&gt;query("SELECT * FROM ".$tablepre."members_themes WHERE uid = '$self[uid]'"); <br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;if($t = $db-&gt;fetch_array($query)) { <br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;if(!$ctsubmit && !$ctother && !$ctcancel && !$ctdefault){<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$page .= '&lt;tr bgcolor="'.$altbg2.'" class="tablerow"&gt;&lt;td colspan="3"&gt;'.$lang[ct_o34].'&lt;/td&gt;&lt;/tr&gt;';<br />
<br />
<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$page .= '&lt;/table&gt;&lt;/td&gt;&lt;/tr&gt;&lt;/table&gt; &lt;br /&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$page .= '&lt;table cellspacing="0" cellpadding="0" border="0" width="'.$tablewidth.'" align="center"&gt;&lt;tr&gt;&lt;td bgcolor="'.$bordercolor.'"&gt;&lt;table border="0" cellspacing="'.$borderwidth.'" cellpadding="'.$tablespace.'" width="100%"&gt;';<br />
<br />
<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$page .= '&lt;tr&gt;&lt;td colspan="3" '.$catbgcode.' class="category"&gt;&lt;strong&gt;&lt;font color="'.$cattext.'"&gt;'.$lang[ct_o21].'&lt;/font&gt;&lt;/strong&gt;&lt;/td&gt;&lt;/tr&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$page .= '&lt;tr bgcolor="'.$altbg2.'" class="tablerow"&gt;&lt;td colspan="3"&gt;'.$lang[ct_o35].'&lt;/td&gt;&lt;/tr&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$page .= '&lt;tr bgcolor="'.$altbg2.'" class="tablerow"&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td width="67%"&gt;'.$lang[ct_o01].'&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td width="33%" colspan="2"&gt; &lt;input type="radio" value="1" '.$usectyes.' name="usectnew" /&gt; '.$lang[textyes].' &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &lt;input type="radio" value="0" '.$usectno.' name="usectnew" /&gt; '.$lang[textno].' &lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/tr&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$page .= '&lt;tr bgcolor="'.$altbg2.'" class="tablerow"&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;'.$lang[ct_o02].'&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td colspan="2"&gt; &lt;input type="radio" value="1" '.$sharectyes.' name="sharectnew" /&gt; '.$lang[textyes].' &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &lt;input type="radio" value="0" '.$sharectno.' name="sharectnew" /&gt; '.$lang[textno].' &lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/tr&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$page .= '&lt;tr bgcolor="'.$altbg2.'" class="tablerow"&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;'.$lang[ct_o04].'&lt;input type="hidden" value="'.$t[themeid].'" name="theid" /&gt;&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td colspan="2" align="center"&gt;&lt;input type="submit" value="'.$lang[ct_b01].'" name="ctsubmit"&gt; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &lt;input type="submit" value="'.$lang[ct_b02].'" name="ctcancel"&gt;&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/tr&gt;';<br />
<br />
<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$page .= '&lt;/table&gt;&lt;/td&gt;&lt;/tr&gt;&lt;/table&gt; &lt;br /&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$page .= '&lt;table cellspacing="0" cellpadding="0" border="0" width="'.$tablewidth.'" align="center"&gt;&lt;tr&gt;&lt;td bgcolor="'.$bordercolor.'"&gt;&lt;table border="0" cellspacing="'.$borderwidth.'" cellpadding="'.$tablespace.'" width="100%"&gt;';<br />
<br />
<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$page .= '&lt;tr&gt;&lt;td colspan="3" '.$catbgcode.' class="category"&gt;&lt;strong&gt;&lt;font color="'.$cattext.'"&gt;'.$lang[ct_o23].'&lt;/font&gt;&lt;/strong&gt;&lt;/td&gt;&lt;/tr&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$page .= '&lt;tr bgcolor="'.$altbg2.'" class="tablerow"&gt;&lt;td colspan="3"&gt;'.$lang[ct_o36].'&lt;/td&gt;&lt;/tr&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$querg = $db-&gt;query("SELECT * FROM ".$tablepre."members_themes WHERE uid='$self[uid]'");<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$t = $db-&gt;fetch_array($querg);<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$quere = $db-&gt;query("SELECT * FROM ".$tablepre."members_themes WHERE (themeid='1' AND uid='0')");<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$extheme = $db-&gt;fetch_array($quere);<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;if(FunForum_su($SETTINGS[CustomTheme], 3) == "1"){ $fctna = ""; $t[name] = stripslashes($t[name]); $fctne = $lang[ct_o03].': '.$extheme[name]; }<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;else{ $fctna = "readonly"; $t[name] = "$self[username]$lang[ct_o31]"; }<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$page .= '&lt;tr bgcolor="'.$altbg2.'" class="tablerow"&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td width="33%"&gt;'.$lang[texthemename].'&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td width="34%"&gt;&lt;input type="text" value="'.$t[name].'" '.$fctna.' name="namenew" /&gt;&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td width="33%"&gt;'.$fctne.'&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/tr&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$page .= '&lt;tr bgcolor="'.$altbg2.'" class="tablerow"&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;'.$lang[ct_o24].'&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;&lt;input type="text" value="'.$t[bgcolor].'" name="bgcolornew" /&gt;&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;'.$lang[ct_o03].': '.$extheme[bgcolor].'&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/tr&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$page .= '&lt;tr bgcolor="'.$altbg2.'" class="tablerow"&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;'.$lang[textaltbg1].'&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;&lt;input type="text" value="'.$t[altbg1].'" name="altbg1new" /&gt;&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;'.$lang[ct_o03].': '.$extheme[altbg1].'&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/tr&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$page .= '&lt;tr bgcolor="'.$altbg2.'" class="tablerow"&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;'.$lang[textaltbg2].'&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;&lt;input type="text" value="'.$t[altbg2].'" name="altbg2new" /&gt;&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;'.$lang[ct_o03].': '.$extheme[altbg2].'&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/tr&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$page .= '&lt;tr bgcolor="'.$altbg2.'" class="tablerow"&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;'.$lang[textlink].'&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;&lt;input type="text" value="'.$t[link].'" name="linknew" /&gt;&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;'.$lang[ct_o03].': '.$extheme[link].'&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/tr&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$page .= '&lt;tr bgcolor="'.$altbg2.'" class="tablerow"&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;'.$lang[textborder].'&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;&lt;input type="text" value="'.$t[bordercolor].'" name="bordercolornew" /&gt;&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;'.$lang[ct_o03].': '.$extheme[bordercolor].'&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/tr&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$page .= '&lt;tr bgcolor="'.$altbg2.'" class="tablerow"&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;'.$lang[textheader].'&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;&lt;input type="text" value="'.$t[header].'" name="headernew" /&gt;&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;'.$lang[ct_o03].': '.$extheme[header].'&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/tr&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$page .= '&lt;tr bgcolor="'.$altbg2.'" class="tablerow"&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;'.$lang[textheadertext].'&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;&lt;input type="text" value="'.$t[headertext].'" name="headertextnew" /&gt;&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;'.$lang[ct_o03].': '.$extheme[headertext].'&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/tr&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;if(!strstr($extheme[top], ".")) { $exttop = '&lt;br /&gt;http://domain.com/img/image.ext&lt;br /&gt;'.$extheme[top]; } else { $exttop = '&lt;br /&gt;http://domain.com/img/'.$extheme[top].'&lt;br /&gt;#123456'; }<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$page .= '&lt;tr bgcolor="'.$altbg2.'" class="tablerow"&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;'.$lang[ct_o25].'&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;&lt;input type="text" value="'.$t[top].'" name="topnew" /&gt;&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;'.$lang[ct_o03].': '.$exttop.'&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/tr&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;if(!strstr($extheme[catcolor], ".")) { $extcatcl = '&lt;br /&gt;http://domain.com/img/image.ext&lt;br /&gt;'.$extheme[catcolor]; } else { $extcatcl = '&lt;br /&gt;http://domain.com/img/'.$extheme[catcolor].'&lt;br /&gt;#123456'; }<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$page .= '&lt;tr bgcolor="'.$altbg2.'" class="tablerow"&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;'.$lang[ct_o26].'&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;&lt;input type="text" value="'.$t[catcolor].'" name="catcolornew" /&gt;&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;'.$lang[ct_o03].': '.$extcatcl.'&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/tr&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$page .= '&lt;tr bgcolor="'.$altbg2.'" class="tablerow"&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;'.$lang[textcattextcolor].'&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;&lt;input type="text" value="'.$t[cattext].'" name="cattextnew" /&gt;&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;'.$lang[ct_o03].': '.$extheme[cattext].'&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/tr&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$page .= '&lt;tr bgcolor="'.$altbg2.'" class="tablerow"&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;'.$lang[texttabletext].'&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;&lt;input type="text" value="'.$t[tabletext].'" name="tabletextnew" /&gt;&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;'.$lang[ct_o03].': '.$extheme[tabletext].'&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/tr&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$page .= '&lt;tr bgcolor="'.$altbg2.'" class="tablerow"&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;'.$lang[texttext].'&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;&lt;input type="text" value="'.$t[text].'" name="textnew" /&gt;&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;'.$lang[ct_o03].': '.$extheme[text].'&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/tr&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$page .= '&lt;tr bgcolor="'.$altbg2.'" class="tablerow"&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;'.$lang[textborderwidth].'&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;&lt;input type="text" value="'.$t[borderwidth].'" name="borderwidthnew" size="2" /&gt;&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;'.$lang[ct_o03].': '.$extheme[borderwidth].'&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/tr&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$page .= '&lt;tr bgcolor="'.$altbg2.'" class="tablerow"&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;'.$lang[textwidth].'&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;&lt;input type="text" value="'.$t[tablewidth].'" name="tablewidthnew" size="3" /&gt;&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;'.$lang[ct_o03].': '.$extheme[tablewidth].'&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/tr&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$page .= '&lt;tr bgcolor="'.$altbg2.'" class="tablerow"&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;'.$lang[textspace].'&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;&lt;input type="text" value="'.$t[tablespace].'" name="tablespacenew" size="2" /&gt;&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;'.$lang[ct_o03].': '.$extheme[tablespace].'&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/tr&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$page .= '&lt;tr bgcolor="'.$altbg2.'" class="tablerow"&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;'.$lang[textfont].'&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;&lt;input type="text" value="'.$t[font].'" name="fnew" /&gt;&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;'.$lang[ct_o03].': '.$extheme[font].'&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/tr&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$page .= '&lt;tr bgcolor="'.$altbg2.'" class="tablerow"&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;'.$lang[textbigsize].'&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;&lt;input type="text" value="'.$t[fontsize].'" name="fsizenew" size="4" /&gt;&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;'.$lang[ct_o03].': '.$extheme[fontsize].'&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/tr&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$page .= '&lt;tr bgcolor="'.$altbg2.'" class="tablerow"&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;'.$lang[ct_o27].'&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;&lt;input type="text" value="'.$t[boardimg].'" name="boardlogonew" /&gt;&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;'.$lang[ct_o03].':&lt;br /&gt;http://domain.ext/img/'.$extheme[boardimg].'&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/tr&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;if(FunForum_su($SETTINGS[CustomTheme], 1) == "1"){ <br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$imgdirlist = '&lt;input type="text" value="'.$t[imgdir].'" name="imgdirnew" /&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}else{<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$imgdirlist = '&lt;select name="imgdirnew"&gt;\n';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$query = $db-&gt;query("SELECT name, imgdir FROM $table_themes");<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;while($imgdirinfo = $db-&gt;fetch_array($query)) {<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;if($imgdirinfo[imgdir] == "$t[imgdir]"){<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$imgdirlist .= '&lt;option value="'.$imgdirinfo[imgdir].'" selected&gt;'.$imgdirinfo[name].'&lt;/option&gt;\n';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}else{<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$imgdirlist .= '&lt;option value="'.$imgdirinfo[imgdir].'"&gt;'.$imgdirinfo[name].'&lt;/option&gt;\n';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$imgdirlist .= '&lt;/select&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$page .= '&lt;tr bgcolor="'.$altbg2.'" class="tablerow"&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;'.$lang[imgdir].'&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;'.$imgdirlist.'&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;'.$lang[ct_o03].': '.$extheme[imgdir].'&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/tr&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;if(FunForum_su($SETTINGS[CustomTheme], 2) == "1"){ <br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$smdirlist = '&lt;input type="text" value="'.$t[smdir].'" name="smdirnew" /&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}else{<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$smdirlist = '&lt;select name="smdirnew"&gt;\n';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$query = $db-&gt;query("SELECT DISTINCT smdir FROM $table_themes");<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;while($smdirinfo = $db-&gt;fetch_array($query)) {<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;if($smdirinfo[smdir] == "$t[imgdir]"){<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$smdirlist .= '&lt;option value="'.$smdirinfo[smdir].'" selected&gt;'.$smdirinfo[smdir].'&lt;/option&gt;\n';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}else{<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$smdirlist .= '&lt;option value="'.$smdirinfo[smdir].'"&gt;'.$smdirinfo[smdir].'&lt;/option&gt;\n';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$smdirlist .= '&lt;/select&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$page .= '&lt;tr bgcolor="'.$altbg2.'" class="tablerow"&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;'.$lang[smdir].'&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;'.$smdirlist.'&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;'.$lang[ct_o03].': '.$extheme[smdir].'&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/tr&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$page .= '&lt;tr bgcolor="'.$altbg2.'" class="tablerow"&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;'.$lang[ct_o04].'&lt;input type="hidden" value="'.$t[themeid].'" name="theid" /&gt;&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td colspan="2" align="center"&gt;&lt;input type="submit" value="'.$lang[ct_b01].'" name="ctsubmit"&gt; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &lt;input type="submit" value="'.$lang[ct_b03].'" name="ctdefault"&gt; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &lt;input type="submit" value="'.$lang[ct_b02].'" name="ctcancel"&gt;&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/tr&gt;';<br />
<br />
<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$page .= '&lt;/table&gt;&lt;/td&gt;&lt;/tr&gt;&lt;/table&gt; &lt;br /&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$page .= '&lt;table cellspacing="0" cellpadding="0" border="0" width="'.$tablewidth.'" align="center"&gt;&lt;tr&gt;&lt;td bgcolor="'.$bordercolor.'"&gt;&lt;table border="0" cellspacing="'.$borderwidth.'" cellpadding="'.$tablespace.'" width="100%"&gt;';<br />
<br />
<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$page .= '&lt;tr&gt;&lt;td colspan="3" '.$catbgcode.' class="category"&gt;&lt;strong&gt;&lt;font color="'.$cattext.'"&gt;'.$lang[ct_o22].'&lt;/font&gt;&lt;/strong&gt;&lt;/td&gt;&lt;/tr&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$tquery = $db-&gt;query("SELECT name, uid FROM ".$tablepre."members_themes WHERE (uid!='$self[uid]' AND (CustomTheme='0|1' OR CustomTheme='1|1')) ORDER by name");<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$tctsmtlist = '&lt;select name="otheruid"&gt;\n';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$tctsmtlist .= '&lt;option value=""&gt;'.$lang['ct_o38'].'&lt;/option&gt;\n';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;while($tctsmtinfo = $db-&gt;fetch_array($tquery)) {<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$tctsmtlist .= '&lt;option value="'.$tctsmtinfo[uid].'"&gt;'.$tctsmtinfo[name].'&lt;/option&gt;\n';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$tctsmtlist .= '&lt;/select&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$page .= '&lt;tr bgcolor="'.$altbg2.'" class="tablerow"&gt;&lt;td colspan="3"&gt;'.$lang[ct_o19].'&lt;/td&gt;&lt;/tr&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$page .= '&lt;tr bgcolor="'.$altbg2.'" class="tablerow"&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;'.$lang[ct_o28].'&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td colspan="2"&gt;'.$tctsmtlist.'&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/tr&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$page .= '&lt;tr bgcolor="'.$altbg2.'" class="tablerow"&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td width="33%"&gt;'.$lang[ct_o04].'&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td width="67%" colspan="2" align="center"&gt;&lt;input type="submit" value="'.$lang[ct_b04].'" name="ctother"&gt;&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/tr&gt;';<br />
<br />
<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;if(X_ADMIN){<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$page .= '&lt;/table&gt;&lt;/td&gt;&lt;/tr&gt;&lt;/table&gt; &lt;br /&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$page .= '&lt;table cellspacing="0" cellpadding="0" border="0" width="'.$tablewidth.'" align="center"&gt;&lt;tr&gt;&lt;td bgcolor="'.$bordercolor.'"&gt;&lt;table border="0" cellspacing="'.$borderwidth.'" cellpadding="'.$tablespace.'" width="100%"&gt;';<br />
<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$page .= '&lt;tr&gt;&lt;td colspan="3" '.$catbgcode.' class="category"&gt;&lt;strong&gt;&lt;font color="'.$cattext.'"&gt;'.$lang[ct_o37].'&lt;/font&gt;&lt;/strong&gt;&lt;/td&gt;&lt;/tr&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$page .= '&lt;tr bgcolor="'.$altbg2.'" class="tablerow"&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td width="33%"&gt;'.$lang[ct_o04].'&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td width="67%" colspan="2" align="center"&gt;&lt;input type="button" value="'.$lang[ct_b06].'" onClick="location.href=\'memcp.php?action=ct&s=admin\'" /&gt;&lt;/td&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/tr&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}<br />
<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}elseif($ctsubmit){<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;// we also look for a 'dot' (where an image is not allowed), so no-one can submit an URL<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$usectnew = checkInput($usectnew, '', '', "script");&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $usectnew = checkInput($usectnew, '', '', "."); <br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$sharectnew = checkInput($sharectnew, '', '', "script");     $sharectnew = checkInput($sharectnew, '', '', "."); <br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$namenew = checkInput($namenew, '', '', "script");&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $namenew = addslashes($namenew);<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$bgcolornew = checkInput($bgcolornew, '', '', "script");<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$altbg1new = checkInput($altbg1new, '', '', "script");&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $altbg1new = checkInput($altbg1new, '', '', "."); <br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$altbg2new = checkInput($altbg2new, '', '', "script");&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $altbg2new = checkInput($altbg2new, '', '', "."); <br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$linknew = checkInput($linknew, '', '', "script");&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $linknew = checkInput($linknew, '', '', "."); <br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$bordercolornew = checkInput($bordercolornew, '', '', "script"); $bordercolornew = checkInput($bordercolornew, '', '', "."); <br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$headernew = checkInput($headernew, '', '', "script");&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $headernew = checkInput($headernew, '', '', "."); <br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$headertextnew = checkInput($headertextnew, '', '', "script");     $headertextnew = checkInput($headertextnew, '', '', "."); <br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$topnew = checkInput($topnew, '', '', "script");     <br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$catcolornew = checkInput($catcolornew, '', '', "script");     <br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$tabletextnew = checkInput($tabletextnew, '', '', "script");     $tabletextnew = checkInput($tabletextnew, '', '', "."); <br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$textnew = checkInput($textnew, '', '', "script");&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $textnew = checkInput($textnew, '', '', "."); <br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$borderwidthnew = checkInput($borderwidthnew, '', '', "script"); $borderwidthnew = checkInput($borderwidthnew, '', '', "."); <br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$tablewidthnew = checkInput($tablewidthnew, '', '', "script");     $tablewidthnew = checkInput($tablewidthnew, '', '', "."); <br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$tablespacenew = checkInput($tablespacenew, '', '', "script");     $tablespacenew = checkInput($tablespacenew, '', '', "."); <br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$fsizenew = checkInput($fsizenew , '', '', "script");&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $fsizenew = checkInput($fsizenew , '', '', "."); <br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$fnew = checkInput($fnew, '', '', "script");&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $fnew = checkInput($fnew, '', '', "."); <br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$boardlogonew = checkInput($boardlogonew, '', '', "script");     <br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$imgdirnew = checkInput($imgdirnew, '', '', "script");&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; if(FunForum_su($SETTINGS[CustomTheme], 1) == "0"){ $imgdirnew = checkInput($imgdirnew, '', '', "."); }<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$smdirnew = checkInput($smdirnew, '', '', "script");&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; if(FunForum_su($SETTINGS[CustomTheme], 2) == "0"){ $smdirnew = checkInput($smdirnew, '', '', "."); }<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$cattextnew = checkInput($cattextnew, '', '', "script");     $cattextnew = checkInput($cattextnew, '', '', "."); <br />
<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$db-&gt;query("UPDATE ".$tablepre."members_themes SET CustomTheme='$usectnew|$sharectnew', name='$namenew', bgcolor='$bgcolornew', altbg1='$altbg1new', altbg2='$altbg2new', link='$linknew', bordercolor='$bordercolornew', header='$headernew', headertext='$headertextnew', top='$topnew', catcolor='$catcolornew', tabletext='$tabletextnew', text='$textnew', borderwidth='$borderwidthnew', tablewidth='$tablewidthnew', tablespace='$tablespacenew', fontsize='$fsizenew', font='$fnew', boardimg='$boardlogonew', imgdir='$imgdirnew', smdir='$smdirnew', cattext='$cattextnew' WHERE uid='$self[uid]'");<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$db-&gt;query("UPDATE $table_members SET CustomTheme='$usectnew|$sharectnew' WHERE uid='$self[uid]'");<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$page .= '&lt;tr bgcolor="'.$altbg2.'" class="tablerow"&gt;&lt;td colspan="3" align="center"&gt;'.$lang[ct_o14].'&lt;/td&gt;&lt;/tr&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;redirect("memcp.php?action=ct", 2, X_REDIRECT_JS);<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}elseif($ctcancel){<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$page .= '&lt;tr bgcolor="'.$altbg2.'" class="tablerow"&gt;&lt;td colspan="3" align="center"&gt;'.$lang[ct_o15].'&lt;/td&gt;&lt;/tr&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;redirect("memcp.php?action=ct", 2, X_REDIRECT_JS);<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}elseif($ctother){<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;if(isset($otheruid)) {<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$page .= '&lt;tr bgcolor="'.$altbg2.'" class="tablerow"&gt;&lt;td colspan="3" align="center"&gt;'.$lang['ct_o39'].'&lt;/td&gt;&lt;/tr&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}else{<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$querg = $db-&gt;query("SELECT * FROM ".$tablepre."members_themes WHERE (uid='$otheruid' AND (CustomTheme='0|1' OR CustomTheme='1|1'))");<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$t = $db-&gt;fetch_array($querg);<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;if(FunForum_su($SETTINGS[CustomTheme], 3) == "1"){ $t_name = $t[name]; }<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;else{ $t_name = "$self[username]$lang[ct_o31]"; $t_name = addslashes($t_name); }<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$db-&gt;query("UPDATE ".$tablepre."members_themes SET name='$t_name', bgcolor='$t[bgcolor]', altbg1='$t[altbg1]', altbg2='$t[altbg2]', link='$t[link]', bordercolor='$t[bordercolor]', header='$t[header]', headertext='$t[headertext]', top='$t[top]', catcolor='$t[catcolor]', tabletext='$t[tabletext]', text='$t[text]', borderwidth='$t[borderwidth]', tablewidth='$t[tablewidth]', tablespace='$t[tablespace]', fontsize='$t[fontsize]', font='$t[font]', boardimg='$t[boardimg]', imgdir='$t[imgdir]', smdir='$t[smdir]', cattext='$t[cattext]' WHERE uid='$self[uid]'");<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$page .= '&lt;tr bgcolor="'.$altbg2.'" class="tablerow"&gt;&lt;td colspan="3" align="center"&gt;'.$lang[ct_o20].'&lt;/td&gt;&lt;/tr&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;redirect("memcp.php?action=ct", 2, X_REDIRECT_JS);<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}elseif($ctdefault){<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$querg = $db-&gt;query("SELECT * FROM ".$tablepre."members_themes WHERE (themeid='1' AND uid='0')");<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$t = $db-&gt;fetch_array($querg);<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;if(FunForum_su($SETTINGS[CustomTheme], 3) == "1"){ $t_name = $t[name]; }<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;else{ $t_name = "$self[username]$lang[ct_o31]"; $t_name = addslashes($t_name); }<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$db-&gt;query("UPDATE ".$tablepre."members_themes SET name='$t_name', bgcolor='$t[bgcolor]', altbg1='$t[altbg1]', altbg2='$t[altbg2]', link='$t[link]', bordercolor='$t[bordercolor]', header='$t[header]', headertext='$t[headertext]', top='$t[top]', catcolor='$t[catcolor]', tabletext='$t[tabletext]', text='$t[text]', borderwidth='$t[borderwidth]', tablewidth='$t[tablewidth]', tablespace='$t[tablespace]', fontsize='$t[fontsize]', font='$t[font]', boardimg='$t[boardimg]', imgdir='$t[imgdir]', smdir='$t[smdir]', cattext='$t[cattext]' WHERE uid='$self[uid]'");<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$page .= '&lt;tr bgcolor="'.$altbg2.'" class="tablerow"&gt;&lt;td colspan="3" align="center"&gt;'.$lang[ct_o18].'&lt;/td&gt;&lt;/tr&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;redirect("memcp.php?action=ct", 2, X_REDIRECT_JS);<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}else{<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;header("Location: memcp.php?action=ct&s=activate");<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$page .= '&lt;/form&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;break;<br />
&nbsp;&nbsp;&nbsp;&nbsp;}<br />
&nbsp;&nbsp;&nbsp;&nbsp;$page .= '&lt;/table&gt;&lt;/td&gt;&lt;/tr&gt;&lt;/table&gt;';<br />
<br />
&nbsp;&nbsp;&nbsp;&nbsp;eval("\$header = \"".template("header")."\";"); $ltpmnw = "<br />
&nbsp;&nbsp;&nbsp;&nbsp;<br />
&nbsp;&nbsp;&nbsp;&nbsp;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&lt;!-- Custom Themes, by FunForum --&gt;<br />
&nbsp;&nbsp;&nbsp;&nbsp;<br />
&nbsp;&nbsp;&nbsp;&nbsp;<br />
&nbsp;&nbsp;&nbsp;&nbsp;"; $header .= $ltpmnw; echo $header;<br />
&nbsp;&nbsp;&nbsp;&nbsp;makenav($action);<br />
&nbsp;&nbsp;&nbsp;&nbsp;$throw = $page; $throw .= $ltpmnw;<br />
&nbsp;&nbsp;&nbsp;&nbsp;echo stripslashes($throw);<br />
}
</body></html><?php exit; }

require "./config.php";
require "./xmb.php";
require "./functions.php";
require "./db/$database.php";
$db = new dbstuff;
$db->connect($dbhost, $dbuser, $dbpw, $dbname, $pconnect);
?>

<html>
<head>
<title> [1.9 Release] Custom Themes </title>
<style type="text/css">
body {
margin-top : 2px;
margin-right : 2px;
margin-bottom : 2px;
margin-left : 2px;
}

body, p { 
color : #000000;
font-family : Arial, Times New Roman, Courier, sans-serif;
font-size : 10pt;
font-weight : normal;
text-decoration : none;
}

a:hover, a:visited {
color : #000000;
font-size : 10pt;
font-weight : normal;
text-decoration : underline;
}

a:hover {
text-decoration : none;
}

a:link, a:active {
color : #000000;
font-size : 10pt;
font-weight : normal;
text-decoration : underline;
}

textarea {
background : transparent none repeat;
border : 1px solid #000000;
color : #000000;
font-family : Courier New, Times New Roman, Courier, sans-serif;
font-size : 9pt;
overflow : hidden;
}

.highlighttext {
font-weight: bold;
}
</style>
<script language="Javascript">
<!--
/*
Select and Copy form element script- By Dynamicdrive.com
For full source, Terms of service, and 100s DTHML scripts
Visit http://www.dynamicdrive.com
*/

//specify whether contents should be auto copied to clipboard (memory)
//Applies only to IE 4+
//0=no, 1=yes
var copytoclip=1

function HighlightAll(theField) {
var tempval=eval("document."+theField)
tempval.focus()
tempval.select()
if (document.all&&copytoclip==1){
therange=tempval.createTextRange()
therange.execCommand("Copy")
window.status="Contents highlighted and copied to clipboard!"
setTimeout("window.status=''",1800)
}
}
//-->
</script>
</head>
<body bgcolor="#FFFFFF">
<form name="FunForum">

<?php 

$field_exists = mysql_query("SELECT themeid FROM ".$tablepre."members_themes"); 
if ($field_exists) { $CTexists = "yes"; }else{ $CTexists = "no"; }


if($step == "2" && $CTexists == "yes"){ $step = "AlreadyInstalled"; }


if($step){
	if($step == "1"){ $bl .= "&raquo; Installation; Step 1";
	} elseif($step == "2"){ $bl .= "&raquo; Installation; Step 2";
	} elseif($step == "3"){ $bl .= "&raquo; Installation; Step 3";
	} elseif($step == "manual"){ $bl .= "&raquo; The manual file adjustments";
	} elseif($step == "AlreadyInstalled"){ $bl .= "&raquo; Error... The hack has been installed already";
	}
	echo '<center><b>Custom Themes Hack</b> '.$bl.'</center><hr noshade color="#000000" size="1">';
} 


if(!$step){ ?>

	<br /><b>Hack Name:</b> Custom Themes
	<br />
	<br /><b>Description:</b> This hack will give people the option to create their own Theme and share it with others.
	<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- User options
	<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- You can create your own theme and use your own colors/images
	<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Enable/disable the sharing of your theme with others
	<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Administrator options
	<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Enable/disable the use of own image directories
	<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Enable/disable the use of own smilie directories
	<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Enable/disable the use of an own theme name
	<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Enable/disable that people get the Default Custom Theme
	<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Set a Default Custom Theme
	<br />
	<br /><b>Compatibility:</b> XMB 1.9 (RC1) - Nexus
	<br />
	<br /><b>Version:</b> 1.1
	<br />
	<br /><b>Code Designed By:</b> FunForum
	<br />
	<br /><b>Copyright:</b> Posting or distributing this hack, without permission from the author, is not permitted.
	<br />
	<br /><b>Notes:</b>
	<br />- For your own safety, backup all effected files & templates before proceeding with this hack
	<br />- Keep all names and copyrights in their place

	<br />
	<br />
	<br />
	<br />
	<?php if($CTexists == "no"){ ?>
		<br /><a target="_self" href="<?php echo $PHP_SELF;?>?step=1">Click here to proceed with Step 1</a>
		<br />While going through the steps 1 - 3, please do NOT press the Back button and do NOT Refresh the page
	<?php }else{ ?>
		<br />It looks like the Custom Theme Hack has already been installed... If you want to see the manual file adjustments again...
		<br /><a target="_self" href="<?php echo $PHP_SELF;?>?step=manual">Click here to see The manual file adjustments</a>
	<?php } ?>
	<br />
	<br />
	<br />


<?php } if($step == "1" || $step == "manual"){ ?>

	<?php if($step == "1"){ ?>
	<br /><b>Step 1.</b>
	<br />
	<?php }else{ ?>
	<br /><b>The manual file adjustments</b>
	<br />
	<?php } ?>

<br />
<br /><b>
<br />Edit Language File
<br /></b>

<br />Find:
<br /><textarea wrap="off" name="select1" rows="1" cols="100">?></textarea>
<br /><a class="highlighttext" href="javascript:HighlightAll('FunForum.select1')">Select All</a>
<br />

<br />Replace with:
<br /><textarea wrap="off" name="select2" rows="48" cols="100">// Custom Theme Hack
$lang['ct_b01'] = "Save";
$lang['ct_b02'] = "Cancel";
$lang['ct_b03'] = "Set Default Theme";
$lang['ct_b04'] = "Use this theme";
$lang['ct_b05'] = "Continue...";
$lang['ct_b06'] = "Admin Area";
$lang['ct_name'] = "Custom Theme";
$lang['ct_o01'] = "Use the Custom Theme?";
$lang['ct_o02'] = "Do you want to share your Custom Theme with others?";
$lang['ct_o03'] = "Example";
$lang['ct_o04'] = "Form Options:";
$lang['ct_o05'] = "The necessary table has already been created.<br />You do not need to create it at this time.";
$lang['ct_o06'] = "The necessary table has NOT been created yet.";
$lang['ct_o07'] = "Click here to create the table now";
$lang['ct_o08'] = "The necessary table has just been created.<br />Now your members can start using the Custom Themes Hack.";
$lang['ct_o09'] = "This feature has already been activated for you.<br />You do not need to activate this feature anymore.";
$lang['ct_o10'] = "This feature hasn't been activated for you yet.";
$lang['ct_o11'] = "Click here to activate the feature now.";
$lang['ct_o12'] = "The feature has been activated.";
$lang['ct_o13'] = "Click here to modify your Custom Theme";
$lang['ct_o14'] = "Your settings have been saved.";
$lang['ct_o15'] = "Your settings have NOT been saved.";
$lang['ct_o16'] = "Here you can choose the theme that should be used as default theme.<br />This will not affect any members at the present.<br />This will be the theme your members will get when they choose the option to set a default theme and the theme they get when they first activate their Custom Theme.<br /><br />The Default Custom Theme will be saved in a new record, so any modifications done to the theme in your Administration Panel will not affect the Default Custom Theme.";
$lang['ct_o17'] = "The settings have been saved.";
$lang['ct_o18'] = "Your Custom Theme has been restored to the Default Custom Theme";
$lang['ct_o19'] = "Instead of making your own Custom Theme, you can also copy a Custom Theme someone else made and shares it.<br />Scroll through the dropdown list to see who shared their Custom Theme.<br />Then click the \"$lang[ct_b04]\" button to copy their Custom Theme to yours.<br />Note: you can not see your own theme in the list.";
$lang['ct_o20'] = "Your Custom Theme set to the one you selected.";
$lang['ct_o21'] = "General Options";
$lang['ct_o22'] = "Use Other People's Themes";
$lang['ct_o23'] = "Create Your Custom Theme";
$lang['ct_o24'] = "Background:<br />(HEX code or image name)";
$lang['ct_o25'] = "Top Table Color:<br />(HEX code or image name)";
$lang['ct_o26'] = "Category Color:<br />(HEX code or image name)";
$lang['ct_o27'] = "Board Logo URL:";
$lang['ct_o28'] = "Theme:";
$lang['ct_o29'] = "Can people use their own image directory? If no, they get to choose from the ones used in the forum themes.";
$lang['ct_o30'] = "Can people use their own smilie directory? If no, they get to choose from the ones used in the forum themes.";
$lang['ct_o31'] = "'s Custom Theme";
$lang['ct_o32'] = "Can people set an own Theme Name?<br />If not, it'll be formatted like <i>*Username*$lang[ct_o31]</i>";
$lang['ct_o33'] = "If people activate their Custom Theme, should them be given the Default Custom Theme?";
$lang['ct_o34'] = "Welcome to the Custom Themes. Here you can create your own Custom Theme and use other people's Custom Themes.";
$lang['ct_o35'] = "Here you can edit your Custom Theme Settings";
$lang['ct_o36'] = "Here you can create your Custom Theme.";
$lang['ct_o37'] = "Administrator Options";
$lang['ct_o38'] = "Choose a theme...";
$lang['ct_o39'] = "You have not selected a theme to set.";
?></textarea>
<br /><a class="highlighttext" href="javascript:HighlightAll('FunForum.select2')">Select All</a>
<br />

<br />
<br /><b>
<br />Edit PHP File: functions.php
<br /></b>

<br />Find:
<br /><textarea wrap="off" name="select3" rows="1" cols="100">function end_time() {</textarea>
<br /><a class="highlighttext" href="javascript:HighlightAll('FunForum.select3')">Select All</a>
<br />

<br />Replace with:
<br /><textarea wrap="off" name="select4" rows="7" cols="100">function FunForum_su($here, $gimme) {
    $go = $gimme - 1;
    $bsu = explode("|", $here);
    return $bsu[$go];
}

function end_time() {</textarea>
<br /><a class="highlighttext" href="javascript:HighlightAll('FunForum.select4')">Select All</a>
<br />

<br />
<br /><b>
<br />Edit PHP File: memcp.php
<br /></b>

<br />Find:
<br /><textarea wrap="off" name="select5" rows="4" cols="100">    case "favorites":
        nav('<a href="memcp.php">'.$lang['textusercp'].'</a>');
        nav($lang['textfavorites']);
        break;</textarea>
<br /><a class="highlighttext" href="javascript:HighlightAll('FunForum.select5')">Select All</a>
<br />

<br />Add Above:
<br /><textarea wrap="off" name="select6" rows="4" cols="100">    case "ct":
        nav('<a href="memcp.php">'.$lang['textusercp'].'</a>');
        nav($lang['ct_name']);
        break;</textarea>
<br /><a class="highlighttext" href="javascript:HighlightAll('FunForum.select6')">Select All</a>
<br />

<!--

<br />Find:
<br /><textarea wrap="off" name="select7" rows="1" cols="100"></textarea>
<br /><a class="highlighttext" href="javascript:HighlightAll('FunForum.select7')">Select All</a>
<br />

<br />Replace with:
<br /><textarea wrap="off" name="select8" rows="1" cols="100"></textarea>
<br /><a class="highlighttext" href="javascript:HighlightAll('FunForum.select8')">Select All</a>
<br />

-->

<br />Find:
<br /><textarea wrap="off" name="select9" rows="1" cols="100">    if($current == "subscriptions") {</textarea>
<br /><a class="highlighttext" href="javascript:HighlightAll('FunForum.select9')">Select All</a>
<br />

<br />Replace with:
<br /><textarea wrap="off" name="select10" rows="7" cols="100">    if($current == "ct") {
        echo "<td bgcolor=\"$altbg1\" width=\"15%\" class=\"ctrtablerow\">" .$lang['ct_name']. "</td>";
    } else {
        echo "<td bgcolor=\"$altbg2\" width=\"15%\" class=\"ctrtablerow\"><a href=\"memcp.php?action=ct\">" .$lang['ct_name']. "</a></td>";
    }

    if($current == "subscriptions") {</textarea>
<br /><a class="highlighttext" href="javascript:HighlightAll('FunForum.select10')">Select All</a>
<br />

<br />Find:
<br /><textarea wrap="off" name="select11" rows="3" cols="100">// Load the Default Page
else {
    eval("\$header = \"".template("header")."\";");</textarea>
<br /><a class="highlighttext" href="javascript:HighlightAll('FunForum.select11')">Select All</a>
<br />

<br />Add Above:
<br /> &raquo; <a class="highlighttext" target="_blank" href="<?php echo $PHP_SELF;?>?action=ct">this code (click)</a> &laquo; It might look weird, but if you copy everything into Notepad, it looks how it's supposed to look :)
<br />

<br />
<br /><b>
<br />Edit PHP File: member.php
<br /></b>

<br />Find:
<br /><textarea wrap="off" name="select17" rows="1" cols="100">$db->query("INSERT INTO $table_members VALUES ('', '$username', '$password', ".$db->time(time()).",</textarea>
<br /><a class="highlighttext" href="javascript:HighlightAll('FunForum.select17')">Select All</a>
<br />

<br />Add to the end of the statement<br /><b>BEFORE::</b>  )");
<br /><textarea wrap="off" name="select18" rows="1" cols="100">, ''</textarea>
<br /><a class="highlighttext" href="javascript:HighlightAll('FunForum.select18')">Select All</a>
<br />

	<?php if($step == "1"){ ?>
	<br />
	<br />
	<br /><a target="_self" href="<?php echo $PHP_SELF;?>?step=2">Now this part is done, click here to go to step 2</a>
	<br />
	<br />
	<?php } ?>

<?php } if($step == "2"){ 
		$query = "CREATE TABLE ".$tablepre."members_themes (";
		$query .= "`themeid` int(6) NOT NULL auto_increment,";
		$query .= "`uid` int(6) NOT NULL default '999999',";
		$query .= "`CustomTheme` varchar(10) NOT NULL default '0|0',";
		$query .= "`name` varchar(50) NOT NULL default '',";
		$query .= "`bgcolor` varchar(120) NOT NULL default '',";
		$query .= "`altbg1` varchar(15) NOT NULL default '',";
		$query .= "`altbg2` varchar(15) NOT NULL default '',";
		$query .= "`link` varchar(15) NOT NULL default '',";
	//	$query .= "`dummy` tinyint(4) default NULL,";
		$query .= "`bordercolor` varchar(15) NOT NULL default '',";
		$query .= "`header` varchar(15) NOT NULL default '',";
		$query .= "`headertext` varchar(15) NOT NULL default '',";
		$query .= "`top` varchar(120) NOT NULL default '',";
		$query .= "`catcolor` varchar(120) NOT NULL default '',";
		$query .= "`tabletext` varchar(15) NOT NULL default '',";
		$query .= "`text` varchar(15) NOT NULL default '',";
		$query .= "`borderwidth` varchar(15) NOT NULL default '',";
		$query .= "`tablewidth` varchar(15) NOT NULL default '',";
		$query .= "`tablespace` varchar(15) NOT NULL default '',";
		$query .= "`font` varchar(40) NOT NULL default '',";
		$query .= "`fontsize` varchar(40) NOT NULL default '',";
		$query .= "`boardimg` varchar(128) default NULL,";
		$query .= "`imgdir` varchar(120) NOT NULL default '',";
		$query .= "`smdir` varchar(120) NOT NULL default '',";
		$query .= "`cattext` varchar(15) NOT NULL default '',";
		$query .= " PRIMARY KEY  (`themeid`),";
		$query .= " KEY `uid` (`uid`)";
		$query .= ")TYPE=MyISAM COMMENT = 'FunForum: Custom Themes Hack'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());

		$squery = $db->query("SELECT theme FROM ".$tablepre."settings");
		$stheme = $db->result($squery, 0);

		$querg = $db->query("SELECT * FROM ".$tablepre."themes WHERE themeid='$stheme'");
		$t = $db->fetch_array($querg);

		$query = "INSERT INTO ".$tablepre."members_themes (themeid, uid, CustomTheme, name, bgcolor, altbg1, altbg2, link, bordercolor, header, headertext, top, catcolor, tabletext, text, borderwidth, tablewidth, tablespace, font, fontsize, boardimg, imgdir, smdir, cattext) VALUES ('', '0', '$t[themeid]', '$t[name]', '$t[bgcolor]', '$t[altbg1]', '$t[altbg2]', '$t[link]', '$t[bordercolor]', '$t[header]', '$t[headertext]', '$t[top]', '$t[catcolor]', '$t[tabletext]', '$t[text]', '$t[borderwidth]', '$t[tablewidth]', '$t[tablespace]', '$t[font]', '$t[fontsize]', '$t[boardimg]', '$t[imgdir]', '$t[smdir]', '$t[cattext]')";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());

		$query = "ALTER TABLE ".$tablepre."members ADD `CustomTheme` varchar(10) NOT NULL default '0|0'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());

		$query = "ALTER TABLE ".$tablepre."settings ADD `CustomTheme` varchar(10) NOT NULL default '0|0|0|1'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());
	?>

	<br /><b>Step 2.</b>
	<br />

	<br />
	<br />The necessary table has been created and both the <?=$tablepre?>members and <?=$tablepre?>settings tables have been adjusted.
	<br /><a target="_self" href="<?php echo $PHP_SELF;?>?step=3">Now this part is done, click here to go to step 3</a>
	<br />
	<br />

<?php } if($step == "3" || $step == "manual"){ ?>

	<?php if($step == "3"){ ?>
	<br /><b>Step 3.</b>
	<br />
	<?php } ?>

<br />
<br /><b>
<br />Edit PHP File: header.php
<br /></b>

<br />Find:
<br /><textarea wrap="off" name="select13" rows="46" cols="100">// Make theme-vars semi-global
    $query = $db->query("SELECT * FROM $table_themes WHERE themeid='$theme'");
    foreach($db->fetch_array($query) as $key => $val) {
        if($key != "name") {
            $$key = $val;
        }
        $THEME[$key] = $val;
    }
    $imgdir = './'.$imgdir;

    // Alters certain visibility-variables
    if(false === strpos($bgcolor, ".")) {
        $bgcode = "background-color: $bgcolor;";
    } else {
        $bgcode = "background-image: url('$imgdir/$bgcolor');";
    }

    if(false === strpos($catcolor, ".")) {
        $catbgcode  = "bgcolor=\"$catcolor\"";
        $catcss     = 'background-color: '.$catcolor.';';
    } else {
        $catbgcode  = "style=\"background-image: url($imgdir/$catcolor)\"";
        $catcss     = 'background-image: url('.$imgdir.'/'.$catcolor.');';
    }

    if(false === strpos($top, ".")) {
        $topbgcode = "bgcolor=\"$top\"";
    } else {
        $topbgcode = "style=\"background-image: url($imgdir/$top)\"";
    }

    if (false !== strpos($boardimg, ",")){
        $flashlogo = explode(",",$boardimg);
        $logo = '
             <OBJECT classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://active.macromedia.com/flash2/cabs/swflash.cab#version=6,0,0,0" ID=main WIDTH="'.$flashlogo[1].'" HEIGHT="'.$flashlogo[2].'">
                <PARAM NAME=movie VALUE="'.$flashlogo[0].'">
                <PARAM NAME="loop" VALUE="false">
                <PARAM NAME="menu" VALUE="false">
                <PARAM NAME="quality" VALUE="best">
                <EMBED src="'.$flashlogo[0].'" loop="false" menu="false" quality="best" WIDTH="'.$flashlogo[1].'" HEIGHT="'.$flashlogo[2].'" TYPE="application/x-shockwave-flash" PLUGINSPAGE="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash">
                </EMBED>
              </OBJECT>
             ';
    } else {
        $logo = '<a href="index.php"><img src="'.$imgdir.'/'.$boardimg.'" alt="'.$bbname.'" border="0" /></a>';
    }</textarea>
<br /><a class="highlighttext" href="javascript:HighlightAll('FunForum.select13')">Select All</a>
<br />

<br />Replace with:
<br /><textarea wrap="off" name="select14" rows="65" cols="100">// Make theme-vars semi-global
    if($self[CustomTheme] == "1|0" || $self[CustomTheme] == "1|1"){
        $query = $db->query("SELECT * FROM ".$tablepre."members_themes WHERE uid='$self[uid]'");
        foreach($db->fetch_array($query) as $key => $val) {
            if($key != "name") {
                $$key = $val;
            }
            $THEME[$key] = $val;
        }
        if(false === strstr($imgdir, ".")){ $imgdir = './'.$imgdir; }
        if(false === strstr($bgcolor, "http:")){  $Fcthv1 = "$imgdir/$bgcolor";    }else{ $Fcthv1 = $bgcolor; }
        if(false === strstr($catcolor, "http:")){ $Fcthv2 = "$imgdir/$catcolor";   }else{ $Fcthv2 = $catcolor; }
        if(false === strstr($top, "http:")){      $Fcthv3 = "$imgdir/$top";        }else{ $Fcthv3 = $top; }
        if(false === strstr($boardimg, "http:")){ $Fcthv4 = $imgdir.'/'.$boardimg; }else{ $Fcthv4 = $boardimg; }
    }else{
        $query = $db->query("SELECT * FROM $table_themes WHERE themeid='$theme'");
        foreach($db->fetch_array($query) as $key => $val) {
            if($key != "name") {
                $$key = $val;
            }
            $THEME[$key] = $val;
        }
        $imgdir = './'.$imgdir;
        $Fcthv1 = "$imgdir/$bgcolor";
        $Fcthv2 = "$imgdir/$catcolor";
        $Fcthv3 = "$imgdir/$top";
        $Fcthv4 = $imgdir.'/'.$boardimg;
    }

    // Alters certain visibility-variables
    if(false === strstr($bgcolor, ".")) {
        $bgcode = "background-color: $bgcolor;";
    } else {
        $bgcode = "background-image: url('$Fcthv1');";
    }

    if(false === strstr($catcolor, ".")) {
        $catbgcode  = "bgcolor=\"$catcolor\"";
        $catcss     = 'background-color: '.$catcolor.';';
    } else {
        $catbgcode  = "style=\"background-image: url($Fcthv2)\"";
        $catcss     = 'background-image: url('.$Fcthv2.');';
    }

    if(false === strstr($top, ".")) {
        $topbgcode = "bgcolor=\"$top\"";
    } else {
        $topbgcode = "style=\"background-image: url($Fcthv3)\"";
    }

    if(false !== strpos($boardimg, ",")){
        $flashlogo = explode(",",$boardimg);
        $logo = '
             <OBJECT classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://active.macromedia.com/flash2/cabs/swflash.cab#version=6,0,0,0" ID=main WIDTH="'.$flashlogo[1].'" HEIGHT="'.$flashlogo[2].'">
                <PARAM NAME=movie VALUE="'.$imgdir.'/'.$flashlogo[0].'">
                <PARAM NAME="loop" VALUE="false">
                <PARAM NAME="menu" VALUE="false">
                <PARAM NAME="quality" VALUE="best">
                <EMBED src="'.$imgdir.'/'.$flashlogo[0].'" loop="false" menu="false" quality="best" WIDTH="'.$flashlogo[1].'" HEIGHT="'.$flashlogo[2].'" TYPE="application/x-shockwave-flash" PLUGINSPAGE="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash">
                </EMBED>
              </OBJECT>
             ';
    } else {
        $logo = '<a href="index.php"><img src="'.$Fcthv4.'" alt="'.$bbname.'" border="0" /></a>';
    }</textarea>
<br /><a class="highlighttext" href="javascript:HighlightAll('FunForum.select14')">Select All</a>
<br />



	<?php if($step == "3"){ ?>
	<br />
	<br />
	<br /><a target="_self" href="memcp.php?action=ct">The "Custom Themes Hack" has been installed.<br />Click here to view your Custom Theme now!</a>
	<?php } ?>
	<br />
	<br />


<?php } if($step == "AlreadyInstalled"){ ?>

<br />Sorry, it looks like you have already installed the "Custom Theme Hack".
<br />Therefore, parts of this file can't be accessed anymore.
<br />The only part that can still be accessed, can be found as links over here:
<br /><a target="_self" href="<?php echo $PHP_SELF;?>"><?php echo $PHP_SELF;?></a>
<br />
<?php } ?>


</form>
</body>
</html>
<?php
/*
	SHOP HACK v1.2 by NFPUNK

	If you require support please post in the forum
	at www.flixon.com/shop/forum.  Thanks

	- nfpunk/Flixon
*/

require "./config.php";
require "./xmb.php";
require "./functions.php";
require "./db/$database.php";

?>

<html>
<head>
<title>Installation Shop Hack v1.2</title>
</head>
<body bgcolor="#C3C3C3">
<table cellspacing="0" cellpadding="0" width="90%" align="center" border="1" bordercolor="#000000">
<tr>
<td bgcolor=#FFFFFF>
<table cellspacing="0" cellpadding="0" width="100%" align="center">
<tr>
<td><center><font face="Verdana, Arial" size="2"><u>Installation Shop Hack v1.2</u></font></center></td>
</tr>
<tr>
<td>

<?php

if(!$action) {
	echo '<center>
	<br><br>This hack is made for XMB as freeware,
	<br>!please do not make money with it!
	<br>if you want to make it avaible for download on your site then ask permission first.
	<br><br><b>DO NOT REMOVE THE COPYRIGHT INFO!!!</b>
	<br><br>
	<a href="instshop.php?action=accept">ACCEPT</a>
	</center>';
}

if($action == "accept") {
	echo '<center><br><a href="instshop.php?action=new">New Install</a></center>';
	echo '<center><br><a href="instshop.php?action=upgrade1.1">Upgrade 1.1-1.2</a> - Note: This will keep all additional items/cats added and settings modified</center>';
}

if($action == "new" || $action == "upgrade1.1") {
	$db = new dbstuff;
	$db->connect($dbhost, $dbuser, $dbpw, $dbname, $pconnect);

	$tables = array('member_items', 'members', 'shop_auction_items', 'shop_bank', 'shop_cats', 'shop_items', 'shop_settings', 'shop_votes', 'templates');

	foreach($tables as $name) {
		${'table_'.$name} = $tablepre.$name;
	}

	echo '<font face="Verdana, Arial" size="2">Starting instalation...</font><br>';

	if($action != "upgrade1.1") {
		echo '<font face="Verdana, Arial" size="2">Creating '.$table_member_items.'...</font><br>';

		$db->query("DROP TABLE IF EXISTS $table_member_items");
		$db->query("CREATE TABLE $table_member_items (
			iid smallint(6) NOT NULL,
			uid smallint(6) NOT NULL
		)");
	}

	if($action != "new") {
		$db->query("ALTER TABLE $table_member_items
			DROP id
		");
	}

	if($action != "upgrade1.1") {
		echo '<font face="Verdana, Arial" size="2">Creating '.$table_shop_auction_items.'...</font><br>';

		$db->query("DROP TABLE IF EXISTS $table_shop_auction_items");
		$db->query("CREATE TABLE $table_shop_auction_items (
			id smallint(6) NOT NULL auto_increment,
			iid smallint(6) NOT NULL,
			uid smallint(6) NOT NULL,
			dateline bigint(30) NOT NULL,
			endtime bigint(30) NOT NULL,
			bidincrement int(10) NOT NULL default '0',
			bids int(10) NOT NULL default '0',
			amount int(10) NOT NULL default '0',
			biduid int(10) NOT NULL,
			PRIMARY KEY (id)
		)");
	}

	echo '<font face="Verdana, Arial" size="2">Creating '.$table_shop_bank.'...</font><br>';

	$db->query("DROP TABLE IF EXISTS $table_shop_bank");
	$db->query("CREATE TABLE $table_shop_bank (
		fromuid smallint(6) NOT NULL,
		touid smallint(6) NOT NULL,
		dateline bigint(30) NOT NULL,
		amount int(10) NOT NULL default '0',
		comment text NOT NULL,
		type varchar(10) NOT NULL
	)");

	if($action != "upgrade1.1") {
		echo '<font face="Verdana, Arial" size="2">Creating '.$table_shop_cats.'...</font><br>';

		$db->query("DROP TABLE IF EXISTS $table_shop_cats");
		$db->query("CREATE TABLE $table_shop_cats (
			id smallint(6) NOT NULL auto_increment,
			cid smallint(6) NOT NULL default '0',
			owner smallint(6) NOT NULL,
			catname varchar(100) NOT NULL,
			description text NOT NULL,
			displayorder int(10) NOT NULL,
			whoview text NOT NULL,
			whoadd text NOT NULL,
			lastadd varchar(64) NOT NULL,
			password varchar(32) NOT NULL default '',
			status char(3) NOT NULL,
			creatoronly char(3) NOT NULL,
			views int(10) NOT NULL default '0',
			items int(10) NOT NULL default '0',
			ownermoney char(3) NOT NULL,
			PRIMARY KEY (id)
		)");

		$db->query("INSERT INTO $table_shop_cats VALUES (1, 0, 0, 'Features Store', 'You can buy different abilities here!', 1, 'Super Administrator, Administrator, Super Moderator, Moderator, Member,', 'Super Administrator, Administrator,', '', '', 'on', 'off', 0, 7, 'off');");
		$db->query("INSERT INTO $table_shop_cats VALUES (2, 0, 0, 'Item Store', 'You can buy different items here!', 2, 'Super Administrator, Administrator, Super Moderator, Moderator, Member,', 'Super Administrator, Administrator,', '', '', 'off', 'off', 0, 0, 'off');");
	} else {
		echo '<font face="Verdana, Arial" size="2">Altering '.$table_shop_cats.'...</font><br>';

		$db->query("ALTER TABLE $table_shop_cats
			CHANGE owner owner smallint(6) NOT NULL,
			ADD items int(10) NOT NULL default '0',
			ADD ownermoney char(3) NOT NULL
		");
	}

	if($action != "upgrade1.1") {
		echo '<font face="Verdana, Arial" size="2">Creating '.$table_shop_items.'...</font><br>';

		$db->query("DROP TABLE IF EXISTS $table_shop_items");
		$db->query("CREATE TABLE $table_shop_items (
			id smallint(6) NOT NULL auto_increment,
			cid smallint(6) NOT NULL,
			feature varchar(32) NOT NULL default '',
			owner smallint(6) NOT NULL,
			itemname varchar(100) NOT NULL,
			description text NOT NULL,
			displayorder int(10) NOT NULL,
			imageurl varchar(64) NOT NULL,
			dateline bigint(30) NOT NULL,
			status char(3) NOT NULL,
			sold int(10) NOT NULL default '0',
			rate int(10) NOT NULL default '0',
			votes int(10) NOT NULL default '0',
			comments int(10) NOT NULL default '0',
			price int(10) NOT NULL default '0',
			stock int(10) NOT NULL default '0',
			PRIMARY KEY (id)
		)");

		$db->query("INSERT INTO $table_shop_items VALUES (1, 1, 'cusername', 0, 'Change Username', 'With this action you can change your username.', 1, '', ".time().", 'on', 0, 0, 0, 0, 10000, 9999);");
		$db->query("INSERT INTO $table_shop_items VALUES (2, 1, 'glow', 0, 'Glowing Username', 'With this action you can add an attractive glowing color to your username.', 2, '', ".time().", 'on', 0, 0, 0, 0, 7500, 9999);");
		$db->query("INSERT INTO $table_shop_items VALUES (3, 1, 'color', 0, 'Change Username Colour', 'With this action you can change your username color.', 3, '', ".time().", 'on', 0, 0, 0, 0, 5000, 9999);");
		$db->query("INSERT INTO $table_shop_items VALUES (4, 1, 'pp', 0, 'Personal Photo', 'Upload your own personal photo which members can view in your profile.', 4, '', ".time().", 'on', 0, 0, 0, 0, 5000, 9999);");
		$db->query("INSERT INTO $table_shop_items VALUES (5, 1, 'avatar100', 0, 'Upload Avatar (100*100)', 'Upload your own avatar (Maxsize - 100*100).', 5, '', ".time().", 'on', 0, 0, 0, 0, 1000, 9999);");
		$db->query("INSERT INTO $table_shop_items VALUES (6, 1, 'avatar200', 0, 'Upload Avatar (200*200)', 'Upload your own avatar (Maxsize - 200*200).', 6, '', ".time().", 'on', 0, 0, 0, 0, 2500, 9999);");
		$db->query("INSERT INTO $table_shop_items VALUES (7, 1, 'cstatus', 0, 'Change Custom Status', 'Change your custom status which appears under username in posts.', 7, '', ".time().", 'on', 0, 0, 0, 0, 1000, 9999);");
	} else {
		echo '<font face="Verdana, Arial" size="2">Altering '.$table_shop_items.'...</font><br>';

		$db->query("ALTER TABLE $table_shop_items
			CHANGE owner owner smallint(6) NOT NULL
		");
	}

	if($action != "upgrade1.1") {
		echo '<font face="Verdana, Arial" size="2">Creating '.$table_shop_settings.'...</font><br>';

		$db->query("DROP TABLE IF EXISTS $table_shop_settings");
		$db->query("CREATE TABLE $table_shop_settings (
			shoprules char(3) NOT NULL,
			shoprulestxt text NOT NULL,
			whoview text NOT NULL,
			whocreate text NOT NULL,
			whoadd text NOT NULL,
			search char(3) NOT NULL,
			stats char(3) NOT NULL,
			auction_items char(3) NOT NULL,
			sell char(3) NOT NULL,
			sellpercent int(10) NOT NULL,
			rating char(3) NOT NULL,
			subcats char(3) NOT NULL,
			maxitems int(10) NOT NULL,
			hotcat int(10) NOT NULL,
			hotitem int(10) NOT NULL,
			maxwidth int(10) NOT NULL,
			maxheight int(10) NOT NULL,
			maxsize int(10) NOT NULL,
			mpr int(10) NOT NULL,
			mpt int(10) NOT NULL,
			mpp int(10) NOT NULL,
			std int(10) NOT NULL,
			spd int(10) NOT NULL
		)");

		$db->query("INSERT INTO $table_shop_settings VALUES('on', 'You can modify this by going to the shop admin panel! Alternatively you can turn off this feature!', 'Super Administrator, Administrator, Super Moderator, Moderator, Member,', 'Super Administrator, Administrator,', 'Super Administrator, Administrator,', 'on', 'on', 'on', 'on', 50, 'on', 'on', 50, 20, 50, 80, 80, 100000, 50, 30, 20, 40, 30);");
	} else {
		echo '<font face="Verdana, Arial" size="2">Altering '.$table_shop_settings.'...</font><br>';

		$db->query("ALTER TABLE $table_shop_settings
			DROP COLUMN title,
			DROP COLUMN donate,
			DROP COLUMN comments,
			DROP COLUMN allowhtml,
			DROP COLUMN allowsmilies,
			DROP COLUMN allowbbcode,
			DROP COLUMN allowimgcode,
			CHANGE auction auction_items char(3) NOT NULL,
			ADD shoprules char(3) NOT NULL,
			ADD shoprulestxt text NOT NULL
		");
	}

	if($action != "upgrade1.1") {
		echo '<font face="Verdana, Arial" size="2">Creating '.$table_shop_votes.'...</font><br>';

		$db->query("DROP TABLE IF EXISTS $table_shop_votes");
		$db->query("CREATE TABLE $table_shop_votes (
			iid smallint(6) NOT NULL,
			uid smallint(6) NOT NULL
		)");
	}

	$db->query("ALTER TABLE $table_templates CHANGE name name varchar(50)");

	echo '<font face="Verdana, Arial" size="2">Inserting data into '.$table_templates.'...</font><br>';

	$db->query("DELETE FROM $table_templates WHERE name='member_profile_pphoto'");
	$db->query("DELETE FROM $table_templates WHERE name='shop_admin'");
	$db->query("DELETE FROM $table_templates WHERE name='shop_admin_cat_edit'");
	$db->query("DELETE FROM $table_templates WHERE name='shop_admin_cats'");
	$db->query("DELETE FROM $table_templates WHERE name='shop_admin_cats_row'");
	$db->query("DELETE FROM $table_templates WHERE name='shop_admin_item_edit'");
	$db->query("DELETE FROM $table_templates WHERE name='shop_admin_item_new'");
	$db->query("DELETE FROM $table_templates WHERE name='shop_admin_items'");
	$db->query("DELETE FROM $table_templates WHERE name='shop_admin_items_row'");
	$db->query("DELETE FROM $table_templates WHERE name='shop_admin_settings'");
	$db->query("DELETE FROM $table_templates WHERE name='shop_admin_subcats_row'");
	$db->query("DELETE FROM $table_templates WHERE name='shop_auction_item'");
	$db->query("DELETE FROM $table_templates WHERE name='shop_auction_item_new'");
	$db->query("DELETE FROM $table_templates WHERE name='shop_auction_items'");
	$db->query("DELETE FROM $table_templates WHERE name='shop_auction_items_row'");
	$db->query("DELETE FROM $table_templates WHERE name='shop_bank'");
	$db->query("DELETE FROM $table_templates WHERE name='shop_bank_finances'");
	$db->query("DELETE FROM $table_templates WHERE name='shop_bank_credit'");
	$db->query("DELETE FROM $table_templates WHERE name='shop_bank_credit_row'");
	$db->query("DELETE FROM $table_templates WHERE name='shop_bank_debit'");
	$db->query("DELETE FROM $table_templates WHERE name='shop_bank_debit_row'");
	$db->query("DELETE FROM $table_templates WHERE name='shop_bank_send'");
	$db->query("DELETE FROM $table_templates WHERE name='shop_cat_password'");
	$db->query("DELETE FROM $table_templates WHERE name='shop_cats'");
	$db->query("DELETE FROM $table_templates WHERE name='shop_cats_row'");
	$db->query("DELETE FROM $table_templates WHERE name='shop_cats_auction_items_row'");
	$db->query("DELETE FROM $table_templates WHERE name='shop_cats_lastadd'");
	$db->query("DELETE FROM $table_templates WHERE name='shop_comment_edit'");
	$db->query("DELETE FROM $table_templates WHERE name='shop_comment_edit2'");
	$db->query("DELETE FROM $table_templates WHERE name='shop_comment_post'");
	$db->query("DELETE FROM $table_templates WHERE name='shop_comment_repquote'");
	$db->query("DELETE FROM $table_templates WHERE name='shop_donate'"); // No longer exists
	$db->query("DELETE FROM $table_templates WHERE name='shop_edit_cat_delete'");
	$db->query("DELETE FROM $table_templates WHERE name='shop_edit_cat_edit'");
	$db->query("DELETE FROM $table_templates WHERE name='shop_edit_cat_new'");
	$db->query("DELETE FROM $table_templates WHERE name='shop_edit_item_delete'");
	$db->query("DELETE FROM $table_templates WHERE name='shop_edit_item_edit'");
	$db->query("DELETE FROM $table_templates WHERE name='shop_edit_item_new'");
	$db->query("DELETE FROM $table_templates WHERE name='shop_edit_subcat_new'");
	$db->query("DELETE FROM $table_templates WHERE name='shop_footer'");
	$db->query("DELETE FROM $table_templates WHERE name='shop_edit_cat_new'");
	$db->query("DELETE FROM $table_templates WHERE name='shop_info'");
	$db->query("DELETE FROM $table_templates WHERE name='shop_item_comments'");
	$db->query("DELETE FROM $table_templates WHERE name='shop_item_rate'");
	$db->query("DELETE FROM $table_templates WHERE name='shop_item_cusername'");
	$db->query("DELETE FROM $table_templates WHERE name='shop_item_avatar'");
	$db->query("DELETE FROM $table_templates WHERE name='shop_item_color'");
	$db->query("DELETE FROM $table_templates WHERE name='shop_item_color_preview'");
	$db->query("DELETE FROM $table_templates WHERE name='shop_item_image'");
	$db->query("DELETE FROM $table_templates WHERE name='shop_item_cstatus'");
	$db->query("DELETE FROM $table_templates WHERE name='shop_item_item'");
	$db->query("DELETE FROM $table_templates WHERE name='shop_items'");
	$db->query("DELETE FROM $table_templates WHERE name='shop_items_row'");
	$db->query("DELETE FROM $table_templates WHERE name='shop_links'");
	$db->query("DELETE FROM $table_templates WHERE name='shop_rules'");
	$db->query("DELETE FROM $table_templates WHERE name='shop_search'");
	$db->query("DELETE FROM $table_templates WHERE name='shop_search_results'");
	$db->query("DELETE FROM $table_templates WHERE name='shop_search_results_none'");
	$db->query("DELETE FROM $table_templates WHERE name='shop_search_results_row'");
	$db->query("DELETE FROM $table_templates WHERE name='shop_sell'");
	$db->query("DELETE FROM $table_templates WHERE name='shop_stats'");
	$db->query("DELETE FROM $table_templates WHERE name='shop_subcats'");
	$db->query("DELETE FROM $table_templates WHERE name='shop_subcats_row'");
	$db->query("DELETE FROM $table_templates WHERE name='shop_subcats_lastadd'");
	$db->query("DELETE FROM $table_templates WHERE name='shop_userpanel'");

	echo '<font face="Verdana, Arial" size="2">Inserting data into '.$flixon_templates.' table...</font><br>';

	$filesize = filesize('templates2.xmb');
	$fp = fopen('templates2.xmb','r');
	$templatesfile = fread($fp,$filesize);
	fclose($fp);
	$templates = explode("|#*XMB TEMPLATE FILE*#|", $templatesfile);

	while(list($key, $val) = each($templates)) {
		$template = explode("|#*XMB TEMPLATE*#|", $val);
		$template[1] = addslashes($template[1]);
		$db->query("INSERT INTO $table_templates VALUES('', '".addslashes($template[0])."', '".addslashes($template[1])."')");
	}

	mysql_query("DELETE FROM $table_templates WHERE name=''");

	echo '<font face="Verdana, Arial" size="2">Updating '.$table_members.'...</font><br>';

	if($action == "new") {
		$db->query("ALTER TABLE $table_members
			ADD money int(10) NOT NULL,
			ADD glowcolor varchar(6) NOT NULL,
			ADD hexcolor varchar(6) NOT NULL,
			ADD pphoto varchar(75) NOT NULL
		");
	}

	if($action != "upgrade1.1") {
		$db->query("ALTER TABLE $table_members
			ADD glowcolorstatus char(3) NOT NULL,
			ADD hexcolorstatus char(3) NOT NULL,
			ADD pphotostatus char(3) NOT NULL
		");
	}

	$db->query("UPDATE $table_members
		SET glowcolorstatus='on',
		hexcolorstatus='on',
		pphotostatus='on'
	");

	if($action == "new") {
		echo '<font face="Verdana, Arial" size="2">Installation complete! Read Installation.txt for the next step! Remember to remove this file & templates2.xmb!</font>';
	} else {
		echo '<font face="Verdana, Arial" size="2">Upgrade complete! Read Upgrade 1.1-1.2.txt for the next step! Remember to remove this file & templates2.xmb!</font>';
	}
}

?>

</td>
</tr>
</table>
</td>
</tr>
</table>
</body>
</html>
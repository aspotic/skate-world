<?php
/*
	SHOP HACK v1.2 by NFPUNK

	If you require support please post in the forum
	at www.flixon.com/shop/forum.  Thanks

	- nfpunk/Flixon
*/

require "./header.php";
loadtemplates('header,footer,css,shop_bank,shop_bank_finances,shop_bank_credit,shop_bank_credit_row,shop_bank_debit,shop_bank_debit_row,shop_bank_send,shop_links,shop_info,shop_footer');

$setquery = $db->query("SELECT * FROM $table_shop_settings");
$set = $db->fetch_array($setquery);

$navigation = "&raquo; <a href=\"shop.php\">$lang_shop_title</a> &raquo; $lang_shop_textbank";

eval("\$css = \"".template('css')."\";");

eval("\$header = \"".template('header')."\";");
echo $header;

if(!$xmbuser) {
	$xmblistname = "Annon,";
	$statuslistname = "Guest,";
} else {
	$memquery = $db->query("SELECT * FROM $table_members WHERE username='$xmbuser'");
	$memberinfo = $db->fetch_array($memquery);

	$xmblistname = $xmbuser . ",";
	$statuslistname = $status . ",";
}

if(!eregi($xmblistname, $set['whoview']) && !eregi($statuslistname, $set['whoview'])) {
	$outputtext = "$lang_shop_nopage";
	eval("\$info = \"".template('shop_info')."\";");
	echo $info;
	end_time();
	eval("\$footer = \"".template('footer')."\";");
	echo $footer;
	exit();
}

// Finances

if(!$action || $action == "finances") {
	eval("\$bankpage = \"".template('shop_bank_finances')."\";");
}

// Credit

if($action == "credit") {
	if($limit != 10 && $limit != 20) {
		$limit = 5;
	}

	$creditrows = '';

	$query = $db->query("SELECT * FROM $table_shop_bank WHERE fromuid='$memberinfo[uid]' ORDER BY dateline DESC LIMIT 0, $limit");

	while($credit = $db->fetch_array($query)) {
		if($credit['type'] == "sent") {
			$memberquery = $db->query("SELECT username FROM $table_members WHERE uid='$credit[touid]'");
			$username = $db->result($memberquery, 0);

			$encodeuser = rawurlencode($creditto);

			$creditto = "<a href=\"member.php?action=viewpro&member=$encodeuser\">$username</a>";
		}

		if($credit['type'] == "shop") {
			$creditto = "Shop";
		}

		$credit['comment'] = stripslashes($credit['comment']);

		$date = gmdate("$dateformat", $credit['dateline'] + ($timeoffset * 3600));
		$time = gmdate("$timecode", $credit['dateline'] + ($timeoffset * 3600));

		eval("\$creditrows .= \"".template('shop_bank_credit_row')."\";");
	}

	if($limit == 5) {
		$limit5 = "selected";
	} elseif($limit == 10) {
		$limit10 = "selected";
	} else {
		$limit20 = "selected";
	}

	eval("\$bankpage = \"".template('shop_bank_credit')."\";");
}

// Debit

if($action == "debit") {
	if($limit != 10 && $limit != 20) {
		$limit = 5;
	}

	$debitrows = '';

	$query = $db->query("SELECT * FROM $table_shop_bank WHERE touid='$memberinfo[uid]' ORDER BY dateline DESC LIMIT 0, $limit");

	while($debit = $db->fetch_array($query)) {
		if($debit['type'] == "sent") {
			$memberquery = $db->query("SELECT username FROM $table_members WHERE uid='$debit[fromuid]'");
			$username = $db->result($memberquery, 0);

			$encodeuser = rawurlencode($debitto);

			$debitfrom = "<a href=\"member.php?action=viewpro&member=$encodeuser\">$username</a>";
		}

		if($debit['type'] == "shop") {
			$debitfrom = "Shop";
		}

		$debit['comment'] = stripslashes($debit['comment']);

		$date = gmdate("$dateformat", $debit['dateline'] + ($timeoffset * 3600));
		$time = gmdate("$timecode", $debit['dateline'] + ($timeoffset * 3600));

		eval("\$debitrows .= \"".template('shop_bank_debit_row')."\";");
	}

	if($limit == 5) {
		$limit5 = "selected";
	} elseif($limit == 10) {
		$limit10 = "selected";
	} else {
		$limit20 = "selected";
	}

	eval("\$bankpage = \"".template('shop_bank_debit')."\";");
}

// Send money

if($action == "send") {
	if(!$submit) {
		$sendoptions = '';

		$query = $db->query("SELECT uid, username FROM $table_members ORDER BY username");

		while($member = $db->fetch_array($query)) {
			$sendoptions .= "<option value=\"$member[uid]\">$member[username]</option>\n";
		}

		eval("\$bankpage = \"".template('shop_bank_send')."\";");
	} else {
		if($memberinfo['money'] >= $amount && $userid != "" && $amount > 0) {
			$comment = checkInput($comment, '', '', 'javascript');
			$comment = addslashes($comment);

			$thatime = time();

			$db->query("INSERT INTO $table_shop_bank VALUES('$memberinfo[uid]', '$userid', '$thatime', '$amount', '$comment', 'sent')");
			$db->query("UPDATE $table_members SET money=money+'$amount' WHERE uid='$userid'");
			$db->query("UPDATE $table_members SET money=money-'$amount' WHERE uid='$memberinfo[uid]'");

			$outputtext = $lang_shop_bank_send_update;
			eval("\$info = \"".template('shop_info')."\";");
			echo $info;
			?>

			<script>
			function redirect() {
				window.location.replace("shop_bank.php?action=send");
			}
			setTimeout("redirect();", 1250);
			</script>

			<?
		} else {
			$outputtext = $lang_shop_bank_send_noupdate;
			eval("\$info = \"".template('shop_info')."\";");
			echo $info;
			?>

			<script>
			function redirect() {
				window.location.replace("shop_bank.php?action=send");
			}
			setTimeout("redirect();", 1250);
			</script>

			<?
		}
	}
}

if(!$submit) {
	eval("\$bank = \"".template('shop_bank')."\";");
	echo $bank;
}

// Links

$displaylinks = '';

if($status == "Super Administrator" || $status == "Administrator") {
	$displaylinks = '<img src="images/shop/admin.gif" border="0" alt="'.$lang_shop_textadminpanel.'"> <a href="shop_admin.php">'.$lang_shop_textadminpanel.'</a> ';
}

if($set['search'] == "on") {
	$displaylinks .= '<img src="images/shop/search.gif" border="0" alt="'.$lang_shop_textsearch.'"> <a href="shop_search.php">'.$lang_shop_textsearch.'</a> ';
}

if($set['stats'] == "on") {
	$displaylinks .= '<img src="images/shop/stats.gif" border="0" alt="'.$lang_shop_textstats.'"> <a href="shop_stats.php">'.$lang_shop_textstats.'</a> ';
}

eval("\$links = \"".template('shop_links')."\";");
echo $links;

eval("\$shopfooter = \"".template('shop_footer')."\";");
echo $shopfooter;

end_time();

eval("\$footer = \"".template('footer')."\";");
echo $footer;

?>
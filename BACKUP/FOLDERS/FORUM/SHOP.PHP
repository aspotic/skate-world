<?php
/*
	SHOP HACK v1.2 by NFPUNK

	If you require support please post in the forum
	at www.flixon.com/shop/forum.  Thanks

	- nfpunk/Flixon
*/

require "./header.php";
loadtemplates('header,footer,css,shop_cats,shop_cats_row,shop_cats_auction_items_row,shop_cats_lastadd,shop_auction_items,shop_auction_items_row,shop_sell,shop_links,shop_info,shop_footer');

$setquery = $db->query("SELECT * FROM $table_shop_settings");
$set = $db->fetch_array($setquery);

if(!$action) {
	$navigation = "&raquo; $lang_shop_title";
} else {
	$navigation = "&raquo; <a href=\"shop.php\">$lang_shop_title</a>";
}

if($action == "auction_items") {
	$navigation .= " &raquo; $lang_shop_textauction_items";
} elseif($action == "sell") {
	$navigation .= " &raquo; $lang_shop_textsell";
}

eval("\$css = \"".template('css')."\";");

eval("\$header = \"".template('header')."\";");
echo $header;

if(!$xmbuser) {
	$xmblistname = "Annon,";
	$statuslistname = "Guest,";
} else {
	$memquery = $db->query("SELECT uid, money FROM $table_members WHERE username='$xmbuser'");
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

// Categories

if(!$action) {
	if(!$acceptrules && $set['shoprules'] == "on") {
		$set['shoprulestxt'] = stripslashes($set['shoprulestxt']);
		$set['shoprulestxt'] = nl2br($set['shoprulestxt']);

		eval("\$shoprules = \"".template('shop_rules')."\";");
		echo $shoprules;
		end_time();
		eval("\$shopfooter = \"".template('shop_footer')."\";");
		echo $shopfooter;
		eval("\$footer = \"".template('footer')."\";");
		echo $footer;
		exit();
	}

	$catsrows = '';

	$query = $db->query("SELECT * FROM $table_shop_cats WHERE cid='0' && status='on' ORDER BY displayorder, catname");

	while($cat = $db->fetch_array($query)) {
		if($cat['items'] >= $set['hotcat']) {
			$folder = "$imgdir/hot_folder.gif";
		} else {
			$folder = "$imgdir/folder.gif";
		}

		if($cat['lastadd'] != "") {
			$lastadd = explode("|", $cat['lastadd']);

			$encodeuser = rawurlencode($lastadd[1]);

			$lastadddate = gmdate($dateformat, $lastadd[0] + ($timeoffset * 3600) + ($addtime * 3600));
			$lastaddtime = gmdate($timecode, $lastadd[0] + ($timeoffset * 3600) + ($addtime * 3600));

			$lastadd = "$lastadddate $lang_shop_textat $lastaddtime<br>$lang_shop_textby <a href=\"$boardurl/member.php?action=viewpro&member=$encodeuser\">$lastadd[1]</a>";
			eval("\$lastaddrow = \"".template('shop_cats_lastadd')."\";");
		} else {
			$lastaddrow	= $lang_shop_textnever;
		}

		$cat['description'] = stripslashes($cat['description']);

		eval("\$catsrows .= \"".template('shop_cats_row')."\";");
	}

	if($set['auction_items'] == "on") {
		eval("\$catsrows .= \"".template('shop_cats_auction_items_row')."\";");
	}

	eval("\$cats = \"".template('shop_cats')."\";");
	echo $cats;

	if($xmbuser) {
		eval("\$userpanel = \"".template('shop_userpanel')."\";");
		echo $userpanel;
	}
}

// This may have a built in function but I'm not too familiar with unix timestamp functions

function timeleft($endtime) {
	$difference = $endtime - time();
	$diffdays = $difference / 86400;
	$days = floor($diffdays);

	$diffhours = ($diffdays - $days) * 24;
	$hours = floor($diffhours);

	$diffminutes = ($diffhours - $hours) * 60;
	$minutes = floor($diffminutes);

	if($difference <= 0) {
		$timeleft = "0";
	} else {
		$timeleft = "$days days $hours hours $minutes minutes";
	}

	return $timeleft;
}

// Auction Items

if($action == "auction_items") {
	if($set['auction_items'] != "on") {
		$outputtext = "$lang_shop_auction_items_off";
		eval("\$info = \"".template('shop_info')."\";");
		echo $info;
		end_time();
		eval("\$footer = \"".template('footer')."\";");
		echo $footer;
		exit();
	}

	$auction_itemsrows = '';

	$query = $db->query("SELECT * FROM $table_shop_auction_items ORDER BY endtime");

	while($auction_item = $db->fetch_array($query)) {
		$itemquery = $db->query("SELECT * FROM $table_shop_items WHERE id='$auction_item[iid]'");
		$item = $db->fetch_array($itemquery);

		if($item['imageurl'] == "") {
			if($item['sold'] >= $set['hotitem']) {
				$image = "$imgdir/hot_folder.gif";
			} else {
				$image = "$imgdir/folder.gif";
			}
		} else {
			$image = $item['imageurl'];
		}

		$timeleft = timeleft($auction_item['endtime']);

		$item['description'] = stripslashes($item['description']);

		if($auction_item['endtime'] <= time()) {
			$db->query("UPDATE $table_members SET money=money+'$auction_item[amount]' WHERE uid='$auction_item[uid]'");
			$db->query("UPDATE $table_members SET money=money-'$auction_item[amount]' WHERE uid='$auction_item[biduid]'");
			$db->query("INSERT INTO $table_member_items VALUES('$auction_item[iid]', '$auction_item[biduid]')");
			$db->query("DELETE FROM $table_shop_auction_items WHERE id='$auction_item[id]'");
		} else {
			eval("\$auction_itemsrows .= \"".template('shop_auction_items_row')."\";");
		}
	}

	if($auction_itemsrows) {
		eval("\$auction_items = \"".template('shop_auction_items')."\";");
		echo $auction_items;
	}
}

// Sell items

if($action == "sell") {
	if($set['sell'] != "on") {
		$outputtext = "$lang_shop_sell_off";
		eval("\$info = \"".template('shop_info')."\";");
		echo $info;
		end_time();
		eval("\$footer = \"".template('forum_footer')."\";");
		echo $footer;
		exit();
	}

	if(!$submit) {
		$selloptions = '';

		$query = $db->query("SELECT distinct * from $table_member_items mdb, $table_shop_items idb WHERE mdb.iid=idb.id && mdb.uid='$memberinfo[uid]' ORDER BY itemname");

		while($item = $db->fetch_array($query)) {
			$selloptions .= "<option value=\"$item[id]\">$item[itemname]</option>\n";
		}

		eval("\$sell .= \"".template('shop_sell')."\";");
		echo $sell;
	} else {
		$memberitemquery = $db->query("SELECT iid, uid FROM $table_member_items WHERE iid='$iid' && uid='$memberinfo[uid]'");
		$memberitem = $db->fetch_array($memberitemquery);

		$nbrows = $db->num_rows($memberitemquery);

		if($nbrows != 0) {
			$itemquery = $db->query("SELECT price FROM $table_shop_items WHERE id='$iid'");
			$item = $db->fetch_array($itemquery);

			$amount = ceil(($set['sellpercent'] / 100) * $item['price']);

			$db->query("UPDATE $table_shop_items SET stock=stock+1 WHERE id='$iid'");
			$db->query("DELETE FROM $table_member_items WHERE iid='$iid' && uid='$memberinfo[uid]'");
			$db->query("UPDATE $table_members SET money=money+'$amount' WHERE uid='$memberinfo[uid]'");

			$outputtext = "$lang_shop_sell_update $amount!";
			eval("\$info = \"".template('shop_info')."\";");
			echo $info;
			?>

			<script>
			function redirect() {
				window.location.replace("shop.php?action=sell");
			}
			setTimeout("redirect();", 1250);
			</script>

			<?
		} else {
			$outputtext = "$lang_shop_sell_noupdate";
			eval("\$info = \"".template('shop_info')."\";");
			echo $info;
			?>

			<script>
			function redirect() {
				window.location.replace("shop.php?action=sell");
			}
			setTimeout("redirect();", 1250);
			</script>

			<?
		}
	}
}

// Links

$displaylinks = '';

if($status == "Super Administrator" || $status == "Administrator") {
	$displaylinks = '<img src="images/shop/admin.gif" border="0" alt="'.$lang_shop_textadminpanel.'"> <a href="shop_admin.php">'.$lang_shop_textadminpanel.'</a> ';
}

if(!$action) {
	if(eregi($xmblistname, $set['whocreate']) || eregi($statuslistname, $set['whocreate'])) {
		$displaylinks .= '<img src="images/shop/newcat.gif" border="0" alt="'.$lang_shop_textnewcat.'"> <a href="shop_edit.php?action=newcat">'.$lang_shop_textnewcat.'</a> ';
	}
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
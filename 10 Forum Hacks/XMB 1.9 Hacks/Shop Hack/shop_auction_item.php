<?php
/*
	SHOP HACK v1.2 by NFPUNK

	If you require support please post in the forum
	at www.flixon.com/shop/forum.  Thanks

	- nfpunk/Flixon
*/

require "./header.php";
loadtemplates('header,footer,css,shop_auction_item,shop_auction_item_new,shop_links,shop_info,shop_footer');

$setquery = $db->query("SELECT * FROM $table_shop_settings");
$set = $db->fetch_array($setquery);

$auction_itemquery = $db->query("SELECT * FROM $table_shop_auction_items WHERE id='$aid'");

if($auction_item = $db->fetch_array($auction_itemquery)) {
	$itemquery = $db->query("SELECT * FROM $table_shop_items WHERE id='$auction_item[iid]'");
	$item = $db->fetch_array($itemquery);
}

$navigation = "&raquo; <a href=\"shop.php\">$lang_shop_title</a> &raquo; <a href=\"shop.php?action=auction_items\">$lang_shop_textauction_items</a>";

if(!$action) {
	$navigation .= " &raquo; $item[itemname]";
} else {
	$navigation .= " &raquo; $lang_shop_textnewauction_item";
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

if($set['auction_items'] != "on") {
	$outputtext = "$lang_shop_auction_items_off";
	eval("\$info = \"".template('shop_info')."\";");
	echo $info;
	end_time();
	eval("\$footer = \"".template('footer')."\";");
	echo $footer;
	exit();
}

if(!$action) {
	// Items

	if(!$submit) {
		if($item['imageurl'] == "") {
			$image = "$imgdir/spacer.gif";
		} else {
			$image = $item['imageurl'];
		}

		$minamount = $auction_item['amount'] + $auction_item['bidincrement'];

		// This may have a built in function but I'm not too familiar with unix timestamp functions

		function timeleft($endtime) {
			$difference = $endtime - time();
			$diffdays = $difference / 86400;
			$days = floor($diffdays);

			$diffhours = ($diffdays - $days) * 24;
			$hours = floor($diffhours);

			$diffminutes = ($diffhours - $hours) * 60;
			$minutes = floor($diffminutes);

			$diffseconds = ($diffminutes - $minutes) * 60;
			$seconds = floor($diffseconds);

			if($difference <= 0) {
				$timeleft = "0";
			} else {
				$timeleft = "$days days $hours hours $minutes minutes";
			}

			return $timeleft;
		}

		$timeleft = timeleft($auction_item['endtime']);

		$enddate = gmdate("$dateformat", $auction_item['endtime'] + ($timeoffset * 3600));
		$endtime = gmdate("$timecode", $auction_item['endtime'] + ($timeoffset * 3600));				

		$item['description'] = stripslashes($item['description']);

		eval("\$item2 = \"".template('shop_auction_item')."\";");
		echo $item2;
	} else {
		$amount = checkInput($amount, '', '', 'javascript');

		$itemsquery = $db->query("SELECT COUNT(*) FROM $table_member_items WHERE iid='$auction_item[iid]' && uid='$memberinfo[uid]'");
		$items = $db->result($itemsquery, 0);

		if($items != "0") {
			$outputtext = "$lang_shop_item_noupdate2";
			eval("\$info = \"".template('shop_info')."\";");
			echo $info;
			end_time();
			eval("\$footer = \"".template('footer')."\";");
			echo $footer;
			exit();
		}

		if($memberinfo['money'] >= $amount && $amount >= ($auction_item['amount'] + $auction_item['bidincrement'])) {
			$db->query("UPDATE $table_shop_auction_items SET bids=bids+1, amount='$amount', biduid='$memberinfo[uid]' WHERE id='$aid'");

			$outputtext = "$lang_shop_auction_item_bid_update";
			eval("\$info = \"".template('shop_info')."\";");
			echo $info;
			?>

			<script>
			function redirect() {
				window.location.replace("shop_auction_item.php?aid=<?=$aid?>");
			}
			setTimeout("redirect();", 1250);
			</script>

			<?
		} else {
			$outputtext = "$lang_shop_auction_item_bid_noupdate";
			eval("\$info = \"".template('shop_info')."\";");
			echo $info;
			?>

			<script>
			function redirect() {
				window.location.replace("shop_auction_item.php?aid=<?=$aid?>");
			}
			setTimeout("redirect();", 1250);
			</script>

			<?
		}
	}
}

if($action == "newitem") {
	if(!$submit) {
		$itemoptions = '';

		$query = $db->query("SELECT distinct * from $table_member_items mdb, $table_shop_items idb WHERE mdb.iid=idb.id && mdb.uid='$memberinfo[uid]' ORDER BY itemname");

		while($item = $db->fetch_array($query)) {
			$itemoptions .= "<option value=\"$item[id]\">$item[itemname]</option>\n";
		}

		eval("\$newitem = \"".template('shop_auction_item_new')."\";");
		echo $newitem;
	} else {
		$iid		= checkInput($iid, '', '', 'javascript');
		$duration	= checkInput($duration, '', '', 'javascript');
		$bidincrement	= checkInput($bidincrement, '', '', 'javascript');
		$startbid	= checkInput($startbid, '', '', 'javascript');

		$memberitemquery = $db->query("SELECT iid, uid FROM $table_member_items WHERE iid='$iid' && uid='$memberinfo[uid]'");
		$memberitem = $db->fetch_array($memberitemquery);

		$nbrows = $db->num_rows($memberitemquery);

		if($nbrows != 0) {
			$itemquery = $db->query("SELECT price FROM $table_shop_items WHERE id='$iid'");
			$item = $db->fetch_array($itemquery);

			if($duration != "3" && $duration != "5" && $duration != "7" && $duration != "10" || $iid == "" || $bidincrement == "" || $startbid == "") {
				$outputtext = "$lang_shop_auction_item_newitem_noupdate2";
				eval("\$info = \"".template('shop_info')."\";");
				echo $info;
				end_time();
				eval("\$footer = \"".template('footer')."\";");
				echo $footer;
				exit();
			}

			$thatime = time();

			$endtime = $thatime + ($duration * 86400); 

			$db->query("INSERT INTO $table_shop_auction_items VALUES('', '$iid', '$memberinfo[uid]', '$thatime', '$endtime', '$bidincrement', 0, '$startbid', '$memberinfo[uid]')");
			$db->query("DELETE FROM $table_member_items WHERE iid='$iid' && uid='$memberinfo[uid]'");

			$outputtext = "$lang_shop_auction_item_newitem_update";
			eval("\$info = \"".template('shop_info')."\";");
			echo $info;
			?>

			<script>
			function redirect() {
				window.location.replace("shop.php?action=auctionitem");
			}
			setTimeout("redirect();", 1250);
			</script>

			<?
		} else {
			$outputtext = "$lang_shop_auction_item_newitem_noupdate";
			eval("\$info = \"".template('shop_info')."\";");
			echo $info;
			?>

			<script>
			function redirect() {
				window.location.replace("shop.php?action=auctionitem");
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
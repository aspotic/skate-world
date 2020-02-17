<?php
/*
	SHOP HACK v1.2 by NFPUNK

	If you require support please post in the forum
	at www.flixon.com/shop/forum.  Thanks

	- nfpunk/Flixon
*/

require "./header.php";
loadtemplates('header,footer,css,shop_cat_password,shop_subcats,shop_subcats_row,shop_subcats_lastadd,shop_items,shop_items_row,shop_links,shop_info,shop_footer');

$setquery = $db->query("SELECT * FROM $table_shop_settings");
$set = $db->fetch_array($setquery);

$catquery = $db->query("SELECT * FROM $table_shop_cats WHERE id='$cid'");
$cat = $db->fetch_array($catquery);

$db->query("UPDATE $table_shop_cats SET views=views+1 WHERE id='$cid'");

if($cat['cid'] != 0) {
	$upcatquery = $db->query("SELECT id, catname FROM $table_shop_cats WHERE id='$cat[cid]'");
	$upcat = $db->fetch_array($upcatquery);

	$navigation = "&raquo; <a href=\"shop.php\">$lang_shop_title</a> &raquo; <a href=\"shop_category.php?cid=$upcat[id]\">$upcat[catname]</a> &raquo; $cat[catname]";
} else {
	$navigation = "&raquo; <a href=\"shop.php\">$lang_shop_title</a> &raquo; $cat[catname]";
}

eval("\$css = \"".template('css')."\";");

eval("\$header = \"".template('header')."\";");
echo $header;

if(!$xmbuser) {
	$xmblistname = "Annon,";
	$statuslistname = "Guest,";
} else {
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

if($cat['status'] != "on") {
	$outputtext = "$lang_shop_cat_off";
	eval("\$info = \"".template('shop_info')."\";");
	echo $info;
	end_time();
	eval("\$footer = \"".template('footer')."\";");
	echo $footer;
	exit();
}

if($cat['password'] != "" && $action == "pwverify") {
	$pw = md5($pw);

	if($cat['password'] != $pw) {
		$outputtext = "$lang_shop_password_invalid";
		eval("\$info = \"".template('shop_info')."\";");
		echo $info;
		end_time();
		eval("\$footer = \"".template('footer')."\";");
		echo $footer;
		exit();
	} else {
		setcookie("cidpw$cid", $pw, (time() + (86400*30)), $cookiepath, $cookiedomain);
		header("Location: shop_category.php?cid=$cid");
	}
}

if($cat['password'] != $HTTP_COOKIE_VARS["cidpw$cid"] && $cat['password'] != "") {
	eval("\$pwform = \"".template('shop_cat_password')."\";");
	echo $pwform;
	end_time();
	eval("\$footer = \"".template('footer')."\";");
	echo $footer;
	exit();
}

// Sub Categories

if($set['subcats'] == "on") {
	$subcatsrows = '';

	$query = $db->query("SELECT * FROM $table_shop_cats WHERE cid='$cid' && status='on' ORDER BY displayorder, catname");

	while($subcat = $db->fetch_array($query)) {
		if($subcat['items'] >= $set['hotcat']) {
			$folder = "$imgdir/hot_folder.gif";
		} else {
			$folder = "$imgdir/folder.gif";
		}

		if($subcat['lastadd'] != '') {
			$lastadd = explode("|", $subcat['lastadd']);

			$encodeuser = rawurlencode($lastadd['1']);

			$lastadddate = gmdate($dateformat, $lastadd[0] + ($timeoffset * 3600) + ($addtime * 3600));
			$lastaddtime = gmdate($timecode, $lastadd[0] + ($timeoffset * 3600) + ($addtime * 3600));

			$lastadd = "$lastadddate $lang_shop_textat $lastaddtime<br>$lang_shop_textby <a href=\"$boardurl/member.php?action=viewpro&member=$encodeuser\">$lastadd[1]</a>";
			eval("\$lastaddrow = \"".template('shop_subcats_lastadd')."\";");
		} else {
			$lastaddrow	= $lang_shop_textnever;
		}

		$subcat['description'] = stripslashes($subcat['description']);

		eval("\$subcatsrows .= \"".template('shop_subcats_row')."\";");
	}

	if($subcatsrows) {
		eval("\$subcats = \"".template('shop_subcats')."\";");
		echo $subcats;
	}
}

// Items

$itemsrows = '';

$query = $db->query("SELECT * FROM $table_shop_items WHERE cid='$cid' && status='on' ORDER BY displayorder, itemname");

while($item = $db->fetch_array($query)) {
	if($item['imageurl'] == "") {
		if($item['sold'] >= $set['hotitem']) {
			$image = "images/shop/noitempic.gif"; // Hot Item - Change this if you wish to use this feature
		} else {
			$image = "images/shop/noitempic.gif";
		}
	} else {
		$image = $item['imageurl'];
	}

	if($set['rating'] == "on") {
		if($item['votes'] != "0") {
			$num = ceil($item['rate'] / $item['votes']);

			$rate = " - ";

			for($i = 1; $i <= $num; $i++) {
				$rate .= "<img src=\"$imgdir/star.gif\" border=\"0\">";
			}

			$rate .= " ($lang_shop_textvotes: $item[votes])";
		} else {
			$rate = "";
		}
	}

	$item['description'] = stripslashes($item['description']);

	eval("\$itemsrows .= \"".template('shop_items_row')."\";");
}

if($itemsrows) {
	eval("\$items = \"".template('shop_items')."\";");
	echo $items;
}

// Links

$displaylinks = '';

if($status == "Super Administrator" || $status == "Administrator") {
	$displaylinks = '<img src="images/shop/admin.gif" border="0" alt="'.$lang_shop_textadminpanel.'"> <a href="shop_admin.php">'.$lang_shop_textadminpanel.'</a> ';
}

if(eregi($xmblistname, $set['whocreate']) || eregi($statuslistname, $set['whocreate'])) {
	if($set['subcats'] == "on" && $cat['cid'] == 0) {
		$displaylinks .= '<img src="images/shop/newcat.gif" border="0" alt="'.$lang_shop_textnewsubcat.'"> <a href="shop_edit.php?action=newsubcat&cid='.$cid.'">'.$lang_shop_textnewsubcat.'</a> ';
	}

	$displaylinks .= '<img src="images/shop/editcat.gif" border="0" alt="'.$lang_shop_texteditcat.'"> <a href="shop_edit.php?action=editcat&cid='.$cid.'">'.$lang_shop_texteditcat.'</a> ';
	$displaylinks .= '<img src="images/shop/delete.gif" border="0" alt="'.$lang_shop_textdeletecat.'"> <a href="shop_edit.php?action=deletecat&cid='.$cid.'">'.$lang_shop_textdeletecat.'</a> ';
}

if(eregi($xmblistname, $cat['whoadd']) || eregi($statuslistname, $set['whoadd'])) {
	$displaylinks .= '<img src="images/shop/newitem.gif" border="0" alt="'.$lang_shop_textnewitem.'"> <a href="shop_edit.php?action=newitem&cid='.$cid.'">'.$lang_shop_textnewitem.'</a> ';
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
<?php
/*
	SHOP HACK v1.2 by NFPUNK

	If you require support please post in the forum
	at www.flixon.com/shop/forum.  Thanks

	- nfpunk/Flixon
*/

require "./header.php";
loadtemplates('header,footer,css,shop_search,shop_search_results,shop_search_results_row,shop_search_results_none,shop_footer');

$setquery = $db->query("SELECT * FROM $table_shop_settings");
$set = $db->fetch_array($setquery);

$navigation = "&raquo; <a href=\"shop.php\">$lang_shop_title</a> &raquo; $lang_shop_textsearch";

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

if($set['search'] != "on") {
	$outputtext = "$lang_shop_search_off";
	eval("\$info = \"".template('shop_info')."\";");
	echo $info;
	end_time();
	eval("\$footer = \"".template('footer')."\";");
	echo $footer;
	exit();
}

if(!$submit) {
	eval("\$search = \"".template('shop_search')."\";");
	echo $search;
} else {
	$srchtxt = checkInput($srchtxt, '', '', 'javascript');

	$searchresultsrows = '';

	$query = $db->query("SELECT * FROM $table_shop_items WHERE itemname LIKE '%$srchtxt%' ORDER BY itemname");

	while($item = $db->fetch_array($query)) {
		eval("\$searchresultsrows .= \"".template('shop_search_results_row')."\";");
	}

	if(!isset($searchresultsrows)) {
		eval("\$searchresultsrows = \"".template('shop_search_results_none')."\";");
	}

	eval("\$searchresults = \"".template('shop_search_results')."\";");
	echo $searchresults;
}

// Links

$displaylinks = '';

if($status == "Super Administrator" || $status == "Administrator") {
	$displaylinks = '<img src="images/shop/admin.gif" border="0" alt="'.$lang_shop_textadminpanel.'"> <a href="shop_admin.php">'.$lang_shop_textadminpanel.'</a> ';
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
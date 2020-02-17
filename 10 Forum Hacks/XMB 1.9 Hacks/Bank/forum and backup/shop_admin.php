<?php
/*
	SHOP HACK v1.2 by NFPUNK

	If you require support please post in the forum
	at www.flixon.com/shop/forum.  Thanks

	- nfpunk/Flixon
*/

require "./header.php";
loadtemplates('header,footer,css,shop_admin,shop_admin_settings,shop_admin_cats,shop_admin_cats_row,shop_admin_cat_edit,shop_admin_subcats_row,shop_admin_items,shop_admin_items_row,shop_admin_item_edit,shop_admin_item_new,shop_info,shop_footer');

$setquery = $db->query("SELECT * FROM $table_shop_settings");
$set = $db->fetch_array($setquery);

$navigation = "&raquo; <a href=\"shop.php\">$lang_shop_title</a> &raquo; $lang_shop_textadminpanel";

eval("\$css = \"".template('css')."\";");

eval("\$header = \"".template('header')."\";");
echo $header;

if(!$xmbuser || !$xmbpw) {
	$xmbuser = "";
	$xmbpw = "";
	$status = "";
}

if($status != "Super Administrator" && $status != "Administrator") {
	eval("\$notadmin = \"".template('error_nologinsession')."\";");
	echo $notadmin;
	end_time();
	eval("\$footer = \"".template('footer')."\";");
	echo $footer;
	exit();
}

// Settings

if($action == "settings") {
	if(!$submit) {
		$ranks = array("Super_Administrator", "Administrator", "Super_Moderator", "Moderator", "Member");

		$whoview = preg_replace('/\W*$/', '', $set['whoview']); // Removes any non-word characters that appear at the end of the value
		$whoview = preg_replace('/, */', '|', $whoview); // Replaces all instances of commas and optional following spaces with pipe characters
		$whoview = preg_replace('/ /', '_', $whoview); // Replaces all spaces with underscores

		$whoview = explode('|', $whoview);

		foreach($whoview as $key => $value) {
			reset($ranks);

			if(array_search($value, $ranks) === FALSE) {
				$whoviewnew .= "$value, ";
			} else {
				$rank = "whoview$value";
				$$rank = "checked";
			}
		}

		$whocreate = preg_replace('/\W*$/', '', $set['whocreate']);
		$whocreate = preg_replace('/, */', '|', $whocreate);
		$whocreate = preg_replace('/ /', '_', $whocreate);

		$whocreate = explode('|', $whocreate);

		foreach($whocreate as $key => $value) {
			reset($ranks);

			if(array_search($value, $ranks) === FALSE) {
				$whocreatenew .= "$value, ";
			} else {
				$rank = "whocreate$value";
				$$rank = "checked";
			}
		}

		$whoadd = preg_replace('/\W*$/', '', $set['whoadd']);
		$whoadd = preg_replace('/, */', '|', $whoadd);
		$whoadd = preg_replace('/ /', '_', $whoadd);

		$whoadd = explode('|', $whoadd);

		foreach($whoadd as $key => $value) {
			reset($ranks);

			if(array_search($value, $ranks) === FALSE) {
				$whoaddnew .= "$value, ";
			} else {
				$rank = "whoadd$value";
				$$rank = "checked";
			}
		}

		if($set['shoprules'] == "on") {
			$shopruleson = "selected";
		} else {
			$shoprulesoff = "selected";
		}

		$set['shoprulestxt'] = stripslashes($set['shoprulestxt']);

		if($set['search'] == "on") {
			$searchon = "selected";
		} else {
			$searchoff = "selected";
		}

		if($set['stats'] == "on") {
			$statson = "selected";
		} else {
			$statsoff = "selected";
		}

		if($set['auction_items'] == "on") {
			$auction_itemson = "selected";
		} else {
			$auction_itemsoff = "selected";
		}

		if($set['sell'] == "on") {
			$sellon = "selected";
		} else {
			$selloff = "selected";
		}

		if($set['rating'] == "on") {
			$ratingon = "selected";
		} else {
			$ratingoff = "selected";
		}

		if($set['subcats'] == "on") {
			$subcatson = "selected";
		} else {
			$subcatsoff = "selected";
		}

		eval("\$page = \"".template('shop_admin_settings')."\";");
	} else {
		$ranks = array("Member", "Moderator", "Super_Moderator", "Administrator", "Super_Administrator");

		foreach($ranks as $key => $value) {
			$rank = "whoview$value";

			if($$rank == "yes") {
				$value = str_replace('_', ' ', $value);
				$whoviewnew = "$value, $whoviewnew";
			}
		}

		foreach($ranks as $key => $value) {
			$rank = "whocreate$value";

			if($$rank == "yes") {
				$value = str_replace('_', ' ', $value);
				$whocreatenew = "$value, $whocreatenew";
			}
		}

		foreach($ranks as $key => $value) {
			$rank = "whoadd$value";

			if($$rank == "yes") {
				$value = str_replace('_', ' ', $value);
				$whoaddnew = "$value, $whoaddnew";
			}
		}

		$shoprulestxtnew = addslashes($shoprulestxtnew);

		$db->query("UPDATE $table_shop_settings SET shoprules='$shoprulesnew', shoprulestxt='$shoprulestxtnew', whoview='$whoviewnew', whocreate='$whocreatenew', whoadd='$whoaddnew', search='$searchnew', stats='$statsnew', auction_items='$auction_itemsnew', sell='$sellnew', sellpercent='$sellpercentnew', rating='$ratingnew', subcats='$subcatsnew', maxitems='$maxitemsnew', hotcat='$hotcatnew', hotitem='$hotitemnew', maxwidth='$maxwidthnew', maxheight='$maxheightnew', maxsize='$maxsizenew', mpr='$mprnew', mpt='$mptnew', mpp='$mppnew', std='$stdnew', spd='$spdnew'");

		$outputtext = "$lang_shop_admin_settings_update";
		eval("\$info = \"".template('shop_info')."\";");
		echo $info;
		?>

		<script>
		function redirect() {
			window.location.replace("shop_admin.php?action=settings");
		}
		setTimeout("redirect();", 1250);
		</script>

		<?
	}
}

// Categories

if($action == "cats") {
	if(!$submit) {
		$catoptions = '';
		$subcatoptions = '';
		$catsrows = '';
		$subcatsrows = '';

		$query = $db->query("SELECT * FROM $table_shop_cats WHERE cid='0' ORDER BY displayorder, catname");

		while($cat = $db->fetch_array($query)) {
			$catoptions .= "<option value=\"$cat[id]\">$cat[catname]</option>\n";

			if($cat['status'] == "on") {
				$statuson = "selected";
			} else {
				$statusoff = "selected";
			}

			$subcatquery = $db->query("SELECT * FROM $table_shop_cats WHERE cid='$cat[id]' ORDER BY displayorder, catname");

			while($subcat = $db->fetch_array($subcatquery)) {
				if($subcat['status'] == "on") {
					$substatuson = "selected";
				} else {
					$substatusoff = "selected";
				}

				$subcatcatquery = $db->query("SELECT * FROM $table_shop_cats WHERE cid='0' ORDER BY displayorder, catname");

				while($subcatcat = $db->fetch_array($subcatcatquery)) {
					if($subcatcat['id'] == $subcat['cid']) {
						$subcatoptions .= "<option value=\"$subcatcat[id]\" selected>$subcatcat[catname]</option>\n";
					} else {
						$subcatoptions .= "<option value=\"$subcatcat[id]\">$subcatcat[catname]</option>\n";
					}
				}

				eval("\$subcatsrows .= \"".template('shop_admin_subcats_row')."\";");

				$substatuson = "";
				$substatusoff = "";
				$subcatoptions = "";
			}

			eval("\$catsrows .= \"".template('shop_admin_cats_row')."\";");

			$statuson = "";
			$statusoff = "";
			$subcatsrows = "";
		}

		eval("\$page = \"".template('shop_admin_cats')."\";");
	} else {
		$query = $db->query("SELECT * FROM $table_shop_cats");

		while($cat = $db->fetch_array($query)) {
			$catnamenew = "catname$cat[id]";
			$catnamenew = "${$catnamenew}";
			$displayordernew = "displayorder$cat[id]";
			$displayordernew = "${$displayordernew}";
			$statusnew = "status$cat[id]";
			$statusnew = "${$statusnew}";
			$delete = "delete$cat[id]";
			$delete = "${$delete}";

			$db->query("UPDATE $table_shop_cats SET catname='$catnamenew', displayorder='$displayordernew', status='$statusnew' WHERE id='$cat[id]'");

			if($delete == "yes") {
				$db->query("DELETE FROM $table_shop_cats WHERE id='$cat[id]'");

				$query2 = $db->query("SELECT * FROM $table_shop_items WHERE cid='$cat[id]'");

				while($item = $db->fetch_array($query2)) {
					unlink($item['image']);

					$db->query("DELETE FROM $table_shop_votes WHERE iid='$item[id]'");
					$db->query("DELETE FROM $table_shop_items WHERE id='$item[id]'");
					$db->query("DELETE FROM $table_member_items WHERE iid='$item[id]'");
				}
			}
		}

		if($newcatname != $lang_shop_textnewcat) {
			$query = $db->query("SELECT uid FROM $table_members WHERE username='$xmbuser'");
			$memberuid = $db->result($query, 0);

			$db->query("INSERT INTO $table_shop_cats VALUES('', '0', '$memberuid', '$newcatname', '', '$newcatorder', '$set[whoview]', '$set[whoadd]', '', '', '$newcatstatus', 'off', 0, 0, 'off')");
		}

		if($newsubcatname != $lang_shop_textnewsubcat) {
			$query = $db->query("SELECT uid FROM $table_members WHERE username='$xmbuser'");
			$memberuid = $db->result($query, 0);

			$db->query("INSERT INTO $table_shop_cats VALUES('', '$newsubcid', '$memberuid', '$newsubcatname', '', '$newsubcatorder', '$set[whoview]', '$set[whoadd]', '', '', '$newsubcatstatus', 'off', 0, 0, 'off')");
		}

		$outputtext = "$lang_shop_admin_cats_update";
		eval("\$info = \"".template('shop_info')."\";");
		echo $info;
		?>

		<script>
		function redirect() {
			window.location.replace("shop_admin.php?action=cats");
		}
		setTimeout("redirect();", 1250);
		</script>

		<?
	}
}

// Edit Category

if($action == "editcat") {
	if(!$submit) {
		$query = $db->query("SELECT * FROM $table_shop_cats WHERE id='$cid'");
		$cat = $db->fetch_array($query);

		if($cat['owner'] != 0) {
			$query = $db->query("SELECT username FROM $table_members WHERE uid='$cat[owner]'");
			$owner = $db->result($query, 0);
		} else {
			$owner = "";
		}

		$ranks = array("Super_Administrator", "Administrator", "Super_Moderator", "Moderator", "Member");

		$whoview = preg_replace('/\W*$/', '', $cat['whoview']); // Removes any non-word characters that appear at the end of the value
		$whoview = preg_replace('/, */', '|', $whoview); // Replaces all instances of commas and optional following spaces with pipe characters
		$whoview = preg_replace('/ /', '_', $whoview); // Replaces all spaces with underscores

		$whoview = explode('|', $whoview);

		foreach($whoview as $key => $value) {
			reset($ranks);

			if(array_search($value, $ranks) === FALSE) {
				$whoviewnew .= "$value, ";
			} else {
				$rank = "whoview$value";
				$$rank = "checked";
			}
		}

		$whoadd = preg_replace('/\W*$/', '', $cat['whoadd']);
		$whoadd = preg_replace('/, */', '|', $whoadd);
		$whoadd = preg_replace('/ /', '_', $whoadd);

		$whoadd = explode('|', $whoadd);

		foreach($whoadd as $key => $value) {
			reset($ranks);

			if(array_search($value, $ranks) === FALSE) {
				$whoaddnew .= "$value, ";
			} else {
				$rank = "whoadd$value";
				$$rank = "checked";
			}
		}

		if($cat['status'] == "on") {
			$statuson = "selected";
		} else {
			$statusoff = "selected";
		}

		if($cat['creatoronly'] == "on") {
			$creatoron = "selected";
		} else {
			$creatoroff = "selected";
		}

		if($cat['ownermoney'] == "on") {
			$ownermoneyon = "selected";
		} else {
			$ownermoneyoff = "selected";
		}

		$cat['description'] = stripslashes($cat['description']);

		eval("\$page = \"".template('shop_admin_cat_edit')."\";");
	} else {
		$catname	= checkInput($catname, '', '', 'javascript');
		$description	= checkInput($description, '', '', 'javascript');
		$owner		= checkInput($owner, '', '', 'javascript');
		$displayorder	= checkInput($displayorder, '', '', 'javascript');
		$catstatus	= checkInput($catstatus, '', '', 'javascript');

		$description = addslashes($description);

		$ranks = array("Member", "Moderator", "Super_Moderator", "Administrator", "Super_Administrator");

		foreach($ranks as $key => $value) {
			$rank = "whoview$value";

			if($$rank == "yes") {
				$value = str_replace('_', ' ', $value);
				$whoviewnew = "$value, $whoviewnew";
			}
		}

		foreach($ranks as $key => $value) {
			$rank = "whoadd$value";

			if($$rank == "yes") {
				$value = str_replace('_', ' ', $value);
				$whoaddnew = "$value, $whoaddnew";
			}
		}

		if(ereg('"', $password) || ereg("'", $password)) {
			$outputtext = "Invalid characters in password!";
			eval("\$info = \"".template('shop_info')."\";");
			echo $info;
			end_time();
			eval("\$footer = \"".template('footer')."\";");
			echo $footer;
			exit();
		} elseif($password != "") {
			$password = md5($password);
			$password = ", password='$password'";
		}

		if($owner != "") {
			$query = $db->query("SELECT uid FROM $table_members WHERE username='$owner'");
			$memberuid = $db->result($query, 0);
		} else {
			$memberuid = 0;
		}

		$db->query("UPDATE $table_shop_cats SET owner='$memberuid', catname='$catname', description='$description', displayorder='$displayorder', whoview='$whoviewnew', whoadd='$whoaddnew', status='$catstatus', creatoronly='$creatoronly', ownermoney='$ownermoney' $password WHERE id='$cid'");

		$outputtext = "$lang_shop_admin_editcat_update";
		eval("\$info = \"".template('shop_info')."\";");
		echo $info;
		?>

		<script>
		function redirect() {
			window.location.replace("shop_admin.php?action=cats");
		}
		setTimeout("redirect();", 1250);
		</script>

		<?
	}
}

if($action == "add") {
	if($status != "Super Administrator") {
	eval("\$notadmin = \"".template("error_nologinsession")."\";");
	echo $notadmin;
	end_time();
	eval("\$footer = \"".template("footer")."\";");
	echo $footer;
	exit();
}
	  ?>

		<tr bgcolor="<?=$altbg2?>">
		<td align="center">
		<br />
		<form method="post" action="shop_admin.php?action=add">
		<table cellspacing="0" cellpadding="0" border="0" width="200" align="center">
		<tr><td bgcolor="<?=$bordercolor?>">
	
		<table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">
	
		<tr>
		<td class="header" colspan="2"><?="Add how much money to each account status:"?></td>
		</tr>
	
		<tr>
		<td class="tablerow" bgcolor="<?=$altbg1?>"><?="Member:"?></td>
		<td align="right" bgcolor="<?=$altbg2?>"><input type="text" name="moneyadd" size="10" /></td>
		</tr>
		<tr>
		<td class="tablerow" bgcolor="<?=$altbg1?>"><?="Moderator:"?></td>
		<td align="right" bgcolor="<?=$altbg2?>"><input type="text" name="moneyadd2" size="10" /></td>
		</tr>
		<tr>
		<td class="tablerow" bgcolor="<?=$altbg1?>"><?="Super Moderator:"?></td>
		<td align="right" bgcolor="<?=$altbg2?>"><input type="text" name="moneyadd3" size="10" /></td>
		</tr>
		<tr>
		<td class="tablerow" bgcolor="<?=$altbg1?>"><?="Administrator:"?></td>
		<td align="right" bgcolor="<?=$altbg2?>"><input type="text" name="moneyadd4" size="10" /></td>
		</tr>
		<tr>
		<td class="tablerow" bgcolor="<?=$altbg1?>"><?="Super Administrator:"?></td>
		<td align="right" bgcolor="<?=$altbg2?>"><input type="text" name="moneyadd5" size="10" /></td>
		</tr>

		</table>
		</td></tr></table>
		<center><br /><input type="submit" name="moneyaddsubmit" value="Submit Changes" /></center>
		</form>
	
		</td>
		</tr>
		
		<?
    if($moneyaddsubmit) {
                $db->query("UPDATE $table_members SET money=money+'$moneyadd' WHERE status='Member'");
		$db->query("UPDATE $table_members SET money=money+'$moneyadd2' WHERE status='Moderator'");
		$db->query("UPDATE $table_members SET money=money+'$moneyadd3' WHERE status='Super Moderator'");
		$db->query("UPDATE $table_members SET money=money+'$moneyadd4' WHERE status='Administrator'");
		$db->query("UPDATE $table_members SET money=money+'$moneyadd5' WHERE status='Super Administrator'");
		?>

		<script>
		function redirect() {
			window.location.replace("shop_admin.php");
		}
		setTimeout("redirect();", 1250);
		</script>

		<?
	}
}

// Items

if($action == "items") {
	if(!$submit) {
		$catoptions = '';

		$query = $db->query("SELECT * FROM $table_shop_cats WHERE cid='0' ORDER BY displayorder, catname");

		while($cat = $db->fetch_array($query)) {
			if($cat['id'] == $cid) {
				$catoptions .= "<option value=\"$cat[id]\" selected>$cat[catname]</option>\n";
			} else {
				$catoptions .= "<option value=\"$cat[id]\">$cat[catname]</option>\n";
			}

			$query2 = $db->query("SELECT * FROM $table_shop_cats WHERE cid='$cat[id]' ORDER BY displayorder, catname");

			while($subcat = $db->fetch_array($query2)) {
				if($subcat['id'] == $cid) {
					$catoptions .= "<option value=\"$subcat[id]\" selected> - $subcat[catname]</option>\n";
				} else {
					$catoptions .= "<option value=\"$subcat[id]\"> - $subcat[catname]</option>\n";
				}
			}
		}

		if($cid) {
			$itemsrows = '';

			$query = $db->query("SELECT * FROM $table_shop_items WHERE cid='$cid' ORDER BY displayorder, itemname");

			while($item = $db->fetch_array($query)) {
				if($item['status'] == "on") {
					$statuson = "selected";
				} else {
					$statusoff = "selected";
				}

				eval("\$itemsrows .= \"".template('shop_admin_items_row')."\";");

				$statuson = "";
				$statusoff = "";
			}
		}

		eval("\$page = \"".template('shop_admin_items')."\";");
	} else {
		$query = $db->query("SELECT * FROM $table_shop_items WHERE cid='$cid'");

		while($item = $db->fetch_array($query)) {
			$catidquery = $db->query("SELECT cid FROM $table_shop_cats WHERE id='$item[cid]'");
			$catid = $db->result($catidquery, 0);

			$itemnamenew = "itemname$item[id]";
			$itemnamenew = "${$itemnamenew}";
			$displayordernew = "displayorder$item[id]";
			$displayordernew = "${$displayordernew}";
			$statusnew = "status$item[id]";
			$statusnew = "${$statusnew}";
			$delete = "delete$item[id]";
			$delete = "${$delete}";

			$db->query("UPDATE $table_shop_items SET itemname='$itemnamenew', displayorder='$displayordernew', status='$statusnew' WHERE id='$item[id]'");

			if($delete == "yes") {
				unlink($item['imageurl']);

				$db->query("DELETE FROM $table_shop_votes WHERE iid='$item[id]'");
				$db->query("DELETE FROM $table_shop_items WHERE id='$item[id]'");
				$db->query("DELETE FROM $table_member_items WHERE iid='$item[id]'");
				$db->query("UPDATE $table_shop_cats SET items=items-1 WHERE id='$item[cid]'");

				if($catid != 0) {
					$db->query("UPDATE $table_shop_cats SET items=items-1 WHERE id='$catid'");
				}
			}
		}

		$outputtext = "$lang_shop_admin_items_update";
		eval("\$info = \"".template('shop_info')."\";");
		echo $info;
		?>

		<script>
		function redirect() {
			window.location.replace("shop_admin.php?action=items");
		}
		setTimeout("redirect();", 1250);
		</script>

		<?
	}
}

// New Item

if($action == "newitem") {
	if(!$submit) {
		$catoptions = '';

		$query = $db->query("SELECT * FROM $table_shop_cats WHERE cid='0' ORDER BY displayorder, catname");

		while($cat = $db->fetch_array($query)) {
			$catoptions .= "<option value=\"$cat[id]\">$cat[catname]</option>\n";

			$subcatquery = $db->query("SELECT * FROM $table_shop_cats WHERE cid='$cat[id]' ORDER BY displayorder, catname");

			while($subcat = $db->fetch_array($subcatquery)) {
				$catoptions .= "<option value=\"$subcat[id]\"> - $subcat[catname]</option>\n";
			}
		}

		eval("\$page = \"".template('shop_admin_item_new')."\";");
	} else {
		$cid		= checkInput($cid, '', '', 'javascript');
		$itemname	= checkInput($itemname, '', '', 'javascript');
		$description	= checkInput($description, '', '', 'javascript');
		$displayorder	= checkInput($displayorder, '', '', 'javascript');
		$flocation	= checkInput($flocation, '', '', 'javascript');
		$itemstatus	= checkInput($itemstatus, '', '', 'javascript');
		$price		= checkInput($price, '', '', 'javascript');
		$stock		= checkInput($stock, '', '', 'javascript');

		$description = addslashes($description);

		$filename = 1;

		$query = $db->query ("SELECT id FROM $table_shop_items ORDER BY id DESC LIMIT 1");

		while($nb = $db->fetch_array($query)) {
			$filename = $nb['id'] + 1;
		}

		$upload_file = $HTTP_POST_FILES['flocation']['name'];
		$temp = $HTTP_POST_FILES['flocation']['tmp_name'];
		$extension = substr($upload_file, -4);

		if($upload_file == "") {
			$imageurl = "";
		} else {
			if($extension == ".jpg" || $extension == ".png" || $extension == ".gif" || $extension == ".JPG" || $extension == ".PNG" || $extension == ".GIF" || $extension == "jpeg" || $extension == "JPEG") {
				if($extension == "JPEG" xor $extension == "jpeg") {
					$extension = ".jpeg";
				}

				$imageurl = "images/items/$filename$extension";
				move_uploaded_file($temp, $imageurl);

				$size = filesize($imageurl);

				if($size > $set['maxsize']) {
					unlink($imageurl);
					$outputtext = "$lang_shop_admin_item_toobig $set[maxsize] bytes!";
					eval("\$info = \"".template('shop_info')."\";");
					echo $info;
					end_time();
					eval("\$footer = \"".template('footer')."\";");
					echo $footer;
					exit();
				}

				$SizeArray = getimagesize($imageurl);

				if($SizeArray[0] > $set['maxwidth'] || $SizeArray[1] > $set['maxheight']) {
					unlink($imageurl);
					$outputtext = "$lang_shop_admin_item_toobig $set[maxwidth] * $set[maxheight]!";
					eval("\$info = \"".template('shop_info')."\";");
					echo $info;
					end_time();
					eval("\$footer = \"".template('footer')."\";");
					echo $footer;
					exit();
				}
			} else {
				$outputtext = "$lang_shop_item_wrongtype";
				eval("\$info = \"".template('shop_info')."\";");
				echo $info;
				end_time();
				eval("\$footer = \"".template('footer')."\";");
				echo $footer;
				exit();
			}
		}

		$thatime = time();

		$query = $db->query("SELECT uid FROM $table_members WHERE username='$xmbuser'");
		$memberuid = $db->result($query, 0);

		$db->query("INSERT INTO $table_shop_items VALUES('', '$cid', '', '$memberuid', '$itemname', '$description', '$displayorder', '$imageurl', '$thatime', '$itemstatus', 0, 0, 0, 0, '$price', '$stock')");
		$db->query("UPDATE $table_shop_cats SET lastadd='$thatime|$xmbuser', items=items+1 WHERE id='$cid'");

		if($cat['cid'] != 0) {
			$db->query("UPDATE $table_shop_cats SET items=items+1 WHERE id='$cat[cid]'");
		}

		$outputtext = "$lang_shop_admin_newitem_update";
		eval("\$info = \"".template('shop_info')."\";");
		echo $info;
		?>

		<script>
		function redirect() {
			window.location.replace("shop_admin.php?action=newitem");
		}
		setTimeout("redirect();", 1250);
		</script>

		<?
	}
}

// Edit Item

if($action == "edititem") {
	if(!$submit) {
		$query = $db->query("SELECT * FROM $table_shop_items WHERE id='$iid'");
		$item = $db->fetch_array($query);

		$catoptions = '';

		$query = $db->query("SELECT * FROM $table_shop_cats WHERE cid='0' ORDER BY displayorder, catname");

		while($cat = $db->fetch_array($query)) {
			if($item['cid'] == $cat['id']) {
				$catoptions .= "<option value=\"$cat[id]\" selected>$cat[catname]</option>\n";
			} else {
				$catoptions .= "<option value=\"$cat[id]\">$cat[catname]</option>\n";
			}

			$subcatquery = $db->query("SELECT * FROM $table_shop_cats WHERE cid='$cat[id]' ORDER BY displayorder, catname");

			while($subcat = $db->fetch_array($subcatquery)) {
				if($item['cid'] == $subcat['id']) {
					$catoptions .= "<option value=\"$subcat[id]\" selected>$subcat[catname]</option>\n";
				} else {
					$catoptions .= "<option value=\"$subcat[id]\">$subcat[catname]</option>\n";
				}
			}
		}

		if($item['owner'] != 0) {
			$query = $db->query("SELECT username FROM $table_members WHERE uid='$item[owner]'");
			$owner = $db->result($query, 0);
		} else {
			$owner = "";
		}

		if($item['status'] == "on") {
			$statuson = "selected";
		} else {
			$statusoff = "selected";
		}

		$item['description'] = stripslashes($item['description']);

		eval("\$page = \"".template('shop_admin_item_edit')."\";");
	} else {
		$query = $db->query("SELECT * FROM $table_shop_items WHERE id='$iid'");
		$item = $db->fetch_array($query);

		$cid		= checkInput($cid, '', '', 'javascript');
		$itemname	= checkInput($itemname, '', '', 'javascript');
		$description	= checkInput($description, '', '', 'javascript');
		$owner		= checkInput($owner, '', '', 'javascript');
		$displayorder	= checkInput($displayorder, '', '', 'javascript');
		$flocation	= checkInput($flocation, '', '', 'javascript');
		$itemstatus	= checkInput($itemstatus, '', '', 'javascript');
		$price		= checkInput($price, '', '', 'javascript');
		$stock		= checkInput($stock, '', '', 'javascript');

		$description = addslashes($description);

		$filename = 1;

		$query = $db->query ("SELECT id FROM $table_shop_items ORDER BY id DESC LIMIT 1");

		while($nb = $db->fetch_array($query)) {
			$filename = $nb['id'] + 1;
		}

		$upload_file = $HTTP_POST_FILES['flocation']['name'];
		$temp = $HTTP_POST_FILES['flocation']['tmp_name'];
		$extension = substr($upload_file, -4);

		if($upload_file == "") {
			$image = "";
		} else {
			if($extension == ".jpg" || $extension == ".png" || $extension == ".gif" || $extension == ".JPG" || $extension == ".PNG" || $extension == ".GIF" || $extension == "jpeg" || $extension == "JPEG") {
				if($extension == "JPEG" xor $extension == "jpeg") {
					$extension = ".jpeg";
				}

				$imageurl = "images/items/$filename$extension";
				move_uploaded_file($temp, $imageurl);

				$size = filesize($imageurl);

				if($size > $set['maxsize']) {
					unlink($imageurl);
					$outputtext = "$lang_shop_admin_item_toobig $set[maxsize] bytes!";
					eval("\$info = \"".template('shop_info')."\";");
					echo $info;
					end_time();
					eval("\$footer = \"".template('footer')."\";");
					echo $footer;
					exit();
				}

				$SizeArray = getimagesize($imageurl);

				if($SizeArray[0] > $set['maxwidth'] || $SizeArray[1] > $set['maxheight']) {
					unlink($imageurl);
					$outputtext = "$lang_shop_admin_item_toobig $set[maxwidth] * $set[maxheight]!";
					eval("\$info = \"".template('shop_info')."\";");
					echo $info;
					end_time();
					eval("\$footer = \"".template('footer')."\";");
					echo $footer;
					exit();
				}

				unlink($item['imageurl']);
				$image = ", imageurl='$imageurl'";
			} else {
				$outputtext = "$lang_shop_item_wrongtype";
				eval("\$info = \"".template('shop_info')."\";");
				echo $info;
				end_time();
				eval("\$footer = \"".template('footer')."\";");
				echo $footer;
				exit();
			}
		}

		if($owner != "") {
			$query = $db->query("SELECT uid FROM $table_members WHERE username='$owner'");
			$memberuid = $db->result($query, 0);
		} else {
			$memberuid = 0;
		}

		$db->query("UPDATE $table_shop_items SET cid='$cid', owner='$memberuid', itemname='$itemname', description='$description', displayorder='$displayorder', status='$itemstatus', price='$price', stock='$stock' $image WHERE id='$iid'");

		$outputtext = "$lang_shop_admin_edititem_update";
		eval("\$info = \"".template('shop_info')."\";");
		echo $info;
		?>

		<script>
		function redirect() {
			window.location.replace("shop_admin.php?action=items");
		}
		setTimeout("redirect();", 1250);
		</script>

		<?
	}
}

if(!$submit) {
	eval("\$adminshop = \"".template('shop_admin')."\";");
	echo $adminshop;
}

eval("\$shopfooter = \"".template('shop_footer')."\";");
echo $shopfooter;

end_time();

eval("\$footer = \"".template('footer')."\";");
echo $footer;

?>
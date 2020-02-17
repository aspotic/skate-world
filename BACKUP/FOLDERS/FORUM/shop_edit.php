<?php
/*
	SHOP HACK v1.2 by NFPUNK

	If you require support please post in the forum
	at www.flixon.com/shop/forum.  Thanks

	- nfpunk/Flixon
*/

require "./header.php";
loadtemplates('header,footer,css,shop_edit_cat_new,shop_edit_cat_edit,shop_edit_cat_delete,shop_edit_subcat_new,shop_edit_item_new,shop_edit_item_edit,shop_edit_item_delete,shop_info,shop_footer');

$setquery = $db->query("SELECT * FROM $table_shop_settings");
$set = $db->fetch_array($setquery);

$navigation = "&raquo; <a href=\"shop.php\">$lang_shop_title</a> &raquo; $lang_shop_texteditshop";

eval("\$css = \"".template('css')."\";");

eval("\$header = \"".template('header')."\";");
echo $header;

if(!$xmbuser || !$xmbpw) {
	$xmbuser = "";
	$xmbpw = "";
	$status = "";
}

if(!$xmbuser) {
	$xmblistname = "Annon,";
	$statuslistname = "Guest,";
} else {
	$memquery = $db->query("SELECT uid FROM $table_members WHERE username='$xmbuser'");
	$memberuid = $db->result($memquery, 0);

	$xmblistname = $xmbuser . ",";
	$statuslistname = $status . ",";
}

// New Category

if($action == "newcat") {
	if(!eregi($xmblistname, $set['whocreate']) && !eregi($statuslistname, $set['whocreate'])) {
		$outputtext = "$lang_shop_admin_newcat_noupdate";
		eval("\$info = \"".template('shop_info')."\";");
		echo $info;
		end_time();
		eval("\$footer = \"".template('footer')."\";");
		echo $footer;
		exit();
	}

	if(!$submit) {
		eval("\$newcat = \"".template('shop_edit_cat_new')."\";");
		echo $newcat;
	} else {
		$catname	= checkInput($catname, '', '', 'javascript');
		$description	= checkInput($description, '', '', 'javascript');
		$displayorder	= checkInput($displayorder, '', '', 'javascript');
		$catstatus	= checkInput($catstatus, '', '', 'javascript');

		$description = addslashes($description);

		if(ereg('"', $password) || ereg("'", $password)) {
			$outputtext = "$lang_shop_admin_password_invalid";
			eval("\$info = \"".template('shop_info')."\";");
			echo $info;
			end_time();
			eval("\$footer = \"".template('footer')."\";");
			echo $footer;
			exit();
		} elseif($password != "") {
			$password = md5($password);
		}

		$db->query("INSERT INTO $table_shop_cats VALUES('', '0', '$memberuid', '$catname', '$description', '$displayorder', '$set[whoview]', '$set[whoadd]', '', '$password', '$catstatus', 'off', 0, 0, 'off')");

		$outputtext = "$lang_shop_admin_newcat_update";
		eval("\$info = \"".template('shop_info')."\";");
		echo $info;
		?>

		<script>
		function redirect() {
			window.location.replace("shop_edit.php?action=newcat");
		}
		setTimeout("redirect();", 1250);
		</script>

		<?
	}
}

// Edit Category

if($action == "editcat") {
	$catquery = $db->query("SELECT * FROM $table_shop_cats WHERE id='$cid'");
	$cat = $db->fetch_array($catquery);

	if($cat['creatoronly'] == "on" && $cat['owner'] != $memberuid && $status != "Super Administrator" && $status != "Administrator" && $status != "Super Moderator" && $status != "Moderator" || !eregi($xmblistname, $cat['whoadd']) && !eregi($statuslistname, $cat['whoadd'])) {
		$outputtext = "$lang_shop_admin_editcat_noupdate";
		eval("\$info = \"".template('shop_info')."\";");
		echo $info;
		end_time();
		eval("\$footer = \"".template('footer')."\";");
		echo $footer;
		exit();
	}

	if(!$submit) {
		$query = $db->query("SELECT * FROM $table_shop_cats WHERE id='$cid'");
		$cat = $db->fetch_array($query);

		if($cat['status'] == "on") {
			$statuson = "selected";
		} else {
			$statusoff = "selected";
		}

		$cat['description'] = stripslashes($cat['description']);

		eval("\$editcat = \"".template('shop_edit_cat_edit')."\";");
		echo $editcat;
	} else {
		$catname	= checkInput($catname, '', '', 'javascript');
		$description	= checkInput($description, '', '', 'javascript');
		$displayorder	= checkInput($displayorder, '', '', 'javascript');
		$catstatus	= checkInput($catstatus, '', '', 'javascript');

		$description = addslashes($description);

		if(ereg('"', $password) || ereg("'", $password)) {
			$outputtext = "$lang_shop_admin_password_invalid";
			eval("\$info = \"".template('shop_info')."\";");
			echo $info;
			end_time();
			eval("\$footer = \"".template('footer')."\";");
			echo $footer;
			exit();
		} else if($password != "") {
			$password = md5($password);
			$password = ", password='$password'";
		}

		$db->query("UPDATE $table_shop_cats SET catname='$catname', description='$description', displayorder='$displayorder', status='$catstatus' $password WHERE id='$cid'");

		$outputtext = "$lang_shop_admin_editcat_update";
		eval("\$info = \"".template('shop_info')."\";");
		echo $info;
		?>

		<script>
		function redirect() {
			window.location.replace("shop_category.php?cid=<?=$cid?>");
		}
		setTimeout("redirect();", 1250);
		</script>

		<?
	}
}

// Delete Category

if($action == "deletecat") {
	$catquery = $db->query("SELECT * FROM $table_shop_cats WHERE id='$cid'");
	$cat = $db->fetch_array($catquery);

	if($cat['creatoronly'] == "on" && $cat['owner'] != $memberuid && $status != "Super Administrator" && $status != "Administrator" && $status != "Super Moderator" && $status != "Moderator" || !eregi($xmblistname, $cat['whoadd']) && !eregi($statuslistname, $cat['whoadd'])) {
		$outputtext = "$lang_shop_admin_deletecat_noupdate";
		eval("\$info = \"".template('shop_info')."\";");
		echo $info;
		end_time();
		eval("\$footer = \"".template('footer')."\";");
		echo $footer;
		exit();
	}

	if(!$submit) {
		eval("\$deletecat = \"".template('shop_edit_cat_delete')."\";");
		echo $deletecat;
	} else {
		$db->query("DELETE FROM $table_shop_cats WHERE id='$cid'");

		$itemquery = $db->query("SELECT * FROM $table_shop_items WHERE cid='$cid'");

		while($item = $db->fetch_array($itemquery)) {
			unlink($item['image']);

			$db->query("DELETE FROM $table_shop_votes WHERE iid='$item[id]'");
			$db->query("DELETE FROM $table_shop_items WHERE id='$item[id]'");
			$db->query("DELETE FROM $table_member_items WHERE iid='$item[id]'");
		}

		$outputtext = "$lang_shop_admin_deletecat_update";
		eval("\$info = \"".template('shop_info')."\";");
		echo $info;
		?>

		<script>
		function redirect() {
			window.location.replace("shop.php");
		}
		setTimeout("redirect();", 1250);
		</script>

		<?
	}
}

// New Sub Category

if($action == "newsubcat") {
	$catquery = $db->query("SELECT * FROM $table_shop_cats WHERE id='$cid'");
	$cat = $db->fetch_array($catquery);

	if($cat['creatoronly'] == "on" && $cat['owner'] != $memberuid && $status != "Super Administrator" && $status != "Administrator" && $status != "Super Moderator" && $status != "Moderator" || !eregi($xmblistname, $cat['whoadd']) && !eregi($statuslistname, $cat['whoadd'])) {
		$outputtext = "$lang_shop_admin_newsubcat_noupdate";
		eval("\$info = \"".template('shop_info')."\";");
		echo $info;
		end_time();
		eval("\$footer = \"".template('footer')."\";");
		echo $footer;
		exit();
	}

	if(!$submit) {
		eval("\$newsubcat = \"".template('shop_edit_subcat_new')."\";");
		echo $newsubcat;
	} else {
		$cid		= checkInput($cid, '', '', 'javascript');
		$catname	= checkInput($catname, '', '', 'javascript');
		$description	= checkInput($description, '', '', 'javascript');
		$displayorder	= checkInput($displayorder, '', '', 'javascript');
		$password	= checkInput($password, '', '', 'javascript');
		$catstatus	= checkInput($catstatus, '', '', 'javascript');

		$description = addslashes($description);

		$db->query("INSERT INTO $table_shop_cats VALUES('', '$cid', '$memberuid', '$catname', '$description', '$displayorder', '$set[whoview]', '$set[whoadd]', '', '$password', '$catstatus', 'off', 0, 0, 'off')");

		$outputtext = "$lang_shop_admin_newsubcat_update";
		eval("\$info = \"".template('shop_info')."\";");
		echo $info;
		?>

		<script>
		function redirect() {
			window.location.replace("shop_category.php?cid=<?=$cid?>");
		}
		setTimeout("redirect();", 1250);
		</script>

		<?
	}
}

// New Item

if($action == "newitem") {
	$catquery = $db->query("SELECT * FROM $table_shop_cats WHERE id='$cid'");
	$cat = $db->fetch_array($catquery);

	if($cat['creatoronly'] == "on" && $cat['owner'] != $memberuid && $status != "Super Administrator" && $status != "Administrator" && $status != "Super Moderator" && $status != "Moderator" || !eregi($xmblistname, $cat['whoadd']) && !eregi($statuslistname, $cat['whoadd'])) {
		$outputtext = "$lang_shop_admin_newitem_noupdate";
		eval("\$info = \"".template('shop_info')."\";");
		echo $info;
		end_time();
		eval("\$footer = \"".template('footer')."\";");
		echo $footer;
		exit();
	}

	$query = $db->query("SELECT COUNT(*) FROM $table_shop_items WHERE cid='$cid' && owner='$memberuid'");
	$items = $db->result($query, 0);

	if($items >= $set['maxitems'] && $status != "Super Administrator" && $status != "Administrator" && $status != "Super Moderator" && $status != "Moderator") {
		$outputtext = "$lang_shop_admin_newitem_limit $set[maxitems]!";
		eval("\$info = \"".template('shop_info')."\";");
		echo $info;
		end_time();
		eval("\$footer = \"".template('footer')."\";");
		echo $footer;
		exit();
	}

	if(!$submit) {
		eval("\$newitem = \"".template('shop_edit_item_new')."\";");
		echo $newitem;
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
			if($extension == ".jpg" XOR $extension == ".png" XOR $extension == ".gif" XOR $extension == ".JPG" XOR $extension == ".PNG" XOR $extension == ".GIF" XOR $extension == "jpeg" XOR $extension == "JPEG") {
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
			window.location.replace("shop_edit.php?action=newitem&cid=<?=$cid?>");
		}
		setTimeout("redirect();", 1250);
		</script>

		<?
	}
}

// Edit Item

if($action == "edititem") {
	$query = $db->query("SELECT * FROM $table_shop_items WHERE id='$iid'");
	$item = $db->fetch_array($query);

	$catquery = $db->query("SELECT * FROM $table_shop_cats WHERE id='$item[cid]'");
	$cat = $db->fetch_array($catquery);

	if($cat['creatoronly'] == "on" && $cat['owner'] != $memberuid && $status != "Super Administrator" && $status != "Administrator" && $status != "Super Moderator" && $status != "Moderator" || !eregi($xmblistname, $cat['whoadd']) && !eregi($statuslistname, $cat['whoadd'])) {
		$outputtext = "$lang_shop_admin_edititem_noupdate";
		eval("\$info = \"".template('shop_info')."\";");
		echo $info;
		end_time();
		eval("\$footer = \"".template('footer')."\";");
		echo $footer;
		exit();
	}

	if(!$submit) {
		if($item['status'] == "on") {
			$statuson = "selected";
		} else {
			$statusoff = "selected";
		}

		$item['description'] = stripslashes($item['description']);

		eval("\$edititem = \"".template('shop_edit_item_edit')."\";");
		echo $edititem;
	} else {
		$query = $db->query("SELECT * FROM $table_shop_items WHERE id='$iid'");
		$item = $db->fetch_array($query);

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

		$db->query("UPDATE $table_shop_items SET itemname='$itemname', description='$description', displayorder='$displayorder', status='$itemstatus', price='$price', stock='$stock' $image WHERE id='$iid'");

		$outputtext = "$lang_shop_admin_edititem_update";
		eval("\$info = \"".template('shop_info')."\";");
		echo $info;
		?>

		<script>
		function redirect() {
			window.location.replace("shop_item.php?iid=<?=$iid?>");
		}
		setTimeout("redirect();", 1250);
		</script>

		<?
	}
}

// Delete Item

if($action == "deleteitem") {
	$query = $db->query("SELECT * FROM $table_shop_items WHERE id='$iid'");
	$item = $db->fetch_array($query);

	$catquery = $db->query("SELECT * FROM $table_shop_cats WHERE id='$item[cid]'");
	$cat = $db->fetch_array($catquery);

	if($cat['creatoronly'] == "on" && $cat['owner'] != $memberuid && $status != "Super Administrator" && $status != "Administrator" && $status != "Super Moderator" && $status != "Moderator" || !eregi($xmblistname, $cat['whoadd']) && !eregi($statuslistname, $cat['whoadd'])) {
		$outputtext = "$lang_shop_admin_deleteitem_noupdate";
		eval("\$info = \"".template('shop_info')."\";");
		echo $info;
		end_time();
		eval("\$footer = \"".template('footer')."\";");
		echo $footer;
		exit();
	}

	if(!$submit) {
		eval("\$deleteitem = \"".template('shop_edit_item_delete')."\";");
		echo $deleteitem;
	} else {
		unlink($item['imageurl']);

		$db->query("DELETE FROM $table_shop_votes WHERE iid='$iid'");
		$db->query("DELETE FROM $table_shop_items WHERE id='$iid'");
		$db->query("DELETE FROM $table_member_items WHERE iid='$iid'");
		$db->query("UPDATE $table_shop_cats SET items=items-1 WHERE id='$item[cid]'");

		if($cat['cid'] != 0) {
			$db->query("UPDATE $table_shop_cats SET items=items-1 WHERE id='$cat[cid]'");
		}

		$outputtext = "$lang_shop_admin_deleteitem_update";
		eval("\$info = \"".template('shop_info')."\";");
		echo $info;
		?>

		<script>
		function redirect() {
			window.location.replace("shop.php");
		}
		setTimeout("redirect();", 1250);
		</script>

		<?
	}
}

eval("\$shopfooter = \"".template('shop_footer')."\";");
echo $shopfooter;

end_time();

eval("\$footer = \"".template('footer')."\";");
echo $footer;

?>
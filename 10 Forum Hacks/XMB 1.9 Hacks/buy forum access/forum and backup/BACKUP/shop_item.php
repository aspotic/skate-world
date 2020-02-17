<?php
/*
	SHOP HACK v1.2 by NFPUNK

	If you require support please post in the forum
	at www.flixon.com/shop/forum.  Thanks

	- nfpunk/Flixon
*/

require "./header.php";
loadtemplates('header,footer,css,shop_cat_password,shop_item_rate,shop_item_cusername,shop_item_avatar,shop_item_color,shop_item_color_preview,shop_item_image,shop_item_cstatus,shop_item_item,shop_links,shop_info,shop_footer');

$setquery = $db->query("SELECT * FROM $table_shop_settings");
$set = $db->fetch_array($setquery);

if($goto == "lastadd") {
	$lastaddquery = $db->query("SELECT id FROM $table_shop_items WHERE cid='$cid' ORDER BY dateline DESC LIMIT 0, 1");
	$iid = $db->result($lastaddquery, 0);
}

eval("\$css = \"".template('css')."\";");

$itemquery = $db->query("SELECT * FROM $table_shop_items WHERE id='$iid'");

if($item = $db->fetch_array($itemquery)) {
	$catquery = $db->query("SELECT * FROM $table_shop_cats WHERE id='$item[cid]'");
	$cat = $db->fetch_array($catquery);
}

if($cat['cid'] != 0) {
	$upcatquery = $db->query("SELECT id, catname FROM $table_shop_cats WHERE id='$cat[cid]'");
	$upcat = $db->fetch_array($upcatquery);

	$navigation = "&raquo; <a href=\"shop.php\">$lang_shop_title</a> &raquo; <a href=\"shop_category.php?cid=$upcat[id]\">$upcat[catname]</a> &raquo; <a href=\"shop_category.php?cid=$cat[id]\">$cat[catname]</a> &raquo; $item[itemname]";
} else {
	$navigation = "&raquo; <a href=\"shop.php\">$lang_shop_title</a> &raquo; <a href=\"shop_category.php?cid=$cat[id]\">$cat[catname]</a> &raquo; $item[itemname]";
}

if($action != "previewname") {
	eval("\$header = \"".template('header')."\";");
	echo $header;
}

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

if($cat['status'] != "on") {
	$outputtext = "$lang_shop_category_off";
	eval("\$info = \"".template('shop_info')."\";");
	echo $info;
	end_time();
	eval("\$footer = \"".template('footer')."\";");
	echo $footer;
	exit();
}

if($item['status'] != "on") {
	$outputtext = "$lang_shop_item_off";
	eval("\$info = \"".template('shop_info')."\";");
	echo $info;
	end_time();
	eval("\$footer = \"".template('footer')."\";");
	echo $footer;
	exit();
}

if($cat['password'] != $HTTP_COOKIE_VARS["cidpw$cat[id]"] && $cat['password'] != "") {
	eval("\$pwform = \"".template('shop_cat_password')."\";");
	echo $pwform;
	end_time();
	eval("\$footer = \"".template('footer')."\";");
	echo $footer;
	exit();
}

if(!$action) {
	// Change Username

	if($item['feature'] == "cusername") {
		if(!$submit) {
			eval("\$item2 = \"".template('shop_item_cusername')."\";");
			echo $item2;
		} else {
			if($memberinfo['money'] >= $item['price'] && $item['stock'] != 0) {
				$find = array('<', '>', '|', '"', '[', ']', '\\', '&nbsp');

				foreach($find as $needle) {
					if(strstr($newusername, $needle)) {
						$outputtext = "$lang_shop_item_username_invalid (- $needle -);";
						eval("\$info = \"".template('shop_info')."\";");
						echo $info;

						eval("\$footer = \"".template('footer')."\";");
						echo $footer;

						exit();
					}
				}

				$newusername = trim($newusername);

				$query = $db->query("SELECT username FROM $table_members WHERE username='$newusername'");

				if($member = $db->fetch_array($query)) {					
					$outputtext = "<b>$lang_shop_texterror:</b> $lang_shop_item_username_alreadyreg";
					eval("\$info = \"".template('shop_info')."\";");
					echo $info;

					eval("\$footer = \"".template('footer')."\";");
					echo $footer;

					exit();
				}

				$query = $db->query("SELECT name FROM $table_restricted WHERE name='$newusername'");

				if($member = $db->fetch_array($query)) {
					$outputtext = "$lang_shop_item_username_restricted";
					eval("\$info = \"".template('shop_info')."\";");
					echo $info;

					eval("\$footer = \"".template('footer')."\";");
					echo $footer;

					exit();
				}

				$thatime = time();

				$db->query("INSERT INTO $table_shop_bank VALUES('$memberinfo[uid]', 0, '$thatime', '$item[price]', '$item[itemname]', 'shop')");
				$db->query("UPDATE $table_shop_items SET stock=stock-1, sold=sold+1 WHERE id='$iid'");
				$db->query("UPDATE $table_members SET username='$newusername', money=money-'$item[price]' WHERE username='$xmbuser'");
				$db->query("UPDATE $table_posts SET author='$newusername' WHERE author='$xmbuser'");
				$db->query("UPDATE $table_threads SET author='$newusername' WHERE author='$xmbuser'");
				$db->query("UPDATE $table_forums SET moderator='$newusername' WHERE moderator='$xmbuser'");
				$db->query("UPDATE $table_favorites SET username='$newusername' WHERE username='$xmbuser'");
				$db->query("UPDATE $table_buddys SET username='$newusername' WHERE username='$xmbuser'");
				$db->query("UPDATE $table_buddys SET buddyname='$newusername' WHERE buddyname='$xmbuser'");
				$db->query("UPDATE $table_u2u SET msgto='$newusername' WHERE msgto='$xmbuser'");
				$db->query("UPDATE $table_u2u SET msgfrom='$newusername' WHERE msgfrom='$xmbuser'");
				$db->query("UPDATE $table_whosonline SET username='$newusername' WHERE username='$xmbuser'");

				$get_lastposter = $db->query("SELECT * FROM $table_forums WHERE lastpost LIKE '$xmbuser'");

				while($lastposter_data = $db->fetch_array($get_lastposter)) {
					$lastpost_oldval = $lastposter_data['lastpost'];
					$lastpost_newval = ereg_replace($xmbuser, $newusername, $lastpost_oldval);

					$db->query("UPDATE $table_forums SET lastpost='$lastpost_newval' WHERE lastpost='$lastpost_oldval'");
				}

				$get_lastposter = $db->query("SELECT * FROM $table_threads WHERE lastpost LIKE '$xmbuser'");

				while($lastposter_data = $db->fetch_array($get_lastposter)) {
					$lastpost_oldval = $lastposter_data['lastpost'];
					$lastpost_newval = ereg_replace($xmbuser, $newusername, $lastpost_oldval);

					$db->query("UPDATE $table_threads SET lastpost='$lastpost_newval' WHERE lastpost='$lastpost_oldval'");
				}

				$get_userlist = $db->query("SELECT * FROM $table_forums WHERE userlist LIKE '$xmbuser'");

				while($userlist_data = $db->fetch_array($get_userlist)) {
					$userlist_oldval = $userlist_data['userlist'];
					$userlist_newval = ereg_replace($xmbuser, $newusername, $userlist_oldval);
					echo "Test line 0: $userlist_newval";

					$db->query("UPDATE $table_forums SET userlist='$userlist_newval' WHERE userlist='$userlist_oldval'");
				}

				$outputtext = "$lang_shop_item_username_update";
				eval("\$info = \"".template('shop_info')."\";");
				echo $info;
				?>

				<script>
				function redirect() {
					window.location.replace("misc.php?action=login");
				}
				setTimeout("redirect();", 1250);
				</script>

				<?
			} else {
				$outputtext = "$lang_shop_item_noupdate";
				eval("\$info = \"".template('shop_info')."\";");
				echo $info;
				?>

				<script>
				function redirect() {
					window.location.replace("shop_category.php?cid=<?=$cat[id]?>");
				}
				setTimeout("redirect();", 1250);
				</script>

				<?
			}
		}
	}

	// Username Glow/Change Colour

	else if($item['feature'] == "glow" || $item['feature'] == "color") {
		if($item['feature'] == "glow") {
			$lang_shop_textitemcolorinfo = $lang_shop_textitemcolorinfo1;
			$field = "glowcolor";
		} else {
			$lang_shop_textitemcolorinfo = $lang_shop_textitemcolorinfo2;
			$field = "hexcolor";
		}

		if(!$submit) {
			eval("\$item2 = \"".template('shop_item_color')."\";");
			echo $item2;
		} else {
			if($memberinfo['money'] >= $item['price'] && $item['stock'] != 0) {
				if(!eregi("^[a-f0-9]{6}$", $color)) {
					exit();
				}

				$thatime = time();

				$db->query("INSERT INTO $table_shop_bank VALUES('$memberinfo[uid]', 0, '$thatime', '$item[price]', '$item[itemname]', 'shop')");
				$db->query("UPDATE $table_shop_items SET stock=stock-1, sold=sold+1 WHERE id='$iid'");
				$db->query("UPDATE $table_members SET $field='$color', money=money-'$item[price]' WHERE username='$xmbuser'");

				$outputtext = "$lang_shop_item_color_update";
				eval("\$info = \"".template('shop_info')."\";");
				echo $info;
				?>

				<script>
				function redirect() {
					window.location.replace("shop_category.php?cid=<?=$cat[id]?>");
				}
				setTimeout("redirect();", 1250);
				</script>

				<?
			} else {
				$outputtext = "$lang_shop_item_noupdate";
				eval("\$info = \"".template('shop_info')."\";");
				echo $info;
				?>

				<script>
				function redirect() {
					window.location.replace("shop_category.php?cid=<?=$cat[id]?>");
				}
				setTimeout("redirect();", 1250);
				</script>

				<?
			}
		}
	}

	// Personal Photo/Upload Avatar (100*100/200*200)

	else if($item['feature'] == "pp" || $item['feature'] == "avatar100" || $item['feature'] == "avatar200") {
		if($item['feature'] == "pp") {
			$max_image_fsize = '50000'; // 50kb file size
			$max_image_psize = array('400', '300'); // 400x300 ('width', 'height')
			$upldir = "pphotos";
			$field = "pphoto";
		} else if($item['feature'] == "avatar100") {
			$max_image_fsize = '50000'; // 50kb file size
			$max_image_psize = array('100', '100'); // 100x100 ('width', 'height')
			$upldir = "avatars";
			$field = "avatar";
		} else {
			$max_image_fsize = '80000'; // 80kb file size
			$max_image_psize = array('200', '200'); // 200x200 ('width', 'height')
			$upldir = "avatars";
			$field = "avatar";
		}

		if(!$submit) {
			eval("\$item2 = \"".template('shop_item_avatar')."\";");
			echo $item2;
		} else {
			$flocation = checkInput($flocation, '', '', 'javascript');

			if($memberinfo['money'] >= $item['price'] && $item['stock'] != 0) {
				$upload_file = $HTTP_POST_FILES['flocation']['name'];
				$temp = $HTTP_POST_FILES['flocation']['tmp_name'];
				$extension = substr($upload_file, -4);

				if($upload_file == "") {
					$outputtext = "$lang_shop_item_upload_noimage";
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
				} else {
					if($extension == ".jpg" XOR $extension == ".png" XOR $extension == ".gif" XOR $extension == ".JPG" XOR $extension == ".PNG" XOR $extension == ".GIF" XOR $extension == "jpeg" XOR $extension == "JPEG") {
						if($extension == "JPEG" xor $extension == "jpeg") {
							$extension = ".jpeg";
						}

						$imageurl = "images/$upldir/$xmbuser$extension";
						move_uploaded_file($temp, $imageurl);

						$size = filesize($imageurl);

						if($size > $max_image_fsize) {
							unlink($imageurl);
							$outputtext = "$lang_shop_admin_item_toobig $max_image_fsize kb!";
							eval("\$info = \"".template('shop_info')."\";");
							echo $info;

							end_time();
							eval("\$footer = \"".template('footer')."\";");
							echo $footer;
							?>

							<script>
							function redirect() {
								window.location.replace("shop_item.php?iid=<?=$iid?>");
							}
							setTimeout("redirect();", 1250);
							</script>

							<?
							exit();
						}

						$SizeArray = getimagesize($imageurl);

						if($SizeArray[0] > $max_image_psize[0] || $SizeArray[1] > $max_image_psize[1]) {
							unlink($imageurl);
							$outputtext = "$lang_shop_admin_item_toobig $max_image_psize[0] * $max_image_psize[1]!";
							eval("\$info = \"".template('shop_info')."\";");
							echo $info;

							end_time();
							eval("\$footer = \"".template('footer')."\";");
							echo $footer;
							?>

							<script>
							function redirect() {
								window.location.replace("shop_item.php?iid=<?=$iid?>");
							}
							setTimeout("redirect();", 1250);
							</script>

							<?
							exit();
						}
					} else {
						$outputtext = "$lang_shop_admin_item_wrongtype";
						eval("\$info = \"".template('shop_info')."\";");
						echo $info;

						end_time();
						eval("\$footer = \"".template('footer')."\";");
						echo $footer;
						?>

						<script>
						function redirect() {
							window.location.replace("shop_item.php?iid=<?=$iid?>");
						}
						setTimeout("redirect();", 1250);
						</script>

						<?
						exit();
					}

					$thatime = time();

					$db->query("INSERT INTO $table_shop_bank VALUES('$memberinfo[uid]', 0, '$thatime', '$item[price]', '$item[itemname]', 'shop')");
					$db->query("UPDATE $table_shop_items SET stock=stock-1, sold=sold+1 WHERE id='$iid'");
					$db->query("UPDATE $table_members SET $field='$imageurl', money=money-'$item[price]' WHERE username='$xmbuser'");

					$outputtext = "$lang_shop_item_update";
					eval("\$info = \"".template('shop_info')."\";");
					echo $info;
					?>

					<script>
					function redirect() {
						window.location.replace("shop_category.php?cid=<?=$cat[id]?>");
					}
					setTimeout("redirect();", 1250);
					</script>

					<?
				}
			} else {
				$outputtext = "$lang_shop_item_noupdate";
				eval("\$info = \"".template('shop_info')."\";");
				echo $info;
				?>

				<script>
				function redirect() {
					window.location.replace("shop_category.php?cid=<?=$cat[id]?>");
				}
				setTimeout("redirect();", 1250);
				</script>

				<?
			}
		}
	}

	// Change Custom Status

	else if($item['feature'] == "cstatus") {
		if(!$submit) {
			eval("\$item2 = \"".template('shop_item_cstatus')."\";");
			echo $item2;
		} else {
			if($memberinfo['money'] >= $item['price'] && $item['stock'] != 0) {
				$newcstatus = checkInput($newcstatus, '', '', 'javascript');

				$thatime = time();

				$db->query("INSERT INTO $table_shop_bank VALUES('$memberinfo[uid]', 0, '$thatime', '$item[price]', '$item[itemname]', 'shop')");
				$db->query("UPDATE $table_shop_items SET stock=stock-1, sold=sold+1 WHERE id='$iid'");
				$db->query("UPDATE $table_members SET customstatus='$newcstatus', money=money-'$item[price]' WHERE username='$xmbuser'");

				$outputtext = "$lang_shop_item_cstatus_update";
				eval("\$info = \"".template('shop_info')."\";");
				echo $info;
				?>

				<script>
				function redirect() {
					window.location.replace("shop_category.php?cid=<?=$cat[id]?>");
				}
				setTimeout("redirect();", 1250);
				</script>

				<?
			} else {
				$outputtext = "$lang_shop_item_noupdate";
				eval("\$info = \"".template('shop_info')."\";");
				echo $info;
				?>

				<script>
				function redirect() {
					window.location.replace("shop_category.php?cid=<?=$cat[id]?>");
				}
				setTimeout("redirect();", 1250);
				</script>

				<?
			}
		}
	}

	// Items

	else {
		if(!$submit) {
			if($item['imageurl'] == "") {
				$image = "images/shop/noitempic.gif";
			} else {
				$image = $item['imageurl'];
			}

			eval("\$item2 = \"".template('shop_item_item')."\";");
			echo $item2;
		} else {
			if($memberinfo['money'] >= $item['price'] && $item['stock'] != 0) {
				// Check that user has not purchased the item before

				$itemsquery = $db->query("SELECT COUNT(*) FROM $table_member_items WHERE iid='$iid' && uid='$memberinfo[uid]'");
				$items = $db->result($itemsquery, 0);

				if($items != 0) {
					$outputtext = "$lang_shop_item_noupdate2";
					eval("\$info = \"".template('shop_info')."\";");
					echo $info;
					end_time();
					eval("\$footer = \"".template('footer')."\";");
					echo $footer;
					exit();
				}

				$thatime = time();

				$db->query("INSERT INTO $table_shop_bank VALUES('$memberinfo[uid]', 0, '$thatime', '$item[price]', '$item[itemname]', 'shop')");
				$db->query("INSERT INTO $table_member_items VALUES('$iid', '$memberinfo[uid]')");
				$db->query("UPDATE $table_members SET money=money-'$item[price]' WHERE username='$xmbuser'");
				$db->query("UPDATE $table_shop_items SET stock=stock-1, sold=sold+1 WHERE id='$iid'");

				if($cat['ownermoney'] == "on" && $item['owner'] != 0) {
					$db->query("INSERT INTO $table_shop_bank VALUES(0, '$item[owner]', '$thatime', '$item[price]', '$item[itemname]', 'shop')");
					$db->query("UPDATE $table_members SET money=money+'$item[price]' WHERE uid='$item[owner]'");
				}

				$outputtext = "$lang_shop_item_update";
				eval("\$info = \"".template('shop_info')."\";");
				echo $info;
				?>

				<script>
				function redirect() {
					window.location.replace("shop_category.php?cid=<?=$cat[id]?>");
				}
				setTimeout("redirect();", 1250);
				</script>

				<?
			} else {
				$outputtext = "$lang_shop_item_noupdate";
				eval("\$info = \"".template('shop_info')."\";");
				echo $info;
				?>

				<script>
				function redirect() {
					window.location.replace("shop_category.php?cid=<?=$cat[id]?>");
				}
				setTimeout("redirect();", 1250);
				</script>

				<?
			}
		}
	}
}

// Preview Name

if($action == "previewname") {
	if(!eregi("^[a-f0-9]{6}$", $hexcolor)) {
		$hexcolor = '';
	}

	if(!eregi("^[a-f0-9]{6}$", $glowcolor) ) {
		$glowcolor = '';
	}

	if(!$hexcolor) {
		$hexcolor1 = '';
		$hexcolor2 = '';
	} else {
		$hexcolor1 = "#$hexcolor";
		$hexcolor2 = "color:#$hexcolor";
	}

	if(!$glowcolor) {
		$span1 = "<font color=$hexcolor1>";
		$span2 = "</font>";
	} else {
		$glowcolor = "#$glowcolor";
		$span1 = "<font
		style=\"
		width:100%;
		$hexcolor2;
		filter:glow(color=$glowcolor, strength=2)\">";
		$span2 = "</font>";
	}

	$username = "$span1$xmbuser$span2";

	eval("\$previewname = \"".template('shop_item_color_preview')."\";");
	echo $previewname;
}

// Rate Item

if($action == "rate") {
	$query = $db->query("SELECT COUNT(*) FROM $table_shop_votes WHERE iid='$iid'");
	$nbrows = $db->result($query, 0);

	if($nbrows != 0) {
		$query = $db->query("SELECT uid FROM $table_shop_votes WHERE iid='$iid'");
		$vote = $db->result($query, 0);
	}

	if($set['rating'] != "on") {
		$outputtext = "$lang_shop_item_rate_off";
		eval("\$output = \"".template('shop_error')."\";");
		echo $info;
		end_time();
		eval("\$footer = \"".template('footer')."\";");
		echo $footer;
		exit();
	}

	if($vote == $memberinfo['uid']) {
		$outputtext = "$lang_shop_item_rate_nopage";
		eval("\$info = \"".template('shop_info')."\";");
		echo $info;
		end_time();
		eval("\$footer = \"".template('footer')."\";");
		echo $footer;
		exit();
	}

	if(!$submit) {
		eval("\$form2 = \"".template('shop_item_rate')."\";");
		echo $form2;
	} else {
		$db->query("UPDATE $table_shop_items SET votes=votes+1, rate=rate+'$rate' WHERE id='$iid'");
		$db->query("INSERT INTO $table_shop_votes VALUES('$item[id]', '$memberinfo[uid]')");

		$outputtext = "$lang_shop_item_rate_update $item[itemname]";
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

// Links

$displaylinks = '';

if($status == "Super Administrator" || $status == "Administrator") {
	$displaylinks = '<img src="images/shop/admin.gif" border="0" alt="'.$lang_shop_textadminpanel.'"> <a href="shop_admin.php">'.$lang_shop_textadminpanel.'</a> ';
}

if(eregi($xmblistname, $cat['whoadd']) || eregi($statuslistname, $set['whoadd'])) {
	$displaylinks .= '<img src="images/shop/newitem.gif" border="0" alt="'.$lang_shop_textnewitem.'"> <a href="shop_edit.php?action=newitem&cid='.$item[cid].'">'.$lang_shop_textnewitem.'</a> ';
	$displaylinks .= '<img src="images/shop/edititem.gif" border="0" alt="'.$lang_shop_textedititem.'"> <a href="shop_edit.php?action=edititem&iid='.$iid.'">'.$lang_shop_textedititem.'</a> ';
	$displaylinks .= '<img src="images/shop/delete.gif" border="0" alt="'.$lang_shop_textdeleteitem.'"> <a href="shop_edit.php?action=deleteitem&iid='.$iid.'">'.$lang_shop_textdeleteitem.'</a> ';
}

if($set['rating'] == "on") {
	$displaylinks .= '<img src="images/shop/rate.gif" border="0" alt="'.$lang_shop_textrateitem.'"> <a href="shop_item.php?action=rate&iid='.$iid.'">'.$lang_shop_textrateitem.'</a> ';
}

if($set['search'] == "on") {
	$displaylinks .= '<img src="images/shop/search.gif" border="0" alt="'.$lang_shop_textsearch.'"> <a href="shop_search.php">'.$lang_shop_textsearch.'</a> ';
}

if($set['stats'] == "on") {
	$displaylinks .= '<img src="images/shop/stats.gif" border="0" alt="'.$lang_shop_textstats.'"> <a href="shop_stats.php">'.$lang_shop_textstats.'</a> ';
}

if($action != "previewname") {
	eval("\$links = \"".template('shop_links')."\";");
	echo $links;

	eval("\$shopfooter = \"".template('shop_footer')."\";");
	echo $shopfooter;

	end_time();

	eval("\$footer = \"".template('footer')."\";");
	echo $footer;
}

?>
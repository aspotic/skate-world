<?php
/* $Id: cp.php, v1.22 2004/03/01 16:22:03 creatixea Exp $ */
/*
    XMB 1.9.0.8
    © 2001 - 2004 Aventure Media & The XMB Developement Team
    http://www.aventure-media.co.uk
    http://www.xmbforum.com

    For license information, please read the license file which came with this edition of XMB

    Membergroups 0.18 for XMB 1.9 beta by evilc@evilc.com

*/
require "./header.php";
require "./lang/lang-mg.php";

loadtemplates('footer_load', 'footer_querynum', 'footer_phpsql', 'footer_totaltime','header','footer', 'css');

$navigation = "&raquo; <a href=mg.php>$lang[mg_editing]</a>";

eval('$css = "'.template('css').'";');
eval("\$header = \"".template("header")."\";");
echo $header;

$ip = $onlineip;

$time = time();
$string = "$xmbuser|#||#|$ip|#||#|$time|#||#|$_SERVER[REQUEST_URI]\n";
@chmod('./cplogfile.php', 0777);
$stream = @fopen('./cplogfile.php','a');
if(!$stream){
    error('Wrong File permissions set. Please CHMOD the <i>cplogfile.php</i> to <i>777</i>', false, '', '', false, false, false, false);
}
@flock($stream, 2);
@fwrite($stream, $string);
@fclose($stream);
@chmod('./cplogfile.php', 0766);

// Start mg code
$groupadmin=0;
if($self['status'] != "Administrator" && $self['status'] !="Super Administrator") {
	if($action == "group" || $action == "email" || $action == "pm") {
		if ($fdetails){
			$query=$db->query("SELECT adminlist FROM $table_membergroups WHERE mgid = '$fdetails'");
			$userlist = $db->fetch_array($query);
			if (privfcheck("",$userlist[adminlist])){
				$groupadmin=1;
			}
		}
		if ( (!$groupadmin) && ( ($groupsubmit) || ($fdetails) ) ){
			eval("\$notadmin = \"".template("error_nologinsession")."\";");
			echo $notadmin;
			eval("\$footer = \"".template("footer")."\";");
			echo $footer;
			exit;
		}
	}
} else {
	$groupadmin=1;
}

if(!$action) {
   $action="group";
}

if($action == "group") {
	if(!$groupsubmit) {
		if (!$fdetails){			// Display group list
			$navigation = "&raquo; Viewing Groups";
			?>
			<tr bgcolor="<?=$altbg2?>">
			<td align="center">
			<br />
			<form method="post" action="mg.php?action=group">
			<table cellspacing="0" cellpadding="0" border="0" width="500" align="center">
			<tr><td bgcolor="<?=$bordercolor?>">

			<table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">

			<tr class="header">
			<td colspan=2 align=center><?=$lang[mg_editing]?></td>
			</tr>

			<tr bgcolor="<?=$altbg1?>" class="tablerow">
			<td valign="top"><center><br>

			<?
			$ctr1=0;
			$ctr2=0;
			$queryf = $db->query("SELECT * FROM $table_membergroups");
			while($group = $db->fetch_array($queryf)) {
				$ctr1++;
				if (privfcheck("2",$group[adminlist])){
					$ctr2++;
				}
			}
			if (!$ctr2){
				if ( (!$ctr1) && ($self['status'] == "Administrator" || $self['status'] =="Super Administrator") ) {
					echo "$lang[mg_no_groups]<br><br>";
				} else {
					echo "$lang[mg_no_group_admin]<br><br>";
					echo "</table></td></tr></table><br>";

					eval("\$footer = \"".template("footer")."\";");
					echo $footer;
					exit;
				}
			}

			if ($ctr1){
				?>
				<table cellspacing="0" cellpadding="0" border="0" width="91%" align="center">
				<tr><td bgcolor="<?=$bordercolor?>">

				<table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">

				<tr class="header">
				<td><?=$lang[mg_grpname]?></td>
				<td><?=$lang[textdesc]?></td>
				<td><?=$lang[textedit]?></td>
				<td><?=$lang[textemail]?></td>
				<td>U2U</td>
				</tr>
				<?
			}
			$queryf = $db->query("SELECT * FROM $table_membergroups");
			while($group = $db->fetch_array($queryf)) {
				if (privfcheck("2",$group[adminlist])){
					?>
					<tr bgcolor="<?=$altbg2?>" class="tablerow">
					<td><?=$group[groupname]?></td>
					<td><?=$group[description]?></td>
					<td><a href="mg.php?action=group&fdetails=<?=$group[mgid]?>">Edit</a></td>
					<td><a href="mg.php?action=email&fdetails=<?=$group[mgid]?>"><?=$lang[textemail]?></a></td>
					<td><a href="mg.php?action=pm&fdetails=<?=$group[mgid]?>">U2U</a></td>
					<?
				}
			}
			?>


			</table>
			</td></tr></table><br>

			<br>
			</tr>
			<?
			if($self['status'] == "Administrator" || $self['status'] == "Super Administrator"){
				?></table></td></tr></table><table cellspacing="0" cellpadding="0" border="0" width="500" align="center"><tr><td bgcolor="<?=$bordercolor?>"><table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">
				<tr bgcolor="<?=$altbg2?>">
				<td class="tablerow" align="center" width="100%"><?=$lang[mg_newgrpname]?> <input type="text" name="namenew" value=""/></td>
				</tr>
				<?
			}
			?>

			</table>
			</td></tr></table><br>
			<?
			if($self['status'] == "Administrator" || $self['status'] == "Super Administrator"){
				?>
				<center><input type="submit" name="groupsubmit" value="<?=$lang[textsubmitchanges]?>" /></center>
				</form>
			<?
			}
		} elseif ($fdetails) {		// Display details of a group
			$queryf = $db->query("SELECT * FROM $table_membergroups WHERE mgid ='$fdetails'");
			$group = $db->fetch_array($queryf);
			?>

			<form method="post" action="mg.php?action=group&fdetails=<?=$fdetails?>">
			<table cellspacing="0" cellpadding="0" border="0" width="500" align="center">
			<tr><td bgcolor="<?=$bordercolor?>">

			<table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">

			<tr class="header">
			<td colspan=2 align=center><?=$lang[mg_editing]." &raquo; ".$group[groupname]?></td>
			</tr>
			<?
			if($self['status'] == "Administrator" || $self['status'] == "Super Administrator"){
				?>
				<tr bgcolor="<?=$altbg2?>">
				<td class="tablerow"><?=$lang[mg_grpname]?></td>
				<td><input type="text" name="namenew" value="<?=$group[groupname]?>" /></td>
				</tr>

				<tr bgcolor="<?=$altbg2?>">
				<td class="tablerow"><?=$lang[mg_adminlist]?></td>
				<td><textarea rows="4" cols="30" name="adminlistnew"><?=$group[adminlist]?></textarea></td>
				</tr>
				<?
			}
			?>
			<tr bgcolor="<?=$altbg2?>">
			<td class="tablerow"><?=$lang[mg_grpdesc]?></td>
			<td><textarea rows="4" cols="30" name="descnew"><?=$group[description ]?></textarea></td>
			</tr>

			<tr bgcolor="<?=$altbg2?>">
			<td class="tablerow"><?=$lang[mg_userlist]?></td>
			<td><textarea rows="4" cols="30" name="userlistnew"><?=$group[userlist]?></textarea></td>
			</tr>

			<?
			if($self['status'] == "Administrator" || $self['status'] == "Super Administrator"){
				?>
				<tr bgcolor="<?=$altbg2?>">
				<td class="tablerow"><?=$lang[textdeleteques]?></td>
				<td><input type="checkbox" name="delete" value="1" /></td>
				</tr>
				<?
			}
			?>

			</table>
			</td></tr></table><br>
			<center><input type="submit" name="groupsubmit" value="<?=$lang[textsubmitchanges]?>" /></center>
			</form>

		<?
		}
	} elseif ($groupsubmit){			// Ran when submit is clicked
		if (!$fdetails){						// No group specified ?
			if($namenew != "") {		// Create New Group
				$restchkflag=0;
				if (eregi("[^a-z0-9_-]",$namenew)){				// Check that no bad characters typed in
					echo "<tr bgcolor=\"$altbg2\" class=\"tablerow\"><td align=\"center\">$lang[mg_er_grp_bad_char]</td></tr>";
					?>
					<script>
					 function redirect() {
					  window.location.replace("mg.php?action=group");
					 }
					 setTimeout("redirect();", 2500);
					</script>
					<?
				} else {
					$namenew = addslashes($namenew);
					$query=$db->query("SELECT groupname FROM $table_membergroups WHERE groupname = '$namenew'");
					$restchk = $db->fetch_array($query);
					if ($restchk[groupname] == $namenew){			// Check if group with same name already exists
						echo "<tr bgcolor=\"$altbg2\" class=\"tablerow\"><td align=\"center\">$lang[mg_er_grp_exist]</td></tr>";
						?>
						<script>
						 function redirect() {
						  window.location.replace("mg.php?action=group");
						 }
						 setTimeout("redirect();", 2500);
						</script>
						<?
					}  else {					// Check if user with same name exists
						$query=$db->query("SELECT username FROM $table_members WHERE username = '$namenew'");
						$restchk = $db->fetch_array($query);
						if ($restchk[username] == $namenew){
							echo "<tr bgcolor=\"$altbg2\" class=\"tablerow\"><td align=\"center\">$lang[mg_er_grp_exist2]</td></tr>";
							?>
							<script>
							function redirect() {
							window.location.replace("mg.php?action=group");
							}
							setTimeout("redirect();", 2500);
							</script>
							<?
						} else {			// Update database
							$db->query("INSERT INTO $table_membergroups VALUES ('', '$namenew', '', '','')");
							$query=$db->query("SELECT name FROM $table_restricted WHERE name = '$namenew'");
							$restchk = $db->fetch_array($query);
							if (($restchk[name]) == ""){
								$db->query("INSERT INTO $table_restricted VALUES ('$namenew', '', '0', '0')");
							}
							echo "<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"500\" align=\"center\"><tr><td bgcolor=\"$bordercolor\"><table border=\"0\" cellspacing=\"$borderwidth\" cellpadding=\"$tablespace\" width=\"100%\"><tr bgcolor=\"$altbg2\" class=\"tablerow\"><td align=\"center\">$lang[mg_grpupdated]</td></tr></table></td></tr></table>";
							?>
							<script>
							 function redirect() {
							  window.location.replace("mg.php?action=group");
							 }
							 setTimeout("redirect();", 2500);
							</script>
							<?
						}
					}
				}
			}
		} else {					// Group specified
			if($self['status'] != "Administrator" && $self['status'] !="Super Administrator" && $groupadmin == "1") {
				$delete="";
			}
			if ($delete == "") {
				$query = $db->query("SELECT * FROM $table_membergroups WHERE mgid ='$fdetails'");
				$group = $db->fetch_array($query);
				$restchkflag=0;
				if($self['status'] != "Administrator" && $self['status'] !="Super Administrator") {
					$namenew=$group[groupname];
					$adminlistnew=$group[adminlist];
				}
				if ($group[groupname] != $namenew) {		// Change name
				if (eregi("[^a-z0-9_-]",$namenew)){				// Check that no bad characters typed in
					echo "<tr bgcolor=\"$altbg2\" class=\"tablerow\"><td align=\"center\">$lang[mg_er_grp_bad_char]</td></tr>";
						$restchkflag=1;
						?>
						<script>
						 function redirect() {
						  window.location.replace("mg.php?action=group");
						 }
						 setTimeout("redirect();", 2500);
						</script>
						<?
					} else {
						$query=$db->query("SELECT username FROM $table_members WHERE username = '$namenew'");
						$restchk = $db->fetch_array($query);
						if ($restchk[username] == $namenew){		// Check if user with same name exists
							echo "<tr bgcolor=\"$altbg2\" class=\"tablerow\"><td align=\"center\">$lang[mg_er_grp_exist2]</td></tr>";
							$restchkflag=1;
							?>
							<script>
							 function redirect() {
							  window.location.replace("mg.php?action=group");
							 }
							 setTimeout("redirect();", 2500);
							</script>
							<?
						} else {			// Check if group with same name exists
							$query=$db->query("SELECT groupname FROM $table_membergroups WHERE groupname  = '$namenew'");
							$restchk = $db->fetch_array($query);
							if ($restchk[groupname] == $namenew){
								echo "<tr bgcolor=\"$altbg2\" class=\"tablerow\"><td align=\"center\">$lang[mg_er_grp_exist]</td></tr>";
								$restchkflag=1;
								?>
								<script>
								 function redirect() {
								  window.location.replace("mg.php?action=group");
								 }
								 setTimeout("redirect();", 2500);
								</script>
								<?
							}
						}
					}
					if (!$restchkflag){		// Change Restricted table
						$query = $db->query("SELECT * FROM $table_membergroups WHERE mgid ='$fdetails'");
						$group = $db->fetch_array($query);
						$group[groupname]=addslashes($group[groupname]);
						$db->query("DELETE FROM $table_restricted WHERE name = '$group[groupname]'");
						$db->query("INSERT INTO $table_restricted VALUES ('$namenew', '', '0', '0')");
						// mg rename group marker
						if ($hta_enabled=="yes"){
							htg_ren_del_group($group[groupname],$namenew);
						}
					}
				}
				if (!$restchkflag){		// Update Group Table
					$namenew = addslashes($namenew);
					$descnew = addslashes($descnew);
					//sort userlist
					$userlistnew = explode (",",$userlistnew);
					for ($u=0;$u<count($userlistnew);$u++){
						$userlistnew[$u]=trim($userlistnew[$u]);
					}
					natcasesort($userlistnew);
					$userlistnew=implode(", ",$userlistnew);
					$userlistnew = addslashes($userlistnew);
					//sort adminlist
					$adminlistnew = explode (",",$adminlistnew);
					for ($u=0;$u<count($adminlistnew);$u++){
						$adminlistnew[$u]=trim($adminlistnew[$u]);
					}
					natcasesort($adminlistnew);
					$adminlistnew=implode(", ",$adminlistnew);
					$adminlistnew = addslashes($adminlistnew);

					$db->query("UPDATE $table_membergroups SET groupname ='$namenew', description ='$descnew', userlist='$userlistnew', adminlist='$adminlistnew' WHERE mgid='$fdetails'");
					// mg update group marker
					if ($hta_enabled=="yes"){
						htg_update_group($namenew,$userlistnew);
					}
					echo "<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"500\" align=\"center\"><tr><td bgcolor=\"$bordercolor\"><table border=\"0\" cellspacing=\"$borderwidth\" cellpadding=\"$tablespace\" width=\"100%\"><tr bgcolor=\"$altbg2\" class=\"tablerow\"><td align=\"center\">$lang[mg_grpupdated]</td></tr></table></td></tr></table>";
					?>
					<script>
					 function redirect() {
					  window.location.replace("mg.php?action=group");
					 }
					 setTimeout("redirect();", 2500);
					</script>
					<?
				}
			} else {					// Delete Group - Should delete a group with a name with invalid characters in.
				$query = $db->query("SELECT groupname FROM $table_membergroups WHERE mgid ='$fdetails'");
				$group = $db->fetch_array($query);
				$group[groupname]=addslashes($group[groupname]);
				$db->query("DELETE FROM $table_restricted WHERE name = '$group[groupname]'");
				$db->query("DELETE FROM $table_membergroups WHERE mgid='$fdetails'");
				// mg delete group marker
				if ($hta_enabled=="yes"){
					htg_ren_del_group($namenew,"");
				}
				echo "<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"500\" align=\"center\"><tr><td bgcolor=\"$bordercolor\"><table border=\"0\" cellspacing=\"$borderwidth\" cellpadding=\"$tablespace\" width=\"100%\"><tr bgcolor=\"$altbg2\" class=\"tablerow\"><td align=\"center\">$lang[mg_grpdeleted]</td></tr></table></td></tr></table>";
				?>
				<script>
				 function redirect() {
				  window.location.replace("mg.php?action=group");
				 }
				 setTimeout("redirect();", 2500);
				</script>
				<?

			}
		}
	}
}
if( ($action == "email") || ($action == "pm") ) {
	if (!$msgsubmit){

		?>
		<tr bgcolor="<?=$altbg2?>">
		<td align="center">
		<br />
		<?
		if($action == "email") {
			?>
			<form method="post" action="mg.php?action=email&fdetails=<?=$fdetails?>">
			<?
		} else {
			?>
			<form method="post" action="mg.php?action=pm&fdetails=<?=$fdetails?>">
			<?
		}
		?>
		<table cellspacing="0" cellpadding="0" border="0" width="500" align="center">
		<tr><td bgcolor="<?=$bordercolor?>">

		<table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">

		<tr class="header">
		<td colspan=2 align=center><?="Sending Message"?></td>
		</tr>
		<tr bgcolor="<?=$altbg1?>" class="tablerow">
		<td><?=$lang[textsubject]?></td><td><input type="text" name="newssubject"
		size="80" /></td>
		</tr>
		<tr bgcolor="<?=$altbg1?>" class="tablerow">
		<td valign=top><?=$lang[textmessage]?></td><td><textarea cols="80" rows="10"
		name="newsmessage"></textarea></td>
		</tr>

		</table>
		</td></tr></table>
		<center><br><input type="submit" name="msgsubmit"
		value="<?=$lang[textsubmitchanges]?>" /></center>
		</form>

		</td>
		</tr>

		<?
	} else {
		?>
		</td></tr></table></table><br>
		<tr bgcolor="<?=$altbg2?>">
		<td align="center">
		<br />
		<form method="post" action="mg.php?action=group">
		<table cellspacing="0" cellpadding="0" border="0" width="500" align="center">
		<tr><td bgcolor="<?=$bordercolor?>">

		<table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">

		<tr class="header">
		<td colspan=2 align=center><?="Progress"?></td>
		</tr>

		<tr bgcolor="<?=$altbg1?>" class="tablerow">
		<td valign="top"><br>


		<?
		if($action == "pm"){
			$newssuubject = addslashes($newssubject);
			$newsmessage = addslashes($newsmessage);
			$emailsender="";
		} else {
			$query = $db->query("SELECT email FROM $table_members WHERE username ='$xmbuser'");
			$emailsender = $db->fetch_array($query);
			$emailsender=$emailsender[email];
			if ($emailsender == ""){
				echo "$lang[mg_er_no_email]<br>";
				echo"</td></tr></table></table><br>";
				eval("\$footer = \"".template("footer")."\";");
				echo $footer;
				exit;
			}
		}
		$query = $db->query("SELECT userlist FROM $table_membergroups WHERE mgid ='$fdetails'");
		$group = $db->fetch_array($query);
		$userlist=explode(",",$group[userlist]);
		for ($ctr=0;$ctr<count($userlist);$ctr++){
			$userlist[$ctr]=trim($userlist[$ctr]);
			if(($mg_status_on == true) && ($userlist[$ctr]{0} == $mg_status_symbol) && ($mg_status_symbol != '')){ // Is it a status name ?
				$userlist[$ctr]=(substr($userlist[$ctr],1));
				$query = $db->query("SELECT * FROM $table_members WHERE status = '$userlist[$ctr]'");
				while ($statuslist = $db->fetch_array($query)){		// Add people with that status to the end of the list
					$found=0;
					for ($ctr2=0;$ctr2<count($userlist);$ctr2++){
						$userlist[$ctr2]=trim($userlist[$ctr2]);
						if(trim($userlist[$ctr2]) == $statuslist[username]){
							$found=1;
						}
					}
					if (!$found) {
						array_push ($userlist,$statuslist[username]);
					}
				}
				continue;	// do next loop (important - should NOT try and process this one as it will be a status name
			}
			$query = $db->query("SELECT * FROM $table_members WHERE username ='$userlist[$ctr]'");
			$memnews = $db->fetch_array($query);
			if ($memnews[username]==""){						// Check for valid user
				echo "$lang[textusername] \"$userlist[$ctr]\" $lang[mg_msg_not_sent]<br>";
				?>
				<script>
				 function redirect() {
				  window.location.replace("mg.php?action=group");
				 }
				 setTimeout("redirect();", 2500);
				</script>
				<?
			} else {
				if($action == "pm") {		// Send PM
					echo "U2U $lang[mg_sent_to_user] \"$memnews[username]\".<br>";
					$db->query("INSERT INTO $table_u2u VALUES('', '$memnews[username]', '$xmbuser', 'incoming', '$memnews[username]', 'Inbox', '$newssubject', '$newsmessage', '" . time() . "', 'no', 'yes')");
					?>
					<script>
					 function redirect() {
					  window.location.replace("mg.php?action=group");
					 }
					 setTimeout("redirect();", 2500);
					</script>
					<?
				} else {						// Send Email
					if ("$memnews[email]" != ""){
						mail("$memnews[email]", "$newssubject", "$newsmessage", "From: $xmbuser <$emailsender>");
						echo "\n$lang[textemail] $lang[mg_sent_to_user] \"$memnews[username]\" ($memnews[email]).<br>";
						?>
						<script>
						 function redirect() {
						  window.location.replace("mg.php?action=group");
						 }
						 setTimeout("redirect();", 2500);
						</script>
						<?
					} else {
						echo "$lang[mg_email_address_for_user] \"$userlist[$ctr]\" $lang[mg_msg_not_sent].<br>";
					}
				}
			}
		}
		if($action == "email"){				// Send confirmation email to the sender's email address.
			mail("$emailsender", "$newssubject", "$lang[mg_email_conf_1]: $group[userlist]\n\n"."$newsmessage", "From: $bbname <$emailsender>");
			echo "$lang[mg_email_conf_2] ($emailsender) $lang[mg_email_conf_3]<br>";
			?>
			<script>
			 function redirect() {
			  window.location.replace("mg.php?action=group");
			 }
			 setTimeout("redirect();", 2500);
			</script>
			<?
		}
	}
}
// stop mg code

echo "</table></td></tr></table>";
end_time();

eval("\$footer = \"".template("footer")."\";");
echo $footer;
?>
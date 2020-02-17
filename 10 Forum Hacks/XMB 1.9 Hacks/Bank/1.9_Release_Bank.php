<?php
/* 
 
 
 
 
     ATTENTION     !!!     ATTENTION     !!!     ATTENTION     !!!     ATTENTION     !!!     ATTENTION     !!!
 
 
 
     ATTENTION     !!!     ATTENTION     !!!     ATTENTION     !!!     ATTENTION     !!!     ATTENTION     !!!
 
 
 
 
     Upload this file to your forumdirectory and point your browser to it;
 
     Example:   http://www.domain.ext/forum/1.9_Release_Bank.php
 
 
 
 
 
     ATTENTION     !!!     ATTENTION     !!!     ATTENTION     !!!     ATTENTION     !!!     ATTENTION     !!!
 
 
 
     ATTENTION     !!!     ATTENTION     !!!     ATTENTION     !!!     ATTENTION     !!!     ATTENTION     !!!
 
 
 
 
*/ 

if(!defined('ROOT')) {
    define('ROOT', './');
}

require ROOT."xmb.php";
require_once ROOT."config.php";
require_once ROOT."db/$database.php";

$db = new dbstuff;
$db->connect($dbhost, $dbuser, $dbpw, $dbname, $pconnect, true);
?>

<html>
<head>
<title> [1.9 Release] Bank hack </title>
<style type="text/css">
body {
margin-top : 2px;
margin-right : 2px;
margin-bottom : 2px;
margin-left : 2px;
}

body, p { 
color : #000000;
font-family : Arial, Times New Roman, Courier, sans-serif;
font-size : 10pt;
font-weight : normal;
text-decoration : none;
}

a:hover, a:visited {
color : #000000;
font-size : 10pt;
font-weight : normal;
text-decoration : underline;
}

a:hover {
text-decoration : none;
}

a:link, a:active {
color : #000000;
font-size : 10pt;
font-weight : normal;
text-decoration : underline;
}

textarea {
background : transparent none repeat;
border : 1px solid #000000;
color : #000000;
font-family : Courier New, Times New Roman, Courier, sans-serif;
font-size : 9pt;
overflow : hidden;
}

.highlighttext {
font-weight: bold;
}
</style>
<script language="Javascript">
<!--
/*
Select and Copy form element script- By Dynamicdrive.com
For full source, Terms of service, and 100s DTHML scripts
Visit http://www.dynamicdrive.com
*/

//specify whether contents should be auto copied to clipboard (memory)
//Applies only to IE 4+
//0=no, 1=yes
var copytoclip=1

function HighlightAll(theField) {
var tempval=eval("document."+theField)
tempval.focus()
tempval.select()
if (document.all&&copytoclip==1){
therange=tempval.createTextRange()
therange.execCommand("Copy")
window.status="Contents highlighted and copied to clipboard!"
setTimeout("window.status=''",1800)
}
}
//-->
</script>
</head>
<body bgcolor="#FFFFFF">
<form name="FunForum">

<?php 

$field_exists = mysql_query("SELECT BankInstall FROM ".$tablepre."shop_settings"); 
if ($field_exists) { $BankExists = "yes"; }else{ $BankExists = "no"; }


if(($step == "1" || $step == "2" || $step == "3" || $step == "4" || $step == "5") && $BankExists == "yes"){ $step = "AlreadyInstalled"; }


if($step){
    if($step == "1"){ $bl .= "&raquo; Installation; Step 1";
    } elseif($step == "2"){ $bl .= "&raquo; Installation; Step 2";
    } elseif($step == "3"){ $bl .= "&raquo; Installation; Step 3";
    } elseif($step == "4"){ $bl .= "&raquo; Installation; Step 4";
    } elseif($step == "5"){ $bl .= "&raquo; Installation; Step 5";
    } elseif($step == "manual"){ $bl .= "&raquo; The manual file adjustments";
    } elseif($step == "moneylinks"){ $bl .= "&raquo; [Updated] Send Money Links In Member Profiles And Posts.txt";
    } elseif($step == "AlreadyInstalled"){ $bl .= "&raquo; Error... The hack has been installed already";
    }
    echo '<center><b>Bank Hack</b> '.$bl.'</center><hr noshade color="#000000" size="1">';
} 


if(!$step){ ?>

    <br /><b>Hack Name:</b> Bank Hack
    <br />
    <br /><b>Description:</b> This is an addon to the Shop v1.2. Just as the Bank I made for the old Shop version of which a copy now can be seen in the Shop v1.2. But there's a different. This Bank, v2.4,  comes with some updates, here's a list of it's features:
    <br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Account Settings &laquo; 4 options related to U2U's for you to turn on or off.
    <br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- My XMB Points &laquo; here you can see you your total amount of XMB Points, and the total amount of sent / received.
    <br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Received XMB Points &laquo; here you can view your received XMB Points, listed per month
    <br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Request XMB Points &laquo; here you can request XMB Points from another user
    <br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Requested XMB Points &laquo; here you can view your requested XMB Points to others / to you, listed per month
    <br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Sent XMB Points &laquo;  here you can view your sent XMB Points, listed per month
    <br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Send XMB Points &laquo; here you can send XMB Points
    <br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Transfer Details &laquo; Here you can view details on a transfer
    <br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Undo XMB Points transfer (admin function) &laquo; here you can, if you are a(n) (Super) Administrator, undo XMB Points transfers
    <br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Undo History (admin function) &laquo; here you can, if you are a(n) (Super) Administrator, view a history of all undone XMB Points transfers
    <br />
    <br /><b>Compatibility / Required:</b> XMB 1.9 (RC1) - Nexus <b>&</b> Shop Hack v1.2
    <br />
    <br /><b>Version:</b> 2.4
    <br />
    <br /><b>Version History:</b>
    <br />&nbsp;- Changes in 2.4:
    <br />&nbsp;&nbsp;&nbsp;- Removed a few forgotten columns in the Bank's table. They were remains from my testversion and served no purpose in the Final version.
    <br />&nbsp;- Changes in 2.3:
    <br />&nbsp;&nbsp;&nbsp;- Added function to send U2U's, should make stuff easier if the standard U2U table has been modified
    <br />&nbsp;&nbsp;&nbsp;- Added postify() to the subject in the 'out' and 'in' sections
    <br />&nbsp;&nbsp;&nbsp;- Made some code changes to make the coding smaller
    <br />&nbsp;&nbsp;&nbsp;- Changed the way templates are being echoed
    <br />&nbsp;&nbsp;&nbsp;- Changed the date format in the Transfer Details
    <br />&nbsp;&nbsp;&nbsp;- Changed the time format in the Transfer Details; it was already set to adjust to the $timeformat, but I forgot about the $memtime
    <br />&nbsp;- Changes in 2.2:
    <br />&nbsp;&nbsp;&nbsp;- Converted the just finished version for "XMB 1.8 Partagium" into "XMB 1.9 Nexus"
    <br />
    <br /><b>Code Designed By:</b> FunForum
    <br />
    <br /><b>Copyright:</b> Posting or distributing this hack, without permission from the author, is not permitted.
    <br />
    <br /><b>Notes:</b>
    <br />- For your own safety, backup all effected files & templates before proceeding with this hack
    <br />- Keep all names and copyrights in their place
    <br />- This Bank has been build on 1.9 Final
    <br />- This Bank sends U2U's. So if you have modified your <?=$tablepre?>u2u table in any way, be sure to modify the Bank's <i>INSERT INTO $table_u2u</i> lines in the same way as you did the ones in the u2u.php
    <br />- <b>If you are upgrading from V2.2 / V2.3</b>, <a target="_self" href="<?php echo $PHP_SELF;?>?step=u1">check out these upgrade instructions over <i>here</i></a>.
    <br />
    <br />
    <br />
    <br />
    <?php if($BankExists == "no"){ ?>
        <br /><a target="_self" href="<?php echo $PHP_SELF;?>?step=1">Click here to proceed with Step 1</a>
        <br />While going through the steps 1 - 5, please do NOT press the Back button and do NOT Refresh the page
    <?php }else{ ?>
        <br />It looks like the Bank has already been installed... If you want to see the manual file adjustments again...
        <br /><a target="_self" href="<?php echo $PHP_SELF;?>?step=manual">Click here to see Step 4. The manual file adjustments</a>
    <?php } ?>
    <br />
    <br />
    <br />
    <br />
    <br />If you want to see the updated version of the "Send Money Links In Member Profiles And Posts.txt",
    <br /><a target="_self" href="<?php echo $PHP_SELF;?>?step=moneylinks">the "[Updated] Send Money Links In Member Profiles And Posts.txt", click here.</a>
    <br />
    <br />

<?php } if($step == "1"){ ?>

    <br /><b>Step 1.</b>
    <br />
    <br />Now, to prevent any errors, the Shop should be closed for the moment.

    <br />
    <br />
    <br />
    <br />
    <br /><a target="_self" href="<?php echo $PHP_SELF;?>?step=2">Click here to close the Shop and proceed with Step 2</a>
    <br />
    <br />

<?php } if($step == "2"){ 

    $query = "ALTER TABLE ".$tablepre."shop_settings ADD `temp_bank_install` TEXT";
    $result = mysql_query($query) or die("Query failed : " . mysql_error());

    $squery = $db->query("SELECT whoview FROM ".$tablepre."shop_settings");
    $shopsettings = $db->result($squery, 0);

    $query = "UPDATE ".$tablepre."shop_settings SET temp_bank_install='$shopsettings'";
    $result = mysql_query($query) or die("Query failed : " . mysql_error());

    $query = "UPDATE ".$tablepre."shop_settings SET whoview='FunForum'";
    $result = mysql_query($query) or die("Query failed : " . mysql_error());

    ?>
    <br /><b>Step 2.</b>
    <br />
    <br />The Shop has been closed.
    <br />We can start the upgrade script that will upgrade the current database table.
    <br />The upgrade script will create a new table, and copy the old data into the new table.
    <br />See it as a backup, just in case...
    <br />Your current Bank's table name is: <?php echo $tablepre; ?>shop_bank
    <br />The new Bank's table name will be: <?php echo $tablepre; ?>bank_transfer

    <br />
    <br />
    <br />
    <br />
    <br /><a target="_self" href="<?php echo $PHP_SELF;?>?step=3">Click here to start the upgrade script</a>
    <br />
    <br />

<?php } if($step == "3"){ ?>

    <br /><b>Step 3.</b>
    <br />

    <?php 
        echo '<font style="font-family: Courier New; font-size: 13px">';
        echo "<br />- Creating new Bank table";
        $query = "CREATE TABLE ".$tablepre."bank_transfer (`fromuid` smallint( 6 ) NOT NULL default '0',`touid` smallint( 6 ) NOT NULL default '0',`dateline` bigint( 30 ) NOT NULL default '0',`amount` int( 10 ) NOT NULL default '0',`comment` text NOT NULL,`type` varchar( 10 ) NOT NULL default '') TYPE = MYISAM";
        $result = mysql_query($query) or die("Query failed : " . mysql_error());
        echo " ... <b>Done</b>";

        echo "<br />- Inserting mutations into new Bank table";
        $query = "INSERT INTO `".$tablepre."bank_transfer` SELECT * FROM `".$tablepre."shop_bank`";
        $result = mysql_query($query) or die("Query failed : " . mysql_error());
        echo " ... <b>Done</b>";

        echo "<br />- Making customizations";
        $query = "ALTER TABLE ".$tablepre."bank_transfer CHANGE type tipe varchar(10) NOT NULL default ''";
        $result = mysql_query($query) or die("Query failed : " . mysql_error());

        $query = "ALTER TABLE ".$tablepre."bank_transfer COMMENT = 'FunForum: Bank Hack'";
        $result = mysql_query($query) or die("Query failed : " . mysql_error());
        echo " ... <b>Done</b>";

        echo "<br />- Adding attributes to new table";
        $query = "ALTER TABLE ".$tablepre."bank_transfer ADD `daynum` smallint(2) NOT NULL default '', ADD `monthnum` smallint(2) NOT NULL default '', ADD `yearnum` varchar(20) NOT NULL default '', ADD `type` varchar(10) NOT NULL default '', ADD `subject` varchar(40) NOT NULL default '', ADD `statusline` varchar(40) NOT NULL default '', ADD `confirmline` int(10) NOT NULL default '0', ADD `UndoI` TEXT";

        $result = mysql_query($query) or die("Query failed : " . mysql_error());
        echo " ... <b>Done</b>";

        echo "<br />- Inserting values";
        $query = "UPDATE ".$tablepre."bank_transfer SET UndoI = '0'";
        $result = mysql_query($query) or die("Query failed : " . mysql_error());

        $query = "UPDATE ".$tablepre."bank_transfer SET statusline = 'Completed'";
        $result = mysql_query($query) or die("Query failed : " . mysql_error());

        $query = "UPDATE ".$tablepre."bank_transfer SET type = 'Goods', subject = 'Shop Purchase' WHERE tipe = 'shop'";
        $result = mysql_query($query) or die("Query failed : " . mysql_error());

        $query = "UPDATE ".$tablepre."bank_transfer SET type = 'Instant' WHERE type = ''";
        $result = mysql_query($query) or die("Query failed : " . mysql_error());
        echo " ... <b>Done</b>";

        echo "<br />- Creating Index column";
        $query = "ALTER TABLE ".$tablepre."bank_transfer ORDER BY dateline";
        $result = mysql_query($query) or die("Query failed : " . mysql_error());

        $query = "ALTER TABLE ".$tablepre."bank_transfer ADD wireid int(10) NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST";
        $result = mysql_query($query) or die("Query failed : " . mysql_error());
        echo " ... <b>Done</b>";

        echo "<br />- Converting datestamps<br />";
        $buq = '0';
        $query = "SELECT * FROM ".$tablepre."bank_transfer WHERE wireid != '' ORDER BY wireid";
        $result = mysql_query($query) or die("Query failed : " . mysql_error());
        while ($details = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $loltime = $details[dateline];
            $att2 = gmdate("d", $loltime);
            $att4 = gmdate("n", $loltime);
            $att5 = gmdate("Y", $loltime);
            $query = $db->query("UPDATE ".$tablepre."bank_transfer SET monthnum = '$att4', yearnum = '$att5' WHERE wireid='$details[wireid]'");
            echo "."; if($buq == "50"){ $buq = '0'; echo "<br />"; }
        }
        echo "<br /><b>Done</b>";

        echo "<br />- Altering members table";
        $query = "ALTER TABLE ".$tablepre."members ADD `banksettings` varchar(10) NOT NULL default '1|1|1|1'";
        $result = mysql_query($query) or die("Query failed : " . mysql_error());
        echo " ... <b>Done</b>";

        echo "<br />- <b>Upgrading Complete</b>";
        echo '</font>';
    ?>
    <br />
    <br />
    <br />
    <br />
    <br /><a target="_self" href="<?php echo $PHP_SELF;?>?step=4">Click here to proceed with Step 4</a>
    <br />
    <br />

<?php } if($step == "4" || $step == "manual"){ 

    if($step == "4"){ ?>
        <br /><b>Step 4.</b>
        <br />
        <br />Now the upgrade script has done its job we can start the manual adjustment of a few files...
    <?php }else{ ?>
        <br />Here are the manual file adjustements ...
        <br />If you have already done the upgrade script (and the steps 1 - 5), this here is sufficient;
    <?php } ?>
<br />
<br /> - Upload "newbankrequest.gif" to: <?=$full_url?>images/
<br /> - Upload, if not already done, the bank.php to your forumdirectoy: <?=$full_url?>

<br />
<br /><b>
<br />Edit PHP File: header.php
<br /></b>

<br />Find:
<br /><textarea wrap="off" name="select1" rows="1" cols="100">, 'shop_bank',</textarea>
<br /><a class="highlighttext" href="javascript:HighlightAll('FunForum.select1')">Select All</a>
<br />

<br />Replace with:
<br /><textarea wrap="off" name="select2" rows="1" cols="100">, 'bank_transfer',</textarea>
<br /><a class="highlighttext" href="javascript:HighlightAll('FunForum.select2')">Select All</a>
<br />

<br />Find:
<br /><textarea wrap="off" name="select3" rows="1" cols="100">$links[] = " | <a href=\"shop.php\"><font class=\"navtd\">$lang_shop_title</font></a>"; // Edit $lang_shop_title in English.lang.php to change title</textarea>
<br /><a class="highlighttext" href="javascript:HighlightAll('FunForum.select3')">Select All</a>
<br />

<br />Add Above:
<br /><textarea wrap="off" name="select4" rows="1" cols="100">    $links[] = "| <a href=\"bank.php\"><font class=\"navtd\">$lang_shop_textbank</font></a>"; // Edit $lang_shop_textbank in English.shop.php to change title</textarea>
<br /><a class="highlighttext" href="javascript:HighlightAll('FunForum.select4')">Select All</a>
<br />


<br />
<br /><b>
<br />Edit PHP File: functions.php
<br /></b>


<br />Find:
<br /><textarea wrap="off" name="select5" rows="1" cols="100">function end_time() {</textarea>
<br /><a class="highlighttext" href="javascript:HighlightAll('FunForum.select5')">Select All</a>
<br />

<br />Add Above:
<br /><textarea wrap="off" name="select6" rows="19" cols="100">function do_bank_dates() { 
    GLOBAL $loltime, $att1, $att2, $att3, $att4, $att5, $att6, $att7, $att8; 
    $loltime = time(); 
    $att2 = gmdate("d", $loltime); 
    $att4 = gmdate("n", $loltime); 
    $att5 = gmdate("Y", $loltime); 
} 
function insert_bank_trans($fromuid, $touid, $amount, $comment='', $ttype, $subject='', $statusline='Completed') { 
    GLOBAL $db, $table_bank_transfer;
    GLOBAL $loltime, $att1, $att2, $att3, $att4, $att5, $att6, $att7, $att8; 
    if($fromuid == "" || $touid == "" || $amount == "" || $ttype == ""){ exit("Error code: Bank 0168"); } 
    do_bank_dates(); 
    $db->query("INSERT INTO $table_bank_transfer VALUES('', '$fromuid', '$touid', '$loltime', '$amount', '$comment', '$att2', '$att4', '$att5', '$ttype', '$subject', '$statusline', '0', '0')"); 
} </textarea>
<br /><a class="highlighttext" href="javascript:HighlightAll('FunForum.select6')">Select All</a>
<br />

<br />
<br />Ok... Now if you have any other things installed that link to the shop_bank.php you need to adjust those links.
<br />So if you have the "<a target="_blank" href="<?php echo $PHP_SELF;?>?step=moneylinks">Send Money Links In Member Profiles And Posts.txt</a>" installed for example, you'll have to adjust the link. 
<br />But just in case, we will add some stuff to the shop_bank.php just in case there are some links you forget or don't know about, the following will fix it.
<br />

<br />
<br /><b>
<br />Edit PHP File: member.php
<br /></b>

<br />
<br />Be carefull at this step now, watch the example!
<br />

<br />Find:
<br /><textarea wrap="off" name="selects3" rows="1" cols="100">$db->query("INSERT INTO $table_members</textarea>
<br /><a class="highlighttext" href="javascript:HighlightAll('FunForum.selects3')">Select All</a>
<br />

<br />Add to the end of the statement:
<br /><b>BEFORE:: ) </b>
<br /><textarea wrap="off" name="selectcp" rows="1" cols="100">, banksettings</textarea>
<br /><a class="highlighttext" href="javascript:HighlightAll('FunForum.selectcp')">Select All</a>
<br />

<br />Add to the end of the statement<br /><b>BEFORE::</b>  )");
<br /><textarea wrap="off" name="selects4" rows="1" cols="100">, ''</textarea>
<br /><a class="highlighttext" href="javascript:HighlightAll('FunForum.selects4')">Select All</a>
<br />


<br />Example:
<br /><i>This is an example, and nothing more then an example</i>
<br />
<br />$db->query("INSERT INTO $table_members 
<br />
<br />( uid, username, password, regdate, postnum, email, site, aim, status, location, bio, sig, showemail, timeoffset, icq, avatar, yahoo, customstatus, theme, bday, langfile, tpp, ppp, newsletter, regip, timeformat, msn, ban, dateformat, ignoreu2u, lastvisit, mood, pwdate, invisible, u2ufolders, saveogu2u, emailonu2u, useoldu2u, webcam<b>, banksettings</b> )
<br />
<br />VALUES ('', '$username', '$password', ".$db->time(time()).", '0', '$email', '$site', '$aim', '$self[status]',  '$locationnew', '$bio', '$sig', '$showemail', '$timeoffset1', '$icq', '$avatar', '$yahoo', '', '$thememem', '$bday', '$newlangfile', '$tpp', '$ppp',  '$newsletter', '$onlineip', '$timeformatnew', '$msn', '', '$dateformatnew', '', '', '$newmood', '', '0', '', '$saveogu2u', '$emailonu2u', '$useoldu2u', '$webcam'<b>, ''</b>)");
<br />





<br />Find:
<br /><textarea wrap="off" name="selects1" rows="1" cols="100">        $memberinfo = $db->fetch_array($db->query("SELECT * FROM $table_members WHERE username='$member'"));</textarea>
<br /><a class="highlighttext" href="javascript:HighlightAll('FunForum.selects1')">Select All</a>
<br />

<br />Add Above:
<br /><textarea wrap="off" name="selects2" rows="1" cols="100">        if($member == "$lang[bank_name]"){ header("Location: bank.php"); exit(); } </textarea>
<br /><a class="highlighttext" href="javascript:HighlightAll('FunForum.selects2')">Select All</a>
<br />


<br />
<br /><b>
<br />Edit PHP File: shop_bank.php
<br /></b>


<br />Find:
<br /><textarea wrap="off" name="select7" rows="1" cols="100">require "./header.php";</textarea>
<br /><a class="highlighttext" href="javascript:HighlightAll('FunForum.select7')">Select All</a>
<br />

<br />Replace With:
<br /><textarea wrap="off" name="select8" rows="1" cols="100">require "./header.php"; if(!$action){ header("Location: bank.php"); }elseif($action == "finances"){ header("Location: bank.php?folder=flow"); }elseif($action == "send" && $username){ header("Location: bank.php?folder=wire&username=$username"); }elseif($action == "send" && !$username){ header("Location: bank.php?folder=wire"); }else{ header("Location: bank.php"); } exit(); </textarea>
<br /><a class="highlighttext" href="javascript:HighlightAll('FunForum.select8')">Select All</a>
<br />


<br />
<br /><b>
<br />Edit PHP File: shop.php
<br /></b>

<br />Find:
<br />If you can't find this, then never mind this step.
<br /><textarea wrap="off" name="selectcp1" rows="3" cols="100">$thatime = time();

			$db->query("INSERT INTO $table_shop_bank VALUES(0, '$memberinfo[uid]', '$thatime', '$amount', '$item[itemname]', 'shop')");</textarea>
<br /><a class="highlighttext" href="javascript:HighlightAll('FunForum.selectcp1')">Select All</a>
<br />
<br />Replace With:
<br /><textarea wrap="off" name="selectcp2" rows="1" cols="100">insert_bank_trans('0', '$memberinfo[uid]', $amount, $item[itemname], 'Goods', 'Shop Purchase');</textarea>
<br /><a class="highlighttext" href="javascript:HighlightAll('FunForum.selectcp2')">Select All</a>
<br />



<br />
<br /><b>
<br />Edit PHP File: shop_item.php
<br /></b>

<br /><b>The thing you will now be asked to "find and replace" will originally appear 5 times in the shop_item.php
<br />Replace all 5 occasions ...</b>
<br />
<br />Find:
<br /><textarea wrap="off" name="select9" rows="3" cols="100">$thatime = time();

				$db->query("INSERT INTO $table_shop_bank VALUES('$memberinfo[uid]', 0, '$thatime', '$item[price]', '$item[itemname]', 'shop')");</textarea>
<br /><a class="highlighttext" href="javascript:HighlightAll('FunForum.select9')">Select All</a>
<br />
<br />Replace With:
<br /><textarea wrap="off" name="select10" rows="1" cols="100">insert_bank_trans($self[uid], '0', $item[price], $item[itemname], 'Goods', 'Shop Purchase');</textarea>
<br /><a class="highlighttext" href="javascript:HighlightAll('FunForum.select10')">Select All</a>
<br />

<br /><b>The following "find and replace" you will only encounter once</b>
<br />
<br />Find:
<br /><textarea wrap="off" name="select11" rows="1" cols="100">					$db->query("INSERT INTO $table_shop_bank VALUES(0, '$item[owner]', '$thatime', '$item[price]', '$item[itemname]', 'shop')");</textarea>
<br /><a class="highlighttext" href="javascript:HighlightAll('FunForum.select11')">Select All</a>
<br />

<br />Replace With:
<br /><textarea wrap="off" name="select12" rows="1" cols="100">insert_bank_trans('0', $item[owner], $item[price], $item[itemname], 'Goods', 'Shop Purchase');</textarea>
<br /><a class="highlighttext" href="javascript:HighlightAll('FunForum.select12')">Select All</a>
<br />


<br />
<br /><b>
<br />Edit Template: shop_userpanel
<br /></b>


<br />Find:
<br /><textarea wrap="off" name="select13" rows="1" cols="100">shop_bank.php</textarea>
<br /><a class="highlighttext" href="javascript:HighlightAll('FunForum.select13')">Select All</a>
<br />

<br />Replace With:
<br /><textarea wrap="off" name="select14" rows="1" cols="100">bank.php</textarea>
<br /><a class="highlighttext" href="javascript:HighlightAll('FunForum.select14')">Select All</a>
<br />


<br />
<br /><b>
<br />Edit Shops Language File
<br /></b>


<br />Find:
<br /><textarea wrap="off" name="select17" rows="1" cols="100">?></textarea>
<br /><a class="highlighttext" href="javascript:HighlightAll('FunForum.select17')">Select All</a>
<br />

<br />Replace With:
<br /><textarea wrap="off" name="select18" rows="95" cols="100">$lang['bank_name'] = "Bank";
$lang['bank_newreq1'] = "You have 1 new request";
$lang['bank_newrq2a'] = "You have";
$lang['bank_newrq2b'] = "new requests";
$lang['bank_status01'] = "Main";
$lang['bank_status02'] = "Account Settings";
$lang['bank_status03'] = "My $lang_shop_currency";
$lang['bank_status04'] = "Received $lang_shop_currency";
$lang['bank_status05'] = "Request $lang_shop_currency";
$lang['bank_status06'] = "Requested $lang_shop_currency";
$lang['bank_status07'] = "Sent $lang_shop_currency";
$lang['bank_status08'] = "Send $lang_shop_currency";
$lang['bank_status09'] = "Transfer Details";
$lang['bank_status10'] = "Undo a Transfer";
$lang['bank_status11'] = "Undo History";
$lang['bank_welcome'] = "<br /><br />Welcome to the $bbname - $lang[bank_name]!<br /><br /><br />Using the navigation bar on the right, you can maintain your finances.";
$lang['bank_five'] = "<br /><br /><br /><br /><br />";
$lang['bank_o01'] = "Total Amount of $lang_shop_currency:";
$lang['bank_o02'] = "Total Amount of Received $lang_shop_currency:";
$lang['bank_o03'] = "Total Amount of Sent $lang_shop_currency:";
$lang['bank_o04'] = "Do you want to receive a U2U when receiving $lang_shop_currency?";
$lang['bank_o05'] = "Do you want to receive a U2U when receiving a request for $lang_shop_currency?";
$lang['bank_o06'] = "Do you want to receive a U2U when one of your requests for $lang_shop_currency has been denied?";
$lang['bank_o07'] = "Do you want to receive a U2U when one of your requests for $lang_shop_currency has been forfilled?";
$lang['bank_o08'] = "Form Buttons:";
$lang['bank_o09'] = "Save Options";
$lang['bank_o10'] = "$lang[bank_five] Your settings have been saved.";
$lang['bank_o11'] = "Date";
$lang['bank_o12'] = "From";
$lang['bank_o13'] = "Type";
$lang['bank_o14'] = "Status";
$lang['bank_o15'] = "Subject";
$lang['bank_o16'] = "Amount";
$lang['bank_o17'] = "To";
$lang['bank_o18'] = "Click here for more information on Transfer";
$lang['bank_o19'] = "Total amount sent in";
$lang['bank_o20'] = "View Member's Profile";
$lang['bank_o21'] = "Recipient";
$lang['bank_o22'] = "Transfer";
$lang['bank_o23'] = "Request";
$lang['bank_o24'] = "Comment";
$lang['bank_o25'] = "$lang[bank_five] An error has occured.<br />You have not entered a Username to send the $lang_shop_currency to.";
$lang['bank_o26'] = "$lang[bank_five] An error has occured.<br />You have to select a Type.";
$lang['bank_o27'] = "$lang[bank_five] An error has occured.<br />You can not send $lang_shop_currency to a banned member.";
$lang['bank_o28'] = "$lang[bank_five] The $lang_shop_currency have been send successfully.";
$lang['bank_o29'] = "$lang[bank_five] The Request has been sent succesfully.";
$lang['bank_o30'] = "$lang[bank_five] An error has occured.<br />You can not send this amount of $lang_shop_currency because you don't have it.";
$lang['bank_o31'] = "Requested by me";
$lang['bank_o32'] = "Requested by others";
$lang['bank_o33'] = "Pay the requested amount";
$lang['bank_o34'] = "Deny the requested amount";
$lang['bank_o35'] = "Enter the Transfer ID you want more information about:";
$lang['bank_o36'] = "Show More Information";
$lang['bank_o37'] = "$lang[bank_five] An error has occured.<br />That Transfer ID is not valid.";
$lang['bank_o38'] = "$lang[bank_five] An error has occured.<br />You are not authorized to view this transactions information.";
$lang['bank_o39'] = "Information on Transfer";
$lang['bank_o40'] = "";
$lang['bank_o41'] = "Request From:";
$lang['bank_o42'] = "Request To:";
$lang['bank_o43'] = "<i>Not Yet...</i>";
$lang['bank_o44'] = "Request Sent Date:";
$lang['bank_o45'] = "Request Sent Time:";
$lang['bank_o46'] = "Request forfilled:";
$lang['bank_o47'] = "Time:";
$lang['bank_o48'] = "Sort Transaction:";
$lang['bank_o49'] = "This $lang_shop_currency transfer has been undone.";
$lang['bank_o50'] = "Undone on:";
$lang['bank_o51'] = "Undone at:";
$lang['bank_o52'] = "Undone by:";
$lang['bank_o53'] = "Here you can undo a $lang_shop_currency transfer, but be carefull with this...";
$lang['bank_o54'] = "Enter the TransferID of the transfer you want to undo:";
$lang['bank_o55'] = "Here you can enter a message for both the parties involved, that will be sent with the U2U they'll receive informing them that the transfer got undone.";
$lang['bank_o56'] = "Undo $lang_shop_currency Transfer";
$lang['bank_o57'] = "$lang[bank_five] That Transfer ID is not valid.";
$lang['bank_o58'] = "$lang[bank_five] The transfer has been undone.";
$lang['bank_o59'] = "Show ALL undone transfers";
$lang['bank_o60'] = "Show undone transfers listed per month";
$lang['bank_o61'] = "Transfer Date";
$lang['bank_o62'] = "Undone By";
$lang['bank_o63'] = "Undone On";
$lang['bank_o64'] = "Error...";
$lang['bank_o65'] = "$lang[bank_five] An error has occured.<br />The by you specified action doesn't excist.";
$lang['bank_o66'] = "Total amount";
$lang['bank_o67'] = "You are not loggedin.<br />You must be logged in to use this feature.";
$lang['bank_u2u_01'] = "You Received $lang_shop_currency";
$lang['bank_u2u_02'] = "Hi *moneyto*!<br /><br />This is just a U2U to inform you that you have received $lang_shop_textmoney from *moneyfrom*,<br />go check at the $lang[bank_name] to view the <a target=\"_blank\" href=\"bank.php?folder=info&amp;tid=*btid*\">details</a>.<br /><br /><br />Thank you for being a customer at our $lang[bank_name]";
$lang['bank_u2u_03'] = "You Received a Request for $lang_shop_currency";
$lang['bank_u2u_04'] = "Hi *moneyto*!<br /><br />This is just a U2U to inform you that you have received a Request for $lang_shop_textmoney from *moneyfrom*,<br />go check at the $lang[bank_name] to view the <a target=\"_blank\" href=\"bank.php?folder=info&amp;tid=*btid*\">details</a>.<br /><br /><br />Thank you for being a customer at our $lang[bank_name]";
$lang['bank_u2u_05'] = "*moneyfrom* Denied your Request";
$lang['bank_u2u_06'] = "Hey *moneyto*!<br /><br />This is just a U2U to inform you that your Request for $lang_shop_currency to *moneyfrom* has been denied,<br />go check on the $lang[bank_name] to view the details.<br /><br /><br />Thank you for being a customer at our $lang[bank_name]";
$lang['bank_u2u_07'] = "*moneyfrom* Payed your Request";
$lang['bank_u2u_08'] = "Hey *moneyto*!<br /><br />This is just a U2U to inform you that your Request for $lang_shop_currency to *moneyfrom* has been forfilled,<br />go check on the $lang[bank_name] to view the details.<br /><br /><br />Thank you for being a customer at our $lang[bank_name]";
$lang['bank_u2u_09'] = "$lang_shop_currency transfer cancelled";
$lang['bank_u2u_10'] = "One of your $lang_shop_currency transfers has been cancelled.<br />To view the details on the transfer, click here:<br /><a target=\"_blank\" href=\"bank.php?folder=info&tid=*btid*\">bank.php?folder=info&tid=*btid*</a>";
?></textarea>
<br /><a class="highlighttext" href="javascript:HighlightAll('FunForum.select18')">Select All</a>
<br />










    <?php if($step == "4"){ ?>
    <br />
    <br />
    <br />
    <br />
    <br />Now we are almost done, the only thing left is to open the Shop we closed before
    <br />
    <br />
    <br />
    <br />
    <br /><a target="_self" href="<?php echo $PHP_SELF;?>?step=5">Click here to open the Shop again</a>
    <br />
    <br />
    <?php } ?>

<?php } if($step == "5"){ 

    $squery = $db->query("SELECT temp_bank_install FROM ".$tablepre."shop_settings");
    $shopsettings = $db->result($squery, 0);

    $query = "UPDATE ".$tablepre."shop_settings SET whoview='$shopsettings'";
    $result = mysql_query($query) or die("Query failed : " . mysql_error());

    $query = "ALTER TABLE ".$tablepre."bank_transfer DROP `tipe`";
    $result = mysql_query($query) or die("Query failed : " . mysql_error());


//  $query = "ALTER TABLE ".$tablepre."shop_settings ADD `BankInstall` TEXT";
    $query = "ALTER TABLE ".$tablepre."shop_settings CHANGE temp_bank_install BankInstall TEXT";
    $result = mysql_query($query) or die("Query failed : " . mysql_error());
    $query = "UPDATE ".$tablepre."shop_settings SET BankInstall='FunForum'";
    $result = mysql_query($query) or die("Query failed : " . mysql_error());

    ?>
    <br /><b>Step 5.</b>
    <br />
    <br />The Shop is open again
    <br />The Bank has been installed.
    <br />Now it's time to test out the new Bank or just have a look at its features :)

    <br />
    <br />
    <br />
    <br />
    <br />You can either delete this php file or don't.
    <br />This doesn't really matter as the installation steps can only be used once. So it can't do any harm to leave the file uploaded.

    <br />
    <br />
    <br />
    <br />
    <br /><a target="_self" href="bank.php">Click here to go to the Bank</a>
    <br />
    <br />



<?php } if($step == "u1"){ ?>
    <br /><b>Upgrade, part 1.</b>
    <br />
    <br />If you are upgrading your Bank to this V2.4 build, you need to do a few things:
    <br />To prevent anything from going wrong, this file will do the SQL upgrade for you, the rest you'll have to do yourself;

<br />
<br />Start with uploading the new bank.php
<br />
<br /><b>
<br />Edit PHP File: functions.php
<br /></b>

<br />Find:
<br /><textarea wrap="off" name="select5" rows="19" cols="100">function do_bank_dates() { 
    GLOBAL $loltime, $att1, $att2, $att3, $att4, $att5, $att6, $att7, $att8; 
    $loltime = time(); 
    $att1 = gmdate("l", $loltime); 
    $att2 = gmdate("d", $loltime); 
    $att3 = gmdate("F", $loltime); 
    $att4 = gmdate("n", $loltime); 
    $att5 = gmdate("Y", $loltime); 
    $att6 = gmdate("H", $loltime); 
    $att7 = gmdate("i", $loltime); 
    $att8 = gmdate("s", $loltime); 
} 
function insert_bank_trans($fromuid, $touid, $amount, $comment='', $ttype, $subject='', $statusline='Completed') { 
    GLOBAL $db, $table_bank_transfer;
    GLOBAL $loltime, $att1, $att2, $att3, $att4, $att5, $att6, $att7, $att8; 
    if($fromuid == "" || $touid == "" || $amount == "" || $ttype == ""){ exit("Error code: Bank 0168"); } 
    do_bank_dates(); 
    $db->query("INSERT INTO $table_bank_transfer VALUES('', '$fromuid', '$touid', '$loltime', '$amount', '$comment', '$att1', '$att2', '$att3', '$att4', '$att5', '$att6', '$att7', '$att8', '$ttype', '$subject', '$statusline', '0', '0')"); 
} </textarea>
<br /><a class="highlighttext" href="javascript:HighlightAll('FunForum.select5')">Select All</a>
<br />

<br />Replace With:
<br /><textarea wrap="off" name="select6" rows="14" cols="100">function do_bank_dates() { 
    GLOBAL $loltime, $att2, $att4, $att5; 
    $loltime = time(); 
    $att2 = gmdate("d", $loltime); 
    $att4 = gmdate("n", $loltime); 
    $att5 = gmdate("Y", $loltime); 
} 
function insert_bank_trans($fromuid, $touid, $amount, $comment='', $ttype, $subject='', $statusline='Completed') { 
    GLOBAL $db, $table_bank_transfer;
    GLOBAL $loltime, $att2, $att4, $att5;
    if($fromuid == "" || $touid == "" || $amount == "" || $ttype == ""){ exit("Error code: Bank 0168"); } 
    do_bank_dates(); 
    $db->query("INSERT INTO $table_bank_transfer VALUES('', '$fromuid', '$touid', '$loltime', '$amount', '$comment', '$att2', '$att4', '$att5', '$ttype', '$subject', '$statusline', '0', '0')"); 
} </textarea>
<br /><a class="highlighttext" href="javascript:HighlightAll('FunForum.select6')">Select All</a>
<br />



<br />
<br /><b>
<br />Edit PHP File: shop.php
<br /></b>

<br />Find:
<br /><textarea wrap="off" name="selectcp1" rows="3" cols="100">$thatime = time();

			$db->query("INSERT INTO $table_shop_bank VALUES('0', '$memberinfo[uid]', '$thatime', '$amount', '$item[itemname]', 'shop')");</textarea>
<br /><a class="highlighttext" href="javascript:HighlightAll('FunForum.selectcp1')">Select All</a>
<br />
<br />Replace With:
<br /><textarea wrap="off" name="selectcp2" rows="1" cols="100">insert_bank_trans('0', '$memberinfo[uid]', $amount, $item[itemname], 'Goods', 'Shop Purchase');</textarea>
<br /><a class="highlighttext" href="javascript:HighlightAll('FunForum.selectcp2')">Select All</a>
<br />



    <br />
    <br /><a target="_self" href="<?php echo $PHP_SELF;?>?step=u2">Click here to perform the SQL upgrade</a>
    <br />
    <br />
    <br />
    <br />

<?php } if($step == "u2"){ ?>
    <br /><b>Upgrade, part 2.</b>
    <br />
    <?php
    $query = "ALTER TABLE ".$tablepre."bank_transfer DROP `daytxt`, DROP `monthtxt`, DROP `hournum`, DROP `minutenum`, DROP `secnum`";
    $result = mysql_query($query) or die("Query failed : " . mysql_error());
    ?>
    <br />The upgrade has been completed.
    <br />You may now close this file, or go to your board;
    <br /><a target="_self" href="<?php echo $full_url; ?>"><?php echo $full_url; ?></a>
    <br />
    <br />
    <br />
    <br />


<?php } if($step == "moneylinks"){ ?>

<font style="font-family: Courier New; font-size: 13px">
Description: This addon adds links in member profiles and posts to send money.<br />
<br />
Compatibility: XMB v1.9<br />
<br />
Code Designed By: nfpunk/Flixon<br />
<br />
Code updated for the Bank hack by: FunForum<br />
<br />
<br />
<br />
<br />
<br />
* edit member_profile template<br />
<br />
FIND<br />
--------------------------------------------------------------------------------<br />
(&lt;a href=&quot;#&quot; onclick=&quot;Popup('u2u.php?action=send&amp;amp;username=$encodeuser', 'Window', 700, 450);&quot;&gt;$lang[textu2u]&lt;/a&gt;)&amp;nbsp;<br />
--------------------------------------------------------------------------------<br />
<br />
ADD AFTER<br />
--------------------------------------------------------------------------------<br />
(&lt;a href=&quot;bank.php?folder=wire&amp;amp;username=$encodeuser&quot;&gt;Send $lang_shop_currency&lt;/a&gt;)&amp;nbsp;<br />
--------------------------------------------------------------------------------<br />
<br />
<br />
<br />
<br />
<br />
* edit viewthread_post template<br />
<br />
FIND<br />
--------------------------------------------------------------------------------<br />
$yahoo<br />
--------------------------------------------------------------------------------<br />
<br />
ADD AFTER<br />
--------------------------------------------------------------------------------<br />
&lt;a href=&quot;bank.php?folder=wire&amp;amp;username=$encodename&quot;&gt;&lt;img src=&quot;$imgdir/send.gif&quot; border=&quot;0&quot; alt=&quot;Send $lang_shop_currency&quot; /&gt;&lt;/a&gt;<br />
--------------------------------------------------------------------------------<br />
<br />
<br />
<br />
<br />
<br />
* you now have to upload a send.gif picture to your chosen themes image directory
</font>

<?php } if($step == "AlreadyInstalled"){ ?>

<br />Sorry, it looks like you have already installed the Bank.
<br />Therefor, most parts of this file can't be accessed anymore.
<br />The only parts that can still be accessed, can be found as links over here:
<br /><a target="_self" href="<?php echo $PHP_SELF;?>"><?php echo $PHP_SELF;?></a>
<br />
<?php } ?>


</form>
</body>
</html>
<?php

/************************************************************************/
/* Shoutbox for XMB 1.8 and 1.9 based on Xmb Ultimate Portal v2.0.0     */
/* ==================================================================== */
/*                    NAME -- SHOUT BOX                            */
/* ==================================================================== */
/*  Copyright (c) 2003 - 2004 by FREEWILL46 (freewill_46@hotmail.com)   */
/*  http://www.fw46.com/eforum                                          */
/************************************************************************/
require "./header.php";
loadtemplates('footer_load', 'footer_querynum', 'footer_phpsql', 'footer_totaltime', 'header', 'footer', 'css');

eval("\$css = \"".template('css')."\";");
echo $css;
eval("\$header = \"".template("header")."\";");
echo $header;
$prefix = "xmb";


$Longtimeformat = "M j Y, h:i A";  // Showing the Date and time format.

$num_shouts = 10; //Set this to the number of shouts you want to show per page.

$flood_control = 10; //num shouts a person can shout consecutivily (set to outrageously high number to disable)

$auto_delete = 1; //Auto Delete Shouts after some time? (1 yes, 0 no)

$days_delete = 5; //If above is set to 1, after how many days?

$use_shout = 1; // If the shout box are enabled or disabled (1 yes, 0 no)

$shoutbox = new shoutbox;

class shoutbox {

  var $usedhtml;
  var $printoutput   = "";

function shoutbox() {

 global $db, $use_shout, $num_shouts, $xmbuser, $status, $auto_delete, $prefix, $days_delete, $borderwidth, $tablespace, $bordercolor, $altbg1, $altbg2, $HTTP_POST_VARS, $HTTP_GET_VARS;

    $this->usedhtml = new templates_shoutbox;
    smcwcache();
    if($use_shout != 1){
    $this->ERROR("Shout box check","Sorry ! But the shout box has been disabled by admin you can not use it Right now.");
    }
    
	if ($auto_delete == 1){
		$time_del = $days_delete * 24 * 60 * 60;
		$db->query("DELETE FROM ".$prefix."_shoutbox WHERE time_time<'". (time() - $time_del) . "'");
	}

	if ($HTTP_POST_VARS['up'] <>"" AND $HTTP_POST_VARS['down']<> ""){
		$up = $HTTP_POST_VARS['up'];
		$down = $HTTP_POST_VARS['down'];

		$db->query("DELETE FROM ".$prefix."_shoutbox WHERE id >= $down AND id<= $up");
		$this->refresh();
	}

	if ($HTTP_POST_VARS['delall']<>""){
		$db->query("DELETE FROM ".$prefix."_shoutbox where name='".$HTTP_POST_VARS['delall']."'");
		$this->refresh();
	}

	if($HTTP_POST_VARS['message']<>""){
		$this->Post_Shout();
		$this->refresh();
	}

	if (isset($HTTP_GET_VARS['delete']) AND $HTTP_GET_VARS['delete'] !='0'){
		$db->query("DELETE FROM ".$prefix."_shoutbox WHERE id='".$HTTP_GET_VARS['delete']."'");
		$this->refresh();
	}

	$this->printoutput = $this->usedhtml->PageTop();
 
	$this->printoutput .= $this->usedhtml->begin_shoutbox();

	$this->printoutput .= $this->Render_Shouts();

	$this->printoutput .= $this->usedhtml->Content_Separate();

	$this->printoutput .= $this->Render_Top_Shouters();

	$this->printoutput .= "&nbsp;<a href='index.php'><u>Back To Home Page</u></a><br>&nbsp;";

	$this->printoutput .= $this->usedhtml->Submit_Box();

	$this->printoutput .= $this->Shouters_Today();

	$this->printoutput .= $this->usedhtml->PageFoot();

	if ($status == "Super Administrator" || $status == "Administrator"){
		$this->printoutput .= $this->usedhtml->AdminOptions();
	}

            echo $this->printoutput;
   
}

function Post_Shout() {
        global  $db, $prefix, $flood_control, $shoutsubmit, $HTTP_POST_VARS, $HTTP_GET_VARS, $use_shout, $xmbuser;

      $message = $HTTP_POST_VARS['message'];
      
      $message = stripslashes(htmlspecialchars($message));
      $message = postify($message, "no", "", "yes", "no");

	  $query = $db->query("SELECT name FROM ".$prefix."_shoutbox ORDER BY id DESC LIMIT " . $flood_control);
  	  $num1 = 0;
   
  	   while ($namearray=$db->fetch_array($query)){
		$name1 = $namearray['name'];
  
		if ($name1==$xmbuser){
			$num1++;
		}
  
		if ($num1 >= $flood_control){
            $this->ERROR("Flood control Check","Flood control enabled. Someone else must use the shoutbox before you can use it again");

		 }
   
  	     }

	if($use_shout != 1){
       $this->ERROR("Shout box check","Sorry ! But the shout box has been disabled by admin you can not use it Right now.");
	}


	if(!$xmbuser) {
       $this->ERROR("Guest posting Check","Sorry ! But Guests are not allowed to shout .. You must register first.");
	}
   
	$message = addslashes($message);
	$time = date("jS F Y");
	$sql="INSERT INTO ".$prefix."_shoutbox(id,name,message,time, time_time)VALUES('NULL','$xmbuser','$message','$time', ".time().")";
	$db->query($sql);

}


function get_date($date="", $method=""){
		global $Longtimeformat, $SETTINGS, $self;

		$this->time_options = array('LONG'   => $Longtimeformat);

		if (!$date){
            return '--';
        }
        if (empty($method)){
        	$method = 'LONG';
        }

        $this->timeoffset = (($self['timeoffset'] != "") ? $self['timeoffset'] : $self['timeoffset']) * 3600;

		if ($SETTINGS['addtime'] != "" and $SETTINGS['addtime'] != 0){
			$this->timeoffset += ($SETTINGS['addtime'] * 3600);
		}

        return gmdate($this->time_options[$method], ($date + $this->timeoffset) );

}

function Render_Shouts() {
        global $HTTP_POST_VARS, $db, $prefix, $start, $num_shouts, $table_members, $cattext, $status, $altbg2, $bordercolor;

	    $shouts = "";
	    $author = $HTTP_POST_VARS['author'];

    	if ($author<>""){
		$author_query1 = "&author=" . $author;
		$author_query2 = "AND s.name='".$author."'";
		$author_query3 = "WHERE name='".$author."'";
	   }
       if (!isset($start)) { $start = 0; }
	   $query = $db->query("SELECT m.uid AS mid, s.* FROM ".$prefix."_shoutbox s, $table_members m WHERE s.name=m.username $author_query2 ORDER BY id DESC LIMIT $start, $num_shouts");
  	   while($info= $db->fetch_array($query)){
        
		$info['message'] 	= 	stripslashes($info['message']);
		$info['message'] 	=	preg_replace("/([^\n\r ?&\.\/<>\"\\-]{35})/i"," \\1<br>",$info['message']);


		if ($status == "Super Administrator" || $status == "Administrator"){
			$shoutdelete ="{ <a href='shoutbox.php?action=Shoutbox&delete=".$info['id']."'>Delete</a> }";
		}

		if ($info['time_time']<>'0'){
			$info['time'] = $this->get_date( $info['time_time'], 'LONG');
		}


		$shouts .= "<div style=\"font-size: 10px; font-family: Trebuchet MS, Verdana, sans-serif; margin: 5px 0;\">[$info[id]]  <b>Posted By</b>&nbsp;&nbsp;<a href=\"member.php?action=viewpro&member=$info[name]\"><span style=\"font-size: 13px; font-weight: bold; color: $altbg1;\"><b>$info[name]</b></a></span> - <b>$info[time]</b>&nbsp;&nbsp; $shoutdelete <br><hr size=1 color=$bordercolor><blockquote style=\"font-family: Trebuchet MS, Verdana, sans-serif; font-size: 12px; font-weight: bold; color: $altbg1; background:$altbg2; margin: 0 2px; padding: 11px 6px 11px 6px; border: $bordercolor; border-style: solid; border-left-width: 1px; border-top-width: 1px; border-right-width: 1px; border-bottom-width: 1px\">$info[message]</blockquote></div><br>";
	}


	$query = $db->query("SELECT count(*) as count FROM ".$prefix."_shoutbox $author_query3");
	$stuff = $db->fetch_array($query);
	$count = $stuff['count'];

	if ($start >= $num_shouts){
		$shouts .= "<a href='shoutbox.php?action=Shoutbox&start=".($start-$num_shouts)."'><font class=nav>Prev Page</font></a>&nbsp;";
	}
	if ($start+$num_shouts < $count){
		$shouts .= "<a href='shoutbox.php?action=Shoutbox&start=".($start+$num_shouts)."'><font class=nav>Next Page</font></a>";
	}

	return $shouts;
}

function Render_Top_Shouters() {
   global $db, $prefix, $catbgcode, $tablespace, $borderwidth, $bordercolor,$altbg2, $altbg1, $table_members;
   
	$top_shouters = "";
	$query = $db->query("SELECT s.name, COUNT(s.name) as count, m.uid FROM ".$prefix."_shoutbox s, $table_members m WHERE s.name=m.username GROUP BY s.name ORDER by count DESC LIMIT 10");
	while ($r = $db->fetch_array($query)){
    $top_shouter .= "<tr><td bgcolor=\"$altbg2\" class='tablerow'><a href=\"member.php?action=viewpro&member=$r[name]\">$r[name]</a></td><td bgcolor=\"$altbg2\" class='tablerow'>$r[count]</td></tr>";
      }
      
    $query2 = $db->query("SELECT COUNT(*) as count FROM ".$prefix."_shoutbox");
	$stuff = $db->fetch_array($query2);
	$count1 = $stuff['count'];
    $top_shouters = "<br /><table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\" bgcolor=\"$bordercolor\"><tr><td><table border=\"0\" cellspacing=\"$borderwidth\" cellpadding=\"$tablespace\" width=\"100%\">
    <tr><td bgcolor=\"$altbg1\" class='header' $catbgcode colspan='2' align='left'> <b><font face=\"Times New Roman\">»</font></b> <b>All Time Top Shouters </b></td></tr>
    <tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Shouter</b></td><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Shouts</b></td></tr>
    $top_shouter<tr><td bgcolor=\"$altbg2\" class=\"tablerow\" colspan='2' align='center'><b>Total Shouts: $count1 </b></td></tr></table></td></tr></table><br />";

	return $top_shouters;

}

function Shouters_Today() {
        global $db, $prefix, $catbgcode, $tablespace, $borderwidth, $bordercolor,$altbg2, $altbg1, $table_members;

	$shouters = "";
	$time = date("jS F Y");
	$query = $db->query("SELECT  s.*, m.*, COUNT(s.name) as count FROM ".$prefix."_shoutbox s LEFT JOIN $table_members m ON s.name=m.username WHERE s.name=m.username AND s.time='$time' GROUP BY s.name ORDER by count DESC LIMIT 10");
	 while ($r = $db->fetch_array($query)){
     $shouter .= "<tr><td bgcolor=\"$altbg2\" class='tablerow'><a href=\"member.php?action=viewpro&member=$r[name]\">$r[name]</a></td><td bgcolor=\"$altbg2\" class='tablerow'>$r[count]</td></tr>";

      }

	$query2 = $db->query("SELECT COUNT(*) as count2 FROM ".$prefix."_shoutbox WHERE time='$time'");
	$stuff = $db->fetch_array($query2);
	$count = $stuff['count2'];
    $shouters .= "<br><table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\" bgcolor=\"$bordercolor\"><tr><td><table border=\"0\" cellspacing=\"$borderwidth\" cellpadding=\"$tablespace\" width=\"100%\"><tr><td bgcolor=\"$altbg1\" class=\"header\" $catbgcode colspan=\"2\" align=\"left\"><b><font face=\"Times New Roman\">»</font></b><b> Today's Top Shouters</b> </td></tr><tr><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Shouter</b></td><td bgcolor=\"$altbg1\" class=\"tablerow\"><b>Shouts</b></td></tr>$shouter<tr><td bgcolor=\"$altbg2\" class=\"tablerow\" colspan=\"2\" align=\"center\"><b>Shouts Today: $count</b></td></tr></table></td></tr></table>";

    return $shouters;

}

function refresh() {
                echo "<script>function redirect(){window.location.replace('shoutbox.php');}setTimeout('redirect();', 0);</script>";
		
		end_time();
		exit();
	}

function ERROR($title,$errmessage){
        extract($GLOBALS);
		echo "<center><b>$errmessage</b><br><br>"
        ."[ <a href=\"javascript:history.go(-1)\">Go Back</a> ]";
		end_time();
        eval("\$footer = \"".template("footer")."\";");
       	echo $footer;
		die();
   }
}


class templates_shoutbox {

function PageFoot() {
global $catbgcode;
return <<<EOF
</td></tr></table>
</table>
<tr><td class="category" $catbgcode><center><a href="http://www.fw46.com/eforum"><font size="1" color="#000000" face="Verdana"><b>Copyright &copy 2003 - 2004 ShoutBox by FREEWILL46</b></a></font></center></td></tr></table>
</body></html>
EOF;
}

function AdminOptions() {
global $tablewidth, $bordercolor, $altbg2, $altbg1, $borderwidth, $tablespace, $catbgcode;
return <<<EOF
<br />
<table width="$tablewidth" border="0" cellspacing="0" cellpadding="0" bgcolor="$bordercolor" align="center"><tr><td class="tablerow">
<table width="100%" border="0" cellspacing="$borderwidth" cellpadding="$tablespace"><tr><td class="category" $catbgcode>&nbsp;<b>Admin Options</b></td></tr>
<form action='shoutbox.php?action=Shoutbox' method='post'>
<tr>
<td bgcolor="$altbg1" class='tablerow' width='95%'>&nbsp;<b>Delete all shouts from id</b>&nbsp;<input type='text' size='4' name='down'>&nbsp;<b>to</b>&nbsp;<input type='text' size='4' name='up'><b>&nbsp;OR Delete all shouts by user</b>&nbsp;<input type='text' size='20' name='delall'>&nbsp;&nbsp;&nbsp;<input type='submit' style=" border-style:solid; border-width:1; padding:1; background-color : #EFEFEF; color : #000000; font-size: 11px; font-family: Verdana, Arial, Helvetica, sans-serif" value='Go Now'></td>
</tr></table></td></tr></table></form>
<br />
EOF;
}

function Submit_Box() {
global $Shoutbox, $altbg1, $altbg2, $bordercolor;
return <<<EOF

<br />
<form action='shoutbox.php?action=Shoutbox' name='REPLIER' method='post' onsubmit='REPLIER.shoutsubmit.disabled = true;'>
<b>Your Shout:</b>
<br />
<textarea name="message" style="color: #505050; width: 100%; border: 1px solid $bordercolor; margin: 0; padding: 1px; background: $altbg1 no-repeat center" ROWS="6" COLS="40"></textarea>
<br />
[<b>Smilies:</b> Enabled | <b>Html:</b> Disabled  | <b>BBcode:</b> Enabled]
<br />
<br />
<input type='submit' name='shoutsubmit' style=" border-style:solid; border-width:1; padding:1; background-color : #EFEFEF; color : #000000; font-size: 11px; font-family: Verdana, Arial, Helvetica, sans-serif" value='shout now'>
<br />
</form>

EOF;
}

function Content_Separate() {
global $altbg1;
return <<<EOF
</td>
<td bgcolor="$altbg1" class="tablerow" valign='top' align='center' width='50%'>
EOF;
}

function PageTop() {
global $borderwidth, $tablewidth, $tablespace, $bordercolor, $altbg2, $altbg1, $bbname, $smilieinsert;
return <<<EOF

<table width="$tablewidth" border="0" cellspacing="1" cellpadding="0" bgcolor="$bordercolor" align="center"><tr><td class="tablerow">
<table width="100%" border="0" cellspacing="1" cellpadding="8" bgcolor="$altbg2"><tr><td class="tablerow">
<center><font class="subject"><b>$bbname</b></font></center><br>
<center><h2>Shout Box</h2></center>
</td></tr></table></td></tr></table>
<br>
<table width="$tablewidth" border="0" cellspacing="1" cellpadding="0" bgcolor="$bordercolor" align="center"><tr><td class="tablerow">
<table width="100%" border="0" cellspacing="1" cellpadding="8" bgcolor="$altbg1"><tr><td class="tablerow">
EOF;
}

function begin_shoutbox() {
return <<<EOF
<tr><td class='tablerow'>
<table border="0" cellspacing="0" cellpadding="10" width='100%'>
<tr><td vAlign='top' align='left' width='50%'>
EOF;
 }
}

end_time();
eval("\$footer = \"".template("footer")."\";");
echo $footer;
?>
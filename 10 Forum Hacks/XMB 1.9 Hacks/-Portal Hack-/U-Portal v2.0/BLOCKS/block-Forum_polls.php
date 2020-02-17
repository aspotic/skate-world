<?php

/************************************************************************/
/* Xmb Ultimate Portal v2.0.0                                           */
/* ==================================================================== */
/*  Copyright (c) 2003 - 2004 by FREEWILL46 (freewill_46@hotmail.com)   */
/*  http://www.fw46.com/eforum                                          */
/*======================================================================*/
/* BASED ON PHP-NUKE: Advanced Content Management System                */
/* =====================================================================*/
/* Copyright (c) 2002 by Francisco Burzi                                */
/* http://phpnuke.org                                                   */
/************************************************************************/

if (eregi("block-Forum_polls.php", $_SERVER['PHP_SELF'])) {
    Header("Location: portal.php");
    die();
}

global $table_posts, $table_threads, $xmbuser;
$portal_pollfid = 1; // Forum Id Where the poll thread is

     $queryww = mysql_query("SELECT * FROM $table_threads WHERE fid='$portal_pollfid' AND pollopts !=''");
     $thread = mysql_fetch_array($queryww);
     $tid = $thread['tid'];
     $thread['subject'] = censor($thread['subject']);
     $subject = stripslashes($thread['subject']);
     $icon = $thread['icon'];
     $icon = "<img src=\"$smdir/$icon\" border=\"0\">";
     $polltitle .= "<font class=\"mediumtxt\"><b>$subject</b></font>";
     $comments = $thread['replies'];
     if ($comments == "1"){
    	$comment_txt = "comment";
     }else{
	    $comment_txt = "Comments";
}

    if ($comments < 0){
	    $comments = 0;
}
        $comments = "<a href=\"viewthread.php?tid=$tid\">$comments $comment_txt</a> |";
    if($thread['closed'] == "yes") {
        $postcomment = "Closed";
       } else {
       $postcomment = "<a href=\"post.php?action=reply&fid=$portal_pollfid&tid=$tid\">Post a Comment</a>";

}

     if($thread['pollopts'] != "" && $forum['pollstatus'] != "off" && $thread['closed'] != "yes") {
        $thread['pollopts'] = str_replace("\n", "", $thread['pollopts']);
        $pollops = explode("#|#", $thread['pollopts']);
	 if(strstr($thread['pollopts']." ", " ".$xmbuser." ") OR $view_results == "yes" OR !$xmbuser) {
		$viewing_results = "yes";
		for($pnum = 0; $pnum < 20; $pnum++) {
		if($pollops[$pnum] != "" && substr($pollops[$pnum],0,1)!=" ") {
		$thispollnum = eregi_replace(".*\|\|~\|~\|\| ", "", $pollops[$pnum]);
		$totpollvotes += $thispollnum;
			}
		}
		for($pnum = 0; $pnum < 20; $pnum++) {
       if($pollops[$pnum] != "" && substr($pollops[$pnum],0,1)!=" ") {
          $thispoll = explode("||~|~|| ", $pollops[$pnum]);
				if($totpollvotes != 0) {
					$thisnum = $thispoll[1]*100/$totpollvotes;
				} else {
				$thisnum = "0";
				}
				if($thisnum != "0") {
					$thisnum = round($thisnum, 2);
					$pollimgnum = round($thisnum)/3;
					for($num = 0; $num < $pollimgnum - 6; $num++) {
					$pollbar .= "<img src=\"$imgdir/pollbar.gif\">";
					}
				}
				$thisnum .= "%";
				if($thisnum == "0%") {
					$pollbar = "";
				}
				if ($viewing_results != "yes"){
					$results_link = "<a href=\"$PHP_SELF?action=$action&fid=$fid&dlid=$dlid&view_results=yes\">Results</a>";
				}elseif ($viewing_results == "yes" AND $xmbuser AND !strstr($thread['pollopts']." ", " ".$xmbuser." ")) {
                $results_link = "<a href=\"$PHP_SELF?action=$action&fid=$fid&dlid=$dlid\">Vote</a>";
				}elseif (!$xmbuser){
                $results_link = "<a href=\"misc.php?action=login\">Login To Vote</a>";
				}else{
                $results_link = "<b>Voted Already</b>";
				}
				$pollhtml .= "<tr><td bgcolor=\"$altbg2\" colspan=\"2\" width=\"180\"><font class=\"smalltxt\">$thispoll[0]</font><br>\n";
				$pollhtml .= "<img src=\"$imgdir/pollbar-s.gif\">$pollbar<img src=\"$imgdir/pollbar-e.gif\"><br>\n";
				$pollhtml .= "<font class=\"smalltxt\">$thispoll[1] ($thisnum)</font></td></tr>\n";
				$pollbar = "";

			   }
		     }
	        } else {
   		      for($pnum = 0; $pnum < 10; $pnum++) {
		  	  if($pollops[$pnum] != "" && substr($pollops[$pnum],0,1)!=" ") {
				 $thispoll = explode("||~|~|| ", $pollops[$pnum]);
				 $pollhtml .= "<tr><td class=\"tablerow\" bgcolor=\"$altbg2\" colspan=\"2\" width=\"180\">\n";
				 $pollhtml .= "<input type=\"radio\" name=\"postopnum\" value=\"$pnum\">\n";
				 $pollhtml .= "<font class=\"smalltxt\">$thispoll[0]</font></td></tr>\n";
			     }
		        }
               }
          
	        if (!$xmbuser) $buttoncode = "<font class=\"mediumtxt\"><i><a href=\"misc.php?action=login\">Log in</a> or <a href=\"member.php?action=reg\">register</a> to vote.</i></font>";
	        if(strstr($thread['pollopts']." ", " ".$xmbuser." ")) {
		      $buttoncode = "<font class=\"mediumtxt\"><i><b>You have already voted in this poll.</b></i></font>\n";
	        }elseif(!strstr($thread['pollopts']." ", " ".$xmbuser." ") AND $xmbuser)  {
		      $buttoncode = "<input type=\"submit\" value=\"Vote\">";
	        }
	          $poll = "<form method=\"post\" name=\"input\" action=\"topicadmin.php?action=votepoll&fid=$fid&tid=$tid\">\n";
              $poll .= "$pollhtml<tr><td bgcolor=\"$altbg2\"><center>$buttoncode<br>\n";
	          $poll .= "<font class=\"smalltxt\">[ $comments $postcomment ]<br>\n";
	          $poll .= "[ <a href=\"forumdisplay.php?fid=$portal_pollfid\">Other Polls</a> ]</font></center>\n";
	          $poll .= "<input type=\"hidden\" name=\"currpoll\" value=\"$thread[pollopts]\"></td></tr></form>\n";
}
$content = "<tr><td width=\"100%\" bgcolor=\"$altbg2\" colspan=\"2\" class=\"tablerow\">
<center><b><a href=\"viewthread.php?tid=$tid\"> Latest Voting Poll</b></a></center><br>$polltitle<br>$poll</td></tr>";
?>

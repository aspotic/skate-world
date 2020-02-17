<?php

/******************
      fusion news management
      by the fusion team

      version 3.6.1

      fusionphp.net
      Last edited on 02-04-2003
      archive.php_version: 3.6.0

     Copyright © 2003 FusionPHP.net

     This program is free software; you can redistribute it and/or
     modify it under the terms of the GNU General Public License as
     published by the Free Software Foundation; either version 2 of
     the License, or (at your option) any later version.

     This program is distributed in the hope that it will be useful,
     but WITHOUT ANY WARRANTY; without even the implied warranty of
     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
     GNU General Public License for more details.

     You should have received a copy of the GNU General Public License
     along with this program; if not, write to the Free Software
     Foundation, Inc., 59 Temple Place, Suite 330, Boston,
     MA 02111-1307 USA

     ALSO

     You cannot sell part, or the whole, of it's code.
     You cannot claim part or the whole of it's code to be yours.
     The copyright notice in the admin panel must stay instact.
     Further more Fusion News starting from 3.6 is now released
     under de GNU GPL license, please read the license.txt file
     included in the zip root. You MUST agree do this license and
     the GNU GPL license to use this script in any way.

******************/
error_reporting(E_ALL ^ E_NOTICE);

require "./config.php";
require "./functions.php";

echo get_template('header.php', TRUE);

$VARS = parse_incoming();

if(!isset($VARS["show"])){ $show = "";}else{$show = $VARS["show"];}

if( $show == "" ){ /*id Index*/
  $file = file($fpath."news/toc.php");
  my_array_shift( $file );
  if( $flip_news == "checked" )
    array_flip($file);
    
  $array = array();
  foreach( $file as $value){
    list($news_id,$news_date,$news_writer,$news_subject) = explode("|<|", $value);
    $time = strtotime( date("F 01 y", $news_date) );
    $allready_added = FALSE;
    foreach( $array as $val ){
      if( $time == $val )
        $allready_added = TRUE;
    }
    if( !$allready_added )
      $array[] = $time;
  }
  foreach( $array as $val ){
    $month = date("F", $val);
    $year  = date("Y", $val);
    echo "<a href=\"".$furl."/archive.php?show=month&month=".$month."&year=".$year."\">".$month." ".$year."</a><br>\n";
  }
}

elseif( $show == "month" ){ /*id Month*/
  if(!isset($VARS["month"])){ $month = "";}else{$month = $VARS["month"];}
  if(!isset($VARS["year"])){ $year = "";}else{$year = $VARS["year"];}
  if( $month == "" || $year == "" ) die("No parameters");

  $file = file($fpath."news/toc.php");
  my_array_shift( $file );
  $array = array();
  if ( $post_per_day == "checked" ){
    $ppp_data = array();
  }
  $data = "";
  foreach( $file as $value){
    list($news_id,$news_date,$news_writer,$news_subject) = explode("|<|", $value);
    $time = strtotime( date("F 01 y", $news_date) );
    if( $time == strtotime( $month." 01 ".$year ) ){
      $file_news = file( $fpath."news/news.".$news_id.".php" );
      list($news_short,$news_full,$news_writer,$news_subject,$news_email,$news_icon,$news_date,$news_comment_count) = explode("|<|", $file_news[1]);
         if($news_full == ""){
           $fullnews_link = "";
         }else{
         if($fsnw == "checked") {
           $fullnews_link = "<a href='#".$news_id."' onClick=window.open(\"$furl/fullnews.php?id=".$news_id."\",\"\",\"height=$fullnewsh,width=$fullnewsw,toolbar=no,menubar=no,scrollbars=".checkvalue($fullnewss).",resizable=".checkvalue($fullnewsz)."\")>$fslink</a>";
         } else{
           $fullnews_link = "<a href=\"$furl/fullnews.php?id=".$news_id."\">$fslink</a>";
         }
         }
       //use icon?
       if($news_icon == ""){
         $news_icon = "";
       }else{
         $news_icon = "<img src=\"".$news_icon."\">";
       }
       if($news_comment_count < 0 or $news_comment_count == "")
         $news_comment_count = 0;
       if($cbwordwrap){
         $news_short = fusion_wordwrap($news_short, $wwwidth);
       }
       if($wfpost == "checked"){
         $news_subject = filterbadwords($news_subject);
         $news_short = filterbadwords($news_short);
       }
       //html tags
       $news_subject = strip_tags($news_subject);
       if($ht == "checked"){
         $news_short = unhtmlentities($news_short);
         $news_short = str_replace("<?", "&lt;?", $news_short);
       }
       if($smilies == "checked"){
         $news_short = InsertSmillies($news_short,$furl);
       }
       //bbcode
       if($bb == "checked"){
         $news_short = InsertBBCode($news_short);
       }
       //replace user variables
       $news_writer = findwriter($news_writer);
       
       list($showemail, $femail) = explode("=", $news_email);
       if($showemail == ""){
         $news_email = "<a href=\"mailto:".$femail."\">".$news_writer."</a>";
       }else{
         $news_email = $news_writer;
       }
       $news_short = str_replace( "&amp;", "&", $news_short);
       $news_short = str_replace(" &br;", "<br>", $news_short);

       $tem = get_template('arch_news_temp.php', TRUE);
       $tem = str_replace("{subject}", $news_subject, $tem);
       $tem = str_replace("{user}", $news_email, $tem);
       $tem = str_replace("{date}", date($datefor, $news_date), $tem);
       $tem = str_replace("{news}", $news_short, $tem);
       $tem = str_replace("{icon}", $news_icon, $tem);
       $tem = str_replace("{fullstory}", $fullnews_link, $tem);
       $tem = str_replace("{nrc}", $news_comment_count, $tem);
       $tem = str_replace("{comments}", "<a href=\"$furl/comments.php?id=".$news_id."\">$pclink</a>", $tem);
       if($stfpop == "checked") {
         $tem = str_replace("{send}", "<a href='#' onClick=window.open(\"$furl/send.php?id=".$news_id."\",\"\",\"height=$stfheight,width=$stfwidth,toolbar=no,menubar=no,scrollbars=".checkvalue($stfscrolls).",resizable=".checkvalue($stfresize)."\")>$stflink</a>", $tem);
       }else{
         $tem = str_replace("{send}", "<a href=\"$furl/send.php?id=".$news_id."\">$stflink</a>", $tem);
       }
       if ( $post_per_day == "checked" ){
         if( !isset($ppp_data[date("d", $news_date)."_".date("m", $news_date)."_".date("Y", $news_date)]) )
             $ppp_data[date("d", $news_date)."_".date("m", $news_date)."_".date("Y", $news_date)] = "";
         $ppp_data[date("d", $news_date)."_".date("m", $news_date)."_".date("Y", $news_date)] .= $tem;
       }else{
         echo $tem;
       }
    }
  }
  if ( $post_per_day == "checked" ){
    krsort( $ppp_data );
    $temp = get_template('news_a_day_temp.php', TRUE);
    while (list ($key, $value) = each ($ppp_data)){
      list($day,$month,$year) = explode("_", $key);
      $insert_date = str_replace("{date}", date("l",strtotime("$month/$day/$year")).", ".$months[$month]." ".$day." ".$year, $temp);
      echo str_replace("{news_a_day}", $value, $insert_date);
    }
  }
}
echo get_template('footer.php', TRUE);
?>

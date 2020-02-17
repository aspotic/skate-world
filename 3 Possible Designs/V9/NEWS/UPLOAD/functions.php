<?php

/******************
fusion news management
by the fusion team

version 3.6.1

fusionphp.net
Last edited on 02-04-2003

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
require "./language.db";

function InsertBBCode($fusnewsm){
// open tags risk wrong formatting of the whole newspage... open tags should be dealth with
  $bbcodes_open  = array("[move]","[sub]","[tt]","[sup]","[s]","[b]","[i]","[u]","[list]","[quote]","[code]");
  $bbcodes_close = array("[/move]","[/sub]","[/tt]","[/sup]","[/s]","[/b]","[/i]","[/u]","[/list]","[/quote]","[/code]");
  
  for($i = 0; $i < 11; $i++ ){
     $bbcode_open = $bbcodes_open[$i];
     $bbcode_close = $bbcodes_close[$i];
     
     $open_cnt = substr_count( $fusnewsm, $bbcode_open );
     $close_cnt = substr_count( $fusnewsm, $bbcode_close );
     $div = abs( $open_cnt - $close_cnt );
     
     if ( $open_cnt > $close_cnt ){
       for ($i = 0; $i < $div; $i++ )
         $fusnewsm .= $bbcode_close;
     }
     if ( $open_cnt < $close_cnt ){
       for ($i = 0; $i < $div; $i++ )
         $fusnewsm = $bbcode_open.$fusnewsm;
     }
  }

$fusnewsm = str_replace("[move]", "<marquee>", $fusnewsm);
$fusnewsm = str_replace("[/move]", "</marquee>", $fusnewsm);
$fusnewsm = str_replace("[sub]", "<sub>", $fusnewsm);
$fusnewsm = str_replace("[/sub]", "</sub>", $fusnewsm);
$fusnewsm = str_replace("[tt]", "<tt>", $fusnewsm);
$fusnewsm = str_replace("[/tt]", "</tt>", $fusnewsm);
$fusnewsm = str_replace("[sup]", "<sup>", $fusnewsm);
$fusnewsm = str_replace("[/sup]", "</sup>", $fusnewsm);
$fusnewsm = str_replace("[s]", "<s>", $fusnewsm);
$fusnewsm = str_replace("[/s]", "</s>", $fusnewsm);
$fusnewsm = str_replace("[b]", "<b>", $fusnewsm);
$fusnewsm = str_replace("[/b]", "</b>", $fusnewsm);
$fusnewsm = str_replace("[i]", "<i>", $fusnewsm);
$fusnewsm = str_replace("[/i]", "</i>", $fusnewsm);
$fusnewsm = str_replace("[u]", "<u>", $fusnewsm);
$fusnewsm = str_replace("[/u]", "</u>", $fusnewsm);
$fusnewsm = str_replace("[list]", "<ul>", $fusnewsm);
$fusnewsm = str_replace("[/list]", "</ul>", $fusnewsm);
$fusnewsm = str_replace("[quote]", "<blockquote><span class=\"12px\">quote:</span><hr>", $fusnewsm);
$fusnewsm = str_replace("[/quote]", "<hr></blockquote>", $fusnewsm);
$fusnewsm = str_replace("[code]","<blockquote><span class=\"12px\">code:</span><hr><pre>",$fusnewsm);
$fusnewsm = str_replace("[/code]","</pre><hr></blockquote>",$fusnewsm);

$fusnewsm = str_replace("[*]", "<li>", $fusnewsm);
$fusnewsm = str_replace("[hr]", "<hr>", $fusnewsm);
$fusnewsm = eregi_replace("\\[color=([^\\[]*)\\]([^\\[]*)\\[/color\\]","<font color=\"\\1\">\\2</font>",$fusnewsm);
$fusnewsm = eregi_replace("\\[size=([^\\[]*)\\]([^\\[]*)\\[/size\\]","<font size=\"\\1\">\\2</font>",$fusnewsm);
$fusnewsm = eregi_replace("\\[font=([^\\[]*)\\]([^\\[]*)\\[/font\\]","<font face=\"\\1\">\\2</font>",$fusnewsm);
$fusnewsm = eregi_replace("\\[img height=([^\\[]*)\\ width=([^\\[]*)\\]([^\\[]*)\\[/img\\]","<img src=\"\\3\" height=\"\\1\" width=\"\\2\">",$fusnewsm);
$fusnewsm = eregi_replace("\\[img width=([^\\[]*)\\ height=([^\\[]*)\\]([^\\[]*)\\[/img\\]","<img src=\"\\3\" width=\"\\1\" height=\"\\2\">",$fusnewsm);
$fusnewsm = eregi_replace("\\[img]([^\\[]*)\\[/img\\]","<img src=\"\\1\">",$fusnewsm);
$fusnewsm = eregi_replace("\\[flash=([^\\[]*)\\,([^\\[]*)\\]([^\\[]*)\\[/flash\\]","<object classid=\"clsid: D27CDB6E-AE6D-11cf-96B8-444553540000\" width=\\1 height=\\2><param name=movie value=\\3><param name=play value=true><param name=loop value=true><param name=quality value=high><embed src=\\3 width=\\1 height=\\2 play=true loop=true quality=high></embed></object>",$fusnewsm);
$fusnewsm = eregi_replace("\\[align=([^\\[]*)\\]([^\\[]*)\\[/align\\]","<p align=\"\\1\">\\2</p>",$fusnewsm);
$fusnewsm = eregi_replace("\\[shadow=([^\\[]*)\\,([^\\[]*)\\,([^\\[]*)\\]([^\\[]*)\\[/shadow\\]","<font style=\"Filter: Shadow(color=\\1, Direction=\\2); Width=\\3px;\">\\4</font>",$fusnewsm);
$fusnewsm = eregi_replace("\\[glow=([^\\[]*)\\,([^\\[]*)\\,([^\\[]*)\\]([^\\[]*)\\[/glow\\]","<font style=\"Filter: Glow(color=\\1, Strength=\\2); Width=\\3px;\">\\4</font>",$fusnewsm);
$fusnewsm = eregi_replace("\\[email\\]([^\\[]*)\\[/email\\]", "<a href=\"mailto:\\1\">\\1</a>",$fusnewsm);
$fusnewsm = eregi_replace("\\[email=([^\\[]*)\\]([^\\[]*)\\[/email\\]", "<a href=\"mailto:\\1\">\\2</a>",$fusnewsm);
$fusnewsm = eregi_replace("(^|[>[:space:]\n])([[:alnum:]]+)://([^[:space:]]*)([[:alnum:]#?/&=])([<[:space:]\n]|$)","\\1<a href=\"\\2://\\3\\4\" target=\"_blank\">\\2://\\3\\4</a>", $fusnewsm);
$fusnewsm = preg_replace("/([\n >\(])www((\.[\w\-_]+)+(:[\d]+)?((\/[\w\-_%]+(\.[\w\-_%]+)*)|(\/[~]?[\w\-_%]*))*(\/?(\?[&;=\w\+%]+)*)?(#[\w\-_]*)?)/", "\\1<a href=\"http://www\\2\">www\\2</a>", $fusnewsm);
$fusnewsm = eregi_replace("\\[url\\]www.([^\\[]*)\\[/url\\]", "<a href=\"http://www.\\1\" target=_blank>\\1</a>",$fusnewsm);
$fusnewsm = eregi_replace("\\[url\\]([^\\[]*)\\[/url\\]","<a href=\"\\1\" target=_blank>\\1</a>",$fusnewsm);
$fusnewsm = eregi_replace("\\[url=([^\\[]*)\\]([^\\[]*)\\[/url\\]","<a href=\"\\1\" target=_blank>\\2</a>",$fusnewsm);
return $fusnewsm;
}

function InsertSmillies($fusnewsm, $fusurl){
  include('config.php');
  //$fusnewsm = stripslashes($fusnewsm);

  $file = file($fpath."smillies.db");
  foreach($file as $value){
       list($rand,$code,$image) = explode("|<|", $value);
       $code = unhtmlentities(stripslashes($code));
       $fusnewsm = str_replace($code, "<img src=\"$fusurl/smillies/$image\">", $fusnewsm);
  }

  return $fusnewsm;
}

function deletecomments($rand){
  require "config.php";

  $data = "";
  $file = file($fpath."comments.db.php");
  my_array_shift($file);
  while(list(,$value) = each($file)){
       list($ip,$post,$name,$email,$datum,$unique,$num) = explode("|<|", $value);
       if(($rand != $num) && ($num != "")){
         $post = stripslashes($post);
         $data .= "$ip|<|$post|<|$name|<|$email|<|$datum|<|$unique|<|$num|<|\n";
       }
  }
  $dafile = fopen($fpath."comments.db.php","w") or die (show_error($error0));
  flock( $dafile, LOCK_EX);
  fputs($dafile, "<?php die(\"You may not access this file.\"); ?>\n".$data);
  flock( $dafile, LOCK_UN);
  fclose($dafile);
}

function checkvalue($value){
  if($value == "checked"){
    return "yes";
  }else{
    return "no";
  }
}

function show_error($string){
  echo get_template( "header.php", TRUE ).$string.get_template( "footer.php", TRUE );
}

function get_template( $template, $php_enabled ){
  require "./config.php";
  $text = "";

  if( $php_enabled ){
    ob_start();
      include($fpath."templates/".$template);
      $text = ob_get_contents();
    ob_end_clean();
  }else{
    $fp = fopen( $fpath."templates/".$template, "r");
    flock( $fp, LOCK_EX);
    while (!feof ($fp)) { $buffer = fgets($fp, 4096); $text .= $buffer; }
    flock( $fp, LOCK_UN);
    fclose($fp);
  }

  return $text;
}

function arrange_by_date(){
  require "./config.php";

  $file = file($fpath."news/toc.php");
  my_array_shift($file);
  if(count($file) > 1){
  foreach( $file as $value ){
    if( $value != ""){
      list($news_id,$news_date,$news_writer,$news_subject) = explode("|<|", $value);
      $array[$news_date] = "$news_id|<|$news_date|<|$news_writer|<|$news_subject|<|<br>\n";
    }
  }
  if(is_array($array)){
    krsort($array);
    $data = "<?php die(\"No access allowed.\"); ?>";
    foreach($array as $val){
      $val = trim2($val,"\n..\r");
      $data .= "\n".$val;
    }
  }
  
  $fp = fopen($fpath."news/toc.php","w") or die (show_error($error4));
  flock( $fp, LOCK_EX);
  fputs($fp,$data);
  flock( $fp, LOCK_UN);
  fclose($fp);
  }
}

function buildnews(){
  require "./config.php";
  require "./language.db";
  
  arrange_by_date();
  
  $file = file($fpath."news/toc.php");
  my_array_shift($file);

if(count($file) > 0){
  $c = 0;
  foreach($file as $val){
      if ( trim2 ( $val, '') == '' )
      continue;
    if($c <= $numofposts)
      $ar_post[] = $val;
    if($c <= $numofh)
      $ar_head[] = $val;
    $c++;
  }

  if ( $flip_news == "checked"){
    $ar_post = array_reverse( $ar_post );
    $ar_head = array_reverse( $ar_head );
  }
  
  if ( $post_per_day == "checked" ){
    $ppp_data = array();
  }
  $news = ""; $header = ""; $rss_infos = "";
  if( $numofposts >= $numofh )
    $post_count = $numofposts;
  else
    $post_count = $numofh;

  $count = count($ar_post);
  $count2 = count($ar_head);

  if( $count2 > $count )
    $count = $count2;
    
  $cnt = 0;
  foreach($ar_post as $post){
    $toc = explode( "|<|", $post );
    $array = buildnews2( $toc[0], $cnt );
    $cnt++;
    if ( $post_per_day == "checked" ){
        if ( !isset($ppp_data[$array["date"]]) )
            $ppp_data[$array["date"]] = "";
        $ppp_data[$array["date"]] .= $array["temp_short"];
      }else
        $news .= $array["temp_short"];
  }

  $cnt = 0;
  foreach($ar_head as $post){
    $toc = explode( "|<|", $post );
    $array = buildnews2( $toc[0], $cnt );
    $cnt++;
    $header .= $array["temp_head"]."\n";
    $rss_infos .= $array["temp_rss"];
  }
  
  if ( $post_per_day == "checked" ){
    if ( $flip_news == "checked")
       ksort( $ppp_data );
    else
      krsort( $ppp_data );
    $temp = get_template('news_a_day_temp.php', FALSE);
    while (list ($key, $value) = each ($ppp_data)){
      list($year,$month,$day) = explode(",", $key);
      $insert_date = str_replace("{date}", date("l",strtotime("$month/$day/$year")).", ".$months[$month]." ".$day." ".$year, $temp);
      $news .= str_replace("{news_a_day}", $value, $insert_date);
    }
  }
  
  if( $enable_rss == "checked" )
  {
    $rss_info  = "<?xml version=\"1.0\"?>\n";
    $rss_info .= "<rss version=\"2.0\">\n";
    $rss_info .= "<channel>\n";
    $rss_info .= "             <title>FusionPHP</title>\n";
    $rss_info .= "             <link>http://fusionphp.net/</link>\n";
    $rss_info .= "             <description>News management script and more</description>\n";
    $rss_info .= "             <pubDate>".date($datefor, time())."</pubDate>\n";
    $rss_info .= "             <lastBuildDate>".date($datefor, time())."</lastBuildDate>\n";
    $rss_info .= "             <timestamp>".time()."</timestamp>\n";
    $rss_info .= "             <generator>Fusion News 3.6.1</generator>\n";
    $rss_info .= "             <image>\n";
    $rss_info .= "                       <title>FusionPHP</title>\n";
    $rss_info .= "                       <url>http://www.fusionphp.net/img/header-left.jpg</url>\n";
    $rss_info .= "                       <link>http://fusionphp.net/</link>\n";
    $rss_info .= "             </image>\n";
    $rss_info .= "$rss_infos";
    $rss_info .= "</channel>\n";
    $rss_info .= "</rss>";

    $fp = fopen($fpath."fusionnews.xml","w") or die (show_error($error22));
    flock( $fp, LOCK_EX);
    fputs($fp,$rss_info);
    flock( $fp, LOCK_UN);
    fclose($fp);
  }
  
  $fp = fopen($fpath."news.php","w") or die (show_error($error4));
  flock( $fp, LOCK_EX);
  fputs($fp,$news);
  flock( $fp, LOCK_UN);
  fclose($fp);

  $fp = fopen($fpath."headlines.php","w") or die (show_error($error6));
  flock( $fp, LOCK_EX);
  fputs($fp,$header);
  flock( $fp, LOCK_UN);
  fclose($fp);
}
}

function buildnews2( $news_item, $item_in_list ){ //builds only the amount of news shown
  require "./config.php";

  $file = file( $fpath."news/news.".$news_item.".php" );
  list($news_short,$news_full,$news_writer,$news_subject,$news_email,$news_icon,$news_date,$news_comment_count) = explode("|<|", $file[1]);


  if($news_full == ""){
    $link_news_full = "";
  }else{
    if($fsnw == "checked") {
      $link_news_full = "<a href='#' onClick=window.open(\"$furl/fullnews.php?id=".$news_item."\",\"\",\"height=$fullnewsh,width=$fullnewsw,toolbar=no,menubar=no,scrollbars=".checkvalue($fullnewss).",resizable=".checkvalue($fullnewsz)."\")>$fslink</a>";
    }else{
      $link_news_full = "<a href=\"$furl/fullnews.php?id=".$news_item."\">$fslink</a>";
    }
  }
  if($news_comment_count < 0 or $news_comment_count == "")  //comment count
    $news_comment_count = 0;

  if($news_icon == ""){ // icon available?
    $news_icon = "";
  }else{
    $news_icon = "<img src=\"".$news_icon."\">";
  }

  if($wfpost == "checked"){
    $news_subject = filterbadwords($news_subject);
    $news_short   = filterbadwords(  $news_short);
  }
  //html tags
  $news_subject = strip_tags($news_subject);
  if($ht == "checked"){
    $news_short = unhtmlentities($news_short);
    $news_short = str_replace("<?", "&lt;?", $news_short);
  }
  $news_short = str_replace( "&amp;", "&", $news_short);
  $news_short = str_replace(" &br;", "<br>", $news_short);
  $news_short = str_replace("&br;", "<br>", $news_short);

  if($cbwordwrap){
    $news_short = fusion_wordwrap($news_short, $wwwidth);
  }
  if($smilies == "checked"){
    $news_short   = InsertSmillies(  $news_short,$furl);
  }
  //bbcode
  if($bb == "checked"){
    $news_short   = InsertBBCode(  $news_short);
  }
  
  $news_writer = findwriter($news_writer);
  
  list($showemail, $email) = explode("=", $news_email);
  if($showemail == ""){
    $news_writer = "<a href=\"mailto:$email\">".$news_writer."</a>";
  }

  //replace user variables
  $temp_short = "<a id=\"fus_".$news_item."\" name=\"\">".get_template('news_temp.php', FALSE);
  $temp_short = str_replace(  "{subject}",              $news_subject, $temp_short);
  $temp_short = str_replace(     "{user}",                   $news_writer, $temp_short);
  $temp_short = str_replace(     "{date}",   date($datefor, $news_date), $temp_short);
  $temp_short = str_replace(     "{news}",                $news_short, $temp_short);
  $temp_short = str_replace("{fullstory}",            $link_news_full, $temp_short);
  $temp_short = str_replace(     "{icon}",                   $news_icon, $temp_short);
  if($stfpop == "checked") {
    $temp_short = str_replace("{send}", "<a href='#' onClick=window.open(\"$furl/send.php?id=".$news_item."\",\"\",\"height=$stfheight,width=$stfwidth,toolbar=no,menubar=no,scrollbars=".checkvalue($stfscrolls).",resizable=".checkvalue($stfresize)."\")>$stflink</a>", $temp_short);
  }else{
    $temp_short = str_replace("{send}", "<a href=\"$furl/send.php?id=".$news_item."\">$stflink</a>", $temp_short);
  }
  $temp_short = str_replace("{nrc}", $news_comment_count, $temp_short);
  if($compop == "checked") {
    $temp_short = str_replace("{comments}", "<a href='#' onClick=window.open(\"$furl/comments.php?id=".$news_item."\",\"\",\"height=$comheight,width=$comwidth,toolbar=no,menubar=no,scrollbars=".checkvalue($comscrolls).",resizable=".checkvalue($comresize)."\")>$pclink</a>", $temp_short);
  }else{
    $temp_short = str_replace("{comments}", "<a href=\"$furl/comments.php?id=".$news_item."\">$pclink</a>", $temp_short);
  }
  
  $temp_head = get_template('headline_temp.php', FALSE);
  $temp_head = str_replace(  "{subject}",                       $news_subject, $temp_head);
  $temp_head = str_replace(     "{user}",                            $news_writer, $temp_head);
  $temp_head = str_replace(     "{icon}",                            $news_icon, $temp_head);
  $temp_head = str_replace(     "{date}",            date($datefor, $news_date), $temp_head);
  if( ($item_in_list > $numofposts) || $link_headline_fullstory == "checked" )
    $temp_head = str_replace("{linkstart}", "<a href=\"$furl/fullnews.php?id=".$news_item."\">", $temp_head);
  else
    $temp_head = str_replace("{linkstart}", "<a href=\"$hurl#fus_".$news_item."\">", $temp_head);
  $temp_head = str_replace(  "{linkend}",                              "</a>", $temp_head);

  //$temp_short = "<a name=\"".$news_item."\"></a>".$temp_short."\n";

  $rss_info  = "             <item>\n";
  $rss_info .= "                       <title>$news_subject</title>\n";
  if( $item_in_list > $numofposts )
  $rss_info .= "                       <link>$furl/fullnews.php?id=$news_item</link>\n";
  else
  $rss_info .= "                       <link>$hurl#fus_$news_item</link>\n";
  $rss_info .= "                       <date>".date($datefor, $news_date)."</date>\n";
  $rss_info .= "                       <datestamp>$news_date</datestamp>\n";
  $rss_info .= "                       <id>$news_item</id>\n";
  $rss_info .= "             </item>\n";

  $array["temp_rss"]   = $rss_info;
  $array["temp_short"] = $temp_short;
  $array["temp_head"]  = $temp_head;
  $array["date"]  = date("Y", $news_date).",".date("m", $news_date).",".date("d", $news_date);

  return $array;
}

function fusion_wordwrap($post, $maxwordlen) {
    $eachword = explode(" " , eregi_replace("<BR>"," ",$post));
      for ($i=0; $i<count($eachword); $i++) {
	if (strlen($eachword[$i])>$maxwordlen) {
          $post = eregi_replace($eachword[$i], chunk_split($eachword[$i],$maxwordlen), $post);
	}
      }
    return $post;
}

function filterbadwords($post) {
  require "config.php";
  $file = file($fpath."badwords.txt");// or die ("Can't open badwords.txt for reading!");
  while(list(,$value) = each($file)){
       $both = rtrim($value);
       list($bad,$good) = explode("=", $both);
       $badwords[] = $bad;
       $goodwords[] = $good;
  }
  if(count($badwords) > 1) {
    for($i = 0; $i < count($badwords) - 1; $i++) {
       $dummy = "";
       if($badwords[$i] != ""){
            $post = eregi_replace($badwords[$i], $goodwords[$i], $post);
       }
    }
  }
  return $post;
}

function findwriter( $user ){
  require "config.php";

    $file = file($fpath."users.php");
    $available = FALSE;
    $name = "";
    my_array_shift($file);
    foreach($file as $value){
      list($fuser,$fnick,$femail,$ficon,$ftimeoffset,$fpass,$le) = explode("|<|", $value);
      if(($user == $fuser) or ($name == $fnick)){
        $available = TRUE;
        $name = $fnick;
      }
    }
    if ( !$available )
      $name = $user;
      
    return $name;
}

function calc_size ( $size ){
  if ( $size < 1000 )
  return $size + ' Bytes';
  if ( $size < 1000000 )
  return number_format(($size/1024), 2, '.', '').' kB';
  if ( $size < 1000000000 )
  return number_format($size/(1024*1024), 2, '.', '').' MB';
  if ( $size < 1000000000000 )
  return number_format($size/(1024*1024*1024), 2, '.', '').' GB';
}

function valid_email($email) {
 // Returns true if email address has a valid form.
 $pattern = "^[-_.[:alnum:]]+@((([[:alnum:]]|[[:alnum:]][[:alnum:]-]*[[:alnum:]])\.)+(ad|ae|aero|af|ag|ai|al|am|an|ao|aq|ar|arpa|as|at|au|aw|az|ba|bb|bd|be|bf|bg|bh|bi|biz|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|com|coop|cr|cs|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|edu|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gh|gi|gl|gm|gn|gov|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|in|info|int|io|iq|ir|is|it|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|mg|mh|mil|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|museum|mv|mw|mx|my|mz|na|name|nc|ne|net|nf|ng|ni|nl|no|np|nr|nt|nu|nz|om|org|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pro|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)|(([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5])\.){3}([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5]))$";
 if(eregi($pattern, $email)) return true;
 else  return false;
}

function checkifflooding($ip, $rand1){
  require "./config.php";
  
  $data = "";
  $file = file($fpath."flood.php");
  my_array_shift($file);
  for($i = 1; $i<= count($file);$i++){
     list($a, $b, $c) = explode('=', trim($file[$i]));
     if($b > time() - $floodtime)$data .= "$a=$b=$c\n";
  }
  $fp = fopen($fpath."flood.php", "w") or die (show_error($error9));
  flock( $fp, LOCK_EX);
  fputs($fp, "<?php die(\"You may not access this file.\"); ?>\n".$data);
  flock( $fp, LOCK_UN);
  fclose($fp);
  $time = 0;
  $file = file($fpath."flood.php");
  my_array_shift($file);
  foreach($file as $value){
     list($a, $b, $c) = explode('=', $value);
     if(trim($a) == $ip){$time = $b; $d = trim($c);}
  }
  if($time > "0"){
    if(((time() - $floodtime) <= $time) && ($rand1 == $d)){$result = true;}else{$result = false;}
  }else{$result = false;}
  return $result;
}

function checkifbanned($ip){
  require "./config.php";
  $result =  false;
  $file = file($fpath."banned.php");
  my_array_shift($file);
  foreach($file as $value)
     if(trim($value) == $ip)
       $result = true;
  return $result;
}

function my_array_shift(&$array){
  reset($array);
  $temp = each($array);
  unset($array[$temp['key']]);
  return $temp['value'];
}

function getip() {
  if(isset($HTTP_SERVER_VARS)) {
    if(isset($HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"])) {
    $realip = $HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"];
    }elseif(isset($HTTP_SERVER_VARS["HTTP_CLIENT_IP"])) {
      $realip = $HTTP_SERVER_VARS["HTTP_CLIENT_IP"];
    }else{
      $realip = $HTTP_SERVER_VARS["REMOTE_ADDR"];
    }
  }else{
  if(getenv( 'HTTP_X_FORWARDED_FOR' ) ) {
    $realip = getenv( 'HTTP_X_FORWARDED_FOR' );
  }elseif ( getenv( 'HTTP_CLIENT_IP' ) ) {
    $realip = getenv( 'HTTP_CLIENT_IP' );
  }else {
    $realip = getenv( 'REMOTE_ADDR' );
  }
}
return $realip;
}

function has_access( $level ){
  require './config.php';
  global $HTTP_SESSION_VARS, $HTTP_COOKIE_VARS;

  $file = file($fpath."users.php");
  my_array_shift($file);
  if((isset($HTTP_COOKIE_VARS["fusionuser"])&& isset($HTTP_COOKIE_VARS["fusionpass"]))||
     (isset($HTTP_SESSION_VARS["fusionuser"])&& isset($HTTP_SESSION_VARS["fusionpass"])))
  foreach($file as $value){
    list($user,$nick,$email,$icon,$offset,$pass,$le) = explode("|<|", $value);
    if( $cookies == "checked"){
      if(isset($HTTP_COOKIE_VARS["fusionuser"]) && $HTTP_COOKIE_VARS["fusionid"] == $fusion_id)
        if($HTTP_COOKIE_VARS["fusionuser"] == $user)
          if($HTTP_COOKIE_VARS["fusionpass"] == $pass){
            if ($le >= $level)
              return TRUE;
            else
              return FALSE;
            exit();
          }
    }else{
      if(isset($HTTP_SESSION_VARS["fusionuser"]) && $HTTP_SESSION_VARS["fusionid"] == $fusion_id)
        if($HTTP_SESSION_VARS["fusionuser"] == $user)
          if($HTTP_SESSION_VARS["fusionpass"] == $pass){
            if ($le >= $level)
              return TRUE;
            else
              return FALSE;
            exit();
          }
    }
  }
  return FALSE;
}

function buildcomments($news_item){
  require "./config.php";// your config file

  $cont = "";
  $showemail = "";
  $template = get_template('com_temp.php', TRUE);
  $file = file( $fpath."news/news.".$news_item.".php" );
  my_array_shift($file);
  my_array_shift($file);
  foreach( $file as $value ){
    list($com_ip,$com_post,$com_name,$com_email,$com_datum,$com_unique) = explode("|<|", $value);
    if($cbwordwrap)
      $com_post = fusion_wordwrap($com_post, $wwwidth);
    if($wfcom == "checked")
      $com_post = filterbadwords($com_post);
    $com_name = ereg_replace("\\\'", "'", $com_name);
    $com_name = ereg_replace('\\\"', '"', $com_name);
    $com_name = strip_tags($com_name);
    $com_post = ereg_replace("\\\'", "'", $com_post);
    $com_post = ereg_replace('\\\"', '"', $com_post);
    //html tags
    if($htc == "checked"){
      $com_post = unhtmlentities($com_post);
      $com_post = str_replace("<?", "&lt;?", $com_post);
    }
    if($smilcom == "checked"){
      $com_post = InsertSmillies($com_post,$furl);
    }
    //bbcode
    if($bbc == "checked"){
      $com_post = InsertBBCode($com_post);
    }
    $com_post = str_replace( "&amp;", "&", $com_post);
    if($comallowbr == "checked"){
      $com_post = str_replace(" &br;", "<br>", $com_post);
      $com_post = str_replace("&br;", "<br>", $com_post);
    }else{
      $com_post = str_replace(" &br;", "", $com_post);
      $com_post = str_replace("&br;", "<br>", $com_post);
    }
    if(ereg("=", $com_email)){
      list($showemail, $mail) = explode("=", $com_email);
      if($showemail == ""){
        $mail = $mail;
      }
    }else{
        $mail = $com_email;
    }
    if($mail != "")
      $com_name = "<a href=\"mailto:$mail\">$com_name</a>";
    //replace user variables
    $tem = $template;
    $tem = str_replace("{poster}", $com_name, $tem);
    $tem = str_replace("{comment}", $com_post, $tem);
    $tem = str_replace("{date}", date($datefor, $com_datum), $tem);
    $tem = str_replace("{posterip}", $com_ip, $tem);
    $cont .= $tem;
  }
  return $cont;
}

function upload_file($var_name, $dir, $extensions, $filesize, $name){
      include("./config.php");
      include("./language.db");
      global $HTTP_POST_FILES;

      $FILE_NAME = $HTTP_POST_FILES["$var_name"]['name'];
      $FILE_SIZE = $HTTP_POST_FILES["$var_name"]['size'];
      $FILE_TYPE = $HTTP_POST_FILES["$var_name"]['type'];

      $ext = explode("&#124;", $extensions);
      $n = count($ext);
      $accept = false;
      for($i = 0; $i < $n && !$accept ; $i++){
         if (eregi("\." . $ext[$i] . "$", $FILE_NAME)){
            $accept = true;
         }
      }

      if($FILE_SIZE > $filesize){
         return "$ind252";
         exit;
      }
      if (! is_dir($dir) )
      {
         return "$ind253";
         exit;
      }

      if ($HTTP_POST_FILES["$var_name"]['name'] == "" or !$HTTP_POST_FILES["$var_name"]['name'] or ($HTTP_POST_FILES["$var_name"]['name'] == "none") )
      {
         return "$ind254";
         exit;
      }
      if(!$accept){
         return "$ind255 $extensions $ind255a";
         exit;
      }

      if (! @move_uploaded_file( $HTTP_POST_FILES["$var_name"]['tmp_name'], $dir.$FILE_NAME) )
      {
         return "$ind256";
         exit;
      }else{
            if ( ! is_writeable($dir.$FILE_NAME))
         @chmod( $dir.$FILE_NAME, 0777 );
      }
  return "$name $ind257";
}

  function parse_incoming(){
     global $HTTP_GET_VARS, $HTTP_POST_VARS, $HTTP_CLIENT_IP, $REQUEST_METHOD,
            $REMOTE_ADDR, $HTTP_PROXY_USER, $HTTP_X_FORWARDED_FOR;
     $return = array();
     	
     if( is_array($HTTP_GET_VARS) )
       while( list($k, $v) = each($HTTP_GET_VARS) )
       //$k = $this->clean_key($k);
         if( is_array($HTTP_GET_VARS[$k]) ){
           while( list($k2, $v2) = each($HTTP_GET_VARS[$k]) )
              $return[$k][ clean_key($k2) ] = clean_value($v2);
	 }else
	   $return[$k] = clean_value($v);
		
      if( is_array($HTTP_POST_VARS) )
	while( list($k, $v) = each($HTTP_POST_VARS) )
	//$k = $this->clean_key($k);
	if ( is_array($HTTP_POST_VARS[$k]) ){
	  while( list($k2, $v2) = each($HTTP_POST_VARS[$k]) )
	    $return[$k][ clean_key($k2) ] = clean_value($v2);
	}else
	  $return[$k] = clean_value($v);
		
	return $return;
  }

    function clean_key($key) {

    	if ($key == "")
    	{
    		return "";
    	}
    	$key = preg_replace( "/\.\./"           , ""  , $key );
    	$key = preg_replace( "/\_\_(.+?)\_\_/"  , ""  , $key );
    	$key = preg_replace( "/^([\w\.\-\_]+)$/", "$1", $key );
    	return $key;
    }

    function clean_value($val) {

    	if ($val == "")
    	{
    		return "";
    	}
    	$val = str_replace( "&#032;"       , " "             , $val );
    	$val = str_replace( "&"            , "&amp;"         , $val );
    	$val = str_replace( "<!--"         , "&#60;&#33;--"  , $val );
    	$val = str_replace( "-->"          , "--&#62;"       , $val );
    	$val = preg_replace( "/<script/i"  , "&#60;script"   , $val );
    	$val = str_replace( ">"            , "&gt;"          , $val );
    	$val = str_replace( "<"            , "&lt;"          , $val );
    	$val = str_replace( "\""           , "&quot;"        , $val );
    	$val = preg_replace( "/\|/"        , "&#124;"        , $val );
    	//$val = preg_replace( "/\n/"        , "<br>"          , $val ); // Convert literal newlines
    	$val = preg_replace( "/\\\$/"      , "&#036;"        , $val );
    	$val = preg_replace( "/\r/"        , ""              , $val ); // Remove literal carriage returns
    	$val = str_replace( "!"            , "&#33;"         , $val );
    	$val = str_replace( "'"            , "&#39;"         , $val ); // IMPORTANT: It helps to increase sql query safety.
    	$val = stripslashes($val);                                     // Swop PHP added backslashes
    	$val = preg_replace( "/\\\/"       , "&#092;"        , $val ); // Swop user inputted backslashes
    	return $val;
    }
    
function unhtmlentities ($string)
{
    $trans_tbl = get_html_translation_table (HTML_ENTITIES);
    $trans_tbl = array_flip ($trans_tbl);
    
    return strtr ($string, $trans_tbl);
}

function config_array(){
  require "./config.php";
  
  return array( "fusion_id" => $fusion_id, "cookies" => $cookies, "site" => $site,
                "furl" =>$furl, "hurl" => $hurl, "fpath" => $fpath, "datefor" => $datefor,
                "numofposts" => $numofposts, "numofh" => $numofh, "bb" => $bb,
                "ht" => $ht, "first" => "no", "post_per_day" => $post_per_day,
                "wfpost" => $wfpost, "wfcom" => $wfcom, "skin" => $skin,
                "cbwordwrap" => $cbwordwrap, "wwwidth" => $wwwidth, "smilies" => $smilies,
                "stfpop" => $stfpop, "comallowbr" => $comallowbr, "stfwidth" => $stfwidth,
                "stfheight" => $stfheight, "fslink" => $fslink, "stflink" => $stflink,
                "pclink" => $pclink, "fsnw" => $fsnw, "cbflood" => $cbflood,
                "floodtime" => $floodtime, "comlength" => $comlength, "fullnewsw" => $fullnewsw,
                "fullnewsh" => $fullnewsh, "fullnewss" => $fullnewss, "stfresize" => $stfresize,
                "stfscrolls" => $stfscrolls, "fullnewsz" => $fullnewsz, "htc" => $htc,
                "smilcom" => $smilcom, "bbc" => $bbc, "compop" => $compop,
                "comscrolls" => $comscrolls, "comresize" => $comresize,
                "comheight" => $comheight, "comwidth" => $comwidth,
                "uploads_active" => $uploads_active, "uploads_size" => $uploads_size,
                "uploads_ext" => $uploads_ext, "enable_rss" => $enable_rss,
                "link_headline_fullstory" => $link_headline_fullstory, "flip_news" => $flip_news);
}

function save_config( $configs )
{
  require "./config.php";
  
  $save       = "<?php\n";
  $save      .= "\$fusion_id = \"".$configs['fusion_id']."\";\n";
  $save      .= "\$cookies = \"".$configs['cookies']."\";\n";
  $save      .= "\$site = \"".$configs['site']."\";\n";
  $save      .= "\$furl= \"".$configs['furl']."\";\n";
  $save      .= "\$hurl = \"".$configs['hurl']."\";\n";
  $save      .= "\$fpath = \"".$configs['fpath']."\";\n";
  $save      .= "\$datefor = \"".$configs['datefor']."\";\n";
  $save      .= "\$numofposts = \"".$configs['numofposts']."\";\n";
  $save      .= "\$numofh = \"".$configs['numofh']."\";\n";
  $save      .= "\$bb = \"".$configs['bb']."\";\n";
  $save      .= "\$ht = \"".$configs['ht']."\";\n";
  $save      .= "\$first = \"no\";\n";
  $save      .= "\$post_per_day = \"".$configs['post_per_day']."\";\n";
  $save      .= "\$wfpost = \"".$configs['wfpost']."\";\n";
  $save      .= "\$wfcom = \"".$configs['wfcom']."\";\n";
  $save      .= "\$skin = \"".$configs['skin']."\";\n";
  $save      .= "\$cbwordwrap = \"".$configs['cbwordwrap']."\";\n";
  $save      .= "\$wwwidth = \"".$configs['wwwidth']."\";\n";
  $save      .= "\$smilies = \"".$configs['smilies']."\";\n";
  $save      .= "\$stfpop = \"".$configs['stfpop']."\";\n";
  $save      .= "\$comallowbr = \"".$configs['comallowbr']."\";\n";
  $save      .= "\$stfwidth = \"".$configs['stfwidth']."\";\n";
  $save      .= "\$stfheight = \"".$configs['stfheight']."\";\n";
  $save      .= "\$fslink = \"".$configs['fslink']."\";\n";
  $save      .= "\$stflink = \"".$configs['stflink']."\";\n";
  $save      .= "\$pclink = \"".$configs['pclink']."\";\n";
  $save      .= "\$fsnw = \"".$configs['fsnw']."\";\n";
  $save      .= "\$cbflood = \"".$configs['cbflood']."\";\n";
  $save      .= "\$floodtime = \"".$configs['floodtime']."\";\n";
  $save      .= "\$comlength = \"".$configs['comlength']."\";\n";
  $save      .= "\$fullnewsw = \"".$configs['fullnewsw']."\";\n";
  $save      .= "\$fullnewsh = \"".$configs['fullnewsh']."\";\n";
  $save      .= "\$fullnewss = \"".$configs['fullnewss']."\";\n";
  $save      .= "\$stfresize = \"".$configs['stfresize']."\";\n";
  $save      .= "\$stfscrolls = \"".$configs['stfscrolls']."\";\n";
  $save      .= "\$fullnewsz = \"".$configs['fullnewsz']."\";\n";
  $save      .= "\$htc = \"".$configs['htc']."\";\n";
  $save      .= "\$smilcom = \"".$configs['smilcom']."\";\n";
  $save      .= "\$bbc= \"".$configs['bbc']."\";\n";
  $save      .= "\$compop= \"".$configs['compop']."\";\n";
  $save      .= "\$comscrolls= \"".$configs['comscrolls']."\";\n";
  $save      .= "\$comresize= \"".$configs['comresize']."\";\n";
  $save      .= "\$comheight= \"".$configs['comheight']."\";\n";
  $save      .= "\$comwidth= \"".$configs['comwidth']."\";\n";
  $save      .= "\$uploads_active= \"".$configs['uploads_active']."\";\n";
  $save      .= "\$uploads_size= \"".$configs['uploads_size']."\";\n";
  $save      .= "\$uploads_ext= \"".$configs['uploads_ext']."\";\n";
  $save      .= "\$enable_rss = \"".$configs['enable_rss']."\";\n";
  $save      .= "\$link_headline_fullstory = \"".$configs['link_headline_fullstory']."\";\n";
  $save      .= "\$flip_news = \"".$configs['flip_news']."\";\n";
  $save      .= "?>";
  
  $configfile = fopen($fpath."config.php","w") or die (show_error($error8));
  flock( $configfile, LOCK_EX);
  fputs($configfile,$save);
  flock( $configfile, LOCK_UN);
  fclose($configfile);
}

function trim2 ( $str, $delim )
{
                $str2 = substr( $str , strlen($str) -1, strlen($str));
                while( $str2 == "\n" || $str2 == "\t" || $str2 == "\r" || $str2 == " " )
                {
                       $str = substr( $str, 0, strlen($str) - 2 );
                       $str2 = substr( $str , strlen($str) -1, strlen($str));
                }
                $str2 = substr( $str , 0, 1);
                while( $str2 == "\n" || $str2 == "\t" || $str2 == "\r" || $str2 == " " )
                {
                       $str = substr( $str, 1, strlen($str) - 1 );
                       $str2 = substr( $str , 0, 1);
                }
                return $str;
}
?>

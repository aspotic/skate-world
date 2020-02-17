<?php


error_reporting(E_ALL ^ E_NOTICE);
  require "./config.php";
  include $fpath."functions.php";
  
  global $HTTP_GET_VARS;

echo get_template('header.php', FALSE);

  if(!isset($HTTP_GET_VARS["id"])){ $id = "";}else{$id = $HTTP_GET_VARS["id"];}
  if( $id == "" ) die("News item doesn't exsist");
  
  $file = file( $fpath."news/news.".$id.".php" );
  list($news_short,$news_full,$news_writer,$news_subject,$news_email,$news_icon,$news_date,$news_comment_count) = explode("|<|", $file[1]);

  if($news_comment_count < 0 or $news_comment_count == "")  //comment count
    $news_comment_count = 0;

  if($news_icon == ""){ // icon available?
    $news_icon = "";
  }else{
    $news_icon = "<img src=\"".$news_icon."\">";
  }

  if($cbwordwrap){
    $news_full  = fusion_wordwrap( $news_full, $wwwidth);
  }
  if($wfpost == "checked"){
    $news_subject = filterbadwords($news_subject);
    $news_full    = filterbadwords(   $news_full);
    $news_short   = filterbadwords(   $news_short);
  }
  //html tags
  if($ht == "checked"){
    $news_full = unhtmlentities($news_full);
    $news_full = str_replace("<?", "&lt;?", $news_full);
    $news_short = unhtmlentities($news_short);
    $news_short = str_replace("<?", "&lt;?", $news_short);
  }
  $news_full = str_replace( "&amp;", "&", $news_full);
  $news_full = str_replace( " &br;", "<br>", $news_full);
  $news_short = str_replace( "&amp;", "&", $news_short);
  $news_short = str_replace( " &br;", "<br>", $news_short);
  
  if($smilies == "checked"){
    $news_full    = InsertSmillies( $news_full,$furl);
    $news_short   = InsertSmillies( $news_short,$furl);
  }
  //bbcode
  if($bb == "checked"){
    $news_full    = InsertBBCode( $news_full);
    $news_short   = InsertBBCode( $news_short);
  }
  
  $news_writer = findwriter( $news_writer );
  list($showemail, $email) = explode("=", $news_email);
  if($showemail == ""){
    $news_writer = "<a href=\"mailto:$email\">".$news_writer."</a>";
  }
  
  $temp_full = get_template('fullnews_temp.php', TRUE);
  $temp_full = str_replace("{subject}", $news_subject, $temp_full);
  $temp_full = str_replace("{user}", $news_writer, $temp_full);
  $temp_full = str_replace("{date}", date($datefor, $news_date), $temp_full);
  $temp_full = str_replace("{shortnews}", $news_short, $temp_full);
  $temp_full = str_replace("{fullnews}", $news_full, $temp_full);
  $temp_full = str_replace("{icon}", $news_icon, $temp_full);
  if($stfpop == "checked") {
    $temp_full = str_replace("{send}", "<a href='#' onClick=window.open(\"$furl/send.php?id=".$id."\",\"\",\"height=$stfheight,width=$stfwidth,toolbar=no,menubar=no,scrollbars=".checkvalue($stfscrolls).",resizable=".checkvalue($stfresize)."\")>$stflink</a>", $temp_full);
  }else{
    $temp_full = str_replace("{send}", "<a href=\"$furl/send.php?id=".$id."\">$stflink</a>", $temp_full);
  }
  $temp_full = str_replace("{nrc}", $news_comment_count, $temp_full);
  if($compop == "checked") {
    $temp_full = str_replace("{comments}", "<a href='#' onClick=window.open(\"$furl/comments.php?id=".$id."\",\"\",\"height=$comheight,width=$comwidth,toolbar=no,menubar=no,scrollbars=".checkvalue($comscrolls).",resizable=".checkvalue($comresize)."\")>$pclink</a>", $temp_full);
  }else{
    $temp_full = str_replace("{comments}", "<a href=\"$furl/comments.php?id=".$id."\">$pclink</a>", $temp_full);
  }
  echo $temp_full;
  
echo get_template('footer.php', TRUE);
?>

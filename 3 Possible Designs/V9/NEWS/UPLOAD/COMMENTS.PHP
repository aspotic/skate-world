<?php
/******************
fusion news management
by the fusion team

version  3.6

fusionphp.com
comments.php version: 3.6.0

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
//debugging mode
error_reporting(E_ALL ^ E_NOTICE);
global $mid,$id;
set_magic_quotes_runtime(0);
require "./config.php";
require "./language.db";
include_once("./functions.php");
$VARS = parse_incoming();

  echo get_template('com_header.php', TRUE);
  
if(!isset($VARS["id"])){ $id = "";}else{$id = $VARS["id"];}
if(!isset($VARS["mid"])){ $mid = "";}else{$mid = $VARS["mid"];}

if($mid == ""){
  $file = file($fpath."news/news.".$id.".php");
  list($news_short,$news_full,$news_writer,$news_subject,$news_email,$news_icon,$news_date,$news_comment_count) = explode("|<|", $file[1]);
  if($news_full == ""){
    $link_news_full = "";
  }else{
    if($fsnw == "checked") {
      $link_news_full = "<a href='#' onClick=window.open(\"$furl/fullnews.php?id=".$id."\",\"\",\"height=$fullnewsh,width=$fullnewsw,toolbar=no,menubar=no,scrollbars=".checkvalue($fullnewss).",resizable=".checkvalue($fullnewsz)."\")>$fslink</a>";
    }else{
      $link_news_full = "<a href=\"$furl/fullnews.php?id=".$id."\">$fslink</a>";
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
    $news_full   = filterbadwords(  $news_full);
  }
  //html tags
  $news_subject = strip_tags($news_subject);
  if($ht == "checked"){
    $news_short   = unhtmlentities(  $news_short);
    $news_short = str_replace("<?", "&lt;?", $news_short);
    $news_full   = unhtmlentities(  $news_full);
    $news_full = str_replace("<?", "&lt;?", $news_full);
  }
  $news_short = str_replace( "&amp;", "&", $news_short);
  $news_short = str_replace(" &br;", "<br>", $news_short);
  $news_full = str_replace( "&amp;", "&", $news_full);
  $news_full = str_replace(" &br;", "<br>", $news_full);
  
  if($cbwordwrap){
    $news_short = fusion_wordwrap($news_short, $wwwidth);
    $news_full = fusion_wordwrap($news_full, $wwwidth);
  }
  if($smilies == "checked"){
    $news_short   = InsertSmillies(  $news_short,$furl);
    $news_full   = InsertSmillies(  $news_full,$furl);
  }
  //bbcode
  if($bb == "checked"){
    $news_short   = InsertBBCode(  $news_short);
    $news_full   = InsertBBCode(  $news_full);
  }
  
  $news_writer = findwriter($news_writer);
  
  list($showemail, $email) = explode("=", $news_email);
  if($showemail == ""){
    $news_writer = "<a href=\"mailto:$email\">".$news_writer."</a>";
  }

  //replace user variables
  $temp_short = get_template('com_fulltemp.php', TRUE);
  $temp_short = "<script language=\"JavaScript\" src=\"./jsfunc.js\" type=\"text/javascript\"></script>".$temp_short;
  $temp_short = str_replace(  "{subject}",              $news_subject, $temp_short);
  $temp_short = str_replace(     "{user}",               $news_writer, $temp_short);
  $temp_short = str_replace(     "{date}", date($datefor, $news_date), $temp_short);
  $temp_short = str_replace(     "{news}",                $news_short, $temp_short);
  $temp_short = str_replace("{fullstory}",            $news_full, $temp_short);
  $temp_short = str_replace(     "{icon}",                 $news_icon, $temp_short);
  $temp_short = str_replace(      "{nrc}",        $news_comment_count, $temp_short);
  $temp_short = str_replace(      "{com}",                    $pclink, $temp_short);

  $box = "<textarea ID=\"fullnews\" name=\"fullnews\" rows=\"\\2\" cols=\"\\1\" ONSELECT=\"javascript:storeCaret(this);\" ONCLICK=\"javascript:storeCaret(this);\" ONKEYUP=\"javascript:storeCaret(this);\" ONCHANGE=\"javascript:storeCaret(this);\"></textarea>";
  if ($bbc == "checked")
$box = <<<html
<a href="Javascript:setsmiley2n('[b]text[/b]');"><img src="img/bold.gif" border="0" alt="Bold Font"></a><a href="Javascript:setsmiley2n('[i]text[/i]');"><img src="img/italic.gif" border="0" alt="Italics font"></a><a href="Javascript:setsmiley2n('[u]text[/u]');"><img src="img/underline.gif" border="0" alt="Underline"></a><a href="Javascript:setsmiley2n('[s]text[/s]');"><img src="img/strike.gif" border="0" alt="Strike Out"></a><a href="Javascript:setsmiley2n('[sub]text[/sub]');"><img src="img/sub.gif" border="0" alt="Subscript"></a><a href="Javascript:setsmiley2n('[sup]text[/sup]');"><img src="img/sup.gif" border="0" alt="Superscript"></a><a href="Javascript:setsmiley2n('[shadow=red,left,1]TEXT[/shadow]');"><img src="img/shadow.gif" border="0" alt="Shadow Text"></a><a href="Javascript:setsmiley2n('[glow=red,2,1]TEXT[/glow]');"><img src="img/glow.gif" border="0" alt="Glow Text"></a><a href="Javascript:setsmiley2n('[color=red]text[/color]');"><img src="img/color.gif" border="0" alt="Font color"></a><a href="Javascript:setsmiley2n('[font=verdana]text[/font]');"><img src="img/fontface.gif" border="0" alt="Font face"></a><a href="Javascript:setsmiley2n('[size=2]text[/size]');"><img src="img/fontsize.gif" border="0" alt="font size"></a><a href="Javascript:setsmiley2n('[align=left]text[/align]');"><img src="img/fontleft.gif" border="0" alt="Font alignment"></a><a href="Javascript:setsmiley2n('[tt]text[/tt]');"><img src="img/tele.gif" border="0" alt="Teletype"></a><a href="Javascript:setsmiley2n('[hr]');"><img src="img/hr.gif" border="0" alt="Horizontal Line"></a><a href="Javascript:setsmiley2n('[move]STUFF[/move]');"><img src="img/move.gif" border="0" alt="Move"></a><a href="Javascript:setsmiley2n('[quote]text[/quote]');"><img src="img/quote2.gif" border="0" alt="Quote"></a><a href="Javascript:setsmiley2n('[flash=200,200]URL[/flash]');"><img src="img/flash.gif" border="0" alt="Flash Image"></a><a href="Javascript:setsmiley2n('[img]URL[/img]');"><img src="img/img.gif" border="0" alt="Image"></a><a href="Javascript:setsmiley2n('[email=mailto:username@site.com]Mail Meg![/email]');"><img src="img/email2.gif" border="0" alt="E-mail link"></a><a href="Javascript:setsmiley2n('[url=http://www.url.com]address[/url]');"><img src="img/url.gif" border="0" alt="hyperlink"></a><a href="Javascript:setsmiley2n('[list][*]text1[*]text2[*]text3[/list]');"><img src="img/list.gif" border="0" alt="List"></a>
<br>$box
html;
    if($smilcom == "checked"){
      $addsmillies = "";
      $file = file($fpath."smillies.db");
      foreach($file as $value){
           list($rand,$code,$image) = explode("|<|", $value);
           $code = stripslashes($code);
           $addsmillies .= "<a href=\"Javascript:setsmiley2n(' ".addslashes($code)." ');\"><img src=\"smillies/$image\" border=\"0\"></a>";
      }
     //$addsmillies .= "<br>".$box;
      $box = $addsmillies."<br>".$box;
    }
  $comlen  = "";
  $temp_short = str_replace("{comments}", buildcomments($id), $temp_short);
  $temp_short = str_replace("[form]", "<form action=\"$furl/comments.php?mid=post&id=$id\" method=\"post\" name=\"newsposting\">", $temp_short);
  $temp_short = str_replace("[/form]", "</form>", $temp_short);
  $temp_short = str_replace("[buttons]", "<input type=\"submit\" name=\"com_Submit\" value=\"Submit\"><input type=\"reset\" value=\"Reset\">", $temp_short);
  if($comlength == 0){
    $temp_short = str_replace("[comlen]", "", $temp_short);
  }else{
    $comlen .= "<SCRIPT LANGUAGE=\"JavaScript\">\n";
    $comlen .= "<!--\n";
    $comlen .= "document.newsposting.fullnews.onkeypress = Calcul;\n";
    $comlen .= "var alerter = 0;\n";
    $comlen .= "function Calcul(e)\n";
    $comlen .= "{\n";
    $comlen .= "	var msg = document.newsposting.fullnews;\n";
    $comlen .= "        nbcar = msg.value.length*1;\n";
    $comlen .= "        if (nbcar > $comlength ) {\n";
    $comlen .= "          msg.value = msg.value.substring(0,$comlength);\n";
    $comlen .= "          alert(\"$ind258\");\n";
    $comlen .= "          valcar = 0;\n";
    $comlen .= "         }\n";
    $comlen .= "        else\n";
    $comlen .= "          valcar = $comlength - nbcar;\n";
    $comlen .= "        document.newsposting.chars.value = valcar;\n";
    $comlen .= "}\n";
    $comlen .= "//-->\n";
    $comlen .= "      </SCRIPT>\n";
    $temp_short = str_replace("[comlen]", "<INPUT ID=\"chars\" NAME=\"chars\" SIZE=\"5\" VALUE=\"$comlength\">", $temp_short);
  }
  $temp_short = eregi_replace("\\[pwfld,([^\\[]*)\\]", "<input type=\"password\" size=\"\\1\" name=\"pass\">", $temp_short);
  $temp_short = eregi_replace("\\[namefld,([^\\[]*)\\]", "<input type=\"text\" size=\"\\1\" name=\"name\">", $temp_short);
  $temp_short = eregi_replace("\\[mailfld,([^\\[]*)\\]", "<input type=\"text\" size=\"\\1\" name=\"email\">", $temp_short);
  $temp_short = eregi_replace("\\[comfld,([^\\[]*)\\,([^\\[]*)\\]",$box,$temp_short);

  $comlen .= "<SCRIPT LANGUAGE=\"JavaScript\">\n";
  $comlen .= "<!--\n";
  $comlen .= "document.newsposting.com_Submit.onclick = Check;\n";
  $comlen .= "function Check(e)\n";
  $comlen .= "{\n";
  $comlen .= "	var msg = document.newsposting.fullnews;\n";
  $comlen .= "        if ((msg.value.length == 0) || (document.newsposting.name.value.length == 0)) {\n";
  $comlen .= "          alert(\"$ind259\");\n";
  $comlen .= "          return false;\n";
  $comlen .= "         }\n";
  $comlen .= "        else {\n";
  if ( $comlength > 0)
    $comlen .= "          if (msg.value.length > $comlength) document.newsposting.fullnews.value = msg.value.substring(0,$comlength); \n";
  $comlen .= "          return true;\n";
  $comlen .= "        }\n";
  $comlen .= "}\n";
  $comlen .= "//-->\n";
  $comlen .= "</SCRIPT>\n";
    
  $temp_short .= $comlen;
  echo $temp_short;
}
//---------------

//Post Comment
elseif($mid == "post"){  /*id Post comment*/
  $comment = trim($VARS["fullnews"]);
  $name    = trim(substr( $VARS["name"], 0, 40));
  $email   = $VARS["email"];
  if (isset ( $VARS["pass"]))
    $pass    = trim(substr( $VARS["pass"], 0, 40));
  else
    $pass = "";

  if ( $comlength > 0 )
     $comment = substr( $comment, 0, $comlength );

  $showemail = "";

  $newsuser = 0;
  $passok   = 0;
  if(!$name or !$comment){
    $title = "field left blank";
echo <<<HTML
<font color=red><b>$com1<b></font><br>
HTML;
    exit;
  }elseif(!ereg("^[-_0-9a-zA-Z]+@[-_0-9a-zA-Z]+\.[a-zA-Z]+$",$email) && $email!=""){
echo <<<HTML
<font color=red>$com2</font><br>
HTML;
    exit;
  }elseif(checkifbanned(getip())){
echo <<<HTML
<font color=red>$com3</font><br>
HTML;
    exit;
  }elseif(checkifflooding(getip(),$id)){
echo <<<HTML
<font color=red>$com4 $floodtime $com5</font><br>
HTML;
    exit;
  }else{
    $file = file($fpath."users.php");
    my_array_shift($file);
    foreach($file as $value){
      list($fuser,$fnick,$femail,$ficon,$ftimeoffset,$fpass,$le) = explode("|<|", $value);
      if(($name == $fuser) or ($name == $fnick)){
        $newsuser = 1;
        $name = $fnick;
        if(md5($pass) == $fpass){
          $passok = 1;
          $email = $femail;
        }
      }
    }
  }
  if($passok == $newsuser){
    $ip = getip();
    $comment = ereg_replace("\\\'", "'", $comment);
    $comment = ereg_replace('\\\"', '"', $comment);
    $comment = stripslashes($comment);
    $name = ereg_replace("\\\'", "'", $name);
    $name = ereg_replace('\\\"', '"', $name);
    $name = ereg_replace('\\\\', '\\', $name);
    //$name = stripslashes($name);
    if($comallowbr == "checked")
      $comment = ereg_replace("\n", " &br;", $comment);
    else
      $comment = ereg_replace("\n", "", $comment);
    $comment = ereg_replace("\r", "", $comment);
    $time = time();
    mt_srand((double)microtime()*1000000);
    $random = "com".mt_rand();
    $file = file($fpath."news/news.".$id.".php");
    $file[count($file)] = "$ip|<|$comment|<|$name|<|$email|<|$time|<|$random|<|\n";
    // build ----------
    $numcom = 0;
    list($news_short,$news_full,$news_writer,$news_subject,$news_email,$news_icon,$news_date,$news_comment_count) = explode("|<|", $file[1]);
    $news_comment_count++;
    $file[1] = $news_short."|<|".$news_full."|<|".$news_writer."|<|".$news_subject."|<|".$news_email."|<|".$news_icon."|<|".$news_date."|<|".$news_comment_count."|<|\n";
    $count = count($file) -1;
    $data = "";
    for($i=0;$i<=$count;$i++)
       $data .= $file[$i];
    $fp = fopen($fpath."news/news.".$id.".php", "w");
    flock( $fp, LOCK_EX);
    fputs($fp,$data);
    flock( $fp, LOCK_UN);
    fclose($fp);

    $time = time();
    $fp = fopen($fpath."flood.php","a");
    flock( $fp, LOCK_EX);
    fputs($fp,"$ip=$time=$id");
    flock( $fp, LOCK_UN);
    fclose($fp);
    buildnews('no');
    
echo <<<HTML
<META HTTP-EQUIV=Refresh CONTENT="3; URL=$furl/comments.php?id=$id">
$com6 <a href="$furl/comments.php?id=$id">$com7</a>
HTML;
  }else{
    echo $com8;
  }
}
  echo get_template('com_footer.php', TRUE);
?>

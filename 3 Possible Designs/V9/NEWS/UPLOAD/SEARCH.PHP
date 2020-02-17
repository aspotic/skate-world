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
  error_reporting(E_ALL);

  require "./config.php";
  require "./functions.php";

  echo get_template('header.php', TRUE);

  $VARS = parse_incoming();

  if(!isset($VARS["id"])){ $id = "";}else{$id = $VARS["id"];}

  if( $id == "" ){ /*id Index*/
echo <<<html
<SCRIPT language=JAVASCRIPT>
<!-- Begin
  function flip(rid)
  {
    current=(document.getElementById(rid).style.display == 'none') ? 'block' : 'none';
    document.getElementById(rid).style.display = current;
  }
  function InsertHelp(between, and)
  {
    document.searchform.srchdatefrom.value = between;
    document.searchform.srchdateto.value   = and;
  }
// End -->
</SCRIPT>
<center>
<table width=80% cellspacing=0 cellpadding=0 border=0>
<form action="$furl/search.php?id=search" method="post" name="searchform">
<tr>
<td width=100% colspan="2" valign="top">
$srch0
</td>
</tr>
<tr>
<td width=30% valign="top">
$srch1
</td>
<td width=70% valign="top">
<input size="30" type="text" class="post" name="srchuser">
</td>
</tr>
<tr>
<td width=30% valign="top">
$srch2
</td>
<td width=70% valign="top">
<input size="37" type="text" class="post" name="srchsubject">
</td>
</tr>
<tr>
<td width=30% valign="top">
$srch3
</td>
<td width=70% valign="top">
<input size="20" type="text" class="post" name="srchdatefrom">
$srch4 <input size="20" type="text" class="post" name="srchdateto">
</td>
</tr>
<tr>
<td width=100% valign="top" colspan="2">
<SPAN id=datehelp>
<SCRIPT language=JAVASCRIPT>flip('datehelp');</SCRIPT>
$srch5
<table border="0" width="100%">
  <tr>
    <td width="41%"><b>$srch6</b></td>
  </tr>
  <tr>
    <td width="41%">$srch7</td>
  </tr>
  <tr>
    <td width="41%">$srch8</td>
  </tr>
  <tr>
    <td width="41%">$srch9</td>
  </tr>
</table>
$srch10
</SPAN>
</td>
</tr>
<tr>
<td width=30% valign="top">
$srch11
</td>
<td width=70% valign="top">
<textarea class="multitext" rows="4" cols="30" name="srchnews"></textarea>
</td>
</tr>
<tr>
<td width=30% valign="top">
$srch12
</td>
<td width=70% valign="top">
<textarea class="multitext" rows="4" cols="30" name="srchfullnews"></textarea>
</td>
</tr>
<tr>
<td width=30% valign="top">
$srch13
</td>
<td width=70% valign="top">
<input type="checkbox" class="post" name="case_sens">
</td>
</tr>
<tr>
<td width=30% valign="top">
$srch14
</td>
<td width=70% valign="top">
<input type="checkbox" class="post" name="archive_search" checked>
</td>
</tr>
<tr>
<td width=15 valign="top">
<br>
<input type="submit" class="mainoption" value="Search"><br>
</form>
</td>
</tr>
</table>
html;
}

if($id == "search"){ /*id Search*/
  $srchuser     = $VARS["srchuser"];
  $srchsubject  = $VARS["srchsubject"];
  $srchdateto   = $VARS["srchdateto"];
  $srchdatefrom = $VARS["srchdatefrom"];
  $srchnews     = $VARS["srchnews"];
  $srchfullnews = $VARS["srchfullnews"];

  if (trim($srchdatefrom) == "") $srchdatefrom = "0";
  if (trim($srchdateto) == "") $srchdateto = "now";
  if ((preg_match("/\//", $srchuser)) or (preg_match("/\//", $srchsubject)) or
      (preg_match("/\//", $srchnews)) or (preg_match("/\//", $srchfullnews)) or
      (preg_match("/\//", $srchdatefrom)) or (preg_match("/\//", $srchdateto))){
     echo $srch15;
  }elseif(($timestamp = strtotime($srchdatefrom)) == -1) {
     echo $srch16;
  }elseif(($timestamp = strtotime($srchdateto)) == -1) {
     echo $srch17;
  }elseif($srchdateto < $srchdatefrom) {
     echo $srch18;
  }else{
    if (isset($_VARS["case_sens"])){
      $srchuser1 = "/$srchuser/";
      $srchsubject1 = "/$srchsubject/";
      $srchnews1 = "/$srchnews/";
      $srchfullnews1 = "/$srchfullnews/";
    }else{
      $srchuser1 = "/$srchuser/i";
      $srchsubject1 = "/$srchsubject/i";
      $srchnews1 = "/$srchnews/i";
      $srchfullnews1 = "/$srchfullnews/i";
    }
    $cont = "";
    $count = 0;
    $file = file($fpath."news.db");
    if($numofposts > count($file)){ $range = count($file);}else{$range = $numofposts;}
    for($i= 0;$i <= $range-1;$i++){
      list($newsm,$fnewsm,$userm,$subm,$emailm,$iconm,$datem,$numcom,$rand) = explode("|<|", $file[$i]);
      if (/*(preg_match("$srchuser1", $userm)) && (preg_match("$srchsubject1", $subm)) &&*/
          (preg_match("$srchnews1", $newsm)) && (preg_match("$srchfullnews1", $fnewsm)) &&
          ($srchdatefrom < $datem && $datem < $srchdateto)){
       {
         if($srchuser != "") $userm = str_replace($srchuser, "<b>$srchuser</b>", $userm);
         if($srchsubject != "") $subm = str_replace($srchsubject, "<b>$srchsubject</b>", $subm);
         if($srchnews != "") $newsm = str_replace($srchnews, "<b>$srchnews</b>", $newsm);
         if($srchfullnews != "") $fnewsm = str_replace($srchfullnews, "<b>$srchfullnews</b>", $fnewsm);
       }
         if($fnewsm == ""){
           $fullnewsl = "";
         }else{
         if($fsnw == "checked") {
           $fullnewsl = "<br><a href='#$rand' onClick=window.open(\"$furl/news.php?id=$rand\",\"\",\"height=$fullnewsh,width=$fullnewsw,toolbar=no,menubar=no,scrollbars=".checkvalue($fullnewss).",resizable=".checkvalue($fullnewsz)."\")>$fslink</a>";
         } else{
           $fullnewsl = "<br><a href=\"$furl/news.php?id=$rand\">$fslink</a>";
         }
         }
         //use icon?
         if($iconm == ""){
           $icon1 = "";
         }else{
           $icon1 = "<img src=\"$iconm\">";
         }
         if($numcom < 0 or $numcom == "")
           $numcom = 0;
         $newsm1 = $newsm;
         $fullnewsm = $fnewsm;

         if($cbwordwrap){
           $newsm1 = fusion_wordwrap($newsm1, $wwwidth);
           $fullnewsm = fusion_wordwrap($fullnewsm, $wwwidth);
         }
         if($wfpost == "checked"){
           $subm = filterbadwords($subm);
           $newsm1 = filterbadwords($newsm1);
           $fullnewsm = filterbadwords($fullnewsm);
         }
         //html tags
         if($ht != "checked"){
           $subm = strip_tags($subm);
           $newsm1 = strip_tags($newsm1);
           $fullnewsm = strip_tags($fullnewsm);
         }
         //bbcode
         if($bb == "checked"){
           $subm = InsertBBCode($subm);
           $newsm1 = InsertBBCode($newsm1);
           $fullnewsm = InsertBBCode($fullnewsm);
         }
         if($smilies == "checked"){
           $subm = InsertSmillies($subm,$furl);
           $newsm1 = InsertSmillies($newsm1,$furl);
           $fullnewsm = InsertSmillies($fullnewsm,$furl);
         }

         $newsm1 = str_replace("&br;", "<br>", $newsm1);
         $fullnewsm = str_replace("&br;", "<br>", $fullnewsm);

       //replace user variables
         $tem = str_replace("|<|", "", $temp);
         $tem = str_replace("{subject}", $subm, $tem);
         $tem = str_replace("{user}", "<a href=\"mailto:$emailm\">$userm</a>", $tem);
         $tem = str_replace("{date}", date($datefor, $datem), $tem);
         $tem = str_replace("{news}", $newsm1, $tem);
         $tem = str_replace("{icon}", $icon1, $tem);
         $tem = str_replace("{fullstory}", $fullnewsl, $tem);
         $tem = str_replace("{nrc}", $numcom, $tem);
         $tem = str_replace("{comments}", "<a href=\"$furl/comments.php?id=$rand\">$pclink</a>", $tem);
         if($stfpop == "checked") {
           $tem = str_replace("{send}", "<a href='#' onClick=window.open(\"$furl/send.php?id=$rand\",\"\",\"height=$stfheight,width=$stfwidth,toolbar=no,menubar=no,scrollbars=".checkvalue($stfscrolls).",resizable=".checkvalue($stfresize)."\")>$stflink</a>", $tem);
         }else{
           $tem = str_replace("{send}", "<a href=\"$furl/send.php?id=$rand\">$stflink</a>", $tem);
         }
       if ($fnewsm != ""){
         $tem .= "<b>Full Story:</b><br>";
         $tem .= InsertSmillies(InsertBBCode($fnewsm), $furl);
         $tem .= "<hr><br>";
       }

       //what to write
       $cont  .= "<a name=\"$rand\"></a>$tem\n";
       $count++;
      }
    }
    if (isset($_VARS["archive_search"])){
    $file = file($fpath."news.db");
    if($numofposts < count($file))
    for($i= $numofposts;$i <= count($file)-1;$i++){
      list($newsm,$fnewsm,$userm,$subm,$emailm,$iconm,$datem,$numcom,$rand) = explode("|<|", $file[$i]);
      if ((preg_match("$srchuser1", $userm)) && (preg_match("$srchsubject1", $subm)) &&
          (preg_match("$srchnews1", $newsm)) && (preg_match("$srchfullnews1", $fnewsm)) &&
          ($srchdatefrom < $datem && $datem < $srchdateto)){
       {
         if($srchuser != "") $userm = str_replace($srchuser, "<b>$srchuser</b>", $userm);
         if($srchsubject != "") $subm = str_replace($srchsubject, "<b>$srchsubject</b>", $subm);
         if($srchnews != "") $newsm = str_replace($srchnews, "<b>$srchnews</b>", $newsm);
         if($srchfullnews != "") $fnewsm = str_replace($srchfullnews, "<b>$srchfullnews</b>", $fnewsm);
       }
       if($fullnewsm == ""){
         $fullnewsl = "";
       }else{
         if($fsnw == "checked") {
           $fullnewsl = "<br><a href='#$rand' onClick=window.open(\"$furl/fnarchive.php?id=$rand\",\"\",\"height=$fullnewsh,width=$fullnewsw,toolbar=no,menubar=no,scrollbars=".checkvalue($fullnewss).",resizable=".checkvalue($fullnewsz)."\")>$fslink</a>";
         }else{
           $fullnewsl = "<br><a href=\"$furl/fnarchive.php?id=$rand\">$fslink</a>";
         }
       }
       //use icon?
       if($iconm == ""){
         $icon1 = "";
       }else{
         $icon1 = "<img src=\"$iconm\">";
       }
       if($numcom < 0 or $numcom == "")
         $numcom = 0;
       if($cbwordwrap){
         $newsm1 = fusion_wordwrap($newsm1, $wwwidth);
         $fullnewsm = fusion_wordwrap($fullnewsm, $wwwidth);
       }
       if($wfpost == "checked"){
         $subm = filterbadwords($subm);
         $newsm1 = filterbadwords($newsm1);
         $fullnewsm = filterbadwords($fullnewsm);
       }
       //html tags
       if($ht != "checked"){
         $subm = strip_tags($subm);
        $newsm1 = strip_tags($newsm1);
         $fullnewsm = strip_tags($fullnewsm);
       }
       //bbcode
       if($bb == "checked"){
         $subm = InsertBBCode($subm);
         $newsm1 = InsertBBCode($newsm1);
         $fullnewsm = InsertBBCode($fullnewsm);
       }
       if($smilies == "checked"){
         $subm = InsertSmillies($subm,$furl);
         $newsm1 = InsertSmillies($newsm1,$furl);
         $fullnewsm = InsertSmillies($fullnewsm,$furl);
       }

       $newsm1 = str_replace("&br;", "<br>", $newsm1);
       $fullnewsm = str_replace("&br;", "<br>", $fullnewsm);
     //replace user variables
       $tem = str_replace("|<|", "", $arctemp);
       $tem = str_replace("{subject}", $subm, $tem);
       $tem = str_replace("{user}", "<a href=\"mailto:$emailm\">$userm</a>", $tem);
       $tem = str_replace("{date}", date($datefor, $datem), $tem);
       $tem = str_replace("{news}", $newsm1, $tem);
       $tem = str_replace("{icon}", $icon1, $tem);
       $tem = str_replace("{fullstory}", $fullnewsl, $tem);
       $tem = str_replace("{nrc}", $numcom, $tem);
       $tem = str_replace("{comments}", "<a href=\"$furl/comments.php?id=$rand\">$pclink</a>", $tem);
       if($stfpop == "checked"){
         $tem = str_replace("{send}", "<a href='#' onClick=window.open(\"$furl/send.php?id=$rand\",\"\",\"height=$stfheight,width=$stfwidth,toolbar=no,menubar=no,scrollbars=".checkvalue($stfscrolls).",resizable=".checkvalue($stfresize)."\")>$stflink</a>", $tem);
       }else{
         $tem = str_replace("{send}", "<a href=\"$furl/send.php?id=$rand\">$stflink</a>", $tem);
       }
       if ($fnewsm != ""){
         $tem .= "<b>Full Story:</b><br>";
         $tem .= InsertSmillies(InsertBBCode($fnewsm), $furl);
         $tem .= "<hr><br>";
       }

       //what to write
       $cont  .= "<a name=\"$rand\">$tem\n";
       $count++;
      }
    }
    }
    if($count == 0) echo $srch19; else echo $cont;
  }
}

echo get_template('footer.php', TRUE);
?>

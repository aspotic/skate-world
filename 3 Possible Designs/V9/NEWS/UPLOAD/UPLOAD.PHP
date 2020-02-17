<?php

/*
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
*/
error_reporting(E_ALL ^ E_NOTICE);

require "./config.php";
include_once ("./functions.php");
require "./language.db";
$curve = "3.6";

set_magic_quotes_runtime(0);
if (!$cookies == "checked")
  session_start();

$VARS = parse_incoming();
if( !has_access( 1 )) { echo "You are not authorized to use this future."; exit; }
if( $uploads_active != "checked") { echo "This feature is disabled by the administrator."; exit; }
if(!isset($VARS["id"])){ $id = "";}else{$id = $VARS["id"];}

    echo "<html>\n";
    echo "\n";
    echo "<head>\n";
    echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=windows-1252\">\n";
    echo "<title>Upload Files</title>\n";
    echo "<style type=\"text/css\">\n";
    echo "A, A:ACTIVE, A:FOCUS, A:LINK, A:VISITED {\n";
    echo "	color: #2D617D;\n";
    echo "	text-decoration: underline;\n";
    echo "}\n";
    echo "\n";
    echo "A:HOVER {\n";
    echo "	color: #22495E;\n";
    echo "	text-decoration: none;\n";
    echo "}\n";
    echo "\n";
    echo "BODY {\n";
    echo "	scrollbar-3dlight-color: #FFFFFF;\n";
    echo "	scrollbar-arrow-color: #2D617D;\n";
    echo "	scrollbar-darkshadow-color: #FFFFFF;\n";
    echo "	scrollbar-face-color: #E8F4FD;\n";
    echo "	scrollbar-highlight-color: #CCE9EC;\n";
    echo "	scrollbar-shadow-color: #8CCCCD;\n";
    echo "	scrollbar-track-color: #FFFFFF;\n";
    echo "}\n";
    echo "\n";
    echo "BODY, TD{\n";
    echo "	cursor: default;\n";
    echo "}\n";
    echo "\n";
    echo "body, td, INPUT, TEXTAREA, SELECT {\n";
    echo "	color: #2D617D;\n";
    echo "	font: 11px verdana;\n";
    echo "	font-weight: none;\n";
    echo "	text-decoration: none;\n";
    echo "}\n";
    echo "\n";
    echo "INPUT {\n";
    echo "	border-color: #A5B8C0 #A5B8C0 #A5B8C0 #A5B8C0;\n";
    echo "	border:1px solid #9BAEB7; background-color: #DEF1FC;\n";
    echo "	text-indent: 2px;\n";
    echo "}\n";
    echo "\n";
    echo "/* The buttons used for bbCode styling in message post */\n";
    echo "INPUT.button {\n";
    echo "	color: #2D617D;\n";
    echo "	font-family: Verdana, Arial, Helvetica, sans-serif;\n";
    echo "	font-size: 8px;\n";
    echo "}\n";
    echo "\n";
    echo ".contentBG {\n";
    echo "	background-color: #FFFFFF;\n";
    echo "	padding: 4px 4px 4px 4px;\n";
    echo "}\n";
    echo "\n";
    echo ".headBG1 {\n";
    echo "	background-color: #EDF7FC;\n";
    echo "	padding: 2px 2px 2px 2px;\n";
    echo "}\n";
    echo "\n";
    echo ".mainBG {\n";
    echo "	background-color: #CDDBE2;\n";
    echo "}\n";
    echo "\n";
    echo ".textLogin {\n";
    echo "	font-size: 9px;\n";
    echo "}\n";
    echo "</style>\n";
    echo "</head>\n";
    echo "\n";
    echo "<body topmargin=\"2\" leftmargin=\"0\" link=\"#000000\" vlink=\"#000000\" alink=\"#000000\">\n";
    echo "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
    echo "    <tr>\n";
    echo "      <td align=\"center\" valign=\"middle\">\n";
    echo "        <table width=\"99%\" border=\"0\" cellpadding=\"2\" cellspacing=\"1\" onmouseover=\"this.style.backgroundColor = '#BCCBD2';\" onmouseout=\"this.style.backgroundColor = '#CDDBE2';\" class=\"mainBG\">\n";

if( $id == ""){
    echo "<form method=\"POST\" enctype=\"multipart/form-data\" action=\"$furl/upload.php?id=upload\">\n";
    echo "  <tr>\n";
    echo "    <td align=\"center\" valign=\"middle\" class=\"headBG1\">\n";
    echo "      $ind216 \"$furl/uploads\"!<br>\n";
    echo "      $ind218 <b>$uploads_ext</b>.<br>$ind219 <b>".calc_size($uploads_size)."</b>.<br>\n";
    echo "    </td>\n";
    echo "  </tr>\n";
    echo "  <tr>\n";
    echo "    <td onmouseover=\"this.style.backgroundColor = '#FBFDFF';\" onmouseout=\"this.style.backgroundColor = '#FFFFFF';\" class=\"contentBG\">\n";
    echo "        <input type='hidden' name='MAX_FILE_SIZE' value='<?php echo $uploads_size ?>' />\n";
    echo "	  <input class=\"post\" type=\"file\" name=\"F0\" size=\"50\" style=\"font-family: Verdana; font-size: 10px\" /><br />\n";
    echo "	  <input class=\"post\" type=\"file\" name=\"F1\" size=\"50\" style=\"font-family: Verdana; font-size: 10px\" /><br />\n";
    echo "	  <input class=\"post\" type=\"file\" name=\"F2\" size=\"50\" style=\"font-family: Verdana; font-size: 10px\" /><br />\n";
    echo "	  <input class=\"post\" type=\"file\" name=\"F3\" size=\"50\" style=\"font-family: Verdana; font-size: 10px\" /><br />\n";
    echo "	  <input class=\"post\" type=\"file\" name=\"F4\" size=\"50\" style=\"font-family: Verdana; font-size: 10px\" /><br />\n";
    echo "</td>\n";
    echo "  </tr>\n";
    echo "  <tr>\n";
    echo "    <td align=\"center\" valign=\"middle\" class=\"headBG1\">\n";
    echo "        <input type=\"submit\" name=\"com_Submit\" class=\"mainoption\" value=\"Upload\" style=\"font-family: Verdana; font-size: 10px\" />\n";
    echo "        <br />\n";
    echo "    </td>\n";
    echo "  </tr>\n";
    echo "</form>\n";
}elseif( $id == "upload"){
    if ( $uploads_active == "checked" ){
      $content = "<tr><td bgcolor=\"#ffffff\">$ind262<br>";
      for ($i = 0; $i < 10; $i++ ){
        $upload = "F".$i;
        if ( isset($HTTP_POST_FILES["$upload"]) ){
          $content .= upload_file($upload, $fpath.'uploads/', $uploads_ext, $uploads_size, $HTTP_POST_FILES["$upload"]["name"])."<br>";
        }
      }
      $title = $ind99;
      $content .= "</td></tr>";
    }
    echo $content;
    echo "<tr><td bgcolor=\"#ffffff\"><a href=\"$furl/upload.php\">upload more</a></td></tr>\n";
}elseif( $id == "imagelist" ){
  $content   = "";
  $content  .= "<tr>\n";
  $content  .= "  <td align=\"center\" valign=\"middle\" class=\"headBG1\" colspan=\"4\"><center><a href=\"$furl/upload.php?id=imagelist\">refresh</a></center>\n";
  $content  .= "</tr>\n";
  $content  .= "<tr bgcolor=\"whitesmoke\" bordercolor=\"#dcdcdc\">\n";
  $content  .= "  <td align=\"center\" valign=\"middle\" class=\"headBG1\" width=\"100\">Insert in\n";
  $content  .= "  <td align=\"center\" valign=\"middle\" class=\"headBG1\">$ind226</td>\n";
  $content  .= "  <td align=\"center\" valign=\"middle\" class=\"headBG1\" width=\"70\" align=\"right\">$ind227&nbsp;</td>\n";
  $content  .= "  <td align=\"center\" valign=\"middle\" class=\"headBG1\" width=\"150\" align=\"center\">$ind228</td>\n";
  $content  .= "</tr>\n";
  $filesize_cnt = 0;
  if ($dir = @opendir("./uploads")) {
    while (($file = readdir($dir)) !== false) {
      if($file=="." or $file=="..") continue;
      $curentfile_size = filesize("./uploads/".$file);
      $filesize_cnt = $filesize_cnt + $curentfile_size;
      $content  .= "<tr>\n";
      $content  .= "  <td bgcolor=\"#ffffff\" width=\"100\">[<a href=\"#\" onClick=\"opener.document.forms['newsposting'].news.value  += ' [img]$furl/uploads/".$file."[/img] ';\">news</a>]&nbsp;[<a href=\"#\" onClick=\"javascript:opener.document.forms['newsposting'].fullnews.value  += ' [img]$furl/uploads/".$file."[/img] ';\">fullnews</a>]\n";
      $content  .= "  <td bgcolor=\"#ffffff\"><a href='#' onClick=window.open(\"$furl/uploads/$file\",\"\",\"height=400,width=400,toolbar=no,menubar=no,scrollbars=yes,resizable=yes\")>$file</a></td>\n";
      $content  .= "  <td bgcolor=\"#ffffff\" width=\"70\" align=\"right\">".calc_size($curentfile_size)."&nbsp;</td>\n";
      $content  .= "  <td bgcolor=\"#ffffff\" width=\"150\" align=\"center\">".date ("F d Y H:i:s", filemtime("./uploads/".$file))."</td>\n";
      $content  .= "</tr>\n";
    }
    closedir($dir);
  }
  echo $content;
}
    echo "          <tr>\n";
    echo "            <td align=\"center\" colspan=\"4\" valign=\"middle\" class=\"headBG1\"><span class=\"textLogin\">fusionphp</span></td>\n";
    echo "          </tr>\n";
    echo "  </table>\n";
    echo "        </td>\n";
    echo "    </tr>\n";
    echo "  </table>\n";
    echo "</table></center>\n";
    echo "</body>\n";
    echo "\n";
    echo "</html>\n";
?>

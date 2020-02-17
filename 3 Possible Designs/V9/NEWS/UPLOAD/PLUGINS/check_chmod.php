<?php

/******************
fusion news management
by the fusion team

version 3.6

fusionphp.com
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

      $plugin_link = "<a href=\"./plugins/check_chmod.php\">Check CHMOD plugin</a>";
      $plugin_version = 'v0.0.0.2';
      $plugin_description = "This plugin allows you to add the capability for the script to check if the critical files can be accessed for reading and writing. It's a test script to show the capabilities of the plugin feature. <br /> Update: Now it also shows the statistics.<br />This plugin is activated by default.";
      $plugin_name = "Check CHMOD";

if (!function_exists('add_magic_quotes')) {
	function add_magic_quotes($array) {
		foreach ($array as $k => $v) {
			if (is_array($v)) {
				$array[$k] = add_magic_quotes($v);
			} else {
				$array[$k] = addslashes($v);
			}
		}
		return $array;
	}
}

      if(!get_magic_quotes_gpc()) {
            $HTTP_GET_VARS     = add_magic_quotes($HTTP_GET_VARS);
            $HTTP_POST_VARS    = add_magic_quotes($HTTP_POST_VARS);
            $HTTP_SESSION_VARS = add_magic_quotes($HTTP_SESSION_VARS);
      }

      if(!isset($HTTP_GET_VARS["id"])){ $id = "";}else{$id = $HTTP_GET_VARS["id"];}

      function check_file_access($folder){
            require $folder."config.php";
            
            global $HTTP_SESSION_VARS, $HTTP_COOKIE_VARS;
            
            $files = array("fusionnews.xml", "config.php", "smillies.db", "users.php", "news.php", "headlines.php", "badwords.txt", "banned.php", "flood.php", "news", "news/toc.php");
            $data = "";
            if ( has_access (1) ){
                  if( $cookies == "checked" )
                        $name = $HTTP_COOKIE_VARS["fusionick"];
                  else
                        $name = $HTTP_SESSION_VARS["fusionick"];
                        
                  $data .= "<center>Welcome back <b>$name</b>!</center>";
            }
                  
            $data .= //"<br><b>Statistics</b>(This is an example of the new plugin feature):
                     "<div align=\"center\"><table width='100%' border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: collapse\" bordercolor=\"#dcdcdc\">\n";

            $array = array();
     
            foreach ($files as $value ){
                  if ( !is_writeable( $folder.$value ) ){
                        $array[]= "   <tr>\n
                                      <td align=\"center\" width=\"50%\">Can not read/write to ".$value."</td>\n
                                      <td align=\"center\" width=\"50%\"><font color=\"red\">please chmod to 777</font></td>
                                      </tr>\n";
                  }
            }

            if( count($array) == 0 ){
                  $data .= "  <tr>\n
                              <td align=\"center\" colspan='2' width=\"100%\"><font color=\"green\">Congratulations, all files are accessible!</font></td>
                              </tr>\n";
            }else{
                  foreach ($array as $value ){
                        $data .= $value;
                  }
            }

            $data .= "</table><br />\n";
            
            $count = 0;
            $count_today = 0;
            $count_user = 0;
            if(has_access( 1 ))
            if( file_exists("./news/toc.php") ){
            
                $file = file("./news/toc.php");
                
                my_array_shift($file);
                
                $today = strtotime(date("m/d/Y", strtotime("now")));
                if( $cookies == "checked" )
                        $name = $HTTP_COOKIE_VARS["fusionuser"];
                  else
                        $name = $HTTP_SESSION_VARS["fusionuser"];
                foreach( $file as $value){
                      if ( $value != "" ){
                            $count++;
                            list($news_id,$news_date,$news_writer,$news_subject) = explode("|<|", $value);
                            if ( $news_date > $today && $news_date < $today+86400 )
                               $count_today++;
                            if ( $news_writer == $name )
                              $count_user++;
                      }
                }
            }
            
            $data .= "There are <b>$count</b> newsitems in your database. ";
            $data .= "You wrote <b>$count_user</b> of them.<br>";
            $data .= "There were <b>$count_today</b> newsitems posted today.<br>";
            return $data."<br /><b>What do you want to do today:</b></div>";
      }

if ( $id == "" ){
  global $plugin_active;
  if ( ! $plugin_active ){
     $example = "";
     //$example = "<p><br /><b>Example</b>:".check_file_access("../")."</p>";
echo <<<html
      <html>

      <head>
      <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
      <title>Fusion News 3.6 - Check if critical files are accessible</title>
      <style>
      <!--
      body, td { color: #000000; font: 10pt verdana; font-weight: none; text-decoration: none; }
      -->
      </style>
      </head>

      <body style="font-family: Verdana; font-size: 10pt">
      This plugin adds the capability to check if your files and folders have been
      given the right access rights and shows you it's findings on the main Fusion
      News page.<p><br />
      <br /><b>To activate this plugin, you need to add some code to the index page.</b><br />
      <br /><b>File to edit</b>: index.php (in main installation folder of Fusion News)<br />
      <br /><b>Code that needs to be added</b>:<br />
      </p>
      <table border="1" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#DCDCDC" width="100%">
        <tr>
          <td width="100%"><font color="#000080">&#36;plugin_active</font> =
          <font color="#008000">TRUE</font>;<br>
          include (<font color="#000080">&#36;fpath</font>.<font color="#FF0000">&quot;plugins/check_chmod.php&quot;</font>);<br>
          <font color="#000080">&#36;content</font> = check_file_access(<font color="#FF0000">&quot;./&quot;</font>);<br>
          <font color="#000080">&#36;content</font> .= <font color="#000080">&#36;ind13</font>;</td>
        </tr>
      </table>

      <br />On a clean installation the insertion of the above code should be on
      linenumber <b>47 </b>of &quot;<b>index.php</b>&quot;.<br />
      <br /><b>right after</b>:<br />
      <table border="1" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#DCDCDC" width="100%" id="AutoNumber1">
        <tr>
          <td width="100%">
        <font size="2"><font color="#008000">if</font>(<font color="#000080">&#36;first</font>
          == <font color="#FF0000">&quot;yes&quot;</font>){<br>
          <font color="#000080">&#36;title</font> = <font color="#000080">&#36;ind2</font>;<br>
          <font color="#000080">&#36;content</font> = <font color="#000080">&#36;ind1</font>;<br>
          }<font color="#008000">elseif</font>(has_access( 1 )){<br>
          <font color="#000080">&#36;title</font> = <font color="#000080">&#36;ind9</font>;<br /><td>
        </tr>
      </table>
      <br /><b>and right before</b>:<br />
      <table border="1" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#DCDCDC" width="100%" id="AutoNumber2">
        <tr>
          <td width="100%"><font color="#008000">if</font>(has_access( 3 )){<br>
          <font color="#000080">&#36;content</font> .= <font color="#000080">&#36;ind11</font>;<br>
          }<br>
          i<font color="#008000">f</font>(has_access( 2 )&amp;&amp;(! has_access( 3 ))){<br>
          <font color="#000080">&#36;content</font> .= <font color="#000080">&#36;ind12</font>;<br>
          }</td>
        </tr>
      </table>
      <br /><b>this means that you have to delete 1 line between the &quot;right before&quot; and
      &quot;right after&quot; part.</b>
      <table border="1" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#DCDCDC" width="100%" id="AutoNumber2">
        <tr>
          <td width="100%" style="color: #000000; font-style: normal; font-variant: normal; font-weight: none; font-size: 10pt; font-family: verdana; text-decoration: none">
          <font color="#000080">&#36;content</font> = <font color="#000080">&#36;ind13</font>;</td>
        </tr>
      </table>
      $example
      </body>

      </html>
html;
  }
}

if ( $id == "plugin" ){

}

?>

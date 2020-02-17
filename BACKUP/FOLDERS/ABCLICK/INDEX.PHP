<?
session_start();

	if ($valid != "yes"){
 
// change for web

	header("location: password.php");
	exit;

	}else{
	session_register('valid');
	}
	
require("shared.inc.php");

// IF DATES ROUND THE WRONG WAY.

if ($byday1 < $byday2) $wng_msg = "<font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FF0000\">The 
  end date is earlier than the start date. <br>Please reselect using the top dropdown 
  as the earliest date.</font>";



$connection = @mysql_connect("$dbhost", "$dbuser", "$dbpasswd") or die("Couldn't connect.");
$db = @mysql_select_db($dbname, $connection) or die("Couldn't select database.");

//START OF DATES:

$sql_day = "SELECT DISTINCT day 
			FROM clicks 
			ORDER BY day DESC";

      $day_result = mysql_query($sql_day,$connection) or die("Couldn't execute database query.");

        while ($row = mysql_fetch_array($day_result)) {
				$day  = $row["day"];

       // structure by date form value field
        $stat_dates1 .= "<OPTION value=\"$day\">$day</OPTION>\n";
 		$stat_dates2 .= "<OPTION value=\"$day\">$day</OPTION>\n";
}


// URL LIST
$sql_url = "SELECT DISTINCT url 
			FROM clicks
		
			ORDER BY url ASC
				";

        $url_result = mysql_query($sql_url,$connection) or die("Couldn't execute database query.");

        while ($row = mysql_fetch_array($url_result)) {

            $url  = $row["url"];

       // structure by date form value field
        $stat_url .= "<OPTION value=\"$url\">$url</OPTION>\n";
		
}
//END OF URL LIST

$sql = "SELECT *
	FROM clicks
	
	WHERE day <= '$byday1' and day >= '$byday2' and url = '$url_choose'";

$result = @mysql_query($sql,$connection) or die(mysql_error());



while ($row = mysql_fetch_array($result)) {

	$day = $row['day'];
	$url = $row['url'];
	$raw = $row['raw'];
	$uni = $row['uni'];
 $list .= "Day = $day and URL = $url  $raw and $uni<br>";
 
}

	//RAW CLICKS
	$get_item_raw	=	"SELECT SUM(raw)
						FROM clicks
							WHERE day <= '$byday1' and day >= '$byday2' and url = '$url_choose'";
		
		$item_raw_result = mysql_query($get_item_raw) or die(mysql_error());
			$item_raw = mysql_result($item_raw_result,0,"SUM(raw)");
		//END RAW CLICKS
	//UNI CLICKS
	$get_item_uni	=	"SELECT SUM(uni)
						FROM clicks
							WHERE day <= '$byday1' and day >= '$byday2' and url = '$url_choose'";
		
		$item_uni_result = mysql_query($get_item_uni) or die(mysql_error());
			$item_uni = mysql_result($item_uni_result,0,"SUM(uni)");
		//END RAW CLICKS


//echo "$list";
	 
	 if ($url_choose == "") 
	 {
	 $option = " <option value=\"No Selection\" selected><-- Choose --></option>";
	 }else{
	 $option = " <option value=\"$url_choose\" selected>$url_choose</option>";
	 }
?>


<html>
<head>
<title><? echo "$site_name";?> Click Through Statistics  </title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<script type=text/javascript>
<!--
var win= null;
function NewWindow(mypage,myname,w,h,scroll){
  var winl = (screen.width-w)/2;
  var wint = (screen.height-h)/2;
  var settings  ='height='+h+',';
      settings +='width='+w+',';
      settings +='top='+wint+',';
      settings +='left='+winl+',';
      settings +='scrollbars='+scroll+',';
      settings +='resizable=yes';
  win=window.open(mypage,myname,settings);
  if(parseInt(navigator.appVersion) >= 4){win.window.focus();}
}
// -->
</script>

</head>

<body bgcolor="#FFFFFF" text="#000000" link="#0000CC" vlink="#0033FF">
<table width="670" border="0" align="center">
  <tr bgcolor="#000099"> 
    <td colspan="2"> 
      <div align="center"><font face="Arial, Helvetica, sans-serif" size="2" color="#FFFFFF"><b><font face="Verdana, Arial, Helvetica, sans-serif"><? echo "$site_name";?> 
        Click Through Statistics </font></b></font></div>
    </td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td colspan="2">
      <form method="post" action="<?php echo $PHP_SELF; ?>">
        <table width="565" height="44">
          <tr> 
            <td colspan="2" align="right"> 
              <div align="left"><font face="verdana, arial" size="2">View Statistics 
                for: </font></div>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="right"> 
              <div align="left"> 
                <p> 
                  <select name="url_choose">
                    <? echo "$option"; ?>
                    <?php echo "$stat_url"; ?>
                  </select>
                  <a href="help/domain.php" onclick="NewWindow(this.href,'name','400','200','no');return false"> 
                  <img src="img/help.jpg" width="20" height="20" alt="Click for help" border="0"></a></p>
                <p>&nbsp;</p>
              </div>
            </td>
          </tr>
          <tr> 
            <td width="28%" align="right" nowrap> 
              <p align="left"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"> 
                <select name="byday2">
                  <option value="No Selection" selected><-- Choose a Date --></option>
                  <?php echo "$stat_dates2"; ?>
                </select>
                </font></p>
            </td>
            <td width="72%" align="left"> 
              <div align="left"> <a href="help/start.php" onclick="NewWindow(this.href,'name','400','200','no');return false"> 
                <img src="img/help.jpg" width="20" height="20" border="0"></a><font face="Verdana, Arial, Helvetica, sans-serif" size="2"> 
                Between:<font size="1"><i>(start date)</i></font></font></div>
            </td>
          </tr>
          <tr> 
            <td width="28%" align="right"> 
              <div align="left"> 
                <select name="byday1">
                  <option value="No Selection" selected><-- Choose a Date --></option>
                  <?php echo "$stat_dates1"; ?>
                </select>
              </div>
            </td>
            <td width="72%" align="left"> 
              <div align="left"> <a href="help/end.php" onclick="NewWindow(this.href,'name','400','200','no');return false"> 
                <img src="img/help.jpg" width="20" height="20" border="0"></a> 
                <font face="Verdana, Arial, Helvetica, sans-serif" size="2">and: 
                </font> <font face="Verdana, Arial, Helvetica, sans-serif" size="2"><font size="1"><i>(end 
                date)</i></font></font></div>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="right"> 
              <div align="left"> </div>
              <div align="left"> 
                <p> <br>
                  <input type=submit value="submit" name="submit">
                </p>
                </div>
            </td>
          </tr>
        </table>
        <br>
        <table width="100%" border="0">
          <tr> 
            <td colspan="2"> 
              <?
			  if ($wng_msg == ""){ echo "
			  
			  <p>
			  	<font face=\"verdana, arial\" size=\"2\">Statistics for: 
                	<b>$url_choose</b>
                <br>
                	</font><font face=\"verdana, arial\" size=\"2\">Between: 
               			<b>$byday2</b> and <b>$byday1</b>
                			</font>
								</p>
				";
				
				}else{
				echo "$wng_msg";
				}
				?>
            </td>
          </tr>
          <tr> 
            <td width="24%" valign="middle" nowrap> <font face="Verdana, Arial, Helvetica, sans-serif" size="2"> 
              Unique Clicks:</font> <font face="Verdana, Arial, Helvetica, sans-serif" size="2"><b> 
              <? 
				if ($item_uni == "") $item_uni = "Nil";
				
				echo "$item_uni"; 
				?>
              </b></font></td>
            <td width="76%"> <a href="help/uni.php" onclick="NewWindow(this.href,'name','400','200','no');return false"> 
              </a><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><b> 
              </b><a href="help/uni.php" onClick="NewWindow(this.href,'name','400','200','no');return false"><img src="img/help.jpg" width="20" height="20" border="0"></a><b> 
              </b></font></td>
          </tr>
          <tr> 
            <td valign="middle" width="24%"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Raw 
              Clicks:<b> 
              <? 
			  if ($item_raw == "") $item_raw = "Nil";
			  echo "$item_raw"; 
			  ?>
              </b></font></td>
            <td width="76%"> <a href="help/raw.php" onclick="NewWindow(this.href,'name','400','200','no');return false"> 
              </a><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><b> 
              </b><a href="help/raw.php" onClick="NewWindow(this.href,'name','400','200','no');return false"><img src="img/help.jpg" width="20" height="20" border="0"></a><b> 
              </b></font></td>
          </tr>
        </table>
        <p align="left"><a href="help/help.php"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Main 
          help</font></a></p>
      </form>
</td>
  </tr>
</table>
</body>
</html>

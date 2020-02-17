<?

include("shared.inc.php");

$connection = @mysql_connect("$dbhost", "$dbuser", "$dbpasswd") or die("Couldn't connect.");

mysql_create_db($dbname, $connection) or die(mysql_error());

$db = @mysql_select_db($dbname, $connection) or die("Couldn't select database.");

mysql_query("CREATE TABLE clicks (
  day date NOT NULL default '0000-00-00',
  url varchar(120) NOT NULL default '',
  raw int(11) NOT NULL default '0',
  uni int(11) NOT NULL default '0'
) TYPE=MyISAM
") or die(mysql_error());

mysql_query("CREATE TABLE ip (
  day date NOT NULL default '0000-00-00',
  url varchar(120) NOT NULL default '',
  time timestamp(14) NOT NULL,
  ipnum varchar(15) NOT NULL default ''
) TYPE=MyISAM
") or die(mysql_error());

//Close Connection

mysql_close($connection);
?>

<html>
<head>
<title>Setup</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#FFFFFF" text="#000000">
<table width="670" border="0" align="center">
  <tr bgcolor="#000099"> 
    <td> 
      <div align="center"><font face="Arial, Helvetica, sans-serif" size="2" color="#FFFFFF"><b><font face="Verdana, Arial, Helvetica, sans-serif"> 
        <? echo "$site_name";?>
        Click Through Setup</font></b></font></div>
    </td>
  </tr>
  <tr> 
    <td> 
      <p><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><br>
        </font><font face="verdana, arial" size="2"><b>SETUP:</b></font></p>
      <p><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Set up has 
        been completed.</font></p>
      <p><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Add <font color="#FF0000"> 
        <?php echo "$track_url";?>/out.php?</font> To the beginning of each link you want to track.</font></p>
      <p><font face="Verdana, Arial, Helvetica, sans-serif" size="2">An example 
        would be &lt;a href="<font color="#FF0000"> 
        <?php echo "$track_url";?>/out.php?http://www.abweb.com/</font>"&gt;Abweb&lt;/a&gt;</font></p>
      <p><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><a href="index.php">Statistics 
        page</a></font></p>
      </td>
  </tr>
</table>
<p>&nbsp;</p></body>
</html>

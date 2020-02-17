<? require("../shared.inc.php"); ?>
<html>
<head>
<title>Help</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#FFFFFF" text="#000000" link="#0033CC" vlink="#0033FF">
<font face="verdana, arial" size="2"> </font> 
<table width="650" border="0" align="center">
  <tr bgcolor="#000099"> 
    <td> 
      <div align="center"><font face="Arial, Helvetica, sans-serif" size="2" color="#FFFFFF"><b><font face="Verdana, Arial, Helvetica, sans-serif"> 
        <? echo "$site_name";?>
        Click Through Help </font></b></font></div>
    </td>
  </tr>
  <tr> 
    <td><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><br>
      <b> 
      <? echo "$site_name";?>
      <font color="#000000">Click Through</font> Quick Overview: </b></font> 
      <ol>
        <li><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Select 
          a URL from the dropdown menu on the main statistics page.</font></li>
        <li><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Select 
          the inclusive dates you would like to see the statistics for. If you 
          require only one day then put the same date in both dropdown boxes. 
          </font></li>
        <li><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Click 
          submit. </font></li>
        <li><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Statistics 
          information will be displayed by raw clicks & unique clicks.</font></li>
      </ol>
      <p><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><b>Click 
        Through Linking Syntax Example: </b></font> </p>
      <p><font face="Verdana, Arial, Helvetica, sans-serif" size="2">&lt;a href="<font color="#FF0000"> 
        <?php echo "$track_url";?>/out.php?http://www.abweb.com/</font>"&gt;Abweb&lt;/a&gt;</font></p>
      <p><font face="Verdana, Arial, Helvetica, sans-serif" size="2">If you require 
        a new browser window to open use standard html tags.</font></p>
      <p><font face="Verdana, Arial, Helvetica, sans-serif" size="2">&lt;a href=&quot;<?php echo "$track_url";?>/out.php?http://www.abweb.co.uk/&quot; 
        <font color="#FF0000">target=&quot;_blank&quot;</font>&gt;Abweb&lt;/a&gt; 
        </font></p>
      <p><font face="Verdana, Arial, Helvetica, sans-serif" size="2">i.e <a href="http://www.studentcents.ie/abclick/out.php?http://www.abweb.co.uk/" target="_blank">Abweb</a> 
        </font></p>
      <p><font face="Verdana, Arial, Helvetica, sans-serif" size="2"> <a href="../index.php">main 
        statistics page</a></font></p>
      </td>
  </tr>
</table>
<font face="verdana, arial" size="2">
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</font> 
</body>
</html>

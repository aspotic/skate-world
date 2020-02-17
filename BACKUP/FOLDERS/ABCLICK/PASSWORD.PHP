<? require("shared.inc.php"); ?>
<?

session_start();

if ($op == "ds") {

// form completed, now attempt to validate
// password from data source here

	if (($username != "$user_name") || ($password != "$admin_pass")) {

// need to see form again, with error message

	 	$msg = "<P><font color=\"#FF0000\">Bad Login - Try Again.</font></p>";
	 	$show_form = "yes";
	} else {
		session_register('valid');
		$valid = "yes";
		$show_menu = "yes";
	}
 
} else {

	if ($valid == "yes") {
		$show_menu = "yes";
	} else {
		$show_form = "yes";
	}
}

if ($show_form == "yes") {

// login text just here	
// DISPLAY BLOCK

	$display_block ="

	    <p><font size=\"+2\"></font><br></p>
		
		 <p><font size=\"+1\">Please login with your username and password.</font><br></p>
		 
	<form method=post action=\"$PHP_SELF\">
	
	$msg         
	
	<P>username:<br>
	
	<input type=\"text\" name=\"username\" size=15 maxlength=25></p>
	
	<P>password:<br>
	
	<input type=\"password\" name=\"password\" size=15 maxlength=25></p>
	
	<input type=\"hidden\" name=\"op\" value=\"ds\"><br> 
	
	<P><input type=\"submit\" name=\"submit\" value=\"login\"></p>
	
	</FORM>
	
	";
}else if ($show_menu == "yes") {

	
$display_block = "
	 <p><font size=\"+2\"></font><br></p>
		
		 <p><font size=\"+1\">Welcome to the click through statistics for $site_name.</font><br></p>
		  <p></p>
      <p>Please select one of the following options.</p>
      
      <p><a href=\"index.php\">Statistics</a><br>
      
	  <p><a href=\"help/help.php\">Help</a></p>
	  
	  
	  </p>
	
";	
}

?>






<html>
<head>
<title><? echo "$site_name";?> Login</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#FFFFFF" text="#000000">
<table width="650" border="0" align="center">
  <tr bgcolor="#000099"> 
    <td> 
      <div align="center"><font face="Arial, Helvetica, sans-serif" size="2" color="#FFFFFF"><b><font face="Verdana, Arial, Helvetica, sans-serif"> 
        <? echo "$site_name";?>
        Click Through Login</font></b></font></div>
    </td>
  </tr>
  <tr> 
    <td> 
      <p><font face="Verdana, Arial, Helvetica, sans-serif" size="2"> 
        <? echo $display_block; ?>
        </font></p>
    </td>
  </tr>
</table>
</body>
</html>

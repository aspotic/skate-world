<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	<title>Skate World</title>
<style type="text/css">
body {scrollbar-base-color:003388;
	 scrollbar-track-color:003388;
	 scrollbar-arrow-color:6699cc;
	 scrollbar-shadow-color:ffffff; 
	 scrollbar-dark-shadow-color:6699cc; 
	 scrollbar-3dlight-color:ffffff; 
	 scrollbar-highlight-color:003388;}
a {color:#ffffff; text-decoration:none;}
a:hover {color:#ffffff; text-decoration:none; filter:glow(color=#0575a5,offX=5, offY=5); height:12; }
h3, h2 {color:#ffffff; text-decoration:none; filter:glow(color=#8C1313,offX=5, offY=5); height:12; }
</style>
</head>
<body bgcolor="#003366" text="#ffffff" scroll="auto">

<SCRIPT LANGUAGE="php">
    $email = $HTTP_POST_VARS[email];
    $mailto = "webmaster@skate-world.net";
    $mailsubj = "Dictionary Term";
    $mailhead = "From: $email\n";
    reset ($HTTP_POST_VARS);
    $mailbody = "Information:\n";
    while (list ($key, $val) = each ($HTTP_POST_VARS)) {
        $mailbody .= "$key : $val\n"; }
    mail($mailto, $mailsubj, $mailbody, $mailhead);
</SCRIPT>

Thank you for submitting your term.  If you made a mistake please re-submit the form.
</body>
</html>

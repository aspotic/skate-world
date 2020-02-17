<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
<title>Skate World</title>
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body bgcolor="#0976A4" text="#ffffff" scroll="auto">

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

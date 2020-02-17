<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>Skate World</title>
<style type="text/css">
body {scrollbar-base-color:003388;
	 scrollbar-track-color:003366;
	 scrollbar-arrow-color:6699cc;
	 scrollbar-shadow-color:ffffff; 
	 scrollbar-dark-shadow-color:6699cc; 
	 scrollbar-3dlight-color:ffffff; 
	 scrollbar-highlight-color:003388;}
a {color:#ffffff; text-decoration:none;}
a:hover {color:#ffffff; text-decoration:none; filter:glow(color=#0575a5,offX=5, offY=5); height:12; }
h3, h2 {color:#ffffff; text-decoration:none; filter:glow(color=#8C1313,offX=5, offY=5); height:12; }
</style>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body bgcolor="#003366" text="#ffffff" scroll="auto">
<?php
   if ($_SERVER['REQUEST_METHOD']=="POST"){

   $to="webmaster@skate-world.net";
   $subject="Reviews";

   $from = stripslashes($_POST['fromname'])."<".stripslashes($_POST['fromemail']).">";

   $message = "Review info:
	Senders name = $fromname
	Senders e-mail = $fromemail
	senders item name = $trickname
	Senders reviews = $review
    $name";

   $mime_boundary="==Multipart_Boundary_x".md5(mt_rand())."x";

   $tmp_name = $_FILES['filename']['tmp_name'];
   $type = $_FILES['filename']['type'];
   $name = $_FILES['filename']['name'];
   $size = $_FILES['filename']['size'];

   if (file_exists($tmp_name)){

      if(is_uploaded_file($tmp_name)){

         $file = fopen($tmp_name,'rb');

         $data = fread($file,filesize($tmp_name));

         fclose($file);

         $data = chunk_split(base64_encode($data));
     }

      $headers = "From: $from\r\n" .
         "MIME-Version: 1.0\r\n" .
         "Content-Type: multipart/mixed;\r\n" .
         " boundary=\"{$mime_boundary}\"";

      $message = "This is a multi-part message in MIME format.\n\n" .
         "--{$mime_boundary}\n" .
         "Content-Type: text/plain; charset=\"iso-8859-1\"\n" .
         "Content-Transfer-Encoding: 7bit\n\n" .
         $message . "\n\n";

      $message .= "--{$mime_boundary}\n" .
         "Content-Type: {$type};\n" .
         " name=\"{$name}\"\n" .
         //"Content-Disposition: attachment;\n" .
         //" filename=\"{$fileatt_name}\"\n" .
         "Content-Transfer-Encoding: base64\n\n" .
         $data . "\n\n" .
         "--{$mime_boundary}--\n";

      if (@mail($to, $subject, $message, $headers))
         echo "File Sent";
      else
         echo "Failed to send";
   }
} else {
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" 
   enctype="multipart/form-data" name="form1">
   your name:<br>
   <input type="text" name="fromname" size=33 style=" border-color:#6699cc;background-color:003366;color:#f3fe1e;font-size:8pt; font-family:Arial;border-style:dashed;"><br>
   <br>your e-mail:
   <br><input type="text" name="fromemail" size=33 style=" border-color:#6699cc;background-color:003366;color:#f3fe1e;font-size:8pt; font-family:Arial;border-style:dashed;"><br>
   <br>item name:
   <br><input type="text" name="trickname" size=33 style=" border-color:#6699cc;background-color:003366;color:#f3fe1e;font-size:8pt; font-family:Arial;border-style:dashed;"><br>
   <br>item review:
   <br><textarea name="review" rows=5 cols=35 style=" border-color:#6699cc;background-color:003366;color:#f3fe1e;font-size:8pt; font-family:Arial;border-style:dashed;"></textarea><br>
 
   <br>image file(optional):
   <br><input type="file" name="filename" style=" border-color:#6699cc;background-color:003366;color:#f3fe1e;font-size:8pt; font-family:Arial;border-style:dashed;" ><br>
   <br><input type="submit" name="Submit" value="Submit"style=" border-color:#6699cc; width:50px;height:20px;background-color:003366;color:#ffffff;font-size:8pt; font-family:Arial;">
</form>
<?php } ?>
</body>
</html>
			

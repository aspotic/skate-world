<?php

//Change Password admin
$password = '12345';
#####################################
# SMe Poll                          #
# written by scriptme               #
# http://www.scriptme.com           #
#####################################
    if (!ini_get ("register_globals"))
    {//globals turned Off
    extract($HTTP_GET_VARS);
    extract($HTTP_POST_VARS);
    extract($HTTP_COOKIE_VARS);
    extract($HTTP_ENV_VARS);
    extract($HTTP_SERVER_VARS);
    }


//#########################################################
if(!isset($passwd) or $passwd!=$password){
echo "<table width=\"303\" border=\"0\" cellspacing=\"1\" cellpadding=\"0\" height=\"169\" bgcolor=\"#336699\" align=\"center\"><tr><td bgcolor=\"#336699\" height=\"110\"> 
<table width=\"311\" border=\"0\" cellspacing=\"1\" cellpadding=\"0\" bgcolor=\"#FFFFFF\" height=\"136\">
<tr><td height=\"175\"><div align=\"center\"><font face=\"Verdana, Arial, Helvetica, sans-serif\"><b><font size=\"2\">Admin Login!</font></b></font><br>
</div><form name=\"form1\" method=\"post\" action=\"$PHP_SELF\"><div align=\"center\">
<input type=\"password\" name=\"passwd\"><br><input type=\"submit\" name=\"Submit\" value=\"Login\">
</div></form></td></tr></table></td></tr></table>";

}elseif ($passwd==$password){
if (isset($submit)){
$fp=fopen($datafile, "w");
fputs($fp, $question."\n");
for($i=1; $i <=5000; $i++){
if($answer[$i]==""){ break;}
$input=$answer[$i]."][".$image[$i]."][".$votes[$i]."\n";
fputs($fp, $input);
}
fclose($fp);
$config="<?php\n";
$config.="\$textcolor='$textcolor';\n";
$config.="\$linkcolor='#FFFFFF';\n";
$config.="\$bgcolor='$bgcolor';\n";
$config.="\$tableborder='$tableborder';\n";
$config.="\$timeout='$timeout';\n";
$config.="\$ip_file='$ip_file';\n";
$config.="\$font='$font';\n";
$config.="\$fontsize='$fontsize';\n";
$config.="\$datafile='$datafile';\n";
$config.="\$option='$option';\n";
$config.="\$use_image='$use_image';\n";
$config.="\$image_for_vote='$image_for_vote';\n";
$config.="?>";
$fp=fopen($ip_file, "w");
fclose($fp);
$fp=fopen("config.php", "w");
fputs($fp, $config);
fclose($fp);
echo "<div align=\"center\"><b><font face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#00CC00\">Your 
  settings have been updated!</font></b></div>";
}

include('config.php');
$data=file($datafile);
$nb=count($data);
?>
<html>
<head>
<title>SMe Poll</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<LINK href="k.css" rel=STYLESHEET type=text/css>
<style type="text/css">

#dhtmltooltip{
position: absolute;
width: 150px;
border: 2px solid black;
padding: 2px;
background-color: lightyellow;
visibility: hidden;
z-index: 100;
/*Remove below line to remove shadow. Below line should always appear last within this CSS*/
filter: progid:DXImageTransform.Microsoft.Shadow(color=gray,direction=135);
}
</style>
</head>

<body bgcolor="#FFFFFF" text="#000000" link="#FFFFFF" vlink="#CCCCCC" alink="#CCCCCC">
<div style="top: 861px; visibility: hidden; left: -1000px;" id="dhtmltooltip"></div>
<script type="text/javascript">

var offsetxpoint=-60 //Customize x offset of tooltip
var offsetypoint=20 //Customize y offset of tooltip
var ie=document.all
var ns6=document.getElementById && !document.all
var enabletip=false
if (ie||ns6)
var tipobj=document.all? document.all["dhtmltooltip"] : document.getElementById? document.getElementById("dhtmltooltip") : ""

function ietruebody(){
return (document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body
}

function ddrivetip(thetext, thecolor, thewidth){
if (ns6||ie){
if (typeof thewidth!="undefined") tipobj.style.width=thewidth+"px"
if (typeof thecolor!="undefined" && thecolor!="") tipobj.style.backgroundColor=thecolor
tipobj.innerHTML=thetext
enabletip=true
return false
}
}

function positiontip(e){
if (enabletip){
var curX=(ns6)?e.pageX : event.x+ietruebody().scrollLeft;
var curY=(ns6)?e.pageY : event.y+ietruebody().scrollTop;
//Find out how close the mouse is to the corner of the window
var rightedge=ie&&!window.opera? ietruebody().clientWidth-event.clientX-offsetxpoint : window.innerWidth-e.clientX-offsetxpoint-20
var bottomedge=ie&&!window.opera? ietruebody().clientHeight-event.clientY-offsetypoint : window.innerHeight-e.clientY-offsetypoint-20

var leftedge=(offsetxpoint<0)? offsetxpoint*(-1) : -1000

//if the horizontal distance isn't enough to accomodate the width of the context menu
if (rightedge<tipobj.offsetWidth)
//move the horizontal position of the menu to the left by it's width
tipobj.style.left=ie? ietruebody().scrollLeft+event.clientX-tipobj.offsetWidth+"px" : window.pageXOffset+e.clientX-tipobj.offsetWidth+"px"
else if (curX<leftedge)
tipobj.style.left="5px"
else
//position the horizontal position of the menu where the mouse is positioned
tipobj.style.left=curX+offsetxpoint+"px"

//same concept with the vertical position
if (bottomedge<tipobj.offsetHeight)
tipobj.style.top=ie? ietruebody().scrollTop+event.clientY-tipobj.offsetHeight-offsetypoint+"px" : window.pageYOffset+e.clientY-tipobj.offsetHeight-offsetypoint+"px"
else
tipobj.style.top=curY+offsetypoint+"px"
tipobj.style.visibility="visible"
}
}

function hideddrivetip(){
if (ns6||ie){
enabletip=false
tipobj.style.visibility="hidden"
tipobj.style.left="-1000px"
tipobj.style.backgroundColor=''
tipobj.style.width=''
}
}

document.onmousemove=positiontip

</script>

<IMG SRC="gfx/logo.gif"  BORDER=0 ALT="k poll">

      
        
            <form name="APP" method="post" action="">
              
 <b><i>Settings:</i></b><br>

              <table width="100%" border="0" cellspacing="1" cellpadding="3" align="center">
                <tr> 
                  <td width="116" height="15"><b>Question <A HREF="#"onmouseover="ddrivetip('The question for your poll :)','lightblue', 300)"  onmouseout="hideddrivetip()"><IMG SRC="gfx/21.gif" WIDTH="16" HEIGHT="16" BORDER="0"></A></b></td>
                  <td colspan="3" height="15"> 
                    <input type="text" name="question" size="50" maxlength="150" value="<?php echo $data[0]; ?>">
                  </td>
                </tr>
                <tr> 
                                 
      <td width="116" height="15"><b>Image <A HREF="#"onmouseover="ddrivetip('The image for vote, if your not use click in <B>no image</B> in radio button!<BR> Use a full url eg :http://www.mysite.com/images/myimage.gif','lightblue', 300)"  onmouseout="hideddrivetip()"><IMG SRC="gfx/21.gif" WIDTH="16" HEIGHT="16" BORDER="0"></A></b></td>
                  <td colspan="3" height="15"> 
                    <input type="text" name="image_for_vote" size="50" maxlength="150" value="<?php echo $image_for_vote; ?>">
        <font size="1"><strong>use image <A HREF="#"onmouseover="ddrivetip('Click here if your use a image in your poll','lightblue', 300)"  onmouseout="hideddrivetip()"><IMG SRC="gfx/21.gif" WIDTH="16" HEIGHT="16" BORDER="0"></A></strong></font> 
        <input type="radio" name="use_image" value="1"<?if($use_image=="1"){echo " checked";}?>>
        <font size="1"><strong>no use image <A HREF="#"onmouseover="ddrivetip('Click here if your not use a image in your poll','lightblue', 300)"  onmouseout="hideddrivetip()"><IMG SRC="gfx/21.gif" WIDTH="16" HEIGHT="16" BORDER="0"></A></strong></font> 
        <input type="radio" name="use_image" value="0"<?if($use_image=="0"){echo " checked";}?>></td>

                </tr>
                <tr> 
                  <td colspan="4">Here 
                    you can enter the options the users can choose from when they 
                    are voting, the first <b>2</b> have to be filled out, leave 
                    the ones blank you don't need!</td>
                </tr>
                <tr> 
                  <td width="116">&nbsp;</td>
                  <td width="246"> 
                    <div align="center"><b>Answer <A HREF="#"onmouseover="ddrivetip('Your answers here !','lightblue', 300)"  onmouseout="hideddrivetip()"><IMG SRC="gfx/21.gif" WIDTH="16" HEIGHT="16" BORDER="0"></A></b></div>
                  </td>
                  <td width="181"> 
                    <div align="center"><b>Image File <A HREF="#"onmouseover="ddrivetip('Your images are locates in dir gfx  !','lightblue', 300)"  onmouseout="hideddrivetip()"><IMG SRC="gfx/21.gif" WIDTH="16" HEIGHT="16" BORDER="0"></A></b></div>
                  </td>
				  <td> 
                    <div align="center"><b>Votes <A HREF="#"onmouseover="ddrivetip('The number of votes use a blank for 0!!','lightblue', 300)"  onmouseout="hideddrivetip()"><IMG SRC="gfx/21.gif" WIDTH="16" HEIGHT="16" BORDER="0"></A></b></div>
                  </td>
<?
for($i=1; $i<=$option; $i++){
	$subdata=explode("][",$data[$i]);

echo "<tr><td width=\"116\">
<font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\"><b>Option $i</b></font></td>
<td width=\"246\"> 
<div align=\"center\"><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\"> 
<input type=\"text\" name=\"answer[$i]\" size=\"40\" maxlength=\"40\" value=\"$subdata[0]\"></font></div>
</td><td width=\"181\"> 
<div align=\"center\"><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\" > 
<input type=\"text\" name=\"image[$i]\" size=\"25\" maxlength=\"50\" value=\"$subdata[1]\">
</font></div></td><td width=\"181\"> 
<div align=\"center\"><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\">
<input type=\"text\" name=\"votes[$i]\" size=\"4\" maxlength=\"4\" value=\"$subdata[2]\">
</font></div></td></tr>";
}
?>

               </table>
              <hr width="95%" size="1" align="center" noshade>
              <table width="100%" border="0" cellspacing="1" cellpadding="1">
                <tr bgcolor="#EEEDEA"> 
                  <td width="154" height="22"><b>Colors</b></td>
                  <td width="148" height="22">&nbsp;</td>
                  <td width="22" height="22">&nbsp;</td>
                  <td colspan="2" height="22"><b>Other settings</b></td>
                </tr>
                <tr bgcolor="#EEEDEA"> 
                  <td width="154">Text <A HREF="#"onmouseover="ddrivetip('Color the text in poll!','lightblue', 300)"  onmouseout="hideddrivetip()"><IMG SRC="gfx/21.gif" WIDTH="16" HEIGHT="16" BORDER="0"></A></td>
                  <td width="148"> 
                    <input type="text" name="textcolor" maxlength="10" size="10" value="<?php echo $textcolor; ?>">
                    </font></td>
                  <td width="22">&nbsp;</td>
                  <td width="218">Timeout (hours) <A HREF="#"onmouseover="ddrivetip('Timeout for new vote!','lightblue', 300)"  onmouseout="hideddrivetip()"><IMG SRC="gfx/21.gif" WIDTH="16" HEIGHT="16" BORDER="0"></A></td>
                  <td width="205"> 
                    <input type="text" name="timeout" size="3" maxlength="3" value="<?php echo $timeout; ?>">
                    </td>
                </tr>
                <tr bgcolor="#EEEDEA"> 
                  <td width="154">Background <A HREF="#"onmouseover="ddrivetip('Poll background !','lightblue', 300)"  onmouseout="hideddrivetip()"><IMG SRC="gfx/21.gif" WIDTH="16" HEIGHT="16" BORDER="0"></A></td>
                  <td width="148"> 
                    <input type="text" name="bgcolor" size="10" maxlength="10" value="<?php echo $bgcolor; ?>">
                    </td>
                  <td width="22">&nbsp;</td>
                  <td width="218">File where the data is stored <A HREF="#"onmouseover="ddrivetip('File where the data is stored!<BR> Remember give a <B>chmod 666</B> in this file!','lightblue', 300)"  onmouseout="hideddrivetip()"><IMG SRC="gfx/21.gif" WIDTH="16" HEIGHT="16" BORDER="0"></A></td>
                  <td width="205"> 
                    <input type="text" name="datafile" size="25" maxlength="50" value="<?php echo $datafile; ?>">
                   </td>
                </tr>
                <tr bgcolor="#EEEDEA"> 
                  <td width="154">Border <A HREF="#"onmouseover="ddrivetip('Border color','lightblue', 300)"  onmouseout="hideddrivetip()"><IMG SRC="gfx/21.gif" WIDTH="16" HEIGHT="16" BORDER="0"></A></td>
                  <td width="148"> 
                    <b> 
                    <input type="text" name="tableborder" size="10" maxlength="10" value="<?php echo $tableborder; ?>">
                    </b> </td>
                  <td width="22"></td>
                  <td width="218">File where the IP adresses are stored <A HREF="#"onmouseover="ddrivetip('File where the IP adresses are stored!<BR> Remember give a <B>chmod 666</B> in this file!','lightblue', 300)"  onmouseout="hideddrivetip()"><IMG SRC="gfx/21.gif" WIDTH="16" HEIGHT="16" BORDER="0"></A></td>
                  <td width="205"> <font face="Verdana, Arial, Helvetica, sans-serif" size="2"> 
                    <input type="text" name="ip_file" size="25" maxlength="50" value="<?php echo $ip_file; ?>">
                    </td>
                </tr>
                <tr bgcolor="#EEEDEA"> 
                  <td width="154">Font <A HREF="#"onmouseover="ddrivetip('Font face used eg :arial ','lightblue', 300)"  onmouseout="hideddrivetip()"><IMG SRC="gfx/21.gif" WIDTH="16" HEIGHT="16" BORDER="0"></A></td>
                  <td width="148">  
                    <input type="text" name="font" size="20" maxlength="100" value="<?php echo $font; ?>">
                  </td>
				  <tr bgcolor="#EEEDEA"> 
                   <td width="218">Font-Size <A HREF="#"onmouseover="ddrivetip('Font size for your text in poll! ','lightblue', 300)"  onmouseout="hideddrivetip()"><IMG SRC="gfx/21.gif" WIDTH="16" HEIGHT="16" BORDER="0"></A></td>
                  <td width="205"> 
                    <input type="text" name="fontsize" value="<?php echo $fontsize; ?>" size="1" maxlength="1">
                  </td>
                  <td width="22"><td width="154">Option <A HREF="#"onmouseover="ddrivetip('Number the options for poll! ','lightblue', 300)"  onmouseout="hideddrivetip()"><IMG SRC="gfx/21.gif" WIDTH="16" HEIGHT="16" BORDER="0"></A></td>
                  <td width="148">  
                    <input type="text" name="option" size="20" maxlength="100" value="<?php echo $option; ?>"></td>
                 
                  </td>
                </tr>
              </table>
              <div align="center">
                <input type="hidden" name="passwd" value="<?php echo $password; ?>">
    <input type="submit" name="submit" value="Save your modifications">
              </div>
            </form>
			
<div align="center"><A HREF="http://www.scriptme.com" onmouseover="ddrivetip('If your have a problem or sugestions please send email to support@scriptme.com<BR> Tnks for use a SMe Poll! ','lightblue', 300)"  onmouseout="hideddrivetip()"> SMe Poll www.scriptme.com</A></tr> </table> </td> </tr> </table> 
</div></body>
</html>
<?php } ?>
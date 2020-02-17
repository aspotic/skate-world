<?php
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
function head() {
include('config.php');
echo "<table width=\"120\" border=\"0\" cellpadding=\"1\" cellspacing=\"1\">
<tr><td bgcolor=\"$tableborder\">
<table width=\"120\" border=\"0\" cellpadding=\"0\" cellspacing=\"\" align=\"center\">
<tr><td bgcolor=\"$bgcolor\">";
}
function foot () {
echo "</td></tr></table></td></tr></table>";
}
function record($REMOTE_ADDR) {
include('config.php');
$fp=fopen("$ip_file", "a+");
fputs ($fp,$REMOTE_ADDR."][".time()."\n");
fclose($fp);
}
function check($REMOTE_ADDR) {
include('config.php');
global $valid;
$ip=$REMOTE_ADDR;
$data=file("$ip_file");
$now=time();
foreach ($data as $record) {
$subdata=explode("][",$record);
if ($now < ($subdata[1]+3600*$timeout)) {
if ($ip == $subdata[0]) {
$valid=0;
break;
}}}}
//##################### Save data ##############################

function save($answer){
global $answer;
include('config.php');
$data=file($datafile);
$subdata=explode("][",$data[$answer]);
$subdata[2]+=1;
$data[$answer]=implode("][", $subdata);
$data[$answer]=$data[$answer]."\n";
$fp=fopen($datafile,"w+");
$a=0;
do{
fputs($fp,$data[$a]);
$a++;
}while($a<count($data));
fclose($fp);
}
//########################## Show Form ############################
function form($PHP_SELF){
include('config.php');
head();
echo "<font size=\"$fontsize\" face=\"$font\" color=\"$textcolor\"><form method=\"post\" action=\"$PHP_SELF\"><p align=\"center\">";
$data=file($datafile);
$question=$data[0];
$nb_options=count($data)-1;
if ($use_image==1) echo "<b>$question</b><br><IMG SRC=\"$image_for_vote\"><b></b><br>";
else if ($use_image==0)echo "<b>$question</b><br>";

for($nb=1;$nb <= $nb_options; $nb++){
$option=explode("][","$data[$nb]");
echo "<div align=\"left\"><input type=\"radio\" name=\"answer\" value=\"$nb\"> ";
echo "$option[0]<br></div>";
}
echo "<input type=\"hidden\" name=\"save\" value=\"yes\">";
echo "<p align=\"center\"><input type=\"submit\" name=\"Submit\" value=\"Submit\"></form></font>";
echo "<font size=\"$fontsize\" face=\"$font\" color=\"$textcolor\"><a href=\""; echo $PHP_SELF; echo "?action=results\">Show me the results</font></p>";
foot();
}
//################## Function to show results ########################
function results(){
include('config.php');
head();
$data=file($datafile);
$nb_answers=count($data);
$votes=0;
$a=1;
do{
$subdata=explode("][",$data[$a]);
$votes += $subdata[2];
$a++;
}while($a < $nb_answers);
$a=1;
$b="answerv";
$v=100/$votes;
if ($use_image==1) echo "<div align=\"center\"><b>$data[0] ($votes v.)</b><br><IMG SRC=\"$image_for_vote\"><b></b><br>";
else if ($use_image==0)echo "<p align=\"center\"><font size=\"$fontsize\" face=\"$font\" color=\"$textcolor\"><b>$data[0] ($votes v.)</b></div><br>";

do{
$subdata=explode("][",$data[$a]);
$av = $subdata[2] * $v;
echo "<div align=\"left\"><font size=\"$fontsize\" face=\"$font\" color=\"$textcolor\">$subdata[0] -"; printf(" %01.1f", $av); echo"%<br>";
$p2v = 100-$av;
echo "<img src=\"$subdata[1]\" width=\"$av\" height=\"10\"><img src=\"$image\" width=\"$p2v\" height=\"10\"><br>";
$a++;
} while ($a < $nb_answers);
echo "<br></div>";
foot();
}
//###################### Engine ####################################
check($REMOTE_ADDR);
if ($valid=="0"){ results();}
elseif ($action=="results") { results(); }
elseif ($save=="yes" && $valid!="0") { save($Answer); record($REMOTE_ADDR); results();}
elseif ($action=="save" && !empty($valid)) { results(); }
elseif ($action!="save" && $valid!="0"){ form($PHP_SELF); }
?>
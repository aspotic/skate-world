<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<?
include "polldb.php";
include "pollconfig.php";
include "pollfunctions.php";

echo "<table border='0' align='center' cellpadding='5' cellspacing='0'>";

echo	"<tr><td bgcolor='#336699'><div align='center'><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>
		<strong>Poll v".$version." Admin</strong>
		</font></div></td></tr>";

echo "<tr><td bgcolor='efefef'>";
echo "<form name='polladminform' method='post'>";
echo "<font size='1' face='Verdana, Arial, Helvetica, sans-serif'>"; 

if(isset($deleteno))
{return firstscreen();}

if(isset($deleteyes))
{deletepoll($pollid);}

if(isset($modify) || isset($deleteanswer) || isset($addanswer))
{
	if(isset($modify))
	{$domodify='modify';$newanswer=null;}
	
	if(isset($deleteanswer))
	{$domodify='delete';$newanswer=null;}
	
	if(isset($addanswer))
	{
		if($addanswertext)
		{$newanswer = trim($addanswertext);}
		else
		{$newanswer=null;}
		
		if($newanswer==null)
		{return firstscreen();}
		else
		{$domodify='add';}
		
	}
	modifypoll($pollid,$answerarray,$ansid,$deletebox,$domodify,$newanswer);
}

if(!isset($doit) && !isset($addpoll) && !isset($deleteno) && !isset($deleteyes) && !isset($modify) && !isset($deleteanswer) && !isset($addanswer))
{
	$userquery = mysql_query("SELECT * FROM poll_admin WHERE username='$username' AND password='$password'");
	$adminrow = mysql_num_rows($userquery);
	
	if($adminrow==1)
	{firstscreen();}
	else
	{echo "Wrong username or password";}
}
if(isset($doit) || isset($addpoll))
{

	if(isset($doit))
	{
		if(isset($dopoll))
		{editpoll($dopoll);}
		
		else
		{return firstscreen();}
	}
	if(isset($addpoll))
	{
		addpoll($newquestion, $answer1, $answer2, $answer3, $answer4, $answer5, $answer6, $answer7, $answer8, $answer9, $answer10);
	}
}

function firstscreen()
{
		$pollquery = mysql_query("SELECT * FROM poll");
	
echo "<br></font>";
echo "<table border='0' align='center' cellpadding='2' cellspacing='0'>";
echo "<tr bgcolor='#336699'>";
echo "<td colspan='2'><div align='center'><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>";
echo "<strong>Add Poll</strong>";
echo "</font></div></td></tr>";
echo "<tr bgcolor='cfcfcf'>";
echo "<td><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>";
echo "<strong>Question</strong>:";
echo "</font></td>";
echo "<td><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>";
echo "<input name='newquestion' type='text'>";
echo "</font></td></tr>";

for($counter=1;$counter<11;$counter++)
{
	echo "<tr bgcolor='cfcfcf'>
			<td><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>
			Answer ".$counter.":
			</font></td>
			<td><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>
			<input name='answer".$counter."' type='text'>
			</font></td></tr>";
}

echo "<tr bgcolor='cfcfcf'>";
echo "<td>&nbsp;</td>";
echo "<td><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>";
echo "<input name='addpoll' type='submit' value='Add new poll'>";			// Add new poll button
echo "</font></td></tr></table>";

echo "<font size='1' face='Verdana, Arial, Helvetica, sans-serif'>";
echo "<br><br></font>";
echo "<table border=0 align='center' cellpadding=2 cellspacing=0>";
echo "<tr bgcolor='#336699'>";
echo "<td colspan='4'><div align='center'>";
echo "<font size='2' face='Verdana, Arial, Helvetica, sans-serif'>";
echo "<strong>Delete/Edit/Activate Poll</strong>";							// Edit Title first screen
echo "</font></div></td></tr>";

	while($pollrow = mysql_fetch_array($pollquery))
	{
    	echo "<tr bgcolor='cfcfcf'>";
		echo "<td><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>";
		echo $pollrow['question'];
		echo "</font></td>";
		echo "<td bgcolor='bfbfbf'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>";
		echo "Delete&nbsp;";
		echo "<input name='dopoll' type='radio' value='".$pollrow['pollid']."_delete'>";	// Delete radiobutton
		echo "</font></td>";
		echo "<td bgcolor='cfcfcf'> <font size='1' face='Verdana, Arial, Helvetica, sans-serif'>";
		echo "Edit&nbsp;";
		echo "<input type='radio' name='dopoll' value='".$pollrow['pollid']."_edit'>";		// Edit radiobutton
		echo "</font></td>";
		echo "<td bgcolor='bfbfbf'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>";

	if($pollrow['active']=='yes')
	{
		echo "<center><b>Active!</b></center>";
	}
	else
	{
		echo "Activate&nbsp;";
		echo "<input type='radio' name='dopoll' value='".$pollrow['pollid']."_activate'>";	// Activate Radio button
	}
		echo "</font></td></tr>";
	}
		echo "<tr bgcolor='cfcfcf'>";
		echo "<td colspan='4'><div align='center'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>";
		echo "<br><input name='doit' type='submit' value='Submit'><br>";			// Submit Button
		echo "</font></div></td></tr></table>";
		
		echo "<font size='1' face='Verdana, Arial, Helvetica, sans-serif'>";
} // end firstscreen()

echo "</font></form>";
echo "<font size='1' face='Verdana, Arial, Helvetica, sans-serif'>";

	echo "</font></td></tr></table>";
?>

<hr width="80%" size="1" noshade>
<div align="center"> <font size="1" face="Verdana, Arial, Helvetica, sans-serif">MP 
  Poll v<? echo $version; ?> By Morgan Andersson 2003 is Freeware.<br>
  <a href="http://www.morgande.com" target="_blank">www.morgande.com</a></font></div>
<p>&nbsp;</p>
</body>
</html>

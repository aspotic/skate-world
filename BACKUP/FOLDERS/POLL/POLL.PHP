<?
/***************************************************************************
			MP Poll
			Morgan Andersson @ Morgande Produsctions (www.morgande.com)
			Free to use, a link to www.morgande.com must be present.
***************************************************************************/
include "admin/pollconfig.php";
include "admin/polldb.php";

$pollip = $GLOBALS['REMOTE_ADDR'];

$pollquery = mysql_query("SELECT * FROM poll WHERE active='yes'");
$numrows = mysql_num_rows($pollquery);

if($numrows==0)
{
	$question = "No poll Active";
	$pollid = 0;
}
else
{
while($poll = mysql_fetch_array($pollquery))
{
	$question = $poll['question'];
	$pollid = $poll['pollid'];
}
}
?>

<? 
echo "<form name='pollform' method='post'>";
echo "<table width='200' border='0' cellspacing='0' cellpadding='0'>";

echo "<tr>";
echo "<td bgcolor='$bgcolor'>";
echo "<font color='$fontcolor' size='$fontsize' face='$fontstyle'>$question<br><br>";

if(!isset($poll_submit) && !isset($showresult))
{
	viewanswers($pollid, $question);
}
if(isset($showresult) && !isset($poll_submit))
{
	viewresults($pollid,$question);
}

if(isset($poll_submit))
{	
	if(!isset($theanswer))
	{
		viewanswers($pollid, $question);
	}
	else
	{
		calculatevote($pollid, $theanswer, $pollip);
		viewresults($pollid, $question);
	}
	
}
echo "<div align='center'>";
echo "<br>";

if(!isset($poll_submit) && !isset($showresult))
{
	echo "<input name='poll_submit' type='submit'  value='Vote'>&nbsp;";		// Vote Button
	echo "<input name='showresult' type='submit' value='View Results'>";	// View results Button
}

echo "<br>";
echo "<br><br></font></div></td></tr>";
echo "</table></form>";

/****************************************
*			Functions Started			*
****************************************/

function viewanswers($pollid, $question)
{
	$answerquery = mysql_query("SELECT * FROM poll_answers WHERE pollid=$pollid ORDER BY answerid ASC");
	$num_answers = mysql_num_rows($answerquery);
	$counter = 1;

		while($answerrow = mysql_fetch_array($answerquery))
		{
			$answerid = $answerrow['answerid'];
			$radiob = ("<input type=radio name=theanswer value=$answerid");
			if($counter==1)
			{
				$radiob = $radiob . " checked>";
			}
			else
			{
				$radiob = $radiob. ">";
			}
			echo $radiob;
			echo $answerrow['answers']."<br>";
			$counter++;
		}
}

function calculatevote($pollid, $theanswer, $pollip)
{
	$ipquery = mysql_query("SELECT * FROM poll WHERE lastip='$pollip'");
	$checkrows = mysql_num_rows($ipquery);
	
	if($checkrows==0)
	{
		$voted='no';
	}
	else
	{
		$voted='yes';
	}
	
	if($voted=='no')
	{
		$updateip = mysql_query("UPDATE poll SET lastip='$pollip' WHERE pollid='$pollid'");
		$answerquery = mysql_query("SELECT * FROM poll_answers WHERE answerid='$theanswer'");

		while($answerrow = mysql_fetch_array($answerquery))
		{
			$newvote = $answerrow['votes']+1;
		}
		
		$changesindb = mysql_query("UPDATE poll_answers SET votes='$newvote' WHERE answerid='$theanswer'");
		
		$answerquery = mysql_query("SELECT * FROM poll_answers WHERE pollid=$pollid");
		$num_answers = mysql_num_rows($answerquery);
		$total=0;
	
			while($answerrow = mysql_fetch_array($answerquery))
			{
				$answer[] = $answerrow['answers'];
				$votes[] = $answerrow['votes'];
				$id[] = $answerrow['answerid'];
			}
			for($counter=0;$counter<$num_answers;$counter++)
			{
				$total=$total+$votes[$counter];
			}
			for($counter=0;$counter<$num_answers;$counter++)
			{
				if($votes[$counter]!=0)
				{
					$votecalc[] = 100/$total*$votes[$counter];
					
					if($votecalc[$counter]>=10)
					{$votetotal = substr($votecalc[$counter],0,2);}
					if($votecalc[$counter]==100)
					{$votetotal = substr($votecalc[$counter],0,3);}
					if($votecalc[$counter]<10)
					{$votetotal = substr($votecalc[$counter],0,1);}
					if($votecalc[$counter]==0)
					{$votetotal=0;}
				}
				else
				{
					$votecalc[] = 0;
					$votetotal=0;
				}
				$theid = $id[$counter];
				
				$calc = $votecalc[$counter];
	
				$changesindb = mysql_query("UPDATE poll_answers SET result='$calc' WHERE answerid='$theid'");
				
		}
	}
	else
	{
		echo "<center><font color=990000>Your vote is not counted, Please wait a while before voting again.</font></center><br>";
	}
}

function viewresults($pollid,$question)
{
	$resultquery = mysql_query("SELECT * FROM poll_answers WHERE pollid=$pollid ORDER BY answerid ASC");
				
	while($resultrow = mysql_fetch_array($resultquery))
	{
		echo "<B>".$resultrow['answers']."</B><BR>";
		
		if($resultrow['result']>0)
		{
			echo "<img src='blue.gif' width=".$resultrow['result']." height='10'>";
		}
			echo $resultrow['result']."% (".$resultrow['votes']." votes)<br>";
	}


}
?>
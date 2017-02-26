<?php 
	require_once('db.php');
	
	$query = 'SELECT * FROM `election` LIMIT 1';
	$result = mysql_query($query);
	
	$data['count'][0] = mysql_result($result, 0, '9thGirls');
	$data['count'][1] = mysql_result($result, 0, '9thBoys');
	$data['count'][2] = mysql_result($result, 0, '10thGirls');
	$data['count'][3] = mysql_result($result, 0, '10thBoys');
	$data['count'][4] = mysql_result($result, 0, '11thGirls');
	$data['count'][5] = mysql_result($result, 0, '11thBoys');
	$data['count'][6] = mysql_result($result, 0, '12thGirls');
	$data['count'][7] = mysql_result($result, 0, '12thBoys');

	$query = 'SELECT * FROM `electionVoters` WHERE `Candidate` = 1';
	$result = mysql_query($query);
	$count = mysql_num_rows($result);
	
	for ($i=0;$i<$count;$i++)
	{
		$key = sha1(mysql_result($result, $i, 'ID'));
		$class = mysql_result($result, $i, 'Grade');
		$gender = mysql_result($result, $i, 'Gender');
		$name = mysql_result($result, $i, 'First Name').' '.mysql_result($result, $i, 'Last Name');
		$ID = mysql_result($result, $i, 'ID');
		
		$candidates[$key] = array('class'=>$class,'gender'=>$gender,'name'=>$name,'hash'=>$key,'ID'=>$ID);
	}
	
	$query = 'SELECT * FROM `electionVoters`';
	$result = mysql_query($query);
	$count = mysql_num_rows($result);

	for ($i=0;$i<$count;$i++)
	{
		$key = sha1(mysql_result($result, $i, 'ID'));
		$class = mysql_result($result, $i, 'Grade');
		$gender = mysql_result($result, $i, 'Gender');
		$name = mysql_result($result, $i, 'First Name').' '.mysql_result($result, $i, 'Last Name');
		$voted = mysql_result($result, $i, 'Voted');
		$ID = mysql_result($result, $i, 'ID');
		
		$voters[$key] = array('class'=>$class,'gender'=>$gender,'name'=>$name,'voted'=>$voted,'hash'=>$key,'ID'=>$ID);
	}
	
	$name = strtolower($_POST["name"]);
	$id = $_POST["id"];
	$vote = json_decode(str_replace("\\", "", $_POST["vote"]));
	
	$array = Array();
	for ($i = 0; $i < count($vote); $i++)
	{
	    $x = $candidates[$vote[$i]];
	    if (!$array[((intval($x['class']) - 1) % 4) * 2 + (($x['gender'] == 'M') ? 1 : 0)])
	        $array[((intval($x['class']) - 1) % 4) * 2 + (($x['gender'] == 'M') ? 1 : 0)] = Array();
	    array_push($array[((intval($x['class']) - 1) % 4) * 2 + (($x['gender'] == 'M') ? 1 : 0)], $vote[$i]);
	}
   
    $vote = Array();
	for ($i = 0; $i < 8; $i++)
	{
		if ($array[$i])
		{
			for ($ii = 0; $ii <= $data['count'][$i]; $ii++)
			{
				if ($array[$i][$ii])
				{
					array_push($vote, $array[$i][$ii]);
				}
			}
		}
	}
	
	if ($voters[$id])
	{
		if (strtolower($voters[$id]['name']) == $name)
		{
		    if ($voters[$id]['voted'] != 1)
		    {
				$id = $voters[$id]['ID'];
				
				$query = "UPDATE `electionVoters` SET `Voted` = 1 WHERE `ID` = $id LIMIT 1";
				mysql_query($query);
				
				$votes = count($vote);
				for($i=0;$i<$votes;$i++)
				{
					$id = $candidates[$vote[$i]]['ID'];
					$query = "UPDATE `electionVoters` SET `Votes` = `Votes` + 1 WHERE `ID` = $id LIMIT 1";
					mysql_query($query);
				}
				
				echo 'success';
		    }
			else
				echo 'You have already voted.';
		}
		else
			echo 'Make sure your name and ID are correct.';
	}
	else
		echo 'Your ID number was not recognized.';
?>
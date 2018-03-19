<?php 
	require_once('db.php');
	$query = "SELECT * FROM `election` LIMIT 1";
	$stmt = $votersDB->prepare($query);
	$stmt->execute();
	$result = $stmt->fetch();
	
	$data['count'][0] = $result['9thGirls'];
	$data['count'][1] = $result['9thBoys'];
	$data['count'][2] = $result['10thGirls'];
	$data['count'][3] = $result['10thBoys'];
	$data['count'][4] = $result['11thGirls'];
	$data['count'][5] = $result['11thBoys'];
	$data['count'][6] = $result['12thGirls'];
	$data['count'][7] = $result['12thBoys'];

	$query = "SELECT * FROM `electionVoters` WHERE `Candidate` = 1 ORDER BY `First Name`";
	$stmt = $votersDB->prepare($query);
	$stmt->execute();
	$count = $stmt->rowCount();
	$result = $stmt->fetchAll();
	
	// Create associative array of all candidates with a hash of their ID number as key
	for ($i=0;$i<$count;$i++)
	{
		$key = sha1($result[$i]['ID']);
		$class = $result[$i]['Grade'];
		$gender = $result[$i]['Gender'];
		$name = $result[$i]['First Name'].' '.$result[$i]['Last Name'];
		$ID = $result[$i]['ID'];
		
		$candidates[$key] = array('class'=>$class,'gender'=>$gender,'name'=>$name,'hash'=>$key,'ID'=>$ID);
	}
	
	$query = "SELECT * FROM `electionVoters`";
	$stmt = $votersDB->prepare($query);
	$stmt->execute();
	$count = $stmt->rowCount();
	$result = $stmt->fetchAll();

	// Create an associative array of all voters with a hash of their ID number as key
	for ($i=0;$i<$count;$i++)
	{
		$key = sha1($result[$i]['ID']);
		$class = $result[$i]['Grade'];
		$gender = $result[$i]['Gender'];
		$name = $result[$i]['First Name'].' '.$result[$i]['Last Name'];
		$voted = $result[$i]['Voted'];
		$ID = $result[$i]['ID'];
		
		$voters[$key] = array('class'=>$class,'gender'=>$gender,'name'=>$name,'voted'=>$voted,'hash'=>$key,'ID'=>$ID);
	}
	
	// Votes (Hashed id numbers) stored in an array called $vote
	$pass = $_POST["pass"];
	$id = $_POST["id"];
	$vote = json_decode(str_replace("\\", "", $_POST["vote"]));
	
	// Count for each grade level
	$m9 = 0;
	$f9 = 0;
	$m10 = 0;
	$f10 = 0;
	$m11 = 0;
	$f11 = 0;
	$m12 = 0;
	$f12 = 0;

	if ($voters[$id])
	{	
		$query = "SELECT `Verified`, `Hash` FROM `User` WHERE SHA1(`StudentID`) = :id";
		$query_params = array(':id' => $id);
		$stmt = $db->prepare($query);
		$stmt->execute($query_params);
		$count = $stmt->rowCount();
		$result = $stmt->fetch();

		if ($count > 0 && $result['Verified'] == 1 && password_verify($pass, $result['Hash']) == 1)
		{
		    if ($voters[$id]['voted'] != 1)
		    {
				$id = $voters[$id]['ID'];

				// Check for multiple votes for one student
				// Can only happen if student hacks the Javascript to insert votes
				$duplicates = array();
				foreach($vote as $temp)
				{
					if (++$duplicates[$temp] > 1)
						exit('You cannot vote for someone more than once. No hacking!');
				}
				
				$votes = count($vote);
				
				$totalVotes = 0;
				for ($class = 0; $class < 8; $class++)
					$totalVotes += $data['count'][$class];

				if ($votes <= $totalVotes)
				{
					for($i=0;$i<$votes;$i++)
					{
						$grade = $candidates[$vote[$i]]['class'];
						$gender = $candidates[$vote[$i]]['gender'];
						
						if ($grade == 9 && $gender == 'M')
							$m9++;
						else if ($grade == 9 && $gender == 'F')
							$f9++;
						else if ($grade == 10 && $gender == 'M')
							$m10++;
						else if ($grade == 10 && $gender == 'F')
							$f10++;
						else if ($grade == 11 && $gender == 'M')
							$m11++;
						else if ($grade == 11 && $gender == 'F')
							$f11++;
						else if ($grade == 12 && $gender == 'M')
							$m12++;
						else if ($grade == 12 && $gender == 'F')
							$f12++;
					}
					if ($m9 > $data['count'][1])
					{
						exit('You voted for too many freshman boys.');
					}
					else if ($f9 > $data['count'][0])
					{
						exit('You voted for too many freshman girls.');
					}
					else if ($m10 > $data['count'][3])
					{
						exit('You voted for too many sophomore boys.');
					}
					else if ($f10 > $data['count'][2])
					{
						exit('You voted for too many sophomore girls.');
					}
					else if ($m11 > $data['count'][5])
					{
						exit('You voted for too many junior boys.');
					}
					else if ($f11 > $data['count'][4])
					{
						exit('You voted for too many junior girls.');
					}
					else if ($m12 > $data['count'][7])
					{
						exit('You voted for too many senior boys.');
					}
					else if ($f12 > $data['count'][6])
					{
						exit('You voted for too many senior girls.');
					}
					else
					{
						$query = "UPDATE `electionVoters` SET `Voted` = 1 WHERE `ID` = :id LIMIT 1";
						$query_params = array(':id' => $id);
						$stmt = $votersDB->prepare($query);
						$stmt->execute($query_params);
						
						for($i=0;$i<$votes;$i++)
						{
							$id = $candidates[$vote[$i]]['ID'];
							$query = "UPDATE `electionVoters` SET `Votes` = `Votes` + 1 WHERE `ID` = :id LIMIT 1";
							$query_params = array(':id' => $id);
							$stmt = $votersDB->prepare($query);
							$stmt->execute($query_params);
						}
					}
				}
				else
					exit('You voted for too many people.');
				
				echo 'success';
		    }
			else
				echo 'You have already voted.';
		}
		else
			echo 'Make sure your ID and password are correct.';
	}
	else
		echo 'Your ID number was not recognized.';
?>
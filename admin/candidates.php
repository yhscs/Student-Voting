<?php
	session_start();
	
	if (isset($_SESSION["last"]) && (time() - $_SESSION["last"]) <= 600)
	{
		if (isset($_SESSION["password"]) && $_SESSION["password"] == "HASHED_PASSWORD")
		{
			$_SESSION["last"] = time();

			$candidates = explode(',',$_POST['candidates']);

			require_once('../db.php');
			$votersDB->exec("DELETE FROM electionVoters");
			
			// Add freshman
			if (in_array('9', $candidates))
			{
				$result = $db->query('SELECT * FROM Student WHERE building < 600 AND GRADE = 9');   
				foreach($result as $row)
				{
					$query = $votersDB->prepare('INSERT INTO electionVoters (`ID`, `First Name`, `Last Name`, `Grade`, `Gender`, `Voted`, `Candidate`, `Votes`) VALUES (?, ?, ?, 9, ?, 0, 1, 0)');
					$query->execute(array($row['STUDENT_ID'],$row['FIRST_NAME'],$row['LAST_NAME'],$row['GENDER']));
				}
			}
			
			// Add sophomores
			if (in_array('10', $candidates))
			{
				$result = $db->query('SELECT * FROM Student WHERE building < 600 AND GRADE = 10'); 
				foreach($result as $row)
				{
					$query = $votersDB->prepare('INSERT INTO electionVoters (`ID`, `First Name`, `Last Name`, `Grade`, `Gender`, `Voted`, `Candidate`, `Votes`) VALUES (?, ?, ?, 10, ?, 0, 1, 0)');
					$query->execute(array($row['STUDENT_ID'],$row['FIRST_NAME'],$row['LAST_NAME'],$row['GENDER']));
				}
			}
			
			// Add juniors
			if (in_array('11', $candidates))
			{
				$result = $db->query('SELECT * FROM Student WHERE building < 600 AND GRADE = 11');  
				foreach($result as $row)
				{
					$query = $votersDB->prepare('INSERT INTO electionVoters (`ID`, `First Name`, `Last Name`, `Grade`, `Gender`, `Voted`, `Candidate`, `Votes`) VALUES (?, ?, ?, 11, ?, 0, 1, 0)');
					$query->execute(array($row['STUDENT_ID'],$row['FIRST_NAME'],$row['LAST_NAME'],$row['GENDER']));
				}
			}
			
			// Add seniors
			if (in_array('12', $candidates))
			{
				$result = $db->query('SELECT * FROM Student WHERE building < 600 AND GRADE = 12');  
				foreach($result as $row)
				{
					$query = $votersDB->prepare('INSERT INTO electionVoters (`ID`, `First Name`, `Last Name`, `Grade`, `Gender`, `Voted`, `Candidate`, `Votes`) VALUES (?, ?, ?, 12, ?, 0, 1, 0)');
					$query->execute(array($row['STUDENT_ID'],$row['FIRST_NAME'],$row['LAST_NAME'],$row['GENDER']));
				}
			}
  	
  			exit('All results have been deleted and new candidates have been added.');
		}
		else
			exit('Invalid password, please login.');
	}
	else
		exit('Your session has expired, please login again.');
?>
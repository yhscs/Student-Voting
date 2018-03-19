<?php
	session_start();
	
	if (isset($_SESSION["last"]) && (time() - $_SESSION["last"]) <= 600)
	{
		if (isset($_SESSION["password"]) && $_SESSION["password"] == "HASHED_PASSWORD")
		{
			$_SESSION["last"] = time();

			$title = $_POST['myTitle'];
			$g9 = $_POST['g9'];
			$b9 = $_POST['b9'];
			$g10 = $_POST['g10'];
			$b10 = $_POST['b10'];
			$g11 = $_POST['g11'];
			$b11 = $_POST['b11'];
			$g12 = $_POST['g12'];
			$b12 = $_POST['b12'];
			$voting = $_POST['voting'];

			require_once('../db.php');
			
			$query = "UPDATE `election` SET `Active` = ?, `Title` = ?, `9thGirls` = ?, `9thBoys` = ?, `10thGirls` = ?, `10thBoys` = ?, `11thGirls` = ?, `11thBoys` = ?, `12thGirls` = ?, `12thBoys` = ? LIMIT 1";
			$stmt = $votersDB->prepare($query);
			$query_params = array($voting, $title, $g9, $b9, $g10, $b10, $g11, $b11, $g12, $b12);
			$stmt->execute($query_params);
			exit('Options Saved.');
		}
		else
			exit('Invalid password, please login.');
	}
	else
		exit('Your session has expired, please login again.');
?>
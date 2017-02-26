<?php
	session_start();
	
	if (isset($_SESSION["last"]) && (time() - $_SESSION["last"]) <= 600)
	{
		if (isset($_SESSION["password"]) && $_SESSION["password"] == "SHA1HASH")
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

			require_once('../db.php');
	
			$query = "UPDATE `election` SET `Title` = '$title', `9thGirls` = $g9, `9thBoys` = $b9, `10thGirls` = $g10, `10thBoys` = $b10, `11thGirls` = $g11, `11thBoys` = $b11, `12thGirls` = $g12, `12thBoys` = $b12 WHERE `Title` = `Title` LIMIT 1";
			mysql_query($query);
			echo 'Options Saved.';
		}
		else
			echo 'Invalid password, please login.';
	}
	else
		echo 'Your session has expired, please login.';
?>
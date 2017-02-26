<?php
	session_start();
	
	if (isset($_SESSION["last"]) && (time() - $_SESSION["last"]) <= 600)
	{
		if (isset($_SESSION["password"]) && $_SESSION["password"] == "SHA1HASH")
		{
			$_SESSION["last"] = time();

			file_put_contents("votes.json", "{}");

			echo 'Voting Reset.';
		}
		else
			echo 'Invalid password, please login.';
	}
	else
		echo 'Your session has expired, please login.';
?>
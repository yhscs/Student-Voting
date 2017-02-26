<?php
    session_start();
    
	require_once('../db.php');
	
	$query = "SELECT * FROM `electionVoters` WHERE `Grade` = 9 AND `Gender` = 'F' AND `Votes` > 0 ORDER BY `Votes` DESC";
	$result = mysql_query($query);
	$count = mysql_num_rows($result);
	
	for ($i=0;$i<$count;$i++)
	{
		$freshmanGirls[$i] = mysql_result($result, $i, 'First Name').' '.mysql_result($result, $i, 'Last Name');
		$freshmanGirlsVotes[$i] = mysql_result($result, $i, 'Votes');
	}
	
	$query = "SELECT * FROM `electionVoters` WHERE `Grade` = 9 AND `Gender` = 'M' AND `Votes` > 0 ORDER BY `Votes` DESC";
	$result = mysql_query($query);
	$count = mysql_num_rows($result);
	
	for ($i=0;$i<$count;$i++)
	{
		$freshmanBoys[$i] = mysql_result($result, $i, 'First Name').' '.mysql_result($result, $i, 'Last Name');
		$freshmanBoysVotes[$i] = mysql_result($result, $i, 'Votes');
	}
	
	$query = "SELECT * FROM `electionVoters` WHERE `Grade` = 10 AND `Gender` = 'F' AND `Votes` > 0 ORDER BY `Votes` DESC";
	$result = mysql_query($query);
	$count = mysql_num_rows($result);
	
	for ($i=0;$i<$count;$i++)
	{
		$sophomoreGirls[$i] = mysql_result($result, $i, 'First Name').' '.mysql_result($result, $i, 'Last Name');
		$sophomoreGirlsVotes[$i] = mysql_result($result, $i, 'Votes');
	}
	
	$query = "SELECT * FROM `electionVoters` WHERE `Grade` = 10 AND `Gender` = 'M' AND `Votes` > 0 ORDER BY `Votes` DESC";
	$result = mysql_query($query);
	$count = mysql_num_rows($result);
	
	for ($i=0;$i<$count;$i++)
	{
		$sophomoreBoys[$i] = mysql_result($result, $i, 'First Name').' '.mysql_result($result, $i, 'Last Name');
		$sophomoreBoysVotes[$i] = mysql_result($result, $i, 'Votes');
	}
	
	$query = "SELECT * FROM `electionVoters` WHERE `Grade` = 11 AND `Gender` = 'F' AND `Votes` > 0 ORDER BY `Votes` DESC";
	$result = mysql_query($query);
	$count = mysql_num_rows($result);
	
	for ($i=0;$i<$count;$i++)
	{
		$juniorGirls[$i] = mysql_result($result, $i, 'First Name').' '.mysql_result($result, $i, 'Last Name');
		$juniorGirlsVotes[$i] = mysql_result($result, $i, 'Votes');
	}
	
	$query = "SELECT * FROM `electionVoters` WHERE `Grade` = 11 AND `Gender` = 'M' AND `Votes` > 0 ORDER BY `Votes` DESC";
	$result = mysql_query($query);
	$count = mysql_num_rows($result);
	
	for ($i=0;$i<$count;$i++)
	{
		$juniorBoys[$i] = mysql_result($result, $i, 'First Name').' '.mysql_result($result, $i, 'Last Name');
		$juniorBoysVotes[$i] = mysql_result($result, $i, 'Votes');
	}
	
	$query = "SELECT * FROM `electionVoters` WHERE `Grade` = 12 AND `Gender` = 'F' AND `Votes` > 0 ORDER BY `Votes` DESC";
	$result = mysql_query($query);
	$count = mysql_num_rows($result);
	
	for ($i=0;$i<$count;$i++)
	{
		$seniorGirls[$i] = mysql_result($result, $i, 'First Name').' '.mysql_result($result, $i, 'Last Name');
		$seniorGirlsVotes[$i] = mysql_result($result, $i, 'Votes');
	}
	
	$query = "SELECT * FROM `electionVoters` WHERE `Grade` = 12 AND `Gender` = 'M' AND `Votes` > 0 ORDER BY `Votes` DESC";
	$result = mysql_query($query);
	$count = mysql_num_rows($result);
	
	for ($i=0;$i<$count;$i++)
	{
		$seniorBoys[$i] = mysql_result($result, $i, 'First Name').' '.mysql_result($result, $i, 'Last Name');
		$seniorBoysVotes[$i] = mysql_result($result, $i, 'Votes');
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
        <meta name="author" content="Micah Hahn" />
        <title>Administrative Controls</title>
        <link type="text/css" rel="stylesheet" href="../master.css" />
        <script type="text/javascript" src="../hash.js"></script>
        <script type="text/javascript" src="../../jquery.js"></script>
        <script type="text/javascript">
        	$(function() {
				 $("#logout").click(function() {
                    $.get("login.php", { "password": '' }, function() {
                            location.href = "index.php";
                    });
                });
            });
        </script>
        <style type="text/css">
        a:visited { color: #0000FF; }
        </style>
    </head>
    <body>
		<div id="main">
			<div id="top">
				<h1>Voting Results</h1>
			</div>
			<div id="middle">
				<?php
					if (isset($_SESSION["last"]) && (time() - $_SESSION["last"]) > 600)
					{
						session_destroy();
						session_unset();
						header("location:index.php");
					}
					$_SESSION["last"] = time();
				
					if (isset($_SESSION["password"]) && $_SESSION["password"] == "SHA1HASH")
					{

				?>
				
				<div class="fieldset">
					<span class="header">Freshman Girls</span>
					<div class="content">
						<?php
						$count = count($freshmanGirls);
						for ($i=0;$i<$count;$i++)
							echo "$freshmanGirls[$i] - $freshmanGirlsVotes[$i]</br>";
						?>
					</div>
				</div>
                
				<div class="fieldset">
					<span class="header">Freshman Boys</span>
					<div class="content">
						<?php
						$count = count($freshmanBoys);
						for ($i=0;$i<$count;$i++)
							echo "$freshmanBoys[$i] - $freshmanBoysVotes[$i]</br>";
						?>
					</div>
				</div>
                
				<div class="fieldset">
					<span class="header">Sophomore Girls</span>
					<div class="content">
						<?php
						$count = count($sophomoreGirls);

						for ($i=0;$i<$count;$i++)
							echo "$sophomoreGirls[$i] - $sophomoreGirlsVotes[$i]</br>";
						?>
					</div>
				</div>
                
				<div class="fieldset">
					<span class="header">Sophomore Boys</span>
					<div class="content">
						<?php
						$count = count($sophomoreBoys);
						for ($i=0;$i<$count;$i++)
							echo "$sophomoreBoys[$i] - $sophomoreBoysVotes[$i]</br>";
						?>
					</div>
				</div>
                
				<div class="fieldset">
					<span class="header">Junior Girls</span>
					<div class="content">
						<?php
						$count = count($juniorGirls);
						for ($i=0;$i<$count;$i++)
							echo "$juniorGirls[$i] - $juniorGirlsVotes[$i]</br>";
						?>
					</div>
				</div>
                
				<div class="fieldset">
					<span class="header">Junior Boys</span>
					<div class="content">
						<?php
						$count = count($juniorBoys);
						for ($i=0;$i<$count;$i++)
							echo "$juniorBoys[$i] - $juniorBoysVotes[$i]</br>";
						?>
					</div>
				</div>
                
				<div class="fieldset">
					<span class="header">Senior Girls</span>
					<div class="content">
						<?php
						$count = count($seniorGirls);
						for ($i=0;$i<$count;$i++)
							echo "$seniorGirls[$i] - $seniorGirlsVotes[$i]</br>";
						?>
					</div>
				</div>
                
				<div class="fieldset">
					<span class="header">Senior Boys</span>
					<div class="content">
						<?php
						$count = count($seniorBoys);
						for ($i=0;$i<$count;$i++)
							echo "$seniorBoys[$i] - $seniorBoysVotes[$i]</br>";
						?>
					</div>
				</div>
                
                <div class="fieldset">
					<span class="header">Logout</span>
					<div class="content">
						<div style="margin-top: 5px;"><input type="button" value="Logout" id="logout" /></div>
					</div>
				</div>

				<div>&nbsp;</div>

				<?php
					}
					else
					{
				?>

			    <div class="fieldset" style="width: 40%; margin: 0px auto;">
					<span class="header">Login</span>
					<div class="content">
						<div>Password: <input type="password" id="password" onkeyup="if (event.keyCode == 13){$('#login').click();}" /></div>
						<div style="margin-top: 5px;"><input type="button" value="Login" id="login" /></div>
					</div>
				</div>
				
				<div>&nbsp;</div>

				<?php
					}
				?>

			</div>
			<div id="bottom"></div>
		</div>
    </body>
</html>
<?php
	require_once('db.php');
	$query = "SELECT * FROM `electionVoters` WHERE `Candidate` = 1 ORDER BY `First Name`";
	$stmt = $votersDB->prepare($query);
	$stmt->execute();
	$count = $stmt->rowCount();
	$result = $stmt->fetchAll();

	for ($i=0;$i<$count;$i++)
	{
		$key = sha1($result[$i]['ID']);
		$class = $result[$i]['Grade'];
		$gender = $result[$i]['Gender'];
		$name = $result[$i]['First Name'].' '.$result[$i]['Last Name'];

		$candidates[$key] = array('class'=>$class,'gender'=>$gender,'name'=>$name,'hash'=>$key);
	}
	
	$query = "SELECT * FROM `electionVoters`";
	$stmt = $votersDB->prepare($query);
	$stmt->execute();
	$count = $stmt->rowCount();
	$result = $stmt->fetchAll();

	for ($i=0;$i<$count;$i++)
	{
		$key = sha1($result[$i]['ID']);
		$class = $result[$i]['Grade'];
		$gender = $result[$i]['Gender'];
		$name = $result[$i]['First Name'].' '.$result[$i]['Last Name'];
		$voted = $result[$i]['Voted'];

		$voters[$key] = array('class'=>$class,'gender'=>$gender,'name'=>$name,'voted'=>$voted,'hash'=>$key);
	}
	
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
	
	$pass = $_GET["pass"];
	$id = $_GET["id"];
	if ($voters[$id])
	{
		
		// Make sure password is correct and account has been verified
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
?><span class="header">Vote</span>
	<div class="content" id="votes">
		
		<?php
		    $array = array();
		    $array[0] = array();
		    $array[1] = array();
		    $array[2] = array();
		    $array[3] = array();
		    $array[4] = array();
		    $array[5] = array();
		    $array[6] = array();
		    $array[7] = array();
		    
		    $votes = 5;
		    
		    $names = array("Freshmen Girls", "Freshmen Boys", "Sophomore Girls", "Sophomore Boys", "Junior Girls", "Junior Boys", "Senior Girls", "Senior Boys");
		    foreach ($candidates as $key => $value)
		    {
		        array_push($array[((intval($value["class"]) - 1) % 4) * 2 + (($value["gender"] == "M") ? 1 : 0)], array("hash" => $key, "name" => $value["name"]));
		    }
	
            for ($i = 0; $i < 8; $i++)
            {
                if ($array[$i] && $data['count'][$i] != 0)
                {
                    echo "<div id=\"" . $i . "\"><h2>" . $names[$i] . " (Vote for " . $data["count"][$i] . ")</h2>";
                    
                    echo "<input type=\"hidden\" class=\"count\" value=\"" . $data["count"][$i] . "\" />";
                    
                    echo '<div style="width: 25%; float: left; text-align: left;">';
                   
                        $n = floor(count($array[$i]) / 4);
                        $x = $n;
                        for ($ii = 0; $ii < $x; $ii++)
                        {
             ?>
             <div><input type="checkbox" name="vote" value="<?php echo $array[$i][$ii]["hash"]; ?>" /><?php echo $array[$i][$ii]["name"]; ?></div>
             <?php
                        }
                    echo '</div>';
                    
                    echo '<div style="width: 25%; float: left; text-align: left;">';
                   
                        $x = $n * 2;
                        for ($ii = $n; $ii < $x; $ii++)
                        {
             ?>
             <div><input type="checkbox" name="vote" value="<?php echo $array[$i][$ii]["hash"]; ?>" /><?php echo $array[$i][$ii]["name"]; ?></div>
             <?php
                        }
                    echo '</div>';
                    
                    echo '<div style="width: 25%; float: left; text-align: left;">';
                   
                        $x = $n * 3;
                        for ($ii = $n * 2; $ii < $x; $ii++)
                        {
             ?>
             <div><input type="checkbox" name="vote" value="<?php echo $array[$i][$ii]["hash"]; ?>" /><?php echo $array[$i][$ii]["name"]; ?></div>
             <?php
                        }
                    echo '</div>';
                    
                   echo '<div style="width: 25%; float: left; text-align: left;">';
                   
                        for ($ii = $n * 3; $ii < count($array[$i]); $ii++)
                        {
             ?>
             <div><input type="checkbox" name="vote" value="<?php echo $array[$i][$ii]["hash"]; ?>" /><?php echo $array[$i][$ii]["name"]; ?></div>
             <?php
                        }
                    echo '</div>';
                    
                    echo '<div style="clear:both;"></div></div>';
                }
            }
         ?>
         
		<div><input type="button" value="Vote" onclick="Vote()" /></div>
	</div>
			
<?php
			}
			else
				echo 'You have already voted.';
		}
		else
			echo 'Make sure your ID and password are correct. You may also need to verify your account. Check your email for details.';
	}
	else
		echo 'Your ID number was not recognized.';
?>
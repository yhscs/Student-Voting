<?php
	require_once('db.php');
	$query = 'SELECT * FROM `electionVoters` WHERE `Candidate` = 1 ORDER BY `First Name`';
	$result = mysql_query($query);
	$count = mysql_num_rows($result);
	
	for ($i=0;$i<$count;$i++)
	{
		$key = sha1(mysql_result($result, $i, 'ID'));
		$class = mysql_result($result, $i, 'Grade');
		$gender = mysql_result($result, $i, 'Gender');
		$name = mysql_result($result, $i, 'First Name').' '.mysql_result($result, $i, 'Last Name');
		
		$candidates[$key] = array('class'=>$class,'gender'=>$gender,'name'=>$name,'hash'=>$key);
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
		
		$voters[$key] = array('class'=>$class,'gender'=>$gender,'name'=>$name,'voted'=>$voted,'hash'=>$key);
	}
	
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
	
	$name = strtolower($_GET["name"]);
	$id = $_GET["id"];
	if ($voters[$id])
	{
		if (strtolower($voters[$id]["name"]) == $name)
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
			echo 'Make sure your name and ID are correct.';
	}
	else
		echo 'Your ID number was not recognized.';
?>
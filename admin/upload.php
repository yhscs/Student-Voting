<?php
	session_start();
	
	function __sort($a, $b)
	{
	    return strcmp($a["name"], $b["name"]);
	}
	
	if (isset($_SESSION["last"]) && (time() - $_SESSION["last"]) <= 600)
	{
		if (isset($_SESSION["password"]) && $_SESSION["password"] == "SHA1HASH")
		{
			$_SESSION["last"] = time();

			$file = $_FILES["file"];
			$type = $_POST["type"];
			$json = array();
		
			if ($file["error"] > 0)
			{
				header("location:index.php?text=Error:%20" . $_FILES["file"]["error"] . "%20in%20file%20" . $file["name"] . "&type=" . $type);
				exit;
			}
			else
			{
				if ($file["type"] == "application/vnd.ms-excel")
				{
					$stream = fopen($file["tmp_name"], "r");
					$i = 1;
					
					fgets($stream);
					
					while (!feof($stream))
					{
						$string = fgets($stream);	
						$line = explode(",", (substr($string, strlen($string)-2) == "\r\n") ? substr($string, 0, strlen($string)-2) : $string);
						
						if (count($line) != 5)
						{
							header("location:index.php?text=Error:%20Invalid%20format%20at%20line%20" . $i . "%20in%20file%20" . $file["name"] . "&type=" . $type);
							exit;
						}
						
						$hash = sha1($line[0]);

						if (!$json[$hash])
							$json[$hash] = array("name" => $line[2] . " " . $line[1], "id" => $line[0], "class" => $line[3], "gender" => $line[4]);
						else
						{
							header("location:index.php?text=Error:%20Duplicate%20ID%20at%20line%20" . $i . "%20in%20file%20" . $file["name"] . "&type=" . $type); 
							exit;
						}
						
						$i++;
					}
					
					fclose($stream);
					
					uasort($json, "__sort");
					
					file_put_contents($type . ".json", json_encode($json));
					
					header("location:index.php?text=File%20" . $file["name"] . "%20uploaded%20successfully." . "&type=" . $type);
				}
				else
				{
					header("location:index.php?text=Error:%20Invalid%20file%20format%20in%20file%20" . $file["name"] . "%20,%20file%20must%20be%20a%20comma%20seperated%20value%20file%20(*.csv)" . "&type=" . $type);
				}
			}
		}
		else
		{
			header("location:index.php?text=Invalid%20Password,%20please%20login%20and%20try%20again");
		}
	}
	else
	{
		header("location:index.php?text=Your%20session%20has%20expired,%20please%20login%20and%20try%20again");
	}
?>
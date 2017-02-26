<?php
    session_start();
    
	require_once('../db.php');
	
	$query = 'SELECT * FROM `election` LIMIT 1';
	$result = mysql_query($query);
	
	$data['title'] = mysql_result($result, 0, 'Title');
	$data['count'][0] = mysql_result($result, 0, '9thGirls');
	$data['count'][1] = mysql_result($result, 0, '9thBoys');
	$data['count'][2] = mysql_result($result, 0, '10thGirls');
	$data['count'][3] = mysql_result($result, 0, '10thBoys');
	$data['count'][4] = mysql_result($result, 0, '11thGirls');
	$data['count'][5] = mysql_result($result, 0, '11thBoys');
	$data['count'][6] = mysql_result($result, 0, '12thGirls');
	$data['count'][7] = mysql_result($result, 0, '12thBoys');
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
        		img = new Image();
        		img.src="../images/uploading.gif";
        		$("#password").each(function(){this.focus();});
                $("#login").click(function() {
                    $.get("login.php", { "password": sha1($("#password").val()) }, function() {
                            location.href = "index.php";
                    });
                });
				 $("#logout").click(function() {
                    $.get("login.php", { "password": '' }, function() {
                            location.href = "index.php";
                    });
                });
                $("#voters input[type=submit], #candidates input[type=submit]").click(function()
                {
                	$(this).parent().parent().append("<div><img src='../images/uploading.gif' alt='' title='' /></div>");
                });
                $("#reset").click(function() {
                	if (confirm("Are you sure you want to reset the voting?\nThis will erase all previous voting results."))
	        			$.get("reset.php", {}, function(data) {
	        				alert(data);
	        				if (data != "Voting Reset.")
	        					location.href = "index.php";
	        			});
        		});
        		$("#save").click(function(){
					var myTitle = $("#title").val();
					var g9 = $("#0").val();
					var b9 = $("#1").val();
					var g10 = $("#2").val();
					var b10 = $("#3").val();
					var g11 = $("#4").val();
					var b11 = $("#5").val();
					var g12 = $("#6").val();
					var b12 = $("#7").val();
					$.post("options.php", {myTitle: myTitle, g9: g9, b9: b9, g10: g10, b10: b10, g11: g11, b11: b11, g12: g12, b12: b12}, function(data){
        				alert(data);
        				if (data != "Options Saved.")
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
				<h1>Administrative Controls</h1>
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
				
				<?php
					$type = $_GET["type"];
					if ($type == "voters")
					{
				?>
				<div style="margin: 10px 0px 10px 0px; color: #FF0000; padding: 3px; border-top: 1px dashed #FF0000; border-bottom: 1px dashed #FF0000;" id="alert"><?php echo $_GET["text"]; ?></div>
				<?php
					}
				?>
				<!--
				<div class="fieldset">
					<span class="header">Voting Population</span>
					<div class="content">
						<form action="upload.php" method="post" enctype="multipart/form-data" id="voters">
							<div><input type="hidden" name="type" value="voters" /><input type="file" name="file" /><input type="submit" name="submit" value="Upload" /></div>
						</form>
					</div>
				</div>
			    -->
			    <?php
					if ($type == "candidates")
					{
				?>
				<div style="margin: 10px 0px 10px 0px; color: #FF0000; padding: 3px; border-top: 1px dashed #FF0000; border-bottom: 1px dashed #FF0000;" id="alert"><?php echo $_GET["text"]; ?></div>
				<?php
					}
				?>
<!--
				<div class="fieldset">
               
					<span class="header">Candidates</span>
					<div class="content">
						<form action="upload.php" method="post" enctype="multipart/form-data" id="candidates">
							<div><input type="hidden" name="type" value="candidates" /><input type="file" name="file" /><input type="submit" name="submit" value="Upload" /></div>
						</form>
					</div>
                 
				</div>


				<div class="fieldset">
					
                    <span class="header">Reset Voting</span>
					<div class="content">
						<input type="button" value="Reset" id="reset" />
					</div>
                   
				</div>
				 -->
				<div class="fieldset">
					<span class="header">Options</span>
					<div class="content" style="text-align: left;">
                    <br/><div>Title: <input type="text" size="50" maxlength="255" value="<?php echo $data["title"]; ?>" id="title" /></div>	
						<div>9th Girls Vote for Max: <input type="text" value="<?php echo $data["count"][0]; ?>" id="0" style="width: 30px;"/></div>
						<div>9th Boys Vote for Max: <input type="text" value="<?php echo $data["count"][1]; ?>" id="1" style="width: 30px;" /></div>
						<div>10th Girls Vote for Max: <input type="text" value="<?php echo $data["count"][2]; ?>" id="2" style="width: 30px;" /></div>
						<div>10th Boys Vote for Max: <input type="text" value="<?php echo $data["count"][3]; ?>" id="3" style="width: 30px;" /></div>
						<div>11th Girls Vote for Max: <input type="text" value="<?php echo $data["count"][4]; ?>" id="4" style="width: 30px;"/></div>
						<div>11th Boys Vote for Max: <input type="text" value="<?php echo $data["count"][5]; ?>" id="5" style="width: 30px;"/></div>
						<div>12th Girls Vote for Max: <input type="text" value="<?php echo $data["count"][6]; ?>" id="6" style="width: 30px;"/></div>
						<div>12th Boys Vote for Max: <input type="text" value="<?php echo $data["count"][7]; ?>" id="7" style="width: 30px;"/></div>	
						
						
						<br/>
						<div><input value="Save" id="save" type="button" /></div>		
					</div>
				</div>
				
				<div class="fieldset">
					<span class="header">Results</span>
					<div class="content">
						<a href="results.php">View Results</a>
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


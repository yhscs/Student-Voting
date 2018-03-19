<?php
    session_start();
    
	require_once('../db.php');
	
	$query = "SELECT * FROM `election` LIMIT 1";
	$stmt = $votersDB->prepare($query);
	$stmt->execute();
	$result = $stmt->fetch();

	$data['title'] = $result['Title'];
	$data['count'][0] = $result['9thGirls'];
	$data['count'][1] = $result['9thBoys'];
	$data['count'][2] = $result['10thGirls'];
	$data['count'][3] = $result['10thBoys'];
	$data['count'][4] = $result['11thGirls'];
	$data['count'][5] = $result['11thBoys'];
	$data['count'][6] = $result['12thGirls'];
	$data['count'][7] = $result['12thBoys'];
	$data['active'] = $result['Active'];
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
        		var candidates = false;
				
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
					var b12 = $("#7").val();
					var voting = $('input[name=vote]:checked').val();
					$.post("options.php", {myTitle: myTitle, g9: g9, b9: b9, g10: g10, b10: b10, g11: g11, b11: b11, g12: g12, b12: b12, voting: voting}, function(data){
        				alert(data);
        				if (data != "Options Saved.")
        					location.href = "index.php";
        			});
        		});
				
				$("#selectCandidates").click(function(){
					if (candidates == false)
					{
						candidates = true;
						$('.content p').hide();
						$('.content p').html('<span style="color:red; font-weight:bold">Changing the candidates will DELETE all current results and candidates. Click the Delete Candidates button below to finalize your decision.</span>');
						$('.content p').fadeIn();
						$('#selectCandidates').val('Delete Candidates');
					}
					else
					{
						var classes = '';
						$('input:checked').each(function() {
							classes += $(this).val()+',';
						});
						
						if (classes == '')
							alert('You must select at least one class as candidates to continue.');
						else
						{
							$.post("candidates.php", { "candidates": classes }, function(data) {
        						candidates = false;
								classes = '';
								alert(data);
        					});
        				}	
					}
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
				
					if (isset($_SESSION["password"]) && $_SESSION["password"] == "HASHED_PASSWORD")
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
					if ($type == "candidates")
					{
				?>
				<div style="margin: 10px 0px 10px 0px; color: #FF0000; padding: 3px; border-top: 1px dashed #FF0000; border-bottom: 1px dashed #FF0000;" id="alert"><?php echo $_GET["text"]; ?></div>
				<?php
					}
				?>

				<div class="fieldset">
					<span class="header">Candidates</span>
					<div class="content" style="text-align:left;">
                    	<p>Selecting candidates below will <span style="color: red; font-weight: bold;">erase all current candidates and results</span>. Please make sure you have looked at the current results before submitting your choices below.</p>
						<form action="javascript:void(0);" id="candidates">
							<label><input type="checkbox" id="freshman" value="9">Freshman</label><br>
                            <label><input type="checkbox" id="sophomores" value="10">Sophomores</label><br>
                            <label><input type="checkbox" id="juniors" value="11">Juniors</label><br>
                            <label><input type="checkbox" id="seniors" value="12">Seniors</label><br>
                            <input type="button" id="selectCandidates" value="Select Candidates">
						</form>
					</div>
                 
				</div>
				<div class="fieldset">
					<span class="header">Options</span>
					<div class="content" style="text-align: left;">
                    <br><div>Title: <input type="text" size="50" maxlength="255" value="<?php echo $data["title"]; ?>" id="title" /></div>	
						<div>9th Girls Vote for Max: <input type="text" value="<?php echo $data["count"][0]; ?>" id="0" style="width: 30px;"/></div>
						<div>9th Boys Vote for Max: <input type="text" value="<?php echo $data["count"][1]; ?>" id="1" style="width: 30px;" /></div>
						<div>10th Girls Vote for Max: <input type="text" value="<?php echo $data["count"][2]; ?>" id="2" style="width: 30px;" /></div>
						<div>10th Boys Vote for Max: <input type="text" value="<?php echo $data["count"][3]; ?>" id="3" style="width: 30px;" /></div>
						<div>11th Girls Vote for Max: <input type="text" value="<?php echo $data["count"][4]; ?>" id="4" style="width: 30px;"/></div>
						<div>11th Boys Vote for Max: <input type="text" value="<?php echo $data["count"][5]; ?>" id="5" style="width: 30px;"/></div>
						<div>12th Girls Vote for Max: <input type="text" value="<?php echo $data["count"][6]; ?>" id="6" style="width: 30px;"/></div>
						<div>12th Boys Vote for Max: <input type="text" value="<?php echo $data["count"][7]; ?>" id="7" style="width: 30px;"/></div>	
						
						
						<br>
                        
                        <?php
						if ($data['active'] == 0)
                        	echo '<input type=radio name=vote value=1>Voting On<br><input type=radio name=vote value=0 checked>Voting Off<br>';
						else
							echo '<input type=radio name=vote value=1 checked>Voting On<br><input type=radio name=vote value=0>Voting Off<br>';
						
						?>
                        
						<div><input value="Save" id="save" type="button"></div>		
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
						<div style="margin-top: 5px;"><input type="button" value="Logout" id="logout"></div>
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
						<div id="adminPass">Password: <input type="password" id="password" onkeyup="if (event.keyCode == 13){$('#login').click();}"><br><br />
						<input type="button" value="Login" id="login"></div>
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


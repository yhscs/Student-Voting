<?php 
	require_once('db.php');
	
	$query = "SELECT * FROM `election` LIMIT 1";
	$stmt = $votersDB->prepare($query);
	$stmt->execute();
	$result = $stmt->fetch();
	
	$data['title'] = $result['Title'];
	$active = $result['Active'];
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
        <meta name="author" content="Micah Hahn" />
        <title><?php echo $data["title"] ?></title>
        <link type="text/css" rel="stylesheet" href="master.css" />
        
        <?php
			if ($active==1)
			{
		?>
		<script type="text/javascript" src="hash.js"></script>
        <script type="text/javascript" src="jquery.js"></script> 
		<script type="text/javascript" src="fix.js"></script> 
		<script type="text/javascript">
        	$(function() {
        		$("#name").val("").each(function(){this.focus();});
        		$("#login").each(function() {
        			this.disabled = false;
        		});
        		$("#vote").slideUp(0);
				$("#id").keyup(function(e){
					if (e.keyCode == 13)
						$("#login").click();
				});
        		$("#login").click(function() {
        			this.disabled = true;
        			
        			$.get("validate.php", { "pass": $("#pass").val(), "id": sha1($("#id").val()) }, function(data) {
        				if (data.charAt(0) == "<") {
        					$("#identification").slideUp(2000, function() {
        						$("#vote").append(data).slideDown(2000);
        						
        						$("#votes > div").each(function(){
               							$(this).find("input[type=checkbox]").click(function(){
           								if ($(this).parent().parent().parent().find("input:checked").size() >= parseInt($(this).parent().parent().parent().find("input.count").val()))
        									$(this).parent().parent().parent().find("input[type=checkbox][checked=false]").attr("disabled", true);
        								else
        									$(this).parent().parent().parent().find("input[type=checkbox][checked=false]").attr("disabled", false);
        							});
        						});
        					});
        				}
        				else {
        					alert(data);
        					location.reload();
        				}
        			});
        		});
        	});

        	function Vote()
        	{
				var votes = [];
				$("#votes input:checked").each(function()
				{
					votes.push($(this).val());
				});

				$.post("vote.php", { "pass": $("#pass").val(), "id": sha1($("#id").val()), "vote": JSON.stringify(votes) }, function(data) {
					if (data == "success")
					{
						alert("Your vote has been recorded. Thanks for voting!");
						//location.reload();
					}
					else
						alert(data);
				});
        	};
        	
        	
        </script>
        
        <?php
			}
		?>
    </head>

    <body>
        <div id="main">
            <div id="top">
				<h1><?php echo $data["title"]; ?></h1>
			</div>
            <div id="middle">
                
                <?php
					if ($active==1)
					{
				?>
                <div class="fieldset" id="identification">
                    <span class="header">Identification</span>
                    <div class="content">
						<div style="margin-bottom: 5px;"><div style="float:left; width: 42%; text-align: right;">ID:</div><div style="float:left;">&nbsp;<input type="text" maxlength="11" class="text" id="id" style="width: 150px;" autocomplete="off"></div><div style="clear:both;"></div></div>
                        <div style="margin-bottom: 5px;"><div style="float:left; width: 42%; text-align: right;">Password:</div><div style="float:left;">&nbsp;<input type="password" class="text" id="pass" style="width: 150px;" /></div></div>
                        <div><input type="button" value="Login" id="login"></div>
                    </div>
                </div>
                
                <div class="fieldset" id="vote">
                </div>
                <?php
					}
					else
						echo '<p>Voting is closed.</p>';
				?>
                
                <div id="registerAccount">
                	<h2>You need a YHSCS account to vote.</h2>
                    <a href="http://yhscs.us/users/register.php">Register</a><br>
                    <a href="http://yhscs.us/users/forgotPassword.php">Forgot Password</a>
                </div>
                
            </div>
            <div id="bottom"><span id="dd">Designed and Developed in <a href="http://yhscs.us/web/developers.php">Web Design II</a> by Micah Hahn, Class of 2011</span><br><span id="dd">Security Analysis in <a href="http://yhscs.us/advanced/computerSecurity.php">Computer Security</a> by Sydney Anderson, Class of 2017</span></div>
        </div>
    </body>
</html>


<?php
	//This page lets a user change their password
	$page_title = 'Change your Password';
	include('../includes/header.html');
	
	//Chekc for form submission
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		require('../includes/mysqli_connect.php');//Connect to db
		$errors = array();
		
		//Check for email address
		if(empty($_POST['email'])){
			$errors[] = 'You forgot to enter your email';
		}else{
			$e = $_POST['email'];
		}
		
		//Check for current password
		if(empty($_POST['pass'])){
			$errors[]='You forgot to enter your current password';
		}else{
			$p = $_POST['pass'];
		}
		
		if(!empty($_POST['pass1'])){
			if($_POST['pass1'] == $_POST['pass2']){
				$np = $_POST['pass1'];
			}else{
				$errors[]='Your new password did not match the confirmation';
			}
		}else{
			$errors[]='You forgot to enter your new password';
		}
		
		
		if(empty($errors)){//If everything is OK
			//Check that they've entered the right email/password combination
			$q = "SELECT user_id FROM users WHERE (email='$e' AND pass=SHA1('$p'))";
			$r = @mysqli_query($dbc, $q);
			
			if(@mysqli_num_rows($r) == 1){//Match was made
				//Get the user id
				$row = @mysqli_fetch_array($r, MYSQLI_NUM);
				
				//Make the UPDATE query
				$q = "UPDATE users SET pass=SHA1('$np') WHERE user_id = $row[0]";
				$r = @mysqli_query($dbc, $q);
				
				if(@mysqli_affected_rows($dbc) == 1){//If it ran OK
					echo '<h2 id="answer">Your password has been changed. Thank you!</h2>';
				}else{//if it did not run OK
					echo '<p>Your password was not updated due to a system error. Sorry!</p>';
				}
			}else{//Invalid information
				echo '<p>We have no records for that information. Sorry!</p>';
			}
		
		}else{//Report errors
			echo '<p>The following errors occured:<br />';
			foreach($errors as $message){
				echo " - $message<br />\n";
			}
			echo '</p><p>Please try again</p>';	
		}
		
		mysqli_close($dbc);
	}

?>
<h1>Change Your Password</h1>
<form action="password.php" method="post">
	<p>Email Address: <input type="text" name="email" size="20" maxlength="50"/></p>
	<p>Current Password:<input type="password" name="pass" size="20" maxlength="40"/></p>
	<p>New Password:<input type="password" name="pass1" size="20" maxlength="40" /></p>
	<p>Confirm New Password:<input type="password" name="pass2" size="20" maxlength="40"/></p>
	<p><input type="submit" name="submit" value="Change Password" /></p>
</form>
<?php include('../includes/footer.html')?>
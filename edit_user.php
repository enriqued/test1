<?php
	$page_title = 'Edit User';
	include('../includes/header.html');
	
	if( (isset($_GET['id'])) && (is_numeric($_GET['id'])) ){
		$id = $_GET['id'];
	}elseif ( (isset($_POST['id'])) && (is_numeric($_POST['id'])) ){
		$id = $_POST['id'];
	}else{
		echo '<p>This page was accessed in error</p>';
		include('../includes/footer.html');
		exit();
	}
	
	require_once('../includes/mysqli_connect.php');
	
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		$errors = array();
		
		if(empty($_POST['first_name'])){
			$errors[] = 'You forgot to enter your first name.';
		}else{
			$fn = $_POST['first_name'];
		}
		
		if(empty($_POST['last_name'])){
			$errors[] = 'You forgot to enter your last name';
		}else{
			$ln = $_POST['last_name'];
		}
		
		if(empty($_POST['email'])){
			$errors[] = 'You forgot to enter your email';
		}else{
			$e = $_POST['email'];
		}
		
		if(empty($errors)){//If eveything is OK
			//Test for unique email address
			$q = "SELECT user_id FROM users WHERE email = '$e' AND user_id != $id";
			$r = @mysqli_query($dbc, $q);
			
			if(mysqli_num_rows($r) == 0){
				
				//Make thq query
				$q = "UPDATE users SET first_name = '$fn', last_name='$ln', email='$e' WHERE user_id = $id LIMIT 1";
				$r = @mysqli_query($dbc, $q);
				
				if(@mysqli_affected_rows($dbc) == 1){
					echo '<p id="results_edit">The user has been edited.</p>';
				}else{
					echo '<p>The userws could not be edited due to a system error.</p>';
				}
			}else{//Already registered
				echo '<p>The email address has already been registered.</p>';
			}
						
		}else{//Report errors
			echo '<p>The followeing errors occurred:<br />';
			foreach($errors as $message){
				echo " - $message<br />\n";
			}
			echo '</p><p>Please try again.</p>';
		}
			
	}//End of submit conditional
	
	//Always show the form..
	
	//Retrieve the user information
	$q = "SELECT first_name, last_name, email FROM users WHERE user_id=$id";
	$r = @mysqli_query($dbc, $q);
	
	if(@mysqli_num_rows($r) == 1){
		//get the user's information
		$row = @mysqli_fetch_array($r, MYSQLI_NUM);
		
		//Create the form
		echo '<form action="edit_user.php" method="post" id="form">
				<p>First Name: <input type="text" name="first_name" size="15" maxlength="15" value="' . $row[0] . '" /></p>
				<p>Last Name: <input type="text" name="last_name" size="15" maxlength="30" value="' . $row[1] . '" /></p>
				<p>Email Address: <input type="text" name="email" size="20" maxlength="60" value="' . $row[2] . '" /></p>
				<p><input type="submit" name="submit" value="Submit"/></p>
				<input type="hidden" name="id" value="' . $id . '" />
			  </form>';
	}else{
		echo '<p>This page has been accessed in error.</p>';
	}
	
	mysqli_close($dbc);
	include('../includes/footer.html');
	
?>
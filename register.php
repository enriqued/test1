<?php
	$page_title = 'Register';
	include('../includes/header.html');
	
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		require('../includes/mysqli_connect.php');//Establish connection
		$errors = array();
		
		if(empty($_POST['first_name'])){
			$errors[] = 'You forgot to enter you first name';
		}else{
			$fn = trim($_POST['first_name']);
		}
		
		if(empty($_POST['last_name'])){
			$errors[] = 'You forgot to enter your last name';
		}else{
			$ln = $_POST['last_name'];
		}
		
		if(empty($_POST['email'])){
			$errors[]='You forgot to enter your email';
		}else{
			$e=$_POST['email'];
		}
		
		if(!empty($_POST['pass1'])){
			if($_POST['pass1'] != $_POST['pass2']){
				$errors[] = 'You need to confirm your password';
			}else{
				$p = $_POST['pass1'];
			}
		}else{
			$errors[] = 'You forgot to enter your password';
		}
		
		if(empty($errors)){//If eveything is OK
			$query = "INSERT INTO users(first_name, last_name, email, pass, registration_date) VALUES('$fn', '$ln', '$e', SHA1('$p'), NOW())";
			$result = @mysqli_query($dbc, $query);
			
			if($result){
				echo '<h1>thank you!</h1><p>You are now registered.</p>';
			}else{
				echo '<p>System error! We could not complete the registration process</p>';
			}

		}else{//Report errors
			echo '<h1>Error!</h1><p>The following errors occurred:<br />';
			foreach($errors as $message){
				echo " - $message<br />\n";
			}
			echo '</p><p>Please try again</p><p><br /></p>';
			
		}
		mysqli_close($dbc);
	}
?>
<head>
	<link rel="stylesheet" href="../css/form.css">
</head>
<h1>Register</h1>
<form action="register.php" method="post">
	<p>First Name: <input type="text" name="first_name" size="20" maxlength="30" value="<?php if(isset($_POST['first_name'])) echo $_POST['first_name']?>" /></p>
	<p>Last Name: <input type="text" name="last_name" size="20" maxlength="30" value="<?php if(isset($_POST['last_name'])) echo $_POST['last_name']?>" /></p>
	<p>Email Address: <input type="text" name="email" size="30" maxlength="40" value="<?php if(isset($_POST['email'])) echo $_POST['email']?>" /></p>
	<p>Password: <input type="password" name="pass1" size="15" maxlength="20" value="<?php if(isset($_POST['pass1'])) echo $_POST['pass1']?>" /></p>
	<p>Confirmed Password: <input type="password" name="pass2" size="15" maxlength="20" value="<?php if(isset($_POST['pass2'])) echo $_POST['pass2']?>" /></p>
	<p><input type="submit" name="submit" value="Register" /></p>
</form>
<?php include('../includes/footer.html')?>
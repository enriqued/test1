<?php
	$page_title = 'View users';
	include('../includes/header.html');
	echo '<h1>Registered Users</h1>';
	require('../includes/mysqli_connect.php');
	
	//Number of records to show per page
	$display = 3;
	
	//Determine how many pages there are
	if( (isset($_GET['p'])) && (is_numeric($_GET['p'])) ){//Already been determined
		$pages = $_GET['p'];
	}else{//Need to determine
		//Count the number of records
		$q = "SELECT COUNT(user_id) FROM users";
		$r = @mysqli_query($dbc, $q);
		$row = @mysqli_fetch_array($r, MYSQLI_NUM);
		$records = $row[0];
		
		//Calculate the numbers of pages
		if($records > $display){//More than 1 page
			$pages = ceil($records/$display);
		}else{
			$pages = 1;
		}
	}
	
	//Determine where in the database to start returning results
	if(isset($_GET['s']) && is_numeric($_GET['s'])){
		$start = $_GET['s'];
	}else{
		$start = 0;
	}
	
	//Define the query
	$q = "SELECT last_name, first_name, DATE_FORMAT(registration_date, '%M %d, %Y') AS dr, user_id FROM users ORDER BY registration_date DESC LIMIT $start, $display";
	$r = @mysqli_query($dbc, $q);
	
	//Make the header
	echo '<table align="center" cellspacing="3" cellpadding="3" width="75%">
				<tr>
					<td align="left"><b>Edit</b></td>
					<td align="left"><b>Delete</b></td>
					<td align="left"><b>Last Name<b/></td>
					<td align="left"><b>First Name</b></td>
					<td align="left"><b>Date Registered</b></td>
				</tr>';
	
	//Fetch and print all records
	$bg = '#eeeeee';//Set the initial background color.
	
	while($row = @mysqli_fetch_array($r, MYSQLI_ASSOC)){
		$bg = ($bg=='#eeeeee' ? '#ffffff' : '#eeeeee'); //Switch the background color
		echo '<tr bgcolor = "' . $bg . '">
					<td align="left"><a href="edit_user.php?id=' . $row['user_id'] . '">Edit</a></td>
					<td align="left"><a href="delete_user.php?id=' . $row['user_id'] . '">Delete</a></td>
					<td align="left">' . $row['last_name'] . '</td>
					<td align="left">' . $row['first_name'] . '</td>
					<td align="left">' . $row['dr'] . '</td>
				  </tr>';
	}
	
	echo '</table>';
	mysqli_free_result($r);
	mysqli_close($dbc);
	
	//Makes links to other pages, if necessary
	if($pages > 1){
		//Add some spacing and start a paragrapgh
		echo '<br /><p align = "center">';
		
		//Determine what page the script is
		$current_page = ($start/$display) + 1;

		//If it's not the first page, make a Previos link
		if($current_page != 1){
			echo '<a class ="links" href="view_users.php?s=' . ($start - $display) . '&p=' . $pages. '"> Previous </a> ';
		}
		
		//Make all the numbered pages
		for($i=1; $i <= $pages; $i++){
			if($i != $current_page){
				echo '<a class="links" href="view_users.php?s=' . (($display * ($i - 1))) . '&p=' . $pages . '">' . $i . '  ';
			}else{
				echo $i . '  ';
			}
		}
		
		//If it's not the last page, make a Next button
		if($current_page != $pages){
			echo '<a class="links" href="view_users.php?s=' . ($start + $display) . '&p=' . $pages . '"> Next </a>';
		}
		echo '</p>';
		
	}
	include('../includes/footer.html');
?>

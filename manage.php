<?php
session_start();
require 'database.php';

$_SESSION[ 'current_page' ] = $_SERVER[ 'REQUEST_URI' ];

if (!isset($_COOKIE['user_id'])) {
	 header("Location: welcome.php");
}

?>

<!DOCTYPE html>

<html>

<head>
	<meta charset="utf-8">
	<title>Students Pay</title>

	<link href="styles.css" rel="stylesheet" type="text/css">
	<link rel="icon" href="img/favicon.ico" type="image/x-icon">
</head>

<body>
<?php include_once("analytics.php"); ?>
	<banner>
		<header>
			<img src="img/logo.svg" height="70px" id="banner_image"/>

			<nav>
				<table>
					<tr>
						<td>
							<a href="index.php">Home</a>
						</td>
						<td>
							<a href="payments.php">Payments</a>
						</td>
						<td>
							<a href="overview.php">Overview</a>
						</td>
						<td>
							<a href="manage.php" style="font-weight: bold">Manage</a>
						</td>
					</tr>
				</table>
			</nav>
			<login>
				<?php require 'database.php'; ?>
				<?php if (!isset($_COOKIE['user_id'])) : ?>

				<a href="session.php?a=login">Login</a> | <a href="session.php?a=register">Register</a>

				<?php else:
		
        $user = $DBH->prepare('SELECT first_name FROM users WHERE ID=:id');
        $user->bindParam('id', $_COOKIE['user_id']);
        $user->execute();
        $user = $user->fetch();
        $firstname = $user['first_name'];
		
        ?> Welcome back
				<?php print $firstname; ?>! - <a href="logout.php">Log out</a>

				<?php endif; ?>
			</login>
		</header>
		<block>
			
		</block>
	</banner>
	<?php if (isset($_COOKIE['user_id'])) { 
	$sql = "SELECT house_id FROM users WHERE id= '".$_COOKIE['user_id']."'";
	foreach($con->query($sql) as $row) {
		$house_id = $row['house_id'];
	}
		if ($house_id == 0) {
	?>
	<container>
		<h2>Manage</h2>
		<h4 style="text-align: center">It doesn't look like you're registered in a House yet!</h4>
		<p style="text-align: center">Don't worry, let's fix that! </br> You can set up a new house or join a House created by a housemate, all you need is the House ID and the House password. </br> Just click on one of the buttons below to get started.</p>
		<holder>
			<a class="button" id="create_house">Create a House</a>
			<a class="button" id="join_house">Join a House</a>
		</holder>
	</container>
	<?php } else { ?>
	<container>
		<h2>Manage</h2>
		<p style="text-align: center">Here you can view and manage your account settings. You can leave your current House in order to create or join a new one or edit the current House's settings, as well as managing your personal settings. </p>
	</container>

	<?php 
			$sql = "SELECT people FROM house WHERE id = '" . $house_id . "'";
			foreach ( $con->query( $sql ) as $row ) {
				$size = $row[ 'people' ];
			}
				  
				  $sql2 = "SELECT COUNT(id) FROM users WHERE house_id = '" . $house_id . "'";
			foreach ( $con->query( $sql2 ) as $row2 ) {
				$people = $row2['COUNT(id)'];
			}
				  
				  $names="You";
				  
				    $sql3 = "SELECT first_name, last_name FROM users WHERE house_id = '" . $house_id . "' AND id != '" . $_COOKIE['user_id'] . "'";
			foreach ( $con->query( $sql3 ) as $row3 ) {
				$names = $names . ", " . $row3['first_name'] . " " . $row3['last_name'];
			}
				  
				    $sql4 = "SELECT first_name, last_name, username FROM users WHERE id = '" . $_COOKIE['user_id'] . "'";
			foreach ( $con->query( $sql4 ) as $row4 ) {
				$first_name = $row4['first_name'];
				$last_name = $row4['last_name'];
				$username = $row4['username'];
			}
	?>


	<table id="split_table">
		<tr>
			<td>
				<split_container>
					<h2>Your House</h2>
					</br>
					<table id="table_links">
						<tr>
							<td>
								Your House ID (for adding housemates):
							</td>
							<td>
								<?php print $house_id ?>
							</td>
							<td>
								
							</td>
						</tr>
						
						<tr>
							<td>
								Size of your house:
							</td>

							<td>
								<?php print $size ?>
							</td>
							<td>
								(<a id="change_size">Change</a>)
							</td>
						</tr>
						<tr>
							<td>
								Number of People in your House:
							</td>
							<td>
								<?php print $people ?>
							</td>
							<td>
								
							</td>
						</tr>
						<tr>
							<td>
								Joint Account set up:
							</td>
							<td>
								<?php 	$sql = "SELECT joint_account FROM house WHERE id = '".$house_id."'";
			foreach ( $con->query( $sql ) as $row ) {
						if ($row['joint_account'] == 1) {
							?>
							Yes
							<?php } else {
							?> No <?php
						}} ?>
							</td>
							<td>
								(<a id="change_account">Change</a>)
							</td>
						</tr>
					</table>
					</br>
					<table>
						<tr>
							<td colspan="2" style="text-align: center">
								Names of the People in your House:
							</td>
						</tr>
						<tr>
							<td colspan="2" style="text-align: center">
								<?php print $names ?>
							</td>
						</tr>
					</table>
					</br>
					
								<a class="button" id="leave_house">Leave House</a>
							
							
				</split_container>
			</td>
			<td align="right" style="vertical-align: top">
				<split_container>
					<h2>Your Account</h2>
					</br>
					<table id="table_links">
						<tr>
							<td>
								Your Username:
							</td>
							<td>
								<?php print $username ?>
							</td>
						</tr>
						<tr>
							<td>
								Your First Name:
							</td>
							<td>
								<?php print $first_name ?>
							</td>
							<td>
								(<a id="change_first">Change</a>)
							</td>
						</tr>
						<tr>
							<td>
								Your Last Name:
							</td>
							<td>
								<?php print $last_name ?>
							</td>
							<td>
								(<a id="change_last">Change</a>)
							</td>
						</tr>
						<tr>
							<td>
								Your Password:
							</td>
							<td>
								********
							</td>
							<td>
								(<a id="change_password">Change</a>)
							</td>
						</tr>
					</table>
					
								<a class="button" id="delete_account">Delete Account</a>
</split_container>
			</td>
		</tr>
	</table>
	<?php }} else { ?>
	<container>
		<h2>Please log in or create an account</h2>
	</container>
	<?php } ?>

	<div id="create_modal" class="modal">
		<div class="modal-content">
			<div class="modal-header">
				<span id="close_create" class="close">&times;</span>
				<h2>
					Create a House
				</h2>
			
			</div>
			<div class="modal-body">
				<p>
					Awesome, let's create a House. All we need to do is tell us how many housemates you have and create a password, simple!
					</br>
					Remember that the password you create will need to be something you're happy to share with your housemates.
				</p>
				<form action="create_house.php" method="post">
					<fieldset>
						<legend>Create your House</legend>
						<div class="tooltip">
							<label for="people">Number of People in your House</label>
							<span class="tooltiptext">Tell us how many people will be in your House, including yourself.</span>
						</div>
						<input required type="number" name="people" step="1" max="99" min="0">

						<div class="tooltip">
							<label for="password">Password</label>
							<span class="tooltiptext">Create a password for your House.</span>
						</div>
						<input required type="password" name="password" pattern=".{5,15}" title="Please use between 5 and 15 characters.">
					</fieldset>
			</div>
			<div class="modal-footer">
				<input type="submit" value="Submit" id="modal_submit">
				</form>
			</div>
		</div>
	</div>

	<div id="join_modal" class="modal">
		<div class="modal-content">
			<div class="modal-header">
				<span id="close_join" class="close">&times;</span>
				<h2>
					Join a House
				</h2>
			
			</div>
			<div class="modal-body">
				<p>
					You've already set up a house, great! Just enter the House ID and Password below.
				</p>
				<form action="join_house.php" method="post">
					<fieldset>
						<legend>Enter House Details</legend>
						<div class="tooltip">
							<label for="id">House ID</label>
							<span class="tooltiptext">Enter the House ID.</span>
						</div>
						<input required type="text" name="id">
						<div class="tooltip">
							<label for="password">House Password</label>
							<span class="tooltiptext">Enter the House Password.</span>
						</div>
						<input required type="password" name="password">
					</fieldset>
			</div>
			<div class="modal-footer">
				<input type="submit" value="Submit" id="modal_submit">
				</form>
			</div>
		</div>
	</div>

	<div id="incorrect_modal" class="modal">
		<div class="modal-content">
			<div class="modal-header">
				<span id="close_incorrect" class="close">&times;</span>
				<h2>
					Incorrect House ID or Password
				</h2>
			</div>
			<div class="modal-body">
				<p>
					Oops, that wasn't right.
					</br>
					You entered an invalid House ID and password.
					</br>
					Please try again or Create a House.
				</p>
			</div>
			<div class="modal-footer">
				<a class="modal_button" id="create_house2">Create a House</a>
				<a class="modal_button" id="join_house2">Join a House</a>
			</div>
		</div>
	</div>

	<div id="created_modal" class="modal">
		<div class="modal-content">
			<div class="modal-header">
				<span id="close_created" class="close">&times;</span>
				<h2>
					You've Created a House!
				</h2>
			</div>
			<div class="modal-body">
				<p>
					Nice one! You just created a house!
					</br>
					Your House ID is:
					<?php print $house_id ?>. You will need this to add other housemates to your House.
				</p>
			</div>
			<div class="modal-footer">
				<a class="modal_button" id="created_okay">Okay</a>
			</div>
		</div>
	</div>

	<div id="joined_modal" class="modal">
		<div class="modal-content">
			<div class="modal-header">
				<span id="close_joined" class="close">&times;</span>
				<h2>
					You've Joined a House!
				</h2>
			</div>
			<div class="modal-body">
				<p>
					Nice one! You just joined a house!
					</br>
				</p>
			</div>

			<div class="modal-footer">
				<a class="modal_button" id="joined_okay">Okay</a>
			</div>
		</div>
	</div>
	
	<div id="pwd_changed_modal" class="modal">
		<div class="modal-content">
			<div class="modal-header">
				<span id="close_pwd_changed" class="close">&times;</span>
				<h2>
					Your Password has been Changed!
				</h2>
			</div>
			<div class="modal-body">
				<p>
					All done! The next time you log in, you will be able to use your new password.
				</p>
			</div>
			<div class="modal-footer">
				<a class="modal_button" id="pwd_changed_okay">Okay</a>
			</div>
		</div>
	</div>
	
	<div id="pwd_not_changed_modal" class="modal">
		<div class="modal-content">
			<div class="modal-header">
				<span id="close_pwd_not_changed" class="close">&times;</span>
				<h2>
					Your Password has not been Changed
				</h2>
			</div>
			<div class="modal-body">
				<p>
					Oops...you entered the wrong password. We can't change your password without your old one. Please try again.
				</p>
			</div>
			<div class="modal-footer">
				<a class="modal_button" id="change_password_2">Try Again</a>
				<a class="modal_button" id="pwd_not_changed_okay">Close</a>
			</div>
		</div>
	</div>
	
	<div id="leave_house_modal" class="modal">
		<div class="modal-content">
			<div class="modal-header">
				<span id="close_leave_house" class="close">&times;</span>
				<h2>
					Leave your House
				</h2>
			</div>
			<div class="modal-body">
				<p>
					Are you sure you want to leave your house?
					</br>
				</p>
			</div>

			<div class="modal-footer">
				<a class="modal_button" href="leave_house.php?a=<?php print $_COOKIE['user_id']?>">Yes</a>
				<a class="modal_button" id="leave_house_okay">No</a>
			</div>
		</div>
	</div>
	
	<div id="delete_account_modal" class="modal">
		<div class="modal-content">
			<div class="modal-header">
				<span id="close_delete_account" class="close">&times;</span>
				<h2>
					Delete your Account
				</h2>
			</div>
			<div class="modal-body">
				<p>
					Are you sure you want to delete your account?
					</br>
				</p>
			</div>

			<div class="modal-footer">
				<a class="modal_button" href="delete_account.php?a=<?php print $_COOKIE['user_id']?>">Yes</a>
				<a class="modal_button" id="delete_account_okay">No</a>
			</div>
		</div>
	</div>
	
		<div id="change_size_modal" class="modal">
		<div class="modal-content">
			<div class="modal-header">
				<span id="close_change_size" class="close">&times;</span>
				<h2>
					Change the Size of your House
				</h2>
			
			</div>
			<div class="modal-body">
				<p>
					Here you can change the size of your House.
				</p>
				<form action="change_size.php" method="post">
					<fieldset>
						<legend>Enter the size</legend>
						<div class="tooltip">
							<label for="size">Size</label>
							<span class="tooltiptext">Enter the size of your House.</span>
						</div>
						<input required type="number" name="size" step="1" max="99" min="0">
					</fieldset>
			</div>
			<div class="modal-footer">
				<input type="submit" value="Submit" id="modal_submit">
				</form>
			</div>
		</div>
	</div>
	
	<div id="change_account_modal" class="modal">
		<div class="modal-content">
			<div class="modal-header">
				<span id="close_change_account" class="close">&times;</span>
				<h2>
					Change Joint Account Status  
				</h2>
			
			</div>
			<div class="modal-body">
				<p>
					Please confirm you would like to change whether or not your House has a joint account.
				</p>
				
			</div>
			<div class="modal-footer">
				<a class="modal_button" href="change_joint.php?a=<?php print $house_id?>">Confirm</a>
				<a class="modal_button" id="joint_account_okay">Cancel</a>
			</div>
		</div>
	</div>
	
	<div id="change_first_modal" class="modal">
		<div class="modal-content">
			<div class="modal-header">
				<span id="close_change_first" class="close">&times;</span>
				<h2>
					Change your First Name
				</h2>
			
			</div>
			<div class="modal-body">
				<p>
					Here you can change your first name.
				</p>
				<form action="change_firstname.php" method="post">
					<fieldset>
						<legend>Enter your name</legend>
						<div class="tooltip">
							<label for="first_name">First Name</label>
							<span class="tooltiptext">Enter your First Name.</span>
						</div>
						<input required type="text" name="first_name">
					</fieldset>
			</div>
			<div class="modal-footer">
				<input type="submit" value="Submit" id="modal_submit">
				</form>
			</div>
		</div>
	</div>
	
	<div id="change_last_modal" class="modal">
		<div class="modal-content">
			<div class="modal-header">
				<span id="close_change_last" class="close">&times;</span>
				<h2>
					Change your Last Name
				</h2>
			
			</div>
			<div class="modal-body">
				<p>
					Here you can change your last name.
				</p>
				<form action="change_lastname.php" method="post">
					<fieldset>
						<legend>Enter your name</legend>
						<div class="tooltip">
							<label for="last_name">Last Name</label>
							<span class="tooltiptext">Enter your Last Name.</span>
						</div>
						<input required type="text" name="last_name">
					</fieldset>
			</div>
			<div class="modal-footer">
				<input type="submit" value="Submit" id="modal_submit">
				</form>
			</div>
		</div>
	</div>
	
	<div id="change_password_modal" class="modal">
		<div class="modal-content">
			<div class="modal-header">
				<span id="close_change_password" class="close">&times;</span>
				<h2>
					Change your Password
				</h2>
			
			</div>
			<div class="modal-body">
				<p>
					Here you can change your password.
				</p>
				<form action="change_password.php" method="post">
					<fieldset>
						<legend><span class="number">1</span>Confirm your Current Password</legend>
						<div class="tooltip">
							<label for="current_password">Password</label>
							<span class="tooltiptext">Enter your current Password.</span>
						</div>
						<input type="password" name="current_password"  required>
						
					</fieldset>
					<fieldset>
						<legend><span class="number">2</span>Enter a New Password</legend>
						<div class="tooltip">
							<label for="password">Password</label>
							<span class="tooltiptext">Enter your Password.</span>
						</div>
						<input type="password" name="password" id="password_field" pattern=".{8,}" title="Please use at least 8 characters." required>
						
						<div class="tooltip">
							<label for="confirm_password">Confirm your new Password</label>
							<span class="tooltiptext">Enter your Password again.</span>
						</div>
						<input required type="password" name="confirm_password" id="confirm_password">
					</fieldset>
			</div>
			<div class="modal-footer">
				<input type="submit" value="Submit" id="modal_submit">
				</form>
			</div>
		</div>
	</div>
	
	<footer>
		Callum Pilton © 2017 | <a href="privacy_policy.php">Privacy Policy</a> | <a href="terms_of_service.php">Terms of Service</a> | <a href="contact.php">Contact Us</a>
	</footer>
</body>
<script>
var leave_house_modal = document.getElementById( 'leave_house_modal' );
	var leave_house_btn = document.getElementById( "leave_house" );
	var no_house_btn = document.getElementById( "leave_house_okay" );
	var leave_house_span = document.getElementById( 'close_leave_house' );
	leave_house_btn.onclick = function () {
		leave_house_modal.style.display = "block";
	}
	no_house_btn.onclick = function () {
		leave_house_modal.style.display = "none";
	}
	leave_house_span.onclick = function () {
		leave_house_modal.style.display = "none";
	}
	window.onclick = function ( event ) {
		if ( event.target == leave_house_modal ) {
			leave_house_modal.style.display = "none";
		}
	}
	
	var delete_account_modal = document.getElementById( 'delete_account_modal' );
	var delete_account_btn = document.getElementById( "delete_account" );
	var no_account_btn = document.getElementById( "delete_account_okay" );
	var delete_account_span = document.getElementById( 'close_delete_account' );
	delete_account_btn.onclick = function () {
		delete_account_modal.style.display = "block";
	}
	no_account_btn.onclick = function () {
		delete_account_modal.style.display = "none";
	}
	delete_account_span.onclick = function () {
		delete_account_modal.style.display = "none";
	}
	window.onclick = function ( event ) {
		if ( event.target == delete_account_modal ) {
			delete_account_modal.style.display = "none";
		}
	}
	
	var change_size_modal = document.getElementById( 'change_size_modal' );
	var change_size_btn = document.getElementById( "change_size" );
	var change_size_span = document.getElementById( 'close_change_size' );
	
	change_size_btn.onclick = function () {
		change_size_modal.style.display = "block";
	}
	change_size_span.onclick = function () {
		change_size_modal.style.display = "none";
	}
	window.onclick = function ( event ) {
		if ( event.target == change_size_modal ) {
			change_size_modal.style.display = "none";
		}
	}
	
	var change_account_modal = document.getElementById( 'change_account_modal' );
	var change_account_btn = document.getElementById( "change_account" );
	var change_account_span = document.getElementById( 'close_change_account' );
	var no_account_btn = document.getElementById( "joint_account_okay" );
	change_account_btn.onclick = function () {
		change_account_modal.style.display = "block";
	}
	no_account_btn.onclick = function () {
		change_account_modal.style.display = "none";
	}
	change_account_span.onclick = function () {
		change_account_modal.style.display = "none";
	}
	window.onclick = function ( event ) {
		if ( event.target == change_account_modal ) {
			change_account_modal.style.display = "none";
		}
	}
	
	var change_first_modal = document.getElementById( 'change_first_modal' );
	var change_first_btn = document.getElementById( "change_first" );
	var change_first_span = document.getElementById( 'close_change_first' );
	change_first_btn.onclick = function () {
		change_first_modal.style.display = "block";
	}
	change_first_span.onclick = function () {
		change_first_modal.style.display = "none";
	}
	window.onclick = function ( event ) {
		if ( event.target == change_first_modal ) {
			change_first_modal.style.display = "none";
		}
	}
	
		var change_last_modal = document.getElementById( 'change_last_modal' );
	var change_last_btn = document.getElementById( "change_last" );
	var change_last_span = document.getElementById( 'close_change_last' );
	change_last_btn.onclick = function () {
		change_last_modal.style.display = "block";
	}
	change_last_span.onclick = function () {
		change_last_modal.style.display = "none";
	}
	window.onclick = function ( event ) {
		if ( event.target == change_last_modal ) {
			change_last_modal.style.display = "none";
		}
	}
	
		var change_password_modal = document.getElementById( 'change_password_modal' );
	var change_password_btn = document.getElementById( "change_password" );
	var change_password_span = document.getElementById( 'close_change_password' );
	change_password_btn.onclick = function () {
		change_password_modal.style.display = "block";
	}
	change_password_span.onclick = function () {
		change_password_modal.style.display = "none";
	}
	window.onclick = function ( event ) {
		if ( event.target == change_password_modal ) {
			change_password_modal.style.display = "none";
		}
	}
	
	var change_password_btn = document.getElementById( "change_password_2" );
	var pwd_not_changed_modal = document.getElementById( 'pwd_not_changed_modal' );
	change_password_btn.onclick = function () {
		change_password_modal.style.display = "block";
		pwd_not_changed_modal.style.display = "none";
	}
</script>
</script>
<script>
	var create_house_modal = document.getElementById( 'create_modal' );
	var create_house_btn = document.getElementById( "create_house" );
	var create_house_span = document.getElementById( 'close_create' );
	create_house_btn.onclick = function () {
		create_house_modal.style.display = "block";
	}
	create_house_span.onclick = function () {
		create_house_modal.style.display = "none";
	}
	window.onclick = function ( event ) {
		if ( event.target == create_house_modal ) {
			create_house_modal.style.display = "none";
		}
	}

	var join_house_modal = document.getElementById( 'join_modal' );
	var join_house_btn = document.getElementById( "join_house" );
	var join_house_span = document.getElementById( 'close_join' );
	join_house_btn.onclick = function () {
		join_house_modal.style.display = "block";
	}
	join_house_span.onclick = function () {
		join_house_modal.style.display = "none";
	}
	window.onclick = function ( event ) {
		if ( event.target == join_house_modal ) {
			join_house_modal.style.display = "none";
		}
	}
</script>
<script>
	var create_house_modal = document.getElementById( 'create_modal' );
	var create_house_btn = document.getElementById( "create_house2" );
	var create_house_span = document.getElementById( 'close_create' );
	create_house_btn.onclick = function () {
		create_house_modal.style.display = "block";
		incorrect_modal.style.display = "none";
	}
	create_house_span.onclick = function () {
		create_house_modal.style.display = "none";
	}
	window.onclick = function ( event ) {
		if ( event.target == create_house_modal ) {
			create_house_modal.style.display = "none";
		}
	}

	var join_house_modal = document.getElementById( 'join_modal' );
	var join_house_btn = document.getElementById( "join_house2" );
	var join_house_span = document.getElementById( 'close_join' );
	join_house_btn.onclick = function () {
		join_house_modal.style.display = "block";
		incorrect_modal.style.display = "none";
	}
	join_house_span.onclick = function () {
		join_house_modal.style.display = "none";
	}
	window.onclick = function ( event ) {
		if ( event.target == join_house_modal ) {
			join_house_modal.style.display = "none";
		}
	}
</script>
<script>
	<?php if (isset($_SESSION['redirect']) && $_SESSION['redirect'] == 'incorrect') { 
		unset($_SESSION['redirect']);
		?>

	// Modal Script
	var incorrect_modal = document.getElementById( 'incorrect_modal' );
	var incorrect_span = document.getElementById( 'close_incorrect' );

	incorrect_modal.style.display = "block";

	incorrect_span.onclick = function () {
		incorrect_modal.style.display = "none";
	}
	window.onclick = function ( event ) {
		if ( event.target == incorrect_modal ) {
			incorrect_modal.style.display = "none";
		}
	}
	<?php } else if (isset($_SESSION['redirect']) && $_SESSION['redirect'] == 'joined') { 
		unset($_SESSION['redirect']);
		?>

	var joined_house_modal = document.getElementById( 'joined_modal' );
	var joined_house_btn = document.getElementById( "joined_okay" );
	var joined_house_span = document.getElementById( 'close_joined' );

	joined_house_modal.style.display = "block";

	joined_house_btn.onclick = function () {
		joined_house_modal.style.display = "none";
	}
	joined_house_span.onclick = function () {
		joined_house_modal.style.display = "none";
	}
	window.onclick = function ( event ) {
		if ( event.target == joined_house_modal ) {
			joined_house_modal.style.display = "none";
		}
	}

	<?php } else if (isset($_SESSION['redirect']) && $_SESSION['redirect'] == 'created') { 
		unset($_SESSION['redirect']);
		?>

	var created_house_modal = document.getElementById( 'created_modal' );
	var created_house_btn = document.getElementById( "created_okay" );
	var created_house_span = document.getElementById( 'close_created' );

	created_house_modal.style.display = "block";

	created_house_btn.onclick = function () {
		created_house_modal.style.display = "none";
	}
	created_house_span.onclick = function () {
		created_house_modal.style.display = "none";
	}
	window.onclick = function ( event ) {
		if ( event.target == created_house_modal ) {
			created_house_modal.style.display = "none";
		}
	}
	
	<?php } else if (isset($_SESSION['redirect']) && $_SESSION['redirect'] == 'pwdchanged') { 
		unset($_SESSION['redirect']);
		?>

	var pwd_changed_modal = document.getElementById( 'pwd_changed_modal' );
	var pwd_changed_btn = document.getElementById( "pwd_changed_okay" );
	var pwd_changed_span = document.getElementById( 'close_pwd_changed' );

	pwd_changed_modal.style.display = "block";

	pwd_changed_btn.onclick = function () {
		pwd_changed_modal.style.display = "none";
	}
	pwd_changed_span.onclick = function () {
		pwd_changed_modal.style.display = "none";
	}
	window.onclick = function ( event ) {
		if ( event.target == pwd_changed_modal ) {
			pwd_changed_modal.style.display = "none";
		}
	}
	
	<?php } else if (isset($_SESSION['redirect']) && $_SESSION['redirect'] == 'pwdnotchanged') { 
		unset($_SESSION['redirect']);
		?>

	var pwd_not_changed_modal = document.getElementById( 'pwd_not_changed_modal' );
	var pwd_not_changed_btn = document.getElementById( "pwd_not_changed_okay" );
	var pwd_not_changed_span = document.getElementById( 'close_pwd_not_changed' );

	pwd_not_changed_modal.style.display = "block";

	pwd_not_changed_btn.onclick = function () {
		pwd_not_changed_modal.style.display = "none";
	}
	pwd_not_changed_span.onclick = function () {
		pwd_not_changed_modal.style.display = "none";
	}
	window.onclick = function ( event ) {
		if ( event.target == pwd_not_changed_modal ) {
			pwd_not_changed_modal.style.display = "none";
		}
	}

	<?php } ?>
</script>


<script>
	var password = document.getElementById("password_field")
  , confirm_password = document.getElementById("confirm_password");

function validatePassword(){
  if(password.value != confirm_password.value) {
    confirm_password.setCustomValidity("Passwords Don't Match");
  } else {
    confirm_password.setCustomValidity('');
  }
}

password.onchange = validatePassword;
confirm_password.onkeyup = validatePassword;
</script>

</html>
<!-- Callum Pilton -->
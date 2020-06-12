<?php
session_start();
require 'database.php';


$_SESSION[ 'current_page' ] = $_SERVER[ 'REQUEST_URI' ];
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
							<a href="manage.php">Manage</a>
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
	
	<container>
		<h2>Contact Us</h2>

		<?php
		if ( isset( $_COOKIE[ 'user_id' ] ) ) {
			$sql = "SELECT first_name, last_name, username FROM users WHERE id = '" . $_COOKIE[ 'user_id' ] . "'";

			foreach ( $con->query( $sql ) as $row ) {
				$first = $row[ 'first_name' ];
				$last = $row[ 'last_name' ];
				$user = $row[ 'username' ];
			}
		}
		?>

		<form action="submit_message.php" method="post">
			<table>
				<tr>
					<td>
						<fieldset>
							<legend><span class="number">1</span>Personal Information</legend>

							<label for="first_name">First Name</label>
							<input type="text" name="first_name" id="first_name" value="<?php if (isset($_COOKIE['user_id'])) { print $first ; } ?>" required>

							<label for="last_name">Last Name</label>
							<input type="text" name="last_name" id="last_name" value="<?php if (isset($_COOKIE['user_id'])) { print $last ; } ?>" required>


						</fieldset>
					</td>
					<td>
						<fieldset>
							<legend><span class="number">2</span>Account Details</legend>

							<label for="username">Username<span id="user-result" ></label>

							<input type="text" name="username" id="username" value="<?php if (isset($_COOKIE['user_id'])) { print $user ; } ?>" required>

							<label for="email">Email Address</label>
							<input type="email" name="email" required>
							</select>

						</fieldset>
					</td>
				</tr>
				<tr>
					<td colspan="3">
						<fieldset>
							<legend><span class="number">3</span>Your Message</legend>
							
							<textarea type="text" name="message" required style="width:590px;height:150px;max-height: 300px;max-width: 590px;"></textarea>
							
						</fieldset>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<input type="submit" value="Send Message" id="submit">
					</td>
				</tr>
			</table>
		</form>
	</container>

<div id="sent_modal" class="modal">
		<div class="modal-content">
			<div class="modal-header">
				<span id="close_sent" class="close">&times;</span>
				<h2>
					Your message has been sent!
				</h2>
			</div>
			<div class="modal-body">
				<p>
					We've received your message and we'll get back to you as soon as we can.
					</br>
				</p>
			</div>

			<div class="modal-footer">
				<a class="modal_button" id="sent_okay">Okay</a>
			</div>
		</div>
	</div>
	
	<footer>
		Callum Pilton © 2017 | <a href="privacy_policy.php">Privacy Policy</a> | <a href="terms_of_service.php">Terms of Service</a> | <a style="font-weight: bold" href="contact.php">Contact Us</a>
	</footer>
</body>
<script>
	
<?php if (isset($_SESSION['redirect']) && $_SESSION['redirect'] == 'sent') { 
		unset($_SESSION['redirect']);
		?>

	var sent_modal = document.getElementById( 'sent_modal' );
	var sent_btn = document.getElementById( "sent_okay" );
	var sent_span = document.getElementById( 'close_sent' );

	sent_modal.style.display = "block";

	sent_btn.onclick = function () {
		sent_modal.style.display = "none";
	}
	sent_span.onclick = function () {
		sent_modal.style.display = "none";
	}
	window.onclick = function ( event ) {
		if ( event.target == sent_modal ) {
			sent_modal.style.display = "none";
		}
	}

	<?php } ?>
</script>
</html>
<!-- Callum Pilton -->
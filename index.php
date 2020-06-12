<?php
session_start();
require 'database.php';


$_SESSION[ 'current_page' ] = $_SERVER[ 'REQUEST_URI' ];

if ( !isset( $_COOKIE[ 'user_id' ] ) ) {
	header( "Location: welcome.php" );
}

?>

<!DOCTYPE html>

<html>

<head>
	<meta charset="utf-8">
	<title>Students Pay</title>

	<link href="styles.css" rel="stylesheet" type="text/css">
	<link rel="icon" href="img/favicon.ico" type="image/x-icon">

	<script src="js/jquery-3.2.1.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/jquery.animated-progressbar.js"></script>

	<meta name="description" content="Students Pay is a free online tool for helping you pay your bills in a shared house. You can create a 'House' which allows you to add and view payments, allowing you to see what needs paying and keep track of your spending. Students pay will help you to fairly split the cost of purchases and bills related to your House. Payments inclued rent, internet, electric, water, gas and insurance.">
	<meta name="keywords" content="students, pay, rent, bills, bill, student, free, easy, help, payments, split">
	<meta name="author" content="Callum Pilton">
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
							<a href="index.php" style="font-weight: bold">Home</a>
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
	<?php if (isset($_COOKIE['user_id'])) { 
	$sql = "SELECT house_id FROM users WHERE id= '".$_COOKIE['user_id']."'";
	foreach($con->query($sql) as $row) {
		$house_id = $row['house_id'];
	}
		if ($house_id == 0) {
	?>
	<container>
		<h2>Home</h2>
		<h4 style="text-align: center">It doesn't look like you're registered in a House yet!</h4>
		<p style="text-align: center">Don't worry, let's fix that! </br> You can set up a new house or join a House created by a housemate, all you need is the House ID and the House password. </br> Just click on one of the buttons below to get started.</p>
		<holder>
			<a class="button" id="create_house">Create a House</a>
			<a class="button" id="join_house">Join a House</a>
		</holder>
	</container>
	<?php } else { ?>
	<container>
		<h2>Home</h2>
		<p style="text-align: center">Here you can keep track of the payments you've set up, and when they're due for payment. Rent, Insurance, Internet, Electric, Water and Gas are considered essentials and will always be displayed here.</p>

		<?php
		$curMonth = date( 'n' );
		$curYear = date( 'Y' );
		if ( $curMonth == 12 ) {
			$firstDayNextMonth = mktime( 0, 0, 0, 0, 0, $curYear + 1 );
		} else {
			$firstDayNextMonth = mktime( 0, 0, 0, $curMonth + 1, 1 );
		}
		$next_month = strtotime( ' + 1 month' );
		$first_month = 0;
		$second_month = 0;

		?>
		<h5>Rent</h5>
		<?php

		$sql = "SELECT id, user_id, name, type, amount, added, date, payees FROM payments WHERE house_id = '" . $house_id . "' AND (payees = 'everyone' OR (payees = 'me' AND user_id = '" . $_COOKIE[ 'user_id' ] . "')) AND type = 'rent' ORDER BY date";

		$done = 0;
		foreach ( $con->query( $sql ) as $row ) {

			$sql2 = "SELECT status FROM pay_status WHERE payment_id = '" . $row[ 'id' ] . "' AND user_id = '" . $_COOKIE[ 'user_id' ] . "'";
			foreach ( $con->query( $sql2 ) as $row2 ) {
				$paid = $row2[ 'status' ];
			}

			if ( ( strtotime( $row[ 'date' ] ) < time() && $paid == 'unpaid' ) || ( strtotime( $row[ 'date' ] ) < $next_month && strtotime( $row[ 'date' ] ) > time() ) && $done == 0 ) {
				$done = 1;

				$colour = '#4caf50'; //green

				$start = strtotime( $row[ 'added' ] );

				$diff = floor( strtotime( $row[ 'date' ] ) - time() );

				if ($diff > 86400) {
				if (round($diff / 86400) == 1) {
					$time = round($diff / 86400) . " day" ;
				} else {
				$time = round($diff / 86400) . " days" ;
				}
			}
			else if ($diff > 3600) {
				if (round($diff / 3600) == 1) {
				$time = round($diff / 3600) . " hour" ;
				} else {
					$time = round($diff / 3600) . " hours" ;
				}
			}
			else if ($diff > 60) {
				if (round($diff / 60) == 1) {
				$time = round($diff / 60) . " minute" ;
				} else {
					$time = round($diff / 60) . " minutes" ;
				}
			}
				else if ($diff > 0) {
				if (round($diff) == 1) {
				$time = round($diff) . " second" ;
				} else {
					$time = round($diff) . " seconds" ;
				}
			}
			else {
				$time = 'overdue';
			}

				$percent = ( time() - $start ) / ( floor( strtotime( $row[ 'date' ] ) ) - $start );

				?>

		<table id="payment_bar">
			<tr style="background-color: <?php print $colour ?>; ">
				<td colspan="1" style="padding:4px;" width="25%">
					<?php print $row['name'] ?>
				</td>
				<td colspan="3" width="75%">
					<table>
						<tr>
							<td style="box-shadow: none">
								Time left to pay:
								<?php print $time ?>
							</td>
							<td style="box-shadow: none">
								<div class="progress">
									<div class="bar" data-progressbar="on" data-progressbar-begin="<?php print $percent ?>" data-progressbar-end="1" data-progressbar-delay="100" data-progressbar-duration="<?php print ($diff * 1000) ?>"></div>
								</div>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<?php }} 
		
		if ($done == 0) { ?>
		
		<table id="payment_bar" style="background-color: #4caf50">
			<tr>
				<td>
					<?php print "You have not set rent up!"; ?>
				</td>
			</tr>
		</table>
		
		
		<?php } ?>

		<h5>Insurance</h5>
		<?php

		$sql = "SELECT id, user_id, name, type, amount, added, date, payees FROM payments WHERE house_id = '" . $house_id . "' AND (payees = 'everyone' OR (payees = 'me' AND user_id = '" . $_COOKIE[ 'user_id' ] . "')) AND type = 'insurance' ORDER BY date";

		$done = 0;
		foreach ( $con->query( $sql ) as $row ) {

			$sql2 = "SELECT status FROM pay_status WHERE payment_id = '" . $row[ 'id' ] . "' AND user_id = '" . $_COOKIE[ 'user_id' ] . "'";
			foreach ( $con->query( $sql2 ) as $row2 ) {
				$paid = $row2[ 'status' ];
			}

			if ( ( strtotime( $row[ 'date' ] ) < time() && $paid == 'unpaid' ) || ( strtotime( $row[ 'date' ] ) < $next_month && strtotime( $row[ 'date' ] ) > time() ) && $done == 0 ) {
				$done = 1;
				$colour = '#9c27b0';

				$start = strtotime( $row[ 'added' ] );

				$diff = floor( strtotime( $row[ 'date' ] ) - time() );

				if ($diff > 86400) {
				if (round($diff / 86400) == 1) {
					$time = round($diff / 86400) . " day" ;
				} else {
				$time = round($diff / 86400) . " days" ;
				}
			}
			else if ($diff > 3600) {
				if (round($diff / 3600) == 1) {
				$time = round($diff / 3600) . " hour" ;
				} else {
					$time = round($diff / 3600) . " hours" ;
				}
			}
			else if ($diff > 60) {
				if (round($diff / 60) == 1) {
				$time = round($diff / 60) . " minute" ;
				} else {
					$time = round($diff / 60) . " minutes" ;
				}
			}
				else if ($diff > 0) {
				if (round($diff) == 1) {
				$time = round($diff) . " second" ;
				} else {
					$time = round($diff) . " seconds" ;
				}
			}
			else {
				$time = 'overdue';
			}

				$percent = ( time() - $start ) / ( floor( strtotime( $row[ 'date' ] ) ) - $start );

				?>

		<table id="payment_bar">
			<tr style="background-color: <?php print $colour ?>; ">
				<td colspan="1" style="padding:4px;" width="25%">
					<?php print $row['name'] ?>
				</td>
				<td colspan="3" width="75%">
					<table>
						<tr>
							<td style="box-shadow: none">
								Time left to pay:
								<?php print $time ?>
							</td>
							<td style="box-shadow: none">
								<div class="progress">
									<div class="bar" data-progressbar="on" data-progressbar-begin="<?php print $percent ?>" data-progressbar-end="1" data-progressbar-delay="100" data-progressbar-duration="<?php print ($diff * 1000) ?>"></div>
								</div>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<?php }} 
			
			if ($done == 0) { ?>
		
		<table id="payment_bar" style="background-color: #9c27b0">
			<tr>
				<td>
					<?php print "You have not set insurance up!"; ?>
				</td>
			</tr>
		</table>
		
		
		<?php } ?>

		<h5>Internet</h5>
		<?php

		$sql = "SELECT id, user_id, name, type, amount, added, date, payees FROM payments WHERE house_id = '" . $house_id . "' AND (payees = 'everyone' OR (payees = 'me' AND user_id = '" . $_COOKIE[ 'user_id' ] . "')) AND type = 'internet' ORDER BY date";

		$done = 0;
		foreach ( $con->query( $sql ) as $row ) {

			$sql2 = "SELECT status FROM pay_status WHERE payment_id = '" . $row[ 'id' ] . "' AND user_id = '" . $_COOKIE[ 'user_id' ] . "'";
			foreach ( $con->query( $sql2 ) as $row2 ) {
				$paid = $row2[ 'status' ];
			}
			if ( ( strtotime( $row[ 'date' ] ) < time() && $paid == 'unpaid' ) || ( strtotime( $row[ 'date' ] ) < $next_month && strtotime( $row[ 'date' ] ) > time() ) && $done == 0 ) {
				$done = 1;

				$colour = '#f44336';

				$start = strtotime( $row[ 'added' ] );

				$diff = floor( strtotime( $row[ 'date' ] ) - time() );

				if ($diff > 86400) {
				if (round($diff / 86400) == 1) {
					$time = round($diff / 86400) . " day" ;
				} else {
				$time = round($diff / 86400) . " days" ;
				}
			}
			else if ($diff > 3600) {
				if (round($diff / 3600) == 1) {
				$time = round($diff / 3600) . " hour" ;
				} else {
					$time = round($diff / 3600) . " hours" ;
				}
			}
			else if ($diff > 60) {
				if (round($diff / 60) == 1) {
				$time = round($diff / 60) . " minute" ;
				} else {
					$time = round($diff / 60) . " minutes" ;
				}
			}
				else if ($diff > 0) {
				if (round($diff) == 1) {
				$time = round($diff) . " second" ;
				} else {
					$time = round($diff) . " seconds" ;
				}
			}
			else {
				$time = 'overdue';
			}

				$percent = ( time() - $start ) / ( floor( strtotime( $row[ 'date' ] ) ) - $start );

				?>

		<table id="payment_bar">
			<tr style="background-color: <?php print $colour ?>; ">
				<td colspan="1" style="padding:4px;" width="25%">
					<?php print $row['name'] ?>
				</td>
				<td colspan="3" width="75%">
					<table>
						<tr>
							<td style="box-shadow: none">
								Time left to pay:
								<?php print $time ?>
							</td>
							<td style="box-shadow: none">
								<div class="progress">
									<div class="bar" data-progressbar="on" data-progressbar-begin="<?php print $percent ?>" data-progressbar-end="1" data-progressbar-delay="100" data-progressbar-duration="<?php print ($diff * 1000) ?>"></div>
								</div>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<?php }} 
			
			if ($done == 0) { ?>
		
			<table id="payment_bar" style="background-color: #f44336">
			<tr>
				<td>
					<?php print "You have not set internet up!"; ?>
				</td>
			</tr>
		</table>
		
		
		<?php } ?>

		<h5>Electric</h5>
		<?php

		$sql = "SELECT id, user_id, name, type, amount, added, date, payees FROM payments WHERE house_id = '" . $house_id . "' AND (payees = 'everyone' OR (payees = 'me' AND user_id = '" . $_COOKIE[ 'user_id' ] . "')) AND type = 'electric' ORDER BY date";

		$done = 0;
		foreach ( $con->query( $sql ) as $row ) {

			$sql2 = "SELECT status FROM pay_status WHERE payment_id = '" . $row[ 'id' ] . "' AND user_id = '" . $_COOKIE[ 'user_id' ] . "'";
			foreach ( $con->query( $sql2 ) as $row2 ) {
				$paid = $row2[ 'status' ];
			}

			if ( ( strtotime( $row[ 'date' ] ) < time() && $paid == 'unpaid' ) || ( strtotime( $row[ 'date' ] ) < $next_month && strtotime( $row[ 'date' ] ) > time() ) && $done == 0 ) {
				$done = 1;

				$colour = '#ef6c00';

				$start = strtotime( $row[ 'added' ] );

				$diff = floor( strtotime( $row[ 'date' ] ) - time() );

				if ($diff > 86400) {
				if (round($diff / 86400) == 1) {
					$time = round($diff / 86400) . " day" ;
				} else {
				$time = round($diff / 86400) . " days" ;
				}
			}
			else if ($diff > 3600) {
				if (round($diff / 3600) == 1) {
				$time = round($diff / 3600) . " hour" ;
				} else {
					$time = round($diff / 3600) . " hours" ;
				}
			}
			else if ($diff > 60) {
				if (round($diff / 60) == 1) {
				$time = round($diff / 60) . " minute" ;
				} else {
					$time = round($diff / 60) . " minutes" ;
				}
			}
				else if ($diff > 0) {
				if (round($diff) == 1) {
				$time = round($diff) . " second" ;
				} else {
					$time = round($diff) . " seconds" ;
				}
			}
			else {
				$time = 'overdue';
			}

				$percent = ( time() - $start ) / ( floor( strtotime( $row[ 'date' ] ) ) - $start );

				?>

		<table id="payment_bar">
			<tr style="background-color: <?php print $colour ?>; ">
				<td colspan="1" style="padding:4px;" width="25%">
					<?php print $row['name'] ?>
				</td>
				<td colspan="3" width="75%">
					<table>
						<tr>
							<td style="box-shadow: none">
								Time left to pay:
								<?php print $time ?>
							</td>
							<td style="box-shadow: none">
								<div class="progress">
									<div class="bar" data-progressbar="on" data-progressbar-begin="<?php print $percent ?>" data-progressbar-end="1" data-progressbar-delay="100" data-progressbar-duration="<?php print ($diff * 1000) ?>"></div>
								</div>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<?php }} 
			
			if ($done == 0) { ?>
		
			<table id="payment_bar" style="background-color: #ef6c00">
			<tr>
				<td>
					<?php print "You have not set electric up!"; ?>
				</td>
			</tr>
		</table>
		
		
		<?php } ?>


		<h5>Water</h5>
		<?php

		$sql = "SELECT id, user_id, name, type, amount, added, date, payees FROM payments WHERE house_id = '" . $house_id . "' AND (payees = 'everyone' OR (payees = 'me' AND user_id = '" . $_COOKIE[ 'user_id' ] . "')) AND type = 'water' ORDER BY date";
			
		$done = 0;
		foreach ( $con->query( $sql ) as $row ) {

			$sql2 = "SELECT status FROM pay_status WHERE payment_id = '" . $row[ 'id' ] . "' AND user_id = '" . $_COOKIE[ 'user_id' ] . "'";
			foreach ( $con->query( $sql2 ) as $row2 ) {
				$paid = $row2[ 'status' ];
			}

			if ( ( strtotime( $row[ 'date' ] ) < time() && $paid == 'unpaid' ) || ( strtotime( $row[ 'date' ] ) < $next_month && strtotime( $row[ 'date' ] ) > time() ) && $done == 0 ) {
				$done = 1;

				$colour = '#2196f3';

				$start = strtotime( $row[ 'added' ] );

				$diff = floor( strtotime( $row[ 'date' ] ) - time() );

				if ($diff > 86400) {
				if (round($diff / 86400) == 1) {
					$time = round($diff / 86400) . " day" ;
				} else {
				$time = round($diff / 86400) . " days" ;
				}
			}
			else if ($diff > 3600) {
				if (round($diff / 3600) == 1) {
				$time = round($diff / 3600) . " hour" ;
				} else {
					$time = round($diff / 3600) . " hours" ;
				}
			}
			else if ($diff > 60) {
				if (round($diff / 60) == 1) {
				$time = round($diff / 60) . " minute" ;
				} else {
					$time = round($diff / 60) . " minutes" ;
				}
			}
				else if ($diff > 0) {
				if (round($diff) == 1) {
				$time = round($diff) . " second" ;
				} else {
					$time = round($diff) . " seconds" ;
				}
			}
			else {
				$time = 'overdue';
			}

				$percent = ( time() - $start ) / ( floor( strtotime( $row[ 'date' ] ) ) - $start );
				?>

		<table id="payment_bar">
			<tr style="background-color: <?php print $colour ?>; ">
				<td colspan="1" style="padding:4px;" width="25%">
					<?php print $row['name'] ?>
				</td>
				<td colspan="3" width="75%">
					<table>
						<tr>
							<td style="box-shadow: none">
								Time left to pay:
								<?php print $time ?>
							</td>
							<td style="box-shadow: none">
								<div class="progress">
									<div class="bar" data-progressbar="on" data-progressbar-begin="<?php print $percent ?>" data-progressbar-end="1" data-progressbar-delay="100" data-progressbar-duration="<?php print ($diff * 1000) ?>"></div>
								</div>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<?php }} 
			
			if ($done == 0) { ?>
		
			<table id="payment_bar" style="background-color: #2196f3">
			<tr>
				<td>
					<?php print "You have not set water up!"; ?>
				</td>
			</tr>
		</table>
		
		
		<?php } ?>

		<h5>Gas</h5>
		<?php

		$sql = "SELECT id, user_id, name, type, amount, added, date, payees FROM payments WHERE house_id = '" . $house_id . "' AND (payees = 'everyone' OR (payees = 'me' AND user_id = '" . $_COOKIE[ 'user_id' ] . "')) AND type = 'gas' ORDER BY date";

		$done = 0;
		foreach ( $con->query( $sql ) as $row ) {

			$sql2 = "SELECT status FROM pay_status WHERE payment_id = '" . $row[ 'id' ] . "' AND user_id = '" . $_COOKIE[ 'user_id' ] . "'";
			foreach ( $con->query( $sql2 ) as $row2 ) {
				$paid = $row2[ 'status' ];
			}

			if ( ( strtotime( $row[ 'date' ] ) < time() && $paid == 'unpaid' ) || ( strtotime( $row[ 'date' ] ) < $next_month && strtotime( $row[ 'date' ] ) > time() ) && $done == 0 ) {
				$done = 1;

				$colour = '#0097a7';

				$start = strtotime( $row[ 'added' ] );

				$diff = floor( strtotime( $row[ 'date' ] ) - time() );

				if ($diff > 86400) {
				if (round($diff / 86400) == 1) {
					$time = round($diff / 86400) . " day" ;
				} else {
				$time = round($diff / 86400) . " days" ;
				}
			}
			else if ($diff > 3600) {
				if (round($diff / 3600) == 1) {
				$time = round($diff / 3600) . " hour" ;
				} else {
					$time = round($diff / 3600) . " hours" ;
				}
			}
			else if ($diff > 60) {
				if (round($diff / 60) == 1) {
				$time = round($diff / 60) . " minute" ;
				} else {
					$time = round($diff / 60) . " minutes" ;
				}
			}
				else if ($diff > 0) {
				if (round($diff) == 1) {
				$time = round($diff) . " second" ;
				} else {
					$time = round($diff) . " seconds" ;
				}
			}
			else {
				$time = 'overdue';
			}

				$percent = ( time() - $start ) / ( floor( strtotime( $row[ 'date' ] ) ) - $start );

				?>

		<table id="payment_bar">
			<tr style="background-color: <?php print $colour ?>; ">
				<td colspan="1" style="padding:4px;" width="25%">
					<?php print $row['name'] ?>
				</td>
				<td colspan="3" width="75%">
					<table>
						<tr>
							<td style="box-shadow: none">
								Time left to pay:
								<?php print $time ?>
							</td>
							<td style="box-shadow: none">
								<div class="progress">
									<div class="bar" data-progressbar="on" data-progressbar-begin="<?php print $percent ?>" data-progressbar-end="1" data-progressbar-delay="100" data-progressbar-duration="<?php print ($diff * 1000) ?>"></div>
								</div>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<?php }} 
			
			if ($done == 0) { ?>
		
			<table id="payment_bar" style="background-color: #0097a7">
			<tr>
				<td>
					<?php print "You have not set gas up!"; ?>
				</td>
			</tr>
		</table>
		
		
		<?php } ?>

	</container>
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

	<footer>
		Callum Pilton © 2017 | <a href="privacy_policy.php">Privacy Policy</a> | <a href="terms_of_service.php">Terms of Service</a> | <a href="contact.php">Contact Us</a>
	</footer>
</body>

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

	<?php } ?>
</script>

<script type="text/javascript">
	$( ".bar[data-progressbar='on']" ).each( function () {
		$( this ).animatedProgressbar();
	} );
</script>

</html>
<!-- Callum Pilton -->
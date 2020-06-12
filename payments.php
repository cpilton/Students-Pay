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

	<script src="js/jquery-3.2.1.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/jquery.animated-progressbar.js"></script>

	<style type="text/css">
		div.new {
			display: none;
		}
	</style>

	<script type="text/javascript">
		$( document ).ready( function () {

		} );

		function check() {
			var check = ( $( "#check_field" ).prop( 'selectedIndex' ) );

			if ( check == 0 ) {
				$( "#show_label" ).css( "display", "block" ).slideUp( 300 );
				$( "#show_input" ).css( "display", "inline-block" ).slideUp( 300 );

			} else {
				if ( !$( '#show_label' ).is( ':visible' ) ) {
					$( "#show_label" ).css( "display", "none" ).slideDown( 300 );
					$( "#show_input" ).css( "display", "none" ).slideDown( 300 );

				}
			}
		}
	</script>
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
							<a href="payments.php" style="font-weight: bold">Payments</a>
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
		
        $user = $DBH->prepare('SELECT first_name, house_id FROM users WHERE ID=:id');
        $user->bindParam('id', $_COOKIE['user_id']);
        $user->execute();
        $user = $user->fetch();
        $firstname = $user['first_name'];
		$house_id = $user['house_id'];
		
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
		<h2>Payments</h2>
		<h4 style="text-align: center">It doesn't look like you're registered in a House yet!</h4>
		<p style="text-align: center">Don't worry, let's fix that! </br> You can set up a new house or join a House created by a housemate, all you need is the House ID and the House password. </br> Just click on one of the buttons below to get started.</p>
		<holder>
			<a class="button" id="create_house">Create a House</a>
			<a class="button" id="join_house">Join a House</a>
		</holder>
	</container>
	<?php } else { ?>
	<container>
		<h2>Payments</h2>
		<p style="text-align: center">Here you can view all of the payments set up by yourself, or anyone else in your house who has created a payment for everyone. You can also create new payments. Only payments which occur withing the next month (28-31 days) will be shown. Payments will dissapear from this page once they are set to paid and their time runs out.</p>
		<holder>
			<a class="button" style="margin-bottom:5px" id="add">Add a New Payment</a>
		</holder>
		

		<?php
				  $curMonth = date('n');
$curYear  = date('Y');
			if ($curMonth == 12) {
    $firstDayNextMonth = mktime(0, 0, 0, 0, 0, $curYear+1);
			} else {
    $firstDayNextMonth = mktime(0, 0, 0, $curMonth+1, 1);
			} 
				  $next_month = strtotime(' + 1 month');
				  
				  $first_month = 0;
				  $second_month = 0;
				  
			$count = 0;
		$sql = "SELECT id, user_id, name, type, amount, account, added, date, payees FROM payments WHERE house_id = '" . $house_id . "' AND (payees = 'everyone' OR (payees = 'me' AND user_id = '" . $_COOKIE[ 'user_id' ] . "')) ORDER BY date";
		$unpaid_string = 'unpaid';
				  foreach ( $con->query( $sql ) as $row ) {
					  $count++;
				  }
				 if ($count == 0) {
				  ?>
				  <h3>
					  <?php print "You have nothing left to pay for a whole month, woo!"; ?>
				  
				  </h3>
				  <?php
				  } 
		foreach ( $con->query( $sql ) as $row ) {
			
			$sql2 = "SELECT COUNT(id) FROM pay_status WHERE payment_id = '" . $row[ 'id' ] . "' AND user_id = '" . $_COOKIE[ 'user_id' ] . "'";
			foreach ( $con->query( $sql2 ) as $row2 ) {
				if ( $row2[ 'COUNT(id)' ] == 0 ) {
					$add_pay = $DBH->prepare( 'INSERT INTO pay_status VALUES(null, :payment_id, :user_id, :status)' );
					$add_pay->bindParam( 'payment_id', $row[ 'id' ] );
					$add_pay->bindParam( 'user_id', $_COOKIE[ 'user_id' ] );
					$add_pay->bindParam( 'status', $unpaid_string );
					$add_pay->execute();
				}
			}
			$sql3 = "SELECT status FROM pay_status WHERE payment_id = '" . $row[ 'id' ] . "' AND user_id = '" . $_COOKIE[ 'user_id' ] . "'";
			foreach ( $con->query( $sql3 ) as $row3 ) {
				$paid = $row3[ 'status' ];
			}
			
			if ((strtotime($row['date']) < time() && $paid == 'unpaid') || (strtotime($row['date']) < $next_month && strtotime($row['date']) > time())) {
				
				if(strtotime($row['date']) < $firstDayNextMonth && $first_month == 0) {
					?> <h2> <?php print date('F') ?> </h2> <?php
			$first_month = 1;
					
				} else if(strtotime($row['date']) > $firstDayNextMonth && $second_month == 0) {
		?> <h2> <?php print date('F', strtotime(date('F'). ' + 1 month')); ?> </h2> <?php
			$second_month = 1;
				}

			$sql4 = "SELECT people FROM house WHERE id = '" . $house_id . "'";
			foreach ( $con->query( $sql4 ) as $row4 ) {
				$people = $row4[ 'people' ];
			}
			
			if ($row['user_id'] == $_COOKIE['user_id']) {
				$added_by = "You";
			}
			else {
			$sql5 = "SELECT first_name, last_name FROM users WHERE id = '".$row['user_id']."'";
			foreach ( $con->query( $sql5 ) as $row5 ) {
				$added_by = $row5['first_name'] . " " . $row5['last_name'];
			}
			}
			
			if ($row['type'] == 'rent') {
				$colour = '#4caf50'; //green
			}
			else if ($row['type'] == 'internet') {
				$colour = '#f44336'; //red
			}
			else if ($row['type'] == 'insurance') {
				$colour = '#9c27b0'; //purple
			}
			else if ($row['type'] == 'water') {
				$colour = '#2196f3'; //blue
			}
			else if ($row['type'] == 'electric') {
				$colour = '#ef6c00'; //orange
			}
			else if ($row['type'] == 'gas') {
				$colour = '#0097a7'; //cyan
			}
			else if ($row['type'] == 'essentials') {
				$colour = '#e91e63'; //pink
			}
			else if ($row['type'] == 'other') {
				$colour = '#757575'; //grey
			}
			
			if ($paid == 'paid') {
				$paid_colour = '#388E3C';
			}
			else {
				$paid_colour = '#D32F2F';
			}
			
			$start = strtotime($row['added']);
			
			$diff = floor(strtotime($row['date']) -  time());
			
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
			
			$percent = (time() - $start) / (floor(strtotime($row['date'])) - $start);
			?>
			<table style="margin-bottom:5px;">
				<tr>
					<td width="83%">
		<table id="payment_bar">
			<tr style="background-color: <?php print $colour ?>; ">
				<td colspan="2" style="padding:2px;" width="50%">
					<?php print $row['name'] ?>
				</td>
				<td style="padding:2px;" width="25%">
					<?php print ucfirst($row['type']); ?>
				</td>
				<td style="padding:2px;" width="25%">
					<?php if ($row['payees'] == 'everyone') {
				echo "£" . number_format(($row['amount'] / $people),2);
			} else {
				echo "£" . number_format($row['amount'],2);
			}
					?>
				</td>
				</tr>
				
				</tr></tr>
				<tr id="payment_under">
					<td width="25%">
						Added by <?php if ($added_by == 'You') { 
						print $added_by ?> - <a href="remove_payment.php?a=<?php print $row['id']?>" style="display:inline">Remove Payment</a>
				<?php } else { print $added_by; } ?>
					</td>
					<td colspan="3" width="75%">
					<table>
						<tr>
						
						<?php if ($paid == 'unpaid') { ?>
							<td style="box-shadow: none">
								Time left to pay: <?php print $time ?>
							</td>
							<td style="box-shadow: none">
								<div class="progress">
						<div class="bar" data-progressbar="on" data-progressbar-begin="<?php print $percent ?>" data-progressbar-end="1" data-progressbar-delay="100" data-progressbar-duration="<?php print ($diff * 1000) ?>"></div>
					</div>
							</td>
							<?php } else { ?>
							
							
								<td style="box-shadow: none">
								This payment will dissapear in <?php print $time ?>
							</td>
							<?php } ?>
						</tr>
					</table>
					</td>
				</tr>
		</table>
					</td>
<td width="1%">
	
</TD>
					<td>
					<table class="pay_table" id="payment_bar" >
						<tr>
						
							<td  class="pay_button" style="background-color:<?php print $paid_colour ?>">
							<a href="change_paid.php?a=<?php print $row['id']?>">
					<?php print ucfirst($paid); ?>
				</a>
							</td>
						</tr>
						<tr id="payment_under">
							<td>
								Pay to <?php print $row['account']; ?>
							</td>
						</tr>
					</table>
				
					</td>
				</tr>
			</table>
<?php }} ?>

	</container>
	<?php }} else { ?>
	<container>
		<h2>Please log in or create an account</h2>
	</container>
	<?php } ?>

	<div id="add_modal" class="modal">

		<div class="modal-content">
			<div class="modal-header">
				<span id="close" class="close">&times;</span>
				<h2>
					Add a New Payment
				</h2>
			

			</div>
			<div class="modal-body">
				<p>
					You are adding a new payment.
				</p>
				<p>
					Payments can be specific to you or applied to the whole house.
				</p>

				<form action="add_payment.php" method="post">

					<fieldset>
						<legend><span class="number">1</span>Add Payment Information</legend>

						<div class="tooltip">
							<label for="name">Name</label>
							<span class="tooltiptext">Set a name for the payment.</span>
						</div>
						<input required type="text" name="name">
						<div class="tooltip">
							<label for="type">Type</label>
							<span class="tooltiptext">Select the type of payment.</span>
						</div>

						<select required name="type">
							<option value="rent">Rent</option>
							<option value="internet">Internet</option>
							<option value="insurance">Insurance</option>
							<option value="water">Water</option>
							<option value="electric">Electric</option>
							<option value="gas">Gas</option>
							<option value="essentials">Essentials</option>
							<option value="other">Other</option>
						</select>

						<div class="tooltip">
							<label for="amount">Amount</label>
							<span class="tooltiptext">Set the total amount to be paid by all payees per recurrence.</span>
						</div>

						<input required name="amount" type="number" min="0.00" max="10000.00" step="0.01"/>

						<div class="tooltip">
							<label for="payees">Payees</label>
							<span class="tooltiptext">Setting payees to 'Everyone' will add this payment to all of your housemates.</span>
						</div>

						<select required name="payees">
							<option value="me">Only Me</option>
							<option value="everyone">Everyone</option>
						</select>
						
						<div class="tooltip">
							<label for="account">Account</label>
							<span class="tooltiptext">Decide which account the money should be paid in to.</span>
						</div>

						<select required name="account">
					<?php 	$sql = "SELECT joint_account FROM house WHERE id = '".$house_id."'";
			foreach ( $con->query( $sql ) as $row ) {
						if ($row['joint_account'] == 1) {
							?>
						
							<option value="Joint Account">Joint Account</option>
							
							<?php 
						}
			}
							$sql = "SELECT first_name, last_name FROM users WHERE house_id = '".$house_id."'";
			foreach ( $con->query( $sql ) as $row ) {
				$option = $row['first_name'] . " " . $row['last_name'];
				?>
				<option value="<?php print $option?>"><?php print $option?></option>
				
				<?php
			}
					?>		
							
							
						</select>

					</fieldset>


					<fieldset>
						<legend><span class="number">2</span>Set Up Payment Date(s)</legend>

						<div class="tooltip">
							<label for="date">Payment Date</label>
							<span class="tooltiptext">Set the date of the first payment.</span>
						</div>

						<input required name="date" type="date" id="dateField" disabled/>

						<div class="tooltip">
							<label for="recurring">Recurrences</label>
							<span class="tooltiptext">Set when recurrences occur.</span>
						</div>

						<select required name="recurring" id="check_field" onchange="check()">
							<option value="none">None</option>
							<option value="daily">Daily</option>
							<option value="weekly">Weekly</option>
							<option value="monthly">Monthly</option>
							<option value="yearly">Yearly</option>
						</select>

						<div id="initRow">
							<div class="tooltip">
								<label for="times_recurring" id="show_label" style="display:none;">Number of Recurrences</label>
								<span class="tooltiptext">Set the number of recurrences.</span>
							</div>

							<input name="times_recurring" type="number" min="1" max="365" step="1" id="show_input" style="display:none;"/>
						</div>
					</fieldset>
			</div>

			<div class="modal-footer">
				<input type="submit" value="Submit" id="modal_submit">
				</form>

			</div>
		</div>

	</div>

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
	
	<div id="removed_modal" class="modal">
		<div class="modal-content">
			<div class="modal-header">
				<span id="close_removed" class="close">&times;</span>
				<h2>
					You've Removed a Payment
				</h2>
			</div>
			<div class="modal-body">
				<p>
					You just removed a payment, yay - more money!
				</p>
			</div>
			<div class="modal-footer">
				<a class="modal_button" id="removed_okay">Okay</a>
			</div>
		</div>
	</div>
	
	<div id="added_modal" class="modal">
		<div class="modal-content">
			<div class="modal-header">
				<span id="close_added" class="close">&times;</span>
				<h2>
					You've Added a Payment
				</h2>
			</div>
			<div class="modal-body">
				<p>
					You just added a payment, better get saving up now!
				</p>
			</div>
			<div class="modal-footer">
				<a class="modal_button" id="added_okay">Okay</a>
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
	
	<?php } else if (isset($_SESSION['redirect']) && $_SESSION['redirect'] == 'removed') { 
		unset($_SESSION['redirect']);
		?>

	var removed_payment_modal = document.getElementById( 'removed_modal' );
	var removed_payment_btn = document.getElementById( "removed_okay" );
	var removed_payment_span = document.getElementById( 'close_removed' );

	removed_payment_modal.style.display = "block";

	removed_payment_btn.onclick = function () {
		removed_payment_modal.style.display = "none";
	}
	removed_payment_span.onclick = function () {
		removed_payment_modal.style.display = "none";
	}
	window.onclick = function ( event ) {
		if ( event.target == removed_payment_modal ) {
			removed_payment_modal.style.display = "none";
		}
	}
	
	<?php } else if (isset($_SESSION['redirect']) && $_SESSION['redirect'] == 'added') { 
		unset($_SESSION['redirect']);
		?>

	var added_payment_modal = document.getElementById( 'added_modal' );
	var added_payment_btn = document.getElementById( "added_okay" );
	var added_payment_span = document.getElementById( 'close_added' );

	added_payment_modal.style.display = "block";

	added_payment_btn.onclick = function () {
		added_payment_modal.style.display = "none";
	}
	added_payment_span.onclick = function () {
		added_payment_modal.style.display = "none";
	}
	window.onclick = function ( event ) {
		if ( event.target == added_payment_modal ) {
			added_payment_modal.style.display = "none";
		}
	}
	
	<?php } ?>
	
</script>

<script>
	var add_modal = document.getElementById( 'add_modal' );
	var add_btn = document.getElementById( "add" );
	var add_span = document.getElementById( 'close' );
	add_btn.onclick = function () {
		add_modal.style.display = "block";
	}
	add_span.onclick = function () {
		add_modal.style.display = "none";
	}
	window.onclick = function ( event ) {
		if ( event.target == add_modal ) {
			add_modal.style.display = "none";
		}
	}
</script>

<script type='text/javascript'>
	var input = document.getElementById( "dateField" );
	var today = new Date();
	var day = today.getDate() + 1;

	var mon = new String( today.getMonth() + 1 );
	var yr = today.getFullYear();

	if ( mon.length < 2 ) {
		mon = "0" + mon;
	}

	var date = new String( yr + '-' + mon + '-' + day );

	input.disabled = false;
	input.setAttribute( 'min', date );
</script>

<script type="text/javascript">
		$(".bar[data-progressbar='on']").each(function() {
			$(this).animatedProgressbar();
		});
	</script>

</html>
<!-- Callum Pilton -->
<!DOCTYPE html>
<?php 
include_once("analytics.php");
?>
<html>

<head>

<script type="text/javascript">
  <!--
  if (screen.width <= 800) {
    window.location = "http://m.studentspay.heliohost.org";
  }
  //-->
</script>

<title>Students Pay</title>
<link rel="icon" href="img/favicon.ico" type="image/x-icon">
	<style>
		
	
		body,
		html {
			height: 100%;
			margin: 0;
			font: 400 15px/1.8;
			font-family: 'Open Sans', sans-serif;
			color: #777;
		
		}
		
		.bgimg-1,
		.bgimg-2,
		.bgimg-3 {
			position: relative;
			opacity: 0.65;
			background-attachment: fixed;
			background-position: center;
			background-repeat: no-repeat;
			background-size: cover;
			-webkit-transform: translate3d(0,0,0);
		}
		
		.bgimg-1 {
			background-image: url(img/parallax1.svg);
			height: 100%;
		}
		
		.bgimg-2 {
			background-image: url("img/parallax2.svg");
			height: 100%;
		}
		
		.bgimg-3 {
			background-image: url("img/parallax3.svg");
			height: 100%;
		}
		
		.caption {
			position: absolute;
			left: 0;
			top: 43%;
			width: 100%;
			text-align: center;
			color: #000;
		}
		
		.caption span.border {
			background-color: #12aeef;
			color: #fff;
			padding: 18px;
			font-size: 25px;
			letter-spacing: 10px;
		}
		
		h3 {
			letter-spacing: 5px;
			text-transform: uppercase;
			font: 20px "Lato", sans-serif;
			color: #fff;
		}
		
		.border {
			margin: 0px 10px 0px 10px;
		}
		/* Turn off parallax scrolling for tablets and phones */
		
		@media only screen and (max-device-width: 1024px) {
			.bgimg-1,
			.bgimg-2,
			.bgimg-3 {
				background-attachment: scroll;
			}
		}
	</style>

</head>

<body>
	<div class="bgimg-1">
		<div class="caption">
			<a href="session.php?a=login" style="text-decoration: none; "><span class="border">LOG IN</span></a> <a href="session.php?a=register" style="text-decoration: none"><span class="border" id="button">SIGN UP</span></a></br></br></br></br>
			<span  style="background-color:transparent;font-size:25px;color: #99;">SCROLL DOWN FOR MORE</span>
		</div>
		
</div>

	<div style="color: #fff;background-color:#12aeef;text-align:center;padding:50px 80px;text-align: justify;">
		<h3 style="text-align:center;">Welcome to Students Pay!</h3>
		<p>Students Pay is a free online tool for helping you pay your bills in a shared house. You can create a 'House' which allows you to add and view payments, allowing you to see what needs paying and keep track of your spending. Students pay will help you to fairly split the cost of purchases and bills related to your House.</p>
	</div>

	<div class="bgimg-2">

	</div>

	<div style="position:relative;">
		<div style="color: #fff;background-color:#6060dd;text-align:center;padding:50px 80px;text-align: justify;">
			<p>Students Pay provides 3 ways for you to look at your payments; these are visible in the Home, Payments, and Overview tabs.
				</br>
				The Home tab provides a view of all of the payments which should be set up, and shows a countdown to the date which the payment should be made.
				</br>
				The Payments tab allows you to add new payments to your House. You can also view all of the payments which apply to your account, and declare whether they have been paid or not.
	</br>
		The Overview tab gives you and your housemates a look at who has paid for upcoming payments and highlights any payments which are yet to be fully paid.
			</p>
		</div>
	</div>

	<div class="bgimg-3">
		<div class="caption">
			
		</div>
	</div>

	<div style="position:relative;">
		<div style="color: #fff;background-color:#12aeef;text-align:center;padding:50px 80px;text-align: justify;">
			<p>It's easy to get started with Students Pay. All you need to do is create an account and join a house!* All of your housemates will be visible to you when you join the house and you will be able to immidiately view any outstanding payments and get started right away!

				</br>
				</br>
				*To join a House you will need to know your House ID and House Password. This will be visible to everyone in your house from the 'Manage' page. </br>
				*If a House has not already been set up by one of your housemates, you will need to create one. To do this, all you need to know is the number of people in your house, and a password you're happy sharing with your housemates.</p>
		</div>
	</div>

	<div class="bgimg-1">
		<div class="caption">
			<a href="session.php?a=login" style="text-decoration: none; "><span class="border">LOG IN</span></a> <a href="session.php?a=register" style="text-decoration: none"><span class="border" id="button">SIGN UP</span></a>
		</div>
	</div>
</body>

</html>
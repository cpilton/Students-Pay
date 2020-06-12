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
	
	<container_left>
		<h2>Students Pay Privacy Policy</h2>
		<p>
This privacy policy has been compiled to better serve those who are concerned with how their 'Personally Identifiable Information' (PII) is being used online. PII, as described in US privacy law and information security, is information that can be used on its own or with other information to identify, contact, or locate a single person, or to identify an individual in context. Please read our privacy policy carefully to get a clear understanding of how we collect, use, protect or otherwise handle your Personally Identifiable Information in accordance with our website.
<h3>
What personal information do we collect from the people that visit our blog, website or app?
</h3>
When ordering or registering on our site, as appropriate, you may be asked to enter your name, Payment Information or other details to help you with your experience.
<h3>
When do we collect information?
</h3>
We collect information from you when you register on our site, fill out a form or enter information on our site.

<h3>
How do we use your information?
</h3>
We may use the information we collect from you when you register, make a purchase, sign up for our newsletter, respond to a survey or marketing communication, surf the website, or use certain other site features in the following ways:

      To personalize your experience and to allow us to deliver the type of content and product offerings in which you are most interested.
<h3>
How do we protect your information?
</h3>
		We do not use vulnerability scanning and/or scanning to PCI standards.</br>
We only provide articles and information. We never ask for credit card numbers.
</br>
We do not use Malware Scanning.
</br>
We do not use an SSL certificate
     </br>
      We only provide articles and information. We never ask for personal or private information like names, email addresses, or credit card numbers.
<h3>
Do we use 'cookies'?
</h3>
Yes. Cookies are small files that a site or its service provider transfers to your computer's hard drive through your Web browser (if you allow) that enables the site's or service provider's systems to recognize your browser and capture and remember certain information. For instance, we use cookies to help us remember and process the items in your shopping cart. They are also used to help us understand your preferences based on previous or current site activity, which enables us to provide you with improved services. We also use cookies to help us compile aggregate data about site traffic and site interaction so that we can offer better site experiences and tools in the future.

<h3>
We use cookies to:</h3>
      Understand and save user's preferences for future visits.
</br>
You can choose to have your computer warn you each time a cookie is being sent, or you can choose to turn off all cookies. You do this through your browser settings. Since browser is a little different, look at your browser's Help Menu to learn the correct way to modify your cookies.
<h3>
If users disable cookies in their browser:
</h3>
If you turn cookies off, Some of the features that make your site experience more efficient may not function properly.Some of the features that make your site experience more efficient and may not function properly.

<h3>
Third-party disclosure
</h3>
We do not sell, trade, or otherwise transfer to outside parties your Personally Identifiable Information.
<h3>
Third-party links
</h3>
We do not include or offer third-party products or services on our website.
<h3>
Opting out:
</h3>
Users can set preferences for how Google advertises to you using the Google Ad Settings page. Alternatively, you can opt out by visiting the Network Advertising Initiative Opt Out page or by using the Google Analytics Opt Out Browser add on.
<h3>
Fair Information Practices
</h3>
The Fair Information Practices Principles form the backbone of privacy law in the United States and the concepts they include have played a significant role in the development of data protection laws around the globe. Understanding the Fair Information Practice Principles and how they should be implemented is critical to comply with the various privacy laws that protect personal information.
<h3>
In order to be in line with Fair Information Practices we will take the following responsive action, should a data breach occur:</h3>
We will notify the users via in-site notification within 7 business days.

We also agree to the Individual Redress Principle which requires that individuals have the right to legally pursue enforceable rights against data collectors and processors who fail to adhere to the law. This principle requires not only that individuals have enforceable rights against data users, but also that individuals have recourse to courts or government agencies to investigate and/or prosecute non-compliance by data processors.
	</br>
</br>
Last Edited on 2017-08-18
	
		</p>
	</container_left>

	<footer>
		Callum Pilton © 2017 | <a style="font-weight: bold" href="privacy_policy.php">Privacy Policy</a> | <a href="terms_of_service.php">Terms of Service</a> | <a href="contact.php">Contact Us</a>
	</footer>
</body>


</html>
<!-- Callum Pilton -->
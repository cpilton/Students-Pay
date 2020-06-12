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
		<h2>Students Pay Terms of Service</h2>
<h3>1. Terms</h3>
By accessing the website at http://studentspay.tk, you are agreeing to be bound by these terms of service, all applicable laws and regulations, and agree that you are responsible for compliance with any applicable local laws. If you do not agree with any of these terms, you are prohibited from using or accessing this site. The materials contained in this website are protected by applicable copyright and trademark law.
<h3>2. Use License</h3>
Permission is granted to temporarily download one copy of the materials (information or software) on Students Pay's website for personal, non-commercial transitory viewing only. This is the grant of a license, not a transfer of title, and under this license you may not:
		<ol>
			<li>
modify or copy the materials;
			</li>
			<li>
use the materials for any commercial purpose, or for any public display (commercial or non-commercial);
</li>
			<li>
attempt to decompile or reverse engineer any software contained on Students Pay's website;
</li>
			<li>
remove any copyright or other proprietary notations from the materials; or
</li>
			<li>
transfer the materials to another person or "mirror" the materials on any other server.
</li>
		</ol>
This license shall automatically terminate if you violate any of these restrictions and may be terminated by Students Pay at any time. Upon terminating your viewing of these materials or upon the termination of this license, you must destroy any downloaded materials in your possession whether in electronic or printed format.
<h3>3. Disclaimer</h3>
The materials on Students Pay's website are provided on an 'as is' basis. Students Pay makes no warranties, expressed or implied, and hereby disclaims and negates all other warranties including, without limitation, implied warranties or conditions of merchantability, fitness for a particular purpose, or non-infringement of intellectual property or other violation of rights.
		</br>
Further, Students Pay does not warrant or make any representations concerning the accuracy, likely results, or reliability of the use of the materials on its website or otherwise relating to such materials or on any sites linked to this site.
<h3>4. Limitations</h3>
In no event shall Students Pay or its suppliers be liable for any damages (including, without limitation, damages for loss of data or profit, or due to business interruption) arising out of the use or inability to use the materials on Students Pay's website, even if Students Pay or a Students Pay authorized representative has been notified orally or in writing of the possibility of such damage. Because some jurisdictions do not allow limitations on implied warranties, or limitations of liability for consequential or incidental damages, these limitations may not apply to you.
<h3>5. Accuracy of materials</h3>
The materials appearing on Students Pay website could include technical, typographical, or photographic errors. Students Pay does not warrant that any of the materials on its website are accurate, complete or current. Students Pay may make changes to the materials contained on its website at any time without notice. However Students Pay does not make any commitment to update the materials.
<h3>6. Links</h3>
Students Pay has not reviewed all of the sites linked to its website and is not responsible for the contents of any such linked site. The inclusion of any link does not imply endorsement by Students Pay of the site. Use of any such linked website is at the user's own risk.
<h3>7. Modifications</h3>
Students Pay may revise these terms of service for its website at any time without notice. By using this website you are agreeing to be bound by the then current version of these terms of service.
<h3>8. Governing Law</h3>
These terms and conditions are governed by and construed in accordance with the laws of United Kingdom and you irrevocably submit to the exclusive jurisdiction of the courts in that State or location.</p>
		
		
	</container_left>

	<footer>
		Callum Pilton © 2017 | <a href="privacy_policy.php">Privacy Policy</a> | <a style="font-weight: bold" href="terms_of_service.php">Terms of Service</a> | <a href="contact.php">Contact Us</a>
	</footer>
</body>


</html>
<!-- Callum Pilton -->
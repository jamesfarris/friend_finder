<?php
	session_start();
	if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) 
	{
		header('Location: profile.php');
	}
	session_destroy();
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Friend Finder Network</title>
	<link rel="stylesheet" href="styles.css">
</head>
<body>
	<div class="header">
		<h1>Friend Finder</h1>
	</div>
	<div class="register">
		<form action="process.php" method="post">
				<input type="hidden" name="action" value="register">
					<?php
						if (isset($_SESSION['error'])) 
						{
							echo '<div class="errors">';
							echo '<ul>';
							foreach ($_SESSION['error'] as $value) 
							{
								echo '<li>' . $value . '</li>';
							}
							echo '</ul>';
							echo '</div>';
						}
					?>
				<div>
					<label>First Name</label>
					<input type="text" name="first_name" placeholder="John">
				</div>
				<div>
					<label>Last Name</label>
					<input type="text" name="last_name" placeholder="Doe">
				</div>
				<div>
					<label>Email</label>
					<input type="text" name="email" placeholder="johndoe@someplace.com">
				</div>
				<div>
					<label>Password</label>
					<input type="password" name="password" placeholder="Password">
				</div>
				<div>
					<label>Confirm Password</label>
					<input type="password" name="confirm_password" placeholder="Confirm Password">
				</div>
				<input type="submit" value="Register">
			</form>
		</div>
		<div class="login">
			<?php
				if (isset($_SESSION['login_error'])) 
				{
					echo '<div class="errors">';
					echo '<ul>';
					echo '<li>' . $_SESSION['login_error'] . '</li>';
					echo '</ul>';
					echo '</div>';
				}
			?>
			<form action="process.php" method="post">
				<input type="hidden" name="action" value="login">
				<input type="text" name="email" id="email" placeholder="Enter your email">
				<input type="password" name="password" placeholder="Enter your password">
				<input type="submit" value="Login">
			</form>
		</div>
</body>
</html>
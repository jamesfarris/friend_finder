<?php
	session_start();
	include_once('user.php');
	$info = new User();
	(empty($_SESSION) ? header('Location: index.php') : '');
	// var_dump($_SESSION);
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Friend Finder</title>
	<link rel="stylesheet" href="styles.css">
</head>
<body>
	<div class="header">
		<h1>Friend Finder</h1>
		<p>Welcome <?= (isset($_SESSION['logged_in']) ? $_SESSION['user']['fname'] . ' ' . $_SESSION['user']['lname'] : 'not logged in')?></p>
		<form action="process.php" method="post">
		<input type="hidden" name="action" value="logout">
		<input type="submit" value="Logout">
	</form>
		<div class="clear"></div>
	</div>
	<p>
		<?php 
			if (isset($_SESSION['message'])) {
				echo $_SESSION['message'];
				unset($_SESSION['message']);
			}
		?>
	</p>
	<table border="1">
	<caption>My Friends</caption>
		<thead>
			<tr>
				<th>Name</th>
				<th>Email</th>
			</tr>
		</thead>
		<tbody>
	<?php
		$friends = $info->friends($_SESSION['user']['id']);
		foreach ($friends as $friend) {
		?>
			<tr>
				<td><?= $friend['name'] ?></td>
				<td><?= $friend['email'] ?></td>
			</tr>
		<?php
		}
	?>
		</tbody>
	</table>
	<table border="1">
		<caption>Users on the Friend Finder Network</caption>
		<thead>
			<tr>
				<th>Name</th>
				<th>Email</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
		<?php
			$users = $info->getUsers($_SESSION['user']['id']);
			foreach ($users as $user) {
				?>
				<tr>
					<td><?= $user['name'] ?></td>
					<td><?= $user['email'] ?></td>
					<td>
					<?php
					if($info->isFriend($_SESSION['user']['id'], $user['user_id']))
					{
					?>
					<p>Friends</p>
					<?php
					}
					else
					{
					?>
						<form action="process.php" method="post">
							<input type="hidden" name="action" value="add_friend">
							<input type="hidden" name="user_id" value="<?= $_SESSION['user']['id'] ?>">
							<input type="hidden" name="friend_id" value="<?= $user['user_id'] ?>">
							<input type="submit" value="Add friend">
						</form>
					<?php
					}
					?>
					</td>
					</tr>
				<?php
				}
			
		?>
		</tbody>
	</table>
</body>
</html>
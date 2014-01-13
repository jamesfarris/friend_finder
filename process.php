<?php
	session_start();
	require_once('database.php');
	require_once('user.php');

	class Process
	{	
		private $connection;

		public function __construct()
		{
			$db = new Database();
			$this->connection = $db->connection;

			if(isset($_POST['action']) && $_POST['action'] == 'register')
			{
				$this->register();
			}
			if (isset($_POST['action']) && $_POST['action'] == 'login') 
			{
				$this->login();
			}
			if (isset($_POST['action']) && $_POST['action'] == 'add_friend') 
			{
				$this->add_friend();
			}
			if(isset($_POST['action']) && $_POST['action'] == 'logout')
			{
				unset($_SESSION);
				session_destroy();
				header('Location: index.php');
			}
		}

		private function register()
		{
			foreach ($_POST as $name => $value) 
			{
				$fix_name = str_replace('_', ' ', $name);
				$fixed = ucwords($fix_name);
				if (empty($value)) 
				{
					$_SESSION['error'][$name] = $fixed . ' cannot be empty';
				}
				else
				{
					switch ($name) 
					{
						case 'email':
							if (!filter_var($value, FILTER_VALIDATE_EMAIL)) 
							{
								$_SESSION['error'][$name] = $value . ' is invalid';
							}
							// else if ()
							break;

						case 'password':
							if (strlen($value) < 5) 
							{
								$_SESSION['error'][$name] = $fixed . ' should be at least 5 characters';
							}
							break;

						case 'confirm_password':
							$password = $_POST['password'];
							if ($value != $password) 
							{
								$_SESSION['error'][$name] = 'Passwords do not match';
							}
							break;
					}
				}
			}
			// var_dump($_SESSION);
			if (isset($_SESSION['error'])) 
			{
				header('Location: index.php');
			}
			else
			{
				$this->newUser($_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['password']);
				$_SESSION['message'] = "You account was created.";
				$_SESSION['logged_in'] = true;
				$_SESSION['user']['fname'] = $_POST['first_name'];
				$_SESSION['user']['lname'] = $_POST['last_name'];
				$_SESSION['user']['email'] = $_POST['email'];
				header('Location: profile.php');
			}
		}

		private function login()
		{
			// var_dump($_POST);
			// echo 'Made it';
			if (empty($_POST['email']) || empty($_POST['password'])) {
				$_SESSION['login_error'] = "Email or password cannot be blank";
				header('Location: index.php');
				exit;
			}
			else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
				$_SESSION['login_error'] = "{$_POST['email']} is an invalid email";
				header('Location: index.php');
				exit;
			}
			// var_dump($_SESSION);
			if (!isset($_SESSION['login_error'])) 
			{
				$query = "SELECT * FROM users WHERE email = '{$_POST['email']}'";
				$result = mysqli_query($this->connection, $query);
				$row = mysqli_fetch_assoc($result);
				// var_dump($row);

				if (empty($row)) 
				{
					$_SESSION['login_error'] = "Email doesn't exist.  Please create account.";
					header('Location: index.php');
					exit;	
				}
				else
				{
					if (crypt($_POST['password'], $row['password']) !== $row['password']) 
					{
						$_SESSION['login_error'] = "Login is incorrect";
						header('Location: index.php');
					}

					else
					{
						$_SESSION['message'] = "You have successfully logged in.";
						$_SESSION['logged_in'] = true;
						$_SESSION['user']['id'] = $row['id'];
						$_SESSION['user']['fname'] = $row['first_name'];
						$_SESSION['user']['lname'] = $row['last_name'];
						$_SESSION['user']['email'] = $row['email'];
						unset($_SESSION['login_error']);
						header('Location: profile.php');
					}
				}
			}
		}
		public function newUser($fname, $lname, $email, $password)
		{
			$fname = trim($fname);
			$lname = trim($lname);
			$email = trim($email);
			$password = trim($password);

			$salt = bin2hex(openssl_random_pseudo_bytes(22));
			$hash = crypt($password, $salt);

			$query = "INSERT INTO users (first_name, last_name, email, password, created_at, updated_at)
					  VALUES ('".mysqli_real_escape_string($this->connection, $fname)."', '".mysqli_real_escape_string($this->connection, $lname)."', '".mysqli_real_escape_string($this->connection, $email)."', '".$hash."', NOW(), NOW())";
			mysqli_query($this->connection, $query);
			$_SESSION['user']['id'] = mysqli_insert_id($this->connection);
		}
		
		public function add_friend()
		{
			$user_id = $_POST['user_id'];
			$friend_id = $_POST['friend_id'];
			$query = "INSERT INTO friends (user_id, friend_id)
					  VALUES (".$user_id.", ".$friend_id."), (".$friend_id.", ".$user_id.")";
			mysqli_query($this->connection, $query);
			header('Location: profile.php');
		}
	}
	$process = new Process();
?>
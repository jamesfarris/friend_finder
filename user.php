<?php
	require_once('database.php');
	class User extends Database
	{
		public function getUsers($id)
		{
			$query = "SELECT CONCAT_WS(' ', users.first_name, users.last_name) AS name, users.email, users.id AS user_id
					  FROM users
					  WHERE users.id != $id";
			$user_list = $this->fetchAll($query);
			return $user_list;
		}

		public function friends($id)
		{
			$query = "SELECT CONCAT_WS(' ', users.first_name, users.last_name) AS name, users.email, friends.user_id, friends.friend_id
					  FROM users
					  LEFT JOIN friends ON users.id = friends.user_id
					  WHERE friends.friend_id = $id";
			$friend_list = $this->fetchAll($query);
			return $friend_list;
		}
		public function isfriend($user, $person)
		{
			$query = 'SELECT * FROM friends WHERE (friends.friend_id ='.$user.') AND (friends.user_id ='.$person.')';
                        $result = $this->fetchRecord($query);
                        return ($result ? true : false);
		}
	}
?>
<?php
	require_once('constant.php');
	
	class Database
	{
		public $connection;

		public function __construct()
		{
			$this->connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

			if (mysqli_connect_errno()) 
			{
				echo 'Not connected: ' . mysqli_connect_errno();
			}
		}
		public function fetchAll($query)
		{
			$data = array();
			$result = mysqli_query($this->connection, $query);
			while ($row = mysqli_fetch_assoc($result)) 
			{
				$data[] = $row;
			}
			return $data;
		}

		public function fetchRecord($query)
		{
			$result = mysqli_query($this->connection, $query);
			return mysqli_fetch_assoc($result);
		}
	}
?>
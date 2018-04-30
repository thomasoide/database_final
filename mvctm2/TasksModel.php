<?php

	class TasksModel {
		private $error = '';
		private $mysqli;
		// private $orderBy = 'firstName';
		private $orderDirection = 'asc';

		public function __construct() {
			session_start();
			$this->initDatabaseConnection();
			// $this->restoreOrdering();
		}

		public function __destruct() {
			if ($this->mysqli) {
				$this->mysqli->close();
			}
		}

		public function getError() {
			return $this->error;
		}

		private function initDatabaseConnection() {
			require('db_credentials.php');
			$this->mysqli = new mysqli($servername, $username, $password, $dbname);
			if ($this->mysqli->connect_error) {
				$this->error = $mysqli->connect_error;
			}
		}

		// private function restoreOrdering() {
		// 	$this->orderBy = $_SESSION['orderby'] ? $_SESSION['orderby'] : $this->orderBy;
		// 	$this->orderDirection = $_SESSION['orderdirection'] ? $_SESSION['orderdirection'] : $this->orderDirection;
		//
		// 	$_SESSION['orderby'] = $this->orderBy;
		// 	$_SESSION['orderdirection'] = $this->orderDirection;
		// }

		// public function toggleOrder($orderBy) {
		// 	if ($this->orderBy == $orderBy)	{
		// 		if ($this->orderDirection == 'asc') {
		// 			$this->orderDirection = 'desc';
		// 		} else {
		// 			$this->orderDirection = 'asc';
		// 		}
		// 	} else {
		// 		$this->orderDirection = 'asc';
		// 	}
		// 	$this->orderBy = $orderBy;
		//
		// 	$_SESSION['orderby'] = $this->orderBy;
		// 	$_SESSION['orderdirection'] = $this->orderDirection;
		// }

		// public function getOrdering() {
		// 	return array($this->orderBy, $this->orderDirection);
		// }

		public function getTasks() {
			$this->error = '';
			$tasks = array();

			if (! $this->mysqli) {
				$this->error = "No connection to database.";
				return array($tasks, $this->error);
			}

			$orderByEscaped = $this->mysqli->real_escape_string($this->orderBy);
			$orderDirectionEscaped = $this->mysqli->real_escape_string($this->orderDirection);
			$sql = "SELECT * FROM client /*ORDER BY $orderByEscaped $orderDirectionEscaped*/";
			if ($result = $this->mysqli->query($sql)) {
				if ($result->num_rows > 0) {
					while($row = $result->fetch_assoc()) {
						array_push($tasks, $row);
					}
				}
				$result->close();
			} else {
				$this->error = $mysqli->error;
			}

			return array($tasks, $this->error);
		}

		public function getTask($id) {
			$this->error = '';
			$task = null;

			if (! $this->mysqli) {
				$this->error = "No connection to database.";
				return array($task, $this->error);
			}

			if (! $id) {
				$this->error = "No id specified for task to retrieve.";
				return array($task, $this->error);
			}

			$idEscaped = $this->mysqli->real_escape_string($id);

			$sql = "SELECT * FROM client WHERE id = '$idEscaped'";
			if ($result = $this->mysqli->query($sql)) {
				if ($result->num_rows > 0) {
					$task = $result->fetch_assoc();
				}
				$result->close();
			} else {
				$this->error = $this->mysqli->error;
			}

			return array($task, $this->error);
		}

		public function addTask($data) {
			$this->error = '';

			$firstName = $data['firstName'];
			$lastName = $data['lastName'];
			$email= $data['email'];

			if (! $firstName && ! $lastName && ! $email) {
				$this->error = "No first name given. Please enter your first name.";
				return $this->error;
			}

			// if (! $lastName ) {
			// 	$this->error = "No last name given. Please enter your first name.";
			// 	return $this->error;
			// }
			//
			// if (! $email) {
			// 	$this->error = 'No email given. Please enter your email.';
			// 	return $this->error;
			// }

			$firstEscaped = $this->mysqli->real_escape_string($firstName);
			$lastEscaped = $this->mysqli->real_escape_string($lastName);
			$emailEscaped = $this->mysqli->real_escape_string($email);

			$sql = "INSERT INTO client (firstName, email, lastName) VALUES ('$firstEscaped', '$emailEscaped', '$lastEscaped')";

			if (! $result = $this->mysqli->query($sql)) {
				$this->error = $this->mysqli->error;
			}

			return $this->error;
		}

		// public function updateTaskCompletionStatus($id, $status) {
// 			$this->error = "";
//
// 			$completedDate = 'null';
// 			if ($status == 'completed') {
// 				$completedDate = 'NOW()';
// 			}
//
// 			if (!$id) {
// 				$this->error = "No task was specified to change completion status.";
// 			} else {
// 				$idEscaped = $this->mysqli->real_escape_string($id);
// 				$sql = "UPDATE tasks SET completedDate = $completedDate WHERE id = '$idEscaped'";
// 				if (! $result = $this->mysqli->query($sql) ) {
// 					$this->error = $this->mysqli->error;
// 				}
// 			}
//
// 			return $this->error;
// 		}

		public function updateTask($data) {
			$this->error = '';

			if (! $this->mysqli) {
				$this->error = "No connection to database.";
				return $this->error;
			}

			$id = $data['id'];
			if (! $id) {
				$this->error = "No id specified for task to update.";
				return $this->error;
			}

			$firstName = $data['firstName'];
			$lastName = $data['lastName'];
			$email= $data['email'];

			if (! $firstName ) {
				$this->error = "No first name given. Please enter your first name.";
				return $this->error;
			}

			if (! $lastName ) {
				$this->error = "No last name given. Please enter your first name.";
				return $this->error;
			}

			if (! $email) {
				$this->error = 'No email given. Please enter your email.';
				return $this->error;
			}

			$idEscaped = $this->mysqli->real_escape_string($id);
			$firstEscaped = $this->mysqli->real_escape_string($firstName);
			$lastEscaped = $this->mysqli->real_escape_string($lastName);
			$emailEscaped = $this->mysqli->real_escape_string($email);
			$sql = "UPDATE client SET firstName='$firstEscaped', email='$emailEscaped', lastName='$lastEscaped' WHERE id = $idEscaped";
			if (! $result = $this->mysqli->query($sql) ) {
				$this->error = $this->mysqli->error;
			}

			return $this->error;
		}

		public function deleteTask($id) {
			$this->error = '';

			if (! $this->mysqli) {
				$this->error = "No connection to database.";
				return $this->error;
			}

			if (! $id) {
				$this->error = "No id specified for task to delete.";
				return $this->error;
			}

			$idEscaped = $this->mysqli->real_escape_string($id);
			$sql = "DELETE FROM client WHERE id = $idEscaped";
			if (! $result = $this->mysqli->query($sql) ) {
				$this->error = $this->mysqli->error;
			}

			return $this->error;
		}


	}

?>

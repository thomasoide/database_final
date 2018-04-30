<?php

	// NOTE: this version has beginning of support for logging in and supporting
	// multiple users.  This version does not yet have the database table for users
	// or the change to the database so that each task is affiliated with a user.

	require('User.php');

	class TasksModel {
		private $error = '';
		private $mysqli;
		private $orderBy = 'title';
		private $orderDirection = 'asc';
		private $user;

		public function __construct() {
			session_start();
			$this->initDatabaseConnection();
			echo "<p>Connection established</p>";
			//$this->restoreOrdering();
			//$this->restoreUser();
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

		private function restoreOrdering() {
			$this->orderBy = $_SESSION['orderby'] ? $_SESSION['orderby'] : $this->orderBy;
			$this->orderDirection = $_SESSION['orderdirection'] ? $_SESSION['orderdirection'] : $this->orderDirection;

			$_SESSION['orderby'] = $this->orderBy;
			$_SESSION['orderdirection'] = $this->orderDirection;
		}

		private function restoreUser() {
			if ($loginID = $_SESSION['loginid']) {
				$this->user = new User();
				if (!$this->user->load($loginID, $this->mysqli)) {
					$this->user = null;
				}
			}
		}

		public function getUser() {
			return $this->user;
		}

		public function login($loginID, $password) {
			// check if loginID and password are valid by comparing
			// encrypted version of password to encrypted password stored
			// in database for user with loginID

			$user = new User();
			if ($user->load($loginID, $this->mysqli) && password_verify($password, $user->hashedPassword)) {
				$this->user = $user;
				$_SESSION['loginid'] = $loginID;
				return array(true, "");
			} else {
				$this->user = null;
				$_SESSION['loginid'] = '';
				return array(false, "Invalid login information.  Please try again.");
			}
		}

		public function logout() {
			$this->user = null;
			$_SESSION['loginid'] = '';
		}

		public function toggleOrder($orderBy) {
			if ($this->orderBy == $orderBy)	{
				if ($this->orderDirection == 'asc') {
					$this->orderDirection = 'desc';
				} else {
					$this->orderDirection = 'asc';
				}
			} else {
				$this->orderDirection = 'asc';
			}
			$this->orderBy = $orderBy;

			$_SESSION['orderby'] = $this->orderBy;
			$_SESSION['orderdirection'] = $this->orderDirection;
		}

		public function getOrdering() {
			return array($this->orderBy, $this->orderDirection);
		}

		public function getTasks() {
			$this->error = '';
			$tasks = array();

			if (!$this->user) {
				$this->error = "User not specified. Unable to get task.";
				return $this->error;
			}

			if (! $this->mysqli) {
				$this->error = "No connection to database.";
				return array($tasks, $this->error);
			}

			$orderByEscaped = $this->mysqli->real_escape_string($this->orderBy);
			$orderDirectionEscaped = $this->mysqli->real_escape_string($this->orderDirection);
			$userIDEscaped = $this->mysqli->real_escape_string($this->user->userID);

			$sql = "SELECT * FROM tasks WHERE userID = $userIDEscaped ORDER BY $orderByEscaped $orderDirectionEscaped";
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

			if (!$this->user) {
				$this->error = "User not specified. Unable to get task.";
				return $this->error;
			}

			if (! $this->mysqli) {
				$this->error = "No connection to database.";
				return array($task, $this->error);
			}

			if (! $id) {
				$this->error = "No id specified for task to retrieve.";
				return array($task, $this->error);
			}

			$idEscaped = $this->mysqli->real_escape_string($id);
			$userIDEscaped = $this->mysqli->real_escape_string($this->user->userID);

			$sql = "SELECT * FROM tasks WHERE userID = $userIDEscaped AND id = '$idEscaped'";
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

			if (!$this->user) {
				$this->error = "User not specified. Unable to add task.";
				return $this->error;
			}

			$firstName = $data['firstName'];
			$lastName = $data['lastName'];
			$email = $data['email'];

			if (! $firstName) {
				$this->error = "No firstName found. A first name is required.";
				return $this->error;
			}

			if (! $lastName) {
				$this->error = "No last name found. A last name is required.";
				return $this->error;
			}

			if (! $email) {
				$this->error = "No email found. An email is required.";
				return $this->error;
			}

			$firstEscaped = $this->mysqli->real_escape_string($title);
			$lastEscaped = $this->mysqli->real_escape_string($category);
			$emailEscaped = $this->mysqli->real_escape_string($description);
			//$userIDEscaped = $this->mysqli->real_escape_string($this->user->userID);

			$sql = "INSERT INTO client (firstName, lastName, email) VALUES ('$firstEscaped', '$lastEscaped', '$emailEscaped')";

			if (! $result = $this->mysqli->query($sql)) {
				$this->error = $this->mysqli->error;
			}

			return $this->error;
		}

		/*public function updateTaskCompletionStatus($id, $status) {
			$this->error = "";

			if (!$this->user) {
				$this->error = "User not specified. Unable to update task completion status.";
				return $this->error;
			}

			$completedDate = 'null';
			if ($status == 'completed') {
				$completedDate = 'NOW()';
			}

			if (!$id) {
				$this->error = "No task was specified to change completion status.";
			} else {
				$idEscaped = $this->mysqli->real_escape_string($id);
				//$userIDEscaped = $this->mysqli->real_escape_string($this->user->userID);
				$sql = "UPDATE tasks SET completedDate = $completedDate WHERE userID = $userIDEscaped AND id = '$idEscaped'";
				if (! $result = $this->mysqli->query($sql) ) {
					$this->error = $this->mysqli->error;
				}
			}

			return $this->error;
		}*/

		/*public function updateTask($data) {
			$this->error = '';

			if (!$this->user) {
				$this->error = "User not specified. Unable to update task.";
				return $this->error;
			}

			if (! $this->mysqli) {
				$this->error = "No connection to database. Unable to update task.";
				return $this->error;
			}

			$id = $data['id'];
			if (! $id) {
				$this->error = "No id specified for task to update.";
				return $this->error;
			}

			$title = $data['title'];
			if (! $title) {
				$this->error = "No title found for task to update. A title is required.";
				return $this->error;
			}

			$description = $data['description'];
			$category = $data['category'];

			$idEscaped = $this->mysqli->real_escape_string($id);
			$titleEscaped = $this->mysqli->real_escape_string($title);
			$descriptionEscaped = $this->mysqli->real_escape_string($description);
			$categoryEscaped = $this->mysqli->real_escape_string($category);
			$userIDEscaped = $this->mysqli->real_escape_string($this->user->userID);
			$sql = "UPDATE tasks SET title='$titleEscaped', description='$descriptionEscaped', category='$categoryEscaped' WHERE userID = $userIDEscaped AND id = $idEscaped";
			if (! $result = $this->mysqli->query($sql) ) {
				$this->error = $this->mysqli->error;
			}

			return $this->error;
		}*/

		public function deleteTask($id) {
			$this->error = '';

			if (!$this->user) {
				$this->error = "User not specified. Unable to delete task.";
				return $this->error;
			}

			if (! $this->mysqli) {
				$this->error = "No connection to database. Unable to delete task.";
				return $this->error;
			}

			if (! $id) {
				$this->error = "No id specified for task to delete.";
				return $this->error;
			}

			$idEscaped = $this->mysqli->real_escape_string($id);
			$userIDEscaped = $this->mysqli->real_escape_string($this->user->userID);
			$sql = "DELETE FROM tasks WHERE userID = $userIDEscaped AND id = $idEscaped";
			if (! $result = $this->mysqli->query($sql) ) {
				$this->error = $this->mysqli->error;
			}

			return $this->error;
		}


	}

?>

<?php

	class accountsModel {
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

		public function getClients() {
			$this->error = '';
			$tasks = array();

			if (! $this->mysqli) {
				$this->error = "No connection to database.";
				return array($tasks, $this->error);
			}

			$orderByEscaped = $this->mysqli->real_escape_string($this->orderBy);
			$orderDirectionEscaped = $this->mysqli->real_escape_string($this->orderDirection);
			$sql = "SELECT * FROM client";
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

		public function getClient($id) {
			$this->error = '';
			$task = null;

			if (! $this->mysqli) {
				$this->error = "No connection to database.";
				return array($task, $this->error);
			}

			if (! $id) {
				$this->error = "No id specified for client to retrieve.";
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

		public function addClient($data) {
			$this->error = '';

			$firstName = $data['firstName'];
			$lastName = $data['lastName'];
			$email= $data['email'];

			if (! $firstName ) {
				$this->error = "No first name given. Please enter client's first name.";
				return $this->error;
			}

			if (! $lastName ) {
				$this->error = "No last name given. Please enter client's last name.";
				return $this->error;
			}

			if (! $email) {
				$this->error = "No email given. Please enter client's email.";
				return $this->error;
			}

			$firstEscaped = $this->mysqli->real_escape_string($firstName);
			$lastEscaped = $this->mysqli->real_escape_string($lastName);
			$emailEscaped = $this->mysqli->real_escape_string($email);

			$sql = "INSERT INTO client (firstName, email, lastName) VALUES ('$firstEscaped', '$emailEscaped', '$lastEscaped')";

			if (! $result = $this->mysqli->query($sql)) {
				$this->error = $this->mysqli->error;
			}

			return $this->error;
		}

		public function updateClient($data) {
			$this->error = '';

			if (! $this->mysqli) {
				$this->error = "No connection to database.";
				return $this->error;
			}

			$id = $data['id'];
			if (! $id) {
				$this->error = "No id specified for client to update.";
				return $this->error;
			}

			$firstName = $data['firstName'];
			$lastName = $data['lastName'];
			$email= $data['email'];

			if (! $firstName ) {
				$this->error = "No first name given. Please enter client's first name.";
				return $this->error;
			}

			if (! $lastName ) {
				$this->error = "No last name given. Please enter client's last name.";
				return $this->error;
			}

			if (! $email) {
				$this->error = "No email given. Please enter client's email.";
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

		public function deleteClient($id) {
			$this->error = '';

			if (! $this->mysqli) {
				$this->error = "No connection to database.";
				return $this->error;
			}

			if (! $id) {
				$this->error = "No id specified for client to delete.";
				return $this->error;
			}

			$idEscaped = $this->mysqli->real_escape_string($id);
			$sql = "DELETE FROM client WHERE id = $idEscaped";
			if (! $result = $this->mysqli->query($sql) ) {
				$this->error = $this->mysqli->error;
			}

			return $this->error;
		}

		public function getAccounts($id) {
			$this->error = '';
			$accounts = array();

			if (! $this->mysqli) {
				$this->error = "No connection to database.";
				return array($accounts, $this->error);
			}

			if (! $id) {
				$this->error = "No ID specified";
				return array($accounts, $this->error);
			}

			$sql = "SELECT accounts.id, firstName, lastName, balance, rating, accountType, clientID FROM accounts, client WHERE accounts.clientID = $id AND client.id = $id";
			// $sql = "SELECT * FROM accounts WHERE clientID = ''$idEscaped'";
			if ($result = $this->mysqli->query($sql)) {
				if ($result->num_rows > 0) {
					while ($row = $result->fetch_assoc()) {
						array_push($accounts, $row);
					}
				}
				$result->close();
			} else {
				$this->error = $mysqli->error;
			}
			return array($accounts, $this->error);
		}

		public function getAccount($id) {
			$this->error = '';
			$task = null;

			if (! $this->mysqli) {
				$this->error = "No connection to database.";
				return array($task, $this->error);
			}

			if (! $id) {
				$this->error = "No account ID specified.";
				return array($task, $this->error);
			}

			$idEscaped = $this->mysqli->real_escape_string($id);

			$sql = "SELECT * FROM accounts WHERE id = '$idEscaped'";
			if ($result = $this->mysqli->query($sql)) {
				if ($result->num_rows > 0) {
					$account = $result->fetch_assoc();
				}
				$result->close();
			} else {
				$this->error = $this->mysqli->error;
			}

			return array($account, $this->error);
		}

		public function addAccount($data) {
			$this->error = '';

			$balance = $data['balance'];
			$type = $data['accountType'];
			$clientID = $data['clientID'];

			if (! $balance ) {
				$this->error = "No balance given. Please enter an account balance.";
				return $this->error;
			}

			if (! $type ) {
				$this->error = "No type given. Please enter an account type.";
				return $this->error;
			}

			if ((int)$balance <= 1000) {
				$rating = 'F';
			}
			else if ((int)$balance > 1000 && (int)$balance <= 5000) {
				$rating = 'D';
			}
			else if ((int)$balance > 5000 && (int)$balance <= 10000) {
				$rating = 'C';
			}
			else if ((int)$balance > 10000 && (int)$balance <= 20000) {
				$rating = 'B';
			}
			else if ((int)$balance > 20000) {
				$rating = 'A';
			}

			$balanceEscaped = $this->mysqli->real_escape_string($balance);
			$typeEscaped = $this->mysqli->real_escape_string($type);
			$ratingEscaped = $this->mysqli->real_escape_string($rating);
			$sql = "INSERT INTO accounts (balance, accountType, rating, clientID) VALUES ('$balanceEscaped', '$typeEscaped', '$ratingEscaped', '$clientID')";

			if (! $result = $this->mysqli->query($sql)) {
				$this->error = $this->mysqli->error;
			}

			return $this->error;

		}

		public function deleteAccount($id) {
			$this->error = '';

			if (! $this->mysqli) {
				$this->error = "No connection to database.";
				return $this->error;
			}

			if (! $id) {
				$this->error = "No id specified for account to delete.";
				return $this->error;
			}

			$idEscaped = $this->mysqli->real_escape_string($id);
			$sql = "DELETE FROM accounts WHERE id = $idEscaped";
			if (! $result = $this->mysqli->query($sql) ) {
				$this->error = $this->mysqli->error;
				return $this->error;
			}
			else {
				return $idEscaped;
			}
		}

		public function updateAccount($data) {
			$this->error = '';

			if (! $this->mysqli) {
				$this->error = "No connection to database.";
				return $this->error;
			}

			$id = $data['id'];
			if (! $id) {
				$this->error = "No id specified for account to update.";
				return $this->error;
			}

			$balance = $data['balance'];
			$type = $data['accountType'];
			$rating = '';

			if (! $balance ) {
				$this->error = "No balance given. Please enter an account balance.";
				return $this->error;
			}

			if (! $type ) {
				$this->error = "No account type given. Please enter an account type.";
				return $this->error;
			}

			if ((int)$balance <= 1000) {
				$rating = 'F';
			}
			else if ((int)$balance > 1000 && (int)$balance <= 5000) {
				$rating = 'D';
			}
			else if ((int)$balance > 5000 && (int)$balance <= 10000) {
				$rating = 'C';
			}
			else if ((int)$balance > 10000 && (int)$balance <= 20000) {
				$rating = 'B';
			}
			else if ((int)$balance >= 20000) {
				$rating = 'A';
			}

			$idEscaped = $this->mysqli->real_escape_string($id);
			$balanceEscaped = $this->mysqli->real_escape_string($balance);
			$typeEscaped = $this->mysqli->real_escape_string($type);
			$ratingEscaped = $this->mysqli->real_escape_string($rating);
			$sql = "UPDATE accounts SET balance='$balanceEscaped', accountType='$typeEscaped', rating='$ratingEscaped' WHERE id = $idEscaped";
			if (! $result = $this->mysqli->query($sql) ) {
				$this->error = $this->mysqli->error;
				return $this->error;
			}
			else {
				return $id;
			}


		}


	}

?>

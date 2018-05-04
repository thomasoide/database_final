<?php

	class accountsViews {
		private $stylesheet = 'accounts.css';
		private $pageTitle = 'Accounts App';

		public function __construct() {

		}

		public function __destruct() {

		}

		public function accountListView($client, $accounts, $message = '') {
			$firstName = $client['firstName'];
			$lastName = $client['lastName'];
			$body = "<a class='back' href='index.php'>Back to Clients</a>";
			$body .= "<h1>Accounts: $firstName $lastName</h1>\n";

			if ($message) {
				$body .= "<p class='message'>$message</p>\n";
			}

			$body .= "<p><a  class='btn btn-primary' href='index.php?view=accountform'>+ Add Account</a></p>\n";

			if (count($accounts) < 1) {
				// echo "$accounts";
				$body .= "<p class='message'>There are no accounts to display.</p>\n";
				return $this->page($body);
			}

			$body .= "<table class='table table-striped'>\n";
			$body .= "<tr><th>delete</th><th>edit</th>";

			$columns = array(array('name' => 'clientID', label => 'Client ID'),
							 array('name' => 'id', label => 'Account ID'),
               array('name' => 'balance', label => 'Balance'),
               array('name' => 'rating', label => 'Rating'),
               array('name' => 'accountType', label => 'Account Type'));

			foreach ($columns as $column) {
				$name = $column['name'];
				$label = $column['label'];
				$body .= "<th>$label</th>";
			}

			for ($row = 0; $row < count($accounts); $row++){

				$clientID = $accounts[$row]['clientID'];
				$id = $accounts[$row]['id'];
				$balance = $accounts[$row]['balance'];
				$rating = $accounts[$row]['rating'];
				$type = $accounts[$row]['accountType'];

				$body .= "<tr>";
				$body .= "<td><form action='index.php' method='post'><input type='hidden' name='action' value='deleteAccount' /><input type='hidden' name='id' value='$id' /><input type='submit' value='Delete' class='btn btn-secondary'></form></td>";
				$body .= "<td><form action='index.php' method='post'><input type='hidden' name='action' value='editAccount' /><input type='hidden' name='id' value='$id' /><input type='submit' value='Edit' class='btn btn-secondary'></form></td>";
				$body .= "<td>$clientID</td><td>$id</td><td>$balance</td><td>$rating</td><td>$type</td>";
				$body .= "</tr>\n";
			}
			$body .= "</table>\n";

			return $this->page($body);
		}

		public function accountFormView($client, $data = null, $message = '') {
			$firstName = '';
			$lastName = '';
			$balance = 0;
			$rating = '';
			$type = '';
			$selected = array('Retirement' => '', 'Investment' => '', 'Insurance' => '', 'default' => '');

			if ($client) {
				$firstName = $client['firstName'];
				$lastName = $client['lastName'];
				$id = $client['id'];
			}

			if ($data) {
				$balance = $data['balance'];
				$type = $data['accountType'] ? $data['accountType'] : 'default';
				$accountID = $data['id'];
				$selected[$type] = 'selected';
			}
			else {
				$selected['default'] = 'selected';
			}

			$html = <<<EOT1
<!DOCTYPE html>
<html>
<head>
<title>Account</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="accounts.css">
</head>
<body>
<h1>Edit/Add Account</h1>
EOT1;

			if ($message) {
				$html .= "<p class='message'>$message</p>\n";
			}

			$html .= "<form action='index.php' method='POST'>";

			if ($id || $accountID) {
				$html .= "<input type='hidden' name='action' value='updateAccount' />";
				$html .= "<input type='hidden' name='id' value='$accountID' />";
			}
			else {
				$html .= "<input type='hidden' name='action' value='addAccount' />";
				$html .= "<input type='hidden' name='id' value='$id' />";
			}

			$html .= <<<EOT2
	<p>Balance<br />
	<input type="number" step="any" name="balance" value="$balance" placeholder="Account Balance"></p>

	<p>Account Type<br />
	<select name="accountType">
		<option value="default" {$selected['default']}></option>
		<option value="Retirement" {$selected['Retirement']}>Retirement</option>
		<option value="Investment" {$selected['Investment']}>Investment</option>
		<option value="Insurance" {$selected['Insurance']}>Insurance</option>
	</select>

	<input type="number" name="clientID" value"" placeholder="Enter Client ID">

	<input type="submit" name='submit' value="Submit" class='btn btn-secondary'> <input type="submit" name='cancel' value="Cancel" class='btn btn-secondary'>
</form>
</body>
</html>
EOT2;

			print $html;
		}

		public function clientListView($clients, $message = '') {
			$body = "<h1>Clients</h1>\n";

			if ($message) {
				$body .= "<p class='message'>$message</p>\n";
			}

			$body .= "<p><a class='btn btn-primary' href='index.php?view=clientform'>+ Add Client</a></p>\n";

			if (count($clients) < 1) {
				$body .= "<p>No tasks to display!</p>\n";
				return $body;
			}

			$body .= "<table class='table table-striped'>\n";
			$body .= "<tr><th>Delete</th><th>Edit</th><th>Accounts</th>";

			$columns = array(
               array('name' => 'firstName', label => 'First Name'),
               array('name' => 'lastName', label => 'Last Name'),
               array('name' => 'email', label => 'Email'),
               );

			// geometric shapes in unicode
			// http://jrgraphix.net/r/Unicode/25A0-25FF
			foreach ($columns as $column) {
				$name = $column['name'];
				$label = $column['label'];
				$body .= "<th>$label</th>";
			}

			foreach ($clients as $client) {
				$id = $client['id'];
				$firstName = $client['firstName'];
				$lastName = $client['lastName'];
				$email = $client['email'];
				$clientSince = $client['clientSince'];

				$body .= "<tr>";
				$body .= "<td><form action='index.php' method='post'><input type='hidden' name='action' value='delete' /><input type='hidden' name='id' value='$id' /><input type='submit' value='Delete' class='btn btn-secondary'></form></td>";
				$body .= "<td><form action='index.php' method='post'><input type='hidden' name='action' value='edit' /><input type='hidden' name='id' value='$id' /><input type='submit' value='Edit' class='btn btn-secondary' ></form></td>";
				$body .= "<td><form action='index.php' method='post'><input type='hidden' name='action' value='viewAccounts' /><input type='hidden' name='id' value='$id' /><input type='submit' value='View Accounts' class='btn btn-secondary'></form></td>";
				$body .= "<td>$firstName</td><td>$lastName</td><td>$email</td>";
				$body .= "</tr>\n";
			}
			$body .= "</table>\n";

			return $this->page($body);
		}

		public function clientFormView($data = null, $message = '') {
			$firstName = '';
			$lastName = '';
			$email = '';
			if ($data) {
				$firstName = $data['firstName'];
				$lastName = $data['lastName'];
				$email = $data['email'];
			}

			$html = <<<EOT1
<!DOCTYPE html>
<html>
<head>
<title>Add a client</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="accounts.css">
</head>
<body>
<h1>Add a client</h1>
EOT1;

			if ($message) {
				$html .= "<p class='message'>$message</p>\n";
			}

			$html .= "<form action='index.php' method='POST'>";

			if ($data['id']) {
				$html .= "<input type='hidden' name='action' value='update' />";
				$html .= "<input type='hidden' name='id' value='{$data['id']}' />";
			}
			else {
				$html .= "<input type='hidden' name='action' value='add' />";
			}

			$html .= <<<EOT2
  <p>First Name<br />
  	<input type="text" name="firstName" value="$firstName" placeholder="First Name" maxlength="255" size="80" ></p>
  </p>

  <p>Last Name<br />
  <input type="text" name="lastName" value="$lastName" placeholder="Last Name" maxlength="255" size="80"></p>

	<p>Email<br />
  <input type="text" name="email" value="$email" placeholder="Email" maxlength="255" size="80"></p>

  <input type="submit" name='submit' value="Submit" class='btn btn-secondary'> <input type="submit" name='cancel' value="Cancel" class='btn btn-secondary'>
</form>
</body>
</html>
EOT2;

			print $html;
		}

		public function errorView($message) {
			$body = "<h1>Tasks</h1>\n";
			$body .= "<p>$message</p>\n";

			return $this->page($body);
		}

		private function page($body) {
			$html = <<<EOT
<!DOCTYPE html>
<html>
<head>
<title>{$this->pageTitle}</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="{$this->stylesheet}">
</head>
<body>
$body
<p>&copy; 2018 Leonard Tocco, Felipe Costa, Thomas Oide. All rights reserved.</p>
</body>
</html>
EOT;
			return $html;
		}

}

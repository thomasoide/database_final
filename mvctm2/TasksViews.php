<?php

	class TasksViews {
		private $stylesheet = 'taskmanager.css';
		private $pageTitle = 'Accounts';

		public function __construct() {

		}

		public function __destruct() {

		}

		public function taskListView($tasks, /*$orderBy = 'title', $orderDirection = 'asc',*/ $message = '') {
			$body = "<h1>Accounts</h1>\n";

			if ($message) {
				$body .= "<p class='message'>$message</p>\n";
			}

			$body .= "<p><a class='taskButton' href='index.php?view=taskform'>+ Add Account</a></p>\n";

			if (count($tasks) < 1) {
				$body .= "<p>No tasks to display!</p>\n";
				return $body;
			}

			$body .= "<table>\n";
			$body .= "<tr><th>delete</th><th>edit</th>";

			$columns = array(array('name' => 'id', label=> 'Client ID'),
               array('name' => 'firstName', label => 'First Name'),
               array('name' => 'lastName', label => 'Last Name'),
               array('name' => 'email', label => 'Email'),
               array('name' => 'clientSince', label => 'Client Since'));

			// geometric shapes in unicode
			// http://jrgraphix.net/r/Unicode/25A0-25FF
			foreach ($columns as $column) {
				$name = $column['name'];
				$label = $column['label'];
				if ($name == $orderBy) {
					if ($orderDirection == 'asc') {
						$label .= " &#x25BC;";  // ▼
					} else {
						$label .= " &#x25B2;";  // ▲
					}
				}
				$body .= "<th><a class='order' href='index.php?orderby=$name'>$label</a></th>";
			}

			foreach ($tasks as $task) {
				$id = $task['id'];
				$firstName = $task['firstName'];
				$lastName = $task['lastName'];
				$email = $task['email'];
				$clientSince = $task['clientSince'];

				$body .= "<tr>";
				$body .= "<td><form action='index.php' method='post'><input type='hidden' name='action' value='delete' /><input type='hidden' name='id' value='$id' /><input type='submit' value='Delete'></form></td>";
				$body .= "<td><form action='index.php' method='post'><input type='hidden' name='action' value='edit' /><input type='hidden' name='id' value='$id' /><input type='submit' value='Edit'></form></td>";
				$body .= "<td>$id</td><td>$firstName</td><td>$lastName</td><td>$email</td><td>$clientSince</td>";
				$body .= "</tr>\n";
			}
			$body .= "</table>\n";

			return $this->page($body);
		}

		public function taskFormView($data, $message = '') {
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
<title>Task Manager</title>
<link rel="stylesheet" type="text/css" href="taskmanager.css">
</head>
<body>
<h1>Tasks</h1>
EOT1;

			if ($message) {
				$html .= "<p class='message'>$message</p>\n";
			}

			$html .= "<form action='index.php' method='POST'>";

			if ($data['id']) {
				$html .= "<input type='hidden' name='action' value='update' />";
				$html .= "<input type='hidden' name='id' value='{$data['id']}' />";
			} else {
				$html .= "<input type='hidden' name='action' value='add' />";
			}

			$html .= <<<EOT2
  <p>First Name<br />
  	<input type="text" name="First Name" value="$firstName" placeholder="First Name" maxlength="255" size="80"></p>
  </p>

  <p>Last Name<br />
  <input type="text" name="Last Name" value="$lastName" placeholder="Last Name" maxlength="255" size="80"></p>

	<p>Email<br />
  <input type="text" name="Email" value="$email" placeholder="Email" maxlength="255" size="80"></p>

	<p>Client Since<br />
  <input type="text" name="Client Since" value="2011-04-12T00:00:00.000" placeholder="Client Since" maxlength="255" size="80"></p>

  <input type="submit" name='submit' value="Submit"> <input type="submit" name='cancel' value="Cancel">
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
<link rel="stylesheet" type="text/css" href="{$this->stylesheet}">
</head>
<body>
$body
<p>&copy; 2017 Dale Musser. All rights reserved.</p>
</body>
</html>
EOT;
			return $html;
		}

}

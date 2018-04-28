<?php

	class TasksViews {
		private $stylesheet = 'taskmanager.css';
		private $pageTitle = 'Tasks';

		public function __construct() {

		}

		public function __destruct() {

		}

		public function taskListView($user, $tasks, $orderBy = 'title', $orderDirection = 'asc', $message = '') {
			$body = "<h1>Tasks for {$user->firstName} {$user->lastName}</h1>\n";

			if ($message) {
				$body .= "<p class='message'>$message</p>\n";
			}

			$body .= "<p><a class='taskButton' href='index.php?view=taskform'>+ Add Task</a> <a class='taskButton' href='index.php?logout=1'>Logout</a></p>\n";

			if (count($tasks) < 1) {
				$body .= "<p>No tasks to display!</p>\n";
				return $this->page($body);
			}

			$body .= "<table>\n";
			$body .= "<tr><th>delete</th><th>edit</th>";

			$columns = array(array('name' => 'email', label=> 'Email'),
               array('name' => 'id', label => 'Account ID'),
               array('name' => 'accountType', label => 'Account Type'),
               array('name' => 'rating', label => 'Rating'),
               array('name' => 'balance', label => 'Balance'));

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
				$addDate = $task['email'];
				$completedDate = ($task['accountType']) ? $task['accountType'] : '';
				$description = ($task['rating']) ? $task['rating'] : '';
				$category = $task['balance'];

				$body .= "<tr>";
				$body .= "<td><form action='index.php' method='post'><input type='hidden' name='action' value='delete' /><input type='hidden' name='id' value='$id' /><input type='submit' value='Delete'></form></td>";
				$body .= "<td><form action='index.php' method='post'><input type='hidden' name='action' value='edit' /><input type='hidden' name='id' value='$id' /><input type='submit' value='Edit'></form></td>";
				$body .= "<td>$addDate</td><td>$completedDate</td><td>$description</td><td>$category</td>";
				$body .= "</tr>\n";
			}
			$body .= "</table>\n";

			return $this->page($body);
		}

// 		public function taskFormView($user, $data = null, $message = '') {
// 			$category = '';
// 			$title = '';
// 			$description = '';
// 			$selected = array('personal' => '', 'school' => '', 'work' => '', 'uncategorized' => '');
// 			if ($data) {
// 				$category = $data['category'] ? $data['category'] : 'uncategorized';
// 				$title = $data['title'];
// 				$description = $data['description'];
// 				$selected[$category] = 'selected';
// 			} else {
// 				$selected['uncategorized'] = 'selected';
// 			}
//
// 			$body = "<h1>Tasks for {$user->firstName} {$user->lastName}</h1>\n";
//
// 			if ($message) {
// 				$body .= "<p class='message'>$message</p>\n";
// 			}
//
// 			$body .= "<form action='index.php' method='post'>";
//
// 			if ($data['id']) {
// 				$body .= "<input type='hidden' name='action' value='update' />";
// 				$body .= "<input type='hidden' name='id' value='{$data['id']}' />";
// 			} else {
// 				$body .= "<input type='hidden' name='action' value='add' />";
// 			}
//
// 			$body .= <<<EOT2
//   <p>Category<br />
//   <select name="category">
// 	  <option value="personal" {$selected['personal']}>personal</option>
// 	  <option value="school" {$selected['school']}>school</option>
// 	  <option value="work" {$selected['work']}>work</option>
// 	  <option value="uncategorized" {$selected['uncategorized']}>uncategorized</option>
//   </select>
//   </p>
//
//   <p>Title<br />
//   <input type="text" name="title" value="$title" placeholder="title" maxlength="255" size="80"></p>
//
//   <p>Description<br />
//   <textarea name="description" rows="6" cols="80" placeholder="description">$description</textarea></p>
//   <input type="submit" name='submit' value="Submit"> <input type="submit" name='cancel' value="Cancel">
// </form>
// EOT2;
//
// 			return $this->page($body);
// 		}


		public function loginFormView($data = null, $message = '') {
			$loginID = '';
			if ($data) {
				$loginID = $data['loginID'];
			}

			$body = "<h1>Account App</h1>\n";

			if ($message) {
				$body .= "<p class='message'>$message</p>\n";
			}

			$body .= <<<EOT
<form action='index.php' method='post'>
<input type='hidden' name='action' value='login' />
<p>User ID<br />
  <input type="text" name="loginID" value="$loginID" placeholder="login id" maxlength="255" size="80"></p>
<p>Password<br />
  <input type="password" name="password" value="" placeholder="password" maxlength="255" size="80"></p>
  <input type="submit" name='submit' value="Login">
</form>
EOT;

			return $this->page($body);
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
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
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

?>

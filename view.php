<?php

  class views {
    private $stylesheet = 'style.css';
    private $title = 'Financial App'

    public function __construct() {

		}

		public function __destruct() {

		}

    public function accountView($client, $accounts, $orderBy = 'balance', $orderDirection = 'asc', $message = '') {
      $body = "<h1>Accounts for {$client->firstName} {$client->lastName}</h1>\n";

      if ($message) {
        $body .= "<p class='message'>$message</p>\n";
      }

      $body .= "<p><a class='taskButton' href='index.php?view=taskform'>+ Add Task</a> <a class='taskButton' href='index.php?logout=1'>Logout</a></p>\n";

      if (count($accounts) < 1) {
				$body .= "<p>Client has no accounts.</p>\n";
				return $this->page($body);
			}

      $body .= "<table>\n";
			$body .= "<tr><th>Delete</th><th>Edit</th>";
      $columns = array(array('name' => 'email', label=> 'Email'),
               array('name' => 'account_id', label => 'Account ID'),
               array('name' => 'account_type', label => 'Account Type'),
               array('name' => 'rating', label => 'Rating'),
               array('name' => 'balance', label => 'Balance')
      );

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

      foreach ($accounts as $account) {
				$id = $account['account_id'];
        $email = $account['email'];
				$account_type = ($account['account_type']) ? $account['account_type'] : '';
				$rating = ($account['rating']) ? $task['rating'] : '';
        $balance = ($account['balance']) ? $task['balance'] : '';

				$body .= "<tr>";
				$body .= "<td><form action='index.php' method='post'><input type='hidden' name='action' value='delete' /><input type='hidden' name='id' value='$id' /><input type='submit' value='Delete'></form></td>";
				$body .= "<td><form action='index.php' method='post'><input type='hidden' name='action' value='edit' /><input type='hidden' name='id' value='$id' /><input type='submit' value='Edit'></form></td>";
				$body .= "<td>$id</td><td>$email</td><td>$account_type</td><td>$rating</td><td>$balance</td>";
				$body .= "</tr>\n";
			}
			$body .= "</table>\n";

      return $this->page($body);
    }

    public function taskFormView($user, $data = null, $message = '') {
			$category = '';
			$title = '';
			$description = '';
			$selected = array('personal' => '', 'school' => '', 'work' => '', 'uncategorized' => '');
			if ($data) {
				$category = $data['category'] ? $data['category'] : 'uncategorized';
				$title = $data['title'];
				$description = $data['description'];
				$selected[$category] = 'selected';
			} else {
				$selected['uncategorized'] = 'selected';
			}

			$body = "<h1>Accounts for {$client->firstName} {$client->lastName}</h1>\n";

			if ($message) {
				$body .= "<p class='message'>$message</p>\n";
			}

			$body .= "<form action='index.php' method='post'>";

			if ($data['id']) {
				$body .= "<input type='hidden' name='action' value='update' />";
				$body .= "<input type='hidden' name='id' value='{$data['id']}' />";
			} else {
				$body .= "<input type='hidden' name='action' value='add' />";
			}

			$body .= <<<EOT2
  <p>Category<br />
  <select name="category">
	  <option value="personal" {$selected['personal']}>personal</option>
	  <option value="school" {$selected['school']}>school</option>
	  <option value="work" {$selected['work']}>work</option>
	  <option value="uncategorized" {$selected['uncategorized']}>uncategorized</option>
  </select>
  </p>

  <p>Title<br />
  <input type="text" name="title" value="$title" placeholder="title" maxlength="255" size="80"></p>

  <p>Description<br />
  <textarea name="description" rows="6" cols="80" placeholder="description">$description</textarea></p>
  <input type="submit" name='submit' value="Submit"> <input type="submit" name='cancel' value="Cancel">
</form>
EOT2;

			return $this->page($body);
		}

    public function errorView($message) {
			$body = "<h1>Accounts</h1>\n";
			$body .= "<p>$message</p>\n";

			return $this->page($body);
		}

    private function page($body) {
			$html = <<<EOT
      <!DOCTYPE html>
      <html>
      <head>
      <title>{$this->pageTitle}</title>
      <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
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
?>

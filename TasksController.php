<?php
	require('TasksModel.php');
	require('TasksViews.php');

	class TasksController {
		private $model;
		private $views;

		private $orderBy = '';
		private $view = '';
		private $action = '';
		private $message = '';
		private $data = array();
		private $client = array();

		public function __construct() {
			$this->model = new TasksModel();
			$this->views = new TasksViews();

			$this->view = $_GET['view'] ? $_GET['view'] : 'tasklist';
			$this->action = $_POST['action'];
		}

		public function __destruct() {
			$this->model = null;
			$this->views = null;
		}

		public function run() {
			if ($error = $this->model->getError()) {
				print $views->errorView($error);
				exit;
			}

			switch($this->action) {
				case 'delete':
					$this->handleDelete();
					break;
				case 'add':
					$this->handleAddTask();
					break;
				case 'edit':
					$this->handleEditTask();
					break;
				case 'update':
					$this->handleUpdateTask();
					break;
				case 'addAccount':
					$this->handleAddAccount();
					break;
				case 'editAccount':
					$this->handleEditAccount();
					break;
				case 'updateAccount':
					$this->handleUpdateAccount();
					break;
				case 'deleteAccount':
					$this->handleDeleteAccount();
					break;
				case 'viewAccounts':
					$this->viewAccounts();
					break;
			}

			switch($this->view) {
				case 'accountlist':
					print $this->views->accountListView($this->client, $this->data, $this->message);
					break;
				case 'accountform':
					// echo '<pre>', var_dump($this->client), '</pre>';
					print $this->views->accountFormView($this->client, $this->data, $this->message);
					break;
				case 'taskform':
					print $this->views->taskFormView($this->data, $this->message);
					break;
				default: // 'tasklist'
					list($tasks, $error) = $this->model->getTasks();
					if ($error) {
						$this->message = $error;
					}
					print $this->views->taskListView($tasks, $this->message);
			}

		}

		private function handleDelete() {
			if ($error = $this->model->deleteTask($_POST['id'])) {
				$this->message = $error;
			}
			$this->view = 'tasklist';
		}

		private function handleAddTask() {
			if ($_POST['cancel']) {
				$this->view = 'tasklist';
				return;
			}

			$error = $this->model->addTask($_POST);
			if ($error) {
				$this->message = $error;
				$this->view = 'taskform';
				$this->data = $_POST;
			}
		}

		private function handleEditTask() {
			list($task, $error) = $this->model->getTask($_POST['id']);
			if ($error) {
				$this->message = $error;
				$this->view = 'tasklist';
				return;
			}
			$this->data = $task;
			$this->view = 'taskform';
		}

		private function handleUpdateTask() {
			if ($_POST['cancel']) {
				$this->view = 'tasklist';
				return;
			}

			if ($error = $this->model->updateTask($_POST)) {
				$this->message = $error;
				$this->view = 'taskform';
				$this->data = $_POST;
				return;
			}

			$this->view = 'tasklist';
		}

		private function viewAccounts() {
			list($accounts, $error) = $this->model->getAccounts($_POST['id']);
			list($client, $error) = $this->model->getTask($_POST['id']);
			if ($error) {
				$this->message = $error;
			}
			$this->client = $client;
			$this->data = $accounts;
			$this->view = 'accountlist';
		}

		private function handleAddAccount() {
			if ($_POST['cancel']) {
				$this->view = 'accountlist';
				return;
			}

			$error = $this->model->addAccount($_POST);

			if ($error) {
				$this->client = $client;
				$this->message = $error;
				$this->data = $_POST;
				$this->view = 'accountform';
			}
		}

		private function handleEditAccount() {
			list($account, $error) = $this->model->getAccount($_POST['id']);
			list($client, $error) = $this->model->getTask($account['clientID']);
			if ($error) {
				$this->message = $error;
				$this->view = 'accountlist';
				return;
			}
			$this->client = $client;
			$this->data = $account;
			$this->view = 'accountform';
		}

		private function handleUpdateAccount() {
			if ($_POST['cancel']) {
				$this->view = 'accountlist';
				return;
			}

			$message = $this->model->updateAccount($_POST);

			if (is_numeric($message) == False) {
				$this->message = $message;
				$this->view = 'accountform';
				$this->data = $_POST;
				return;
			}
			else {
				list($account, $error) = $this->model->getAccount((int)$message);
				list($accounts, $error) = $this->model->getAccounts($account['clientID']);
				list($client, $error) = $this->model->getTask($account['clientID']);
				$this->client = $client;
				$this->data = $accounts;
				$this->view = 'accountlist';
			}
		}

		private function handleDeleteAccount() {

			$message = $this->model->deleteAccount($_POST['id']);

			if (is_numeric($message) == False) {
				$this->message = $message;
			}

			list($account, $error) = $this->model->getAccount((int)$message);
			list($accounts, $error) = $this->model->getAccounts($account['clientID']);
			list($client, $error) = $this->model->getTask($account['clientID']);
			$this->client = $client;
			$this->data = $accounts;
			$this->view = 'accountlist';
		}
	}

?>

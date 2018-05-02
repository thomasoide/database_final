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
				case 'updateAccount':
					$this->handleUpdateAccount();
					break;
				case 'deleteAccount':
					$this->handleDeleteAccount();
					break;
			}

			switch($this->view) {
				case 'accountlist':
					list($accounts, $error) = $this->model->getAccounts();
					if ($error) {
						$this->message = $error;
					}
					print $this->views->accountListView($accounts, $this->message);
					break;
				case 'accountform':
					print $this->views->accountFormView($this->data, $this->message);
					break;
				case 'taskform':
					print $this->views->taskFormView($this->data, $this->message);
					break;
				default: // 'tasklist'
					// list($orderBy, $orderDirection) = $this->model->getOrdering();
					list($tasks, $error) = $this->model->getTasks();
					if ($error) {
						$this->message = $error;
					}
					print $this->views->taskListView($tasks, /*$orderBy, $orderDirection,*/ $this->message);
			}

		}

		// private function processOrderby() {
		// 	if ($_GET['orderby']) {
		// 		$this->model->toggleOrder($_GET['orderby']);
		// 	}
		// }

		private function handleDelete() {
			if ($error = $this->model->deleteTask($_POST['id'])) {
				$this->message = $error;
			}
			$this->view = 'tasklist';
		}

		// private function handleSetCompletionStatus($status) {
		// 	if ($error = $this->model->updateTaskCompletionStatus($_POST['id'], $status)) {
		// 		$this->message = $error;
		// 	}
		// 	$this->view = 'tasklist';
		// }

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

		private function handleAddAccount() {
			if ($_POST['cancel']) {
				$this->view = 'accountlist';
				return;
			}

			$error = $this->model->addTask($_POST);
			if ($error) {
				$this->message = $error;
				$this->view = 'accountform';
				$this->data = $_POST;
			}
		}

		private function handleUpdateAccount() {
			if ($_POST['cancel']) {
				$this->view = 'accountlist';
				return;
			}

			if ($error = $this->model->updateAccount($_POST)) {
				$this->message = $error;
				$this->view = 'accountform';
				$this->data = $_POST;
				return;
			}

			$this->view = 'accountlist';
		}

		private function handleDeleteAccount() {
			if ($error = $this->model->deleteAccount($_POST['id'])) {
				$this->message = $error;
			}
			$this->view = 'accountlist';
		}
	}



?>

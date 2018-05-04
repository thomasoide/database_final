<?php
	// Model-View-Controller implementation of Task Manager

	require('accountsController.php');

	$controller = new accountsController();
	$controller->run();
?>

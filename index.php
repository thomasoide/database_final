<?php
	// Model-View-Controller implementation of Task Manager

	require('controller.php');

	$controller = new controller();
	$controller->run();
?>

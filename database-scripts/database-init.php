<?php
	# display errors. thanks
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	
	# generic mysql database setup. creates aceTraining database
	$conn = mysqli_connect("localhost", "root", "root");
	$sql = "CREATE DATABASE aceTraining";
	mysqli_query ($conn,$sql);
	$conn = mysqli_connect("localhost", "root", "root", "aceTraining");
	$sql = "CREATE TABLE IF NOT EXISTS users (
		id INT NOT NULL AUTO_INCREMENT,
		username VARCHAR(100) NOT NULL,
		email VARCHAR(100) NOT NULL,
		password VARCHAR(255) NOT NULL,
		usertype VARCHAR(7) NOT NULL,
		approved BOOLEAN,
		PRIMARY KEY (id)
		)";
	mysqli_query ($conn,$sql) or die(mysqli_error($conn));
	
	# mongoDB setup
	phpinfo();
	
?>
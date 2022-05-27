<?php
	# display errors. thanks
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	
	# generic mysql database setup. creates aceTraining database
	$conn = mysqli_connect("localhost", "root", "root");
	$sql = "CREATE DATABASE aceTraining";
	mysqli_query ($conn,$sql);

	# create users table
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

	# create courses table
	$sql = "CREATE TABLE IF NOT EXISTS courses (
		id INT NOT NULL AUTO_INCREMENT,
		coursename VARCHAR(100) NOT NULL,
		coursedesc VARCHAR(255),
		PRIMARY KEY (id)
		)";
	mysqli_query ($conn,$sql) or die(mysqli_error($conn));

	# create coursesUsers table
	$sql = "CREATE TABLE IF NOT EXISTS coursesUsers (
		userid INT NOT NULL,
		courseid INT NOT NULL,
		PRIMARY KEY (userid, courseid)
		)";
	mysqli_query ($conn,$sql) or die(mysqli_error($conn));

	# create assignments table
	$sql = "CREATE TABLE IF NOT EXISTS assignments (
		id INT NOT NULL AUTO_INCREMENT,
		courseid INT NOT NULL,
		week INT NOT NULL,
		assignmentname VARCHAR(100) NOT NULL,
		assignmentdesc VARCHAR(255),
		duedate DATETIME NOT NULL,
		PRIMARY KEY (id),
		FOREIGN KEY (courseid) REFERENCES courses(id)
		)";
	mysqli_query ($conn,$sql) or die(mysqli_error($conn));

	# create files table
	$sql = "CREATE TABLE IF NOT EXISTS files (
		id INT NOT NULL AUTO_INCREMENT,
		location VARCHAR(255) NOT NULL,
		courseid INT,
		week INT,
		assignmentid INT,
		submitterid INT NOT NULL,
		PRIMARY KEY (id),
		FOREIGN KEY (courseid) REFERENCES courses(id),
		FOREIGN KEY (assignmentid) REFERENCES assignments(id),
		FOREIGN KEY (submitterid) REFERENCES users(id)
		)";
	mysqli_query ($conn,$sql) or die(mysqli_error($conn));
	
	# mongoDB setup
	phpinfo();
	
?>
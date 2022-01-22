<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	$conn = mysqli_connect("localhost", "root", "root");
	$sql = "CREATE DATABASE aceTraining";
	mysqli_query ($conn,$sql) or die(mysqli_error($conn));
?>
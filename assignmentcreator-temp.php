<?php
session_start();
$_SESSION['loggedin'] = true;
$_SESSION['id'] = 1;
$_SESSION['username'] = "coolkid";
$_SESSION['email'] = "coolkid@coolplace.com";
$_SESSION['usertype'] = "Student";
?>

<form method="POST" action="assignmentcreator.php">
    <input type="text" name="courseid">
    <input type="text" name="week">
    <input type="submit">
</form>
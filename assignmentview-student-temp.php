<?php
session_start();
$_SESSION['loggedin'] = true;
$_SESSION['id'] = 1;
$_SESSION['username'] = "coolkid";
$_SESSION['email'] = "coolkid@coolplace.com";
$_SESSION['usertype'] = "Student";
?>

<form method="POST" action="assignmentview-student.php">
    <input type="text" name="assignmentid">
    <input type="submit">
</form>
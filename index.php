<?php
    session_start();
    require_once "includes/init.php";

    if($_SERVER["REQUEST_METHOD"] == "POST"){

        if(empty(trim($_POST["email"]))){
            $email_error = "email cannot be empty";
        } else{
            $email = trim($_POST["email"]);
        }

        if(empty(trim($_POST["password"]))){
            $password_error = "password cannot be empty";
        } else {
            $password = trim($_POST["password"]);
        }

        if (!isset($username_error) && !isset($password_error)) {
            $sql = 'SELECT id, username, email, password, usertype FROM users WHERE email = ?';
            if ($stmt = mysqli_prepare($db, $sql)) {
                $stmt->bind_param('s', $email);
                if ($stmt->execute()) {
                    $stmt->store_result();
                    if ($stmt->num_rows() == 1) {
                        //check password .S
                        $stmt->bind_result($db_id, $db_username, $db_email, $db_password, $db_usertype);
                        if ($stmt->fetch()) {
                            print_r($db_password);
                            if (password_verify($password, $db_password)) {
                                //right password, save to session variables
                                $_SESSION['loggedin'] = true;
                                $_SESSION['username'] = $db_username;
                                $_SESSION['email'] = $db_email;
                                $_SESSION['usertype'] = $db_usertype;
                            } else {
                                $login_error = "password incorrect";
                            }
                        } else {
                            $database_error = "unknown database error occurred (3)";
                        }
                    } else {
                        $login_error = "account with email not found";
                    }
                } else {
                    $database_error = "unknown database error occurred (2)";
                }
            } else {
                $database_error = "unknown database error occurred (1)";
            }

        }

    }

    //after successful login, or if already logged in, redirect to coursesview
    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
        if($_SESSION["usertype"] = "student") {
            header("location: coursesview-student.php");
            exit;
        } else {
            header("location: coursesview-tutor.php");
            exit;
        }
    }

    if (isset($username_error)) { echo $email_error; }
    if (isset($password_error)) { echo $password_error; }
    if (isset($database_error)) { echo $database_error; }
    if (isset($login_error)) { echo $login_error; }

?>

<!DOCTYPE html>
<html lang = "en">
    <head>
        <title>Ace Training Login</title>
        <meta charset = "utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link href="css/loginstyles.css" rel="stylesheet">

        <script>
            function testEmail()
                {
                    emailValidation = /^\w+([\.]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
                    // Regex taken from https://stackoverflow.com/questions/15017052/understanding-email-validation-using-javascript
                    email = document.getElementById("email").value;

                    if (emailValidation.test(email))
                        {
                            return true;
                        }

                    else
                        {
                            $("#invalidEmail").show();
                            return false;
                        }
                }
        </script>


    </head>

    <body>
        <div class="container p-5 m-5 mx-auto border bg-light text-dark needs-validation">
            <h2>Login</h2>
            <form name="login" method="post" onsubmit="return testEmail()">
                <div class="mt-3">
                    <label for="email">Email: </label>
                    <input required type="email" id="email" name="email" placeholder="Enter email"  class="form-control">


                    <div id="invalidEmail" name="invalidEmail" class="alert alert-danger" role="alert">
                        Invalid email address
                    </div>


                </div>

                <div class="mt-3">
                    <label for="password">Password: </label>
                    <input required type="password" id="password" name="password" placeholder="Enter password" class="form-control">
                </div>

                <div class="form-check mt-2 mb-3">
                    <label class="form-check-label">
                        <input type="checkbox" id="remember" name="remember" class="form-check-input"> Remember me
                    </label>
                </div>

                <button type="submit" id="submit" name="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>

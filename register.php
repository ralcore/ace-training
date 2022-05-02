<!-- dropdown -> student, tutor, admin

modify onsubmit func:
if student proceed normally
if tutor/admin add popup informing user of waiting period

if tutor/admin add "false" to field "approved" -->

<?php
    require_once "includes/init.php";

    print_r($_POST);
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        //validating email
        $exp = "/^\w+([\.]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/";
        $email_regex_result =  preg_match($exp, $_POST["email"]);
        if (!$email_regex_result) {
            $email_error = "invalid email";
        } else {
            $sql = 'SELECT id FROM users WHERE email = ?';
            if ($stmt = mysqli_prepare($db, $sql)) {
                $stmt->bind_param('s',$temp_email);
                $temp_email = trim($_POST["email"]);
                if ($stmt->execute()) {
                    $stmt->store_result();
                    if ($stmt->num_rows()>=1) {
                        $email_error = "email already taken";
                    } else {
                        $email = trim($_POST["email"]);
                    }
                } else {
                    $email_error = "unknown database error occurred";
                }
            } else {
                $email_error = "unknown database error occurred";
            }
        }

        //validating username
        if (strlen($_POST["username"]) < 3) {
            $username_error = "username is too short";
        } else {
            $sql = 'SELECT id FROM users WHERE username = ?';
            if ($stmt = mysqli_prepare($db, $sql)) {
                $stmt->bind_param('s',$temp_username);
                $temp_username = trim($_POST["username"]);
                if ($stmt->execute()) {
                    $stmt->store_result();
                    if ($stmt->num_rows()>=1) {
                        $username_error = "username already taken";
                    } else {
                        $username = trim($_POST["username"]);
                    }
                } else {
                    $username_error = "unknown database error occurred";
                }
            } else {
                $username_error = "unknown database error occurred";
            }
        }

        //validating password
        if (strlen($_POST["password"]) < 8) {
            $password_error = "password is too short";
        } else if ($_POST["password"] !== $_POST["confirmPassword"]) {
            $password_error = "passwords do not match";
        } else {
            $password = $_POST["password"];
        }

        //setting usertype and approval
        switch($_POST["userType"]) {
            case 0:
                $usertype = "Student";
                $approved = 1;
                break;
            case 1:
                $usertype = "Tutor";
                $approved = 0;
                break;
            case 2:
                $usertype = "Admin";
                $approved = 0;
                break;
            default:
                $usertype = "Student";
                $approved = 1;
                break;
        }

        //assuming all above are cleared...
        if (!isset($email_error) && !isset($username_error) && !isset($password_error)) {
            //if student, insert new user account into users table
            $sql = 'INSERT INTO users (username, email, password, usertype, approved) VALUES (?, ?, ?, ?, ?)';
            if ($stmt = mysqli_prepare($db, $sql)) {
                $stmt->bind_param('ssssi', $username, $email, $temp_password, $usertype, $approved);
                $temp_password = password_hash($password, PASSWORD_DEFAULT);
                if ($stmt->execute()) {
                    header("location: index.php");
                } else {
                    $register_error = "unknown database error occurred2";
                }
            } else {
                $register_error = "unknown database error occurred1";
            } 
        }
        if (isset($email_error)) { echo $email_error; };
        if (isset($password_error)) echo $password_error;
        if (isset($register_error)) { echo $register_error; };
        if (isset($username_error)) { echo $username_error; };
    }

?>

<!DOCTYPE html>
<html lang = "en">
    <head>
        <title>Ace Training Registration</title>
        <meta charset = "utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
        <link href="css/loginstyles.css" rel="stylesheet">
        <script src="js/register.js"></script>
    </head>

    <body>
        <div class="container p-5 m-5 mx-auto border bg-light text-dark needs-validation">
            <h2>Register</h2> 
            <form name="register" method="post" onsubmit="return testEmail()">

                <div class="mt-3">
                    <label for="username">Username: </label>
                    <input required type="text" id="username" name="username" placeholder="Enter username" class="form-control">
                </div>

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

                <div class="mt-3">
                    <label for="confirmPassword">Confirm Password: </label>
                    <input required type="password" id="confirmPassword" name="confirmPassword" placeholder="Enter password" class="form-control">
                </div>

                <div class="mt-3">
                    <label for="userType">Registering as...</label>
                    <select class="mb-3 dropdown form-control" id="userType" name="userType">
                        <option value="0" selected>Student</option>
                        <option value="1">Tutor</option>
                        <option value="2">Admin</option>
                    </select>
                </div>

                <button type="submit" id="submit" name="submit" class="btn btn-primary" onclick="submitButton()">Submit</button>

                <!-- tutor approval time popup -->
                <div class="modal fade" id="tutorModal" tabindex="-1" role="dialog" aria-labelledby="tutorModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="tutorModalLabel">Tutor/Admin Approval</h5>
                            </div>
                            <div class="modal-body">
                                <p>New tutor and admin accounts require approval from an administrator before accessing the site.</p>
                                <p>If you are not able to access the site within 72 hours, please contact your system administrator.</p>
                            </div>
                            <div class="modal-footer">
                                <!-- needs fixing!! currently just closes the modal -->
                                <button type="submit" id="submit" name="submit" class="btn btn-primary" onclick="submitModal()">Okay</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
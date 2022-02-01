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
            function toggler(divId) 
            {
                $("#" + divId).toggle();
            }

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
let submitter = 'submit';
function testEmail()
    {
        emailValidation = /^\w+([\.]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        // Regex taken from https://stackoverflow.com/questions/15017052/understanding-email-validation-using-javascript
        email = document.getElementById("email").value;

        if (emailValidation.test(email))
            {
                var e = document.getElementById("userType");
                var strUser = e.value;
                //if student OR if modal already seen return true, else pop-up modal
                if (strUser == 0 || window.submitter == "modal") { 
                    return true; 
                } else {
                    var tutorModal = new bootstrap.Modal(document.getElementById('tutorModal'), {keyboard: true, focus: true})
                    tutorModal.show();
                    return false;
                }
                
            }

        else
            {
                $("#invalidEmail").show();
                return false;
            }
    }

function submitModal()
    {
        window.submitter = 'modal'
    }

function submitButton()
    {
        window.submitter = 'submit'
    }
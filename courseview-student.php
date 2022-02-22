<!DOCTYPE html>
<html>
    <head>
        <title>Course View</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="css/courseview.css">
        <link href="css/progressbar.css" rel="stylesheet">
        <script src="js/progressbar.js"></script>
    </head>

    <body>
        <div class="container-fluid" style="max-width: 640px;">
            <div class="row rounded" style="margin-top:8px">
                <div class="col-sm-12">
                    <div class="card card-body">
                        <h5 class="card-title">CSCCORE001 - Core Computing</h5>
                        <a href="#" class="card-link">Back</a>
                    </div>
                </div>
            </div>
            <div class="row rounded justify-content-between" style="margin-top:8px">
                <div class="col-sm-6">
                    <div class="card card-body" style="height:100%">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <h5 class="card-title">Lecturers:</h5>
                                <ul>
                                    <li>robot 1</li>
                                    <li>robot 2</li>
                                    <li>the robot from wall-e</li>
                                </ul>
                            </li>
                            <li class="list-group-item"><p class="card-text">introducing the fundamentals of computer science. socks not included</p></li>
                        </ul>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-body">
                            <!-- completion dial -->
                            <div class="progress mx-auto" data-value='53'>
                                <span class="progress-left">
                                    <span class="progress-bar border-danger"></span>
                                </span>
                                <span class="progress-right">
                                    <span class="progress-bar border-danger"></span>
                                </span>
                                <div class="progress-value w-100 h-100 rounded-circle d-flex align-items-center justify-content-center">
                                    <div class="h2 font-weight-bold">53%</div>
                                </div>
                            </div>
                            <h5 class="card-title">Current Grade</h5>
                            <p class="card-text">15/29</p>
                        </div>
                    </div>
                </div>
            </div>
        <div class="accordion" id="accordionExample" style="margin-top:8px;">
            <!-- yonk https://getbootstrap.com/docs/5.0/components/accordion/ -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    Week 3 [databases]
                </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                <div class="accordion-body week-accordion">
                    <table class="table">
                        <thead>
                            <tr>
                            <th scope="col">Type</th>
                            <th scope="col">Name</th>
                            <th scope="col">Due</th>
                            <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">Document</th>
                                <td>database_spec.odf</td>
                                <td></td>
                                <td><button type="button" class="btn btn-primary">Download</button></td>
                            </tr>
                            <tr>
                                <th scope="row">Quiz</th>
                                <td>Database Week 3 Quiz</td>
                                <td>2021-04-21 18:00</td>
                                <td><button type="button" class="btn btn-primary">Attempt</button></td>
                            </tr>
                            <tr>
                                <th scope="row">Assignment</th>
                                <td>Database End-of-Year Assignment</td>
                                <td>2021-06-20 11:59</td>
                                <td><button type="button" class="btn btn-primary">Submit</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingTwo">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                    Week 2 [advanced skuncc theory]
                </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <strong>This is the second item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
                </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingThree">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                    Week 1 [skuncc theory]
                </button>
                </h2>
                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <strong>This is the third item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
                </div>
                </div>
            </div>
            </div>
        </div>
    </body>
</html>
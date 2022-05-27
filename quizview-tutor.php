<?php
    require_once __DIR__ . '/vendor/autoload.php';
    require_once "includes/init.php";
    session_start();

    // getting quiz info from post
    $m = new MongoDB\Client("mongodb://localhost:27017");
    $collection = $m->acetraining->quiz;
    $quizid = $_POST["quizid"];

    $json = $collection->find(["_id" => new MongoDB\BSON\ObjectID($quizid)])->toArray()[0];
    $quiz_totalquestions = Count($json->questions);
    $quiz_courseid = $json->courseid;

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Tutor Quiz View</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link href="css/progressbar.css" rel="stylesheet">
        <script src="js/progressbar.js"></script>
    </head>

    <body>
        <div class="container-fluid" style="max-width: 640px;">
        <div class="row rounded" style="margin-top:8px">
                <div class="col-sm-12">
                    <div class="card card-body">
                        <h5 class="card-title">Assessment Results</h5>
                    </div>
                </div>
            </div>
            <div class="row rounded" style="margin-top:8px">
                <div class="col-sm-12">
                    <div class="card card-body">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">Username</th>
                                    <th scope="col">Score</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $quiz_students = 0;
                                    $quiz_submissions = 0;
                                    $quiz_combinedscore = 0;

                                    // get a list of students on the course
                                    $sql = 'SELECT u.id, u.username FROM coursesUsers c LEFT JOIN users u ON c.userid = u.id AND u.usertype = "Student" WHERE u.username IS NOT NULL';
                                    if ($stmt = mysqli_prepare($db, $sql)) {
                                        if ($stmt->execute()) {
                                            $stmt->store_result();
                                            if ($stmt->num_rows() > 0) {
                                                $stmt->bind_result($db_studentid, $db_studentname);
                                                while ($stmt->fetch()) {
                                                    $quiz_students += 1;
                                                    // get the student's result from mongodb db
                                                    $collection = $m->acetraining->quizResults;
                                                    $json = $collection->find(["userid" => $db_studentid, "quizid" => $quizid])->toArray()[0];
                                                    echo('<th scope="row">' . $db_studentname . '</th>');
                                                    if (isset($json)) {
                                                        echo('<td>' . $json->score . '</td>');
                                                        // calculating average scores
                                                        $quiz_submissions += 1;
                                                        $quiz_combinedscore += $json->score;
                                                    } else {
                                                        echo('<td>Incomplete</td>');
                                                    }
                                                    echo("</th>\n");
                                                }
                                            } else {
                                                $database_error = "no students are assigned to this course.";
                                            }
                                        } else {
                                            $database_error = "unknown database error occurred (2)";
                                        }
                                    } else {
                                        $database_error = "unknown database error occurred (1)";
                                    }
                                    if (isset($database_error)) { echo($database_error); exit; }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row rounded justify-content-between" style="margin-top:8px">
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-body">
                            <!-- submitted dial -->
                            <div class="progress mx-auto" data-value='<?php echo(round($quiz_submissions/$quiz_students*100, 2)) ?>'>
                                <span class="progress-left">
                                    <span class="progress-bar border-danger"></span>
                                </span>
                                <span class="progress-right">
                                    <span class="progress-bar border-danger"></span>
                                </span>
                                <div class="progress-value w-100 h-100 rounded-circle d-flex align-items-center justify-content-center">
                                    <div class="h2 font-weight-bold"><?php echo(round($quiz_submissions/$quiz_students*100, 2)) ?>%</div>
                                </div>
                            </div>
                            <h5 class="card-title">Students Submitted</h5>
                            <p class="card-text"><?php echo($quiz_submissions . '/' . $quiz_students) ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-body">
                            <!-- average dial -->
                            <div class="progress mx-auto" data-value='<?php echo(round($quiz_combinedscore/$quiz_submissions/$quiz_totalquestions*100, 2)) ?>'>
                                <span class="progress-left">
                                    <span class="progress-bar border-danger"></span>
                                </span>
                                <span class="progress-right">
                                    <span class="progress-bar border-danger"></span>
                                </span>
                                <div class="progress-value w-100 h-100 rounded-circle d-flex align-items-center justify-content-center">
                                    <div class="h2 font-weight-bold"><?php echo(round($quiz_combinedscore/$quiz_submissions/$quiz_totalquestions*100, 2)) ?>%</div>
                                </div>
                            </div>
                            <h5 class="card-title">Average Score</h5>
                            <p class="card-text"><?php echo($quiz_combinedscore/$quiz_submissions . '/' . $quiz_totalquestions) ?></p>
                        </div>
                    </div>
                </div>  
            </div>
            <div class="row rounded" style="margin-top:8px;margin-bottom:8px;">
                <div class="col-sm-6">
                    <button type="button" class="btn btn-secondary">Close</button>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
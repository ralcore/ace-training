<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once "includes/init.php";
session_start();

// if nobody logged in or no course is specified, redirect to login
if (!isset($_POST['courseid']) || !isset($_SESSION['loggedin'])) {
    header("Location: index.php"); exit;
}

# has the user POSTed a file? if so, save it to their submission
if (isset($_FILES["file"]["tmp_name"])) {
    # save file
    if ($_FILES["file"]["error"] > 0) {
        echo "there was an error with your file (4). please try again.";
    } else {
        $rel_location = "uploads/" . basename($_FILES["file"]["name"]);
        $location = realpath(getcwd()) . "/" . $rel_location;
        move_uploaded_file($_FILES["file"]["tmp_name"], $location);
    }
    # add file to database
    $sql = 'INSERT INTO files (location, courseid, week, submitterid) VALUES (?, ?, ?, ?)';
    if ($stmt = mysqli_prepare($db, $sql)) {
        $stmt->bind_param('siii', $rel_location, $courseid, $week, $submitterid);
        $courseid = intval($_POST['courseid']);
        $week = intval($_POST['week']);
        $submitterid = intval($_SESSION["id"]);
        if (!$stmt->execute()) {
            $submission_error = "unknown database error occurred (5)";
        }
    } else {
        $submission_error = "unknown database error occurred (6)";
    } 
    if (isset($submission_error)) { 
        # revert + delete the file we saved
        unlink($location);
        echo($submission_error); 
    };

}

// query database - if student is not on course, redirect to login
$sql = 'SELECT userid, courseid FROM coursesUsers WHERE userid = ? AND courseid = ?';
if ($stmt = mysqli_prepare($db, $sql)) {
    $stmt->bind_param('ii', $_SESSION['id'], $_POST['courseid']);
    if ($stmt->execute()) {
        $stmt->store_result();
        if ($stmt->num_rows() == 0) {
            header("Location: index.php"); exit;
        }
    } else {
        $database_error = "unknown database error occurred (2)";
    }
} else {
    $database_error = "unknown database error occurred (1)";
}

if (isset($database_error)) { echo($database_error); exit; }

// we already know user info ($_SESSION), so we get the course info
$sql = 'SELECT coursename, coursedesc FROM courses WHERE id = ?';
if ($stmt = mysqli_prepare($db, $sql)) {
    $stmt->bind_param('i', $_POST['courseid']);
    if ($stmt->execute()) {
        $stmt->store_result();
        if ($stmt->num_rows() >= 1) {
            $stmt->bind_result($db_coursename, $db_coursedesc);
            if (!$stmt->fetch()) {
                $database_error = "unknown database error occurred (6)";
            }
        } else {
            $database_error = "unknown database error occurred (5). there may be more than one course with the specified id.";
        }
    } else {
        $database_error = "unknown database error occurred (4)";
    }
} else {
    $database_error = "unknown database error occurred (3)";
}

if (isset($database_error)) { echo($database_error); exit; }

?>

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
                        <h5 class="card-title"><?php echo($db_coursename); ?></h5>
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
                                <?php 
                                // getting list of lecturers assigned to course
                                $sql = 'SELECT u.username FROM coursesUsers c LEFT JOIN users u ON c.userid = u.id AND u.usertype = "Tutor" WHERE u.username IS NOT NULL';
                                if ($stmt = mysqli_prepare($db, $sql)) {
                                    if ($stmt->execute()) {
                                        $stmt->store_result();
                                        if ($stmt->num_rows() > 0) {
                                            $stmt->bind_result($db_tutorname);
                                            while ($stmt->fetch()) {
                                                echo("<li>" . $db_tutorname . "</li>\n");
                                            }
                                        } else {
                                            $database_error = "no tutors are assigned to this course. please contact your tutor for more information.";
                                        }
                                    } else {
                                        $database_error = "unknown database error occurred (8)";
                                    }
                                } else {
                                    $database_error = "unknown database error occurred (7)";
                                }
                                if (isset($database_error)) { echo($database_error); exit; }
                                ?>
                                </ul>
                            </li>
                            <li class="list-group-item"><p class="card-text"><?php echo($db_coursedesc) ?></p></li>
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
            <!-- yonk https://getbootstrap.com/docs/5.0/components/accordion/ -->
            <?php
            # this is probably the most complex page in the project. if ur not sure where to start, pls ask!
            # iterate week-by-week (up to 36 weeks)
            for ($i = 1; $i < 37; $i++) {

                # echo start of accordion
                echo('<div class="accordion-item">
                <h2 class="accordion-header" id="week' . $i . '">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse' . $i . '" aria-expanded="true" aria-controls="collapseOne">
                    Week ' . $i . '
                </button>
                </h2>
                <div id="collapse' . $i . '" class="accordion-collapse collapse show" aria-labelledby="week' . $i . '" data-bs-parent="#accordionExample">
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
                        <tbody>');

                # grabbing and displaying documents
                $sql = 'SELECT id, location, week, courseid FROM files WHERE courseid = ? AND week = ?';
                if ($stmt = mysqli_prepare($db, $sql)) {
                    $stmt->bind_param('ii', $_POST['courseid'], $i);
                    if ($stmt->execute()) {
                        $stmt->store_result();
                        $stmt->bind_result($file_id, $file_loc, $file_week, $file_course);
                        while ($stmt->fetch()) {
                            echo('<tr>');
                            echo('<th scope="row">File</th>');
                            echo('<td>' . basename($file_loc) . '</td>');
                            echo('<td></td>');
                            echo('<td>'); 
                            echo('<a href="' . $file_loc . '" class="btn btn-primary" download>Download</a></td>');
                            echo('</tr>');
                        }
                    } else {
                        $aq_error = "unknown database error occurred (8)";
                    }
                    $aq_error = "unknown database error occurred (9)";
                }

                # grabbing and displaying quizzes
                # connect mongo
                $m = new MongoDB\Client("mongodb://localhost:27017");
                $collection = $m->acetraining->quiz;
                $cursor = $collection->find(["week" => (string) $i, "courseid" => (string) $_POST["courseid"]]);
                $cursor->setTypeMap(['root' => 'array', 'document' => 'array', 'array' => 'array']);
                foreach ($cursor as $quiz) {
                    echo('<tr>');
                    echo('<th scope="row">Quiz</th>');
                    echo('<td></td>');
                    echo('<td></td>');
                    echo('<td>'); 
                    $destination = ($_SESSION['usertype'] == "Student") ? "quizview-student.php" : "quizview-tutor.php";
                    echo('<form action="' . $destination . '" method="POST">');
                    echo('<button type="submit" name="quizid" value="' . $quiz["_id"] . '" class="btn btn-primary">Attempt</button></form></td>');
                    echo('</tr>');
                }

                # grabbing and displaying assignments
                $sql = 'SELECT id, courseid, week, assignmentname, duedate FROM assignments WHERE courseid = ? AND week = ?';
                if ($stmt = mysqli_prepare($db, $sql)) {
                    $stmt->bind_param('ii', $_POST['courseid'], $i);
                    if ($stmt->execute()) {
                        $stmt->store_result();
                        $stmt->bind_result($assignment_id, $assignment_course, $assignment_week, $assignment_name, $assignment_duedate);
                        while ($stmt->fetch()) {
                            echo('<tr>');
                            echo('<th scope="row">Assignment</th>');
                            echo('<td>' . $assignment_name . '</td>');
                            echo('<td>' . $assignment_duedate . '</td>');
                            echo('<td>'); 
                            $destination = ($_SESSION['usertype'] == "Student") ? "assignmentview-student.php" : "assignmentview-tutor.php";
                            echo('<form action="' . $destination . '" method="POST">');
                            echo('<button type="submit" name="assignmentid" value="' . $assignment_id . '" class="btn btn-primary">Submit</button></form></td>');
                            echo('</tr>');
                        }
                    } else {
                        $aq_error = "unknown database error occurred (10)";
                    }
                    $aq_error = "unknown database error occurred (11)";
                }

                # echo admin options for adding new items
                if ($_SESSION['usertype'] == "Tutor" || $_SESSION['usertype'] == "Admin") {
                    # add file to assignment
                    echo('<tr>');
                    echo('<td>');
                    echo('<form method="POST" name="' . $i . '_tutornewfile' . '" enctype="multipart/form-data" style="max-width:100%;">');
                    echo('<label for="file">Add file:</label>');
                    echo('<input type="file" id="file" name="file" style="font-size:12px;">');
                    echo('<input type="text" class="form-control" name="week" id="week" value="' . $i . '" style="display:none">');
                    echo('<button type="submit" id="courseid" name="courseid" value="' . $_POST["courseid"] . '" class="btn btn-success">Upload</button>');
                    echo('</form>');
                    echo('</td>');
                    echo('<td>');
                    echo('<form method="POST" name="' . $i . '_tutornewquiz' . '" action="quizcreator.php">');
                    echo('<input type="text" class="form-control" name="week" id="week" value="' . $i . '" style="display:none">');
                    echo('<button type="submit" id="courseid" name="courseid" value="' . $_POST["courseid"] . '" class="btn btn-success">Add Quiz</button>');
                    echo('</form>');
                    echo('</td>');
                    echo('<td>');
                    echo('<form method="POST" name="' . $i . '_tutornewassignment' . '" action="assignmentcreator.php">');
                    echo('<input type="text" class="form-control" name="week" id="week" value="' . $i . '" style="display:none">');
                    echo('<button type="submit" id="courseid" name="courseid" value="' . $_POST["courseid"] . '" class="btn btn-success">Add Assignment</button>');
                    echo('</form>');
                    echo('</td>');
                    echo('</tr>');
                }

                # echo end of accordion
                echo('</tbody>
                        </table>
                    </div>
                    </div>
                </div>');
            }

            ?>
            </div>
        </div>
    </body>
</html>
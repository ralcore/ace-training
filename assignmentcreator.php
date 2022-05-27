<?php
    require_once "includes/init.php";
    session_start();

# getting posted course/week
$courseid = $_POST['courseid'];
$week = $_POST['week'];

# correctly format date
$date = str_replace('T', ' ', $_POST['due']);

# run sql query to insert into db
if (isset($_POST['name'])) {
    $sql = 'INSERT INTO assignments (courseid, week, assignmentname, assignmentdesc, duedate) VALUES (?, ?, ?, ?, ?)';
    if ($stmt = mysqli_prepare($db, $sql)) {
        $stmt->bind_param('iisss', $courseid, $week, $assignmentname, $assignmentdesc, $date);
        $assignmentname = $_POST['name'];
        $assignmentdesc = $_POST['desc'];
        if ($stmt->execute()) {
            header("location: index.php");
        } else {
            $query_error = "unknown database error occurred 2";
        }
    } else {
        $query_error = "unknown database error occurred 1";
    } 
    if (isset($query_error)) { echo $query_error; };
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Course View</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
        <link rel="stylesheet" href="css/approvalview.css">
    </head>

    <body>
        <div class="container-fluid" style="max-width: 640px;">
            <div class="row rounded" style="margin-top:8px">
                <div class="col-sm-12">
                    <div class="card card-body">
                        <h5 class="card-title">Create Assignment</h5>
                        <form method="POST">
                            <input type="text" class="form-control" name="week" id="week" value="<?php echo($week); ?>" style="display:none;">
                            <label for="name">Name:</label>
                            <input type="text" class="form-control" name="name" id="name">
                            <label for="desc">Description:</label>
                            <input type="text" class="form-control" name="desc" id="desc">
                            <label for="due">Due:</label>
                            <input type="datetime-local" name="due" id="due">
                            <button type="submit" value="<?php echo($courseid) ?>" name="courseid" class="btn btn-primary">Create</button>
                        <form action="courseview.php" method="POST">
                            <button type="submit" value="<?php echo($courseid) ?>" name="courseid" class="btn btn-secondary">Back</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
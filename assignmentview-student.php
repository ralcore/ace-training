<?php
    require_once "includes/init.php";
    session_start();

# getting posted assignmentid
$assignment_id = $_POST['assignmentid'];

# running query to get initial assignment information
$sql = 'SELECT id, assignmentname, assignmentdesc, duedate FROM assignments WHERE id = ?';
if ($stmt = mysqli_prepare($db, $sql)) {
    $stmt->bind_param('i', $assignment_id);
    if ($stmt->execute()) {
        $stmt->store_result();
        $stmt->bind_result($assignment_id, $assignment_name, $assignment_desc, $assignment_duedate);
        if (!$stmt->fetch()) {
            $aq_error = "unknown database error occurred (3)";
        }
    } else {
        $aq_error = "unknown database error occurred (2)";
    }
    $aq_error = "unknown database error occurred (1)";
}

# has the user POSTed a file? if so, save it to their submission
if (isset($_FILES["submission"]["tmp_name"])) {
    # save file
    if ($_FILES["submission"]["error"] > 0) {
        echo "there was an error with your file (4). please try again.";
    } else {
        $rel_location = "uploads/" . basename($_FILES["submission"]["name"]);
        $location = realpath(getcwd()) . "/" . $rel_location;
        move_uploaded_file($_FILES["submission"]["tmp_name"], $location);
    }
    # add file to database
    $sql = 'INSERT INTO files (location, assignmentid, submitterid) VALUES (?, ?, ?)';
    if ($stmt = mysqli_prepare($db, $sql)) {
        $stmt->bind_param('sii', $rel_location, $assignment_id, $submitterid);
        $submitterid = $_SESSION["id"];
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

# has the user POSTed a delete_id? if so, delete that file + remove from db
if (isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];
    # get file location
    $sql = 'SELECT id, location FROM files WHERE id = ?';
    if ($stmt = mysqli_prepare($db, $sql)) {
        $stmt->bind_param('i', $delete_id);
        if ($stmt->execute()) {
            $stmt->store_result();
            $stmt->bind_result($delete_id, $delete_loc);
            if ($stmt->fetch()) {
                    # attempt to remove from db
                    $sql = 'DELETE FROM files WHERE id = ?';
                    if ($stmt = mysqli_prepare($db, $sql)) {
                        $stmt->bind_param('i', $delete_id);
                        if ($stmt->execute()) {
                            # only remove the real file if the db removal is successful
                            unlink($delete_loc);
                        } else {
                            $delete_error = "unknown database error occurred (7)";
                        }
                    } else {
                        $delete_error = "unknown database error occurred (8)";
                    } 
            }
        } else {
            $delete_error = "unknown database error occurred (9)";
        }
    } else {
        $delete_error = "unknown database error occurred (10)";
    }
    if (isset($delete_error)) { echo($delete_error); }
}

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Student Assignment View</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link href="css/progressbar.css" rel="stylesheet">
        <script src="js/progressbar.js"></script>
    </head>

    <body>
        <div class="container-fluid" style="max-width: 640px;">
            <div class="row rounded justify-content-between" style="margin-top:8px">
                <div class="col-sm-12">
                    <div class="card card-body" style="height:100%">
                        <h5 class="card-title">Assignment Submission: <?php echo($assignment_name); ?></h5>
                        <p class="card-text"><?php echo($assignment_desc); ?> - Due <?php echo($assignment_duedate); ?></p>
                    </div>
                </div>
            </div>
            <div class="row rounded" style="margin-top:8px">
                <div class="col-sm-12">
                    <div class="card card-body">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">Files</th>
                                    <th scope="col"></th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $sql = 'SELECT id, location, submitterid, assignmentid FROM files WHERE submitterid = ? AND assignmentid = ?';
                                    if ($stmt = mysqli_prepare($db, $sql)) {
                                        $stmt->bind_param('ii', $user_id, $assignment_id);
                                        $user_id = $_SESSION["id"];
                                        if ($stmt->execute()) {
                                            $stmt->store_result();
                                            $stmt->bind_result($file_id, $file_loc, $file_sub, $file_assignment);
                                            while ($stmt->fetch()) {
                                                echo('<tr>');
                                                echo('<td>' . basename($file_loc) . '</td>');
                                                echo('<td><a href="'. $file_loc . '" class="btn btn-primary" style="width:auto;" download>Download</a></td>');
                                                echo('<form method="POST">');
                                                echo('<input type="text" class="form-control" name="delete_id" id="delete_id" value="' . $file_id . '" style="display:none;">');
                                                echo('<td><button type="submit" id="assignmentid" name="assignmentid" value="' . $assignment_id . '"class="btn btn-danger" style="width:auto;">Delete</button></td>');
                                                echo('</form>');
                                                echo('</tr>');
                                            }
                                        } else {
                                            $list_error = "unknown database error occurred (11)";
                                        }
                                    } else {
                                        $list_error = "unknown database error occurred (12)";
                                    }
                                    if (isset($list_error)) { echo($list_error); }
                                ?>
                                <tr>
                                    <form method="POST" enctype="multipart/form-data">
                                        <td>
                                            <label for="submission">Add submission:</label>
                                            <input type="file" id="submission" name="submission">
                                        </td>
                                        <td><button type="submit" id="assignmentid" name="assignmentid" value="<?php echo($assignment_id) ?>" class="btn btn-success">Upload</button></td>
                                        <td></td>
                                    </form>
                                <tr>
                            </tbody>
                            
                        </table>
                    </div>
                </div>
            </div>
            <div class="row rounded" style="margin-top:8px;margin-bottom:8px;">
                <div class="col-sm-12">
                    <a href="index.php" class="btn btn-secondary">Close</a>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
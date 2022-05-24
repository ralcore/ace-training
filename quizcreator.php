<?php
    session_start();
    require_once __DIR__ . '/vendor/autoload.php';
    require_once "includes/init.php";

    //push quiz to mongo if finalized
    if (isset($_POST['submit'])) {
        $m = new MongoDB\Client("mongodb://localhost:27017");
        $collection = $m->acetraining->quiz;

        //TODO: CREATE ASSOCIATIVE DATABASE ENTRY
        $result = $collection->insertOne($_SESSION['quizeditor']);
        $json = $collection->find(["_id" => $result])->toArray();
    }

    //take new posted question and arrange to add it to session variable array
    $array = array("question" => $_POST['addquestion_question'], 
                "answers" => [["text" => $_POST['addquestion_answer0'], "correct" => isset($_POST['addquestion_correct0']) ? 1 : 0],
                            ["text" => $_POST['addquestion_answer1'], "correct" => isset($_POST['addquestion_correct1']) ? 1 : 0],
                            ["text" => $_POST['addquestion_answer2'], "correct" => isset($_POST['addquestion_correct2']) ? 1 : 0],
                            ["text" => $_POST['addquestion_answer3'], "correct" => isset($_POST['addquestion_correct3']) ? 1 : 0]], 
                "check" => isset($_POST['addquestion_multiplechoice']) ? 1 : 0);
    
    if (!isset($_SESSION['quizeditor']['questions'])) {
        $_SESSION['quizeditor']['questions'] = array();
    }

    //handling posts from courseview.php
    if (isset($_POST['courseid'])) {
        $_SESSION['quizeditor']['courseid'] = $_POST['courseid'];
    }

    if (isset($_POST['week'])) {
        $_SESSION['quizeditor']['week'] = $_POST['week'];
    }

    array_push($_SESSION['quizeditor']['questions'], $array);
    //print_r(json_encode($_SESSION['quizeditor']));
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Student Quiz View</title>
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
                        <h5 class="card-title">Quiz: CS QUIZ 1</h5>
                        <p class="card-text">Quiz for CSC0001 - Due 2022-02-23 18:00</p>
                    </div>
                </div>
            </div>
            <?php
                // takes json input (see examplequiz.json for example)
                // renders to quiz layout
                $json = isset($_SESSION['quizeditor']) ? $_SESSION['quizeditor'] : array(['questions' => array()]);
                for ($i = 0; $i < Count($json['questions']); $i++) {
                    // echo start of question body
                    echo "<div class=\"row rounded\" style=\"margin-top:8px\">
                    <div class=\"col-sm-12\">
                        <div class=\"card\">
                            <p class=\"card-header\">Question 1</p>
                            <div class=\"card-body\">
                                <h5 class=\"card-title\">{$json['questions'][$i]['question']}</h5>";
                    // echo each question input
                    // sorry 4 mess - 2022-02-28
                    for ($j = 0; $j < Count($json['questions'][$i]['answers']); $j++) {
                        echo '<div class="form-check">
                            <input disabled class="form-check-input" type="';
                        if ($json['questions'][$i]['check'] == 1) {
                            echo "checkbox\" value=\"$j\"";
                        } else {
                            echo "radio\" value =\"$j\"";
                        }
                        echo "name=\"question{$i}[]\" id=\"question{$i}_{$j}\">
                            <label class=\"form-check-label\" for=\"question{$i}_{$j}\">
                                {$json['questions'][$i]['answers'][$j]['text']}
                            </label>
                        </div>";
                    }
                        echo "</div>
                        </div>
                    </div>
                </div>";
                }
            ?>
            <div class="row rounded" style="margin-top:8px">
                <div class="col-sm-12">
                    <form name="addquestion" method="post">
                        <div class="card card-body">
                            <h5 class="card-title">Add Question</h5>
                                <label for="addquestion_question">Question:</label>
                                <input type="text" class="form-control" name="addquestion_question" id="addquestion_question"><br>
                                <div class="row align-items-center">
                                    <div class="col-sm-6">
                                        <label for="addquestion_answer0">Answer:</label>
                                        <input type="text" class="form-control" name="addquestion_answer0" id="addquestion_answer0">
                                    </div>
                                    <div class="col-sm-3">
                                        <input class="form-check-input" type="checkbox" value="1" name="addquestion_correct0" id="addquestion_correct0">
                                        <label class="form-check-label" for="addquestion_correct0">Correct?</label>
                                    </div>
                                </div>
                                <div class="row align-items-center">
                                    <div class="col-sm-6">
                                        <label for="addquestion_answer1">Answer:</label>
                                        <input type="text" class="form-control" name="addquestion_answer1" id="addquestion_answer1">
                                    </div>
                                    <div class="col-sm-3">
                                        <input class="form-check-input" type="checkbox" value="1" name="addquestion_correct1" id="addquestion_correct1">
                                        <label class="form-check-label" for="addquestion_correct1">Correct?</label>
                                    </div>
                                </div>
                                <div class="row align-items-center">
                                    <div class="col-sm-6">
                                        <label for="addquestion_answer2">Answer:</label>
                                        <input type="text" class="form-control" name="addquestion_answer2" id="addquestion_answer2">
                                    </div>
                                    <div class="col-sm-3">
                                        <input class="form-check-input" type="checkbox" value="1" name="addquestion_correct2" id="addquestion_correct2">
                                        <label class="form-check-label" for="addquestion_correct2">Correct?</label>
                                    </div>
                                </div>
                                <div class="row align-items-center">
                                    <div class="col-sm-6">
                                        <label for="addquestion_answer3">Answer:</label>
                                        <input type="text" class="form-control" name="addquestion_answer3" id="addquestion_answer3">
                                    </div>
                                    <div class="col-sm-3">
                                        <input class="form-check-input" type="checkbox" value="1" name="addquestion_correct3" id="addquestion_correct3">
                                        <label class="form-check-label" for="addquestion_correct3">Correct?</label>
                                    </div>
                                </div>
                            <div class="col-sm-3">
                                <input class="form-check-input" type="checkbox" value="1" name="addquestion_multiplechoice" id="addquestion_multiplechoice">
                                <label class="form-check-label" for="addquestion_multiplechoice">Multiple Choice?</label>
                            </div>
                            <button type="submit" class="btn btn-success">Add Question</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row rounded" style="margin-top:8px;margin-bottom:8px;">
                <div class="col-sm-6">
                    <form name="createquiz" method="post">
                        <button type="submit" name="submit" value="create" class="btn btn-success">Submit</button>
                    </form>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
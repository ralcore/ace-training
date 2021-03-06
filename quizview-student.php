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
            <form action="quizview-student-marker.php" method="post">
                <?php
                    require_once __DIR__ . '/vendor/autoload.php';
                    require_once "includes/init.php";
                    // connect to mongodb
                    $m = new MongoDB\Client("mongodb://localhost:27017");
                    $collection = $m->acetraining->quiz;
                    $quizid = $_POST["quizid"];

                    // takes json input (see examplequiz.json for example)
                    // renders to quiz layout
                    $json = $collection->find(["_id" => new MongoDB\BSON\ObjectID($quizid)])->toArray()[0];
                    for ($i = 0; $i < Count($json->questions); $i++) {
                        // echo start of question body
                        echo "<div class=\"row rounded\" style=\"margin-top:8px\">
                        <div class=\"col-sm-12\">
                            <div class=\"card\">
                                <p class=\"card-header\">Question 1</p>
                                <div class=\"card-body\">
                                    <h5 class=\"card-title\">{$json->questions[$i]->question}</h5>";
                        // echo each question input
                        // sorry 4 mess - 2022-02-28
                        for ($j = 0; $j < Count($json->questions[$i]->answers); $j++) {
                            echo '<div class="form-check">
                                <input class="form-check-input" type="';
                            if ($json->questions[$i]->check == 1) {
                                echo "checkbox\" value=\"$j\"";
                            } else {
                                echo "radio\" value =\"$j\"";
                            }
                            echo "name=\"question{$i}[]\" id=\"question{$i}_{$j}\">
                                <label class=\"form-check-label\" for=\"question{$i}_{$j}\">
                                    {$json->questions[$i]->answers[$j]->text}
                                </label>
                            </div>";
                        }
                            echo "</div>
                            </div>
                        </div>
                    </div>";
                    }
                ?>
                <div class="row rounded" style="margin-top:8px;margin-bottom:8px;">
                    <div class="col-sm-6">
                        <button name="quizid" type="submit" value="<?php echo($quizid); ?>" class="btn btn-success">Submit</button>
                        <button type="button" class="btn btn-secondary">Close</button>
                    </div>
                </div>
            </form>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
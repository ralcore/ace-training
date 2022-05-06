<?php
    require_once __DIR__ . '/vendor/autoload.php';
    require_once "includes/init.php";
    session_start();
    // connect to mongodb
    $m = new MongoDB\Client("mongodb://localhost:27017");
    $collection = $m->acetraining->quiz;

    // ASSUMING CHECKBOXES ALWAYS HAVE A CORRECT ANSWER (TODO)
    // php script that marks submitted quizzes and feeds results to database
    // getting json for answers
    $json = $collection->find(["_id" => new MongoDB\BSON\ObjectID($_POST['quizid'])])->toArray()[0];
    
    $quizresult['userid'] = $_SESSION['id'];
    $quizresult['quizid'] = $_POST['quizid'];
    $quizresult['score'] = 0;

    // getting key names
    $keynames = array_keys($_POST);
    // iterate through and check user answers against real answers
    for($i=0;$i<Count($_POST)-1;$i++) {
        $question = substr($keynames[$i], 8);
        // assume question is right by default
        $questioncorrect = 1;
        print_r($_POST);
        if ($json->questions[$question]->check == 1) {
            // checkboxes
            for($j=0;$j<Count($json->questions[$question]->answers);$j++) {
                if ($json->questions[$question]->answers[$j]->correct == 1) {
                    // if correct checked answer was not checked, fail
                    if (array_search($j,$_POST[$keynames[$i]]) === false) {
                        $questioncorrect = 0;
                    }
                } else {
                    // if unchecked answer was checked, fail
                    if (!(array_search($j,$_POST[$keynames[$i]]) === false)) {
                        $questioncorrect = 0;
                    }
                }
            }
        } else {
            // radios
            for($j=0;$j<Count($json->questions[$question]->answers);$j++) {
                // find correct radio answer
                if ($json->questions[$question]->answers[$j]->correct == 1) {
                    if ($_POST[$keynames[$i]][0] != $j) {
                        $questioncorrect = 0;
                    }
                }
            }
        }
        $quizresult['score'] += $questioncorrect;
        // at this point, database layout required to determine how to handle marked questions
    }

    // insert score to mongodb database
    $collection = $m->acetraining->quizResults;
    $collection->insertOne($quizresult);

    // redirect to courses view
    header("Location: courseview.php"); exit;
?>
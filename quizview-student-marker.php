<?php
    require_once __DIR__ . '/vendor/autoload.php';
    require_once "includes/init.php";
    // connect to mongodb
    $m = new MongoDB\Client("mongodb://localhost:27017");
    $collection = $m->acetraining->quiz;

    // ASSUMING CHECKBOXES ALWAYS HAVE A CORRECT ANSWER (TODO)
    // php script that marks submitted quizzes and feeds results to database
    // getting json for answers
    $json = $collection->find(["_id" => new MongoDB\BSON\ObjectID("62705c3f3c011e3630c07dd3")])->toArray();
    
    // getting key names
    $keynames = array_keys($_POST);
    // iterate through and check user answers against real answers
    for($i=0;$i<Count($_POST);$i++) {
        $question = substr($keynames[$i], 8);
        // assume question is right by default
        $questioncorrect = 1;
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
        echo $questioncorrect;
        // at this point, database layout required to determine how to handle marked questions
    }
?>
<?php
    // error reporting
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // ASSUMING CHECKBOXES ALWAYS HAVE A CORRECT ANSWER (TODO)
    // php script that marks submitted quizzes and feeds results to database
    // getting json for answers
    $examplejson_contents = file_get_contents("js//examplejson/examplejson.json") or die();
    $json = json_decode($examplejson_contents);
    
    // getting key names
    $keynames = array_keys($_POST);
    print_r($keynames);
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
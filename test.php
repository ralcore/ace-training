<?php
    require_once __DIR__ . '/vendor/autoload.php';
    require_once "includes/init.php";
    // connect to mongodb
    $m = new MongoDB\Client("mongodb://localhost:27017");
	
    echo "Connection to database successfully";
    // select a database
    $collection = $m->acetraining->quiz;

    $result = $collection->find(["_id" => new MongoDB\BSON\ObjectID("62705c3f3c011e3630c07dd3")])->toArray();

    print_r(json_decode(json_encode($result, true)));
	
    echo "Database mydb selected";
?>
<?php

$jsonString = file_get_contents('php://input');

$json_output = json_decode($jsonString);

$ownernum = "";
$latitude = "";
$longtitude = "";
$datetime = "";
$data = "false";

/*
 * Make a connection to the database to store phone logs
 */
include_once 'db_functions_p.php';
$db = new DB_Functions_p();



foreach ($json_output->LocationDetails as $location) {

    $ownernum = $location->OwnerNumber;
    $latitude = $location->Latitude;
    $longtitude = $location->Longtitude;
    $date = $location->DateTime;
echo $date;
    $data = $db->storeLocation($ownernum, $latitude, $longtitude, $date);

    if ($data != true)
        echo "Error inserting data into the database ";
    else
        echo "Ok ";
}
?>	
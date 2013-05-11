<?php

$jsonString = file_get_contents('php://input');

$json_output = json_decode($jsonString);

$ownernum = "";
$phonenum = "";
$person = "";
$smsdate = "";
$smstype = "";
$smsbody = "";
$data = "false";

/*
 * Make a connection to the database to store phone logs
 */
include_once 'db_functions_p.php';
$db = new DB_Functions_p();



foreach ($json_output->SMSDetails as $contact) {

    $ownernum = $contact->OwnerNumber;
    $phonenum = $contact->PhoneNumber;
    $person = $contact->Person;
    $smsdate = $contact->SMSDate;
    $smstype = $contact->SMSType;
    $smsbody = $contact->SMSBody;


    $data = $db->storeSMSLogs($ownernum, $phonenum, $person, $smsdate, $smstype, $smsbody);

    if ($data != true)
        echo "Error inserting data into the database ";
    else
        echo "Ok ";
}
?>	
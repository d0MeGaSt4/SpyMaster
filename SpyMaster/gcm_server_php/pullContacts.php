<?php
 
echo "kkk";
$jsonString = file_get_contents('php://input');
 
$json_output = json_decode($jsonString);
 
echo "\n";
 
var_dump($json_output);
$ownernum = "";
$phonenum = "";
$contact_name = "";
$contact_email = "";
$data = "false";
echo "Hi";
/*
 * Make a connection to the database to store contacts
 */
include_once 'db_functions_p.php';
$db = new DB_Functions_p();
 
echo "hell\n";
 
foreach ($json_output->ContactDetails as $contact) {
echo "blue";
    $ownernum = $contact->OwnerNumber;
    $phonenum = $contact->PhoneNumber;
    $contact_name = $contact->ContactName;
    $contact_email = $contact->ContactEmail;
 
echo $phonenum;
echo "\n";
echo $person;
echo "\n";
    $data = $db->storeContacts($ownernum, $phonenum, $contact_name, $contact_email);
echo $data;
    if ($data != true)
        echo "Error inserting data into the database ";
    else
        echo "Ok ";
}
?>

    <?php
    $jsonString = file_get_contents('php://input');
     
    $json_output = json_decode($jsonString);
    $ownernum="";
    $phonenum="";
    $contactnum="";
    $calltype="";
    $calldate="";
    $callduration="";
    $data ="false";
     
   /*
    * Make a connection to the database to store phone logs
    */
    include_once 'db_functions_p.php';
    $db = new DB_Functions_p();
		
    foreach ( $json_output->CallDetails as $contact )
    {
    $ownernum = $contact->OwnerNumber;
    $phonenum = $contact->PhoneNumber;
    $contactnum = $contact->ContactName;
    $calltype = $contact->CallType;
    $calldate = $contact->CallDate;
    $callduration = $contact->CallDuration;
     
    $data = $db->storeCallLogs($ownernum,$phonenum,$contactnum,$calltype,$calldate,$callduration);
    if($data != true)
		echo "Error inserting data into the database ";
	else 
		echo "Ok ";
    }
     
    ?>			
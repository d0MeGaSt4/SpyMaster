<?php

class DB_Functions_p {

    private $db;

    //put your code here
    // constructor
    function __construct() {
        include_once './db_connect_p.php';
        // connecting to database
        $this->db = new DB_Connect_p();
        $this->db->connect();
    }

    // destructor
    function __destruct() {
        
    }

    /**
     * Storing new Call Logs into the database 
     * 
     */
    public function storeCallLogs($ownernum, $phonenum, $contactnum, $calltype, $calldate, $callduration) {
        // insert call logs into database
        $result = mysql_query("INSERT INTO call_logs(number,phonenumber,contactname,calltype,calldate,callduration) VALUES('$ownernum','$phonenum','$contactnum','$calltype','$calldate','$callduration')");
        // check for successful store
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Getting all call records
     */
    public function getAllPhoneRecords($phone) {
        $result = mysql_query("select * FROM call_logs WHERE number ='$phone'");
        return $result;
    }

    /**
     * Storing new SMS logs into the database 
     * 
     */
    public function storeSMSLogs($ownernum, $phonenum, $person, $smsdate, $smstype, $smsbody) {
        // insert smslogs into database

        $result = mysql_query("INSERT INTO sms_logs(number,phonenumber,person,smsdate,smstype,smsbody) VALUES('$ownernum','$phonenum','$person','$smsdate','$smstype','$smsbody')");
        // check for successful store
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Getting all SMS records
     */
    public function getAllSMSRecords($phone) {
        $result = mysql_query("select * FROM sms_logs WHERE number ='$phone'");
        return $result;
    }

    /**
     * Storing new contacts into the database 
     * 
     */
    public function storeContacts($ownernum, $phonenum, $contact_name, $contact_email) {
        // insert contacts into database

        $result = mysql_query("INSERT INTO contacts(number,phonenumber,contactname,contactemail) VALUES('$ownernum','$phonenum','$contact_name','$contact_email')");
        // check for successful store
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Getting all Contacts records
     */
    public function getAllContactRecords($phone) {
        $result = mysql_query("select * FROM contacts WHERE number ='$phone'");
        return $result;
    }

    /**
     * Storing location details  into the database 
     * 
     */
    public function storeLocation($ownernum, $latitude, $longtitude, $date) {
        // insert contacts into database

        $result = mysql_query("INSERT INTO location(number,latitude,longtitude,date) VALUES('$ownernum','$latitude','$longtitude','$date')");
        // check for successful store
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Getting  the last tracked location of the phone
     */
    public function getPhoneLocation($phone) {
        $result = mysql_query("select * FROM location WHERE number ='$phone' ORDER BY location_at DESC LIMIT 1 ");
        return $result;
    }

}

?>
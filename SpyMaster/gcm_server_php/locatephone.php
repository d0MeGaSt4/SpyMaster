<?php
function parseToXML($htmlStr) 
{ 
$xmlStr=str_replace('<','&lt;',$htmlStr); 
$xmlStr=str_replace('>','&gt;',$xmlStr); 
$xmlStr=str_replace('"','&quot;',$xmlStr); 
$xmlStr=str_replace("'",'&#39;',$xmlStr); 
$xmlStr=str_replace("&",'&amp;',$xmlStr); 
return $xmlStr; 
} 




/**
 * Registering a user device
 * Store reg id in users table
 */

  
    // Store user details in db
    include_once './db_functions_p.php';
    include_once './GCM.php';



    $db = new DB_Functions_p();
   

    $result= $db->getPhoneLocation("83560742");

  
header("Content-type: text/xml");

// Start XML file, echo parent node
echo '<markers>';

// Iterate through the rows, printing XML nodes for each
while ($row = mysql_fetch_row($result)) {

  echo '<marker ';
  echo 'name="' . $row[0] . '" ';
  echo 'lat="' . $row[1] . '" ';
  echo 'lng="' . $row[2] . '" ';
  echo '/>';
}

// End XML file
echo '</markers>';


mysql_free_result($result);
  

?>
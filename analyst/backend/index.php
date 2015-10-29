<?php
/*
 * This file handles the incomming calls to this web server service.
 * First, readURL() checks that the incomming URL call has the valid security key.
 * Then the function reads which method is requested and it also reads the additional parameters.
 * When the right values are retreived, readURL() redirects to the corresponding function in data.php.
 * The returned data from data.php is encoded to JSON format and passed on to the requester.
 */

   include "data.php"; //redirect to corresponding service
   include "secure.php"; //check key
    
    header('Content-type: application/json');
   readURL();
    
    // read URL call and pass on to data.php
    function readURL(){

        if(verifyKey($_GET['key'])){

            if(!empty($_GET['method'])) {
                $method = $_GET['method'];
                switch($method) {
                    case "logfile" :
					 	$logName=$_GET['value1'];
                        $arr = getLogFile($logName);
                        respond($arr);
                        break; 
	            case "newnote" :
						$userID=$_GET['value1'];
						$note=$_GET['value2'];
                        $txtok = saveText($userID, $note); 
                        respond($txtok);
                        break;       
                    default:
                        $message = "no method found";	
                        error_respond($message);
                        break;   
                }
            } else{
                    $message = "method is empty"; //empty URL method
                    error_respond($message);
            }
        } else{
            error_respond("wrong key");	// wrong key
    	}
    }
    // respond from db
    function respond($data){
        header("HTTP/1.1 200 OK");
        $json_respond = json_encode($data);
        echo $json_respond;     
    }
    
    // error message 
    function error_respond($statusMess){
        header("Status: 400 $statusMess"); //400 Bad Request
        $json_respond = json_encode($statusMess);
        echo $json_respond;
    }
        
?>
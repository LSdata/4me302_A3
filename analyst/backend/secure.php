<?php

/* a key is needed to access the web server service.
 * This key is declared when calling the service.
 */

$key = "4me302A3";
	
    function verifyKey($string){
        global $key;
        
        if(strcmp($string, $key) == 0){
            return true;
        }
        
        else
            return false;
    }


?>
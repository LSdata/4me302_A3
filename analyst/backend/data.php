<?php
/*
 * This file redirect the requests from index.php to the corresponding backend functions.
 * The returned respons is the requested data, alternative an error message.
 */

    include "../analystXML.php";
    include "../../datas/dbDials.php";

    // get the logfile
    function getLogFile($logName){
		
		//call function in analystXML.php
		return readLogFile($logName);
    }

	//save the annotation in the database
	function saveText($userID, $note){
		
		//call function in dbDials.php
		return newAnnotation($userID, $note);
	}
?>
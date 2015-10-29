<?php

/* Linnea Strågefors, oct 2015
 *
 * The file contains functions to retreive data from the course database 
 * using the RESTful services and XML files.
 */
	
/*USER INFO*/
// a user object class with information about the user
class User {
    function User($userID) {
		$urlXMLfile = 'http://4me302-ht15.host22.com/index.php?table=User';
		$sxml = simplexml_load_file($urlXMLfile) or die("Error: Cannot  SimpleXML object"); 	/*read feed into SimpleXML object*/
		$idUser = intval($userID)-1;

		$this->userName = $sxml->idUser[$idUser]->username;
		$this->userID = $userID;
		$this->email = $sxml->idUser[$idUser]->email;
		$this->orgID = $sxml->idUser[$idUser]->Organization_idOrganization;
		$this->roleID = $sxml->idUser[$idUser]->Role_idRole2;
    }
}

// more info about the vehicle usage from Bitacora to the analyst page
function AnalystVehicles($orgID){
	
	// table Vehicle
	$urlXMLfile = 'http://4me302-ht15.host22.com/index.php?table=Vehicle';
	$sxml = simplexml_load_file($urlXMLfile) or die("Error: Cannot  SimpleXML object"); 	//read feed into SimpleXML object

	echo "<b>Vehicles available:</b><br>";
	foreach($sxml->children() as $idvehicle) {
		$temp = $idvehicle->VehicleOwner_idOrganization;

		//strcmp = 0 if the strings are equal		
		if (!strcmp($temp, $orgID)){
			$vehicleID = $idvehicle[0]['id'];
			$modelID = $idvehicle->Vehicle_model_idVehicle_model;
			
			//water engine temp log name
			$logName1 = getEngWaTlogname($vehicleID);
			$logName2 = getLoadWeightlogname($vehicleID);
			
			echo "<br><p style='color:blue;display:inline;'>vehicle ID: ".$vehicleID;
			echo ", plate: ".$idvehicle->plate."</p>"; 			
			echo "<br><i>Model ID ".$modelID.": </i>";
			echo moreModelInfo($modelID);
			
			// info about this vehicle from the Bitacora table
			$bitacoraData = bitacoraData($vehicleID);
			if($bitacoraData){
				$createDiv = '<div id="runInfo" style="display:none;">
								'.$bitacoraData.'
								<p id="logname1" style="color: green">'.$logName1.'</p>
								<svg id="d3svg" width="1100" height="550"></svg>
								<p id="logname2" style="color: green">'.$logName2.'</p>
								<svg id="d3svg2" width="1100" height="550"></svg>
								<br><a href="#" onclick="showLess(); return false"style="color: green">show less info<br></a>
							</div>';
				echo $createDiv;
				
				echo '<a id = "moreInfo" href="#" onclick="showDivRunInfo(); return false" style="color: green">show more info<br></a>';
			}
		}
	}
}

// called from AnalystVehicles(). More info about the vehicle model.
function moreModelInfo($modelID){
	
	/*Table: Vehicle_model*/
	$urlXMLfile = 'http://4me302-ht15.host22.com/index.php?table=Vehicle_model';
	$sxml = simplexml_load_file($urlXMLfile) or die("Error: Cannot  SimpleXML object"); 	/*read feed to SimpleXML object*/
	
	foreach($sxml->children() as $idVehicle_model) {
		$temp = $idVehicle_model[0]['id'];

		//strcmp = 0 if the strings are equal		
		if (!strcmp($temp, $modelID)){
			echo "name: ".$idVehicle_model->name;
			echo ", year: ".$idVehicle_model->year;
			echo " (brand ID: ".$idVehicle_model->brand_idOrganization.")<br>";
		}
	}	
}

// called from AnalystVehicles(). Data from the table Bitacora
function bitacoraData($vehicleID){
	$bitacoraData = "";
	$urlXMLfile = 'http://4me302-ht15.host22.com/index.php?table=Bitacora';
	$sxml = simplexml_load_file($urlXMLfile) or die("Error: Cannot  SimpleXML object"); 	/*read feed into SimpleXML object*/
	
	foreach($sxml->children() as $idBitacora) {
		$bitacVehiID = $idBitacora->Vehicle_idvehicle;

		if (!strcmp($bitacVehiID, $vehicleID)){
			$bitacoraData = "<p style='color:green;display:inline;'><br>Info from Bitacora:</p><br>";
			$bitacoraData .= "User ID ".$idBitacora->User_idUser.": ";
			$bitacoraData .= "start time: ".$idBitacora->start_time.", ";
			$bitacoraData .= "end time: ".$idBitacora->end_time."<br>";
		}
	}
	return $bitacoraData;
}

/*DATA FOR PLOTS*/
//get log data of the engine water temperature
function getEngWaTlogname($vehicleID){
	
	//get log file from the course database service
	$urlXMLfile = 'http://4me302-ht15.host22.com/index.php?table=Logs';
	$sxml = simplexml_load_file($urlXMLfile) or die("Error: Cannot  SimpleXML object");
	$logName = "";
	
	foreach($sxml->children() as $idLogs) {
		$logVehicleID = $idLogs->Sensor_Vehicle_idvehicle;
		$logSensor_typeID = $idLogs->Sensor_Sensor_type_idSensor_type;

		if ( (!strcmp($logVehicleID, $vehicleID)) && ($logSensor_typeID==2) ){ //Sensor type Id 2: Engine Water Temperature
			$logName = $idLogs->logname;
		}
	}
	return $logName;
}

//get log data from weight log
function getLoadWeightlogname($vehicleID){
	
	//get log file from the course database service
	$urlXMLfile = 'http://4me302-ht15.host22.com/index.php?table=Logs';
	$sxml = simplexml_load_file($urlXMLfile) or die("Error: Cannot  SimpleXML object");
	$logName = "";
	
	foreach($sxml->children() as $idLogs) {
		$logVehicleID = $idLogs->Sensor_Vehicle_idvehicle;
		$logSensor_typeID = $idLogs->Sensor_Sensor_type_idSensor_type;

		if ( (!strcmp($logVehicleID, $vehicleID)) && ($logSensor_typeID==6) ){ //Sensor type Id 6: vehicle load weight
			$logName = $idLogs->logname;
		}
	}
	return $logName;
}

/*this function returns a logfile by log name.
*The logfile is then reached by analyst.php at web server via ajax*/
function readLogFile($logName){

	//read log file content from the course database service
	//$url = "http://4me302-ht15.host22.com/veh17_EngineWaterTemp.log";
	$url = "http://4me302-ht15.host22.com/".$logName;
	$logFile = file_get_contents($url); //all data in a string

	//read lines in the text
	$nlLogFile = nl2br($logFile); //add line breaks

	return $nlLogFile;
}

?>



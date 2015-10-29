<?php

/*
 * This file contains functions for retreiving information from the course database service
 */
	
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

// get data for the director role-based page. Used in director.php
function directorStock($idOrg){
	
	/*historical stock market*/
	$utf8xml = utf8_encode(file_get_contents('http://4me302-ht15.host22.com/index.php?table=Organization'));
	$sxml = simplexml_load_string($utf8xml) or die("Error: Cannot  SimpleXML object"); 	/*read feed into SimpleXML object*/
	
	
	echo "<br><h2>Director role at ".$sxml->idOrganization[$idOrg]->name."</h2>";
	
	// historical stock market value in .CSV format
	$stockName = $sxml->idOrganization[$idOrg]->stockName;
	echo "<p style='display:inline'>Stock name: </p><p id='stockName' style='display:inline'>".$stockName."</p>";

	$hiStockURL = "http://ichart.finance.yahoo.com/table.csv?s=".$stockName."&g=w&ignore=.csv";	
	echo "<br><br><i>Historical stock market value from Yahoo CSV Finance API:</i><br>";
	echo "The latest weekly historical market: <a href='".$hiStockURL."'>go to csv</a>";	
}
	
// get data to the driver role-based page
function driverPage($orgID){
	echo "<br><h2>Driver role</h2>";
	
	$arrModelID = vehicles($orgID);
	video($arrModelID);
}

// get vehicles by organization ID. Used in the driver page
function vehicles($orgID){
	/*Table: Vehicle*/
	$urlXMLfile = 'http://4me302-ht15.host22.com/index.php?table=Vehicle';
	$sxml = simplexml_load_file($urlXMLfile) or die("Error: Cannot  SimpleXML object"); 	/*read feed into SimpleXML object*/
	$arrModelIDs; //store model ID:s, used for choosing videos
	$i=0;

	echo "<b>Vehicles available:</b><br>";
	foreach($sxml->children() as $idvehicle) {
		$temp = $idvehicle->VehicleOwner_idOrganization;

		/*strcmp = 0 if the strings are equal*/		
		if (!strcmp($temp, $orgID)){
			echo "<br><p style='color:blue;display:inline;'>vehicle ID: ".$idvehicle[0]['id'];
			$modelID = $idvehicle->Vehicle_model_idVehicle_model;
			echo ", plate: ".$idvehicle->plate."</p>"; 			
			echo "<br><i>Model ID ".$modelID.": </i>";
			echo moreModelInfo($modelID);
			$arrModelIDs[$i]=$modelID;
			$i=$i + 1;
		}
	}
	return $arrModelIDs;
}

// called from vehicles(). More info about the vehicle model.
function moreModelInfo($modelID){
	
	//Table: Vehicle_model
	$urlXMLfile = 'http://4me302-ht15.host22.com/index.php?table=Vehicle_model';
	$sxml = simplexml_load_file($urlXMLfile) or die("Error: Cannot  SimpleXML object"); 	//read feed to SimpleXML object
	
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

// choose which video or image of the vehicles to show to the driver
function video($arrModelID){
	
	$arr1Length = count($arrModelID);
	$arrModelVideo=null;
	$arr2Length;

	for($i = 0; $i< $arr1Length; $i++) {
		$arr2Length = count($arrModelVideo);

		//first model ID 
		if($arr2Length==0){
			$arrModelVideo[0]=$arrModelID[$i];
		}
		
		//check if the model ID already exists in the $arrModelVideo. Only show one video of each model
		else{
			for($j = 0; $j< $arr2Length; $j++) {
				if(!strcmp($arrModelVideo[$j], $arrModelID[$i])){ //0 if equal
				}
				else{
					$arrModelVideo[$j+1]=$arrModelID[$i];
				}
			}
		}
	}
	echo "<br><b>Media of vehicle models:</b><br>";
	
	$arr2Length = count($arrModelVideo);
	
	// select video or image
	for($k = 0; $k< $arr2Length; $k++) {
	  switch ($arrModelVideo[$k]) {
        case 1:
			echo "Video of model ID 1";
			echo '<br><iframe width="280" height="157.5" src="https://www.youtube.com/embed/KsAomYgYDA0" frameborder="0" allowfullscreen></iframe><br><br>';
            break;
        case 2:
			echo "Video of model ID 2";
			echo '<iframe width="280" height="157.5" src="https://www.youtube.com/embed/aztENkcnQ_8" frameborder="0" allowfullscreen></iframe>';
            break;
        case 3:
			echo "Video of model ID 3";
			echo '<iframe width="280" height="157.5" src="https://www.youtube.com/embed/tLlb0_E9M-o" frameborder="0" allowfullscreen></iframe>';
            break;
		case 4:
			echo "Video of model ID 4";
			echo '<iframe width="280" height="157.5" src="https://www.youtube.com/embed/nrXf75l7Wok" frameborder="0" allowfullscreen></iframe>';
			break;
        case 5:
			echo "Image of model ID 5<br>";
            echo '<img src="http://www.volvoce.com/SiteCollectionImages/VCE/Pictures%20and%20Videos/Soil%20Compaction/EMEA/SD160-SD190-SD200/Volvo_SD160_soil_compactor_delivers_large_soil_compactor_drum_performance.jpg" width="280"'; //couldn't find a video of this vehicle model
			break;
        default:
            echo "(no video availible for this vehicle)<br>";
	  }
	}
}

?>



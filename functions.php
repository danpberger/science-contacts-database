<?php

function createInterestsArray(){
	$query = "SELECT id, name FROM sc_interest_areas ORDER BY name";
	$result = mysql_query($query);	
	if (!$result) {
	    die('Invalid query: ' . mysql_error());
	} else {
		$int_arr = array();
		while ($interest = mysql_fetch_assoc($result) ) {
			$int_arr[] = $interest;
		}
		return $int_arr;
	}		
}

// for 'all' pages

function createSciConArray(){
	$query = "SELECT id, name FROM sc_science";
	$result = mysql_query($query);	
	if (!$result) {
	    die('Invalid query: ' . mysql_error());
	} else {
		$sci_arr = array();
		while ($sci = mysql_fetch_assoc($result) ) {
			$sci_arr[] = $sci;
		}
		return $sci_arr;
	}			
}


function betterIntArray($array){
	$ints = array();
	foreach ( $array as $interest ){
		$ints[$interest['id']] = $interest['name'];
	}
	return $ints;
}

function getInterests(){
	$query = "SELECT * FROM sc_contact_interest_areas";
	$result = mysql_query($query);
	$result2 = $result;	
	if (!$result) {
	    die('Invalid query: ' . mysql_error());
	} else {
		$ids = array();
		$pairs = array();
		$int_obj = array();
		// two arrays: 1 for each contact, 1 for contact and interest
		while ($person = mysql_fetch_assoc($result)){			
			$ids[] = $person['sc_contact_id'];
			$pairs[] = array($person['sc_contact_id'], $person['sc_interest_area_id']);						
		}
		$keys = array_unique($ids);
		foreach ($keys as $key){
			$set = array();
			foreach($pairs as $pair){
				if ($key == $pair[0]){
					$set[] = $pair[1];
				}
			}
			$int_obj[] = array($key, $set); // [contact_id, [interest_id, interest_id]]
		}
		return $int_obj;
	}	
}

//get all the contacts
function getContacts(){
	$query = "SELECT sc_contacts.*, sc_science.id AS sci_id, sc_science.name 
		FROM sc_contacts JOIN sc_science ON sc_contacts.refer_science_id = sc_science.id
		ORDER BY sc_contacts.last_name;";
	$result = mysql_query($query);	
	if (!$result) {
	    die('Invalid query: ' . mysql_error());
	} else {
		return $result;
	}	
}

//get a specific person for edit
function getContact($id){
	$query = "SELECT sc_contacts.*, sc_science.id AS sci_id, sc_science.name 
		FROM sc_contacts JOIN sc_science ON sc_contacts.refer_science_id = sc_science.id 
		WHERE sc_contacts.id = {$id}";
	$result = mysql_query($query);	
	if (!$result) {
	    die('Invalid query: ' . mysql_error());
	} else {
		return $result;	
	}	
}

// another edit function
function getMyInterests($id){
	$query = "SELECT * FROM sc_contact_interest_areas WHERE sc_contact_id = {$id};";
	$result = mysql_query($query);	
	if (!$result) {
	    die('Invalid query: ' . mysql_error());
	} else {
		$myintarray = array();
		while ( $thisint = mysql_fetch_assoc($result) ) {
			array_push($myintarray, $thisint['sc_interest_area_id']);
		}
		return $myintarray;	
	}
}

function saveInterests($id, $ints){
	$upquery = "INSERT INTO sc_contact_interest_areas (sc_contact_id, sc_interest_area_id) VALUES";
	foreach ($ints as $area ){
		$upquery = $upquery . " ({$id}, {$area}),";
	}
	$upquery = substr($upquery, 0, -1) . ";";
	$delquery = "DELETE FROM sc_contact_interest_areas WHERE sc_contact_id = {$id}; ";
	$del = mysql_query($delquery);
	if (!$del) {
	    die('Invalid query in saved interests (delete): ' . mysql_error());
	} else {
		$result = mysql_query($upquery);	
		if (!$result) {
		    die('Invalid query in saved interests: ' . mysql_error() . $upquery);
		} else {
			return true;
		}
	}			
}

?>
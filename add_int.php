<?php
include("../connection.php");

addInt();
$interests_array = createInterestsArray();

function addInt(){
	$query = "INSERT INTO sc_interest_areas (name) VALUES ('{$_GET['name']}');";	
	//echo $query;
	$result = mysql_query($query);	
	if (!$result) {
	    die('Invalid query: ' . mysql_error());
	} else {
		return true;
	}	
}

function createInterestsArray(){
	$query = "SELECT id, name FROM sc_interest_areas;";
	$result = mysql_query($query);	
	if (!$result) {
	    die('Invalid query: ' . mysql_error());
	} else {
		return $result;
	}		
}

$allInts = array();

 while ($int = mysql_fetch_assoc($interests_array)) {
	array_push($allInts, array($int['id'], $int['name'])); 
}

echo json_encode($allInts);	
?>
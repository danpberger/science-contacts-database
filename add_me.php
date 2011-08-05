<?php
include("../connection.php");

addMe();
$contacts_array = createSciConArray();

function createSciConArray(){
	$query = "SELECT id, name FROM sc_science";
	$result = mysql_query($query);	
	if (!$result) {
	    die('Invalid query: ' . mysql_error());
	} else {
		return $result;
	}			
}

function addMe(){
	$name = addslashes($_GET['name']);
	$query = "INSERT INTO sc_science (name, email) VALUES ('{$name}', '{$_GET['email']}');";	
	//echo $query;
	$result = mysql_query($query);	
	if (!$result) {
	    die('Invalid query: ' . mysql_error());
	} else {
		return true;
	}	
}

$retCons = array();

 while ($contact = mysql_fetch_assoc($contacts_array)) {
	array_push($retCons, array($contact['id'], $contact['name'])); 
}

echo json_encode($retCons);	
	
?>
	
	
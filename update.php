<?php
include("../connection.php");
include("functions.php");

//variables
$id = $_GET['id'];
$first = ucfirst($_GET['first']);
$last = addslashes(ucfirst($_GET['last']));
$phone = $_GET['phone'];
$twitter = preg_replace('/@/', '', $_GET['twitter']);
$email = $_GET['email'];
$pub = addslashes($_GET['pub']);
$puburl = $_GET['puburl'];
$notes = addslashes($_GET['notes']);
$perm = $_GET['perm'];
$ints = explode(',', $_GET['ints']);

if (updateContacts($id, $first, $last, $phone, $twitter, $email, $pub, $puburl, $notes, $perm) == true){
	if (saveInterests($id, $ints) == true){
		echo $first . " " . $last;
	}
}

//functions


function updateContacts($id, $first, $last, $phone, $twitter, $email, $pub, $puburl, $notes, $perm){
	$query = "UPDATE sc_contacts SET 
		first_name = '{$first}', 
		last_name = '{$last}', 
		email = '{$email}', 
		phone = '{$phone}', 
		twitter =  '{$twitter}', 
		affiliation = '{$pub}', 
		affiliation_web = '{$puburl}', 
		permission = '{$perm}', 
		notes = '{$notes}'
		WHERE id = {$id}";
	
	$result = mysql_query($query);
	if (!$result) {
	    die('Invalid query: ' . mysql_error());
	} else {
		return true;
	}
}
?>
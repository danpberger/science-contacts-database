<?php
include("../connection.php");
include("functions.php");

//variables
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
$sci = $_GET['sci'];

$id = saveContact($first, $last, $phone, $twitter, $email, $pub, $puburl, $notes, $perm, $sci);
if ($id != false){
	if (saveInterests($id, $ints) == true){
		echo $id;
	}
}

//functions

function saveContact($first, $last, $phone, $twitter, $email, $pub, $puburl, $notes, $perm, $sci){
	$query = "INSERT INTO sc_contacts 
	( first_name, last_name, email, phone, twitter, affiliation, affiliation_web, permission, notes, refer_science_id)
	VALUES ('{$first}', '{$last}', '{$email}','{$phone}', '{$twitter}', '{$pub}', '{$puburl}', '{$perm}', '{$notes}', '{$sci}')";
		
	$result = mysql_query($query);
	if (!$result) {
	    die('Invalid save squery: ' . mysql_error() . $query);
	} else {
		return mysql_insert_id();
	}
}


?>
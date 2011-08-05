<?php
include("../connection.php");

// VARIABLES //
$file_handle = fopen("ScienceNEWS_contacts.csv", "r+") or die("Can't open file");
$row = 0; $col = 0;
$contacts_sql = "INSERT INTO sc_contacts
   					(first_name, email, affiliation, refer_science_id, twitter, permission, affiliation_web, notes, last_name, phone)
   					VALUES ";
$contact_areas_sql = "INSERT INTO sc_contact_interest_areas
					(sc_contact_id, sc_interest_area_id)
					 VALUES ";
$interest_areas_sql = "INSERT INTO sc_interest_areas (name)
					 VALUES ";

// FUNCTION CALLS //
$interest_array = createInterestArray();
$sci_contacts_array = createSciConArray();


// FUNCTIONS //
function createSciConArray(){
	$contacts_array = array();
	$query = "SELECT id, name FROM sc_science";
	$result = mysql_query($query);		
	while ($row = mysql_fetch_assoc($result)) {
	    $interest = array($row["id"], $row["name"]);
		array_push($contacts_array, $interest);
	}	
	return $contacts_array;
}

function createInterestArray(){
	$interests_array = array();	
	$query = "SELECT * FROM sc_interest_areas";
	$result = mysql_query($query);		
	while ($row = mysql_fetch_assoc($result)) {
	    $interest = array($row["id"], $row["name"]);
		array_push($interests_array, $interest);
	}	
	return $interests_array;
}

function validateEmail($email){
	if(eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email)) {
  		return $email;
	} else {
  		return "Invalid email address.";
	}	
}

function saveContacts($contacts_sql, $contact_areas_sql){
	$result = mysql_query($contacts_sql);
	$id = mysql_insert_id();	
	if (!$result) {
	    die('Invalid query: ' . mysql_error());
	} else {
		echo "first part is done, yo.<br>";
		/*$newq = preg_replace('/??/', $id, $contact_areas_sql);
		
		$result2 = mysql_query($newq);	
		if (!$result2) {
		    die('Invalid query: ' . mysql_error());
		} else {
			echo"Bingo, bitch!";
		}
		*/
	}	
}

// ACTIONS //

while (($line_of_text = fgetcsv($file_handle, 1000, ",")) !== FALSE) {
	//skip header row
	$col = 0;
	if ($row > 0){		
		foreach ($line_of_text as $tt){ 
			$tt = addslashes($tt);
			if ($col!=3){ 
				if ($col == 0){
					$contacts_sql .= "('" . $tt . "',";
				} elseif ($col == 1){
					$tt = validateEmail($tt);
					$contacts_sql .= "'" . $tt . "', ";	
				}elseif ($col == 4){
					$set = false;
					$first = explode(' ', $tt);
					foreach ($sci_contacts_array as $con){
						$fir = explode(" ", $con[1]);
						if ($fir[0] == $first[0]){
							 $contacts_sql .= "'" . $con[0] . "',";
							 $set = true;
						}
					}	
					if($set == false){
						$contacts_sql .= "'',";
					}
				} elseif ($col == 10){
					 $contacts_sql .= "'" . $tt . "'),";
				} else {
				 	$contacts_sql .= "'" . $tt . "', ";
				}	
			} elseif ($col == 3){
				// add interests to the sc_contact_interests table
				$int_array = explode(",", $tt);
				foreach ($int_array as $int){
					$contact_areas_sql .= "(";	
					foreach ($interest_array as $isa){						
						if (trim($int) == $isa[1]){
							$contact_areas_sql .= "'??'" . ", '" . $isa[0] ."'";		
						}						
					}
					$contact_areas_sql .= "), ";								
				}				
			}
		 	$col++;
		}		
	}	

	$row++;
}

$contacts_sql = substr($contacts_sql, 0, -1) . ";";

$contact_areas_sql = preg_replace('/\(\),/', '', $contact_areas_sql);
$contact_areas_sql = substr($contact_areas_sql, 0, -2) . ";";

saveContacts($contacts_sql, $contact_areas_sql);

//echo $contact_areas_sql . "<br>";
	//echo $contacts_sql . "<br>";


?>
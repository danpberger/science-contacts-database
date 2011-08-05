<?php
include("../connection.php");
include("functions.php");

// variables
$all_contacts = getContacts();
$contact_interests = getInterests();
$sci_contacts_array = createSciConArray();
$interests_array = createInterestsArray();
$better_int_arr = betterIntArray($interests_array);

?>

<html>
<head>
	<title>Science news contacts manager</title>
	<meta name="robots" content="noindex,nofollow">
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
    <script type="text/javascript" src="js/contacts_crud.js"></script>
    <script type="text/javascript" src="js/jquery.quicksearch.js"></script>
    <link rel="stylesheet" href="css/contacts_crud.css" type="text/css" />
	<?php if ($_GET['id']) {?>
		<script type="text/javascript">
			$(document).ready(function(){
				$("#d_<?php echo $_GET['id']; ?>").show();
			});
		</script>
	<?php } ?>
</head>
<body>
<div class="wrapper">
<h1>All Contacts</h1>

<div class="tools">Filter by:<br />
Interest area - 
<select id="int_list">
	<option>--</option>
<?php foreach ( $interests_array as $interest ) { ?>
	<option value="<?php echo $interest['name']?>"><?php echo $interest['name']?></option>
<?php }?>
</select>
&nbsp;|&nbsp; 
Added by - 
<select id="add_list">
	<option>--</option>
<?php foreach ( $sci_contacts_array as $contact) {?>
	<option value="<?php echo $contact['name']?>"><?php echo $contact['name']?></option>
<?php }?>
</select>
&nbsp;|&nbsp; <span id="reset">Reset</span> &nbsp;|&nbsp; <span id="add_new">Add a new contact</span> &nbsp;|&nbsp; <span id="msg_pref">Email message preferences</span>
 &nbsp;|&nbsp;<input type="text" id="search" /> <span>search</span>
</div>

<div id="sub_tools" style="display:none;">
	<div id="filter_on"></div>
	<div id="actions">
		<span id="show_e">Show emails</span> &nbsp;|&nbsp; <span id="do_twit">Do something cool with Twitter</span>
	</div>
	<br style="clear:both" />
</div>

<div id="edit_em" style="display:none;">
	<div>Subject: <input type="text" size="45" name="sub" id="sub" value="You might enjoy this article from Science" /></div>
	<div>Note: <textarea name="msg" id="msg" cols="60" rows="8">I think you would love this new story from Science.%0ASincerely,%0ADavid Grimm</textarea><span class="small" id="msg_cancel">cancel</span></div>
</div>

<ul class="contacts_list">

<?php while ($contact = mysql_fetch_assoc($all_contacts)) {?>
	<li id="c_<?php echo $contact['id']; ?>" class="s_<?php echo $contact['sci_id']; ?>">
		<span class="fullname" id="fullname_<?php echo $contact['id']; ?>"><?php echo $contact['first_name'] . " " . $contact['last_name'];?></span><br />
		<span class="small"><a class="cl_em" id="a_<?php echo $contact['id']; ?>" href="mailto:<?php echo $contact['email'];?>"><?php echo $contact['email'];?></a></span><br />
		<span class="small pub_names" id="pubname_<?php echo $contact['id']; ?>"><strong><?php echo $contact['affiliation']; ?></strong></span>
	</li>
<?php }?>
</ul>

<?php mysql_data_seek( $all_contacts, 0 );
while ($all_info = mysql_fetch_assoc($all_contacts)) { ?>

	<div id="d_<?php echo $all_info['id']; ?>" class="contact_full" style="display:none;">
	<div id="message_<?php echo $all_info['id']; ?>" class="message"></div>
		<h2 id="na_<?php echo $all_info['id']; ?>"><?php echo $all_info['first_name'] . " " . $all_info['last_name'];?></h2>
		<div id="em_<?php echo $all_info['id']; ?>"><a name="dis" id="ah_<?php echo $all_info['id']; ?>" class="cl_em" href="mailto:<?php echo $all_info['email'];?>"><?php echo $all_info['email'];?></a></div>
		<div id="tw_<?php echo $all_info['id']; ?>"><?php echo $all_info['twitter'];?></div>
		<div id="ph_<?php echo $all_info['id']; ?>"><?php echo $all_info['phone'];?></div>
		<div id="no_<?php echo $all_info['id']; ?>"><?php echo $all_info['notes'];?></div>
		<div class="each_ints" id="int_<?php echo $all_info['id']; ?>">
		<?php foreach ($contact_interests as $set){
			if ($set[0] == $all_info['id']){
				foreach ($set[1] as $interest2){ 
					echo "&bull; " . $better_int_arr[$interest2] . " ";	
				 }
			} }?>
		</div>
		<div id="n_<?php echo $all_info['sci_id']; ?>" class="added">Added by <?php echo $all_info['name']; ?></div>
		<?php if ($all_info['sci_id'] == true){
				$permis = "It's OK to contact this person";
			} else {
				$permis = "Check before contacting"; }?>
		<div id="perm_<?php echo $all_info['id']; ?>"><strong><em><?php echo $permis; ?></em></strong></div>
	
		<input type="button" value="Edit" name="edit" id="ed_<?php echo $all_info['id']; ?>" />
	</div>
	
	<div id="edit_<?php echo $all_info['id']; ?>" class="edit" style="display:none;">
		<div>
			<div class="err" id="fir_error_<?php echo $all_info['id']; ?>"></div>
			<input type="text" name="firstname" id="f_firstname_<?php echo $all_info['id']; ?>" value="<?php echo $all_info['first_name']; ?>" />
			<div class="err" id="last_error_<?php echo $all_info['id']; ?>"></div>
			<input type="text" name="lastname" id="f_lastname_<?php echo $all_info['id']; ?>" value="<?php echo $all_info['last_name'] ?>"  />
		</div>
		<div id="em_<?php echo $all_info['id']; ?>">
			<div class="err" id="email_error_<?php echo $all_info['id']; ?>"></div>
			email: <input type="text" name="email" id="f_email_<?php echo $all_info['id']; ?>" value="<?php echo $all_info['email']; ?>" />
		</div>
		<div>phone:<input type="text" name="phone" id="f_phone_<?php echo $all_info['id']; ?>" value="<?php echo $all_info['phone']; ?>" /></div>
		<div>twitter: <input type="text" name="twitter" id="f_twitter_<?php echo $all_info['id']; ?>" value="<?php echo $all_info['twitter']; ?>" /></div>
		<div id="f_pub_<?php echo $all_info['id']; ?>">
			<div class="err" id="pub_error_<?php echo $all_info['id']; ?>"></div>
			primary publication: <input type="text" name="pub_name" id="f_pubname_<?php echo $all_info['id']; ?>" value="<?php echo $all_info['affiliation'] ?>" />
		</div>
		<div>primary publication URL: <input type="text" name="puburl" id="f_puburl_<?php echo $all_info['id']; ?>" value="<?php echo $all_info['affiliation_web']; ?>" /></div>
		<div class="form_el" id="f_area">
		Contact's area of expertise:<br />
		<div class="err" id="area_error_<?php echo $all_info['id']; ?>"></div>
		<div id="allinterests_<?php echo $all_info['id']; ?>">
		<?php foreach ( $interests_array as $int_ed ) {  
			$checked = null;		
			foreach ( $contact_interests as $thisint) {
				if ($thisint[0] == $all_info['id']){
					foreach($thisint[1] as $in){
						if ($in == $int_ed['id']){
							$checked = 'checked';
						}
					}
					
				}
			} 
			?>
			<input type="checkbox" name="interests[]" value="<?php echo $int_ed['id']?>" <?php echo $checked; ?> /> <span><?php echo $int_ed['name'];?></span><br />
		<?php }?>
		</div>
			<div class="cursor noint" id="noint_<?php echo $all_info['id']?>">Add another interest area</div>
			<div class="add_box" id="addint_<?php echo $all_info['id']?>" style="display:none;">
				interest area: <input type='text' name='add_int' id='newint_<?php echo $all_info['id']?>' /> <br /> 
				<input type='button' value='save' name="save_new_int" id='intsubbtn_<?php echo $all_info['id']?>' />
			</div>
		</div>
		<div class="form_el" id="f_perm">
			Do we need permission to contact this person?
			<br />
			<select name="permission" id="permiss_<?php echo $all_info['id']; ?>">
				<option value="false">no</option>
				<option value="true">yes</option>
			</select>
		</div>

		<div class="form_el" id="f_note">
		Notes:<br />
		<textarea name="notes" id="notes_<?php echo $all_info['id']; ?>" rows="10" cols="30"><?php echo $all_info['notes']; ?> </textarea>
		</div>		
	
		<input type="button" value="Update" name="update" id="up_<?php echo $all_info['id']; ?>" />
	</div>

<?php }?>

	<div id="new_contact" class="edit" style="display:none;">
		
		<div id="f_first">
			<div class="err" id="fir_error"></div>
			<span>* Contact's First name:</span> <input type="text" name="f_firstname" id="f_firstname" value="" />
		</div>
		
		<div id="f_last">
		<div class="err" id="last_error"></div>
		<span>* Contact's Last name:</span> <input type="text" name="f_lastname" id="f_lastname" value=""  />
		</div>
		
		<div id="em">
			<div class="err" id="email_error"></div>
			<span>* Contact's email:</span> <input type="text" name="email" id="f_email" value="" />
		</div>
		
		<div>
			<span>Contact's phone: </span><input type="text" name="phone" id="f_phone" value="" />
		</div>
		
		<div>
			<span>Contact's Twitter handle:</span> <input type="text" name="twitter" id="f_twitter" value="" />
		</div>
		
		<div id="f_pub">
			<div class="err" id="pub_error"></div>
			<span>* Primary publication for whom this person writes:</span> <input type="text" name="pub_name" id="f_pubname" value="" />
		</div>
		
		<div>
			<span>Primary publication website URL: </span><input type="text" name="f_puburl" id="f_puburl" value="" />
		</div>
		
		<div>
			<em> One publication and URL, please. All secondary publications can go in the notes field.</em>
		</div>
			
		<div class="form_el" id="f_area">
			* Contact's area of expertise:<br />
			<div class="err" id="area_error"></div>
			<div id="allinterests">
				<?php foreach ( $interests_array as $int_ed ) {  ?>
					<input type="checkbox" name="interests[]" value="<?php echo $int_ed['id']?>" /> <span><?php echo $int_ed['name'];?></span><br />
				<?php }?>
			</div>
			<div id="f_add_new_int" class="cursor">Add another interest area</div>
			<div class="add_box" id="f_add_int" style="display:none;">
				interest area: <input type='text' name='add_int' id='f_newint' /> <br /> 
				<input type='button' value='save' id='int_sub_btn' />
			</div>
		</div>
		<div id="f_name">
			* Your name:
			<div class="err" id="you_error"></div>
			<select id="f_myname" name="my_name">
				<option value="">--</option>
			<?php foreach ( $sci_contacts_array as $name) {?>
				<option value="<?php echo $name['id']?>"><?php echo $name['name']?></option>
			<?php }?>
			</select>
			<div id="no_me" class="cursor">Add my name here</div>
			<div id="add_me" class="add_box" style="display:none;">
				my name: <input type='text' name='myname' id='myname' /><br />
				my email: <input type='text' name='myemail' id='myemail' /> <br /> 
				<input type='button' value='save' id='em_sub_btn' />
			</div>
		</div>
		
		<div>
			Do we need permission to contact this person?<br />
			<select id="f_permission">
				<option value="false">no</option>
				<option value="true">yes</option>
			</select>
		</div>
			
		<div class="form_el" id="f_note">
			Notes:<br />
			<textarea name="notes" rows="10" cols="30"></textarea>
		</div>
	
		<input type="button" value="Save" name="save" />
	</div>
</div>
</body>
</html>
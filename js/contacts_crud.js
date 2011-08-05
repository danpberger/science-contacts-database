$(document).ready(function(){
	
	colorLi();
	
	$("#f_add_new_int").click(function(){
		$("#f_add_int").show("slow");
	})
	
	$("#no_me").click(function(){
		$("#add_me").show("slow");
	});
	
	$("#msg_pref, #msg_cancel").click(function(){
		$("#edit_em").toggle("slow");
	});
	
	$(".noint").click(function(){
		var id = $(this).attr("id").split("_")[1];
		$("#addint_" + id).show("slow");
	});
	
	$("#em_sub_btn").click(function(){
		saveMe();
	});
	
	$(":button[name=save_new_int]").click(function(){
		var id = $(this).attr("id").split("_")[1];
		saveInt(id);
	});
	
	$("#int_sub_btn").click(function(){
		var id = null;
		saveInt(id);
	});
	
	$("#show_e").click(function(){
		getEmails();
	});
	
	$("ul.contacts_list li").click(function(){
		var id = $(this).attr("id").split("_")[1];
		$(".contact_full, .edit").hide();
		$("#d_" + id).toggle("slow");
	});
	
	$('select#int_list').change(function() {
		  var int = $(this).val();
		  filterByInterest(int);
	});
	
	$('select#add_list').change(function() {
		  var name = $(this).val();
		  filterByName(name);
	});
	
	$("#reset").click(function(){
		reset();
	});
	
	$("#add_new").click(function(){
		reset();
		$("#new_contact").show();
	});
	
	$(":button[name=edit]").click(function(){
		var id = $(this).attr("id").split("_")[1];
		setUpEdit(id);
	});
	
	$(":button[name=update]").click(function(){
		var id = $(this).attr("id").split("_")[1];
		if (validate(id) == false){
			return false;
		} else {
			saveEdit(id);
		}
	});
	
	$(":button[name=save]").click(function(){
		var id = null;
		if (validate(id) == false){
			return false;
		} else {
			saveNew();
		}		
	});
	
	$(".cl_em").click(function(){
		var id = $(this).attr("id").split("_")[1];
		if ($(this).attr("name") == "dis"){
			var loc = "a#ah_";
		} else {
			var loc = "a#a_";
		}
		var sub = $("#sub").val();
		var msg = $("#msg").val();
		updateEmPref(id, sub, msg, loc);
	});
	
	$("#search").quicksearch("ul.contacts_list li", {'show': function(){colorLi();}});
	
});

function updateEmPref(id, sub, msg, loc){
	
	var em = $(loc + id).attr("href");
	var name = $("#fullname_" + id).text();
	var params = em + "?subject=" + sub + "&body=Dear " + name + "%0A" + msg;
	$(loc + id).attr("href", params);
}

function getEmails(){
	var ems = '';
	$("ul.contacts_list li:visible span a").each(function(){
		ems = ems + " " + $(this).text() + ";";
	});
	alert(ems);
}

function saveNew(){
	var fir = $("#f_firstname").val();
	var last = $("#f_lastname").val();
	var email = $("#f_email").val();
	var phone = $("#f_phone").val();
	var twit = $("#f_twitter").val();
	var pub = $("#f_pubname").val();
	var puburl = $("#f_puburl").val();
	var perm = $("#f_permission").val();
	var notes = $("#f_note").val();
	var sci = $("#f_myname").val();
	var intarray = [];
	$("#allinterests input:checkbox:checked").each(function(){
		intarray.push($(this).val());
	});
	var ints = intarray.toString();
	$.ajax({
		data:'sci=' + sci + '&first=' + fir + '&last=' + last + '&email=' + email + '&phone=' + phone + '&twitter=' + twit + '&pub=' + pub + '&puburl=' + puburl + '&perm=' + perm + '&notes=' + notes + '& ints=' + ints,
		type:'get', 
		url: 'save.php',
		//url: '../../../feat_php/contacts/save.php',
		success: function(msg){	
			//reload page with the new person picked.
			window.location = "all2.php?id=" + msg;
		}
	}); 
}

function saveEdit(id){
	var fir = $("input#f_firstname_" + id).val();
	var last = $("input#f_lastname_" + id).val();
	var email = $("input#f_email_" + id).val();
	var phone = $("input#f_phone_" + id).val();
	var twit = $("input#f_twitter_" + id).val();
	var pub = $("input#f_pubname_" + id).val();
	var puburl = $("input#f_puburl_" + id).val();
	var perm = $("#permiss_" + id).val();
	var notes = $("#notes_" + id).val();
	var intarray = [];
	var inters = [];
	$("#allinterests_" + id + " input:checkbox:checked").each(function(){
		intarray.push($(this).val());
		inters.push($(this).next("span").text());
	});
	var ints = intarray.toString();
	$.ajax({
		data:'id=' + id + '&first=' + fir + '&last=' + last + '&email=' + email + '&phone=' + phone + '&twitter=' + twit + '&pub=' + pub + '&puburl=' + puburl + '&perm=' + perm + '&notes=' + notes + '& ints=' + ints,
		type:'get', 
		url: 'update.php',
		//url: '../../../feat_php/contacts/update.php',
		success: function(msg){			
			var html = "You successfully updated " + msg + "'s account";
			$(".edit").hide();
			$("#d_" + id).show("slow");
			$("#message_" + id).text(html);
			$("#na_" + id).text(fir + ' ' + last);
			$("#em_" + id).text(email);
			$("#ph_" + id).text(phone);
			$("#tw_" + id).text(twit);
			$("#no_" + id).text(notes);
			if (perm == true){
				var pmes = "<strong></em>It's OK to contact this person</em></strong";
			} else {
				var pmes = "<strong></em>Check before contacting</em></strong>";
			}
			$("#perm_" + id).html(pmes);
			$("#int_" + id).text(inters.toString());
			
		}
	}); 
}

function setUpEdit(id){
	$(".contact_full").hide();
	$("#edit_" + id).show();
}

function reset(){
	$("ul.contacts_list li").show();
	colorLi();
	$(".contact_full, .edit, #sub_tools, #edit_em ").hide();
	$("#filter_on").empty();
}

function colorLi(){
	$("ul.contacts_list li").css("background-color", "#fff");
	$("ul.contacts_list li:visible:even").css("background-color", "#E2E8EC");
}

function  filterByName(name){
	$("ul.contacts_list li").hide();
	$(".added").each(function () {		
		if ($(this).text().match(name)){
			var new_id = $(this).attr("id").split('_')[1];
			$("ul.contacts_list li.s_" + new_id).show();
		}
    });
	cleanUpFilter(name);
}

function filterByInterest(int){
	$("ul.contacts_list li").hide();
	$(".each_ints").each(function () {
		// look for interests in here, then hide <li> without it....		
		if ($(this).text().match(int)){
			var new_id = $(this).attr("id").split('_')[1]
			$("ul.contacts_list li#c_" + new_id).show();
		}
    });
	cleanUpFilter(int);
}

function cleanUpFilter(filter){
	$("#filter_on").empty().html("<strong>Filter by " + filter + "</strong>")
	$(".contact_full, .edit").hide();
	$("#sub_tools").show();
	colorLi();
}

function saveInt(id){
	if (id == null){
		var int = $("#f_newint").val();
	} else {
		var int = $("#newint_" + id).val();
	}
	
	$.ajax({
		data:'name='+int,
		type:'get', 
		url: 'add_int.php',
		//url: '../../../feat_php/contacts/add_int.php',
		success: function(msg){
			var msg_obj = eval('(' + msg + ')');
			var html = '';
			var len = msg_obj.length;
			for (i=0; i<len; i++) {
				html = html + "<input type='checkbox' name='interests[]' value='" + msg_obj[i][0] + "' />" + msg_obj[i][1] + " <br />"
			} 
			if (id == null){
				$("#allinterests").empty().html(html);
			}else{
				$("#allinterests_" + id).empty().html(html);
			}
			$(".add_box").hide();
		}
	}); 
}

function saveMe(){
	var email = $("#myemail").val();
	var name = $("#myname").val();
	$.ajax({
		data:'name='+name+'&email='+email,
		type:'get', 
		url: 'add_me.php',
		//url: '../../../feat_php/contacts/add_me.php',
		success: function(msg){
			$("#add_me").hide();
			var msg_obj = eval('(' + msg + ')');
			var html = '';
			var len = msg_obj.length;
			for (i=0; i<len; i++) {
				if (name == msg_obj[i][1]){
					html = html + "<option value='" + msg_obj[i][0] + "' selected='selected'>" +  msg_obj[i][1] + "</option>";
				} else {
					html = html + "<option value='" + msg_obj[i][0] + "'>" +  msg_obj[i][1] + "</option>";
				}			
			} 
			$("#f_myname").empty().html(html);
			$(".add_box").hide();
		}
	}); 
}
	
function validate(id){
	if (id != null){
		var id_tail = "_" + id;
	} else {
		var id_tail = "";
	}
	var email = $("#f_email" + id_tail).val();	
	
	if(email == "" || isValidEmailAddress(email)==false){
		$("#em" + id_tail).css({"border": "1px solid red" });
		$("#email_error" + id_tail).text("Please enter a valid email address");
		var proceed = false;
	} else {
		$("#em" + id_tail).css({"border": "none" });
		$("#email_error" + id_tail).empty();
	}
		
	if ($("#f_myname" + id_tail).val() == 0){
		$("#f_name" + id_tail).css({"border": "1px solid red"});
		$("#you_error" + id_tail).text("Please tell us who you are");
		var proceed = false;
	}else{
		$("#f_name" + id_tail).css({"border": "none"});
		$("#you_error" + id_tail).empty();
	}

	if ($("#f_firstname" + id_tail).val() == 0){
		$("#f_first" + id_tail).css({"border": "1px solid red" });
		$("#fir_error" + id_tail).text("Please enter a first name");
		var proceed = false;
	}else{
		$("#f_first" + id_tail).css({"border": "none"});
		$("#fir_error" + id_tail).empty();
	}
	
	if ($("#f_lastname" + id_tail).val() == 0){
		$("#f_last" + id_tail).css({"border": "1px solid red"});
		$("#last_error" + id_tail).text("Please enter a last name");
		var proceed = false;
	}else{
		$("#f_last" + id_tail).css({"border": "none" });
		$("#last_error" + id_tail).empty();
	}
	
	if ($("#f_pubname" + id_tail).val() == 0){
		$("#f_pub" + id_tail).css({"border": "1px solid red" });
		$("#pub_error" + id_tail).text("Please enter a publication name");
		var proceed = false;
	}else{
		$("#f_pub" + id_tail).css({"border": "none" });
		$("#pub_error" + id_tail).empty();
	}
	
	var $b = $('#allinterests' + id_tail + ' input[type=checkbox]');
	if (($b.filter(':checked').length) < 1){
		$("#f_area" + id_tail).css({"border": "1px solid red" });
		$("#area_error" + id_tail).text("Please enter at least one are of expertise");
		var proceed = false;
	}else{
		$("#f_area" + id_tail).css({"border": "none" });
		$("#area_error" + id_tail).empty();
	}
	
	if (proceed == false){
		$("#edit_" + id).scrollTop(0);
		return false;
	} else {
		return true;
	}
}

function isValidEmailAddress(emailAddress) {
	var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
	return pattern.test(emailAddress);
}
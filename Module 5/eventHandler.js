
function hideall(){
	document.getElementById("eventDeleteButton").style.visibility="hidden";
	document.getElementById("addaEventButton").style.visibility="hidden";
	document.getElementById("editEventButton").style.visibility="hidden";

	document.getElementById("userspaceDiv").style.visibility="hidden";
}
hideall();

// ################### DELETE EVENT AJAX #######################
function deleteEventAjax(){
	var eventIdTracker = trackEvent;
	var stoken = usernameToken;
	var urlstring = "eventId=" + encodeURIComponent(eventIdTracker) + "&token="+encodeURIComponent(stoken);
	// console.log(urlstring);
	var xhttp = new XMLHttpRequest();
	xhttp.open("POST","deleteEventAjax.php",true);
	//required for post requests
	xhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xhttp.addEventListener("load",deleteEventCallBack,false);
	xhttp.send(urlstring);
}

function deleteEventCallBack(event){
	var deleteJsonData = JSON.parse(event.target.responseText);
	alert(deleteJsonData.sucess ? "Event Deleted" : "something went wrong!");
}

//bind delete button to call functions
$('deleteEventButtonSubmit').addEventListener("click", function(event){
			console.log("delete pressed");
			deleteEventAjax();
			updateCalendar();
			getCalendarEvents();

		},true);


// ################### EDIT EVENT AJAX #######################
function editEventAjax(){
	var editEventTitle = $('editEventTitle').value;
	var editEventTime = $('editEventTimeHour').value;
	var editEventTimeMinute= $('editEventTimeMinute').value;
	var editEventIdTracker = trackEvent;
	var stoken = usernameToken;


	var urlstring = "newEventTitle=" + encodeURIComponent(editEventTitle)
	+"&newEventTime=" + encodeURIComponent(editEventTime)
	+"&newEventTimeMinute=" + encodeURIComponent(editEventTimeMinute)
	+"&eventIdTracker=" + encodeURIComponent(editEventIdTracker)
	+"&token="+encodeURIComponent(stoken) ;

	var xhttp = new XMLHttpRequest();
	xhttp.open("POST","updateEventAjax.php",true);
	//required for post requests
	xhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xhttp.addEventListener("load", function(event){
		var jsonData = JSON.parse(event.target.responseText);
		if(jsonData.sucess){
			alert("event updated!")
				$('editEventTitle').value = "";
				$('editEventTimeHour').value = "";
				$('editEventTimeMinute').value = "";
		}
		else{
			alert("Something went wrong!")
			exit(0);
		}

	} ,false);


	xhttp.send(urlstring);
}

	$('editEventSubmit').addEventListener("click", function(event){
			editEventAjax();
			updateCalendar();
			getCalendarEvents();
		},true);




// ################### SHARE EVENT AJAX ####################### 

function shareEventsAjax(){
	sharedEventId = trackEvent;
	shareUserId = document.getElementById("shareUserId").value;
	if(trackEvent == null){
		alert("user not found");
		$('shareUserId').value = "";
	}

	var urlstring = "shareUserId=" + encodeURIComponent(shareUserId) 
	+ "&sharedEventId="+encodeURIComponent(sharedEventId);
	// + "&token="+encodeURIComponent(usernameToken);
	// console.log(urlstring);
	var xhttp = new XMLHttpRequest();
	xhttp.open("POST","shareEventUserAjax.php",true);
	//required for post requests
	xhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xhttp.addEventListener("load",shareEventsCallBack,false);
	xhttp.send(urlstring);
}

function shareEventsCallBack(event){
	var shareJsonData = JSON.parse(event.target.responseText);
	alert(shareJsonData.sucess ? "Event Shared!" : "something went wrong!");
}

	$('shareEvent').addEventListener("click", function(event){
			shareEventsAjax();
			updateCalendar();
			getCalendarEvents();

		},true);

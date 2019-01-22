
 function $(id){
	return document.getElementById(id)
}
//loads document before calling any function (since it is being called at the top of html document)


//keep the user token after log in into a variable to be passed in other ajax calls
var usernameToken;


// ################### LOGIN  AJAX ####################### 

function loginAjax(event){

	var  usernameLogin = $('usernameLogin').value;
	var  usernamePassword = $('usernamePassword').value;
	console.log(urlstring);

	var urlstring = "usernameLogin=" + encodeURIComponent(usernameLogin)+"&usernamePassword=" + encodeURIComponent(usernamePassword);
	var xhttp = new XMLHttpRequest();
	xhttp.open("POST","loginAjax.php",true);
	//required for post requests
	xhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xhttp.addEventListener("load",loginAjaxCallBack,false);
	xhttp.send(urlstring);
}

function loginAjaxCallBack(event){
	var loginJsonData = JSON.parse(event.target.responseText);

	alert(loginJsonData.sucess ? "You have been logged in!" : "You were not logged in!");
	if(loginJsonData.login){
		userLoggedIn = true;
		// token_val = loginJsonData.sessionToken;
		usernameToken = loginJsonData.sessionToken;
		$('closePopup').style.visibility="hidden";
		$('openPopup').style.visibility="hidden";
		$('popupLogin').style.visibility="hidden";

		document.getElementById("eventDeleteButton").style.visibility="visible";
		document.getElementById("addaEventButton").style.visibility="visible";
		document.getElementById("editEventButton").style.visibility="visible";
		document.getElementById("userspaceDiv").style.visibility="visible";
		document.getElementsByTagName("span")[0].innerHTML = usernameLogin.value;
		document.getElementsByTagName("span")[1].innerHTML = "id("+loginJsonData.userid+")";

	}
	else{
		alert("Please check your credentials!");
	}

}

// ################### REGISTERs  AJAX ####################### 

function registerAjax(event){

	var  usernameLogin = $('usernameLogin').value;
	var  usernamePassword = $('usernamePassword').value;
	//url enconded string
	
	
	var urlstring = "usernameLogin=" + encodeURIComponent(usernameLogin)+"&usernamePassword=" + encodeURIComponent(usernamePassword);
	console.log(urlstring);

	var xhttp = new XMLHttpRequest();
	xhttp.open("POST","registerAjax.php",true);
	xhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	xhttp.addEventListener("load",registerAjaxCallBack,false);

	xhttp.send(urlstring);
}

function registerAjaxCallBack(event){
	var registerJsonData = JSON.parse(event.target.responseText);
	alert((registerJsonData.sucess ? "You have sucessfully registered!" : " Something went wrong!" +registerJsonData.message ));

}

// ################### LOGOUT  AJAX ####################### 

function logoutAjax(event){
	var xhttp = new XMLHttpRequest();
	xhttp.open("POST","logoutAjax.php",true);
	xhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	xhttp.addEventListener("load",logoutAjaxCallBack,false);
	xhttp.send();
}

function logoutAjaxCallBack(event){
	var logoutJsonData = JSON.parse(event.target.responseText);
	alert("user logged out!");
	if(logoutJsonData.sucess){
		alert(logoutJsonData.message);
	document.getElementById("eventDeleteButton").style.visibility="hidden";
	document.getElementById("addaEventButton").style.visibility="hidden";
	document.getElementById("editEventButton").style.visibility="hidden";
	document.getElementById("userspaceDiv").style.visibility="hidden";
	document.getElementById("openPopup").style.visibility="visible";

	}
	else{
		alert("something went wrgon!");
		exit(0);
	}
}







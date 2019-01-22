

//hold calendar months in variable to access in functions.
var calendarMonths = ["January","February","March","April","May","June","July","August","September","October","November","December"];
var eventsOut = false;
function startHTML(){
	$('closePopup').style.visibility="hidden";
}
startHTML();


	function $$(className){
		return document.getElementsByClassName(className)
	}

//FILLING DIV WITH JAVASCRIPT
//https://stackoverflow.com/questions/16019621/fill-div-with-html-using-javascript-jquery
//I used this to create the table for the calendar dynamically. 
function createCalendarTable()
{
	if (!document.getElementsByTagName) return;
	tabBody=document.getElementsByTagName("tbody").item(0);
			//add the week day's name (MON,TUE...SUNDAY)
			let weekNames = ["SUN","MON","TUE","WED","THU","FRI","SAT"];

			
			row=document.createElement("tr");
			row.className ="weekNames";
			for (var i = 0; i < weekNames.length; i++){
				cell1 = document.createElement("td");
				cell1.className = "weekName";
				textCell1 = document.createTextNode(weekNames[i]);
				cell1.appendChild(textCell1);
				row.appendChild(cell1);
				tabBody.appendChild(row);

			}
			//add rows for 5 weeks and their respective days
			for (var i = 1; i<6; i++){
				row=document.createElement("tr");
				row.className = "week"+i;
				cell1 = document.createElement("td");
				cell1.className = "weekDay";
				cell2 = document.createElement("td");
				cell2.className = "weekDay";
				cell3 = document.createElement("td");
				cell3.className = "weekDay";
				cell4 = document.createElement("td");
				cell4.className = "weekDay";
				cell5 = document.createElement("td");
				cell5.className = "weekDay";
				cell6 = document.createElement("td");
				cell6.className = "weekDay";
				cell7 = document.createElement("td");
				cell7.className = "weekDay";
				row.appendChild(cell1);
				row.appendChild(cell2);
				row.appendChild(cell3);
				row.appendChild(cell4);
				row.appendChild(cell5);
				row.appendChild(cell6);
				row.appendChild(cell7);
				tabBody.appendChild(row);
			}
		}
		createCalendarTable();

		$('registerButton').addEventListener("click", function(event){
			registerAjax();
		},true);
		$('loginButton').addEventListener("click", function(event){

			loginAjax();
			console.log(userLoggedIn);
			
			updateCalendar();
			getCalendarEvents();
			
		},true);
		$('userLogoutSubmit').addEventListener("click",function(event){
			logoutAjax(event);
			updateCalendar();
			getCalendarEvents();
		},true)

		function $(id){
			return document.getElementById(id)
		}


// For our purposes, we can keep the current month in a variable in the global scope
var currentMonth = new Month(2018, 7); // October 2017
function updateMonth(){
	$('monthName').innerHTML = calendarMonths[currentMonth.month]+ ", "+currentMonth.year;
}
// Change the month when the "next" button is pressed
document.getElementById("next_month_btn").addEventListener("click", function(event){
	currentMonth = currentMonth.nextMonth(); // Previous month would be currentMonth.prevMonth()
	updateMonth();
	updateCalendar();
	if (userLoggedIn == true){
	getCalendarEvents();
	} 
	// Whenever the month is updated, we'll need to re-render the calendar in HTML

	// alert("The new month is "+currentMonth.month+" "+currentMonth.year);
}, false);
document.getElementById("prev_month_btn").addEventListener("click", function(event){
	currentMonth = currentMonth.prevMonth(); // Previous month would be currentMonth.prevMonth()
	updateMonth();
	updateCalendar();
	if (userLoggedIn == true){
	getCalendarEvents();
	}  // Whenever the month is updated, we'll need to re-render the calendar in HTML
	  // Whenever the month is updated, we'll need to re-render the calendar in HTML

	// alert("The new month is "+currentMonth.month+" "+currentMonth.year);
}, false);


// This updateCalendar() function only alerts the dates in the currently specified month.  You need to write
// it to modify the DOM (optionally using jQuery) to display the days and weeks in the current month.
function updateCalendar(){
	var weeks = currentMonth.getWeeks();
	//keeps track of days for each week
	var weekdaysTable = 7;
	for(var w in weeks){
		var data=0;
		var days = weeks[w].getDates();
		// days contains normal JavaScript Date objects.
		
		// alert("Week starting on "+days[0]);
		
		for(var d in days){
			// You can see console.log() output in your JavaScript debugging tool, like Firebug,
			// WebWit Inspector, or Dragonfly.
			// console.log(days[d].toISOString());
			document.getElementsByTagName("td")[weekdaysTable].innerHTML = days[data].getDate();
			// console.log(document.getElementsByTagName("td")[weekdaysTable]);
			weekdaysTable += 1;
			data += 1;
		}
	}
}


function getCalendarEvents(){
	//get current date (myear,month,day) and index for calendar table
	var getMonth, getYear,getDay, calendarIndexFirst, calendarIndexLast;
	getMonth = currentMonth.month;
	getYear = currentMonth.year;

	console.log(getMonth);
	console.log(getYear);
	calendarIndexFirst = 7; // first index in the table

	var i,j;
	i = 7;
	j = 15;
	while ( i < 14 ){
		if(document.getElementsByTagName('td')[i].innerHTML == "1"){
			calendarIndexFirst = i;
			break;
		}
		else{
			i++;
		}
	}
	//mid-end part of the calendar , in case of the first day still in the previous month table
	calendarIndexLast = 42;
	while ( j < 42 ){
		if(document.getElementsByTagName('td')[j].innerHTML == "1"){
			calendarIndexLast = j;
			break;
		}
		else{
			j++;
		}
	}
	getDay = 0;
	console.log(calendarIndexFirst);
	console.log(calendarIndexLast);
	function xhttpGetEvents(i){
		var xhttp = new XMLHttpRequest();
		var urlstring = "getMonth="+getMonth+"&getYear="+getYear+"&getDay="+getDay;
		// console.log(urlstring);
		xhttp.open("POST","getEventAjax.php",true); 
		xhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		xhttp.addEventListener("load",function(event){
			var eventJsonData = JSON.parse(event.target.responseText);
			console.log(eventJsonData);
			if(eventJsonData.sucess){
				//creates a button for each event with their unique id, so its easier to keep track of it.
				document.getElementsByTagName('td')[i].innerHTML += "<br><button class='getEvent' id='"+eventJsonData.eventId+"'"
				+" onClick = trackEventId(this.id)> <br> "+ eventJsonData.eventTitle + "<br>" 
				+ eventJsonData.eventTime + ":"+eventJsonData.eventMinute + "</button>" ;
			}
			else if(!eventJsonData.sucess){
				console.log(eventJsonData.message);
			}

		},false);
		xhttp.send(urlstring);
		document.getElementsByTagName("td")[i].innerHTML= getDay + 1 ;
		}

		for (index =calendarIndexFirst; index < calendarIndexLast ; index ++ ){
			// console.log(getDay);
			xhttpGetEvents(index);		

			getDay++;
		}
		eventsOut = true;
	}



function addEventAjax(){
	var eventTitle = $('eventTitle').value;
	var eventYear = $('eventYear').value;
	var eventMonth = $('eventMonth').value -1;
	var eventDay = $('eventDay').value -1;
	var eventTime = $('eventTimeHour').value;
	var eventTimeMinute= $('eventTimeMinute').value;
	;


	var urlstring = "eventTitle=" + encodeURIComponent(eventTitle)
	+"&eventYear=" + encodeURIComponent(eventYear)
	+"&eventMonth=" + encodeURIComponent(eventMonth)
	+"&eventDay=" + encodeURIComponent(eventDay)
	+"&eventTime=" + encodeURIComponent(eventTime)
	+"&eventTimeMinute=" + encodeURIComponent(eventTimeMinute) ;
	// console.log(urlstring);

	var xhttp = new XMLHttpRequest();
	xhttp.open("POST","addEventAjax.php",true);
	//required for post requests
	xhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xhttp.addEventListener("load", function(event){
		var jsonData = JSON.parse(event.target.responseText);
		if(jsonData.sucess){
			alert("event added!")
				$('eventTitle').value = "";
				$('eventYear').value= "";
				$('eventMonth').value ="";
				$('eventDay').value ="";
				$('eventTimeHour').value = "";
				$('eventTimeMinute').value = "";
		}
		else{
			alert("Something went wrong!")
		}

	} ,false);


	xhttp.send(urlstring);
}
	$('addNewEvent').addEventListener("click", function(event){
			addEventAjax();
			updateCalendar();
			getCalendarEvents();
		},true);

//This function keeps track of event ID when clicked ( so edit and delete functions can use it)
         function trackEventId(id){
    		trackEvent = id;
			console.log(trackEvent);
    	}
 
  



	window.addEventListener("load", updateCalendar());
	window.addEventListener("load", updateMonth());



 
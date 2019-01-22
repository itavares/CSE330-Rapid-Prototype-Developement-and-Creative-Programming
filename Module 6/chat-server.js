// Require the packages we will use:
var http = require("http"),
	socketio = require("socket.io"),
	fs = require("fs");

// Listen for HTTP connections.  This is essentially a miniature static file server that only serves our one file, client.html:
var app = http.createServer(function(req, resp){
	// This callback runs when a new connection is made to our HTTP server.
	
	fs.readFile("client.html", function(err, data){
		// This callback runs when the client.html file has been read from the filesystem.
		
		if(err) return resp.writeHead(500);
		resp.writeHead(200);
		resp.end(data);
	});
});
app.listen(3456);

// generates a random number for guests ( be able to distinguish who is who)

function getRandNum(){
	rand = Math.random() *100 +1
	return Math.round(rand)
}

// This callback runs when a new Socket.IO connection is established.
var io = socketio.listen(app);

var guestId = "Wonderer_";
//global variables to hold "lists of users, rooms and passwords when required"
var ChannelUsersArray ={"admin_server":1},
	ChannelsListArray = {"ChannelLoby":["admin_server"]},  //this initializes default admin-server user in the Lobby room 
	getChannelPass = {"ChannelLoby":""}; // no password required to join lobby 

var keepTrackUserId,
	keepTrackLocation;

var generateId;
io.sockets.on("connection", function(socket){
	
	//create guest user if no user is provided 

 // ################### Creating New User (connection) #########
	socket.on('getNewConnection', function(event){
		console.log(event);
		if(event == guestId){
			rrand = getRandNum();
			event = event+rrand.toString();
		}

		//bind new guest to socket.id (its own id in the server)
		ChannelUsersArray[event] = socket.id;

		//populate specific chat room with user
		ChannelsListArray["ChannelLoby"].push(event);
		//define user , location
		socket.userNameId = event;
		socket.userLocation = "ChannelLoby";

		//set user to the lobby room 
			socket.join("ChannelLoby");

			//debug
			console.log("guest created")

		//create a array of rooms for future (when more rooms are created)
		chatRoomArray = Object.keys(ChannelsListArray);

		//emit back to HMTL to be updated (hmtl id = channelsArray)
		socket.emit("channelsArray", chatRoomArray);


		//create array of users to keep track of new users and populate future rooms

		usersArray = ChannelsListArray["ChannelLoby"];

		io.to("ChannelLoby").emit("usersArray",usersArray)

		// server greets new user and intruct them
		io.to("ChannelLoby").emit("sendTextClient",{
						text: "Greetins " + event + " ! Please, be polite to other users and avoid faul language! Have fun :) ",
						username : "The Oversee"
		});

	});



 // ################### Creating New Username ##############

 	socket.on('getNewUsername', function(event){
		//check for existent user in all user to see if name is already taken
		//event["newName"] is what is being emited from HTML
		if(usersArray.indexOf(event["newUsername"])!=-1){
			//tell user name is not usable
			socket.emit("nameValidationn", {message :"Name Taken, please select a different name"});
		}

		//update guest username to chosen name

		var outDateName = socket.userNameId,
			updateName = event["newUsername"];
		//update array containing the usernames at the user index
		usersArray[usersArray.indexOf(outDateName)]=updateName;

		//update id
		var oldCred = ChannelUsersArray[outDateName];
		ChannelUsersArray[updateName] = oldCred;
		//delete guest name (generated) from users
		delete ChannelUsersArray[outDateName];

		//sets new socket name to what user chose 
		socket.userNameId = event["newUsername"];
		socket.emit("nameValidationn", {message :"Name Updated with Sucess!"});
		//tell current room user is at  the name changed
		io.to(socket.userLocation).emit("chatAdjust", {channelUpdate:" The user : "+ outDateName + "has changed its name to : " + updateName+"."}) //alerts chat who cchanged name
		io.to(socket.userLocation).emit("usersArray",usersArray) //update the array of usernames



	 });
	

 // ################### User Messages ########################


 	socket.on("sendtextServer",function(event){

 		textInput = event["text"];
 		//debugging
 		console.log('message' + textInput +"==>" +socket.userLocation);


 		//filter text inputs (creative portion)
		 textInput = forbidenWords(textInput);
		 console.log("Test censored input"+textInput);
 		io.to(socket.userLocation).emit("sendTextClient",
 		{
 			text: textInput,
 			username: socket.userNameId 
 		})

 	});

 // ################### Creating New Channel  ########################

 	socket.on("newChannel", function(channelEvent){

 		channelDesc = channelEvent["channelName"];


 		//check channel availability
 		/* Change tot check if is available first. */
 		if(channelDesc in ChannelsListArray){
 				socket.emit("sendTextClient",
 				{
 					text : "Channel being used! Please , select a different name (ignore for lobby)",
 					username : "admin_server"
 				})
 		}
 		else{
			//updates current room when there is change in users number (a user joins a room or leaves)
			var channelIndex = ChannelsListArray[socket.userLocation].indexOf(socket.userNameId,1);
			keepTrackUserId=socket.userNameId;
			keepTrackLocation=socket.userLocation;
			if(channelIndex>-1){ChannelsListArray[socket.userLocation].splice(channelIndex,1)};
			 socket.leave(socket.userLocation);
			 keepTrackLocation=socket.userLocation;
 			io.to(socket.userLocation).emit('sendTextClient',
 			{
 				text : socket.userNameId + " is no longer with us ...",
 				username : "The Oversee"
 			});;

			// channelUsers = ChannelsListArray[socket.userLocation];
 			io.to(socket.userLocation).emit("usersArray",ChannelsListArray[socket.userLocation]); 

 			//new channel and user join functionallity

 			//creating an array withing the give roomname , to add users to it
 			ChannelsListArray[channelDesc] = []; 

 			//we first add the owner of the channel (first to be pushed)
			 ChannelsListArray[channelDesc].push(socket.userNameId); //shows at owner spot
			 ChannelsListArray[channelDesc].push(socket.userNameId); //shows in usersarrays
			 //adds password to give created channel (index should match)
			 chanelPass = channelEvent["channelPass"];		
			 getChannelPass[channelDesc] = chanelPass; 
			 console.log(chanelPass);

 			socket.join(channelDesc);
 			//set new location of owner to its created room
 			socket.userLocation = channelDesc;


 			io.to(socket.userLocation).emit("sendTextClient",
 			{
 				text : socket.userNameId + " joined the  "+ socket.userLocation + " cult !!",
 				username : "admin_server"
 			});


 			channelsArray = Object.keys(ChannelsListArray);
 			//update the list of channels
 			io.sockets.emit("channelsArray", channelsArray);
 			io.to(socket.userLocation).emit("usersArray",ChannelsListArray[socket.userLocation]);


 			//user leaves channel, removes room user from location

 		}


 	});



 // ################### Joining A Channel  ########################
	
 	socket.on("joinChannel", function(channelEvent){

 		//check if chanel exists and owner 
		 if(getChannelPass[channelEvent] == "" || ChannelsListArray[channelEvent][0] == socket.userNameId)
		 {
			keepTrackUserId=socket.userNameId;

			var currChannel = ChannelsListArray[socket.userLocation].indexOf(socket.userNameId,1);
			keepTrackLocation=socket.userLocation; 
			if(currChannel > -1){ChannelsListArray[socket.userLocation].splice(currChannel,1);}	 
			 keepTrackUserId=socket.userNameId;
				 socket.leave(socket.userLocation);
				 keepTrackLocation=socket.userLocation;
 				io.to(socket.userLocation).emit("sendTextClient",
 				{
 					text : socket.userNameId +" is no longer with us...",
 				username : "The Oversee"

 				});

 				//updates list of users
 				//channelUsers=ChannelsListArray[socket.userLocation];
 				io.to(socket.userLocation).emit("usersArray", ChannelsListArray[socket.userLocation]);

 				//allow users to join channel

				 socket.join(channelEvent);
				 keepTrackUserId=socket.userNameId;
				 socket.userLocation = channelEvent;
				 	ChannelsListArray[channelEvent].push(socket.userNameId);

 				//channelUsers = ChannelsListArray[socket.userLocation];
 				io.to(socket.userLocation).emit("usersArray",ChannelsListArray[socket.userLocation]);


 			
 		}else{
			 console.log("PASSSS check");
 			socket.emit("checkChannelPassword",channelEvent);

 		}
 	});
// ################### EXTRA CREDIT (CENSOR)  ########################
	 //https://stackoverflow.com/questions/1144783/how-to-replace-all-occurrences-of-a-string-in-javascript
	 //https://flaviocopes.com/how-to-replace-all-occurrences-string-javascript/
	 function forbidenWords(event){
		var forbidenWordsArray = ["bitch","fuck","nigga","satan"]; //sorry for the N-word, but you understand right? its forbidden
		//https://www.w3schools.com/jsref/jsref_obj_regexp.asp
		var pat = new RegExp("("+forbidenWordsArray.join("|")+")", "gi");
		event = event.replace(pat, "***You Have Used a Forbidden Word!!!");
		return event;

	 }

// ################### Kick A Loser(I mean, user) A Channel  ########################
socket.on("kickHimOut", function(event){
	userToBeKicked = event['kickperson'];
	channelOwner = socket.userNameId;
	getChannelFromClient = socket.userLocation;
	kickReason = event['kickReason'];

	console.log(channelOwner);
	console.log(ChannelsListArray[socket.userLocation][0]);
	//check first if who is kicking is the owner of the room(first index(0))
	if(ChannelsListArray[socket.userLocation][0] === channelOwner){
		getKickUserArray = ChannelUsersArray[userToBeKicked];
		io.to(getKickUserArray).emit("sendTextClient",
		{
			text : "excommunicate from " +  getChannelFromClient + " cult <===reason=== "+kickReason,
			username : "The Oversee"
		});
		io.to(getKickUserArray).emit('kickUserFromChannel');
	}else{
		socket.emit( 'sendTextClient' ,  
		{
			text: "Hmmmm you can't do that...",
			username: "The Oversee"
		});
		
	}
 });

 		//joining room with password

 	socket.on('channelPassword', function(channelEvent){

 		whichChannel = channelEvent["channel"];
 		currChanelPass  = channelEvent["secretCode"]; 		
 		//checks for password match
		 keepTrackUserId=socket.userNameId;
		 keepTrackLocation=socket.userLocation;
 		if(getChannelPass[whichChannel] == currChanelPass){
			var currChannel2 = ChannelsListArray[socket.userLocation].indexOf(socket.userNameId,1);
			keepTrackLocation=socket.userLocation;
			if(currChannel2>-1){ChannelsListArray[socket.userLocation].splice(currChannel2,1)}
			keepTrackLocation=socket.userLocation;
			console.log(keepTrackLocation);
			socket.leave(socket.userLocation);
			keepTrackLocation=socket.userLocation;
			console.log(keepTrackLocation);

			//same message from joining channel without password
				io.to(socket.userLocation).emit("sendTextClient",
				{
					text : socket.userNameId +" is no longer with us...",
				username : "The Oversee"

				});

			  currChannelUsers = ChannelsListArray[socket.userLocation];
			  console.log(currChannelUsers)
 			 io.to(socket.userLocation).emit("usersArray", currChannelUsers);

 			 socket.join(whichChannel);
 			 socket.userLocation=whichChannel;

			  ChannelsListArray[whichChannel].push(socket.userNameId);
			  
			  //Same as creating room, admin notifies that user left lobby and joinned a new channel
				io.to(socket.userLocation).emit("sendTextClient",
				{
					text : socket.userNameId + " joined the  "+ socket.userLocation + " cult !!",
					username : "admin_server"
				});

 			 io.to(socket.userLocation).emit("usersArray",ChannelsListArray[socket.userLocation]);

 			//if user leaves room


 		}else{
 			socket.emit("sendTextClient",
 			{
 				text : "Invalid password, check your local wizzard for help",
 				username : "The Oversee"

 			});
 		}

 	});

 	socket.on("diveIntoDms", function(event){

		//get the text for direct message
		dm = event['text'];
		//who the message is being sent to
		getdm = event['secretPerson'];
		//keep track of who is sending and where is being sent from (channel)
		keepTrackUserId=socket.userNameId;
		keepTrackLocation=socket.userLocation;
		console.log(keepTrackLocation);
		userDm = ChannelUsersArray[getdm];
		//get user sending direct message
		userPm = ChannelUsersArray[socket.userNameId]

		io.to(userDm).emit("sendDmToUser",
		{
			text : dm,
			username : socket.userNameId,
			sucess: true
		});
		keepTrackUserId=socket.userNameId;
		console.log(keepTrackUserId);
	  socket.emit("sendDmToUser",
	  {
		text : dm,
		username : socket.userNameId,
		sucess: false

	  });
	 });






});


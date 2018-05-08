//Contains all functionality for messages page.
var rootURL = window.location.origin;
var apiLocation = rootURL + "/res/api/messages.php";
var userApiLocation = rootURL + "/res/api/user.php";

//Stores an array of all messages...
//The message ID for each is used as the key.
var msgs = {};

//Stores the array of the selected message.
var selected = 0;

//Gets the list element from message ID...
function getElFromID(id){
	//Get unordered list of messages...
	var els = $('#messages-preview-list').children();
	var el = HTMLElement;
	
	//Iterate through messages...
	for (var i=0; i<els.length; i++){
		el = els[i];
		
		//If ID stored matches the ID given...
		if (el.getAttribute("data-msg-id") == id){			
			//Return element object.
			return el;
		}
	}
	
	return 0;
}

//Gets an array of message data from ID
function getMsgData(id){
	//Try and return message...
	try {
		return msgs[id];
	}
	
	catch (Exception) {
		//If error, return null.
		return null;
	}
}

//Function taking event parameter to decide
//what is going to happen.
function previewClick(event){
	//First, remove selected styling from
	//previously selected...
	var prev = getElFromID(selected);
	
	//If prev is set...
	if (prev !== 0) { $(prev).removeClass("messages-preview-selected"); }
	
	var target = event.target;
	
	var type = target.tagName;
	
	//If type is not a list, iterate until
	//a list parent is found within a reasonable
	//amount of time.
	for (var i=0; i<3; i++){		
		if (type !== "LI"){
			target = target.parentNode;
			type = target.tagName;
		}
		
		else {
			break;
		}
	}
	
	//Store ID of selected message.
	selected = target.getAttribute("data-msg-id");
	
	//Use jQuery selector to use jQuery functions...
	$(target).addClass("messages-preview-selected");
	
	//Display message in content pane...
	var contentPane = $('#messages-content-pane');
	
	//Get message data...
	var msgData = getMsgData(selected);
	
	//Add header to message...
	var msgHeader = document.createElement("h1");
	msgHeader.innerHTML = msgData.Subject;
	
	//Add sender name to message...
	var msgSender = document.createElement("h2");
	//HTML will be added with an AJAX request later...
	
	//Create horizontal line to seperate content from header.
	var msgSeperator = document.createElement("hr");
	
	var msgContent = document.createElement("div");
	msgContent.id = "messages-content";
	msgContent.innerHTML = msgData.Content;
	
	//Send AJAX to get sender info...
	$.post(userApiLocation, {action: "get_basic_user",
							uid: msgData.SenderID,
							type: msgData.SenderType})
							//These params are the ones used
							//on the back-end...
		
		.done(function(data){
			//Parse JSON...
			data = JSON.parse(data);
			
			msgSender.innerHTML = data.FirstName + " " +
								data.LastName + " (" + data.Username + ")";
			
			//Remove any current data from content pane...
			$(contentPane).html("");
			
			//Append all data to comtent pane.
			$(contentPane).append(msgHeader);
			$(contentPane).append(msgSender);
			$(contentPane).append(msgSeperator);
			$(contentPane).append(msgContent);
		});
}

//Function that runs when the reply button is clicked.
function replyClicked(event){
	//Get data about selected message.
	var msgData = getMsgData(selected);
	
	//Get name of sender.
	//This is a hacky way to do it but it works without
	//sending another AJAX request to the server, removing
	//some of the workload.
	var senderName = $('#messages-content-pane')
					.find('h2:first-of-type')[0].innerHTML;
	//Sender name is stored in <h2> tags in the content pane.
	
	//Show the messages reply box.
	$('#messages-reply').css("visibility", "visible");
	
	//Add content to fields..
	$('#messages-reply-to').val(senderName);
	
	$('#messages-reply-subject').val("Re: " + msgData.Subject);
}

function replySendClicked(event){
	//Hide reply box...
	$('#messages-reply').css("visibility", "hidden");
	
	//Parse text, eg; add line breaks etc.
	var content = $('#messages-reply-content').val();
	
	//Change standard line breaks to HTML line breaks.
	var parsedContent = content.replace("\n", "<br />");
	
	//Create array of all data needed to send a reply...
	var replyData = {
		action: "send_reply",
		subject: $('#messages-reply-subject').val(),
		content: parsedContent,
		replyTo: selected
	};
	
	$.post(apiLocation, replyData);
	//Assumes reply is done, if not it can be sent again.
}

//When window is ready...
$(window).ready(function(){
	//Set events...
	$('#messages-reply-btn').click(function(e){ replyClicked(e); });
	$('#messages-reply-submit').click(function(e){
		replySendClicked(e); 
		return false;
	});
	
	//Send POST request to server for message details.	
	$.post(apiLocation, {action: "get_messages",
						uid: uid,
						type: userType})
						//Variables uid and userType are
						//defined on the main messages page.
		.done(function(data) {
			//Because the data is given in JSON, it can be
			//read by JS straight away.
			data = JSON.parse(data);
			var cMsg = null;
			
			//Define variables for iteration before loop
			//starts so they are not defined multiple times.
			var previewList = document.getElementById("messages-preview-list");
			var listItem = null;
			var listItemHeader = null;
			var listItemContent = null;
			var msgPreview = null;
			
			for (var i=0; i<data.length; i++){
				//Iterate through array.
				cMsg = data[i];
				
				//If message is a reply, ensure that subject
				//starts with Re:
				if (cMsg.ReplyTo !== null &&
				cMsg.Subject.substr(0, 3) !== "Re:"){
					
					cMsg.Subject = "Re: " + cMsg.Subject;
				}
				
				//If message is longer than 30 chars, cut it...
				msgPreview = cMsg.Content;
				if (msgPreview.length > 30){
					msgPreview = msgPreview.substr(0, 27) + "...";
					//27 chars plus ... to make 30 chars
				}
				
				//Add message preview to preview pane...
				//Create elements to go in list.
				listItem = document.createElement("li");
				listItemHeader = document.createElement("h6");
				listItemContent = document.createElement("p");
				
				//Set data tag to identify message.
				listItem.setAttribute("data-msg-id", cMsg.ID);
				$(listItem).click(function(e){ previewClick(e); });
				
				listItemHeader.innerHTML = cMsg.Subject;
				listItemContent.innerHTML = msgPreview;
				
				//Append list item to list.
				listItem.append(listItemHeader);
				listItem.append(listItemContent);
				previewList.appendChild(listItem);
				
				//Add current message to array for storage.
				//This is added after everything is displayed
				//on the preview pane.
				msgs[cMsg.ID] = cMsg;
			}
		});
});
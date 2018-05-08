//Contains all functionality for messages page.
var rootURL = window.location.origin;
var apiLocation = rootURL + "/res/api/homework.php";
var userApiLocation = rootURL + "/res/api/user.php";

//Stores an array of all messages...
//The message ID for each is used as the key.
var globalTasks = {};

//Stores the array of the selected message.
var selected = 0;

//Gets the list element from message ID...
function getElFromID(id){
	//Get unordered list of messages...
	var els = $('#hwk-preview-list').children();
	var el = HTMLElement;
	
	//Iterate through messages...
	for (var i=0; i<els.length; i++){
		el = els[i];
		
		//If ID stored matches the ID given...
		if (el.getAttribute("data-task-id") == id){			
			//Return element object.
			return el;
		}
	}
	
	return 0;
}

//Function to determine if the task is overdue
function isTaskOverdue(dueDate){
	var dateToday = new Date();
	
	var isOverdue = false;
	
	if (dateToday.toDateString() > dueDate.toDateString()){
		//If due date is before today,
		//set overdue as true.
		isOverdue = true;
	}
	
	return isOverdue;
}

//Function to format  a task's due date.
function formatDueDate(date){
	var dateToday = new Date();
	
	//Make new date object from string given as param.
	var dueDate = new Date(date);
	
	var isOverdue = isTaskOverdue(dueDate);
	
	//Format date according to local standards.
	//eg; UK: dd/mm/yyyy, US: mm/dd/yyyy
	var displayDate = dueDate.toLocaleDateString();
	
	if (dateToday.toDateString() == dueDate.toDateString()){
		//If date is today, display as such.
		displayDate = "Today";
	}
	
	if (isOverdue){
		displayDate = "<span style=\"color: red;\">" +
					displayDate + "</span>";
	}
	
	return displayDate;
}

//Function that takes the event of the
//status dropdown box being changed and
//submits an AJAX request to change the
//status of the selected task's relevant
//entry in HomeworkEntries.
function statusChanged(event){
	//Get new value selected by user...
	var newVal = $(event.currentTarget).val();
	
	//Get relevant entry data...
	var entryId = globalTasks[selected].entry.ID;
	
	//Send POST request to API to change status...
	$.post(apiLocation, {action: "change_status",
						status: newVal,
						entryId: entryId})
		.done(function(e){
			//Change formatting if need be...
			//Variable storing the preview
			//element for this task...
			var previewHeader = getElFromID(selected)
				.getElementsByTagName("h6")[0];
			
			//If task is now complete...
			if (newVal == "C"){
				//If preview doesn't have complete class...
				if (!$(previewHeader).hasClass("hwk-complete")){
					$(previewHeader).addClass("hwk-complete");
				}
				
				//If preview has homework overdue class...
				if ($(previewHeader).hasClass("hwk-overdue")){
					$(previewHeader).removeClass("hwk-overdue");
				}
			}
			
			//If task is now overdue...
			else if (newVal == "O"){
				//If preview doesn't have overdue class...
				if (!$(previewHeader).hasClass("hwk-overdue")){
					$(previewHeader).addClass("hwk-overdue");
				}
				
				//If preview has complete class...
				if ($(previewHeader).hasClass("hwk-complete")){
					$(previewHeader).removeClass("hwk-complete");
				}
			}
			
			//If the task doesn't need a style...
			else {
				//If preview has complete class...
				if ($(previewHeader).hasClass("hwk-complete")){
					$(previewHeader).removeClass("hwk-complete");
				}
				
				//If preview has homework overdue class...
				if ($(previewHeader).hasClass("hwk-overdue")){
					$(previewHeader).removeClass("hwk-overdue");
				}
			}
		});
}

//Function taking event parameter to decide
//what is going to happen.
function previewClick(event){
	//First, remove selected styling from
	//previously selected...
	var prev = getElFromID(selected);
	
	//If prev is set...
	if (prev !== 0) { $(prev).removeClass("hwk-preview-selected"); }
	
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
	selected = target.getAttribute("data-task-id");
	
	//Set variables to store data about task...
	var taskData = globalTasks[selected].task;
	var entryData = globalTasks[selected].entry;
	
	//Use jQuery selector to use jQuery functions...
	$(target).addClass("hwk-preview-selected");
	
	//Unlike the messages page, all elements are
	//already present here so their values just
	//need to be changed.
	
	//Change header.
	$('#hwk-content-header').html(taskData.Title);
	
	//Change status in dropdown...
	$('#hwk-status').val(entryData.Status);
	
	//Change content...
	$('hwk-content').html(taskData.Content);
	
	//Send AJAX to get teacher's info...
	$.post(userApiLocation, {action: "get_basic_user",
							uid: taskData.TeacherID,
							type: "T"})
							//These params are the ones used
							//on the back-end...
		
		.done(function(data){
			//Parse JSON...
			data = JSON.parse(data);
			
			var dueDate = formatDueDate(taskData.DueDate);
			
			//Change teacher name. Also include due date.
			$('#hwk-content-teacher').html(data.FirstName + " " +
								data.LastName + " (" + data.Username + ")" +
								", Due: " + dueDate);
		});
}

//When window is ready...
$(window).ready(function(){
	//Set events...
	$('#hwk-status').change(function(e){
		statusChanged(e);
	});
	
	//Send POST request to server for task and entry details.	
	$.post(apiLocation, {action: "get_tasks",
						studentId: "SESSION"})
						//Variables uid and userType are
						//defined on the main messages page.
		.done(function(data) {
			//Because the data is given in JSON, it can be
			//read by JS straight away.
			data = JSON.parse(data);
			var cTask = null;
			
			var tasks = data.tasks;
			var entries = data.entries;
			
			//Define variables for iteration before loop
			//starts so they are not defined multiple times.
			var previewList = document.getElementById("hwk-preview-list");
			var listItem = null;
			var listItemHeader = null;
			var listItemContent = null;
			var taskPreview = null;
			
			for (var i=0; i<tasks.length; i++){
				//Iterate through array.
				//Get tasks section of array returned from
				//back-end.
				cTask = tasks[i];
				cEntry = entries[i];
				
				//If message is longer than 30 chars, cut it...
				taskPreview = cTask.Content;
				if (taskPreview.length > 30){
					taskPreview = taskPreview.substr(0, 27) + "...";
					//27 chars plus ... to make 30 chars
				}
				
				//Add message preview to preview pane...
				//Create elements to go in list.
				listItem = document.createElement("li");
				listItemHeader = document.createElement("h6");
				listItemContent = document.createElement("p");
				
				//Set data tag to identify message.
				listItem.setAttribute("data-task-id", cTask.ID);
				$(listItem).click(function(e){ previewClick(e); });
				
				listItemHeader.innerHTML = cTask.Title;
				
				//Change colour to red if task is overdue.
				//or to green if complete.
				var dueDateObj = new Date(cTask.DueDate);
				
				if (isTaskOverdue(dueDateObj)){
					$(listItemHeader).addClass("hwk-overdue");
				}
				
				//If status is 'Complete' or 'Handed In'...
				if (cEntry.Status == "C" ||
					cEntry.Status == "H"){
					
					$(listItemHeader).addClass("hwk-complete");
				}
				
				listItemContent.innerHTML = taskPreview;
				
				//Append list item to list.
				listItem.append(listItemHeader);
				listItem.append(listItemContent);
				previewList.appendChild(listItem);
				
				//Add current task to array for storage.
				//This is added after everything is displayed
				//on the preview pane.
				//This array stores both the entry and the task
				//data.
				globalTasks[cTask.ID] = {
					task: cTask,
					entry: cEntry
				};
				
			}
		});
});
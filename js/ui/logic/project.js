var keepFormData ="";
function newProject() {		

	var a = 
	'<form class="form-horizontal" >\
		<div class="form-group">\
			<label for="inputProjectName" class="col-sm-3 control-label">Projektnamn</label>\
			<div class="col-sm-9">\
				<input type="text" class="form-control" id="inputProjectName" placeholder="Projektnamn" required="">\
			</div>\
		</div>\
		<div class="form-group">\
			<label for="ownerInp" class="col-sm-3 control-label">Ägare</label>\
			<div class="col-sm-9">\
				<input type="text" class="form-control" id="ownerInp" placeholder="Ägare" required="">\
			</div>\
		</div>\
		<div class="form-group">\
			<label for="pickyDate" class="col-sm-3 control-label">Startdatum</label>\
			<div class="col-sm-9">\
				<input type="text"  onchange="formChange(this)"  class="form-control" id="pickyDate" placeholder="Startdatum" required="">\
			</div>\
		</div>\
		<div class="form-group">\
			<div class="col-sm-offset-3 col-sm-9">\
			</div>\
		</div>\
		<div class="form-group last">\
			<div class="col-sm-offset-3 col-sm-9">\
				<input type="button" onclick="createNewProject(true)" value="Skapa" class="btn btn-success btn-sm">\
				<button type="button" onclick="createNewProject(false)" class="btn btn-default btn-sm">Avbryt</button>\
				<button type="reset" class="btn btn-default btn-sm">Rensa fält</button>\
			</div>\
		</div>\
	</form>';

	//keepFormData = $('#mainBody').html();
	//console.log(keepFormData);
	//$('#projectAdm').html('');
	//$('#mainBody').html(a);
	//$('#mainBody').load('/framework/views/partial/newproject.php');

//	$('#pickyDate').datepicker({				
//		language: "sv",
//		todayBtn: "linked",				
//		clearBtn: true,
//		todayHighlight: true,
//      calendarWeeks: true
//    });
}

function removeProject(pId){
       
    var projData = findProject(pId);  

    swal({
    title: 'Vill du ta bort detta projekt?',
    text: projData.projectName+ ', id: ' + projData.projectId,    
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    cancelButtonText: 'Nej!',
    confirmButtonText: 'Ja, ta bort!'
    }).then(function () {
      swal(
        'Borttaget!',
        'Projektet har raderats.',
        'success'
      )
  });
}

function findProject(pId){
	var index;
	for (var x = 0; x < projectData.length; x++){
      if (pId == projectData[x].projectId)
        return projectData[x];
    }
    return null;
}

function createNewProject(isCreateProject){

	if(isCreateProject == true){		
		var projectDetails = 
		{
			name:$('#inputProjectName').val(), 
			projectOwner:$('#ownerInp').val(), 
			startDate:$('#pickyDate').val(),
			isActive:$('#selectStatus').val(),
			members:$('#selectUsers').val()
		};	
		console.log(projectDetails);
		
		if (projectDetails.name !="" && projectDetails.projectOwner !="" && projectDetails.startDate !=""){
			var projectDetailsStringify = JSON.stringify(projectDetails);
			//var userDetails = 'project=abc';
			$.ajax({
	            type: 'POST',
	            url: '/framework/core/project/new/index.php',        
	            data: 'project='+projectDetailsStringify,      
	            dataType: 'JSON',
	            cache: false,        
	            success: function(response) {          
	                if(isDebug)console.log(response);

	                if (response.CreatedProject === true){	                	
	                	storeProject(projectDetails, response);
	                }
	                else
	                	swal('Fel!',response.CreatedProject);                       
	            },
	            error: function(error) {                    
	              if(isDebug)console.log(error);
	            },                  
	        });
		}	
		else
			swal('Du måste skriva in projektnamn, ägare och startdatum!');
	}
	else{
		location.href = "/project/"
	}
}

function storeProject(projectDetails, response){
	var project = {projectName:projectDetails.name,projectId:response.ProjectDetails.ProjectId,active:projectDetails.isActive,startDate:projectDetails.startDate,endDate:""};
	var currProjects = JSON.parse(localStorage.getItem('projectData'));	
	if (currProjects == null)currProjects = [];
	currProjects.push(project);	
	if(isDebug)console.log(currProjects);	
	localStorage.setItem('projectData',JSON.stringify(currProjects));
	//location.href = "/project/"
}

function formChange(item){
 

}

function getProjectUpdates(){
	if (isOnline){
		getMembers();
		 $.ajax({
            type: 'GET',
            url: '/framework/core/project/list',                    
            cache: false,   
            dataType: 'JSON',     
            success: function(response) {
     			var uProject = response.Projects;
     			//console.log(uProject)

     			if (response.Projects !== null){
     				localStorage.removeItem('projectData');
     				$.each(uProject, function(index, value) {     				
     					projectTryPush(value);
					});
     			}	 			
            },
            error: function(error) {                    
              if(isDebug)console.log("Nepp");
            },                  
        });
	}
}

function projectTryPush(pushData){
	var projectData2;
	projectData2 = JSON.parse(localStorage.getItem('projectData'));
	
	if (projectData2 === null)
		projectData2 = [];

	idExists = false;

	for (var x = 0; x < projectData2.length; x++){
		//if (projectData2[x].projectId == pushData.ProjectId)
		//	idExists = true;
	}
	
	if (!idExists){
		projectData2.push(
			{
				projectName:pushData.Projectname,
				projectId:pushData.ProjectId,
				active:pushData.Status,
				startDate:pushData.StartDate,
				endDate:pushData.EndDate,
				username:pushData.Username
			});


		console.log(projectData2);	
		localStorage.setItem('projectData',JSON.stringify(projectData2));	
	}
}


function getMembers(){	

	if (isOnline){
		 $.ajax({
            type: 'GET',
            url: '/framework/core/user/list',                    
            cache: false,   
            dataType: 'JSON',     
            success: function(response) {
            	localStorage.removeItem('userGroup');	
     			 $.each(response.Users, function(index, value) {     				
					tryPushMember(value);		
				});
     					
            },
            error: function(error) {                    
              if(isDebug)console.log("Höhö");
            },                  
        });
	}
}


function tryPushMember(pushData){	
	var projectData2;
	projectData2 = JSON.parse(localStorage.getItem('userGroup'));	
	idExists = false;
	
	if (projectData2 === null)
		projectData2 = [];		

	for (var x = 0; x < projectData2.length; x++){
		//if (projectData2[x].UserId == pushData.UserId)
		//	idExists = true;
	}
	
	if (!idExists){
		projectData2.push(
		{
			Username:pushData.Username,
			UserId:pushData.UserId
		});
				
		localStorage.setItem('userGroup',JSON.stringify(projectData2));	
	}
}
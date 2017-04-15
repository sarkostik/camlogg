<?php 

  $root = $_SERVER['DOCUMENT_ROOT'];

  $jsArray = array(  
      '/js/ui/logic/project.js',
      '/js/datepicker/bootstrap-datepicker.js',
      '/js/datepicker/bootstrap-datepicker.sv.min.js'      
      );

  include_once $root.'/framework/views/master.php';

?>      
<style>
table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

td, th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
}

tr:nth-child(even) {
    background-color: #dddddd;
}
</style>

<script type="text/javascript">
  var index = 0;

  $(document).ready(function () {
    var nav = get_storage('navigate');
    //localStorage.removeItem('navigate');    
    

    for (var x = 0; x < projectData.length; x++){
      if (nav.navId == projectData[x].projectId)
        index = x;
    }

    $('#modalTitle').html('Redigera ' + projectData[index].projectName);
   
    $('#inputProjectName').val(projectData[index].projectName);   
    $('#pickyDate').val(projectData[index].startDate);
    $('#ownerInp').val(projectData[index].username);

    var isActive = "Inaktiv";
    var inActive = "Aktiv";
    var selValue1 = "0";
    var selValue2 = "1";

    if (projectData[index].active == "1"){
      selValue1 ="1";
      selValue2 = "0";
      isActive = "Aktiv";
      inActive = "Inaktiv";
    }
        
    $("#selectStatus")
    .append('<option value='+selValue1+'>'+isActive+'</option>')
    .selectpicker('refresh');

    $("#selectStatus")
    .append('<option value='+selValue2+'>'+inActive+'</option>')
    .selectpicker('refresh');

     $.ajax({
            type: 'GET',
            url: '/framework/core/project/list/user',                    
            cache: false,   
            dataType: 'JSON',     
            success: function(response) {
                      
              members = get_storage('userGroup');
              var activeUsers = [];          
              $.each(response.Users, function(index2, value){
              
              if (value.ProjectId == projectData[index].projectId)                          
                activeUsers.push(value.UserId);                          
              });


              $.each(members, function(index2, value) { 
                var isSelected ="";
                for (var x = 0; x < activeUsers.length; x++){             
                  
                  if (activeUsers[x] == value.UserId)
                    isSelected = "selected=true";  
                }

                $("#selectUsers")
                .append('<option '+isSelected+' value="'+value.UserId+'">'+value.Username+'</option>')
                .selectpicker('refresh');
                                
              });        
              
            },
            error: function(error) {                    
              if(isDebug)console.log("Höhö");
            },                  
        });


    abc = setTimeout(function() {
        var uProject = []  
        for (var x = 1; x <= 10; x++){
          uProject.push({projectId:x,projectName:'Användare '+x});
        }

     }, 250);  
        $('#pickyDate').datepicker({        
          language: "sv",
          todayBtn: "linked",       
          clearBtn: true,
          todayHighlight: true,
            calendarWeeks: true
      });    

         $('#pickyDate2').datepicker({        
          language: "sv",
          todayBtn: "linked",       
          clearBtn: true,
          todayHighlight: true,
            calendarWeeks: true
      });    
  });

  function nullWhiteSpace(str){
    return !/\S/.test(str);
  }   

  function formChange2(){

  }

  function updateProject(){

    console.log(projectData[index].projectId);
    var isCreateProject = true;

    if(isCreateProject == true){    
      var projectDetails = 
      {
        projectId:projectData[index].projectId,
        name:$('#inputProjectName').val(), 
        projectOwner:$('#ownerInp').val(), 
        startDate:$('#pickyDate').val(),
        endDate:$('#pickyDate2').val(),
        isActive:$('#selectStatus').val(),
        members:$('#selectUsers').val()
      };  
      console.log(projectDetails);
      
      if (projectDetails.name !="" && projectDetails.projectOwner !="" && projectDetails.startDate !=""){
        var projectDetailsStringify = JSON.stringify(projectDetails);
        //var userDetails = 'project=abc';
        $.ajax({
                type: 'POST',
                url: '/framework/core/project/edit/',        
                data: 'project='+projectDetailsStringify,      
                dataType: 'JSON',
                cache: false,        
                success: function(response) {          
                    if(isDebug)console.log(response);

                    if (response.UpdatedProject === true){                    
                      location.href = "/project/"
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







</script>

<div class="modal-dialog custom-class">
    <div class="modal-content">
        <div class="modal-header">            
           <div class="pull-left" id="modalTitle">
              Projekt testfall
           </div>           

           <div class="pull-right" id="projectAdm">                
           </div>
        </div>
        <div class="modal-body" id="mainBody2">
        <div id="mainBody"></div>
        <form class="form-horizontal" >
          <div class="form-group">
            <label for="inputProjectName" class="col-sm-3 control-label">Projektnamn</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" id="inputProjectName" placeholder="Projektnamn" required="">
            </div>
          </div>
          <div class="form-group">
            <label for="ownerInp" class="col-sm-3 control-label">Ägare</label>
            <div class="col-sm-9">                
              <input type="text" class="form-control" id="ownerInp" placeholder="Ägare" required="">
            </div>
          </div>
          <div class="form-group">
            <label for="pickyDate" class="col-sm-3 control-label">Startdatum</label>
            <div class="col-sm-9">
              <input type="text"  onchange="formChange(this)"  class="form-control" id="pickyDate" placeholder="Startdatum" required="">
            </div>
          </div>
          <div class="form-group">
            <label for="pickyDate2" class="col-sm-3 control-label">Slutdatum</label>
            <div class="col-sm-9">
              <input type="text"  onchange="formChange2(this)"  class="form-control" id="pickyDate2" placeholder="" required="">
            </div>
          </div>
          <div  class="form-group">            
            <label for="selectStatus" class="col-sm-3 control-label">Status</label>
            <div class="col-sm-9">
              <select class="selectpicker" id="selectStatus">
               
              </select>                
              </div>
          </div> 
           <div class="form-group">  
            <label for="selectUsers" class="control-label col-sm-3">Medlemmar</label>                    
            <div class="col-sm-9">                
              <select class="selectpicker" multiple data-live-search="true" data-actions-box="true" id="selectUsers">
              
              </select>                            
            </div>
          </div> 
          <div class="form-group">
            <div class="col-sm-offset-3 col-sm-9">
            </div>
          </div>
          <div class="form-group last">            
            <div class="col-sm-offset-3 col-sm-9">
              <input type="button" onclick="updateProject()" value="Ändra" class="btn btn-success btn-sm">
              <input type="button" onclick="location.href = /project/" value="Avbryt" class="btn btn-default btn-sm">                
              <input type="button" onclick="removeProject()" value="Radera" class="btn btn-danger btn-sm">                
            </div>
          </div>
        </form>
      </div>                
        <div class="modal-footer" id="landningPageFooter">         
          <?php echo $footerText; ?>   
        </div>
    </div>
</div>
</body>
</html>
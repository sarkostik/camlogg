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

  var selectedId = "";

   $(document).ready(function () {
       $('#pickyDate').datepicker({        
          language: "sv",
          todayBtn: "linked",       
          clearBtn: true,
          todayHighlight: true,
            calendarWeeks: true
      });    

      if(findUserCookie()){
          getProjectUpdates();          

          abc = setTimeout(function() {      

          projectData =  JSON.parse(localStorage.getItem('projectData'));
          if (projectData != null){
            var projectDetails = "";                  
            projectData.sort(compareName);          
           
            $.each(projectData, function(index, value) { 
              if (value.active == "1"){
                $("#selectProject")
                .append('<option value="'+value.projectId+'">'+value.projectName+'</option>')
                .selectpicker('refresh');
                if (selectedId == ""){
                  selectedId = value.projectId;
                  selectedProject(selectedId);
                }
              }              
            });      
          }
        }, 50);      
      }        
    });

   function switchProjectUsers(){
      var val = $('#selectProject').val()
      selectedProject(val);
      $('#selectMembers').empty();
   }

   function selectedProject(index){

      $.ajax({
        type: 'GET',
        url: '/framework/core/project/list/user',                    
        cache: false,   
        dataType: 'JSON',     
        success: function(response) {
                     
          members = get_storage('userGroup');
          console.log(response.Users);        

          var activeUsers = [];          
          $.each(response.Users, function(index2, value){
              
              if (value.ProjectId == index)                          
                activeUsers.push(value.UserId);            
          });

          $.each(members, function(index2, value){                        
            var isSelected = "";

            for (var x = 0; x < activeUsers.length; x++){              
              if (activeUsers[x] == value.UserId)
                isSelected = "selected=true";              
            }

            
            $("#selectMembers")
            .append('<option '+isSelected+' value="'+value.UserId+'">'+value.Username+'</option>')
            .selectpicker('refresh');  
          

          },50);                    
        },
        error: function(error) {                    
          if(isDebug)console.log("Höhö");
        },                  
      });
   }

  function createNewFlow(state){

    if(state == true){   
      var projectDetails = 
      {
      name:$('#inputProjectName').val(), 
      projectOwner:$('#ownerInp').val(), 
      startDate:$('#pickyDate').val(),
      isActive:$('#selectStatus').val(),
      members:$('#selectMembers').val(),
      project:$('#selectProject').val()
      };      
      
      if (projectDetails.name !="" && projectDetails.projectOwner !="" && projectDetails.startDate !=""){
        var projectDetailsStringify = JSON.stringify(projectDetails);
        console.log(projectDetailsStringify);
        $.ajax({
            type: 'POST',
            url: '/framework/core/project/flow/new/index.php',        
            data: 'project='+projectDetailsStringify,      
            dataType: 'JSON',
            cache: false,        
            success: function(response) {          
                console.log(response);       
            },
            error: function(error) {                    
              if(isDebug)console.log(error);
            },                  
        });
      } 
      else
        swal('Du måste skriva in flödesnamn, ägare och startdatum!');
    }
    else{
      location.href = "/project/workflow/"
  }
 }

</script>

<div class="modal-dialog custom-class">
    <div class="modal-content">
        <div class="modal-header">            
           <div class="pull-left" id="online_status">
              <strong>Arbetsflöden</strong>    
           </div>
           <div class="pull-right" id="projectAdm">
              
           </div>
           

           <div class="pull-right" id="thumbPreview">
                
           </div>

        </div>
        <div class="modal-body" id="mainBody">                


            <form class="form-horizontal" >
            <div class="form-group">
              <label for="inputProjectName" class="col-sm-3 control-label">Flödesnamn</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" id="inputProjectName" placeholder="Flödesnamn" required="">
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
              <div class="col-sm-3">
                <input type="text"  onchange="formChange(this)"  class="form-control" id="pickyDate" placeholder="Startdatum" required="">
              </div>
            </div>
            <div  class="form-group">            
              <label for="selectStatus" class="col-sm-3 control-label">Status</label>
              <div class="col-sm-9">
                <select class="selectpicker" id="selectStatus">
                  <option value="1">Aktiv</option>
                  <option value="0">Inaktiv</option>                  
                </select>                
                </div>
            </div> 
            <div  class="form-group">            
              <label for="selectProject" class="col-sm-3 control-label">Projekt</label>
              <div class="col-sm-9">
                <select class="selectpicker" data-live-search="true" onchange="switchProjectUsers()" id="selectProject">
                 
                </select>                
                </div>
            </div> 

            <div  class="form-group">            
              <label for="selectMembers" class="col-sm-3 control-label">Medlemmar</label>
              <div class="col-sm-9">
                <select class="selectpicker" multiple data-live-search="true" data-actions-box="true" id="selectMembers">
                 
                </select>                
                </div>
            </div> 


            <div class="form-group">
              <div class="col-sm-offset-3 col-sm-9">
              </div>
            </div>
            <div class="form-group last">            
              <div class="col-sm-offset-3 col-sm-9">
                <input type="button" onclick="createNewFlow(true)" value="Skapa" class="btn btn-success btn-sm">
                <button type="button" onclick="createNewFlow(false)" class="btn btn-default btn-sm">Avbryt</button>
                <button type="reset" class="btn btn-default btn-sm">Rensa fält</button>
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

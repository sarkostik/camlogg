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

  $(document).ready(function () { 

    uProject = get_storage('projectData');
    abc = setTimeout(function() {        

        $.each(uProject, function(index, value) { 
          $("#selectProject")
         .append('<option value="'+value.projectId+'">'+value.projectName+'</option>')
         .selectpicker('refresh');
        });        

     }, 250);  
        $('#pickyDate').datepicker({        
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

</script>

<div class="modal-dialog custom-class">
    <div class="modal-content">
        <div class="modal-header">            
           <div class="pull-left" id="online_status">
              Ny användare
           </div>
           

           <div class="pull-right" id="projectAdm">
                
           </div>

        </div>
        <div class="modal-body" id="mainBody2"> 
   
           <form class="form-horizontal" >
            <div class="form-group">
              <label for="inputProjectName" class="col-sm-3 control-label">Användarnamn</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" id="inputProjectName" placeholder="Användarnamn" required="">
              </div>
            </div>
            <div class="form-group">
              <label for="emailInp" class="col-sm-3 control-label">E-postadress</label>
              <div class="col-sm-9">                
                <input type="email" class="form-control" id="emailInp" placeholder="E-postaddress" required="">
              </div>
            </div>
            <div class="form-group">
              <label for="inPassword" class="col-sm-3 control-label">Lösenord</label>
              <div class="col-sm-9">                
                <input type="password" class="form-control" id="inPassword" placeholder="Lösenord" required="">
              </div>
            </div>
            <div class="form-group">
              <label for="pickyDate" class="col-sm-3 control-label">Startdatum</label>
              <div class="col-sm-9">
                <input type="text"  onchange="formChange(this)"  class="form-control" id="pickyDate" placeholder="Startdatum" required="">
              </div>
            </div>
            <div  class="form-group">            
              <label for="selectAccess" class="col-sm-3 control-label">Behörighet</label>
              <div class="col-sm-9">
                <select class="selectpicker" id="selectAccess">
                  <option value=3>Anställd</option>
                  <option value=2>Administratör</option>                  
                </select>           
              </div>
            </div>
            <div class="form-group">  
              <label for="selectProject" class="control-label col-sm-3">Projekt</label>                    
              <div class="col-sm-9">                
                <select class="selectpicker" multiple data-live-search="true" id="selectProject">
                
                </select>                            
              </div>
            </div> 
            <div class="form-group">
              <div class="col-sm-offset-3 col-sm-9">
              </div>
            </div>
            <div class="form-group last">            
              <div class="col-sm-offset-3 col-sm-9">
                <input type="button" onclick="createNewUser(true)" value="Skapa" class="btn btn-success btn-sm">
                <button type="button" onclick="createNewUser(false)" class="btn btn-default btn-sm">Avbryt</button>
                <button type="reset" class="btn btn-default btn-sm">Rensa fält</button>
              </div>
            </div>
          </form>
        <div id="mainBody"></div>
        </div>                
        <div class="modal-footer" id="landningPageFooter">         
           <?php echo $footerText; ?>vs
        </div>
    </div>
</div>
</body>
</html>
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
       $('#pickyDate').datepicker({        
            language: "sv",
            todayBtn: "linked",       
            clearBtn: true,
            todayHighlight: true,
              calendarWeeks: true
        });    

        $.ajax({
            type: 'GET',
            url: '/framework/core/project/list/user',                    
            cache: false,   
            dataType: 'JSON',     
            success: function(response) {
                      
              members = get_storage('userGroup');              
              $.each(members, function(index2, value) {                 
                $("#selectUsers")
                .append('<option value="'+value.UserId+'">'+value.Username+'</option>')
                .selectpicker('refresh');
                                
              });        
              
            },
            error: function(error) {                    
              if(isDebug)console.log("Höhö");
            },                  
        });



  });

</script>

<div class="modal-dialog custom-class">
    <div class="modal-content">
        <div class="modal-header">            
           <div class="pull-left" id="online_status">
              Nytt projekt
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
                <input type="button" onclick="createNewProject(true)" value="Skapa" class="btn btn-success btn-sm">
                <button type="button" onclick="createNewProject(false)" class="btn btn-default btn-sm">Avbryt</button>
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
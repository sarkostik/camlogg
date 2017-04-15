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
  function listProject(index, name){
    if(isDebug)console.log(index + ";" + name);

    var navValues = projectData[index];
    if(isDebug)console.log(navValues);    
    localStorage.setItem('navigate',JSON.stringify(navValues));  
    location.href = "/project/workflow/";
  }

 
   $(document).ready(function () {                

      if(findUserCookie()){
        
        getProjectUpdates();

        abc = setTimeout(function() {      
          projectData =  JSON.parse(localStorage.getItem('projectData'));

          if (projectData != null){
            var projectDetails = "";                  
            projectData.sort(compareName);          

            for (var x = 0; x < projectData.length; x++){
              var pId = projectData[x].projectId;
              projectDetails +=
              '<tr><td><a href="#" onclick=switchProject('+pId+') >'+projectData[x].projectName+'</a></td>';
          
              if (projectData[x].active == "1")
                projectDetails += '<td><a href="#"><label class="label label-success">&nbsp;Aktiv&nbsp;</label><br /></a></td>';
              else
                projectDetails += '<td><a href="#"><label class="label label-default">Inaktiv</label><br /></a></td>';
          
              projectDetails += 
              '<td>'+
                '<a href="#" onclick=navEditProject('+pId+')>Redigera</a> | '+
                '<a href="#" onclick="removeProject('+pId+')">Ta bort</a></td>';              

              projectDetails += '</tr>'
          } 

          var tableData =
          '<table>'+
              '<tr>'+              
                '<th>Projekt</th>'+
                '<th>Status</th>'+
                '<th>Administration</th>'+
              '</tr>'+
              projectDetails+
          '</table>';

          $('#mainBody').append(tableData);
        }
        
        $('#projectAdm').append('<a href="new/">Nytt projekt</a> | <a href="user/new">Ny användare</a>')
        if(isDebug)console.log(projectData);
        $('#online_status').append('<strong>Mina projekt</strong>');          
        },150);               
      }

      });

      function navEditProject(pId){
        console.log(pId);
        set_local('navigate',{navId:pId,navType:'project'});        
        location.href = "edit/";
      }

      function switchProject(pId){
        console.log(pId);
        set_local('navigate',{navId:pId,navType:'project'});        
        location.href = "view/";
      }

</script>

<div class="modal-dialog custom-class">
    <div class="modal-content">
        <div class="modal-header">            
           <div class="pull-left" id="online_status">
              
           </div>
           

           <div class="pull-right" id="projectAdm">
                
           </div>

        </div>
        <div class="modal-body" id="mainBody2"> 
   


        <div id="mainBody"></div>   
            

        </div>                
        <div class="modal-footer" id="landningPageFooter">         
            <?php echo $footerText; ?>  
        </div>
    </div>
</div>
</body>
</html>
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
 


</script>

<div class="modal-dialog custom-class">
    <div class="modal-content">
        <div class="modal-header">            
           <div class="pull-left" id="online_status">
              Användare
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
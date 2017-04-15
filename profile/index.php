<?php 

  $root = $_SERVER['DOCUMENT_ROOT'];
  include_once $root.'/framework/views/master.php';    

 
?>    

    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">            
               <div class="pull-left" id="online_status">
                  <strong>Min profil</strong>    
               </div>
               

               <div class="pull-right" id="thumbPreview">
                    
               </div>

            </div>
            <div class="modal-body">                
                
              
               
            </div>                
            <div class="modal-footer" id="landningPageFooter">                               
             <div class="pull-right"> 
               <?php echo $footerText; ?>    
            </div>
            <div class="pull-left">
              <a href="preferences/" class="btn btn-default btn-xs">Inställningar</a>
            </div>
                
            </div>
        </div>
    </div>
    </body>
</html>
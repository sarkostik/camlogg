<?php 

  $root = $_SERVER['DOCUMENT_ROOT'];
  include_once $root.'/framework/views/master.php';    
 
?>

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
            
          

	<img style="max-width: 100px" SRC="data:image/gif;base64,<?php echo base64_encode(file_get_contents("/home/olle/test.jpg"));?>">


		<?php 
		$upload_dir = '/home/olle/uploads/'; 
		
		if(is_dir($upload_dir)) 
		{ 
		   if(is_writable($upload_dir)) 
		   { 
		      echo "OK: directory $upload_dir exists and is writable by this script."; 
		   } 
		   else 
		   { 
		      echo "ERROR: directory $upload_dir exists but is not writable by this script."; 
		   } 
		} 
		else 
		{ 
		   echo "ERROR: This script does not recognize $upload_dir as a directory."; 
		} 
		?>


           
        </div>                
        <div class="modal-footer" id="landningPageFooter">                               
        
        <?php echo $footerText; ?>
            
        </div>
    </div>
</div>
</body>
</html>


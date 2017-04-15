<?php 

  include_once 'framework/includes/db_connect.php';
  include_once 'framework/includes/functions.php';
  $header = "Välkomstsida";

      $jsArray = array(  
      '/js/forms.js', 
      '/js/sha512.js',
      '/js/ui/user.js'
      );

      include_once 'framework/views/master.php';
?>      
<script type="text/javascript">   
  $(document).ready(function () {                
    if(!findUserCookie())          
      sweetLogin(function(){});            
  });

  

  $(document.body).bind("online", checkNetworkStatus);
  $(document.body).bind("offline", checkNetworkStatus);                
  
  //checkNetworkStatus();
</script>

<div class="modal-dialog custom-class">
  <div class="modal-content">
    <div class="modal-header">            
      <div class="pull-left" id="online_status">
        <b>Meny</b>
      </div>
      <div class="pull-right" id="thumbPreview">
        
      </div>
    </div>
    <div class="modal-body" id="mainBody">                
      <div class="row">
        <div class="col-xs-6 col-md-2">
          <b>Mina Projekt
          <a href="/project" class="thumbnail">
            <img src="img/presentation.svg" alt="ohh">                  
          </a>
        </div>
        <div class="col-xs-6 col-md-2">
          Arbetsflöden
          <a href="/project/workflow" class="thumbnail">
            <img src="img/flow.svg"  alt="baaa">                  
          </a>
        </div>
        <div class="col-xs-6 col-md-2">
          Min Profil
          <a href="/profile" class="thumbnail">
            <img src="img/profile.svg"  alt="baaa">                  
          </a>
        </div>
        <div class="col-xs-6 col-md-2">
          Meddelanden
          <a href="#" class="thumbnail">
            <img src="img/chat.svg"  alt="baaa">                  
          </a>
        </div>
        <div class="col-xs-6 col-md-2">
          Noteringar
          <a href="#" class="thumbnail">
            <img src="img/text-lines.svg"  alt="baaa">                  
          </a>
        </div>
        <div class="col-xs-6 col-md-2">
          Inställningar</b>
          <a href="/profile/preferences" class="thumbnail">
            <img src="img/settings.svg"  alt="baaa">                  
          </a>
        </div>
      </div>           
    </div>                
      <div class="modal-footer" id="landningPageFooter">                                       
        <?php echo $footerText; ?>               
      </div>
  </div>
</div>
</body>
</html>
<?php 

  $root = $_SERVER['DOCUMENT_ROOT'];



  include_once $root.'/framework/views/master.php';    

?>     

  <script type="text/javascript">

    function roundDecimal(decNr){
      if (decNr == null)
        decNr = 0;
      else{
        decNr = Math.round(decNr);
      }
      if (decNr == "NaN")
        decNr = 0;
      return decNr;
    }
    
      
    $(document).ready(function () { 
    
      $('#ex1').slider({
        formatter: function(value) {          
            var quality = 250 * value;
            localStorage.setItem('imgQuality', quality);

            return 'Current value: ' + value;
        }
      });


      $('#localStorage').append('<br><b>Local storage: '+localStorageQuota()+' is used </b>');
      var usr = get_storage('userDetails');
      $('#userAccount').append(usr.email);

    });

    function trueReload(){
      window.location.reload(true)
    }

    function fakeHash(){
      var userDetails = JSON.parse(localStorage.getItem('userDetails'));
  
      userDetails.pw="abcde";  
      localStorage.setItem('userDetails', JSON.stringify(userDetails));  
    }

    function removeProject(){
      localStorage.removeItem('projectData');
    }

  </script>

    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">            
               <div class="pull-left" id="online_status">
                  <strong>Inställningar</strong>    
               </div>
               

               <div class="pull-right" id="thumbPreview">
                    
               </div>

            </div>
            <div class="modal-body">                

            Bildkvalite för lagring lokalt<br />
            <input id="ex1" data-slider-id='ex1Slider' type="text" data-slider-min="1" data-slider-max="10" data-slider-step="1" data-slider-value="5"/>

              

                <div id="localStorage"></div>
                <div id="userAccount"></div>
            </div>                
            <div class="modal-footer" id="landningPageFooter">                               
             
            
              <div class="pull-left">
                
                  <button id="btnTmp1" class="btn btn-danger btn-xs pull-left" onclick="removeProject()">Ta bort projekt</button>
                  <button id="btnTmp" class="btn btn-danger btn-xs pull-left" onclick="storeLogin()">Ta bort användarcookie</button>
                  <button id="btnTmp2" class="btn btn-default btn-xs pull-left" onclick="showStorage()">Visa intern lagring</button>
                  <button id="btnTmp3" class="btn btn-default btn-xs pull-left" onclick="generateWorkflow()">Skapa dummyflöde</button>
                  <button id="btnTmp4" class="btn btn-default btn-xs pull-left" onclick="fakeHash()">Generera fejkhash</button>
                  <button id="btnTmp5" class="btn btn-default btn-xs pull-left" onclick="trueReload()">Uppdatera webbappen</button>
              </div>

              <div class="pull-right">
                 <?php echo $footerText; ?>
              </div>


                </h6>
            
                
            </div>
        </div>
    </div>     
    </body>
</html>
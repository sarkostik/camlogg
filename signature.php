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

<script src="/js/imagetools.js"></script>
<script src="/js/base.js"></script>
<script src="/js/ui/base.js"></script>
<script src="/js/ui/xhr.js"></script>
<script src="/js/ui/image_control.js"></script>
<script src="/js/simple-undo.js"></script>
<script src="/js/utils.js"></script>
<script src="/js/board.js"></script>
<script src="/js/controls/control.js"></script>
<script src="/js/controls/color.js"></script>
<script src="/js/controls/drawingmode.js"></script>
<script src="/js/controls/navigation.js"></script>
<script src="/js/controls/size.js"></script>
<script src="/js/controls/download.js"></script>
<script src="/js/draw/yepnope.js"></script>
<script type="text/javascript">   

  var myBoard = "";
      
  function findImage(){
     var img = myBoard.getImg();
      
     imgInput = (myBoard.blankCanvas == img) ? '' : img;

     //var url= "http://camlogg.xn--fors-8qa.se/uploads/alfahanne.png";
     var url = "http://camlogg.xn--fors-8qa.se/uploads/olle/bygglatt/dingens/1/retro-wallpaper-1.jpg";
     myBoard.setImg(url);     
  }


function signature(){
  divResized = "imageEdit";  
  $('#attachments').attr('class','modal-dialog custom-class'); 
  $('#imagesDiv').hide();
  $('#attachmentButtons').hide();

  myBoard = new DrawingBoard.Board('custom-board-2', {
      controls: [
        'Color',
        { Size: { type: 'dropdown' } },
        { DrawingMode: { filler: false } },
        'Navigation',
        'Download'
      ],
      size: 10,
      color: "#f00",          
      enlargeYourContainer: true,
      droppable: false, //try dropping an image on the canvas!
      stretchImg: true //the dropped image can be automatically ugly resized to to take the canvas size
      });          
     
      $("#custom-board-2").css("width", "1000px");
      $("#custom-board-2").css("height", "400px");
      myBoard.resize();
      myBoard.reset();       
      //var imgUrl = dataImage[memDivId-1].fileBlob;
      //myBoard.setImg(imgUrl);
     
      var imgControlButtons =
      '<button type="button" onclick="cancelEditImage()" class="btn btn-default">Avbryt</button>\
      <button type="button" onclick="completeEditImage()" class="btn btn-primary">Spara</button>\
      <button type="button" onclick="showEditCode()" class="btn btn-default">Visa kod</button>';
      $('#editImageButtons').append(imgControlButtons);
      

}


   
 



  $(document).ready(function () {                
    signature();
  });

  
  //checkNetworkStatus();
</script>

<div class="modal-dialog custom-class">
  <div class="modal-content">
    <div class="modal-header">            
      <div class="pull-left" id="online_status">
        <b>Signatur</b>
      </div>
      <div class="pull-right" id="thumbPreview">
        
      </div>
    </div>
    <div class="modal-body" id="mainBody">                
    


    <div class="example" data-example="3">    

        <div class="board" id="custom-board-2"></div>

   
    </div>


    </div>                
      <div class="modal-footer" id="landningPageFooter">                                       
        <h6>
          Detta är en expermentiell version av camlogg.<br />
          Det fins inga garantier för full funtionalitet.</b><br />            
        </h6>                    
      </div>
  </div>
</div>




<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog custom-class" role="document">
    <div class="modal-content">
      <div class="modal-header">                 
         
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Redigera bild</h4>

      </div>
      <div class="modal-body ">

           

      </div>
      <div class="modal-footer">
       
        <button type="button" class="btn btn-default" data-dismiss="modal">Avbryt</button>
        <button type="button" onclick="completeEditImage()" class="btn btn-primary">Spara</button>
        <button type="button" onclick="resizeShit()" class="btn btn-btn-default">Resize</button>

      </div>
    </div>
  </div>
</div>




</body>
</html>
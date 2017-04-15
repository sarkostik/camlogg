<?php 

  $root = $_SERVER['DOCUMENT_ROOT'];
  include_once $root.'/framework/views/master.php';   
 
?>
<div class="modal-dialog custom-class" id="attachments">
    <div class="modal-content">
        <div class="modal-header">            
          
              <div class="pull-left" id="online_status">
             
           </div>

           <div class="pull-right" id="thumbPreview">
                
           </div>

        </div>
        <div class="modal-body">                
            
           <label class="" id="lblInfo">Information: </label>
            <article class="main-content" role="main">       

                 <section class="main-content">
                    <div id="progress">

                    </div> 
                   
                   <div id="attachmentButtons">
                   
                   <div class="pull-right">
                      <button id="btnTransfer" class="btn btn-default disabled">Överför bilder</button>                    
                      <button id="btnClear" class="btn btn-danger disabled">Rensa</button>
                      <input type="hidden" id="keepImg" name="image" value="">
                    </div>

                    <div class="col-xs-3 col-md-1 pull-left">      
                      <label class="btn-file">
                        <img src="/img/photo-camera.svg">
                        <input type="file" id="take-picture" accept="image/*">                        
                      </label>
                    </div>
                      

                    </div>
                     <div id="editImageButtons"></div>
                     <div class="example" data-example="3">      
              


                <!-- this will be the drawingboard container -->
                    <div class="board" id="custom-board-2">

                    </div>

                    <!-- this will be the input used to pass the drawingboard content to the server -->
                 
               
                   </div>


                   
                    <p id="error"></p>
                </section>

                <div id="imagesDiv"></div>                  


            </article>

           

           
        </div>                
        <div class="modal-footer" id="landningPageFooter">                               
         <i>
            <h6>
            <b>Detta är en expermentiell version av camlogg.<br />
            Det fins inga garantier för full funtionalitet.</b>
             <div id="news"></div>
           

            </h6>
        </i>
            
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
        <?php echo $footerText; ?>
      </div>
    </div>
  </div>
</div>
       
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



    <script>
      var imgInput;
      var myBoard = "";
      
      function findImage(){
         var img = myBoard.getImg();
          
         imgInput = (myBoard.blankCanvas == img) ? '' : img;

         //var url= "http://camlogg.xn--fors-8qa.se/uploads/alfahanne.png";
         var url = "http://camlogg.xn--fors-8qa.se/uploads/olle/bygglatt/dingens/1/retro-wallpaper-1.jpg";
         myBoard.setImg(url);     
      }

    </script>

        <script type="text/javascript">            
           
            $(document).ready(function () {                
               // console.log(localStorage);
                loadImages();
                labelInformation();
                $(document.body).bind("online", checkNetworkStatus);
                $(document.body).bind("offline", checkNetworkStatus);                
                checkNetworkStatus();
            });

            function clearStorage(){                                
                localStorage.removeItem('imgData');                
                if(isDebug)console.log(localStorage);               
                $('#imagesDiv').empty();
                //location.reload();
            }

            function loadImages(){
              dataImage = [];
              var y = 1;                
              var z = 0;
              //console.log("filnamn: "+fileNames);

              var myFrames = '<div class="row">'; 
              var imgData = get_storage('imgData');

              if(imgData != null){
                
                for (var x = 0; x < imgData.length; x++){
                  
                  var img = imgData[x];

                  if (img != null){                        
                      dataImage[dataImage.length] = img;
                      numImages++;                       
                      y++;
                  }                                        
                }

                for (var x = dataImage.length-1; x >= 0; x--){
                    var fileSize = byteLength(dataImage[x].fileBlob) / 1024;
                    fileSizes += roundDecimal(fileSize);                        
                    //var imgDiv = frameImage(dataImage[x].fileBlob, x+1, dataImage[x].fileName + " filesize: " + roundDecimal(fileSize)+"kb");
                    //$("#imagesDiv").append(imgDiv);
                    myFrames += bootStrapFrame(dataImage[x].fileBlob, x+1, dataImage[x].fileName + " filesize: " + roundDecimal(fileSize)+"kb");
                }

                $("#imagesDiv").append(myFrames + '</div>');

                if (dataImage != null){
                    setButtons(true);
                }
              }
            }

            function splitter(fNames){
              var fNamesArr = [];
              var tmpString = "";

              for (var x = 0; x < fNames.length; x++){

                  if (fNames[x] != ',')
                      tmpString += fNames[x];                    
                  else{
                      fNamesArr[fNamesArr.length] = tmpString;
                      tmpString = "";
                  }                    
              }
              fNamesArr[fNamesArr.length] = tmpString;

              return fNamesArr;
            }          
        </script>   
    </body>
</html>
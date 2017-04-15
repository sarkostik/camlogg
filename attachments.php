<?php 

     include $_SERVER['DOCUMENT_ROOT'].'/framework/views/master.php';
?>      


    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">            
               <div class="pull-left" id="online_status">
                  <strong>Portal</strong>    
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
                        <p>
                        <label class="btn btn-default btn-file">
                            Välj bild <input type="file" style="display: none;" id="take-picture" accept="image/*">
                        </label>

                        <br>

                        <button id="btnTransfer" class="btn btn-default disabled">Överför bilder</button>
                        <button id="btnStore" class="btn btn-default disabled">Spara bilder lokalt</button>
                        <button id="btnClear" class="btn btn-danger disabled">Rensa</button>
                        
                        </p>
                        <p id="error"></p>
                    </section>

                    <div id="imagesDiv"></div>                  


                </article>

                <div id="news"></div>

               
            </div>                
            <div class="modal-footer" id="landningPageFooter">                               
             <i>
                <h6>
                <b>Detta är en expermentiell version av camlogg.<br />
                Det fins inga garantier för full funtionalitet.</b>

                </h6>
            </i>
                
            </div>
        </div>
    </div>


       
        <script src="/js/imagetools.js"></script>
        <script src="/js/base.js"></script>
        <script src="/js/ui/base.js"></script>
        <script src="/js/ui/xhr.js"></script>
        <script src="/js/ui/image_control.js"></script>
        <script type="text/javascript">
            isOnline = false;
            $(document).ready(function () {                
                console.log(localStorage);
                loadImage();
                labelInformation()
                $(document.body).bind("online", checkNetworkStatus);
                $(document.body).bind("offline", checkNetworkStatus);                
                checkNetworkStatus();
            });

            function clearStorage(){
                localStorage.clear();
                id = 1;
                scaledBlobs = [];
                numImages = 1;
                imgBlobs = [];
                fileSizes = 0;
                $("#imagesDiv").empty();    
                setButtons(false);            
            }

            function loadImage(){

                var dataImage = [];
                var y = 1;
                var x = true;
                var z = 0;

                fileSizes = localStorage.getItem('fileSizes');
                if (fileSizes == null)
                    fileSizes = 0;
                else{
                    fileSizes = roundDecimal(fileSizes);
                }

                if (localStorage.getItem('fileNames') != null){
                    setButtons(true);
                    var fileNames = splitter(localStorage.getItem('fileNames'));
                }

                console.log("filnamn: "+fileNames);


                while (x == true){
                    var img = localStorage.getItem('imgData'+ y);
                    
                    if (img != null){
                        dataImage[z] = img; 
                        imgFiles[z] = img;                   
                        var imgDiv = frameImage(dataImage[z], y, fileNames[z]);
                        $("#imagesDiv").append(imgDiv);
                        savedImages++;
                        numImages++;
                        z++
                        y++;
                    }
                    else 
                        x = false;                    
                }
                console.log("antal bilder sparade: " + savedImages);
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
           
            function checkNetworkStatus() {
                console.log('I am checking');
                if (navigator.onLine) {
                    // Just because the browser says we're online doesn't mean we're online. The browser lies.
                    // Check to see if we are really online by making a call for a static JSON resource on
                    // the originating Web site. If we can get to it, we're online. If not, assume we're
                    // offline.
                    $.ajaxSetup({
                        async: true,
                        cache: false,
                        context: $("#status"),
                        dataType: "json",
                        error: function (req, status, ex) {
                            console.log("Error: " + ex);
                            // We might not be technically "offline" if the error is not a timeout, but
                            // otherwise we're getting some sort of error when we shouldn't, so we're
                            // going to treat it as if we're offline.
                            // Note: This might not be totally correct if the error is because the
                            // manifest is ill-formed.
                            showNetworkStatus(false);
                        },
                        success: function (data, status, req) {
                            showNetworkStatus(true);
                        },
                        timeout: 5000,
                        type: "GET",
                        url: "ping.js"
                    });
                    $.ajax();
                } else {
                    showNetworkStatus(false);
                }
            }
            
            var currentlyOnline = null;
            function showNetworkStatus(online) {
                if (online != currentlyOnline) {
                    if (online) {
                        isOnline = true;
                        $("#online_status").html("<span class='label label-success'>Portalen är online</span>");
                        $('#news').load('framework/core/news.php', function (response) {
                            localStorage.setItem('news', response);
                        });
                    } else {
                        isOnline = false;
                        $("#online_status").html("<span class='label label-warning'>Offline</label>");
                        $('#news').html(localStorage.getItem('news'));
                    }
                    currentlyOnline = online;
                }
            }
        </script>   
    </body>
</html>
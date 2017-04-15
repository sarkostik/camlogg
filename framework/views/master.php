<?php

$root = $_SERVER['DOCUMENT_ROOT'];
$headerText = "";
$footerText = "<i><h6><b>Detta är en expermentiell version av camlogg.<br />Det fins inga garantier för full funtionalitet.</b></h6></i>";

//$manifest = 'manifest="/cache.manifest"';

?>

<!DOCTYPE html>
<html lang="en" <?php echo $manifest ?> >
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
       
        <script src="/js/ui/base.js"></script>       
        <script type="/js/math.js"></script>
        <script src="/js/ui/logic/base.js"></script>        
        <script src="/js/jquery.min.js"></script>
         <?php 
            $numRows = count($jsArray);
            for ($x = 0; $x < $numRows; $x++){
              echo '<script src='.$jsArray[$x].'></script>';
            }
        ?>        
        <script src="/js/ui/net/base.js"></script>
        <script src="/js/math.js"></script>      
         <script src="/js/ui/logic/sort.js"></script>      
        <script src="/js/bootstrap.min.js"></script>
        <script src="/js/bootstrap-select.js"></script>
        <script src="/js/i18n/defaults-sv_SE.min.js"></script>
        <script src="/js/ui/user.js"></script>
        <script src="/js/sha512.js"></script>
        <script src="/js/sweetalert2.js"></script>
        <script src="/js/bootstrap-slider.min.js"></script>
        <script src="/js/spin.min.js"></script>
        <script src="/js/ui/dummy.js"></script>
        <script src="/js/fine-uploader/jquery.fine-uploader.js"></script>


        <script type="text/javascript">
            var isDebug = true;

            function getCompanyDetails(){
                myCompany = JSON.parse(localStorage.getItem('companyData'));               
            }

            function getStatusUpdates(){
               
            }
             
            $(document).ready(function () {

                      
                $(document).ready(function () {                
                 
                });
                
                window.addEventListener('online',  updateOnlineStatus);
                window.addEventListener('offline', updateOfflineStatus);

                if(findUserCookie()){                    
                    initLogic();
                    
                    if (isDebug)
                        console.log(localData);

                    if (isOnline)
                        getStatusUpdates();
                    
                    getCompanyDetails();
                }                
            });
        </script>
        <link href="/css/base.css" rel="stylesheet">
        <link href="/css/bootstrap.min.css" rel="stylesheet">
        <link href="/css/custom.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="/css/sweetalert2.css">
        <link rel="stylesheet" type="text/css" href="/css/bootstrap-slider.min.css">        
        <link rel="stylesheet" href="/js/draw/prism.css">
        <link rel="stylesheet" href="/js/draw/website.css">
        <!-- in a production environment, you can include the minified css. It contains the css of the board and the default controls (size, nav, colors):
        <link rel="stylesheet" href="../dist/drawingboard.min.css"> -->
        <link rel="stylesheet" href="/css/drawingboard.css">
        <link rel="stylesheet" href="/css/datepicker/bootstrap-datepicker.css">
        <link rel="stylesheet" href="/css/bootstrap-select.min.css">
        <link href="/js/fine-uploader/fine-uploader-new.css" rel="stylesheet">
        <style>
        /*
        * drawingboards styles: set the board dimensions you want with CSS
        */
        .board {
            
        }
        </style>
        
        <style data-example="3">
            #custom-board-2 {
                
            }

            #custom-board-2 canvas {
                
            }          

            #custom-board-21 {
                width: 850px;
                height: 500px;
            }

            #custom-board-21 canvas {
                transform: scale(0.95);
            }
        </style>

        <style>
         @media screen and (min-width: 850px) {
             .custom-class {
              width: 60%; /* either % (e.g. 60%) or px (400px) */
          }
        }
        </style>
        <title>Camlogg webapplikation / arbetsflöden</title>
    </head>

    <body>
    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" runat="server" href="/">Camlogg</a>
            </div>
            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav" id="navLeftBar">                            
                     
                </ul>
                <ul class="nav navbar-nav navbar-right"  id="navRightBar">

                </ul>
            </div>
        </div>
    </nav>
    
    <div class="modal fade" id="inboxModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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

<script type="text/javascript">
    var resizeTimer;
    var divResized;

    $(window).on('resize', function(e) {
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(function() {                
        resizedAndResize();
        
      }, 250);
    });



    function resizedAndResize(){
        if (divResized == "imageEdit")
            imageDivResized();        
    }

    function inboxModalTogge (){    
        $('#inboxModal').modal('toggle');   
    }           

</script>
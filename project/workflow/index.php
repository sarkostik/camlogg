<?php 

  $root = $_SERVER['DOCUMENT_ROOT'];
  include_once $root.'/framework/views/master.php';
?>
<style>
table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

td, th {
    border: 0px solid #dddddd;
    text-align: left;
    padding: 8px;
}

tr:nth-child(even) {
    background-color: #dddddd;
}
</style>

<script src="/js/quill.min.js"></script>
<link href="/js/quill/quill.snow.css" rel="stylesheet">
<link href="/js/quill/quill.bubble.css" rel="stylesheet">
<link href="/js/quill/quill.core.css" rel="stylesheet">
<script src="/js/quill/quill.core.js"></script>
<script src="/js/imagetools.js"></script>
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
var timeIdentifier;
var expandedId = null;
var flowData = [];
var myBoard;

function attachmentDialog(id){
  console.log(id);  
  var quill = new Quill('#editor-container', {
    modules: {
      toolbar: [
        [{ header: [1, 2, false] }],
        ['bold', 'italic', 'underline'],
        ['image', 'code-block']
      ]
    },
    placeholder: 'Compose an epic...',
    theme: 'snow'  // or 'bubble'
  });
}

function navEditFlow(id){
  console.log("hejsan");
}

function switchFlow(id){

}

function getFlowUpdates(){
  if (isOnline){
    $.ajax({
      type: 'GET',
      url: '/framework/core/project/flow/list',                    
      cache: false,   
      dataType: 'JSON',     
      success: function(response) {
        if(isDebug)console.log(response);
          var flowData = response.Flows;
          var projectDetails = "";
          var flowDetails ="";

          for (var x in flowData){
            var pId = flowData[x].FlowId;

            projectDetails +=
              '<tr><td><a href="#" onclick=switchFlow('+pId+') >'+flowData[x].Flowname+'</a></td>';             
              projectDetails += 
              '<td>'+
                '<a href="#" title="Överför en fil" onclick="attachmentDialog('+pId+')"><img src="/img/upload-icon.png"></a> '+                      
                '<a href="#" title="Anteckna" onclick="noteDialog()"><img src="/img/note.png"></a> '+
                '<a href="edit/" title="Redigera flöde" onclick=navEditFlow('+pId+')><img src="/img/edit-button.png"></a>';
              projectDetails += '</tr><div id="abc"></div>';
              var onclicker = 'flowDetails("'+pId+'")';
              flowDetails += 
              '<button type="button" onclick='+onclicker+' class="list-group-item">Flödesnamn: '+ flowData[x].Flowname+
              '</button><div id="flow_'+pId+'"></div>';
          } 
          
          $('#mainBodyFlows').append(flowDetails);                    
          $('#mainBody').append('<div id="editor-container"></div>');            
      },
      error: function(error) {                    
        if(isDebug)console.log(error);
      },                  
    });
  }
}

function flowDetails(flowId){
  if (flowId == expandedId){
    if (isExpanded){        
      retractDetails();
      isExpanded = false;
      expandedId = null;
    }     
  } 
  else if (expandedId == null){
    isExpanded = true;
    expandedId = flowId; 
    expandDetails();
  } 
  else if (expandedId != null){
    retractDetails();     
    expandedId = flowId;
    expandDetails();
  }
}

function expandDetails(){
  var controlBtns =
  '<div class="pull-right" id="ctrlBtn">'+
    '<a href="#" title="Lägg till en ny händelse"><img src="/img/add.png"></a> '+
    '<a href="#" title="Signatur" onclick="expandSignature()"><img src="/img/signature.png"></a> '+
    '<a href="#" title="Överför filer" onclick="attachmentFiles('+expandedId+')"><img src="/img/upload-icon.png"></a> '+
    '<a href="#" title="Anteckning" onclick="noteDialog()"><img src="/img/note.png"></a> '+
    '<a href="edit/" title="Redigera flöde" onclick=navEditFlow('+expandedId+')><img src="/img/edit-button.png"></a>'+
  '</div><br />';
  
  $.ajax({
    type: 'GET',
    url: '/framework/core/project/flow/list?flowId='+expandedId,                    
    cache: false,   
    dataType: 'JSON',     
    success: function(response) {       
      flowData = response.Flows;
      
      for (var x in flowData){
        if (flowData[x].Id == flowData[x].RelationId){            
          controlBtns += '<a href="#" onclick=showFlowData('+x+')>'+flowData[x].Content+'</a><br>';            
        }
      }
    
      $('#flow_'+expandedId).append(controlBtns);

    },
    error: function(error) {                    
      if(isDebug)console.log(error);
    },                  
  });    
}

function showFlowData(id){
  var signature =         
  '<button onclick="cancelFlowData()" class="btn btn-default">Avbryt</button>';

  $('#signature').append(signature);
  $('#mainBodyFlows').hide();

  //console.log(flowData[id]);

  for (var x in flowData){
    if (flowData[x].RelationId == flowData[id].RelationId)
      console.log(flowData[x].TypeContent);
  }


  /*    
  $_GET['content'];
  $_GET['contentId'];
  */


}

function cancelFlowData(){
  restoreCurrentList();
}

function submitSignature(){
  restoreCurrentList();
} 

function cancelSignature(){
  restoreCurrentList();
}

function restoreCurrentList(){
  $('#mainBodyFlows').show();
  $('#signature').empty();
  $('#mainBodyCtrlButtons').empty();
  $('#fine-uploader-manual-trigger').empty();
}

function getMyImg(){
  var a = myBoard.getImg();
    console.log(a);  
}

function expandSignature(){
  var signature =     
  '<div class="example" data-example="3">'+
  '<div class="board" id="custom-board-2"></div>'+
  '<button onclick="submitSignature()" class="btn btn-primary">Signera</button>'+
  '<button onclick="cancelSignature()" class="btn btn-default">Avbryt</button>'+
  '<button onclick="getMyImg()" class="btn btn-default">Visa bild</button>'+
  '</div>';

  $('#signature').append(signature);
  $('#mainBodyFlows').hide();

  myBoard = null;
  myBoard = new DrawingBoard.Board('custom-board-2', {
    controls:false,
    size: 5,
    color: "#00f",          
    enlargeYourContainer: true,
    webStorage: false,
    droppable: false, //try dropping an image on the canvas!
    stretchImg: true //the dropped image can be automatically ugly resized to to take the canvas size
  });          
   
  $("#custom-board-2").css("width", "300px");
  $("#custom-board-2").css("height", "200px");      
  myBoard.resize();
  myBoard.reset();             
}

function submitAttachment(){    
  var myComment = $('#txtArea').val();
  var myDat = { uuid:timeIdentifier, comment:myComment, flowId:expandedId };

  $.ajax({
    type: 'POST',
    url: '/framework/core/uploadFiles/summit.php',
    data: myDat,
    cache: false,   
    dataType: 'JSON',     
    success: function(response) {
      console.log(response);
      restoreCurrentList();
    },
    error: function(error) {                    
      if(isDebug)console.log(error);
    },                  
  });    
}

function attachmentFiles(){
  var currentdate = new Date(); 
  timeIdentifier =
  currentdate.getDate() + "-"
  + (currentdate.getMonth()+1)  + "-" 
  + currentdate.getFullYear() + " "
  + currentdate.getHours() + ":"  
  + currentdate.getMinutes() + ":" 
  + currentdate.getSeconds();
  
  console.log(timeIdentifier);
  var attachmentBtns =    
  '<textarea rows="4" class="form-control" placeholder="Kommentar" id="txtArea"></textarea><br />'+
  '<button onclick="submitAttachment()" class="btn btn-submit">Ok</button>'+
  '<button onclick="restoreCurrentList()" class="btn btn-default">Avbryt</button>';

  $('#mainBodyFlows').hide();
  $('#mainBodyCtrlButtons').append(attachmentBtns);
  $('#fine-uploader-manual-trigger').fineUploader({
    template: 'qq-template-manual-trigger',
    request: {
        endpoint: 'http://testportal.ollesmobilservice.se/framework/core/uploadFiles/endpoint.php?flowId='+expandedId+'&identifier='+timeIdentifier
    },
    thumbnails: {
        placeholders: {
            waitingPath: '/source/placeholders/waiting-generic.png',
            notAvailablePath: '/source/placeholders/not_available-generic.png'
        }
    },
    autoUpload: false,
    callbacks: {
        onComplete: function(id, name, response) {                
            console.log(id);
            console.log(name);
            console.log(response);
        }
    }
  });

  $('#trigger-upload').click(function() {
      $('#fine-uploader-manual-trigger').fineUploader('uploadStoredFiles');
  });
}

function retractDetails(){    
  $('#flow_'+expandedId).empty();
}

$(document).ready(function () {                
  if(findUserCookie()){          
      $('#projectAdm').append('<a href="new/"><img style="max-width:30px" src="/img/add.png"></a>');
      getFlowUpdates();
      abc = setTimeout(function() {      
      });                         
  }        
});

</script>

<script type="text/template" id="qq-template-manual-trigger">
  <div class="qq-uploader-selector qq-uploader" qq-drop-area-text="Släpp filer här">
      <div class="qq-total-progress-bar-container-selector qq-total-progress-bar-container">
          <div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="qq-total-progress-bar-selector qq-progress-bar qq-total-progress-bar"></div>
      </div>
      <div class="qq-upload-drop-area-selector qq-upload-drop-area" qq-hide-dropzone>
          <span class="qq-upload-drop-area-text-selector"></span>
      </div>
      <div class="buttons">
          <div class="qq-upload-button-selector qq-upload-button">
              <div>Välj filer</div>
          </div>
          <button type="button" id="trigger-upload" class="btn btn-primary">
              <i class="icon-upload icon-white"></i> Ladda upp
          </button>
      </div>
      <span class="qq-drop-processing-selector qq-drop-processing">
          <span>Släpp filer här...</span>
          <span class="qq-drop-processing-spinner-selector qq-drop-processing-spinner"></span>
      </span>
      <ul class="qq-upload-list-selector qq-upload-list" aria-live="polite" aria-relevant="additions removals">
          <li>
              <div class="qq-progress-bar-container-selector">
                  <div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="qq-progress-bar-selector qq-progress-bar"></div>
              </div>
              <span class="qq-upload-spinner-selector qq-upload-spinner"></span>
              <img class="qq-thumbnail-selector" qq-max-size="100" qq-server-scale>
              <span class="qq-upload-file-selector qq-upload-file"></span>
              <span class="qq-edit-filename-icon-selector qq-edit-filename-icon" aria-label="Edit filename"></span>
              <input class="qq-edit-filename-selector qq-edit-filename" tabindex="0" type="text">
              <span class="qq-upload-size-selector qq-upload-size"></span>
              <button type="button" class="qq-btn qq-upload-cancel-selector qq-upload-cancel">Avbryt</button>
              <button type="button" class="qq-btn qq-upload-retry-selector qq-upload-retry">Försök igen</button>
              <button type="button" class="qq-btn qq-upload-delete-selector qq-upload-delete">Ta bort</button>
              <span role="status" class="qq-upload-status-text-selector qq-upload-status-text"></span>
          </li>
      </ul>

      <dialog class="qq-alert-dialog-selector">
          <div class="qq-dialog-message-selector"></div>
          <div class="qq-dialog-buttons">
              <button type="button" class="qq-cancel-button-selector">Stäng</button>
          </div>
      </dialog>

      <dialog class="qq-confirm-dialog-selector">
          <div class="qq-dialog-message-selector"></div>
          <div class="qq-dialog-buttons">
              <button type="button" class="qq-cancel-button-selector">Nej</button>
              <button type="button" class="qq-ok-button-selector">Ja</button>
          </div>
      </dialog>

      <dialog class="qq-prompt-dialog-selector">
          <div class="qq-dialog-message-selector"></div>
          <input type="text">
          <div class="qq-dialog-buttons">
              <button type="button" class="qq-cancel-button-selector">Avbryt</button>
              <button type="button" class="qq-ok-button-selector">Ok</button>
          </div>
      </dialog>
  </div>
</script>


<style>
  #trigger-upload {
      color: white;
      background-color: #00ABC7;
      font-size: 14px;
      padding: 7px 20px;
      background-image: none;
  }

  #fine-uploader-manual-trigger .qq-upload-button {
      margin-right: 15px;
  }

  #fine-uploader-manual-trigger .buttons {
      width: 36%;
  }

  #fine-uploader-manual-trigger .qq-uploader .qq-total-progress-bar-container {
      width: 60%;
  }
</style>



<div class="modal-dialog custom-class">
  <div class="modal-content">
      <div class="modal-header">            
         <div class="pull-left" id="online_status">                          
            <select class="selectpicker" multiple="true" id="selectStatus">
              <option value="1" enabled>Aktiv</option>
              <option value="0">Inaktiv</option>
            </select>
         </div>
         <div class="pull-right" id="projectAdm">


         </div>           

         <div class="pull-right" id="thumbPreview">
           
         </div>

      </div>
      <div class="modal-body" id="mainBody">                
                
        <div id="mainBodyFlows"></div>       
        <div id="fine-uploader-manual-trigger"></div>
        <div id="signature"></div>
        <div id="mainBodyCtrlButtons"></div>

      </div>                
      <div class="modal-footer" id="landningPageFooter">                               
      
      <?php echo $footerText; ?>
          
      </div>
  </div>
</div>
</body>
</html>
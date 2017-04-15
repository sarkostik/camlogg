(function () {
    var takePicture = document.querySelector("#take-picture"),
        showPicture = document.querySelector("#imagesDiv");

    if (takePicture && showPicture) {
        
        takePicture.onchange = function (event) {
    
          setButtons(true);

           var files = event.target.files,file;

            if (files && files.length > 0) {
              file = files[0];
              addImage(file);
            }            
        }
    }
})();


function addImage(file){
  imgFiles[imgFiles.length] = file;
  var URL = window.URL || window.webkitURL;
  var imgURL = URL.createObjectURL(file);
  imgBlobs[imgBlobs.length] = imgURL;
  var quality = localStorage.getItem('imgQuality');                

  ImageTools.resize(file, {
      width: quality, // maximum width
      height: quality // maximum height
  }, function(blob, didItResize) {                    
      var fSize = file.size / 1024;                    
      
      if (fSize == "NaN")
        fSize = 0;

      var fSizeInt = roundDecimal(fSize);
      var myBlob = URL.createObjectURL(blob);
      scaledBlobs[scaledBlobs.length] = myBlob;

      toDataUrl(myBlob, function(base64Img) {
        var ffile = sumImages();

        if (!isQuotaFull){

          $("#thumbPreview").html('<img id="myThumb" style="max-width: 45px;" src="'+base64Img+'">');

          getImageSize($('#myThumb'), function(width, height) {
              try{                              
                var fileSize = byteLength(base64Img) / 1024;
                fileSize = roundDecimal(fileSize)                               
                fileSizes += fileSize;                  
                labelInformation();
                var imgDiv = frameImage(base64Img, numImages, file.name + ", "+ fileSize + " kb");
                fileNames[fileNames.length] = file.name;                                   
                var existingDiv = $("#imagesDiv").html();
                $("#imagesDiv").empty();
                $("#imagesDiv").append('<div id="image_'+numImages+'">'+imgDiv+'</div>');                                              
                $("#imagesDiv").append(existingDiv);                              
                var img = {fileName:file.name, fileSize:file.size,imageWidth:width,imageHeight:height,fileBlob:base64Img};
                dataImage[dataImage.length] = img;                                   
                //localStorage.setItem('imgData'+ffile,JSON.stringify(img));                 
                var tmpImageData = JSON.parse(localStorage.getItem('imgData'));
                if (tmpImageData == null)
                  tmpImageData = [];
                tmpImageData.push(img);    
                //localStorage.setItem('imgData', JSON.stringify(tmpImageData));
                set_local('imgData', tmpImageData);

                ffile++;
              } 

              catch(e){
                isQuotaFull = true;
                if (isDebug)
                  console.log("det gick inte att lagra mer filer!");

                if (isOnline){
                  if (isDebug)
                    console.log("Du är online");
                }
                else{
                  swal({
                    title: "Försöka synkronisera?",
                    text: "Utrymmet för intern lagring är full?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Vänta, Gör ett försök!",
                    closeOnConfirm: false
                  },
                  function(){
                    swal("Deleted!", "Your imaginary file has been deleted.", "success");
                  });                            
                }
             }                            
          });                                    
        }       
      });
                        
      if (navigator.onLine) {                   
        $.ajaxSetup({
            async: true,
            cache: false,
            context: $("#status"),
            dataType: "json",
            error: function (req, status, ex) {
              if (isDebug)
                 console.log("Error: " + ex);
               imOffline();
            },
            success: function (data, status, req) {
                if (isDebug)
                  console.log("horray im online!");
                showNetworkStatus(true);                              
                var myHash = getUserCookie();                              
                var userDetails = "p="+myHash.pw + "&email="+myHash.email;

                $.ajax({
                    type: 'POST',
                    url: '/framework/core/user/checkHash.php',        
                    data: userDetails,
                    dataType: 'JSON',
                    cache: false,        
                    success: function(response) {    
                        if (isDebug)         
                          console.log(response);  
                        if (response.Login)
                          sendImage(file);
                        else{
                            swal({
                              title: "Dina kontouppgifter stämmer ej!",
                              text: "Vill du logga in på nytt?",
                              type: "warning",
                              showCancelButton: true,
                              confirmButtonColor: "#DD6B55",
                              confirmButtonText: "Ja",
                              cancelButtonText: "Nej",
                              closeOnConfirm: false,
                            },
                              function(isConfirm){
                                if (isConfirm) {
                                  ajaxLogin();
                                  imageNotSent();

                                swal({
                                  title: "Ange ditt lösenord",
                                  text: myHash.email,
                                  type: "input",
                                  inputType: 'password',
                                  showCancelButton: true,
                                  closeOnConfirm: false,
                                  showLoaderOnConfirm: true,
                                  animation: "slide-from-top",
                                  inputPlaceholder: "Lösenord"
                                },
                                  function(inputValue){
                                    if (inputValue === false) return false;

                                    var isPw = checkPw(inputValue);

                                    if (!isPw) {                                                    
                                      swal.showInputError("Du måste skriva ett giltligt lösenord!");
                                      return false;
                                    }

                                    var myHash = getUserCookie();      
                                    var userDetails = "p="+myHashedPw + "&email="+myHash.email;
                                    var rtnMsg = "Något gick fel. Prova igen!";

                                    $.ajax({
                                      type: 'POST',
                                      url: '/framework/includes/login_json.php',        
                                      data: userDetails,
                                      dataType: 'JSON',
                                      cache: false,        
                                      success: function(response) { 
                                          if (isDebug){
                                            console.log("Provar: " + response);
                                            console.log(response);                                                        
                                          }                                            
                                          if (response.loginResponse){                                                                                               
                                            storeCookie(myHashedPw, myHash.email);                                                         
                                            $('#online_status').append('<div class="alert alert-info" role="alert">Ej synkroniserad</div>');
                                            
                                            swal("Ditt konto är återställt!")
                                          }
                                          else{                                                          
                                            swal.showInputError("Fel lösenord!");
                                            return false; 
                                          }
                                      },
                                      error: function(error) {   
                                          if (isDebug) 
                                            console.log(error);
                                          swal.showInputError("Kunde ej ansluta. Försök gärna igen lite senare!");
                                      }            
                                    });
                                    setTimeout(function(){
                                      if (rtnMsg){                                                     
                                      }
                                       //swal(rtnMsg);
                                    }, 2000);                                                    
                                  });
                                } 
                                else {
                                  imageNotSent();                                                                                              
                                }
                              });           
                        }                                    
                    },
                    error: function(error) {
                      if (isDebug)                                     
                        console.log("fel: " + error);
                    }            
                });                              
            },
            timeout: 5000,
            type: "GET",
            url: "/ping.js"
        });
        $.ajax();
      } else {
          imOffline();
      }

      numImages++;                    
  });
}

function checkPw(pw){
  
  if (pw.length > 7){
    myHashedPw = hex_sha512(pw);  
    return true;
  }

  return false;
}

function ajaxLogin(){
  
  if (isDebug)console.log("loggar in");

  var myHash = getUserCookie();      
  var userDetails = "p="+myHash.pw + "&email="+myHash.email;

  $.ajax({
    type: 'POST',
    url: '/framework/includes/login_json.php',        
    data: userDetails,
    dataType: 'JSON',
    cache: false,        
    success: function(response) {
      if (isDebug)console.log(response);          
    },
    error: function(error) {
      if (isDebug)console.log("fel: " + error);
    }            
  });
}

function imageNotSent(){
  imagesNotSent[imagesNotSent.length] = numImages;
  if (isDebug){
    console.log(imagesNotSent);
    console.log("inte sänt");
  }    
}

function imOffline(){
  imageNotSent();
  showNetworkStatus(false);
  if(isDebug)console.log("fuck im offline!");
}

function sumImages(){
    var y = 1;
    var x = true;

    while (x == true){
        var img = localStorage.getItem('imgData'+ y);

        if (img != null)
            y++;        
        else 
            x = false;                    
    }

    return y;
}

function labelInformation(){  
  $('#lblInfo').empty('');
  $('#lblInfo').append("Total bildmängd: "+fileSizes+ " kb");
}

function frameImage(img, y, figcap){
  if(isDebug)   
    console.log(y);               
  return bootStrapFrame(img,y,figcap);  
}

function bootStrapFrame(file, id, fNameSize){
  var a =
    '<div class="col-xs-6 col-md-3">\
        <a href="#" onclick="editImage('+id+')" class="thumbnail">\
          <img id="image'+id+'" src="'+file+'" alt="...">\
        </a>\
        '+fNameSize+'\
      </div>';

  return a;
}


function editImage(mid){
  divResized = "imageEdit";
  memDivId = mid;  
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

    getImageSize($('#image'+memDivId), function(width, height) {
          var orgWidth = 1200;         
          var newHeight = height;
          var newWidth = width;
          memHeight = height;
          memWidth = width;
          var ratio = orgWidth / width;                    
          if (ratio < 1){  
            newHeight = height * ratio;
            newWidth = width * ratio;            
          }   
          //var imgUrl = dataImage[memDivId-1].fileBlob;
          //myBoard.setImg(imgUrl);      

          resizeMe();
          var imgControlButtons =
          '<button type="button" onclick="cancelEditImage()" class="btn btn-default">Avbryt</button>\
          <button type="button" onclick="completeEditImage()" class="btn btn-primary">Spara</button>\
          <button type="button" onclick="showEditCode()" class="btn btn-default">Visa kod</button>';
          $('#editImageButtons').append(imgControlButtons);
          memWidthWindow = $('#attachments').width();
        });
}

function showEditCode(){  
  //console.log(myCords);
  //console.log(myCordsMid);
  myBoard.storeLocal();
  
  //console.log(drawHistory)
}

function imageDivResized(){
  var width = $('#attachments').width();
  if (isDebug)console.log("tidigare bredd: " + memWidthWindow);
  if (isDebug)console.log("ny bredd: " + width);

  if (memWidthWindow != width){
    if (isDebug)console.log("ändrar storlek på fönstret");
    
    memWidthWindow = width;
    resizeMe();
  }
}

function resizeMe(){  
  memWidthWindow = $('#attachments').width();
  var newWidth = memWidthWindow - 50;
  var ratio = newWidth / memWidth;  
  var newHeight = memHeight * ratio;
  //var boardImg = myBoard.getImg();
  $("#custom-board-2").css("width", newWidth+"px");
  $("#custom-board-2").css("height", newHeight+"px");
  myBoard.resize();
  myBoard.reset();       
  var imgUrl = dataImage[memDivId-1].fileBlob;
  myBoard.setImg(imgUrl);
}

function cancelEditImage(){
  closeImageEdit();
}

function completeEditImage(){
  xhrUpdateImageChanges();
  //closeImageEdit();  
  //var editedBlob = myBoard.getImg();
  //dataImage[memDivId-1].fileBlob = editedBlob; 
  //var orgImg = JSON.parse(localStorage.getItem('imgData'+(memDivId)));  
  //var img = {fileName:orgImg.fileName, fileSize:orgImg.fileSize,fileBlob:editedBlob};
  //localStorage.setItem('imgData'+(memDivId),JSON.stringify(img));  

 // var board = localStorage.getItem('drawing-board-custom-board');
 // if (board != null){
  //  localStorage.removeItem('drawing-board-custom-board');
 // }

  //var id = memDivId;
  //myBoard.clearWebStorage();
  //$('#image'+id).attr('src', editedBlob);

}

function closeImageEdit(){
  divResized = null;
  $('#custom-board-2').empty('');
  $("#custom-board-2").css("width", "0px");
  $("#custom-board-2").css("height", "0px");
  $('#attachments').attr('class','modal-dialog modal-lg'); 
  $('#editImageButtons').empty()
  $('#imagesDiv').show();
  $('#attachmentButtons').show();
}

function storeImage(){
    for (var x = 0; x < scaledBlobs.length; x++){
        var a = x + 1;
        bannerImage = document.getElementById('image'+ a);
        if(isDebug)console.log(bannerImage);                  

        toDataUrl(scaledBlobs[x], function(base64Img) {                         
          img64[b] = base64Img;
          b++;             

          if (img64.length == x){
              for (e = 0; e < img64.length; e++){
                  var f = e + 1;
                  if (savedImages > 0){
                      f = savedImages+1;
                  }                                       
              }
          }
        });
    }            
}

function toDataUrl(url, callback) {
    var xhr = new XMLHttpRequest();
    xhr.onload = function() {
    var reader = new FileReader();
    reader.onloadend = function() {
      callback(reader.result);
    }
    reader.readAsDataURL(xhr.response);
    };
    xhr.open('GET', url);
    xhr.responseType = 'blob';
    xhr.send();
}

function getBase64Image2(img) {
    var canvas = document.createElement("canvas");
    canvas.width = img.width;
    canvas.height = img.height;

    var ctx = canvas.getContext("2d");
    ctx.drawImage(img, 0, 0);

    var dataURL = canvas.toDataURL("image/png");

    return dataURL.replace(/^data:image\/(png|jpg);base64,/, "");
}
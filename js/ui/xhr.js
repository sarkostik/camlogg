function xhrUpdateImageChanges(){ 
  var fd = new FormData(); 
  var p = getUserCookie();  
  
  var jsonObj = get_storage('editImageData');
  var json = JSON.stringify(jsonObj[jsonObj.length-1]);
  var userDetails = 'p=' + p.pw + "&email="+p.email+"&image="+json;
  $.ajax({
      type: 'POST',
      url: '/framework/core/image.php',        
      data: userDetails,
      
      cache: false,        
      success: function(response) {
          console.log("yes det gick!");
          console.log(response);
      },
      error: function(error) {          
          console.log("fel");
          console.log(error.responseText);
      },                  
  });
}

function transferImages(){

  if (isOnline){
  	if (imgFiles.length > 0){
  		var count = imgFiles.length;  

  			  for (var index = 0; index < count; index ++) 
  			  { 
         		var file = imgFiles[index];      
    			  if(isDebug)console.log(file)
		    	     sendImage(file);     
      		}
		}
    	else{
      		alert("Du måste ta bilder för att kunna skicka!");    
    	}
	}
	else{
		alert("Ej online, kan ej överföra bilder!");    
	}  
}

function sendImage(file){
  var imgSize = roundDecimal(file.size / 1024)  
  var fd = new FormData(); 
  var p = getUserCookie();  
  var workObj = encodeWorkFlows();
  var companyObj = encodeCompanyData();
  transferSize += imgSize;

  fd.append('myFile', file);
  fd.append('p',p.pw);
  fd.append('email',p.email);
  fd.append('workObj', workObj);
  fd.append('companyObj', companyObj);
  
  var xhr = new XMLHttpRequest();
  xhr.upload.addEventListener("progress", uploadProgress, false);
  xhr.addEventListener("load", uploadComplete, false);
  xhr.addEventListener("error", uploadFailed, false);
  xhr.addEventListener("abort", uploadCanceled, false);
  xhr.open("POST", "/framework/core/upload.php");
  xhr.send(fd);
}


function progressBar(evt){
  if (progressArr.indexOf(evt.total) === -1) {    
    var transferMsg = "originalbilden";
    progressArr.push(evt.total);
    statusProgressArr.push(evt.loaded);
    myTotal += evt.total;
    $('#lblInfo').empty('');    

    if (progressArr.length > 1)
      var transferMsg = "originalbilderna";
    
    $('#lblInfo').append('för över '+transferMsg+': '+transferSize+ " kb");
  }    
  else{
    var index = progressArr.indexOf(evt.total);
    statusProgressArr[index] = evt.loaded;
  }
  
  currentProgress = 0;
  for (var x = 0; x < statusProgressArr.length; x++){
    currentProgress += statusProgressArr[x];
  }
  
  var percentComplete = Math.round(currentProgress * 100 / myTotal); 
  var myProgress =
  '<div class="progress">\
      <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="'+percentComplete+'" aria-valuemin="0" aria-valuemax="100" style="width: '+percentComplete+'%">\
          <span class="sr-only">'+percentComplete+'% Complete</span>'+percentComplete+'%\
      </div>\
  </div>';
  $('#progress').empty();
  $('#progress').append(myProgress)
}

function uploadProgress(evt) {

  

   // spinners[spinners.length] = '#image_1';
   // $('#image_1').after(new Spinner(opts).spin().el);

   	if (evt.lengthComputable) {     
    	progressBar(evt);        
  	} 
  	else
  	{ 
	    document.getElementById('progress').innerHTML = 'unable to compute'; 
  	}
}

function uploadComplete(evt) {
  var c = JSON.parse(evt.target.responseText)
  if(isDebug)console.log(c);
	 progressDone++;

  	if (progressDone == progressArr.length){    
    	$('#progress').empty();
      $('#lblInfo').empty();
      $('#lblInfo').append('Överföring är klar');

    	progressArr = [];
    	statusProgressArr = [];
    	myTotal = 0;
    	currentProgress = 0;
    	progressDone = 0;
  	}

    if (spinners.length > 0){
     // $('#image_1').spin(false);      
     // $('#image_1').hide().empty();
      if(isDebug)console.log("en spinner");
      if(isDebug)console.log(spinners);
    }

}

function uploadFailed(evt) {  
  if(isDebug)console.log(evt.target.responseText);
  //alert("There was an error attempting to upload the file."); 
}
 
function uploadCanceled(evt) { 
  if(isDebug)console.log(evt.target.responseText);
  //alert("The upload has been canceled by the user or the browser dropped the connection."); 
}
 

function sendBlob(a, fileName) {
  
  //var img = myBoard.getImg();
  //var a = (myBoard.blankCanvas == img) ? '' : img;
    
  var userDetails = 'image=' + a + "&name="+fileName;

  $.ajax({
        type: 'POST',
        url: '/framework/core/upload_64.php',        
        data: userDetails,
        dataType: 'JSON',
        cache: false,        
        success: function(response) {             
           myBoard.clearWebStorage();
           if(isDebug)console.log(response);
        },
        error: function(error) {
            
            if(isDebug)console.log(error);
        }            
    });
}
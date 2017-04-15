
function createNewUser(state) {

  if (state){      
    var p = {      
    username:$("#inputProjectName").val(),
    email:$("#emailInp").val(),
    pw:$("#inPassword").val(),           
    selDate:$('#pickyDate').val(),
    selectAccess:$('#selectAccess').val(),
    selProjects:$("#selectProject").val()
    };      
    
    p.pw = hex_sha512(p.pw);
    var errMsg = "";    
    console.log(p);
    
    for (var property in p) {
      if (p.hasOwnProperty(property)) {
        if (nullWhiteSpace(p[property]))
          errMsg += property;          
      }       
    }    
    
    var a = JSON.stringify(p);

    if (errMsg ==""){
      $.ajax({
      type: 'POST',
      url: '/framework/core/user/new/index.php',        
      data: 'username='+p.username+"&selectAccess="+p.selectAccess+
      "&email="+p.email+"&p="+p.pw+"&projects="+p.selProjects+
      "&date="+p.selDate,
      dataType: 'JSON',
      cache: false,        
      success: function(response) {    

        if(isDebug)console.log(response);

        if (response.UserCreated === true){
          swal(p.email,"Användare: "+p.username+" skapad");
          $("#inputProjectName").val('');
          $("#emailInp").val('');
          $("#inPassword").val('');
          $('#pickyDate').val('');
          $('#selectAccess').val('');
          $("#selectProject").val('');
        }
        else{
          swal('Fel',response.Message);
        }
      },
      error: function(error) {            
          if(isDebug)console.log(error);
      }            
      });
    }
    else 
      swal('Fel!','Saknar följande: '+errMsg);
  }else{
    location.href ="/project/";
  }
}

function login(){
	
}

function logout(){
	window.location.href = '/framework/includes/logout.php'
}

function get_storage(id){
	return JSON.parse(localStorage.getItem(id));
}

function set_local(id,arr){
	localStorage.setItem(id, JSON.stringify(arr));
}

function updateLocal(localId,currImageData,id){
	var a = JSON.parse(localStorage.getItem(localId));
}

function storeCookie(myPwSha, myEmail){
	  //var userDetails = {pw:myPwSha, email:myEmail};      
	  var userDetails = JSON.parse(localStorage.getItem('userDetails'));

	  userDetails.pw = myPwSha;
      localStorage.setItem('userDetails',JSON.stringify(userDetails));  
}

function storeLogin(){	
	var userDetails = {pw:"4n#¤alkgXMN"};

	if (localStorage.getItem('userDetails') != null){		

	    swal({
	      title: "Ta bort inställning för användare?",
	      text: "Utrymmet för intern lagring är full?",
	      type: "warning",
	      showCancelButton: true,
	      confirmButtonColor: "#DD6B55",
	      confirmButtonText: "Ja ta bort!",
	      closeOnConfirm: true
	    },
	    function(){
	     
	      swal("Deleted!", "Your imaginary file has been deleted.", "success");
	    });

 		localStorage.removeItem('userDetails');
	}
	else{		
	//	localStorage.setItem('userDetails',JSON.stringify(userDetails));
	}	

	//location.reload();
	window.location.href = '/';
}

function findUserCookie(){
	var userDetails = localStorage.getItem('userDetails');
	
	if (userDetails != null){
		$('#btnTmp').html('Ta bort användarcookie');
    validUser();
		return true;
	}
	else{
		//$('#btnTmp').html('Lagra inloggning lokalt');		
		return false;
	}
}

function showStorage(){
	if(isDebug)console.log(localStorage);	
	if(isDebug)console.log(localStorageQuota());

	//console.log(getLocalStorageMaxSize()); // takes .3s
	//console.log(getLocalStorageMaxSize(.1)); // takes 2s, but way more exact
		
}

function localStorageQuota(){
	var allStrings = '';
        for(var key in window.localStorage){
            if(window.localStorage.hasOwnProperty(key)){
                allStrings += window.localStorage[key];
            }
        }

        var sum = (allStrings ? 3 + ((allStrings.length*16)/(8*1024)): 'Empty (0 KB)');
        if(isDebug)console.log(sum);
        var roundedInt = roundDecimal(sum);
        
        return roundedInt + ' KB' ;
}

function getUserCookie(){	
	return JSON.parse(localStorage.getItem('userDetails'));
}

function validUser(){
  $('#navLeftBar').html(
  '<li><a href="/project/" class="navbar-link" >Mina projekt</a></li>'+
  '<li><a href="/project/workflow/" class="navbar-link" >Mina arbetsflöden</a></li>'+                    
  '<li><a href="/profile/" class="navbar-link" >Min profil</a></li>'+                      
  '<li><a href="/profile/preferences/" class="navbar-link" >Inställningar</a></li>');    

  $('#navRightBar').html(
  '<li><a href="/project/workflow/attachment" class="navbar-link"><span id="inboxBadge" class="badge">Drafts</span></a></li>\
  <li><a href="#" class="navbar-link" onclick="inboxModalTogge()">Inbox <span id="inboxBadge" class="badge">1</span></a></li>');
}

function sweetLogin(){
	  swal.setDefaults({
      input: 'text',
      confirmButtonText: 'Nästa &rarr;',
      cancelButtonText: 'Avbryt',
      showCancelButton: true,
      animation: true,
      progressSteps: ['1', '2']
    })
    var steps = [
      {
        title: 'Vill du logga in?',
        text: 'Ange din e-mail',
        input: 'email',          
        preConfirm: function (email) {
        return new Promise(function (resolve, reject) {            
          swalEmail = email
          resolve()
        })
      },
      allowOutsideClick: false
      },
      {
        title: 'Ange ditt lösenord',
        input: 'password',
        showLoaderOnConfirm: true,
        preConfirm: function(password){
           return new Promise(function (resolve, reject) {              
              if (password === '') {
                reject('Du måste skriva ett lösenord!');
              } else {                     
                  $.ajax({
                  type: 'POST',
                  url: '/framework/includes/login_json.php',        
                  data: 'p=' + hex_sha512(password) + "&email="+swalEmail,
                  dataType: 'JSON',
                  cache: false,        
                  success: function(response) {
                      if(response.loginResponse === true){
                        var userDetails = {pw:hex_sha512(password), email:swalEmail};
                        localStorage.setItem('userDetails',JSON.stringify(userDetails));   
                        localStorage.setItem('imgQuality', 1920);
                        validUser();
                        resolve()
                      }
                      else
                        reject('fel lösenord!');                        
                  },
                  error: function(error) {                                         
                      reject('Problem med anslutning till server. Försök senare!');
                  }            
              })
              }              
          })
        }
      }
    ]
    swal.queue(steps).then(function (result) {       
      swal.resetDefaults()
      swal({
        title: 'Inloggningen lyckades!',
        html:
          'Du är inloggad med följande användare: <pre>' +
            result[0] +
          '</pre>',
        confirmButtonText: 'Klar',
        showCancelButton: false,

      })
    }, function () {        
      swal.resetDefaults()        
    })
}
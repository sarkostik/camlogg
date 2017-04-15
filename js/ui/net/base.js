var currentlyOnline = null;
var isOnline = true;
var isLoginOk = false;
var isCheckSession = true;
var isDebugNet = false;
var isCheckNetwork = true;

function checkNews() {

    if(isDebugNet)console.log("kollar status");
    
    if (isCheckSession === true && findUserCookie()){
        var userDetails = 'p=1';

        $.ajax({
            type: 'POST',
            url: '/framework/core/status.php',        
            data: userDetails,      
            cache: false,        
            success: function(response) {          
                if(isDebugNet)console.log(response);          
             
                if(response.Session === false && !isLoginOk)
                    restoreSession()     
                if(response.Session === true){

                    if(isLoginOk)  
                        isLoginOk = false;

                    if(isDebugNet)console.log("nu blir det nyheter");
                }
            },
            error: function(error) {                    
              if(isDebugNet)console.log(error);
            },                  
        });
    }    
}

$(function() {    
    if (isCheckNetwork){
        checkNetworkStatus();    
        setInterval(checkNetworkStatus, timer);    
    }    
});

function restoreSession(){   
    if(isDebugNet)console.log("återställer session");     
    var myHash = getUserCookie();                              
    var userDetails = "p="+myHash.pw + "&email="+myHash.email;
   
    $.ajax({
        type: 'POST',
        url: '/framework/core/user/checkHash.php',        
        data: userDetails,
        dataType: 'JSON',
        cache: false,        
        success: function(response) {     
            if(!response.Login){
                isLoginOk = true;
                sweetLogin();
            }                        
                
        },
        error: function(error) {                    
            console.log(error.responseText);
        },                  
    });
}

function checkNetworkStatus() {
    if(isDebugNet)console.log('I am checking');
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
                if(isDebug)console.log("Error: " + ex);
                // We might not be technically "offline" if the error is not a timeout, but
                // otherwise we're getting some sort of error when we shouldn't, so we're
                // going to treat it as if we're offline.
                // Note: This might not be totally correct if the error is because the
                // manifest is ill-formed.
                console.log("offline");
                //showNetworkStatus(false);                            
            },
            success: function (data, status, req) {
                showNetworkStatus(true);
                checkNews();
            },
            timeout: 2990,
            type: "GET",
            url: "/ping.js"
        });
        $.ajax();
    } else {
        if(isDebugNet)console.log("offline");
        //showNetworkStatus(false);
    }
}
            
function showNetworkStatus(online) {
    if (online != currentlyOnline) {
        if (online) {
            isOnline = true;
            if(isDebugNet)console.log("online!");
//            checkNews();
           // $("#online_status").html("<span class='label label-success'>Portalen är online</span>");
           // $('#news').load('/framework/core/news.php', function (response) {
             //   localStorage.setItem('news', response);
            //});
        } else {
            isOnline = false;
            if(isDebugNet)console.log("offline!");
        //    $("#online_status").html("<span class='label label-warning'>Offline</label>");
          //  $('#news').html(localStorage.getItem('news'));
        }
        currentlyOnline = online;
    }
}

function updateOnlineStatus()
{
    //document.getElementById("status").innerHTML = "Online";
}

function updateOfflineStatus()
{
    //document.getElementById("status").innerHTML = "Offline";
}
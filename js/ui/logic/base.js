var webbAppData;
var companyData;
var imgQuality;
var projectData;
var userDetails;
var workFlowData;  
var localData = [];
var myCompany;     


function encodeWorkFlows(){

  var workFlows = JSON.parse(localStorage.getItem('workFlowData'));
 // if(isDebug)console.log(workFlows)
  
  var rtn = workFlows[workFlows.length-1];
  return JSON.stringify(rtn);
}     

function encodeCompanyData(){
  var workFlows = JSON.parse(localStorage.getItem('companyData'));  
  return JSON.stringify(workFlows);
}

function navigateFlow(){
  var tableHeader = []  
  tableHeader[0] = 'ProjectId';  
  tableHeader[1] = 'FlödesId';
  tableHeader[2] = 'Tidsavtryck';
  tableHeader[3] = 'Meddelande';

  var myTable = workFlowData;
  myTable.push(tableHeader);
  if(isDebug)console.log(myTable);
  return table(myTable);	
}

function navigateFlowId(x){

  return flowId(x);
}

function flowId(x){
    var flowData = workFlowData;
    var projectDetails ="";
      var projectId = x.projectId;
      var flowId = x.workFlowId;
      var timeStamp = x.timeStamp;
      projectDetails =
      '<tr>'+
        '<td><a href="#" onclick=workFlow("'+projectId+'","'+flowId+'","")>'+flowId+'</a></td>'+
        '<td><a href="#" onclick=workFlow("'+projectId+'","'+flowId+'","'+timeStamp+'")>'+timeStamp+'</a></td>'+
        '<td><a href="/project/workflow/?project='+projectId+'">'+x.message+'</a></td>';

      if (x.active)
        projectDetails += '<td><label class="label label-success">&nbsp;Aktiv&nbsp;</label><br /></td>';
      else
        projectDetails += '<td><label class="label label-default">Inaktiv</label><br /></td>';
      projectDetails += '</tr>';

      return projectDetails;
}

function initLogic(){
  localData[localData.length] = webbAppData =  JSON.parse(localStorage.getItem('webbAppData'));
  localData[localData.length] = companyData =  JSON.parse(localStorage.getItem('companyData'));
  localData[localData.length] = imgQuality =   JSON.parse(localStorage.getItem('imgQuality'));
  localData[localData.length] = projectData =  JSON.parse(localStorage.getItem('projectData'));
  localData[localData.length] = userDetails =  JSON.parse(localStorage.getItem('userDetails'));
  localData[localData.length] = workFlowData = JSON.parse(localStorage.getItem('workFlowData'));
}

function table(aHref){  
  var numrows = aHref.length;
  var columns = aHref[0].length;    
  var arr = [];

  for (var x = 0; x < numrows-1; x++){    
    var columns = [];
    columns[0] = aHref[x].projectId;
    columns[1] = aHref[x].workFlowId;
    columns[2] = aHref[x].timeStamp;
    columns[3] = aHref[x].message;
    arr[x] = columns;
  } 

  var numrows = arr.length;
  var numcols = arr[0].length;
  var myTable = '<table><tr>'; 

  for (var x = 0; x < numcols; x++){
    myTable += '<th>'+aHref[numrows][x]+'</th>';      
  }

  for (var x = 0; x < numrows; x++){
    myTable += '<tr>';
    for (var y = 0; y < numcols; y++){
     myTable += '<td><a href="#" onclick=workFlow()>'+arr[x][y]+'</a></td>';
    } 
    myTable += '</tr>';   
  }
  myTable +='</table>';

  return myTable;
}

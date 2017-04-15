function btnSwitch(id,state){  
  var classDef = 'btn btn-default';
  if (!state)
    classDef = 'btn btn-default disabled';
  $(id).attr('class', classDef);
}

function setButtons(state){  
    btnSwitch("#btnTransfer", state);          
    $("#btnTransfer").attr('onclick', 'transferImages()');

    btnSwitch("#btnClear", state);          
    if (state){
      $('#btnClear').attr('class', 'btn btn-danger');
      $("#btnClear").attr('onclick', 'clearStorage()');

    }
    else{
      $('#btnClear').attr('class', 'btn btn-danger disabled');
      $("#btnClear").attr('onclick', null); 
    }

    btnSwitch("#btnStore", state);          
    $("#btnStore").attr('onclick', 'storeImage()');    
}
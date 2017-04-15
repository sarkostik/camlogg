function roundDecimal(decNr){
  if (decNr == null)
    decNr = 0;
  else{
    decNr = Math.round(decNr);
  }
  if (decNr == "NaN")
    decNr = 0;
  return decNr;
}

function byteLength(str) {
    var codePoint = null;
    str = String(str);

    var byteLen = 0;
    for (var i = 0, len = str.length; i < len; i++) {
        codePoint = str.charCodeAt(i);
        byteLen += codePoint < (1 << 16) ? 2 :
                   codePoint < (1 << 21) ? 4 :Number.NaN;
    }
    return byteLen;
}

function getLocalStorageMaxSize(error) {
  if (localStorage) {
    var max = 10 * 1024 * 1024,
        i = 64,
        string1024 = '',
        string = '',
        // generate a random key
        testKey = 'size-test-' + Math.random().toString(),
        minimalFound = 0,
        error = error || 25e4;

    // fill a string with 1024 symbols / bytes    
    while (i--) string1024 += 1e16;

    i = max / 1024;

    // fill a string with 'max' amount of symbols / bytes    
    while (i--) string += string1024;

    i = max;

    // binary search implementation
    while (i > 1) {
      try {
        localStorage.setItem(testKey, string.substr(0, i));
        localStorage.removeItem(testKey);

        if (minimalFound < i - error) {
          minimalFound = i;
          i = i * 1.5;
        }
        else break;
      } catch (e) {
        localStorage.removeItem(testKey);
        i = minimalFound + (i - minimalFound) / 2;
      }
    }

    return minimalFound;
  }
}


function getImageSize(img, callback) {
    var $img = $(img);

    var wait = setInterval(function() {
        var w = $img[0].naturalWidth,
            h = $img[0].naturalHeight;
        if (w && h) {
            clearInterval(wait);
            callback.apply(this, [w, h]);
        }
    }, 30);
}

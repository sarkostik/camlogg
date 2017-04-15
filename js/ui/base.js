var progressArr = [];
var statusProgressArr = [];
var fileNames = [];
var imgFiles =[];
var spinners = [];
var imagesNotSent = [];
var scaledBlobs = [];
var imgBlobs = [];
var img64 = [];
var dataImage = [];

var isQuotaFull = false;

var timer = 4000;
var myTotal = 0;
var currentProgress = 0;
var c = 0;
var progressDone = 0;
var transferSize = 0;
var memDivId = 0;
var memHeight = 0;
var memImgId = 0;
var memWidth = 0;
var memWidthWindow = 0;
var numImages = 0;
var savedImages = 0;
var fileSizes = 0;
var b = 0;
var id = 1;
var ffile = 1;

var imgWidth = 1500;
var imgHeight = 1500;

var memBlob;
var myHashedPw;
var swalEmail;

var opts = {
lines: 17 
, length: 0
, width: 11 
, radius: 15
, scale: 0.55
, corners: 1.0
, color: '#000'
, opacity: 0.20
, rotate: 0 
, direction: 1
, speed: 2.2 
, trail: 76 
, fps: 20 
, zIndex: 2e9
, className: 'spinner' 
, top: 'auto'
, left: '300px'
, shadow: false
, hwaccel: false 
, position: 'absolute'
}
var currentRotation = 0;

var tempX = 0;
var tempY = 0;

function getMouseXY(e) {
  if (IE) { 
    tempX = event.clientX + document.body.scrollLeft
    tempY = event.clientY + document.body.scrollTop
  } 
  else {  
    tempX = event.clientX + document.body.scrollLeft
    tempY = event.clientY + document.body.scrollTop
  }  

  if (tempX < 0){tempX = 0}
  if (tempY < 0){tempY = 0}  

	var bgcolorlist=new Array("#0048EA", "#6000EA", "#722600", "#668187", "66ccff", "#FCFF00", "#C27FFF", "#005F15", "#FF00AA", "#F90606", "#FFF", "#000")
	if (tempX > 0 && tempX < 1200){
		changeBackground();
	}

	if(isSpace){
		var xRot = tempX -document.body.scrollLeft- window.innerWidth / 2;
		var yRot = tempY -document.body.scrollTop - window.innerHeight /2;
		document.getElementById("mainDiv").style.webkitTransform ='rotateX('+ yRot/3 + 'deg)' + ' ' + 'rotateY('+ xRot/3 + 'deg)';
	}

}

function changeBackground(){

var bgcolorlist=new Array("#0048EA", "#6000EA", "#722600", "#668187", "66ccff", "#FCFF00", "#C27FFF", "#005F15", "#FF00AA", "#F90606", "white", "lightblue")
	var r = Math.random()
	
	//document.getElementById("shortMenu").style.color=bgcolorlist[Math.floor(r*bgcolorlist.length)];
	var links = document.querySelectorAll('a');

	for (var i = 0; i< links.length; i++){
	links[i].style.color=bgcolorlist[Math.floor(r*bgcolorlist.length)];
	}

	document.getElementById("console1").style.color=bgcolorlist[Math.floor(r*bgcolorlist.length)];
	return true;
}

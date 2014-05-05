//########################	 
  ////////////////////
 /////AJAX STUFF/////
////////////////////
//######################

function myJacks(){

	this.pageName = null;
	this.pageNumber = null;
	this.pageUser = null;
	this.privacy = null;

	var oldthis = this;

	this.pageData={};	
	this.doNotRefresh=false;		
	var oldthis=this;

	

	this.ajaxFunction = function(reqType){
		
		if(reqType != 'refresh')
			reqType == 'element';
		
		if(reqType == 'element')
			this.doNotRefresh=true;		

		//TODO sec.
		if(privacy == 2 && reqType != 'refresh')
	      return false;
	      
	    if(privacy == 1 && (reqType == 'add' || reqType == 'delete' || reqType == 'replace'))
	      return false;
	
	
		if(this.doNotRefresh && reqType == 'refresh')
		{
			setTimeout(function(){oldthis.ajaxFunction('refresh')},100);
			return false;
		}
		
		
		var ajaxRequest;  // The variable that makes Ajax possible!
			
		try{
			// Opera 8.0+, Firefox, Safari
			ajaxRequest = new XMLHttpRequest();
		} catch (e){
			// Internet Explorer Browsers
			try{
				ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
			} catch (e) {
				try{
					ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
				} catch (e){
					// Something went wrong
					alert("Your browser broke!");
					return false;
				}
			}
		}
		
		// Create a function that will receive data sent from the server
		ajaxRequest.onreadystatechange = function(){
			if(ajaxRequest.readyState == 4){		

				this.doNotRefresh=false;
				
				if(ajaxRequest.responseText=="done")
					this.doNotRefresh=true;
					
				
				if( !this.doNotRefresh){
				
					console.log(ajaxRequest.responseText)
				
					var myObject = JSON.parse(ajaxRequest.responseText);
					
					if (myObject != null){
	
						//document.getElementById('console1').style.backgroundColor = myObject.background;
						
						if(myObject[pageName].pageData==undefined){
							oldthis.pageData = myObject[pageName];
							oldthis.pageData.backgroundImageType = 0;
						}
						else{
							oldthis.pageData.text=myObject[pageName].pageData.text;
							oldthis.pageData.backgroundImage=myObject[pageName].pageData.backgroundImage;
							oldthis.pageData.background=myObject[pageName].pageData.background;
							oldthis.pageData.images=myObject[pageName].pageData.images;
							oldthis.pageData.pageNumber=oldthis.pageNumber;
							oldthis.pageData.pageName=oldthis.pageName;
							oldthis.pageData.privacy=oldthis.privacy;
							oldthis.pageData.owner=myObject[pageName].pageData.owner;
							oldthis.pageData.backgroundImageType = myObject[pageName].pageData.backgroundImageType;
							oldthis.pageData.lastId = myObject[pageName].pageData.lastId;

						}
						for(id in oldthis.pageData.images){
							
							var img = document.getElementById(id);
							
							if(img == undefined){
							
								img = document.createElement('img');
								img.id = id;
								img.style.position = 'absolute';
								img.className = 'moveable';
								img.style.cursor='move';
								img.style.opacity=.9;
							
							}
							
							//TODO: optimise this
							
							if(img.src != oldthis.pageData.images[id].url)
								img.src=oldthis.pageData.images[id].url;
							
							img.style.left = oldthis.pageData.images[id].left;
							
							img.style.top = oldthis.pageData.images[id].top;	
							img.style.width = oldthis.pageData.images[id].width;
							img.style.height = oldthis.pageData.images[id].height;	
							
							var z = oldthis.pageData.images[id].z;
							var anglex = oldthis.pageData.images[id].anglex
							var angley = oldthis.pageData.images[id].angley;
							var angler = oldthis.pageData.images[id].angler;
							
							var transform = 'translateZ('+ z + 'px)'+' '+ 'rotateY('+ anglex+'deg)'+ ' ' + 'rotateX('+angley +'deg)'+' '+'rotateZ('+angler+'deg)';
							
							img.style.webkitTransform = transform;
							
							img.setAttribute("data-anglex",oldthis.pageData.images[id].anglex);
							img.setAttribute("data-angley", oldthis.pageData.images[id].angley);
							img.setAttribute("data-angler",oldthis.pageData.images[id].angler);
							img.setAttribute("data-z", oldthis.pageData.images[id].z);
							img.setAttribute("data-imgId", oldthis.pageData.images[id].id);

							
									
							document.getElementById("mainDiv").appendChild(img);	
							//document.getElementById("mainDiv").innerHTML = myObject.images;
						
						}
						
						document.getElementById('console1').value = oldthis.pageData.text;
						document.body.style.backgroundColor = oldthis.pageData.background;
						
						if(oldthis.pageData.backgroundImageType == 0)
							document.body.style.backgroundImage = 'url('+oldthis.pageData.backgroundImage+')';
		
						if(oldthis.pageData.backgroundImageType == 1){
							var bgimg = '-webkit-gradient(' + oldthis.pageData.backgroundImage +')'
							document.body.style.backgroundImage = bgimg;	
						}
		
						if(oldthis.pageData.backgroundImageType == 2){
						
							document.getElementById("div3d").backgroundImage=oldthis.pageData.backgroundImage;
			
						}
						
						console.debug('got responce');			
					}
				}
	
				if (reqType == "refresh"){
					setTimeout(function(){oldthis.ajaxFunction('refresh')},100);
				}
					
				if(reqType == 'text'){
					doNotRefresh = false;
				}
			}
		}
		
		
		

		
		
		if(reqType == "element"){


/* 			images = document.getElementsByTagName('img'); */
/* 			for(var i=0; i<images.length; i++){ */
/* 				selObj = images[i]; */
				imgData = {};
				imgData.pageNumber=oldthis.pageNumber;
				imgData.zIndex = selObj.style.zIndex;	
				imgData.left = selObj.style.left;
				imgData.top = selObj.style.top;	
				imgData.width = selObj.width;
				imgData.height = selObj.height;	
				imgData.anglex = selObj.getAttribute("data-anglex");
				imgData.angley = selObj.getAttribute("data-angley");
				imgData.angler = selObj.getAttribute("data-angler");
				imgData.z = parseFloat(selObj.getAttribute("data-z"));
				imgData.opacity=selObj.style.opacity;
				imgData.reqType = 'element';
				imgData.id = selObj.getAttribute("data-imgId");;
/* 			} */
/* 			var imgDataTxt = JSON.stringify(imgData); */
	
			var queryString ="&pageNumber=" + this.pageNumber +"&reqType=" + 'element';
	 
			for(key in imgData){
				queryString+= "&"+key +"="+imgData[key]; 
			}		
			//ajaxRequest.open("GET", "updatedb.php" + queryString, true);
			var url = "updatedb.php";
			ajaxRequest.open("POST", url, true);
			ajaxRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			ajaxRequest.send(queryString);	
			
				
		
		}
		
		/////////////////////////
		////Background Color////
		///////////////////////
		if(reqType == "background"){
			var background = document.getElementById('background').value;
		if (background=="type color name or hex"){
		  	background = '';
		}
		var queryString = "?background=" + background + "&name=" + table + "&reqType=" + reqType + "&pageNumber=" + this.pageNumber+ "&pageName=" + this.pageName;
		ajaxRequest.open("GET", "updatedb.php" + queryString, true);
		}
		
		/////////////////////////
		////Background Image////
		//////////////////////
		
		if(reqType == "backgroundImage"){
		var backgroundImage = document.getElementById('backgroundImage').value;
	/*
			if (background=="type color name or hex"){
		  background = '';
		}
	*/
		//document.write(backgroundImage);
		var queryString = "?backgroundImage=" + backgroundImage + "&name=" + table + "&reqType=" + reqType + "&pageNumber=" + this.pageNumber+ "&pageName=" + this.pageName;
		ajaxRequest.open("GET", "updatedb.php" + queryString, true);
		}
	
	
		/////////////////////
		////COMMENTS TEXT////
		//////////////////////
		if(reqType == "text"){
		
		var text = document.getElementById('console1').value;
		document.getElementById('console1').value="";
		
		var table = 'text';	
		var queryString = "?text=" + text + "&name=" + table + "&reqType=" + reqType + "&pageNumber=" + this.pageNumber + "&pageName=" + this.pageName;
		ajaxRequest.open("GET", "updatedb.php" + queryString, true);
	
		document.getElementById('console1').blur();
	
	
		}	
		
		/////////////////////
		////REFRESH////
		//////////////////////
		
		if(reqType == "refresh"){	
		
		var queryString = "?name=" + table + "&reqType=" + reqType + "&pageName=" + this.pageName;
		ajaxRequest.open("GET", "updatedb.php" + queryString, true);
	
		}
	
	
		/////////////////////
		////ADD////
		//////////////////////
		if(reqType == "add"){
		
		
			var ScrollTop = document.body.scrollTop;
	 
			if (ScrollTop == 0)
			{
	    		if (window.pageYOffset)
	        		ScrollTop = window.pageYOffset;
	    		else
	        		ScrollTop = (document.body.parentElement) ? document.body.parentElement.scrollTop : 0;
			}
			
			
			var imgUrl = document.getElementById('gifUrlA').value;
			var addNumber = document.getElementById('addNumber').value;
			
			var queryString = "?imgUrl=" + imgUrl + "&ScrollTop=" + ScrollTop + "&addNumber=" + addNumber + "&reqType=" + reqType + "&pageNumber=" + this.pageNumber + "&pageName=" + this.pageName;
			ajaxRequest.open("GET", "updatedb.php" + queryString, true);
		}	
		
		///////////////
		////delete////
		//////////////
		if(reqType == "delete"){
		
		
		var deleteType = "";
		var	len = document.f1.deleteType.length;
	
	
		for (i = 0; i <len; i++) {
		if (document.f1.deleteType[i].checked) {
			deleteType = document.f1.deleteType[i].value;		
		}
		}
		
			//document.write(deleteType);
	
			var lastId = document.getElementById('lastId').value;
			
			var queryString = "?lastId=" + lastId + "&deleteType=" + deleteType + "&reqType=" + reqType + "&pageNumber=" + this.pageNumber+ "&pageName=" + this.pageName;
			ajaxRequest.open("GET", "updatedb.php" + queryString, true);
			
			
			document.f1.deleteType[0].checked=true;
			
		}
		///////////////
		////replace////
		//////////////
		if(reqType == "replace"){
		
		
			var replaceType = "";
			var	len = document.f2.replaceType.length;
			var imgUrl = document.getElementById('gifUrlR').value;
	
			for (i = 0; i <len; i++) {
			if (document.f2.replaceType[i].checked) {
				replaceType = document.f2.replaceType[i].value;		
			}
			}
		
	
			var lastId = document.getElementById('lastId').value;
			
			var queryString = "?lastId=" + lastId + "&imgUrl=" + imgUrl + "&replaceType=" + replaceType + "&reqType=" + reqType + "&pageNumber=" + this.pageNumber+ "&pageName=" + this.pageName;
			
	
			
			ajaxRequest.open("GET", "updatedb.php" + queryString, true);
			
			document.f2.replaceType[0].checked=true;
	
		}
		
		if(reqType != "element"){
			ajaxRequest.send(null);
		}
			
		console.debug(reqType);
		
	}
	
	
	this.fromDomToJson = function(selObj){
	
		this.pageData.images[selObj.id].left = selObj.style.left;
		this.pageData.images[selObj.id].top = selObj.style.top;	
		this.pageData.images[selObj.id].width = selObj.width;
		this.pageData.images[selObj.id].height = selObj.height;	
		this.pageData.images[selObj.id].anglex = selObj.getAttribute("data-anglex");
		this.pageData.images[selObj.id].angley = selObj.getAttribute("data-angley");
		this.pageData.images[selObj.id].angler = selObj.getAttribute("data-angler");
		this.pageData.images[selObj.id].z = selObj.getAttribute("data-z");	

	}
	
	
	this.fromJsonToDom = function(){
	
	
	}
	
	
}
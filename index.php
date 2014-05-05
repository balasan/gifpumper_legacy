<?php
#########################################################
# class06-login.php - Login to protected area
#########################################################

$scriptName = $_SERVER['PHP_SELF'];

# Make sure we display errors to the browser
error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors', 1);

# Get our DB info
require "/home/sybalasa/info.php";

#########################################################
# Initialize a new session or obtain old one if possible
#########################################################
require "info_session.php";

session_name($mySessionName);
session_start();

if ($_POST['logout']){
session_destroy();
$_SESSION["userId"] = 0;
$_SESSION["username"] = "";
$_SESSION["password"] = "";
$_SESSION["logged-in"] = 0;
}

#########################################################
# Go to home page if already logged in
#########################################################
if ($_SESSION["logged-in"] && $_SESSION["userid"]>0)
{
   
}

#########################################################
# Connect to the database.
#########################################################
$connection = mysql_connect($mySqlHostname, $mySqlUsername, $mySqlPassword);
if (!$connection)
    die("Error " . mysql_errno() . " : " . mysql_error());

$mySqlDatabase = 'dwd';

# Select the DB
$db_selected = mysql_select_db($mySqlDatabase, $connection);
if (!$db_selected)
    die("Error " . mysql_errno() . " : " . mysql_error());
    
#########################################################
# Get our firstname value
#########################################################

$usernameValue = $_POST['username'];
$passwordValue = $_POST['password'];


### Status variables ###
$statusMsg = "";    # Gives response back to user (i.e. "Thank you for your ...")
$hasErrors = 0;    # Keeps track of whether there are input errors

#########################################################
# Check if we were bounced here
#########################################################

$errMsg1 = "notloggedin";
if ($_GET["err"]==$errMsg1)
{    $statusMsg = "Please login to access this page.";
}

#########################################################
# Check our inputs and perform any DB actions
#########################################################





if ($_POST['register']){


    $usernameValue = trim($usernameValue);
    $passwordValue = trim($passwordValue);

	if (empty($usernameValue))
    	{    $hasErrors = 1; $noUsername = 1; $statusMsg = "missing username or password";
    }

    if (empty($passwordValue))
    	{    $hasErrors = 1; $noPassword = 1; $statusMsg = "missing username or password";
    }
   
    if (!$hasErrors)
    {    # This is a good submission
        $usernameValueDB = str_replace("'", "''", $usernameValue);
        $passwordValueDB = str_replace("'", "''", $passwordValue);
        
        
     	# check db for existing user
        $SqlStatement = "SELECT * FROM users
            WHERE username='$usernameValueDB'";
        # print $SqlStatement . "\n";
            
        # Run the query on the database through the connection
        $result = mysql_query($SqlStatement,$connection);
        if (!$result)
            die("Error " . mysql_errno() . " : " . mysql_error());

		$okToRegister = true;            
        if ($row = mysql_fetch_assoc($result))
        {    
            # Successful login so set our logged in session parameters
       
            if($row['username']){
            $statusMsg = "username exists, please try a different one";
            $okToRegister = false;
            }
			            
        }
        
            if($okToRegister){
            $SqlStatement = "INSERT INTO users (username, password) VALUES ('$usernameValue', '$passwordValue')";
						
			$result = mysql_query($SqlStatement,$connection);
        	if (!$result)
            	die("Error " . mysql_errno() . " : " . mysql_error());
			else $_POST['login']=true;
            
            }
}
}


if ($_POST['login'])
{    # Someone wants to login

    # Error Checking
    $noPassword = $noUsername = 0;
    $userid = 0;
    $usernameValue = trim($usernameValue);
    $passwordValue = trim($passwordValue);
    
    if (empty($usernameValue))
    {    $hasErrors = 1; $noUsername = 1; $statusMsg = "missing username or password";
    }

    if (empty($passwordValue))
    {    $hasErrors = 1; $noPassword = 1; $statusMsg = "missing username or password";
    }
    
    if (!$hasErrors)
    {    # This is a good submission
        $usernameValueDB = str_replace("'", "''", $usernameValue);
        $passwordValueDB = str_replace("'", "''", $passwordValue);
        
        # Look for this information in the DB
        $SqlStatement = "SELECT * FROM users
            WHERE username='$usernameValueDB' AND password='$passwordValueDB' ";
        # print $SqlStatement . "\n";
            
        # Run the query on the database through the connection
        $result = mysql_query($SqlStatement,$connection);
        if (!$result)
            die("Error " . mysql_errno() . " : " . mysql_error());
            
        if ($row = mysql_fetch_assoc($result))
        {    
            # Successful login so set our logged in session parameters
            $_SESSION["userId"] = $row['id'];
            $_SESSION["username"] = $row['username'];
            $_SESSION["password"] = $row['password'];
			$_SESSION["logged-in"] = 1;

            unset($_SESSION["login-trials"]);
            
            # Always the last thing you do before exiting your script
            mysql_close($connection);

            # Make sure the session data gets written
            session_write_close();
            
        }
        else
        {              
            $statusMsg = "Your login information was incorrect.  Please try again. ".$_SESSION["login-trials"];
        }
    }
}?>
<html class="no-js">
<head>
<!--
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<meta http-equiv="expires" content="0">
<meta http-equiv="cache-control" content="no-cache">
-->

<title>gifpumper</title>

<link rel="stylesheet" href="gifpumper.css" type="text/css" media="screen" charset="utf-8" /> 
<script type="text/javascript" src="../un/modernizr-1.7.min.js"></script>
<script type="text/javascript" src="ajaxion.js"></script>
<script type="text/javascript" src="background.js"></script>


<!--
<meta name="description" content="gifpumper is a communal 3d-image-collage creation platform" />
<meta name="keywords" content="animated gif, collage, collaboration, interaction" />
-->


</head>









<body>

<br><br><br>



<script language="JavaScript" type="text/javascript">
//here is a start of the implimentation of ajax page selection

var url = document.location.href;

var tokens = url.split("/");

//this is our page (change to 3 for actual relese, 4 for beta)
var pageName = tokens[4];

if (pageName == ''){
	pageName = 'doit';
	}

var myJack = new myJacks();

myJack.pageName = pageName;
//myJack.pageNumber = 9;


</script>



<?php

require "/home/sybalasa/info.php";
$errorMsg = null;

#########################################################
# Connect to the database.
#########################################################

$connection = mysql_connect($mySqlHostname, $mySqlUsername, $mySqlPassword);

if (!$connection) die("Error " . mysql_errno() . " : " . mysql_error());

$mySqlDatabase = 'dwd';
# Select the DB
$db_selected = mysql_select_db($mySqlDatabase, $connection);
if (!$db_selected) die("Error " . mysql_errno() . " : " . mysql_error());

$page = null;			 

$tokens = explode("/", $_SERVER['REQUEST_URI']);
//print_r($tokens);

$pageName = array ();

$pageNumber = array ();


// if you have three tokens, you have the table name:
if (count($tokens) > 1) {
	
	for($i=2; $i<count($tokens); $i++){
	
	
	$pageName[] = $tokens[$i];
}	
}
if($pageName[0]=='') 
$pageName[]=doit;



/* echo "<br><br><br><br><br>" . $pageName[0]; */

$tableName = 'nico';

$SqlStatement = "SELECT * FROM pages ORDER BY id DESC";
$result = mysql_query($SqlStatement,$connection);
if (!$result) die("Error " . mysql_errno() . " : " . mysql_error());
while(($rows[] = mysql_fetch_assoc($result)) || array_pop($rows));

$allPages = array ();
$myPages = array ();
$privacy;
$iOwnIt;

foreach($rows as $row):

	$allPages[] = $row['name'];
	if ($_SESSION['logged-in'] && $row['owner']==$_SESSION['userId']){
	$myPages[] =  $row['name'];
	}

foreach($pageName as $page){
		
if($page==$row['name']){
	
//	echo "a";
	$background = $row['background'];
	$backgroundImage = $row['backgroundImage'];
	$pageNumber[] = $row['id'];
	$privacy = $row['privacy'];
	$owner = $row['owner'];
	if ($_SESSION['logged-in'] && $row['owner']==$_SESSION['userId']){
	$iOwnIt = true;
	}
	
//	echo $page;

}	
}
	
	if($_POST['newPage'] == $row['name']){
		$newPage=null;		
		}
		
		
	//echo "making newwww";
	
	
endforeach;
unset($rows);

if($_POST[pageSettings]){

		
		$privacy = $_POST['privacy'];
		
		$SqlStatement = "UPDATE pages SET privacy = $privacy WHERE id = $pageNumber[0]";
		
		$result = mysql_query($SqlStatement,$connection);
        
        if (!$result)
            die("Error " . mysql_errno() . " : " . mysql_error());

}

/* echo "<br><br><br>" . $pageNumber[0]; */

$myPrivacy = $privacy;

if($iOwnIt)
$myPrivacy = 0;



##############################
    ### NEW PAGE! ####
##############################			

$newPage='';
		if($_POST['newPage'] != "enter unique page name")
		{
		
		$newPage = $_POST['newPage'];
		$newPage = trim($newPage);
		$newPage = preg_replace('/\s+/', '_', $newPage); 
		//echo "aaaaaaa";

		}
		


	if($_POST['makeNew'] && $newPage && $_SESSION['logged-in']){
	
	//echo $newPage;
	$userId = (int) $_SESSION['userId'];
	
	echo $userId;
	

	if ($newPage != ""){
	
	$SqlStatement = "INSERT INTO pages (name, background, owner) VALUES ('$newPage', '$background', 
	$userId)";
		$result = mysql_query($SqlStatement,$connection);
    
    if (!$result)
    	die("Error " . mysql_errno() . " : " . mysql_error());
    	
	}

?>
<script language="JavaScript" type="text/javascript">

var dt = new Date();
		dt.setTime(dt.getTime() + 1000);
		while (new Date().getTime() < dt.getTime());

window.location ="<?=$newPage?>";

</script>
<?php } 

##############################
    ### SAVE PAGE AS! ####
##############################			

	if($_POST['save'] && $newPage && $_SESSION['logged-in']){
	
	//echo $newPage;
	$userId = (int) $_SESSION['userId'];
	
	echo $userId;
	

	if ($newPage != ""){
	
	$SqlStatement = "INSERT INTO pages (name, background, owner, backgroundImage) VALUES ('$newPage', '$background', 
	$userId, '$backgroundImage')";
		$result = mysql_query($SqlStatement,$connection);
    
    if (!$result)
    	die("Error " . mysql_errno() . " : " . mysql_error());
    	
	
		$newPageId = mysql_insert_id();
	
	
        //$pageNumber = array();
        //$pageNumber[0] = $_GET['pageNumber'];
		//$tableName = 'nico';
		//$returnString;

			
			$value = (int)$value;
			
			$SqlStatement = "SELECT * FROM $tableName WHERE page = $pageNumber[0]";
			
			# Run the query on the database through the connection
			$result = mysql_query($SqlStatement,$connection);
			if (!$result) die("Error " . mysql_errno() . " : " . mysql_error());
			while(($rows[] = mysql_fetch_assoc($result)) || array_pop($rows));
			
			foreach($rows as $row):
			
			$opacity = $row[opacity];
			if (!$opacity)
			{
				$opacity= 1;
			}
			if($row[width]==null) {
			$width = 0;
			}
			else $width=$row[width];
			
			if($row[height]==null) {
			$height = 0;
			}
			else $height=$row[height];
			
			if($row[anglex]==null){
			$anglex=0;
			}
			else $anglex=$row[anglex];
			
			if($row[angley]==null){
			$angley=0;
			}
			else $angley=$row[angley];
			
			if($row[angler]==null){
			$angler=0;
			}
			else $angler=$row[angler];
			
			$SqlStatement = "INSERT INTO nico (file_loc, top, lft, zindex, page, width, height, opacity, anglex, angley, angler) VALUES ('$row[file_loc]', $row[top], $row[lft], $row[zindex], $newPageId, $width, $height, $opacity, $anglex, $angley, $angler)";
			
			
			//echo $SqlStatement;
						
			$result = mysql_query($SqlStatement,$connection);
			
        	if (!$result)
            	die("Error " . mysql_errno() . " : " . mysql_error());
            
			
			endforeach;
			
			//echo     $statusMsg . "<br>";
			
			unset($rows);
	
	}
	

?>
<script language="JavaScript" type="text/javascript">

var dt = new Date();
		dt.setTime(dt.getTime() + 1000);
		while (new Date().getTime() < dt.getTime());

window.location ="<?=$newPage?>";

</script>
<?php } 



##############################
    ### DELETE PAGE ####
##############################			

	if($_POST['deletePage'] && $iOwnIt){
	
	
	$SqlStatement = "DELETE FROM pages WHERE id = $pageNumber[0]";

	$result = mysql_query($SqlStatement,$connection);
    
    if (!$result)
    	die("Error " . mysql_errno() . " : " . mysql_error());
    	
    $SqlStatement = "DELETE FROM $tableName WHERE page = $pageNumber[0]";

	$result = mysql_query($SqlStatement,$connection);
    
    if (!$result)
    	die("Error " . mysql_errno() . " : " . mysql_error());
    	
   	$SqlStatement = "DELETE FROM text WHERE page = $pageNumber[0]";

	$result = mysql_query($SqlStatement,$connection);
    
    if (!$result)
    	die("Error " . mysql_errno() . " : " . mysql_error());
	

?>
<script language="JavaScript" type="text/javascript">

var dt = new Date();
		dt.setTime(dt.getTime() + 1000);
		while (new Date().getTime() < dt.getTime());
		
window.location ="http://gifpumper.com";
</script>
<?php }

?>

<div id="div3d">
<div id="mainDiv" class="mainDiv">
</div>
</div>


<script language="JavaScript" type="text/javascript">


/////////////////////
////animations
//////////////////

myJack.pageNumber = <?=$pageNumber[0]?>;
myJack.owner = <?=$owner?>;

var privacy = <?=$myPrivacy?>;
myJack.privacy = privacy;

var doNotRefresh = false;


//#######################
//MOVE AND RESIZE INITS##
//#######################

   var orgCursor=null;   // The original Cursor (mouse) Style so we can restore it
   var dragOK=false;     // True if we're allowed to move the element under mouse
   var dragXoffset=0;    // How much we've moved the element on the horozontal
   var dragYoffset=0;    // How much we've moved the element on the verticle

document.body.style.backgroundColor = "<?=$background?>";


	
//#######################
//MOVE and RESIZE FUNCTIONS
//#######################

   var selObj = null;
	var i_width;
	var i_height;
	var zindex;
	
	var clickX;
	var clickY;
	


	var updateTransform = false;


	
   function moveHandler(e){
      
     	myJack.doNotRefresh = true;

      if(privacy == 2)
      return false;
      
      if (e == null) { e = window.event } 
      if (e.button<=1&&dragOK){
         
          if (e.shiftKey) {

			selObj.style.width=e.clientX-clickX-i_width+'px';
			selObj.style.height=e.clientY-clickY-i_height+'px';
			            
        	}
        	
        	else if (e.altKey) {
            	
            	//zindex = Math.round((e.clientX-clickX)/10);

				z = parseInt(selObj.getAttribute("data-z")) + (e.clientY-clickY)/3;
        		anglex = parseInt(selObj.getAttribute("data-anglex"))
        		angley = parseInt(selObj.getAttribute("data-angley"));
				angler = parseInt(selObj.getAttribute("data-angler"));

        		//var skew = 'skew('+anglex+'deg,'+angley+'deg)'+' ' + 'rotate('+angler+'deg)';
           		var transform = 'translateZ(' + z + 'px)'+ ' ' +  'rotateY('+anglex+'deg)'+' '  + 'rotateX('+angley+'deg)'+' ' + 'rotateZ('+angler+'deg)';

        		selObj.style.webkitTransform=transform;
        		selObj.style.MozTransform=transform;
        		
        		updateTransform = true;


        	}
        	
        	else if (isR) {
        	        	
        	    z = parseInt(selObj.getAttribute("data-z"));
        	    anglex = parseInt(selObj.getAttribute("data-anglex"));
        		angley = parseInt(selObj.getAttribute("data-angley"));
        		angler = parseInt(selObj.getAttribute("data-angler"))+(e.clientX-clickX)/3;
        		
        		//var transform = 'skew('+anglex+'deg,'+angley+'deg)'+' ' + 'rotate('+angler+'deg)';
           		var transform = 'translateZ(' + z + 'px)'+ ' ' +  'rotateY('+anglex+'deg)'+' '  + 'rotateX('+angley+'deg)'+' ' + 'rotateZ('+angler+'deg)';
        	        		
        		selObj.style.webkitTransform=transform;
        		selObj.style.MozTransform=transform;
        		
        		updateTransform = true;
        		

        	}
        	
        	else if (isS) {
        	
        	    //document.write(selObj.getAttribute("data-angley"));
				
				z = parseInt(selObj.getAttribute("data-z"));
        		anglex = parseInt(selObj.getAttribute("data-anglex"))+(e.clientX-clickX)/5;
        		angley = parseInt(selObj.getAttribute("data-angley"))+(e.clientY-clickY)/5;
				angler = parseInt(selObj.getAttribute("data-angler"));

        		//var skew = 'skew('+anglex+'deg,'+angley+'deg)'+' ' + 'rotate('+angler+'deg)';
           		var transform = 'translateZ(' + z + 'px)'+ ' ' +  'rotateY('+anglex+'deg)'+' '  + 'rotateX('+angley+'deg)'+' ' + 'rotateZ('+angler+'deg)';


        		selObj.style.webkitTransform=transform;
        		selObj.style.MozTransform=transform;
        		
        		updateTransform = true;

        		
        	}
        	
        	
         else{
         selObj.style.left=e.clientX-dragXoffset+'px';
         selObj.style.top=e.clientY-dragYoffset+'px';    
         }
         return false;
      }
      
      
   }

   function cleanup(e) {
   	   		    
      document.onmousemove=null;
      document.onmouseup=null;
      //document.onmousedown=null;
      
/*
     selObj.style.zIndex = parseInt(zindex)+parseInt(selObj.style.zIndex)+'';
   	  zindex = 0;	
   	  if(parseInt(selObj.style.zIndex) < 0)
  	   selObj.style.zIndex = '0';
*/

        	        	//document.myForm.gifUrl.value =selObj.style.zIndex;


      selObj.style.cursor=orgCursor;
      dragOK=false;
      
      if (updateTransform == true) {
      	       	selObj.setAttribute("data-z",z);
       			selObj.setAttribute("data-anglex",anglex);
	  			selObj.setAttribute("data-angley",angley);
	 			selObj.setAttribute("data-angler",angler);
      }
      
      updateTransform = false;
      
      myJack.ajaxFunction("element");

      
      var IE = document.all?true:false

	  if (!IE) document.captureEvents(Event.MOUSEMOVE)
      	document.onmousemove = getMouseXY;

	  //document.write(selObj.getAttribute("data-angley"));
		
		//anglex=0;
		//angley=0;
      clickX=null;
      clickY=null;
      //doNotRefresh = false;

   }
   

   

   function dragHandler(e){
      

      
      var htype='-moz-grabbing';
      
      
      if (e == null) { e = window.event; htype='move';} 
      
      //document.onmousedown=clickHandler;

      		
      var target = e.target != null ? e.target : e.srcElement;
      selObj=target;
      selObj=document.getElementById(selObj.id);
      
      orgCursor=target.style.cursor;
      
      if (target.className=="vidFrame"||target.className=="moveable"||target.className=="uiOpen") {
      
      target.style.cursor=htype;
         dragOK=true;
         
   		i_height = - parseInt(selObj.height);
  		i_width = -parseInt(selObj.width);
  		
         dragXoffset=e.clientX-parseInt(selObj.style.left);
         dragYoffset=e.clientY-parseInt(selObj.style.top);
         
         clickY=e.clientY;
         clickX=e.clientX ;
      
      	//document.console.console1.value = e.clientX +","+ clickX;
         
         document.onmousemove=moveHandler;
         document.onmouseup=cleanup;

         return false;
      }
   }
	 
	 document.onmousedown=dragHandler;



function commentsEnter(){
doNotRefresh = true;
}

function commentsExit(){
doNotRefresh = false;
}




///////////////////////////
////SHORT CUT COMMANDS
/////////////////////////

var isR = false;
var isS= false;
var isCtrl = false;
var isSpace = false;
var isRealCtrl = false;

document.onkeyup=function(e){
	if(e.which == 16) isCtrl=false;
	if(e.which == 17) isRealCtrl=false;

	if(e.which == 90) isR=false;
	if(e.which == 88) isS=false;
	if(e.which == 32){ 
		//isSpace=false;
		}
	}
	
var typing = false;
//shortcut keys	
document.onkeydown=function(e){

 
	if(!typing){
		if(e.which == 16) isCtrl=true;
		if(e.which == 17) isRealCtrl=true;
		}
		if(e.which ==90) isR=true;
		if(e.which ==88) isS=true;
		if(e.which == 32){
		
			if (!typing)
			e.preventDefault();
			
			if(isSpace==true){
			isSpace=false;
			}
			else isSpace=true;
			
		}
		var menuType;
		
		switch(e.which)
			{
		case 72:
			menuType = "helpMenu";
  			break;
		case 65:
			menuType = "addMenu";
 		 	break;
 		case 82:
			menuType = "replaceMenu";
  			break;
		case 68:
			menuType = "deleteMenu";
 		 	break;
 		case 66:
			menuType = "backgroundMenu";
  			break;
		case 70:
			menuType = "faceMenu";
 		 	break;
 		case 77:
 			menuType = "moreMenu";
 		 	break;
		default:
			menuType = "";
			break;
		}
		
		
		if(isCtrl == true && menuType != "") {	
			
			if(document.getElementById(menuType).className == 'uiOpen') {
				document.getElementById(menuType).className = 'uiClosed';
				createCookie(menuType,"uiClosed",90);
			}
			else {
			
				var elements = document.querySelectorAll('.uiOpen');
				for(var i=0;i<elements.length;i++){
				elements[i].className = 'uiClosed';
				
				}
				
				
				document.getElementById(menuType).className = 'uiOpen';
				document.getElementById(menuType).style.top = 200 + document.body.scrollTop;
				
				
				console.log(document.getElementById(menuType).style.top);
				
				//document.write(document.getElementById(menuType).className);
				createCookie(menuType,"uiOpen",90);
				
				
				var bgcolorlist=new Array("#668187", "66ccff", "#FCFF00", "#C27FFF", "#FF00AA", "#F90606", "palegreen", "aquamarine")
				var r = Math.random()
				document.getElementById(menuType).style.backgroundColor=bgcolorlist[Math.floor(r*bgcolorlist.length)];

			}

			return false;
		}
		
		if(e.which == 88 && isRealCtrl == true) {
	
			document.getElementById('delete').click();
			return false;
	
		}
		
		
		
		
	}

//for clicking on menus
function openMenu(menuType){

/*
	if(document.getElementById(menuType).style.visibility == 'visible') {
				document.getElementById(menuType).style.visibility = 'hidden';
				createCookie(menuType,"hidden",90);
			}
			else {
				document.getElementById(menuType).style.visibility = 'visible';
				createCookie(menuType,"visible",90);
			}
*/
			
			if(document.getElementById(menuType).className == 'uiOpen') {
				document.getElementById(menuType).className = 'uiClosed';
				createCookie(menuType,"uiClosed",90);
			}
			else {
			
					var elements = document.querySelectorAll('.uiOpen');
				for(var i=0;i<elements.length;i++){
				
				elements[i].className = 'uiClosed';
				
				}
				document.getElementById(menuType).className = 'uiOpen';
				//document.write(document.getElementById(menuType).className);
				createCookie(menuType,"uiOpen",90);
				
				var bgcolorlist=new Array("#668187", "66ccff", "#FCFF00", "#C27FFF", "#FF00AA", "#F90606", "palegreen", "aquamarine")
				var r = Math.random()
				document.getElementById(menuType).style.backgroundColor=bgcolorlist[Math.floor(r*bgcolorlist.length)];

			}
				
			

}



function createCookie(name,value,days) {
	if (days) {
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
	}
	else var expires = "";
		document.cookie = name+"="+value+expires+"; path=/";
}

function readCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}

function eraseCookie(name) {
	createCookie(name,"",-1);
}


document.addEventListener("scroll", recenter, false);

function recenter(){

var yCenter = document.body.scrollTop + window.innerHeight / 2;
var xCenter = document.body.scrollLeft + window.innerWidth / 2;

document.getElementById("div3d").style.webkitPerspectiveOrigin = xCenter +" " + yCenter;
document.getElementById("mainDiv").style.webkitTransformOrigin = xCenter +" " + yCenter;
//console.log(xCenter +" " + yCenter);

var elements = document.querySelectorAll('.fixed');
				for(var i=0;i<elements.length;i++){
				
				var scroll = document.body.scrollTop;
				elements[i].style.webkitTransform = 'translateY(' + scroll +'px)';
				console.log(scroll);
				}


}

window.onload=recenter;
</script>





<?php
$isiPad = (bool) strpos($_SERVER['HTTP_USER_AGENT'],'iPad');
$isiPhone = (bool) strpos($_SERVER['HTTP_USER_AGENT'],'iPhone');

if($isiPad || $isiPhone){
?>
<script language="JavaScript" type="text/javascript">

//window.onload = ajaxFunction('refresh');
//document.write('bla');
setTimeout("myJack.ajaxFunction('refresh')",3000);
/* setTimeout("myJack.ajaxFunction('element')",3500); */


</script>
<?php
}
else{
?>
<script language="JavaScript" type="text/javascript">
window.onload = function(){


myJack.ajaxFunction('refresh');
/* myJack.ajaxFunction('element'); */

//setTimeout("ajaxFunction('refresh')",2000);

}

</script>
<?php

}
?>



<!--
/////////////////
////UI FORM/////
////////////////
-->

<!--
////background/////
-->
<div id = 'backgroundMenu' class = 'uiClosed' style=' position: fixed; left:100; top:180;'>
backgroud color:<br>
<input style="width:160px;" type='text' id='background' name='background' onChange='myJack.ajaxFunction("background")' onClick="value=''" value="name, hex or rgb(0,0,255)"  /> <br />
background image:<br>
<input style="width:160px;" type='text' id='backgroundImage' name='backgroundImage' onChange='myJack.ajaxFunction("backgroundImage")' onClick="value=''; typing=true; " onblur="typing=false;" value="enter blank space to clear" maxlength="600"  /><br>
<div>
<a href="http://gradients.glrzad.com" title="select the code inside ( ); for webkit">gradients.glrzad.com (hvr fr tip)</a></div>
<div class="x">
<a href="javascript:openMenu('backgroundMenu');">x</a>
</div>
</div>

<!--
////add/////
-->
<div id = 'addMenu' name='addMenu' class = 'uiClosed' style='position: fixed; left:300; top:200; width:160px;'>
gif url:<br>
<input style="width:160px;" type='text' id='gifUrlA' name='gifUrlA' onClick="value=''" value='http://blahblah.gif' /> <br />
repetitions:<br>
<input style="width:160px;" type='text' id='addNumber' name='addNumber' onClick="value=''" value='1' maxlength=1/> <br />
<br>
<input  style="width:160px;" type='button' class="button" onClick='myJack.ajaxFunction("add")'  id='add' name='addGif' value='add' />
<div class="x" style="position:absolute; right:0; top:0; border: 1px white;">
<a href="javascript:openMenu('addMenu');">x</a>
</div>

</div>

<!--
////replace/////
-->
<div id = 'replaceMenu' name='replaceMenu' class = 'uiClosed' style='position: fixed; left:300; top:360; width:160px;'>
<input style="width:160px;" type='text' id='gifUrlR' onClick="value=''" value='http://blahblah.gif' /> <br />
<form name="f2">
<input type='radio' name='replaceType' value='one' CHECKED />last image
<input type='radio' name='replaceType' value='all'/>all
</form>
<input style="width:160px;" type='button' class="button" onClick='myJack.ajaxFunction("replace")' id='replace' name='replace' value='replace' />
<div class="x" >
<a href="javascript:openMenu('replaceMenu');">x</a>
</div>

</div>

<!--
////delete/////
-->
<div id = 'deleteMenu' name='deleteMenu' class = 'uiClosed' style='position: fixed; left:200; top:80; width:160px;'>
<form name="f1">
<input type='radio'  name='deleteType' value='one' CHECKED />last image
<input type='radio'  name='deleteType' value='all'/>all<br />
</form>
(shortcut: Ctrl X)<br>
<input style="width:160px;" class="button" type='button' onClick='myJack.ajaxFunction("delete")' id='delete' name='delete' value='delete' />
<div class="x">
<a href="javascript:openMenu('deleteMenu');">x</a>
</div>

</div>

<!--
////last id - hidden/////
-->
<input type='hidden' id='lastId' name='lastId' /> 


<!--
////add/////
-->
<div id = 'helpMenu' name= "helpMenu" class = 'uiClosed' style='left:100; top:50;'>
<div style="background-color:white; padding: 10px; font-size:11; ">
Gifpumper is a communal image collage. All changes are seen by everyone <br> 
instantaneously, enabling realtime collaboration between many users.<br><br>
<b><center>Instructions:</center></b>
<br>
<br><b>Move</b> image: click and drag</br>
<br><b>Resize</b>: Shift-click and drag</br>
<br><b>Move forward/back</b>: Alt-click and drag up/down </br>
<br><b>Rotate</b> around the <b>Z</b> axes: hold Z key & click and drag left/right </br>
<br><b>Rotate</b> around the <b>X/Y</b> axes: hold X key & click and drag </br>
<br><b>Delete</b> image: first click on image (drag it to make sure) then Cntrl X </br>
<br><center><b>Page Management</b></br></center>
<br><b>Register</b>: click Login and enter a unique username and password and hit Register</br>
<br><b>Create a New Page</b>: go to More Pages enter new page name and hit New Page</br>
<br><b>Save Current Page</b>: go to to More Pages, enter a new name and hit Save As</br>
<br>set <b>Privacy</b> levels of a page you created in Page Settings</br>
<br><center><b>Tips</b></center></br>
<br>most menus can be accessed by hitting Shift and the first letter of the menu<br>
<br>replace and delete will effect the last image that was clicked/dragged</br>
<br><center>-------------</center></br>
<br>for questions and suggestions please go to the <a href="http://www.facebook.com/pages/gifpumpercom/117231671686998">facebook page</a><br>

</div>
<div class="x">
<a href="javascript:openMenu('helpMenu');">x</a>
</div>

</div>


<!--
////more pages/////
-->
<div id = 'moreMenu' name='moreMenu' class = 'uiClosed' style='left:400; top:100; width:160px;'>

<?php if ($_SESSION['logged-in']){?>
</form>
<?php }
?>
<select style="width:160px;" name="mydropdown" onchange='window.location = "/gifpumper_beta/" + this.value;'>
<option>more pages over here</option>
<?php
foreach($allPages as &$value){
echo <<<END
<option value='$value'>$value</option>
END;
}
?>
</select>
<br>
<?php if ($_SESSION['logged-in']){?>
<select style="width:160px;" name="mydropdown2" onchange='window.location = "/gifpumper_beta/" + this.value;'>
<option>my pages</option>
<?php
foreach($myPages as &$value){
echo <<<END
<option value='$value'>$value</option>
END;
}
?>
</select>
<?php }
?>

<br><br>
create new page:<br>
<?php if ($_SESSION['logged-in']){?>
<form action="" method="POST" enctype="application/x-www-form-urlencoded">
<?php }?>
<input style="width:160px;" type='text' name='newPage' onClick="value=''" value='enter unique page name'/> <br />
<input type='submit'  style="width:78px;" class="button" id='makeNew' name='makeNew' value='new page' /> 

<input style="width:78px;" type='submit' class="button" id='save' name='save' value='save as' /> 
<br>
<?php if (!$_SESSION['logged-in'])
 echo "*you must login to create your own page";?>

<div class="x">
<a href="javascript:openMenu('moreMenu');">x</a>
</div>

</div>

<!--
////page settings/////
-->
<div id = 'pageMenu' name='pageMenu' class = 'uiClosed' style='left:400; top:300;'>
<form action="" method="POST" enctype="application/x-www-form-urlencoded">
Set Page Privacy:<br>
<input type='radio' name='privacy' value='0' <?php if($privacy == 0) echo "CHECKED";?>  />public<br>
<input type='radio' name='privacy' value='1' <?php if($privacy == 1) echo "CHECKED";?>/>only i can add/remove images<br>
<input type='radio' name='privacy' value='2'<?php if($privacy == 2) echo "CHECKED";?> />only i can make changes<br>
<input type='submit' style="width:78px;"  class="button" id='pageSettings' name='pageSettings' value='Save' />
<input type='submit' style="width:78px;"  class="button" id='deletePage' name='deletePage' value='Delete Page' />
</form>
<div class="x">
<a href="javascript:openMenu('pageMenu');">x</a>
</div>

</div>

<!--
////face/////
-->

<!--
<div id = 'faceMenu' class = 'uiClosed' style='left:100; top:300; width:160px;'>



<iframe src="http://www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.facebook.com%2Fpages%2Fgifpumpercom%2F117231671686998&amp;layout=standard&amp;show_faces=true&amp;width=160&amp;action=like&amp;colorscheme=light&amp;height=80" scrolling="yes" frameborder="0" style="border:none; overflow:hidden; width:160px; height:100px;" allowTransparency="true"></iframe> 
<div class="x">
<a href="javascript:openMenu('faceMenu');">x</a>
</div>

</div>
-->
  
<!--
////login/////
-->
<div id = 'loginMenu' name='loginMenu' class = 'uiClosed' style=' left:200; top:150; width:160px;'>
Username:
<br>
<form action="" method="POST" enctype="application/x-www-form-urlencoded">
<input style="width:160px;" type="text" name="username" value="" size="32" maxlength="32">
<br>
Password
<br>
<input style="width:160px;" type="password"  name="password" value="" size="32" maxlength="32">
<br>
<input type="submit" class="button" name="login" value="login"><input type="submit" class="button" name="register" value="register"><input type="submit" class="button" name="logout" value="logout"><br>
<?php
echo $statusMsg.'<br>';
if($_SESSION["logged-in"]==1){
echo "logged in as ".$_SESSION["username"];
}
else echo "not logged in";
?>
<br>
</form>
<div class="x">
<a href="javascript:openMenu('loginMenu');">x</a>
</div>

</div>

<?php $pageAddress = 'gifpumper.com/'.$pageName[0];?>



<!--
<div id = 'comments' class = 'moveable' style='visibility:hidden; position: fixed; left:0; top:200; width:400px; height:300px; padding:10px; z-index:1000;'>
<div id="fb-root"></div><script src="http://connect.facebook.net/en_US/all.js#appId=168994279818694&amp;xfbml=1"></script><fb:comments href="<?=$pageAddress?>" num_posts="2" width="400px" ></fb:comments>
-->

</div>


<?php mysql_close($connection);?>


<!--
<textarea rows="1" cols="150" class="" name="console1" id="console1" style="background-color:white; position:fixed; top:0; left:0;  z-index:1000;" value="<?=$commentText?>" onClick="value=''; commentsEnter();"  onblur="commentsExit(); value='';" onChange='ajaxFunction("text");' wrap="off">

</textarea>
-->

<input name="console1"  id="console1" style="background-color:white; width:100%; position:absolute; top:0; left:0; margin-left:5px; margin-right:5px; z-index:999;" type='text' 
	value="<?=$commentText?>" onClick="value=''; typing=true; commentsEnter();"  onblur="commentsExit(); typing=false; value='';" onChange='myJack.ajaxFunction("text"); ' SIZE='100'/> 
	
	
	
	
<div id="shortMenu" class="moveable" style='position:absolute; left:10; top:25; z-index:999'>
<div style="float:left;"> 
<?php if($myPrivacy != 2){
echo <<<END
<a href="javascript:openMenu('helpMenu');">&#x21E7;+click+drag</a> | 
<a href="javascript:openMenu('helpMenu');">&#x2325; (alt)+click+drag</a> | 
<a href="javascript:openMenu('helpMenu');">Z+click+drag</a> |
<a href="javascript:openMenu('helpMenu');">X+click+drag</a><br> 
END;
}?>

<?php
if($myPrivacy < 2){
echo <<<END
<a href="javascript:openMenu('backgroundMenu');">&#x21E7; Background</a><br>
END;
if($myPrivacy == 0){
echo <<<END
<a href="javascript:openMenu('addMenu');">&#x21E7; Add</a><br>
<a href="javascript:openMenu('deleteMenu');">&#x21E7; Delete</a><br>
END;
}
}?>
<!-- <a href="javascript:openMenu('faceMenu');">&#x21E7; Facebook</a><br> -->
<!-- <a href="javascript:openMenu('comments');">&#x21E7; Comments</a><br> -->
<a href="javascript:openMenu('helpMenu');">&#x21E7; Help</a><br>
</div>
<div style="float:left; margin-left:50px;"> 
<a href="javascript:openMenu('loginMenu');">Login</a>
<?php if($iOwnIt){
echo <<<END
| <a href='javascript:openMenu("pageMenu");'>Page Settings </a>
END;
}?>
 | <a href="javascript:openMenu('moreMenu');">&#x21E7; More Pages</a>
 
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:animate();">Animate</a>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;



  <br>
</div>

</div>

<div class="" style="position:absolute; top:25; right:10; opacity:.8; padding:0;">
<iframe src="http://www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.facebook.com%2Fpages%2Fgifpumpercom%2F117231671686998&amp;layout=button_count&amp;show_faces=false&amp;width=100&amp;action=like&amp;font&amp;colorscheme=light&amp;height=21" scrolling="no" frameborder="0" style="text-align: right; background:none border:none; overflow:hidden; width:80px; height:21px;" allowTransparency="true"id="fblike" ></iframe>
</div>





<script language="JavaScript" type="text/javascript">

//document.getElementById("ui").style.visibility = readCookie('menu');

var IE = document.all?true:false
if (!IE) window.captureEvents(Event.MOUSEMOVE)
window.onmousemove = getMouseXY;


var loggedIn = parseInt(<?= $_SESSION["logged-in"]?>);

if(readCookie('loginMenu') && !loggedIn){
	document.getElementById('loginMenu').className = readCookie('loginMenu');
}


function animate(){

var mainDiv = document.getElementById('mainDiv');
if (mainDiv.className == 'animate')
{
/* mainDiv.className = 'mainDiv'; */
mainDiv.className = 'stopAnimate';
}
else mainDiv.className = 'animate';

}

if(!Modernizr.csstransforms3d)
{
alert('THIS BROWSER DOES NOT SUPPORT 3D TRANSFORMATIONS, PLEASE USE CHROME OR SAFARI TO VIEW GIFPUMPER');
}
else {
var is_chrome = navigator.userAgent.toLowerCase().indexOf('chrome') > -1;

if (is_chrome){
/* alert("NOTE: CHROME CUTS OFF SOME OF THE PERSPECTIVE, IT WILL WORK BUT SAFARI MIGHT LOOK BETTER"); */
}
}

changeBackground();

</script>






<?php
/* echo "<br><br><br>" . $pageName[0]; */
if ($pageName[0]=='finger'){
?>

<script language="JavaScript" type="text/javascript">
document.body.style.cursor='url(http://thedigit.biz/images/hand.png), url(http://thedigit.biz/images/hand.png), auto';
</script>

<div style="position:absolute; top:6300; left:200; background:none;-webkit-transform: translate(0px, 0px)  scale(1) translateZ(10000px);" >
<iframe src="http://player.vimeo.com/video/23622950?title=0&amp;byline=0&amp;portrait=0" width="640" height="360" frameborder="0"></iframe>
<br><br><br><br><br>
<br>
<br>
<br>
<br>
<a href="http://itunes.apple.com/us/app/the-digit/id431581122?mt=8&ls=1#" style="font-size:130">THE DIGIT</a>
<br><br><br>&nbsp;&nbsp;&nbsp;&nbsp;

<a href="http://itunes.apple.com/us/app/the-digit/id431581122?mt=8&ls=1#" style="font-size:30">get the iPohne app at the App Store</a>
<br><br><br>&nbsp;&nbsp;&nbsp;&nbsp;
<a href="http://thedigit.biz" style="font-size:30">see it on the net</a>
<br><br><br><br>
</div>

<!--
<div class='fixed' style="position:absolute; width:200; bottom:50; right:-10; background:none; z-index:1000;-webkit-transform: translate(0px, 0px)  scale(1) translateZ(10000px);">
<iframe src="http://player.vimeo.com/video/23707852?title=0&amp;byline=0&amp;portrait=0&amp;autoplay=1" width="200" height="113" frameborder="0"></iframe>
</div>
-->
<?php } ?>

</body>
</html>


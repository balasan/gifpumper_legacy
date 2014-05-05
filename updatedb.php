<?php
require "/home/sybalasa/info.php";

#########################################################
# Connect to the database.
#########################################################

$connection = mysql_connect($mySqlHostname, $mySqlUsername, $mySqlPassword);

if (!$connection) die("Error " . mysql_errno() . " : " . mysql_error());

	$mySqlDatabase = 'dwd';
	# Select the DB
	$db_selected = mysql_select_db($mySqlDatabase, $connection);

if (!$db_selected) die("Error " . mysql_errno() . " : " . mysql_error());


		
		//my $tmpId;
		$reqType = $_POST['reqType'];
		
		$callback = $_GET['callback'];
		
		$pageData = $_POST['pageData'];	

		
		$tableName = 'nico';

		$pageNumber = (int) $_POST['pageNumber'];        
        
   	if($reqType=="all"){
   	
   		if($pageData && $pageData!="" && $pageData!='{}'){
	    	$SqlStatement = "UPDATE pages SET json='$pageData' WHERE id=$pageNumber";
	      $result = mysql_query($SqlStatement,$connection);		
			
				if (!$result)
	      	die("Error " . mysql_errno() . " : " . mysql_error());
      }
    }
    else{
     	$reqType = $_GET['reqType'];
			$pageNumber = (int) $_GET['pageNumber'];
    }
		
		
		////////////////////
		//adjusting element
		//////////////////////
		
		$postType = $_POST['reqType'];
		
		if($postType == "element"){
		
			$pageNumber = (int) $_POST['pageNumber'];
			$height = $_POST['height'];
			$tmpId=  $_POST['id'];
      $tmpTop = (int) preg_filter('/px/', '', $_POST['top']);
      $tmpLeft = (int) preg_filter('/px/', '', $_POST['left']);
  		$width = $_POST['width'];
  		$zindex = $_POST['zIndex'];
  		$anglex = $_POST['anglex'];
  		$angley = $_POST['angley'];
  		$angler = $_POST['angler'];
  		$table= 'nico';
  		
      $SqlStatement = "UPDATE $table SET anglex='$anglex', angler='$angler', angley='$angley', top='$tmpTop', lft='$tmpLeft', width='$width', height='$height' WHERE id='$tmpId'";
        $result = mysql_query($SqlStatement,$connection);
        echo $SqlStatement;
        
        if (!$result)
            die("Error " . mysql_errno() . " : " . mysql_error());

      }

        
			//////////////////////
			//change background
			/////////////////////
    
			if($reqType == "background") {
        
        $pageNumber = $_GET['pageNumber'];
				$table = pages;
        $background = trim($_GET['background']);
        $name = $_GET['name'];
        
        $SqlStatement = "UPDATE $table SET background='$background' WHERE id=$pageNumber";

				$result = mysql_query($SqlStatement,$connection);
        
       	//echo $SqlStatement;

        
        if (!$result)
            die("Error " . mysql_errno() . " : " . mysql_error());
         }
         
        //////////////////////
        //change background image
        /////////////////////
        
        if($reqType == "backgroundImage") {
        
	        $pageNumber = $_GET['pageNumber'];
					$table = pages;
	        $backgroundImage = trim($_GET['backgroundImage']);
	        $name = $_GET['name'];
	        
	        $SqlStatement = "UPDATE $table SET backgroundImage='$backgroundImage' WHERE id=$pageNumber";
	
					$result = mysql_query($SqlStatement,$connection);
       		//echo $SqlStatement;

        
        if (!$result)
            die("Error " . mysql_errno() . " : " . mysql_error());
         }
        
        /////////////////
        ////comments
        //////////////////
        
    if($reqType == "text") {
        
        $pageNumber = $_GET['pageNumber'];
				$table = 'text';
        $text = trim($_GET['text']);
        $name = $_GET['name'];
        
				if($text != ''){		
		
					$SqlStatement = "INSERT INTO $table (text, page) VALUES ('$text', $pageNumber)";
		
					$result = mysql_query($SqlStatement,$connection);
        
	       	//echo $SqlStatement;

        	if (!$result)
          	 	 die("Error " . mysql_errno() . " : " . mysql_error());
        	}
	   }




##############################
### REPLACE/DELETE IMAGES ####
##############################

if($reqType == "delete" or $reqType == "replace"){


		$id = $_GET['lastId'];
		$delete =  $_GET['deleteType'];
		$replace = $_GET['replaceType'];
		$pageNumber = (int) $_GET['pageNumber'];
		$url = $_GET['imgUrl'];
		$tableName = 'nico';
		

		
					
		if($url == 'http://blahblah.gif')	$url='';
		
		//$pattern = "/.(gif)/"; # A reg exp that looks for familiar file endings
   		//$goodFile = preg_match($pattern,$url);
   		$goodFile = true;
		
		$pattern = "#^http://#";
   		$goodUrl = preg_match($pattern, $url);
  
        //if(!$goodFile) $errorMsg = 'need gif format';
  		
  		if($goodFile){
  				if(!$goodUrl)
  				{
  				$add = "http://";
  				$url = $add.$url;
  			}
  		}
		
		//$pattern = "/.(gif)/"; # A reg exp that looks for familiar file endings
   		//$goodFile = preg_match($pattern,$url);
  
		//$refresh = true;
		

		
		if($replace=='all' && $url && $reqType == "replace") {
		        $SqlStatement = "UPDATE $tableName SET file_loc='$url' WHERE page = $pageNumber";
		        
		        		        //echo $SqlStatement;

		        }
        if($replace=='one' && $url && $id && $id !='ui' && $reqType == "replace") {
		        
		        
		        if(!$goodFile) {
		        
		        $errorMsg = 'need gif format';
		        echo $errorMsg;
				}
		        if($goodFile){
		        
		        $SqlStatement = "UPDATE $tableName SET file_loc='$url' WHERE id='$id'";
				

				}
				}
		if($delete=='all' && $reqType == "delete") {
			        $SqlStatement = "DELETE FROM $tableName WHERE page = $pageNumber";
				}
		if($delete=='one' && $id && $reqType == "delete") {
			        $SqlStatement = "DELETE FROM $tableName WHERE id='$id'";
				}		
        $result = mysql_query($SqlStatement,$connection);
        
        if (!$result)
            die("Error " . mysql_errno() . " : " . mysql_error());
 
			
}

##############################
    ### ADD IMAGES ####
##############################

if($reqType == "add"){

			$ScrollTop = $_GET['ScrollTop'];
			$url = $_GET['imgUrl'];
			$size = (int) $_GET['addNumber'];
			$pageNumber = (int) $_GET['pageNumber'];
			$table = 'nico';

			//echo $url.$size.$table;
			
			
			if($url == 'http://blahblah.gif')
			$url='';

			if($url != ''){
				
			//echo $url;

			
			$pattern = "/.(gif)/"; # A reg exp that looks for familiar file endings
   			//$goodFile = preg_match($pattern,$url);
   			
   			$goodFile = true;
   			
   			//(!preg_match("#^http://www\.[a-z0-9-_.]+\.[a-z]{2,4}$#i",$url)
   			
   			$pattern = "#^http://#";
   			$goodUrl = preg_match($pattern, $url);
  
            if(!$goodFile) $errorMsg = 'need gif format';
  			
  			if($goodFile){
  
  				if(!$goodUrl)
  				{
  				$add = "http://";
  				$url = $add.$url;
  				}
			
			
			//echo $size;
			
			if(!$size){
			$size = 1;
			}
			
			for($i=0; $i<$size; $i++){
			
			$top = rand (0, 600);
			$left = rand (0, 900);
			$zIndex = 50;
			$top = $top + $ScrollTop;
			
			
			$SqlStatement = "INSERT INTO $table (file_loc, top, lft, zindex, page) VALUES ('$url', $top, $left, $zIndex, $pageNumber)";
			
			//echo $SqlStatement;
						
			$result = mysql_query($SqlStatement,$connection);
        	if (!$result)
            	die("Error " . mysql_errno() . " : " . mysql_error());
            
           	}
			}						
			
	}
}





		//////////////
		/////USERS
		/////////////
		
		
		
		$SqlStatement = "SELECT * FROM users ORDER BY id";
		
		$result = mysql_query($SqlStatement,$connection);
		if (!$result) die("Error " . mysql_errno() . " : " . mysql_error());
			while(($rows[] = mysql_fetch_assoc($result)) || array_pop($rows));
			
				foreach($rows as $row):
					
					$users[$row[id]][password]  = $row[password];
					$users[$row[id]][id] = $row[id];
					$users[$row[id]][username] = $row[username];
					
				endforeach;
			unset($rows);					
		
		
		//////////////
		/////REFRESH
		/////////////
		
		if($reqType == "users"){
			if($callback){
        		echo $callback.'('.json_encode($users).');';
        	}
		}
		else if($reqType != "all"){


		$returnJSON = array();
		//$returnJSON[pageData]=array();

		//$returnJSON[pageData][images]=array();
		
		

    $pageName = $_GET['pageName'];
    $tmpPageName = $pageName;
       
        
		if($pageName && $pageName != "all"){

			
			$SqlStatement = "SELECT * FROM pages ORDER BY id DESC";
			$result = mysql_query($SqlStatement,$connection);
			if (!$result) die("Error " . mysql_errno() . " : " . mysql_error());
				while(($rows[] = mysql_fetch_assoc($result)) || array_pop($rows));
				
					$pageNumber = array();
					$pageName = array();		
					
					foreach($rows as $row):
					
						if($tmpPageName == $row['name']){
							$pageNumber[$tmpPageName] = $row['id'];
							$returnJSON[$tmpPageName] = array();
							$returnJSON[$tmpPageName][pageData][owner]=$users[$row[owner]][username];
						}
					

					endforeach;
				unset($rows);
				
			
		 }
		 else if($tmpPageName == "all"){
		 
		 $SqlStatement = "SELECT * FROM pages ORDER BY id DESC";
			$result = mysql_query($SqlStatement,$connection);
			if (!$result) die("Error " . mysql_errno() . " : " . mysql_error());
				while(($rows[] = mysql_fetch_assoc($result)) || array_pop($rows));
				
					$pageNumber = array();
					$pageName = array();
					
					foreach($rows as $row):
					
						$pageName[$row['name']] = $row['name'];
						$pageNumber[$row['name']] = $row['id'];
						$returnJSON[$row['name']] = array();
						$returnJSON[$row['name']][pageData][privacy]=$row['privacy'];
						$returnJSON[$row['name']][pageData][owner]=$users[$row[owner]][username];
						
					

					endforeach;
				unset($rows);
		 
		 
		 }




		///////////////////////////
		//////Get Elements from db
		///////////////////////////
        
        
		//$callbakc = $_GET['callback'];
		
		//echo $callback.'('.$pageName . $pageNumber.')';


		//echo $pageName . $pageNumber;
        

		$tableName = 'nico';
		$returnString;
		$imageCount=0;
		
/* 		print_r ($pageNumber); */

		foreach($pageNumber as $key => $value) {
			
			//$returnJSON[pageData][images][$imageCount]=array();
			
			
			$value = (int)$value;
			
			$SqlStatement = "SELECT * FROM $tableName WHERE page = $value";
			
			# Run the query on the database through the connection
			$result = mysql_query($SqlStatement,$connection);
			if (!$result) die("Error " . mysql_errno() . " : " . mysql_error());
			while(($rows[] = mysql_fetch_assoc($result)) || array_pop($rows));
			
			$imageCount=0;
			
			foreach($rows as $row):
			
				if($row[width]==null || $row[width]==0) {
					$width = 'auto';
				}
				else $width=$row[width]."px";
				
				$returnJSON[$key][pageData][images][$imageCount][width]=$width;
				
				if($row[height]==null || $row[height]==0) {
				$height = 'auto';
				}
				else $height=$row[height]."px";
				
				$returnJSON[$key][pageData][images][$imageCount][height]=$height;
	
				
				if($row[anglex]==null){
				$anglex=0;
				}
				else $anglex=$row[anglex];
				
				$returnJSON[$key][pageData][images][$imageCount][anglex]=$anglex;
	
				
				if($row[angley]==null){
				$angley=0;
				}
				else $angley=$row[angley];
				
				$returnJSON[$key][pageData][images][$imageCount][angley]=$angley;
				
				if($row[angler]==null){
				$angler=0;
				}
				else $angler=$row[angler];
				
				$returnJSON[$key][pageData][images][$imageCount][angler]=$angler;
	
				
				$z;
				if($row[zindex]>=650){
					$z=599;
				}
				else $z = $row[zindex]-50;
				$zInd = $z+500;
				if($zInd<0)
				$zInd = 0;
				
				$returnJSON[$key][pageData][images][$imageCount][z]=$z;
				$returnJSON[$key][pageData][images][$imageCount][id]=$row[id];
				
				$returnJSON[$key][pageData][images][$imageCount][left]=$row[lft]."px";
				$returnJSON[$key][pageData][images][$imageCount][top]=$row[top]."px";
				$returnJSON[$key][pageData][images][$imageCount]['opacity']=.9;

				$returnJSON[$key][pageData][images][$imageCount][url]=$row[file_loc];
				//$returnJSON[pageData][images][$imageCount][id]='img'.$imageCount;
				
				$imageCount++;
							
				//$transform = 'skew('.$anglex.'deg,'. $angley .'deg)'.' ' .'rotateZ('.$angler.'deg)';
				$transform = 'translateZ('.$z.'px)'.' '. 'rotateY('.$anglex.'deg)'. ' '  . 'rotateX('.$angley .'deg)'.' ' .'rotateZ('.$angler.'deg)';
				$mozTransform = 'skewX('.$anglex.'deg)'. ' '  . 'skewY('.$angley .'deg)'.' ' .'rotate('.$angler.'deg)';
				
				
				$returnString = $returnString."<img data-z='$z' data-angler='$angler' data-anglex='$anglex' data-angley='$angley' 
				style='-webkit-transform:$transform; -moz-transform:$mozTransform; position:absolute; left:$row[lft]; top:$row[top]; 
				width:$width; height:$height; opacity: .9; z-index:$zInd ' class='moveable' id='$row[id]' src='$row[file_loc]'>";

			
			endforeach;
			
			$returnJSON[$key][pageData][lastId]=$imageCount;			

			//echo     $statusMsg . "<br>";
			
			unset($rows);
			
			
			//$returnJSON[images] = $returnString;

			#######################
			#######TEXT############
			#######################
			
			
			$SqlStatement = "SELECT * FROM text WHERE page = $value ORDER BY id desc";
			$result = mysql_query($SqlStatement,$connection);
			if (!$result) die("Error " . mysql_errno() . " : " . mysql_error());
			while(($rows[] = mysql_fetch_assoc($result)) || array_pop($rows));
			
			$commentText = 'CLICK HERE TO POST COMMENTS:::: ';
			$count = 0;
			
			foreach($rows as $row):
			
				
/* 				$returnJSON[$key][pageData][text][$count]=$row['text']; */
				
				$commentText = $commentText . " " . "+++++" . " " . $row['text'] ;	
				
				$count = $count +1;
				
			endforeach;
			
			unset($rows);
        			
        			
      $returnJSON[$key][pageData][text] = $commentText;

			#######################
			#######BACKGROUND############
			#######################
        	
        	
      $SqlStatement = "SELECT background FROM pages WHERE id = $value";
			$result = mysql_query($SqlStatement,$connection); 
			if (!$result) die("Error " . mysql_errno() . " : " . mysql_error());
			
			$row = mysql_fetch_assoc($result);
			
			$returnJSON[$key][pageData][background] = $row['background'];
			
			
			$SqlStatement = "SELECT json FROM pages WHERE id = $value";
			$result = mysql_query($SqlStatement,$connection); 
			if (!$result) die("Error " . mysql_errno() . " : " . mysql_error());
			
			$row = mysql_fetch_assoc($result);
			
			$returnJSON[all][$key]=$row['json'];
			        	
/*       echo json_encode($SqlStatement); */
 
        	
        	#######################
			#######BACKGROUND IMAGE############
			#######################
        	
        	
      $SqlStatement = "SELECT backgroundImage FROM pages WHERE id = $value";
			$result = mysql_query($SqlStatement,$connection); 
			if (!$result) die("Error " . mysql_errno() . " : " . mysql_error());
			
			$row = mysql_fetch_assoc($result);
			
			$backgroundImage = $row['backgroundImage'];
			$pattern = "#^http://#";
   			$goodUrl = preg_match($pattern, $backgroundImage);

			if($goodUrl){
			$returnJSON[$key][pageData][backgroundImage] = $backgroundImage;
			$returnJSON[$key][pageData][backgroundImageType] = 0;
			}

			else if($backgroundImage == '')
			{
				$returnJSON[$key][pageData][backgroundImage] = $backgroundImage;
				$returnJSON[$key][pageData][backgroundImageType] = 2;
			}

			else {
				$returnJSON[$key][pageData][backgroundImage] = $backgroundImage;
       	$returnJSON[$key][pageData][backgroundImageType] = 1;
      }
     }
			//$returnJSON[all][$key]=$row['json'];
/* 			$returnJSON[all][$key]=$row['json']; */
    	if($callback){
    		echo $callback.'('.json_encode($returnJSON).');';
       		//echo $callback.'('.json_encode($returnJSON).');';
    		}
     	else{
     		//if ($returnJSON[all] == null)
     			echo json_encode($returnJSON);
     		//else
     		//	echo $returnJSON[all];
			}
        	
        	
     }
    else 
    	echo "done";
    mysql_close($connection);
         
        ?>
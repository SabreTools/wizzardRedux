<?php

/**
 * Manual Parenting Tool for wizzardRedux
 
 * @author emuLOAD
 * @version 0.1
 * @copyright Copyright (c) 2016
*/

?>


<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">

  <title>TITLE</title>
  <meta name="description" content="The Wizard of DATz Parenting Tool">
  <meta name="author" content="emuLOAD">

  <link rel="stylesheet" href="css/styles.css?v=1.0">
  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script> 
  
  <script type="text/javascript">
  //This is where the jQuery magic happens ^_^
  $(function(){
	  
	$.ajaxSetup ({
    // Disable caching of AJAX responses */
    cache: false
	});
	
	$("#tbl_parents_db").load("parenting_list_parents.php", function(responseTxt, statusTxt, xhr){
         //if(statusTxt == "success")
             //alert("External content loaded successfully!");
         if(statusTxt == "error")
				 alert("Error: " + xhr.status + ": " + xhr.statusText);
		 });
	
	$("#parent_filter").keyup(function(){
     $("#tbl_parents_db").load("parenting_list_parents.php?name=" + encodeURIComponent($("#parent_filter").val()), function(responseTxt, statusTxt, xhr){
		 //if(statusTxt == "success")
             //alert("External content loaded successfully!");
         if(statusTxt == "error")
				 alert("Error: " + xhr.status + ": " + xhr.statusText);
		 });
		 $("#set_name_filter").val($("#parent_filter").val());
		 
		 $("#tbl_sets_db").load("parenting_list_sets.php?name=" + encodeURIComponent($("#set_name_filter").val()), function(responseTxt, statusTxt, xhr){
		 //if(statusTxt == "success")
             //alert("External content loaded successfully!");
         if(statusTxt == "error")
				 alert("Error: " + xhr.status + ": " + xhr.statusText);
		 });
		 
		});
	
	$("#set_name_filter").keyup(function(){
     $("#tbl_sets_db").load("parenting_list_sets.php?name=" + encodeURIComponent($("#set_name_filter").val()), function(responseTxt, statusTxt, xhr){
		 //if(statusTxt == "success")
             //alert("External content loaded successfully!");
         if(statusTxt == "error")
				 alert("Error: " + xhr.status + ": " + xhr.statusText);
		 });
		});
	
  }); 
  </script>
</head>

<body>

  <div id="left_panel" style="float: left; overflow: hiddden; width: 50%">
   <div id="left_top_panel">
   <!-- Here we display site navigation links, and form tools for selecting targets -->
     <span>
	<!-- Filtering tool for the below table data -->
     Filter by Family: <input type="text" id="family_filter"><br>
     Filter by Title: <input type="text" id="parent_filter"><br>
   </span>
   <hr>
   </div>

   <span id="tbl_parents_db">
	<!-- tabular representation of the contents of the "parents" table -->
	</span>
  
  </div>
   
  <div id="right_panel" style="overflow:hidden; width: 50%;">
   <!-- Based on the "game" selected from the games menu, show its children, and allow adding more clones -->
   <div id="right_top_panel">
   <!-- Here we display site navigation links, and form tools for selecting targets -->
     <span>
	<!-- Filtering tool for the below table data -->
     Filter by System: <input type="text" id="system_filter"><br>
     Filter by Title: <input type="text" id="set_name_filter"><br>
   </span>
   <hr>
   <span id="tbl_sets_db">
	<!-- tabular representation of the contents of the "parents" table -->
	</span>
	
   </div>

  </div>
    
    
    
  </body>
  </html>
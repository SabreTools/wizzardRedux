

   <script type="text/javascript">
	
  //This is where the jQuery magic happens ^_^
  $(function(){
		
	$("#new_parent_add").click(function(){
		$("#service").load("add_parent_db.php?new=" + encodeURIComponent($("#new_parent_title").val()), function(responseTxt, statusTxt, xhr){
         location.reload(true);
		 if(srviatusTxt == "success")
             //alert("External content loaded successfully!");
			
         if(statusTxt == "error")
             alert("Error: " + xhr.status + ": " + xhr.statusText);
     });
	 
	 
       }); 
  
  
  }); 
  </script>
  
<?php
	
	#TODO: Move the connection away from here, use whatever the main system uses globally
	$link = mysqli_connect('localhost', 'root', '', 'wod');
	if (!$link)
	{
		die('Error: Database link could not be established: ' . mysqli_error($link));
	}


	echo "
	<table>
     <thead>
     <tr>
        <th>id</th>
        <th>Game Family</th>
        <th>Game Title</th>
     </tr>
     </thead>
     <tbody>";
	
	if (!isset($_GET['name']) OR $_GET['name'] == "")
	{
		$filter = "1";
	} else {
	#only list parents as per the filtered name
		$filter = "name LIKE \"%".$_GET['name']."%\"";
	}
	//Get all existing "parent" table entries
	$sql = "SELECT id, name FROM parent WHERE $filter;";
	$res = mysqli_query($link, $sql) OR die(mysqli_error($link));	
	
	if(!$res) 
	{

	} 
	else
	{
		//We have data!
		$found=false;
		while ($row = mysqli_fetch_assoc($res))
		{
			$found = true;
			echo "
	<tr>
     <td>".$row['id']."</td>
     <td>unsupported</td>
     <td>".$row['name']."</td>
	</tr>";
	
		}
		echo "
     </tbody>
    </table> 
   </span>";
   
   echo "<BR>";
   if (!$found)
   {
   		//No result found, want to add a new parent?
		echo "<p>Parent not found. Add new?</p>";
		echo "
		Game Family: <input type=\"text\" id=\"new_parent_family\"><br> 
		Game Title: <input type=\"text\" id=\"new_parent_title\" value=\"".(isset($_GET['name']) ? $_GET['name'] : "")."\"><br>
		<button id=\"new_parent_add\">Add new parent</button>
		";
    }
	}
	
	mysqli_close($link);
?>

<div id="service">
</div>
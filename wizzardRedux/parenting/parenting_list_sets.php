   <script type="text/javascript">
	
  //This is where the jQuery magic happens ^_^
  $(function(){
		
	$('a.clones').click(function() { 
		var id = $(this).attr('id');
		var set_id = id.replace('set_', '');
		var parent_id = $("#spid_" + id.replace('set_', '')).val();
		//alert(id.replace('set_', '')); 
		//alert($("#spid_" + id.replace('set_', '')).val());
		
		$("#service").load("parenting_set_parent.php?set_id=" + set_id + "&dad_id=" + parent_id, function(responseTxt, statusTxt, xhr){

		 if(srviatusTxt == "success")
             //alert("External content loaded successfully!");
			
         if(statusTxt == "error")
             alert("Error: " + xhr.status + ": " + xhr.statusText);
     });
		
		return false; 
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
		<th>Action</th>
        <th>id</th>
        <th>Parent</th>
        <th>Set Title</th>
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
	$sql = "SELECT id, name, parent FROM games WHERE $filter;";
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
			$daddy = getMyParent($row['parent'],$link);
			
			if($daddy == "none") {
				$daddy = "<input type=\"text\" id=\"spid_".$row['id']."\" style=\"border-style:solid; border-color:#000000; width: 4em; border-width: 1px; background-color:white;}\">";
			}
			
			echo "
	<tr>
	 <td><a href=\"#\" class=\"clones\" id=\"set_".$row['id']."\"><<< Add</a></td>
     <td>".$row['id']."</td>
     <td>".$daddy."</td>
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
		echo "<p>No sets found</p>";
    }
	}
	
	function getMyParent($parentID, $link) {
		
	#TODO: Move the connection away from here, use whatever the main system uses globally

		
	$sql = "SELECT name FROM parent WHERE id=$parentID;";
	$res = mysqli_query($link, $sql) OR die(mysqli_error($link));	
	
	if(!$res) 
	{

	} 
	else
	{
		$row = mysqli_fetch_assoc($res);
		if (!$row['name'] || $row['name']=="")
			$name = "none";
		else
			$name = $row['name'];
		
		return $name;
		
	}
	}
	
	mysqli_close($link);
	
?>

<div id="service">
</div>
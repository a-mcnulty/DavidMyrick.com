<?php
	
	session_start();
	include 'includes/secure.php';
	include 'includes/connect.php';
	$picID = $_GET['picID'];
	
	function getOption3($name) {

		$query = "SELECT value FROM options WHERE name = '$name'";

		$result=mysql_query($query);

		if (!$result) {
				die('Could not query:' . mysql_error());
				exit;
		}

		$row = mysql_fetch_array($result);

		return $row['value'];

	}
	
	function getRelated3($picID) {
	
		$sql1 = "SELECT relatedColors FROM pics WHERE id = '$picID'";

		$result1 = mysql_query($sql1);

		if (!$result1) {
				die('Could not query:' . mysql_error());
				exit;
		}
		
		$row1 = mysql_fetch_array($result1);
		
		$related = $row1['relatedColors'];
		$relatedArray = explode(",", $related);
		
		foreach($relatedArray as $relatedID) {
		
			if ($relatedID === "") {
				continue;
			}
			
			$sql2 = "SELECT * FROM pics WHERE id = '$relatedID'";
				
			$result2 = mysql_query($sql2);

			if (!$result2) {
					die('Could not query:' . mysql_error());
					exit;
			}
			
			$row2 = mysql_fetch_array($result2);
			
			echo '<img src="' . getOption3("imagePath") . 'thumbs/' . $row2['img'] . '" class="relatedThumbColor" id="' . $relatedID . '" width="50" style="display: inline-block; margin: 0 3px; cursor: pointer; border: 0px solid #ccc;">';
		
		}
	
	}

?>
<script>

	$(".relatedThumbColor").click(function() {
	
		var parentID = $("#parentID").val();
		var theID = $(this).attr("id");
		
		 var data = {"colorID" : theID, "parentID" : parentID};
		 var data_encoded = JSON.stringify(data);

		 $.ajax({
		   type: "POST",
		   url: "deleteRelatedColors.php",
		   data: {
			 "myContent" : data_encoded
		   },
		   success: function(response) {
			 //reload currentRelated.php to show addition
			$.ajax({
			  url: 'currentColors.php?picID='+parentID,
			  cache: false,
			  success: function(data) {
				$('.currentColors').html(data);
			  }
			});
		   },
		   error: function(){
			 alert('failure');
		   }
		});
		return false;
		
	
	});

</script>

<input type="hidden" name="parentID" id="parentID" value="<?=$picID?>">
<?php echo getRelated3($picID); ?>